<?php 
include('../header.php');
include(ROOT_PATH.'language/'.$lang_code_global.'/lang_add_meeting.php');
if(!isset($_SESSION['objLogin'])){
	header("Location: " . WEB_URL . "logout.php");
	die();
}
$success = "none";
$floor_no = '';
$title = $_data['text_1'];
$button_text=$_data['save_button_text'];
$successful_msg=$_data['text_15'];
$form_url = WEB_URL . "meeting/addmeeting.php";
$id="";
$hdnid="0";
$meeting_title = '';
$meeting_description = '';
$issue_date = '';
//	
if(isset($_POST['txtIssueDate'])){
	if(isset($_POST['hdn']) && $_POST['hdn'] == '0'){
		$month = date('m');
		$year = date('Y');
		$sql = "INSERT INTO `tbl_meeting`(meeting_title,meeting_description,issue_date,branch_id) values('$_POST[txtMeetingTitle]','$_POST[txtMeetingDesc]','".$ams_helper->datepickerDateToMySqlDate($_POST['txtIssueDate'])."',".(int)$_SESSION['objLogin']['branch_id'].")";
		mysqli_query($link,$sql);
		mysqli_close($link);
		$url = WEB_URL . 'meeting/meetinglist.php?m=add';
		header("Location: $url");
		die();
	} else {
		$sql = "UPDATE `tbl_meeting` SET `meeting_title`='".$_POST['txtMeetingTitle']."',`meeting_description`='".$_POST['txtMeetingDesc']."',`issue_date`='".$ams_helper->datepickerDateToMySqlDate($_POST['txtIssueDate'])."' WHERE meeting_id='".$_GET['id']."'";
		mysqli_query($link,$sql);
		$url = WEB_URL . 'meeting/meetinglist.php?m=up';
		header("Location: $url");
		die();
	}
	$success = "block";
}

if(isset($_GET['id']) && $_GET['id'] != ''){
	$result = mysqli_query($link,"SELECT * FROM tbl_meeting where meeting_id = '" . $_GET['id'] . "'");
	while($row = mysqli_fetch_array($result)){
		$meeting_title = $row['meeting_title'];
		$meeting_description = $row['meeting_description'];
		$issue_date = $ams_helper->mySqlToDatePicker($row['issue_date']);
		
		$hdnid = $_GET['id'];
		$title = $_data['text_16'];
		$button_text=$_data['update_button_text'];
		$successful_msg=$_data['text_17'];
		$form_url = WEB_URL . "meeting/addmeeting.php?id=".$_GET['id'];
	}
}	
?>
<!-- Content Header (Page header) -->

<section class="content-header">
  <h1><?php echo $title;?></h1>
  <ol class="breadcrumb">
    <li><a href="<?php echo WEB_URL?>dashboard.php"><i class="fa fa-dashboard"></i><?php echo $_data['home_breadcam'];?></a></li>
    <li class="active"><?php echo $_data['text_2'];?></li>
    <li class="active"><?php echo $title;?></li>
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
          <div class="form-group col-md-12">
            <label for="txtIssueDate"><span style="color:red;">*</span> <?php echo $_data['text_5'];?> :</label>
            <input type="text" name="txtIssueDate" value="<?php echo $issue_date;?>" id="txtIssueDate" class="form-control datepicker" />
          </div>
		  <div class="form-group col-md-12">
            <label for="txtMeetingTitle"><span style="color:red;">*</span> <?php echo $_data['text_6'];?> :</label>
            <input type="text" name="txtMeetingTitle" value="<?php echo $meeting_title;?>" id="txtMeetingTitle" class="form-control" />
          </div>
		  <div class="form-group col-md-12">
            <label for="txtMeetingDesc"><span style="color:red;">*</span> <?php echo $_data['text_66'];?> :</label>
            <textarea name="txtMeetingDesc" class="form-control" id="txtMeetingDesc"><?php echo $meeting_description; ?></textarea>
          </div>
		</div>
		<div class="box-footer">
          <div class="form-group pull-right">
            <button type="submit" name="button" class="btn btn-success"><i class="fa fa-floppy-o"></i> <?php echo $button_text; ?></button>
            <a class="btn btn-warning" href="<?php echo WEB_URL; ?>meeting/meetinglist.php"><i class="fa fa-reply"></i> <?php echo $_data['back_text'];?></a> </div>
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
$( document ).ready(function() {
	CKEDITOR.replace('txtMeetingDesc',{height: 500});
});
function validateMe(){
	var _contents = CKEDITOR.instances.txtMeetingDesc.getData();
	if($("#txtIssueDate").val() == ''){
		alert("<?php echo $_data['v1']; ?>");
		$("#txtIssueDate").focus();
		return false;
	}
	else if($("#txtMeetingTitle").val() == ''){
		alert("<?php echo $_data['v2']; ?>");
		$("#txtMeetingTitle").focus();
		return false;
	}
	else if(_contents == ''){
		alert("<?php echo $_data['v3']; ?>");
		return false;
	}
	else{
		return true;
	}
}
</script>
<?php include('../footer.php'); ?>
