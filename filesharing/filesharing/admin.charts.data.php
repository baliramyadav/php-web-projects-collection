<?php
include("config.php");

include("classes/sfs.class.php");
$SFS = new SFS($config);

$config = $SFS->config;

include("functions.php");

if (!$sfs_auth) exit("permission denied");

$firstdayDB = $firstday = null;
$chartsDataUploads = $lineXticks = $lineData = $chartsDataFtypes = array();

$period = isset($_REQUEST["period"]) ? $_REQUEST["period"] : null;
$unit = isset($_REQUEST["unit"]) ? $_REQUEST["unit"] : null;

$add2sql = $lastday = null;

if ($period && $unit) {
	switch ($unit) {
		case 'year':
			if ($period == "all") {
				$add2sql = null;
			} else {
				$add2sql = "and date_format(created, '%Y') = '" . intval($period) . "'";
				$lastday = "$period-12-31";
			}
			break;
		case 'month':
			if ($period) {
				$add2sql = "and date_format(created, '%Y-%m') = " . $SFS->dbquote($period);

				$firstday = "$period-01";
				$lastday = date("Y-m-t",strtotime("$period-01"));

			}
			break;
		case 'halfyear':
			if ($period) {
				$year = preg_replace('/\..*$/','',$period);
	      preg_match_all('/\d$/',$period,$matches);
	      if ($matches[0][0] == 1) {
	      	$firstday = "$year-01-01";
	      	$lastday = "$year-06-30";
					$add2sql = "and created between cast('$firstday' as DATE) and cast('$lastday' as DATE)";
	      } else {
	      	$firstday = "$year-07-01";
	      	$lastday = "$year-12-31";
					$add2sql = "and created between cast('$firstday' as DATE) and cast('$lastday' as DATE)";
	      }
			}
			break;
	}



}

//linecharts data
$sql = "select date_format(created , '%Y-%m-%d' ) as df, count(id) as numf, sum(fsize) as sizeSum from `" . $config->tablePrefix . "files` where uid = 0 $add2sql group by df order by df";

$res = $SFS->dbquery($sql);
while ($row = mysqli_fetch_object($res)) {
	$dbData_numf[strtotime($row->df)] = $row->numf;
	$dbData_sizeSum[strtotime($row->df)] = round($row->sizeSum/1024/1024,2);
	if (!$firstdayDB) $firstdayDB = $row->df;
	$lastdayDB = $row->df;
}

if ($res && (mysqli_num_rows($res) > 1 || $period)) {
	$start_ts = strtotime($firstdayDB);
	$end_ts = strtotime(date("Y-m-d"));

	//unit and price given
	if ($lastday) {
		$end_ts = strtotime($lastday);
		$end_ts_db = strtotime($lastdayDB);
		if (($unit == "year" || $unit == "halfyear") && $end_ts > $end_ts_db) {
			$end_ts = $end_ts_db;
		}
		
	}
	if ($firstday) {
		$start_ts = strtotime($firstday);
	}

	$j = 0;
	//calc days
	$days = ($end_ts - $start_ts)/(60*60*24)+1;
	for ($i=$start_ts;$i<=$end_ts;$i+=60*60*24) {
		if (date("G",$i) == 23) $i += 60*60;
		if (date("G",$i) == 1) $i -= 60*60;
		$j++;
		if (isset($dbData_numf[$i]) && $dbData_numf[$i]) {
			$chartsDataUploads[] = array($j,intval($dbData_numf[$i]));
			$chartsDataSizes[] = array($j,intval($dbData_sizeSum[$i]));
		} else {
			$chartsDataUploads[] = array($j,0);
			$chartsDataSizes[] = array($j,0);

		}
		if ($days < 60) {
			$lineXticks[] = array($j,date("Y-m-d",$i));
		} elseif ($days < 140) {
			$lineXticks[] = array($j,!($j%2)||$j==1?date("Y-m-d",$i):"");
		} elseif ($days < 240) {
			$lineXticks[] = array($j,!($j%3)||$j==1?date("Y-m-d",$i):"");
		} elseif ($days < 700) {
			$lineXticks[] = array($j,!($j%10)||$j==1?date("Y-m-d",$i):"");
		} else {
			$lineXticks[] = array($j,!($j%15)||$j==1?date("Y-m-d",$i):"");
		}
			// $lineXticks[] = array($j,!($j%3)||$j==1?date("Y-m",$i):"");
	}
	$lineData[] = array("label" => "Uploads","data" => $chartsDataUploads);
	$lineData[] = array("label" => "Sizes (MB)","data" => $chartsDataSizes);
}

// mail("mac@homac.at","sfs",print_r($lineData,true) . "\n----\n" . print_r($lineXticks,true));



if (!$period) {
	if ($lineData) {
		//piecharts data
		if ($SFS->dbVersion < 5.7) {
			$sql = "select fname, ftype, if(char_length(substring_index(fname, '.', -1)) > 4,'unknown', substring_index(fname, '.', -1)) as ext, count(id) as numf from `" . $config->tablePrefix . "files` where uid = 0 group by ext";
		} else {
			$sql = "select any_value(fname) as fname, any_value(ftype) as ftype, if(char_length(substring_index(fname, '.', -1)) > 4,'unknown', substring_index(fname, '.', -1)) as ext, count(id) as numf from `" . $config->tablePrefix . "files` where uid = 0 group by ext";
		}
		$res = $SFS->dbquery($sql);
		if ($res && mysqli_num_rows($res) > 1) {
			while ($row = mysqli_fetch_object($res)) {
				$chartsDataFtypes[] = array("label" => $row->ext . " (" . $row->numf . ")", "data" => intval($row->numf));
			}
		}
	} else {
		$chartsDataFtypes = $lineData = $lineXticks = null;
	}
	$jsData = array("lineData" => $lineData,
					"lineXticks" => $lineXticks,
					"pieData" => $chartsDataFtypes);
}

if ($period) {
	$jsData = array("lineData" => $lineData,
					"lineXticks" => $lineXticks);	
}



echo json_encode($jsData);

$SFS->sendLastPHPError();


?>