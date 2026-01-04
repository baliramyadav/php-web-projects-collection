<?php
include("config.php");
include("classes/sfs.class.php");
$SFS = new SFS($config);

$config = $SFS->config;

include("functions.php");

if (!$sfs_auth) exit("permission denied");


$sWhere = null;

/* 
 * Paging
 */
$sLimit = "";
if ( isset( $_GET['iDisplayStart'] ) && $_GET['iDisplayLength'] != '-1' )
{
	$sLimit = "limit ".intval($_GET['iDisplayStart']).", ".
		intval($_GET['iDisplayLength']);
}

//columns for ordering and filtering
$aColumns = array( ' ', 'descr', 'fsize', 'created', 'downloads', ' ' );
$sColumns = array('descr', 'created');

/*
 * Ordering
 */
$sOrder = "";
if ( isset( $_GET['iSortCol_0'] ) )
{
	$sOrder = "ORDER BY  ";
	for ( $i=0 ; $i<intval( $_GET['iSortingCols'] ) ; $i++ )
	{
		if ( $_GET[ 'bSortable_'.intval($_GET['iSortCol_'.$i]) ] == "true" )
		{
			$sOrder .= "`".$aColumns[ intval($_GET['iSortCol_'.$i] ) ]."` ".
			 	// $SFS->dbquote($_GET['sSortDir_'.$i] ) .", ";
			 	mysqli_real_escape_string($SFS->db, $_GET['sSortDir_'.$i] ) .", ";
		}
	}
	
	$sOrder = substr_replace( $sOrder, "", -2 );
	if ( $sOrder == "ORDER BY" )
	{
		$sOrder = "";
	}
}
if (!$sOrder) $sOrder = "order by id desc";

/* 
 * Filtering
 * NOTE this does not match the built-in DataTables filtering which does it
 * word by word on any field. It's possible to do here, but concerned about efficiency
 * on very large tables, and MySQL's regex functionality is very limited
 */
if ( isset($_GET['sSearch']) && $_GET['sSearch'] != "" && strlen($_GET['sSearch']) > 2)
{
	// $sWhere = "and (";
	for ( $i=0 ; $i<count($sColumns) ; $i++ )
	{
		if (trim($sColumns[$i])) {
			$sWhere .= "`".$sColumns[$i]."` LIKE ".$SFS->dbquote("%".$_GET['sSearch']."%")." OR ";
		}
	}
	$sWhere = substr_replace( $sWhere, "", -3 );
	// $sWhere .= ')';
}

$sWhere = "where uid = 0" . ($sWhere ? " and ($sWhere)" : "");


//Totals
$sql = "select count(id) as total from `" . $config->tablePrefix . "files` where uid = 0";
$res = $SFS->dbquery($sql);
$row = mysqli_fetch_object($res);
$total = $row->total;

if ($sWhere) {
	//Filtered Totals w/o limit
	$sql = "select count(id) as total_filtered from `" . $config->tablePrefix . "files` $sWhere";
	$res = $SFS->dbquery($sql);
	$row = mysqli_fetch_object($res);
	$total_filtered = $row->total_filtered;
} else $total_filtered = $total;


$sql = "select *,
	md5(concat(id,'~'," . $SFS->dbquote($config->secretKey) . ",'##',created)) as skey, 
	md5(concat(created,'~',created," . $SFS->dbquote($config->secretKey) . ",'][',id*3)) as fkey, 
	date_add(" . ($config->delOn=="download"?"last_download":"created") . ", interval " . intval($config->delDays) . " day) as accessible_until,
	date_add(created, interval del_days day) as accessible_until_by_user, 
	datediff(date_add(" . ($config->delOn=="download"?"last_download":"created") . ", 
	interval " . intval($config->delDays) . " day),now()) as days_remaining, 
	datediff(date_add(created, interval del_days day),now()) as days_remaining_by_user
	from `" . $config->tablePrefix . "files` $sWhere $sOrder $sLimit ";
$res = $SFS->dbquery($sql);


$output = array(
		"sEcho" => intval($_GET['sEcho']),
		"iTotalRecords" => $total,
		"iTotalDisplayRecords" => $total_filtered,
		"aaData" => array()
);

//through the files
while ($row = mysqli_fetch_object($res)) {
	list($fileKey,$delKey) = $SFS->genFileKeys($row->id);
	if ($row->shortkey) {
		$downloadPage = $config->instUrl . "/" . $row->shortkey;
	} else {
		$downloadPage = $config->baseDownloadUrl . $row->skey . ".html";
	}

	$fileExtension = pathinfo($row->fname, PATHINFO_EXTENSION);

	$downloadLink = $config->baseFilesUrl . $row->fkey . "." . $fileExtension . "?ddl=1";
	$thisRow = array();


	//the icon
	$fileIcon = "fa-file-o";
	switch ($fileExtension) {
		case 'txt':
			$fileIcon = "fa-file-text-o";
			break;
		case 'pdf':
			$fileIcon = "fa-file-pdf-o";
			break;
		case 'doc':
		case 'docx':
		case 'odt':
			$fileIcon = "fa-file-word-o";
			break;
		case 'xls':
		case 'xlsx':
		case 'ods':
			$fileIcon = "fa-file-excel-o";
			break;
		case '7z':
		case 'zip':
		case 'rar':
		case 'tar':
		case 'gz':
			$fileIcon = "fa-file-archive-o";
			break;
		case 'jpg':
		case 'jpeg':
		case 'png':
		case 'gif':
		case 'bmp':
		case 'tif':
		case 'tiff':
		case 'psd':
			$fileIcon = "fa-file-image-o";
			break;
	}


  if ($row->del_days > -1) {
    $row->days_remaining = $row->days_remaining_by_user;
    $row->accessible_until = $row->accessible_until_by_user;
  }
	$additionalFileInfo = null;
	if (!$row->locked && $config->delDays > -1 || $row->del_days > -1) {
		$additionalFileInfo = "accessible until: " . date("Y-m-d",strtotime($row->accessible_until)) . "<br />
			<small>". ($row->days_remaining == 1 ? "1 day remaining" : $row->days_remaining . " days remaining") . '</small>';
	}
	if (!$row->locked && $config->delSettingsByUploader && $row->del_downloads > 0) {
	  $additionalFileInfo .= "<br />Downloads: ".$row->downloads . "/" . $row->del_downloads;
	}

	$additionalFileInfoBlock = null;

	if ($row->locked) {
		$additionalFileInfoBlock = "<div class='alert alert-warning alert-sm mb0 cleanup-info js-hide'>File is locked</div>";
	} else {
		$additionalFileInfoBlock = "<div class='alert alert-info alert-sm mb0 cleanup-info js-hide'>$additionalFileInfo</div>";
	}

	$thisRow[] = $row->id;
	$thisRow[] = "<i class='fa $fileIcon fa-fw'></i> <strong>" . he($row->descr) . "</strong>$additionalFileInfoBlock";
	$thisRow[] = fsize($row->fsize);
	$thisRow[] = $row->created;
	$thisRow[] = $row->downloads;

	if (!$row->locked) {
		$lockIcon = "fa-lock";
		$lockMessage = "protect from autodeleting this file";
		$lockBtnClass = "btn-default";
		$lockUrl = "#?as=files&amp;action=lockFile&amp;fid=" . $row->id;
	} else {
		$lockIcon = "fa-unlock";
		$lockMessage = "enable autodeletion of this file";
		$lockBtnClass = "btn-warning";
		$lockUrl = "#?as=files&amp;action=unlockFile&amp;fid=" . $row->id;
	}

	$thisRow[] = "<div class='btn-group btn-group-xs'>
			<button type='btn' class='btn btn-default js-adm-get-qrcode' title='get QR-Code' data-url='$downloadPage'><i class='fa fa-qrcode fa-fw'></i></button>
			<a class='btn btn-success' title='download file directly' href='$downloadLink'><i class='fa fa-download fa-fw'></i></a>
			<a class='btn btn-primary' title='visit Download-Page' href='$downloadPage' onclick='window.open(this.href); return false;'><i class='fa fa-globe fa-fw'></i></a>
			<a class='btn $lockBtnClass js-btn-lockFile' title='$lockMessage' href='$lockUrl'><i class='fa $lockIcon fa-fw'></i></a>
			<a class='btn btn-danger delFile' title='remove file' href='#?as=files&amp;action=delFile&amp;fid=" . $row->id . "'><i class='fa fa-trash-o fa-fw'></i></a>
		</div>";


	$output['aaData'][] = $thisRow;

}
	
	echo json_encode( $output );

  $SFS->sendLastPHPError();

?>