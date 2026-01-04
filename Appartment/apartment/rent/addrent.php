<?php 
include('../header.php');
include('../utility/common.php');
include(ROOT_PATH.'language/'.$lang_code_global.'/lang_add_rented.php');
if(!isset($_SESSION['objLogin'])){
	header("Location: " . WEB_URL . "logout.php");
	die();
}
$success = "none";
$r_name = '';
$r_email = '';
$r_contact = '';
$r_address = '';
$r_nid = '';
$r_floor_no = 0;
$r_unit_no = 0;
$r_advance = '';
$r_rent_pm = '';
$r_date = '';
$r_month = '';
$r_year = '';
$r_password = '';
$r_status = '1';
$branch_id = '';
$title = $_data['add_new_renter'];
$button_text = $_data['save_button_text'];
$successful_msg = $_data['added_renter_successfully'];
$form_url = WEB_URL . "rent/addrent.php";
$id="";
$hdnid="0";
$image_rnt = WEB_URL . 'img/no_image.jpg';
$img_track = '';

if(isset($_POST['txtRName'])){
	if(isset($_POST['hdn']) && $_POST['hdn'] == '0'){
		$r_password = $converter->encode($_POST['txtPassword']);
		$image_url = uploadImage();
		$sql = "INSERT INTO tbl_add_rent(r_name,r_email,r_contact,r_address,r_nid,r_floor_no,r_unit_no,r_advance,r_rent_pm,r_date,r_month,r_year,r_password,r_status,image,branch_id) values('$_POST[txtRName]','$_POST[txtREmail]','$_POST[txtRContact]','$_POST[txtRAddress]','$_POST[txtRentedNID]','$_POST[ddlFloorNo]','$_POST[ddlUnitNo]','$_POST[txtRAdvance]','$_POST[txtRentPerMonth]','$_POST[txtRDate]','$_POST[ddlMonth]','$_POST[ddlYear]','$r_password','$_POST[chkRStaus]','$image_url','" . $_SESSION['objLogin']['branch_id'] . "')";
		mysqli_query($link,$sql);
		//update unit status
		$sqlx = "UPDATE `tbl_add_unit` set status = 1 where floor_no = '".(int)$_POST['ddlFloorNo']."' and uid = '".(int)$_POST['ddlUnitNo']."'";
		mysqli_query($link,$sqlx);
		////////////////////////
		mysqli_close($link);
		$url = WEB_URL . 'rent/rentlist.php?m=add';
		header("Location: $url");
		
	}
	else{
		$image_url = uploadImage();
		if($image_url == ''){
			$image_url = $_POST['img_exist'];
		}
		$sql = "UPDATE `tbl_add_rent` SET `r_name`='".$_POST['txtRName']."',`r_email`='".$_POST['txtREmail']."',`r_password`='".$converter->encode($_POST['txtPassword'])."',`r_contact`='".$_POST['txtRContact']."',`r_address`='".$_POST['txtRAddress']."',`r_nid`='".$_POST['txtRentedNID']."',`r_floor_no`='".$_POST['ddlFloorNo']."',`r_unit_no`='".$_POST['ddlUnitNo']."',`r_advance`='".$_POST['txtRAdvance']."',`r_rent_pm`='".$_POST['txtRentPerMonth']."',`r_date`='".$_POST['txtRDate']."',`r_month`='".$_POST['ddlMonth']."',`r_year`='".$_POST['ddlYear']."',`r_status`='".$_POST['chkRStaus']."',`image`='".$image_url."' WHERE rid='".$_GET['id']."'";
		mysqli_query($link,$sql);
		//update unit status
		$sqlx = "UPDATE `tbl_add_unit` set status = 0 where floor_no = '".(int)$_POST['hdnFloor']."' and uid = '".(int)$_POST['hdnUnit']."'";
		mysqli_query($link,$sqlx);
		$sqlxx = "UPDATE `tbl_add_unit` set status = 1 where floor_no = '".(int)$_POST['ddlFloorNo']."' and uid = '".(int)$_POST['ddlUnitNo']."'";
		mysqli_query($link,$sqlxx);
		///////////////////////////////////////////
		$url = WEB_URL . 'rent/rentlist.php?m=up';
		header("Location: $url");
	}

	$success = "block";
}

if(isset($_GET['id']) && $_GET['id'] != ''){
	$result = mysqli_query($link,"SELECT * FROM tbl_add_rent where rid = '" . $_GET['id'] . "'");
	if($row = mysqli_fetch_array($result)){
		$r_name = $row['r_name'];
		$r_email = $row['r_email'];
		$r_contact = $row['r_contact'];
		$r_address = $row['r_address'];
		$r_nid = $row['r_nid'];
		$r_floor_no = $row['r_floor_no'];
		$r_unit_no = $row['r_unit_no'];
		$r_advance = $row['r_advance'];
		$r_rent_pm = $row['r_rent_pm'];
		$r_date = $row['r_date'];
		$r_month = $row['r_month'];
		$r_year = $row['r_year'];
		$r_status = $row['r_status'];
		$r_password = $converter->decode($row['r_password']);
		if($row['image'] != ''){
			$image_rnt = WEB_URL . 'img/upload/' . $row['image'];
			$img_track = $row['image'];
		}
		$hdnid = $_GET['id'];
		$title = $_data['update_rent'];
		$button_text = $_data['update_button_text'];
		$successful_msg = $_data['update_renter_successfully'];
		$form_url = WEB_URL . "rent/addrent.php?id=".$_GET['id'];
	}
	
	//mysqli_close($link);

}

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
	return '';
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
    <li class="active"><?php echo $_data['add_new_renter_information_breadcam'];?></li>
    <li class="active"><?php echo $title;?></li>
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
        <h3 class="box-title"><?php echo $_data['add_new_renter_entry_form'];?></h3>
      </div>
      <form onSubmit="return validateMe();" action="<?php echo $form_url; ?>" method="post" enctype="multipart/form-data" id="frm_renter_entry">
        <div class="box-body row">
          <div class="form-group col-md-6">
            <label for="txtRName"><span class="errorStar">*</span> <?php echo $_data['add_new_form_field_text_1'];?> :</label>
            <input type="text" name="txtRName" value="<?php echo $r_name;?>" id="txtRName" class="form-control" />
          </div>
          <div class="form-group col-md-6">
            <label for="txtREmail"><span class="errorStar">*</span> <?php echo $_data['add_new_form_field_text_2'];?> :</label>
            <input type="text" name="txtREmail" value="<?php echo $r_email;?>" id="txtREmail" class="form-control" />
          </div>
          <div class="form-group col-md-6">
            <label for="txtPassword"><span class="errorStar">*</span> <?php echo $_data['add_new_form_field_text_3'];?> :</label>
            <input type="text" name="txtPassword" value="<?php echo $r_password;?>" id="txtPassword" class="form-control" />
          </div>
          <div class="form-group col-md-6">
            <label for="txtRContact"><span class="errorStar">*</span> <?php echo $_data['add_new_form_field_text_4'];?> :</label>
            <input type="text" name="txtRContact" value="<?php echo $r_contact;?>" id="txtRContact" class="form-control" />
          </div>
          <div class="form-group  col-md-12">
            <label for="txtRAddress"><span class="errorStar">*</span> <?php echo $_data['add_new_form_field_text_5'];?> :</label>
            <textarea name="txtRAddress" id="txtRAddress" class="form-control"><?php echo $r_address;?></textarea>
          </div>
          <div class="form-group col-md-6">
            <label for="txtRentedNID"><span class="errorStar">*</span> <?php echo $_data['add_new_form_field_text_6'];?> :</label>
            <input type="text" name="txtRentedNID" value="<?php echo $r_nid;?>" id="txtRentedNID" class="form-control" />
          </div>
          <div class="form-group col-md-6">
            <label for="ddlFloorNo"><span class="errorStar">*</span> <?php echo $_data['add_new_form_field_text_7'];?> :</label>
            <select onchange="getUnit(this.value)" name="ddlFloorNo" id="ddlFloorNo" class="form-control">
              <option value="">--<?php echo $_data['select_floor'];?>--</option>
              <?php 
				  	$result_floor = mysqli_query($link,"SELECT * FROM tbl_add_floor WHERE branch_id = " . (int)$_SESSION['objLogin']['branch_id'] . " order by fid ASC");
					while($row_floor = mysqli_fetch_array($result_floor)){?>
              <option <?php if($r_floor_no == $row_floor['fid']){echo 'selected';}?> value="<?php echo $row_floor['fid'];?>"><?php echo $row_floor['floor_no'];?></option>
              <?php } ?>
            </select>
          </div>
          <div class="form-group col-md-6">
            <label for="ddlUnitNo"><span class="errorStar">*</span> <?php echo $_data['add_new_form_field_text_8'];?> :</label>
            <select name="ddlUnitNo" id="ddlUnitNo" class="form-control">
              <option value="">--<?php echo $_data['select_unit'];?>--</option>
              <?php 
				  	$result_unit = mysqli_query($link,"SELECT * FROM tbl_add_unit where floor_no = ".(int)$r_floor_no." order by unit_no ASC");
					while($row_unit = mysqli_fetch_array($result_unit)){?>
              <option <?php if($r_unit_no == $row_unit['uid']){echo 'selected';}?> value="<?php echo $row_unit['uid'];?>"><?php echo $row_unit['unit_no'];?></option>
              <?php } ?>
            </select>
          </div>
          <div class="form-group col-md-6">
            <label for="txtRAdvance"><span class="errorStar">*</span> <?php echo $_data['add_new_form_field_text_9'];?> :</label>
            <div class="input-group">
              <input type="text" name="txtRAdvance" value="<?php echo $r_advance;?>" id="txtRAdvance" class="form-control" />
              <div class="input-group-addon"> <?php echo CURRENCY;?> </div>
            </div>
          </div>
          <div class="form-group col-md-6">
            <label for="txtRentPerMonth"><span class="errorStar">*</span> <?php echo $_data['add_new_form_field_text_10'];?> :</label>
            <div class="input-group">
              <input type="text" name="txtRentPerMonth" value="<?php echo $r_rent_pm;?>" id="txtRentPerMonth" class="form-control" />
              <div class="input-group-addon"> <?php echo CURRENCY;?> </div>
            </div>
          </div>
          <div class="form-group col-md-6">
            <label for="txtRDate"><span class="errorStar">*</span> <?php echo $_data['add_new_form_field_text_11'];?> :</label>
            <input type="text" name="txtRDate" value="<?php echo $r_date;?>" id="txtRDate" class="form-control datepicker"/>
          </div>
          <div class="form-group col-md-6">
            <label for="ddlMonth"><span class="errorStar">*</span> <?php echo $_data['add_new_form_field_text_12'];?> :</label>
            <select name="ddlMonth" id="ddlMonth" class="form-control">
              <option value="">--<?php echo $_data['select_month'];?>--</option>
              <?php 
				  	$result_unit = mysqli_query($link,"SELECT * FROM tbl_add_month_setup order by m_id ASC");
					while($row_unit = mysqli_fetch_array($result_unit)){?>
              <option <?php if($r_month == $row_unit['m_id']){echo 'selected';}?> value="<?php echo $row_unit['m_id'];?>"><?php echo $row_unit['month_name'];?></option>
              <?php } ?>
            </select>
          </div>
          <div class="form-group col-md-6">
            <label for="ddlYear"><span class="errorStar">*</span> <?php echo $_data['add_new_form_field_text_13'];?> :</label>
            <select name="ddlYear" id="ddlYear" class="form-control">
              <option value="">--<?php echo $_data['select_year'];?>--</option>
              <?php 
				  	$result_unit = mysqli_query($link,"SELECT * FROM tbl_add_year_setup order by y_id ASC");
					while($row_unit = mysqli_fetch_array($result_unit)){?>
              <option <?php if($r_year == $row_unit['y_id']){echo 'selected';}?> value="<?php echo $row_unit['y_id'];?>"><?php echo $row_unit['xyear'];?></option>
              <?php } ?>
            </select>
          </div>
          <div class="form-group col-md-12">
            <label for="chkRStaus"><?php echo $_data['add_new_form_field_text_14'];?> :</label>
            <select name="chkRStaus" id="chkRStaus" class="form-control">
              <option <?php if($r_status=='1'){echo 'selected';}?> value="1"><?php echo $_data['add_new_form_field_text_16']; ?></option>
              <option <?php if($r_status=='0'){echo 'selected';}?> value="0"><?php echo $_data['add_new_form_field_text_17']; ?></option>
            </select>
          </div>
          <div class="form-group col-md-12">
            <label for="Prsnttxtarea"><?php echo $_data['add_new_form_field_text_15'];?> :</label>
            <img class="form-control" src="<?php echo $image_rnt; ?>" style="height:100px;width:100px;" id="output"/>
            <input type="hidden" name="img_exist" value="<?php echo $img_track; ?>" />
          </div>
          <div class="form-group col-md-12"> <span class="btn btn-file btn btn-default"><?php echo $_data['upload_image'];?>
            <input type="file" name="uploaded_file" onchange="loadFile(event)" />
            </span> </div>
        </div>
        <div class="box-footer">
          <div class="form-group pull-right">
            <?php if($hdnid=='0') { ?>
            <button type="button" onclick="renter_email_exist();" name="button" class="btn btn-success"><i class="fa fa-floppy-o"></i> <?php echo $button_text; ?></button>
            <?php } else { ?>
            <button type="submit" name="button" class="btn btn-success"><i class="fa fa-floppy-o"></i> <?php echo $button_text; ?></button>
            <?php } ?>
            <a class="btn btn-warning" href="<?php echo WEB_URL; ?>rent/rentlist.php"><i class="fa fa-reply"></i> <?php echo $_data['back_text'];?></a> </div>
        </div>
        <input type="hidden" value="<?php echo $hdnid; ?>" name="hdn"/>
        <input type="hidden" value="<?php echo $r_floor_no; ?>" name="hdnFloor"/>
        <input type="hidden" value="<?php echo $r_unit_no; ?>" name="hdnUnit"/>
      </form>
      <!-- /.box-body -->
    </div>
    <!-- /.box -->
  </div>
</div>
<!-- /.row -->
<script type="text/javascript">
function validateMe(){
	if($("#txtRName").val() == ''){
		alert("<?php echo $_data['required_1']; ?>");
		$("#txtRName").focus();
		return false;
	}
	else if($("#txtREmail").val() == '' || !checkValidEmail('txtREmail')){
		alert("<?php echo $_data['required_2']; ?>");
		$("#txtREmail").focus();
		return false;
	}
	else if($("#txtPassword").val() == ''){
		alert("<?php echo $_data['required_3']; ?>");
		$("#txtPassword").focus();
		return false;
	}
	else if($("#txtRContact").val() == ''){
		alert("<?php echo $_data['required_4']; ?>");
		$("#txtRContact").focus();
		return false;
	}
	else if($("#txtRAddress").val() == ''){
		alert("<?php echo $_data['required_5']; ?>");
		$("#txtRAddress").focus();
		return false;
	}
	else if($("#txtRentedNID").val() == ''){
		alert("<?php echo $_data['required_6']; ?>");
		$("#txtRentedNID").focus();
		return false;
	}
	else if($("#ddlFloorNo").val() == ''){
		alert("<?php echo $_data['required_7']; ?>");
		$("#ddlFloorNo").focus();
		return false;
	}
	else if($("#ddlUnitNo").val() == ''){
		alert("<?php echo $_data['required_8']; ?>");
		$("#ddlUnitNo").focus();
		return false;
	}
	else if($("#txtRAdvance").val() == ''){
		alert("<?php echo $_data['required_9']; ?>");
		$("#txtRAdvance").focus();
		return false;
	}
	else if($("#txtRentPerMonth").val() == ''){
		alert("<?php echo $_data['required_10']; ?>");
		$("#txtRentPerMonth").focus();
		return false;
	}
	else if($("#txtRDate").val() == ''){
		alert("<?php echo $_data['required_11']; ?>");
		$("#txtRDate").focus();
		return false;
	}
	else if($("#ddlMonth").val() == ''){
		alert("<?php echo $_data['required_12']; ?>");
		$("#ddlMonth").focus();
		return false;
	}
	else if($("#ddlYear").val() == ''){
		alert("<?php echo $_data['required_13']; ?>");
		$("#ddlYear").focus();
		return false;
	}
	else{
		return true;
	}
}

//check renter email exist or not
function renter_email_exist(){
   var email = $("#txtREmail").val();
   if(email != ''){
	   $.ajax({
		  url: '../ajax/getunit.php',
		  type: 'POST',
		  data: 'email='+email+'&token=renter_email_exist',
		  dataType: 'json',
		  success: function(data) {
			 if(data != '-99'){
			 	if(data.email_exist){
					alert('<?php echo $_data['email_exist']; ?>');
					$("#txtREmail").focus();
				} else {
					jQuery("#frm_renter_entry").submit();
				}
			 }
			 else{
			 	window.location.href = '../index.php';
			 }
		  }
	   });
   } else {
   		$("#frm_renter_entry").submit();
   }
}
</script>
<?php include('../footer.php'); ?>
