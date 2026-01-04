<?php 
include('../header.php');
include(ROOT_PATH.'language/'.$lang_code_global.'/lang_add_visitor.php');
if(!isset($_SESSION['objLogin'])){
	header("Location: " . WEB_URL . "logout.php");
	die();
}
$success = "none";
$floor_no = '';
$title = $_data['text_1'];
$button_text=$_data['save_button_text'];
$successful_msg=$_data['text_15'];
$form_url = WEB_URL . "visitor/addvisitor.php";
$id="";
$hdnid="0";
$floor_id = 0;
$unit_id = 0;
$name = '';
$mobile = '';
$address = '';
$intime = '';
$outtime = '';
$xdate = '';
$branch_id = '';
$issue_date = '';
if(isset($_POST['txtName'])){
	if(isset($_POST['hdn']) && $_POST['hdn'] == '0'){
		$month = date('m');
		$year = date('Y');
		$sql = "INSERT INTO `tbl_visitor`(issue_date,name,mobile,address,floor_id,unit_id,intime,outtime,xmonth,xyear,branch_id) values('$_POST[txtIssueDate]','$_POST[txtName]','$_POST[txtMobile]','$_POST[txtAddress]','$_POST[ddlFloorNo]','$_POST[ddlUnitNo]','$_POST[txtInTime]','$_POST[txtOutTime]',$month,$year,'" . $_SESSION['objLogin']['branch_id'] . "')";
		mysqli_query($link,$sql);
		mysqli_close($link);
		$url = WEB_URL . 'visitor/visitorlist.php?m=add';
		header("Location: $url");
		
	} else{
		
		$sql = "UPDATE `tbl_visitor` SET `issue_date`='".$_POST['txtIssueDate']."',`name`='".$_POST['txtName']."',`mobile`='".$_POST['txtMobile']."',`address`='".$_POST['txtAddress']."',`floor_id`='".$_POST['ddlFloorNo']."',`unit_id`='".$_POST['ddlUnitNo']."',`intime`='".$_POST['txtInTime']."',`outtime`='".$_POST['txtOutTime']."' WHERE vid='".$_GET['id']."'";
		mysqli_query($link,$sql);
		$url = WEB_URL . 'visitor/visitorlist.php?m=up';
		header("Location: $url");
	}

	$success = "block";
}

if(isset($_GET['id']) && $_GET['id'] != ''){
	$result = mysqli_query($link,"SELECT * FROM tbl_visitor where vid = '" . $_GET['id'] . "'");
	while($row = mysqli_fetch_array($result)){
		
		$issue_date = $row['issue_date'];
		$name = $row['name'];
		$mobile = $row['mobile'];
		$floor_id = $row['floor_id'];
		$unit_id = $row['unit_id'];
		$intime = $row['intime'];
		$outtime = $row['outtime'];
		$address = $row['address'];
		$hdnid = $_GET['id'];
		$title = $_data['text_16'];
		$button_text=$_data['update_button_text'];
		$successful_msg=$_data['text_17'];
		$form_url = WEB_URL . "visitor/addvisitor.php?id=".$_GET['id'];
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
		  <div class="form-group col-md-6">
            <label for="txtName"><span style="color:red;">*</span> <?php echo $_data['text_6'];?> :</label>
            <input type="text" name="txtName" value="<?php echo $name;?>" id="txtName" class="form-control" />
          </div>
		  <div class="form-group col-md-6">
            <label for="txtMobile"><span style="color:red;">*</span> <?php echo $_data['text_7'];?> :</label>
            <input type="text" name="txtMobile" value="<?php echo $mobile;?>" id="txtMobile" class="form-control" />
          </div>
		  <div class="form-group col-md-12">
            <label for="txtAddress"><span style="color:red;">*</span> <?php echo $_data['text_8'];?> :</label>
            <textarea name="txtAddress" class="form-control" id="txtAddress"><?php echo $address; ?></textarea>
          </div>
		  <div class="form-group col-md-6">
            <label for="ddlFloorNo"><span style="color:red;">*</span> <?php echo $_data['text_9'];?> :</label>
            <select onchange="getUnitReport(this.value)" name="ddlFloorNo" id="ddlFloorNo" class="form-control">
              <option value="">--<?php echo $_data['text_10'];?>--</option>
              <?php 
				  	$result_floor = mysqli_query($link,"SELECT * FROM tbl_add_floor where branch_id = " . (int)$_SESSION['objLogin']['branch_id'] . " order by fid ASC");
					while($row_floor = mysqli_fetch_array($result_floor)){?>
              <option <?php if($floor_id == $row_floor['fid']){echo 'selected';}?> value="<?php echo $row_floor['fid'];?>"><?php echo $row_floor['floor_no'];?></option>
              <?php } ?>
            </select>
          </div>
          <div class="form-group col-md-6">
            <label for="ddlUnitNo"><span style="color:red;">*</span> <?php echo $_data['text_11'];?> :</label>
            <select name="ddlUnitNo" id="ddlUnitNo" class="form-control">
              <option value="">--<?php echo $_data['text_12'];?>--</option>
              <?php 
				  	if(!empty($floor_id)){
						$result_unit = mysqli_query($link,"SELECT * FROM tbl_add_unit where floor_no = ".(int)$floor_id." and branch_id = " . (int)$_SESSION['objLogin']['branch_id'] . " order by uid ASC");
						while($row_unit = mysqli_fetch_array($result_unit)){ ?>
              				<option <?php if($unit_id == $row_unit['uid']){echo 'selected';}?> value="<?php echo $row_unit['uid'];?>"><?php echo $row_unit['unit_no'];?></option>
						}
              <?php } } ?>
            </select>
          </div>
		  <div class="form-group col-md-6">
            <label for="txtInTime"><span style="color:red;">*</span> <?php echo $_data['text_13'];?> :</label>
            <input type="text" name="txtInTime" value="<?php  echo $intime;?>" id="txtInTime" class="form-control" />
          </div>
		  <div class="form-group col-md-6">
            <label for="txtOutTime"><span style="color:red;">*</span> <?php echo $_data['text_14'];?> :</label>
            <input type="text" name="txtOutTime" value="<?php echo $outtime;?>" id="txtOutTime" class="form-control" />
          </div>
        </div>
		<div class="box-footer">
          <div class="form-group pull-right">
            <button type="submit" name="button" class="btn btn-success"><i class="fa fa-floppy-o"></i> <?php echo $button_text; ?></button>
            <a class="btn btn-warning" href="<?php echo WEB_URL; ?>visitor/visitorlist.php"><i class="fa fa-reply"></i> <?php echo $_data['back_text'];?></a> </div>
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
	if($("#txtIssueDate").val() == ''){
		alert("<?php echo $_data['r1']; ?>");
		$("#txtIssueDate").focus();
		return false;
	}
	else if($("#txtName").val() == ''){
		alert("<?php echo $_data['r2']; ?>");
		$("#txtName").focus();
		return false;
	}
	else if($("#txtMobile").val() == ''){
		alert("<?php echo $_data['r3']; ?>");
		$("#txtMobile").focus();
		return false;
	}
	else if($("#txtAddress").val() == ''){
		alert("<?php echo $_data['r4']; ?>");
		$("#txtAddress").focus();
		return false;
	}
	else if($("#ddlFloorNo").val() == ''){
		alert("<?php echo $_data['r5']; ?>");
		$("#ddlFloorNo").focus();
		return false;
	}
	else if($("#ddlUnitNo").val() == ''){
		alert("<?php echo $_data['r6']; ?>");
		$("#ddlUnitNo").focus();
		return false;
	}
	else if($("#txtInTime").val() == ''){
		alert("<?php echo $_data['r7']; ?>");
		$("#txtInTime").focus();
		return false;
	}
	else if($("#txtOutTime").val() == ''){
		alert("<?php echo $_data['r8']; ?>");
		$("#txtOutTime").focus();
		return false;
	}
	else{
		return true;
	}
}
</script>
<?php include('../footer.php'); ?>
