<?php 
include('../header_emp.php');
include('../utility/common.php');
include(ROOT_PATH.'language/'.$lang_code_global.'/lang_add_employee_request.php');
if(!isset($_SESSION['objLogin'])){
	header("Location: " . WEB_URL . "logout.php");
	die();
}
//
$success = "none";
$l_from = '';
$l_to = '';
$l_description = '';
$l_status = '';
$l_created_date = '';
$title = $_data['t_text_1'];
$button_text = $_data['save_button_text'];
$successful_msg = $_data['text_8'];
$form_url = WEB_URL."e_dashboard/add_leave_request.php";
$id="";
$hdnid="0";

if(isset($_POST['txtLeaveFrom'])){
	$_leave_from_date = $ams_helper->datepickerDateToMySqlDate($_POST['txtLeaveFrom']);
	$_leave_to_date = $ams_helper->datepickerDateToMySqlDate($_POST['txtLeaveTo']);
	if(isset($_POST['hdn']) && $_POST['hdn'] == '0'){
		$sql = "INSERT INTO tbl_employee_leave_request(`employee_id`, `branch_id`, `from`, `to`, `leave_text`) values(".(int)$_SESSION['objLogin']['eid'].",".(int)$_SESSION['objLogin']['branch_id'].",'$_leave_from_date','$_leave_to_date', '".trim($_POST['txtLeaveText'])."')";
		mysqli_query($link,$sql);
		mysqli_close($link);
		$url = WEB_URL . 'e_dashboard/leave_request_list.php?m=add';
		header("Location: $url");
		die();
	
	} else {
		$sql = "UPDATE `tbl_employee_leave_request` SET `from`='$_leave_from_date',`to`='$_leave_to_date',`leave_text`='".trim($_POST['txtLeaveText'])."' WHERE leave_id='".$_GET['id']."'";
		mysqli_query($link,$sql);
		$url = WEB_URL . 'e_dashboard/leave_request_list.php?m=up';
		header("Location: $url");
		die();
	}
	$success = "block";
}

if(isset($_GET['id']) && $_GET['id'] != ''){
	$result = mysqli_query($link,"SELECT * FROM tbl_employee_leave_request where leave_id = ".(int)$_GET['id']);
	while($row = mysqli_fetch_array($result)){
		$l_from = $ams_helper->mySqlToDatePicker($row['from']);
		$l_to = $ams_helper->mySqlToDatePicker($row['to']);
		$l_description = $row['leave_text'];
		$l_status = $row['request_status'];
		$l_created_date = $row['request_date'];
		$hdnid = $_GET['id'];
		$title = $_data['text_1_1'];
		$button_text = $_data['update_button_text'];
		$successful_msg = $_data['text_9'];
		$form_url = WEB_URL . "e_dashboard/add_leave_request.php?id=".$_GET['id'];
	}
	
	//mysqli_close($link);

}	
?>
<!-- Content Header (Page header) -->

<section class="content-header">
  <h1><?php echo $title;?></h1>
  <ol class="breadcrumb">
    <li><a href="<?php echo WEB_URL?>dashboard.php"><i class="fa fa-dashboard"></i><?php echo $_data['home_breadcam'];?></a></li>
    <li class="active"><?php echo $_data['text_2'];?></li>
    <li class="active"><?php echo $_data['text_3'];?></li>
  </ol>
</section>
<!-- Main content -->
<section class="content">
<!-- Full Width boxes (Stat box) -->
<div class="row">
  <div class="col-md-12">
    <div align="right" style="margin-bottom:1%;"></div>
    <div class="box box-success">
      <div class="box-header">
        <h3 class="box-title"><?php echo $_data['text_4'];?></h3>
      </div>
      <form onSubmit="return validateMe();" action="<?php echo $form_url; ?>" method="post" enctype="multipart/form-data">
        <div class="box-body row">
          <div class="form-group col-md-6">
            <label for="txtLeaveFrom"><span style="color:red;">*</span> <?php echo $_data['text_5'];?> :</label>
            <input type="text" name="txtLeaveFrom" value="<?php echo $l_from; ?>" id="txtLeaveFrom" class="form-control datepicker" />
          </div>
		  <div class="form-group col-md-6">
            <label for="txtLeaveTo"><span style="color:red;">*</span> <?php echo $_data['text_leave_to'];?> :</label>
            <input type="text" name="txtLeaveTo" value="<?php echo $l_to; ?>" id="txtLeaveTo" class="form-control datepicker" />
          </div>
          <div class="form-group col-md-12">
            <label for="txtLeaveText"><span style="color:red;">*</span> <?php echo $_data['text_6'];?> :</label>
            <textarea name="txtLeaveText" id="txtLeaveText" class="form-control"><?php echo $l_description;?></textarea>
          </div>
        </div>
		<div class="box-footer">
          <div class="form-group pull-right">
            <button type="submit" name="button" class="btn btn-success"><i class="fa fa-floppy-o"></i> <?php echo $button_text; ?></button>
            <a class="btn btn-warning" href="<?php echo WEB_URL; ?>e_dashboard/leave_request_list.php"><i class="fa fa-reply"></i> <?php echo $_data['back_text'];?></a> </div>
        </div>
        <input type="hidden" value="<?php echo $hdnid; ?>" name="hdn"/>
      </form>
      <!-- /.box-body -->
    </div>
    <!-- /.box -->
  </div>
</div>
<!-- /.row -->
<script type="text/javascript">
function validateMe(){
	if($("#txtLeaveFrom").val() == ''){
		alert("<?php echo $_data['r1']; ?>");
		$("#txtLeaveFrom").focus();
		return false;
	}
	else if($("#txtLeaveTo").val() == ''){
		alert("<?php echo $_data['r2']; ?>");
		$("#txtLeaveTo").focus();
		return false;
	}
	else if($("#txtLeaveText").val() == ''){
		alert("<?php echo $_data['r3']; ?>");
		$("#txtLeaveText").focus();
		return false;
	}
	else{
		return true;
	}
}
</script>
<?php include('../footer.php'); ?>
