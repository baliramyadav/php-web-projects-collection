<?php 
include('../header.php');
include('../utility/common.php');
include(ROOT_PATH.'language/'.$lang_code_global.'/lang_add_employee.php');
if(!isset($_SESSION['objLogin'])){
	header("Location: " . WEB_URL . "logout.php");
	die();
}
$success = "none";
$e_name = '';
$e_email = '';
$salary = '0.00';
$e_contact = '';
$e_pre_address = '';
$e_per_address = '';
$e_nid = '';
$e_designation = 0;
$e_date = '';
$ending_date = '';
$e_status = 1;
$e_password = '';
$branch_id = '';
$title = $_data['add_new_employee'];
$button_text = $_data['save_button_text'];
$successful_msg = $_data['added_employee_successfully'];
$form_url = WEB_URL . "employee/addemployee.php";
$id="";
$hdnid="0";
$image_emp = WEB_URL . 'img/no_image.jpg';
$img_track = '';

if(isset($_POST['txtEmpName'])){
	if(isset($_POST['hdn']) && $_POST['hdn'] == '0'){
		$e_password = $converter->encode($_POST['txtPassword']);
		$image_url = uploadImage();
		$sql = "INSERT INTO tbl_add_employee(e_name,e_email, e_contact, e_pre_address,e_per_address,e_nid,e_designation,e_date,ending_date,e_password,e_status,image,branch_id,salary) values('$_POST[txtEmpName]','$_POST[txtEmpEmail]','$_POST[txtEmpContact]','$_POST[txtEmpPreAddress]','$_POST[txtEmpPerAddress]','$_POST[txtEmpNID]','$_POST[ddlMemberType]','$_POST[txtEmpDate]','$_POST[txtEndingDate]','$e_password','$_POST[chkEmpStaus]','$image_url','" . $_SESSION['objLogin']['branch_id'] . "','$_POST[txtSalary]')";
		mysqli_query($link,$sql);
		mysqli_close($link);
		$url = WEB_URL . 'employee/employeelist.php?m=add';
		header("Location: $url");
	} else {
		$image_url = uploadImage();
		if($image_url == ''){
			$image_url = $_POST['img_exist'];
		}
		$sql = "UPDATE `tbl_add_employee` SET `e_name`='".$_POST['txtEmpName']."',`e_email`='".$_POST['txtEmpEmail']."',`e_password`='".$converter->encode($_POST['txtPassword'])."',`e_contact`='".$_POST['txtEmpContact']."',`e_pre_address`='".$_POST['txtEmpPreAddress']."',`e_per_address`='".$_POST['txtEmpPerAddress']."',`e_nid`='".$_POST['txtEmpNID']."',`e_designation`='".$_POST['ddlMemberType']."',`e_date`='".$_POST['txtEmpDate']."',`ending_date`='".$_POST['txtEndingDate']."',`e_status`='".$_POST['chkEmpStaus']."',`image`='".$image_url."',`salary`='".$_POST['txtSalary']."' WHERE eid='".$_GET['id']."'";
		mysqli_query($link,$sql);
		$url = WEB_URL . 'employee/employeelist.php?m=up';
		header("Location: $url");
	}
	$success = "block";
}

if(isset($_GET['id']) && $_GET['id'] != ''){
	$result = mysqli_query($link,"SELECT * FROM tbl_add_employee where eid = '" . $_GET['id'] . "'");
	while($row = mysqli_fetch_array($result)){
		$e_name = $row['e_name'];
		$e_email = $row['e_email'];
		$e_contact = $row['e_contact'];
		$e_pre_address = $row['e_pre_address'];
		$e_per_address = $row['e_per_address'];
		$e_nid = $row['e_nid'];
		$e_designation = $row['e_designation'];
		$e_date = $row['e_date'];
		$ending_date = $row['ending_date'];
		$e_status = $row['e_status'];
		$salary = $row['salary'];
		$e_password = $converter->decode($row['e_password']);
		if($row['image'] != ''){
			$image_emp = WEB_URL . 'img/upload/' . $row['image'];
			$img_track = $row['image'];
		}
		$hdnid = $_GET['id'];
		$title = $_data['update_employee'];
		$button_text =$_data['update_button_text'];
		$successful_msg="Update Employee Successfully";
		$form_url = WEB_URL . "employee/addemployee.php?id=".$_GET['id'];
	}
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
    <li><a href="<?php echo WEB_URL?>/dashboard.php"><i class="fa fa-dashboard"></i><?php echo $_data['home_breadcam'];?></a></li>
    <li class="active"><?php echo $_data['add_new_employee_information_breadcam'];?></li>
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
        <h3 class="box-title"><?php echo $_data['add_new_employee_entry_form'];?></h3>
      </div>
      <form onSubmit="return validateMe();" action="<?php echo $form_url; ?>" method="post" enctype="multipart/form-data" id="frm_employee_entry">
        <div class="box-body row">
          <div class="form-group col-md-6">
            <label for="txtEmpName"><span style="color:red;">*</span> <?php echo $_data['add_new_form_field_text_1'];?> :</label>
            <input type="text" name="txtEmpName" value="<?php echo $e_name;?>" id="txtEmpName" class="form-control" />
          </div>
          <div class="form-group col-md-6">
            <label for="txtEmpEmail"><span style="color:red;">*</span>  <?php echo $_data['add_new_form_field_text_2'];?> :</label>
            <input type="email" name="txtEmpEmail" value="<?php echo $e_email;?>" id="txtEmpEmail" class="form-control" required />
          </div>
          <div class="form-group col-md-6">
            <label for="txtPassword"><span style="color:red;">*</span> <?php echo $_data['add_new_form_field_text_3'];?> :</label>
            <input type="text" name="txtPassword" value="<?php echo $e_password;?>" id="txtPassword" class="form-control" />
          </div>
          <div class="form-group col-md-6">
            <label for="txtEmpContact"><span style="color:red;">*</span> <?php echo $_data['add_new_form_field_text_4'];?> :</label>
            <input type="text" name="txtEmpContact" value="<?php echo $e_contact;?>" id="txtEmpContact" class="form-control" />
          </div>
          <div class="form-group col-md-6">
            <label for="txtEmpPreAddress"><span style="color:red;">*</span> <?php echo $_data['add_new_form_field_text_5'];?> :</label>
            <textarea name="txtEmpPreAddress" id="txtEmpPreAddress" class="form-control"><?php echo $e_pre_address;?></textarea>
          </div>
          <div class="form-group col-md-6">
            <label for="txtEmpPerAddress"><span style="color:red;">*</span> <?php echo $_data['add_new_form_field_text_6'];?> :</label>
            <textarea name="txtEmpPerAddress" id="txtEmpPerAddress" class="form-control"><?php echo $e_per_address;?></textarea>
          </div>
          <div class="form-group col-md-6">
            <label for="txtEmpNID"><span style="color:red;">*</span> <?php echo $_data['add_new_form_field_text_7'];?> :</label>
            <input type="text" name="txtEmpNID" value="<?php echo $e_nid;?>" id="txtEmpNID" class="form-control" />
          </div>
          <div class="form-group col-md-6">
            <label for="ddlMemberType"><span style="color:red;">*</span> <?php echo $_data['add_new_form_field_text_8'];?> :</label>
            <select name="ddlMemberType" id="ddlMemberType" class="form-control">
              <option value="">--<?php echo $_data['add_new_form_field_text_15'];?>--</option>
              <?php 
				  	$result_type = mysqli_query($link,"SELECT * FROM tbl_add_member_type order by member_id ASC");
					while($row_type = mysqli_fetch_array($result_type)){
				  ?>
              <option <?php if($e_designation == $row_type['member_id']){echo 'selected';}?> value="<?php echo $row_type['member_id'];?>"><?php echo $row_type['member_type'];?></option>
              <?php }?>
            </select>
          </div>
          <div class="form-group col-md-6">
            <label for="txtEmpDate"><span style="color:red;">*</span> <?php echo $_data['add_new_form_field_text_9'];?> :</label>
            <input type="text" name="txtEmpDate" value="<?php echo $e_date;?>" id="txtEmpDate" class="form-control datepicker"/>
          </div>
          <div class="form-group col-md-6">
            <label for="txtEndingDate"><?php echo $_data['add_new_form_field_text_10'];?> :</label>
            <input type="text" name="txtEndingDate" value="<?php echo $ending_date;?>" id="txtEndingDate" class="form-control datepicker"/>
          </div>
		  <div class="form-group col-md-6">
            <label for="txtSalary"><?php echo $_data['add_new_form_field_text_100'];?> :</label>
            <input type="text" name="txtSalary" value="<?php echo $salary;?>" id="txtSalary" class="form-control"/>
          </div>
		  <div class="form-group col-md-6">
            <label for="chkEmpStaus"><?php echo $_data['add_new_form_field_text_11'];?> :</label>
            <select name="chkEmpStaus" id="chkEmpStaus" class="form-control">
              <option <?php if($e_status=='1'){echo 'selected';}?> value="1"><?php echo $_data['add_new_form_field_text_12']; ?></option>
              <option <?php if($e_status=='0'){echo 'selected';}?> value="0"><?php echo $_data['add_new_form_field_text_13']; ?></option>
            </select>
          </div>
          <div class="form-group col-md-12">
            <label for="Prsnttxtarea"><?php echo $_data['add_new_form_field_text_14'];?> :</label>
            <img class="form-control" src="<?php echo $image_emp; ?>" style="height:100px;width:100px;" id="output"/>
            <input type="hidden" name="img_exist" value="<?php echo $img_track; ?>" />
          </div>
          <div class="form-group col-md-12"> <span class="btn btn-file btn btn-default"><?php echo $_data['upload_image'];?>
            <input type="file" name="uploaded_file" onchange="loadFile(event)" />
            </span> </div>
        </div>
		<div class="box-footer">
          <div class="form-group pull-right">
            <?php if($hdnid=='0') { ?>
            <button type="button" onclick="employee_email_exist();" name="button" class="btn btn-success"><i class="fa fa-floppy-o"></i> <?php echo $button_text; ?></button>
            <?php } else { ?>
            <button type="submit" name="button" class="btn btn-success"><i class="fa fa-floppy-o"></i> <?php echo $button_text; ?></button>
            <?php } ?>
            <a class="btn btn-warning" href="<?php echo WEB_URL; ?>employee/employeelist.php"><i class="fa fa-reply"></i> <?php echo $_data['back_text'];?></a> </div>
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
	if($("#txtEmpName").val() == ''){
		alert("<?php echo $_data['v1']; ?>");
		$("#txtEmpName").focus();
		return false;
	}
	else if($("#txtEmpEmail").val() == '' || !checkValidEmail('txtEmpEmail')){
		alert("<?php echo $_data['v2']; ?>");
		$("#txtEmpEmail").focus();
		return false;
	}
	else if($("#txtPassword").val() == ''){
		alert("<?php echo $_data['v3']; ?>");
		$("#txtPassword").focus();
		return false;
	}
	else if($("#txtEmpContact").val() == ''){
		alert("<?php echo $_data['v4']; ?>");
		$("#txtEmpContact").focus();
		return false;
	}
	else if($("#txtEmpPreAddress").val() == ''){
		alert("<?php echo $_data['v5']; ?>");
		$("#txtEmpPreAddress").focus();
		return false;
	}
	else if($("#txtEmpPerAddress").val() == ''){
		alert("<?php echo $_data['v6']; ?>");
		$("#txtEmpPerAddress").focus();
		return false;
	}
	else if($("#txtEmpNID").val() == ''){
		alert("<?php echo $_data['v7']; ?>");
		$("#txtEmpNID").focus();
		return false;
	}
	else if($("#ddlMemberType").val() == ''){
		alert("<?php echo $_data['v8']; ?>");
		$("#ddlMemberType").focus();
		return false;
	}
	else if($("#txtEmpDate").val() == ''){
		alert("<?php echo $_data['v9']; ?>");
		$("#txtEmpDate").focus();
		return false;
	}
	else{
		return true;
	}
}

//check employee email exist or not
function employee_email_exist(){
   var email = $("#txtEmpEmail").val();
   if(email != ''){
	   $.ajax({
		  url: '../ajax/getunit.php',
		  type: 'POST',
		  data: 'email='+email+'&token=employee_email_exist',
		  dataType: 'json',
		  success: function(data) {
			 if(data != '-99'){
			 	if(data.email_exist){
					alert('<?php echo $_data['email_exist']; ?>');
					$("#txtEmpEmail").focus();
				} else {
					jQuery("#frm_employee_entry").submit();
				}
			 }
			 else{
			 	window.location.href = '../index.php';
			 }
		  }
	   });
   } else {
   		$("#frm_employee_entry").submit();
   }
}
</script>
<?php include('../footer.php'); ?>
