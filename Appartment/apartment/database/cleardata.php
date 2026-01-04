<?php 
include('../header.php');
if(!isset($_SESSION['objLogin'])){
	header("Location: " . WEB_URL . "logout.php");
	die();
}

if(isset($_SESSION['login_type']) && ((int)$_SESSION['login_type'] != 5)){
	header("Location: ".WEB_URL."logout.php");
	die();
}
?>
<?php
include(ROOT_PATH.'language/'.$lang_code_global.'/lang_common.php');
$addinfo = 'none';
$msg = "";
$stop_table_list = array('tblsuper_admin','tbl_add_month_setup','tbl_add_year_setup','tbl_currency','tbl_add_bill_type','tbl_add_utility_bill','tbl_add_member_type','tbl_settings','tbl_max_power','tblbranch');
if(isset($_POST['hdnRemoveData'])){
	//clear all table
	$result = mysqli_query($link,"show tables");
	while($row = mysqli_fetch_assoc($result)){
		$table_name = trim($row['Tables_in_ams_final_test']);
		if(!empty($table_name) && !in_array($table_name, $stop_table_list)){
			mysqli_query($link, "DELETE FROM ".$table_name."");
		}
	}
	$msg = $_data['database_clear_msg'];
	$addinfo = 'block';
	mysqli_close($link);
}
?>
<!-- Content Header (Page header) -->

<section class="content-header">
  <h1> <?php echo $_data['database_clear_dummy_data']; ?> </h1>
  <ol class="breadcrumb">
    <li><a href="<?php echo WEB_URL?>dashboard.php"><i class="fa fa-dashboard"></i><?php echo $_data['home_breadcam']; ?></a></li>
    <li class="active"><?php echo $_data['database_clear_dummy_data']; ?></li>
  </ol>
</section>
<!-- Main content -->
<section class="content">
<!-- Full Width boxes (Stat box) -->
<div class="row">
  <div class="col-xs-12">
    <div id="you" class="alert alert-success alert-dismissable" style="display:<?php echo $addinfo; ?>">
      <button aria-hidden="true" data-dismiss="alert" class="close" type="button"><i class="fa fa-close"></i></button>
      <h4><i class="icon fa fa-check"></i> <?php echo $_data['success'];?> !</h4>
      <?php echo $msg; ?> </div>
    <div class="box box-success">
      <!-- /.box-header -->
      <div class="box-body">
	  
	  <div class="alert alert-warning alert-dismissable">
	  <p style="color:#fff !important;font-size:20px !important;"><i class="icon fa fa-bullhorn"></i> <?php echo $_data['database_clear_dummy_msg']; ?></p>
    	</div>
		
		<div class="col-md-12" align="center">
			<form id="frmDumymData" method="post" enctype="multipart/form-data">
				<input type="hidden" value="1" name="hdnRemoveData" />
				<button class="btn btn-success btn-lg" onclick="_clearDummyData();" type="button" name="btnDummyData"><i class="fa fa-refresh"></i> <?php echo $_data['database_clear_dummy_data_start']; ?></button>
			</form>
		</div>
        
      </div>
      <!-- /.box-body -->
    </div>
    <!-- /.box -->
  </div>
  <!-- /.col -->
</div>
<!-- /.row -->
<script type="text/javascript">
	function _clearDummyData(){
		if(confirm("<?php echo $_data['database_clear_dummy_data_confirm']; ?>")){
			jQuery("#frmDumymData").submit();
		}
	}
</script>
<?php include('../footer.php'); ?>
