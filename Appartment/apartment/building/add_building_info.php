<?php 
include('../header.php');
include('../utility/common.php');
include(ROOT_PATH.'language/'.$lang_code_global.'/lang_add_building_info.php');
if(!isset($_SESSION['objLogin'])){
	header("Location: " . WEB_URL . "logout.php");
	die();
}
$success = "none";
$name = '';
$address = '';
$security_guard_mobile = '';
$secrataty_mobile = '';
$moderator_mobile = '';
$building_make_year = '';
$building_image = '';
$b_name = '';
$b_address = '';
$b_phone = '';
$branch_id = '';
$title = $_data['text_1'];
$button_text = $_data['save_button_text'];
$form_url = WEB_URL . "building/add_building_info.php";
$id="";
$hdnid="0";
$image_building = WEB_URL . 'img/no_image.jpg';
$img_track = '';
$building_rules = '';
$rowx_unit = array();
$addinfo = 'none';
if(isset($_POST['txtBName'])){
	$sqlx = "DELETE FROM `tbl_add_building_info` where branch_id =".$_SESSION['objLogin']['branch_id'];
	mysqli_query($link,$sqlx);
	$image_url = uploadImage();
	$sql = "INSERT INTO tbl_add_building_info(name,address, security_guard_mobile, secrataty_mobile,moderator_mobile,building_make_year,b_name,b_address,b_phone,building_image,building_rules,branch_id) values('$_POST[txtBName]','$_POST[txtBAddress]','$_POST[txtBSecurityGuardMobile]','$_POST[txtBSecrataryMobile]','$_POST[txtBModeratorMobile]','$_POST[txtBMakeYear]','$_POST[txtBlName]','$_POST[txtBlAddress]','$_POST[txtBlPhone]','$image_url','".$_POST['txtApartmentRules']."','".$_SESSION['objLogin']['branch_id']."')";
	mysqli_query($link,$sql);
	$addinfo = 'block';
	//mysqli_close($link);
	
}
	$result = mysqli_query($link,"SELECT *,y.y_id,y.xyear FROM tbl_add_building_info bi inner join tbl_add_year_setup y on y.y_id = bi.building_make_year where bi.branch_id = " . (int)$_SESSION['objLogin']['branch_id'] . " order by bi.name");
	if($row = mysqli_fetch_array($result)){
		$name = $row['name'];
		$address = $row['address'];
		$security_guard_mobile = $row['security_guard_mobile'];
		$secrataty_mobile = $row['secrataty_mobile'];
		$moderator_mobile = $row['moderator_mobile'];
		$building_make_year = $row['building_make_year'];
		$b_name = $row['b_name'];
		$b_address = $row['b_address'];
		$b_phone = $row['b_phone'];
		if($row['building_image'] != ''){
			$image_building = WEB_URL . 'img/upload/' . $row['building_image'];
			$img_track = $row['building_image'];
		}
		$building_rules = $row['building_rules'];
	}
	//mysqli_close($link);

//for image upload
function uploadImage(){
	if((!empty($_FILES["uploaded_file"])) && ($_FILES['uploaded_file']['error'] == 0)) {
	  $filename = basename($_FILES['uploaded_file']['name']);
	  $ext = substr($filename, strrpos($filename, '.') + 1);
	  if(($ext == "jpg" && $_FILES["uploaded_file"]["type"] == 'image/jpeg') || ($ext == "png" && $_FILES["uploaded_file"]["type"] == 'image/png') || ($ext == "gif" && $_FILES["uploaded_file"]["type"] == 'image/gif')){   
	  	$temp = explode(".",$_FILES["uploaded_file"]["name"]);
	  	$newfilename = NewGuid() . '.' .end($temp);
		move_uploaded_file($_FILES["uploaded_file"]["tmp_name"], ROOT_PATH . '/img/upload/' . $newfilename);
		return $newfilename;
	  }
	  else{
	  	return '';
	  }
	}
	return $_POST['img_exist'];
}
function NewGuid() { 
    $s = strtoupper(md5(uniqid(rand(),true))); 
    $guidText = 
        substr($s,0,8) . '-' . 
        substr($s,8,4) . '-' . 
        substr($s,12,4). '-' . 
        substr($s,16,4). '-' . 
        substr($s,20); 
    return $guidText;
}	
?>
<!-- Content Header (Page header) -->

<section class="content-header">
  <h1><?php echo $title;?></h1>
  <ol class="breadcrumb">
    <li><a href="<?php echo WEB_URL?>dashboard.php"><i class="fa fa-dashboard"></i><?php echo $_data['home_breadcam'];?></a></li>
    <li class="active"><?php echo $_data['setting'];?></li>
    <li class="active"><?php echo $_data['text_3'];?></li>
  </ol>
</section>
<!-- Main content -->
<section class="content">
<!-- Full Width boxes (Stat box) -->
<div class="row">
  <div class="col-md-12">
    <div id="you" class="alert alert-success alert-dismissable" style="display:<?php echo $addinfo; ?>">
      <button aria-hidden="true" data-dismiss="alert" class="close" type="button"><i class="fa fa-close"></i></button>
      <h4><i class="icon fa fa-check"></i> <?php echo $_data['success_text'];?> !</h4>
      <?php echo $_data['update_text'];?> </div>
    <div align="right" style="margin-bottom:1%;"> </div>
    <div class="box box-success">
      <div class="box-header">
        <h3 style="color:red;font-weight:bold;text-decoration:underline;" class="box-title"><?php echo $_data['text_4'];?></h3>
      </div>
      <form onSubmit="return validateMe();" action="" method="post" enctype="multipart/form-data">
        <div class="box-body row">
          <div class="form-group col-md-12">
            <label for="txtBName"><span style="color:red;">*</span> <?php echo $_data['text_5'];?> :</label>
            <input type="text" name="txtBName" value="<?php echo $name;?>" id="txtBName" class="form-control" />
          </div>
          <div class="form-group col-md-12">
            <label for="txtBAddress"><span style="color:red;">*</span> <?php echo $_data['text_6'];?> :</label>
            <textarea name="txtBAddress" id="txtBAddress" class="form-control"><?php echo $address;?></textarea>
          </div>
          <div class="form-group col-md-6">
            <label for="txtBSecurityGuardMobile"><span style="color:red;">*</span> <?php echo $_data['text_7'];?>:</label>
            <input type="text" name="txtBSecurityGuardMobile" value="<?php echo $security_guard_mobile;?>" id="txtBSecurityGuardMobile" class="form-control" />
          </div>
          <div class="form-group col-md-6">
            <label for="txtBSecrataryMobile"><span style="color:red;">*</span> <?php echo $_data['text_8'];?> :</label>
            <input type="text" name="txtBSecrataryMobile" value="<?php echo $secrataty_mobile;?>" id="txtBSecrataryMobile" class="form-control" />
          </div>
          <div class="form-group col-md-6">
            <label for="txtBModeratorMobile"><span style="color:red;">*</span> <?php echo $_data['text_9'];?> :</label>
            <input type="text" name="txtBModeratorMobile" value="<?php echo $moderator_mobile;?>" id="txtBModeratorMobile" class="form-control" />
          </div>
          <div class="form-group col-md-6">
            <label for="txtBMakeYear"><span style="color:red;">*</span> <?php echo $_data['text_10'];?> :</label>
            <select name="txtBMakeYear" id="txtBMakeYear" class="form-control">
              <option value="">--<?php echo $_data['text_14'];?>--</option>
              <?php 
				  	$rs = mysqli_query($link,"SELECT * FROM tbl_add_year_setup order by y_id ASC");
					while($rows = mysqli_fetch_array($rs)){?>
              <option <?php if($building_make_year == $rows['y_id']){echo 'selected';}?> value="<?php echo $rows['y_id'];?>"><?php echo $rows['xyear'];?></option>
              <?php }mysqli_close($link); ?>
            </select>
          </div>
          <div class="clearfix">&nbsp;</div>
          <div class="form-group box-header"> &nbsp;&nbsp;
            <h3 style="color:red;font-weight:bold;text-decoration:underline;" class="box-title"><?php echo $_data['text_11'];?></h3>
          </div>
          <div class="form-group col-md-12">
            <label for="txtBlName"><span style="color:red;">*</span> <?php echo $_data['text_5'];?> :</label>
            <input type="text" name="txtBlName" value="<?php echo $b_name;?>" id="txtBlName" class="form-control" />
          </div>
          <div class="form-group col-md-12">
            <label for="txtBlAddress"><span style="color:red;">*</span> <?php echo $_data['text_6'];?> :</label>
            <textarea name="txtBlAddress" id="txtBlAddress" class="form-control"><?php echo $b_address;?></textarea>
          </div>
          <div class="form-group col-md-12">
            <label for="txtBlPhone"><span style="color:red;">*</span> <?php echo $_data['text_12'];?> :</label>
            <input type="text" name="txtBlPhone" value="<?php echo $b_phone;?>" id="txtBlPhone" class="form-control" />
          </div>
          <div class="form-group col-md-12">
            <label for="Prsnttxtarea"><?php echo $_data['text_13'];?> :</label>
            <img class="form-control" src="<?php echo $image_building; ?>" style="height:250px;width:200px;" id="output"/>
            <input type="hidden" name="img_exist" value="<?php echo $img_track; ?>" />
          </div>
          <div class="form-group col-md-12"> <span class="btn btn-file btn btn-default"><?php echo $_data['upload_image'];?>
            <input type="file" name="uploaded_file" onchange="loadFile(event)" />
            </span> </div>
          <div class="clearfix">&nbsp;</div>
          <div class="form-group box-header"> &nbsp;&nbsp;
            <h3 style="color:red;font-weight:bold;text-decoration:underline;" class="box-title"><?php echo $_data['text_15'];?></h3>
          </div>
          <div class="form-group col-md-12">
            <label for="txtApartmentRules"><span style="color:red;">*</span> <?php echo $_data['text_16'];?> :</label>
            <textarea name="txtApartmentRules" id="txtApartmentRules" class="form-control"><?php echo $building_rules; ?></textarea>
          </div>
        </div>
        <div class="box-footer">
          <div class="form-group pull-right">
            <button type="submit" name="button" class="btn btn-success"><i class="fa fa-floppy-o"></i> <?php echo $button_text; ?></button>
            <a class="btn btn-warning" href="<?php echo WEB_URL; ?>dashboard.php"><i class="fa fa-reply"></i> <?php echo $_data['back_text'];?></a> </div>
        </div>
      </form>
      <!-- /.box-body -->
    </div>
    <!-- /.box -->
  </div>
</div>
<!-- /.row -->
<script type="text/javascript">
function validateMe(){
	if($("#txtBName").val() == ''){
		alert("<?php echo $_data['r1']; ?>");
		$("#txtBName").focus();
		return false;
	}
	else if($("#txtBAddress").val() == ''){
		alert("<?php echo $_data['r2']; ?>");
		$("#txtBAddress").focus();
		return false;
	}
	else if($("#txtBSecurityGuardMobile").val() == ''){
		alert("<?php echo $_data['r3']; ?>");
		$("#txtBSecurityGuardMobile").focus();
		return false;
	}
	else if($("#txtBSecrataryMobile").val() == ''){
		alert("<?php echo $_data['r4']; ?>");
		$("#txtBSecrataryMobile").focus();
		return false;
	}
	else if($("#txtBModeratorMobile").val() == ''){
		alert("<?php echo $_data['r5']; ?>");
		$("#txtBModeratorMobile").focus();
		return false;
	}
	else if($("#txtBMakeYear").val() == ''){
		alert("<?php echo $_data['r6']; ?>");
		$("#txtBMakeYear").focus();
		return false;
	}
	else if($("#txtBlName").val() == ''){
		alert("<?php echo $_data['r7']; ?>");
		$("#txtBlName").focus();
		return false;
	}
	else if($("#txtBlAddress").val() == ''){
		alert("<?php echo $_data['r8']; ?>");
		$("#txtBlAddress").focus();
		return false;
	}
	else if($("#txtBlPhone").val() == ''){
		alert("<?php echo $_data['r9']; ?>");
		$("#txtBlPhone").focus();
		return false;
	}
	else{
		return true;
	}
}
CKEDITOR.replace( 'txtApartmentRules',{height: 700});
</script>
<?php include('../footer.php'); ?>
