<?php 
include('../header_ten.php');
include('../utility/common.php');
include(ROOT_PATH.'language/'.$lang_code_global.'/lang_add_complain.php');
if(!isset($_SESSION['objLogin'])){
	header("Location: " . WEB_URL . "logout.php");
	die();
}
$success = "none";
$c_title = '';
$c_description = '';
$c_date = '';
$c_month = '';
$c_year = '';
$c_userid = '';
$branch_id = '';
$title = $_data['t_text_1'];
$button_text = $_data['save_button_text'];
$successful_msg = $_data['text_8'];
$form_url = WEB_URL . "t_dashboard/addcomplain.php";
$id="";
$hdnid="0";


if(isset($_POST['txtCTitle'])){
	$xmonth = date('m');
	$xyear = date('Y');
	if(isset($_POST['hdn']) && $_POST['hdn'] == '0'){
	$sql = "INSERT INTO tbl_add_complain(c_title,c_description, c_date, c_month,c_year,c_userid,branch_id,complain_by,person_name,person_email,person_contact) values('$_POST[txtCTitle]','$_POST[txtCDescription]','$_POST[txtCDate]',$xmonth,$xyear,'".(int)$_SESSION['objLogin']['rid']."','" . $_SESSION['objLogin']['branch_id'] . "','tenant','" . $_SESSION['objLogin']['r_name'] . "','" . $_SESSION['objLogin']['r_email'] . "','" . $_SESSION['objLogin']['r_contact'] . "')";
	mysqli_query($link,$sql);
	mysqli_close($link);
	$url = WEB_URL . 't_dashboard/complainlist.php?m=add';
	header("Location: $url");
	
}
else{
	$sql = "UPDATE `tbl_add_complain` SET `c_title`='".$_POST['txtCTitle']."',`c_description`='".$_POST['txtCDescription']."',`c_date`='".$_POST['txtCDate']."' WHERE complain_id='".$_GET['id']."'";
	//echo $sql;
	//die();
	mysqli_query($link,$sql);
	$url = WEB_URL . 't_dashboard/complainlist.php?m=up';
	header("Location: $url");
}

$success = "block";
}

if(isset($_GET['id']) && $_GET['id'] != ''){
	$result = mysqli_query($link,"SELECT * FROM tbl_add_complain where complain_id = '" . $_GET['id'] . "'");
	while($row = mysqli_fetch_array($result)){
		
		$c_title = $row['c_title'];
		$c_description = $row['c_description'];
		$c_date = $row['c_date'];
		$hdnid = $_GET['id'];
		$title = $_data['text_1_1'];
		$button_text = $_data['update_button_text'];
		$successful_msg = $_data['text_9'];
		$form_url = WEB_URL . "t_dashboard/addcomplain.php?id=".$_GET['id'];
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
        <div class="box-body">
          <div class="form-group">
            <label for="txtCTitle"><span style="color:red;">*</span> <?php echo $_data['text_5'];?> :</label>
            <input type="text" name="txtCTitle" value="<?php echo $c_title;?>" id="txtCTitle" class="form-control" />
          </div>
          <div class="form-group">
            <label for="txtCDescription"><span style="color:red;">*</span> <?php echo $_data['text_6'];?> :</label>
            <textarea name="txtCDescription" id="txtCDescription" class="form-control"><?php echo $c_description;?></textarea>
          </div>
          <div class="form-group">
            <label for="txtCDate"><span style="color:red;">*</span> <?php echo $_data['text_7'];?> :</label>
            <input type="text" name="txtCDate" value="<?php echo $c_date;?>" id="txtCDate" class="form-control datepicker"/>
          </div>
        </div>
		<div class="box-footer">
          <div class="form-group pull-right">
            <button type="submit" name="button" class="btn btn-success"><i class="fa fa-floppy-o"></i> <?php echo $button_text; ?></button>
            <a class="btn btn-warning" href="<?php echo WEB_URL; ?>t_dashboard/complainlist.php"><i class="fa fa-reply"></i> <?php echo $_data['back_text'];?></a> </div>
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
	if($("#txtCTitle").val() == ''){
		alert("<?php echo $_data['r1']; ?>");
		$("#txtCTitle").focus();
		return false;
	}
	else if($("#txtCDescription").val() == ''){
		alert("<?php echo $_data['r2']; ?>");
		$("#txtCDescription").focus();
		return false;
	}
	else if($("#txtCDate").val() == ''){
		alert("<?php echo $_data['r3']; ?>");
		$("#txtCDate").focus();
		return false;
	}
	else{
		return true;
	}
}
</script>
<?php include('../footer.php'); ?>
