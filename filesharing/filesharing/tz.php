<?php
include_once("includes/header.php"); 

$sql = "select now() as d, @@session.time_zone as tz";
$res = $SFS->dbquery($sql);
$row = mysqli_fetch_object($res);
$dbdate =  $row->d;
$dbtz = $row->tz;
$wsdate = date("Y-m-d H:i:s");

?>

<div class='container'>

  <div class='row'>
   <div class='page-header'>
   	<h2>SFS Timezone Helper</h2>
	</div>
     <div class='col-xs-12 col-xs-offset-0 col-sm-6 col-sm-offset-3 col-md-4 col-md-offset-4'>

				<div class='panel panel-info'>
	        <div class='panel-heading'><h3 class='panel-title'>Webserver</h3></div>
	        <div class='panel-body'>
	        	<dl class="dl-horizontal">
	        		<dt>Datetime</dt><dd><?php echo $wsdate; ?></dd>
	        		<dt>Timezone</dt><dd><?php echo date_default_timezone_get(); ?></dd>
	        	</dl>
	        </div>
	        <div class='panel-heading'><h3 class='panel-title'>Database Server</h3></div>
	        <div class='panel-body'>
	        	<dl class="dl-horizontal">
	        		<dt>Datetime</dt><dd><?php echo $dbdate; ?></dd>
	        		<dt>Timezone</dt><dd><?php echo $dbtz ?></dd>
	        	</dl>
	        </div>
	      </div>
  	</div>
  </div>

 <div class='row'>
     <div class='col-xs-12 col-xs-offset-0 col-sm-6 col-sm-offset-3 '>


<?php

if ($dbdate != $wsdate) {
	echo "<div class='alert alert-danger'>It seems there are differences between the output times of your Webserver and the time settings of your database server.</div>";
		$wsUTC = date("P");
		// $tdiffS = (strtotime($dbdate)-strtotime($wsdate));
		// $tdiffH = floor($tdiffS/3600);
		// $tdiffM= abs(floor($tdiffS%3600));
		// // $tdiffHM = floor($tdiff) . ":" . abs($tdiff%60);
		// $tdiffHM = sprintf('%02d:%02d',$tdiffH,$tdiffM);
		// if ($tdiffHM >= 0) $tdiffHM = "+" . $tdiffHM;
		echo '<div class="alert alert-info">Please try to set <code>$config->db_timezoneCorrection</code> to <code>' . $wsUTC . '</code></div>';
} else {
	echo "<div class='alert alert-success'>It seems there are no time differences between your webserver and your database server.</div>";
}
?>
		</div>
  </div>
</div>
<?php
	include("includes/footer.php");
?>