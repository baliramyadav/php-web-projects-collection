<?php 
include('../header.php');
include(ROOT_PATH.'language/'.$lang_code_global.'/lang_notice.php');
if(!isset($_SESSION['objLogin'])){
	header("Location: " . WEB_URL . "logout.php");
	die();
}
////////////////////////////////////////////////////////////////////
$notice_title ='';
$notice_created_date ='';
$notice_status ='';
$notice_description ='';
///////////////////////////////////////////////////////////////////
$button_text = $_data['save_button_text'];
$form_url = WEB_URL . "notice/notice.php";
$hval = 0;

if(isset($_POST['txtNoticeTitle'])){
	if($_POST['hdnSpid'] == '0'){
		//unpublish other
		if(isset($_POST['ddlNoticeStatus']) && $_POST['ddlNoticeStatus']=='1'){
			mysqli_query($link,"UPDATE tbl_notice_board set notice_status=0 WHERE branch_id=".$_SESSION['objLogin']['branch_id']);
		}
		$sql="INSERT INTO `tbl_notice_board`(`notice_title`,`notice_description`,`notice_status`,`branch_id`,`created_date`) VALUES ('$_POST[txtNoticeTitle]','".$_POST['txtNoticeBoard']."','".$_POST['ddlNoticeStatus']."','".$_SESSION['objLogin']['branch_id']."','".$ams_helper->datepickerDateToMySqlDate($_POST['txtNoticeDate'])."')";	
		mysqli_query($link,$sql);
		mysqli_close($link);
		$url = WEB_URL . 'notice/notice.php?m=add';
		header("Location: $url");
	} else {
		//unpublish other
		if(isset($_POST['ddlNoticeStatus']) && $_POST['ddlNoticeStatus']=='1'){
			mysqli_query($link,"UPDATE tbl_notice_board set notice_status=0 WHERE branch_id=".$_SESSION['objLogin']['branch_id']);
		}
		$sql_update="UPDATE `tbl_notice_board` set notice_title = '$_POST[txtNoticeTitle]',notice_description = '".$_POST['txtNoticeBoard']."',notice_status = '".$_POST['ddlNoticeStatus']."',created_date = '".$ams_helper->datepickerDateToMySqlDate($_POST['txtNoticeDate'])."' where notice_id= '" . (int)$_POST['hdnSpid'] . "'";	
		mysqli_query($link,$sql_update);
		mysqli_close($link);
		$url = WEB_URL . 'notice/notice.php?m=up';
		header("Location: $url");
	}
	$success = "block";
}

if(isset($_GET['notice_id']) && $_GET['notice_id'] != ''){
	$result_location = mysqli_query($link,"SELECT * FROM tbl_notice_board where branch_id= '".(int)$_SESSION['objLogin']['branch_id']."' and notice_id=".(int)$_GET['notice_id']);
	if($row = mysqli_fetch_array($result_location)){
		$notice_title = $row['notice_title'];
		$notice_created_date = $ams_helper->mySqlToDatePicker($row['created_date']);
		$notice_status = $row['notice_status'];
		$notice_description = $row['notice_description'];
		//////////////////////////////////////////////////////////////////////
		$button_text = $_data['update_button_text'];
		$form_url = WEB_URL . "notice/notice.php?id=".$_GET['notice_id'];
		$hval = $row['notice_id'];
	}	
}
?>
<!-- Content Header (Page header) -->

<section class="content-header">
  <h1><?php echo $_data['text_1'];?></h1>
  <ol class="breadcrumb">
    <li><a href="<?php echo WEB_URL?>dashboard.php"><i class="fa fa-dashboard"></i> <?php echo $_data['home_breadcam'];?></a></li>
    <li class="active"><?php echo $_data['text_3bb'];?></li>
    <li class="active"><?php echo $_data['text_1'];?></li>
  </ol>
</section>
<!-- Main content -->
<section class="content">
<!-- Full Width boxes (Stat box) -->
<div class="row">
  <div class="col-md-12">
    <div align="right" style="margin-bottom:1%;"> </div>
    <div class="box box-success">
      <div class="box-header">
        <h3 class="box-title"><?php echo $_data['text_2'];?></h3>
      </div>
      <form onSubmit="return validateMe();" action="<?php echo $form_url; ?>" method="post" enctype="multipart/form-data">
        <div class="box-body row">
          <div class="form-group col-md-12">
            <label for="txtNoticeTitle"><span style="color:red;">*</span> <?php echo $_data['text_2a'];?> :</label>
            <input type="text" name="txtNoticeTitle" value="<?php echo $notice_title;?>" id="txtNoticeTitle" class="form-control" />
          </div>
		  <div class="form-group col-md-6">
            <label for="txtNoticeDate"><span style="color:red;">*</span> <?php echo $_data['text_2b'];?> :</label>
            <input type="text" name="txtNoticeDate" value="<?php echo $notice_created_date;?>" id="txtNoticeDate" class="form-control datepicker" />
          </div>
		  <div class="form-group col-md-6">
            <label for="ddlNoticeStatus"><?php echo $_data['text_3a'];?> :</label>
            <select name="ddlNoticeStatus" id="ddlNoticeStatus" class="form-control">
				<option value="1" <?php if($notice_status=='1'){echo 'selected';}?>><?php echo $_data['text_3b'];?></option>
				<option value="0" <?php if($notice_status=='0'){echo 'selected';}?>><?php echo $_data['text_3c'];?></option>
			</select>
          </div>
		  <div class="form-group col-md-12">
            <label for="txtNoticeBoard"><span style="color:red;">*</span> <?php echo $_data['text_3'];?> :</label>
            <textarea name="txtNoticeBoard" id="txtNoticeBoard" class="form-control"><?php echo $notice_description; ?></textarea>
          </div>
          <div class="form-group col-md-12" align="right">
            <input type="submit" name="submit" class="btn btn-success" value="<?php echo $button_text; ?>"/>
			&nbsp;
            <input type="reset" onClick="javascript:window.location.href='<?php echo WEB_URL; ?>notice/notice.php';" name="btnReset" id="btnReset" value="<?php echo $_data['reset'];?>" class="btn btn-warning"/>
          </div>
        </div>
        <input type="hidden" name="hdnSpid" value="<?php echo $hval; ?>"/>
      </form>
      <h4 style="text-align:center; color:red;"><?php echo $_data['text_5'];?></h4>
      <!-- /.box-body -->
<?php
$delinfo = 'none';
$addinfo = 'none';
$msg = "";
if(isset($_GET['delid']) && $_GET['delid'] != '' && $_GET['delid'] > 0){
	$sqlx= "DELETE FROM `tbl_notice_board` WHERE notice_id = ".$_GET['delid'];
	mysqli_query($link,$sqlx); 
	$delinfo = 'block';
}
if(isset($_GET['m']) && $_GET['m'] == 'add'){
	$addinfo = 'block';
	$msg = $_data['text_7'];
}
if(isset($_GET['m']) && $_GET['m'] == 'up'){
	$addinfo = 'block';
	$msg = $_data['text_8'];
}
?>      
      <!-- Main content -->
      <section class="content">
      <!-- Full Width boxes (Stat box) -->
      <div class="row">
        <div class="col-xs-12">
          <div class="alert alert-danger alert-dismissable" style="display:<?php echo $delinfo; ?>">
            <button aria-hidden="true" data-dismiss="alert" class="close" type="button"><i class="fa fa-close"></i></button>
            <h4><i class="icon fa fa-ban"></i> <?php echo $_data['delete_text'];?> !</h4>
            <?php echo $_data['text_9'];?> </div>
          <div class="alert alert-success alert-dismissable" style="display:<?php echo $addinfo; ?>">
            <button aria-hidden="true" data-dismiss="alert" class="close" type="button"><i class="fa fa-close"></i></button>
            <h4><i class="icon fa fa-check"></i> <?php echo $_data['success'];?> !</h4>
            <?php echo $msg; ?> </div>
          <div class="box box-success">
            <div class="box-header">
              <h3 class="box-title"><?php echo $_data['text_4'];?></h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <table class="table sakotable table-bordered table-striped dt-responsive">
                <thead>
                  <tr>
                    <th><?php echo $_data['text_3'];?></th>
					<th><?php echo $_data['text_33'];?></th>
                    <th><?php echo $_data['action_text'];?></th>
                  </tr>
                </thead>
                <tbody>
                  <?php
				$result = mysqli_query($link,"SELECT * FROM tbl_notice_board where branch_id=".(int)$_SESSION['objLogin']['branch_id']);
				while($row = mysqli_fetch_array($result)){?>
                  <tr>
					<td><?php echo $row['notice_title']; ?></td>
					<?php if($row['notice_status']=='1') { ?>
					<td><label class="label label-success ams_label">Published</label></td>
					<?php } else { ?>
					<td><label class="label label-danger ams_label">Disable</label></td>
					<?php } ?>
                    <td><a class="btn btn-warning ams_btn_special" data-toggle="tooltip" href="<?php echo WEB_URL;?>notice/notice.php?notice_id=<?php echo $row['notice_id']; ?>" data-original-title="<?php echo $_data['edit_text'];?>"><i class="fa fa-pencil"></i></a> <a class="btn btn-danger ams_btn_special" data-toggle="tooltip" onclick="deleteNotice(<?php echo $row['notice_id']; ?>);" href="javascript:;" data-original-title="<?php echo $_data['delete_text'];?>"><i class="fa fa-trash-o"></i></a></td>
                  </tr>
                  <?php } mysqli_close($link);$link = NULL; ?>
                </tbody>
              </table>
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->
    </div>
    <!-- /.box -->
  </div>
</div>
<!-- /.row -->
<script type="text/javascript">
	$( document ).ready(function() {
    	CKEDITOR.replace('txtNoticeBoard',{height: 500});
	});
	function validateMe(){
		var _contents = CKEDITOR.instances.txtNoticeBoard.getData();
		if($("#txtNoticeTitle").val() == ''){
			alert("<?php echo $_data['v_1']; ?>");
			$("#txtNoticeTitle").focus();
			return false;
		}
		else if($("#txtNoticeDate").val() == ''){
			alert("<?php echo $_data['v_3']; ?>");
			$("#txtNoticeDate").focus();
			return false;
		}
		else if(_contents == ''){
			alert("<?php echo $_data['v_2']; ?>");
			$("#txtNoticeBoard").focus();
			return false;
		}
		else{
			return true;
		}
	}

  function deleteNotice(Id){
  	var iAnswer = confirm("<?php echo $_data['confirm']; ?>");
	if(iAnswer){
		window.location = '<?php echo WEB_URL;?>notice/notice.php?delid=' + Id;
	}
  }
  </script>

<?php include('../footer.php'); ?>
