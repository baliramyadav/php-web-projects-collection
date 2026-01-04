<?php 
include('../header.php');
include(ROOT_PATH.'language/'.$lang_code_global.'/lang_utility_bill_setup.php');
if(!isset($_SESSION['objLogin'])){
	header("Location: " . WEB_URL . "logout.php");
	die();
}

if(isset($_SESSION['login_type']) && ((int)$_SESSION['login_type'] != 5)){
	header("Location: ".WEB_URL."logout.php");
	die();
}

$gas_bill ='0.00';
$security_bill = '0.00';
$station_logo ='';
$button_text = $_data['update_button_text'];
$form_url = WEB_URL . "setting/utility_bill_setup.php";
$hval = 0;
$station_logo = WEB_URL . 'img/no_image.jpg';
$img_track = '';
$addinfo = 'none';
$msg = "";

if(isset($_POST['txtGasBill'])){
	mysqli_query($link,"DELETE FROM `tbl_add_utility_bill` WHERE branch_id = ".(int)$_SESSION['objLogin']['branch_id']); 
	$sql="INSERT INTO `tbl_add_utility_bill`(`branch_id`,`gas_bill`,`security_bill`) VALUES (".(int)$_SESSION['objLogin']['branch_id'].",'$_POST[txtGasBill]','$_POST[txtSecurityBill]')";
	mysqli_query($link,$sql);
	mysqli_close($link);
	$url = WEB_URL . 'setting/utility_bill_setup.php?m=up';
	header("Location: $url");
	$success = "block";
}

if(isset($_GET['m']) && $_GET['m'] == 'up'){
	$addinfo = 'block';
	$msg = $_data['text_6'];
}
//
$result_location = mysqli_query($link,"SELECT * FROM tbl_add_utility_bill where branch_id= '".(int)$_SESSION['objLogin']['branch_id']."'");
if($row = mysqli_fetch_array($result_location)){
	$gas_bill = $row['gas_bill'];
	$security_bill = $row['security_bill'];
}
?>
<!-- Content Header (Page header) -->

<section class="content-header">
  <h1><?php echo $_data['text_1'];?></h1>
  <ol class="breadcrumb">
    <li><a href="<?php echo WEB_URL?>dashboard.php"><i class="fa fa-dashboard"></i><?php echo $_data['home_breadcam'];?></a></li>
    <li class="active"><?php echo $_data['setting'];?></li>
    <li class="active"><?php echo $_data['text_1'];?></li>
  </ol>
</section>
<!-- Main content -->
<section class="content">
<!-- Full Width boxes (Stat box) -->
<div class="row">
  <div class="col-md-12">
  
  <div class="alert alert-success alert-dismissable" style="display:<?php echo $addinfo; ?>">
            <button aria-hidden="true" data-dismiss="alert" class="close" type="button"><i class="fa fa-close"></i></button>
            <h4><i class="icon fa fa-check"></i> <?php echo $_data['success'];?> !</h4>
            <?php echo $msg; ?> </div>
  
  
    <div align="right" style="margin-bottom:1%;"> </div>
    <div class="box box-success">
      <div class="box-header">
        <h3 class="box-title"><?php echo $_data['text_2'];?></h3>
      </div>
      <form onSubmit="return validateMe();" action="<?php echo $form_url; ?>" method="post" enctype="multipart/form-data">
        <div class="box-body">
          <div class="form-group">
            <label for="txtGasBill"><?php echo $_data['text_3'];?> :</label>
            <div class="input-group">
              <input type="text" name="txtGasBill" value="<?php echo $gas_bill;?>" id="txtGasBill" class="form-control" />
              <div class="input-group-addon"><?php echo CURRENCY;?></div>
            </div>
          </div>
          <div class="form-group">
            <label for="txtSecurityBill"><?php echo $_data['text_4'];?> :</label>
            <div class="input-group">
              <input type="text" name="txtSecurityBill" value="<?php echo $security_bill;?>" id="txtSecurityBill" class="form-control" />
              <div class="input-group-addon"><?php echo CURRENCY;?></div>
            </div>
          </div>
          <div class="form-group pull-right">
            <input type="submit" name="submit" class="btn btn-success" value="<?php echo $button_text; ?>"/>
          </div>
        </div>
      </form>
    </div>
  </div>
</div>
<!-- /.row -->
<?php include('../footer.php'); ?>
