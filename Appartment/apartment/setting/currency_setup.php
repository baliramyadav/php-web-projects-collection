<?php 
include('../header.php');
include(ROOT_PATH.'language/'.$lang_code_global.'/lang_currency_setup.php');
if(!isset($_SESSION['objLogin'])){
	header("Location: " . WEB_URL . "logout.php");
	die();
}
if(isset($_SESSION['login_type']) && ((int)$_SESSION['login_type'] != 5)){
	header("Location: ".WEB_URL."logout.php");
	die();
}

$symbol ='';
$name ='';
$button_text = $_data['save_button_text'];
$form_url = WEB_URL . "setting/currency_setup.php";
$hval = 0;

if(isset($_POST['txtSymbol'])){
	if($_POST['hdnSpid'] == '0'){
		$sql="INSERT INTO `tbl_currency`(`symbol`,`name`) VALUES ('$_POST[txtSymbol]','$_POST[txtName]')";	
		mysqli_query($link,$sql);
		mysqli_close($link);
		$url = WEB_URL . 'setting/currency_setup.php?m=add';
		header("Location: $url");
	} else {
		$sql_update="UPDATE `tbl_currency` set symbol = '$_POST[txtSymbol]',name = '$_POST[txtName]' where cid= '" . (int)$_POST['hdnSpid'] . "'";	
		mysqli_query($link,$sql_update);
		mysqli_close($link);
		$url = WEB_URL . 'setting/currency_setup.php?m=up';
		header("Location: $url");
	}
	$success = "block";
}

if(isset($_GET['spid']) && $_GET['spid'] != ''){
	$result_location = mysqli_query($link,"SELECT * FROM tbl_currency where cid= '" . (int)$_GET['spid'] . "'");
	if($row = mysqli_fetch_array($result_location)){
		$symbol = $row['symbol'];
		$name = $row['name'];
		$button_text = $_data['update_button_text'];
		$form_url = WEB_URL . "setting/currency_setup.php?id=".$_GET['spid'];
		$hval = $row['cid'];
	}	
}
	
?>
<!-- Content Header (Page header) -->

<section class="content-header">
  <h1><?php echo $_data['text_1'];?> </h1>
  <ol class="breadcrumb">
    <li><a href="<?php echo WEB_URL?>dashboard.php"><i class="fa fa-dashboard"></i> <?php echo $_data['home_breadcam'];?></a></li>
    <li class="active"><?php echo $_data['setting'];?></li>
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
        <div class="box-body">
          <div class="form-group">
            <label for="txtSymbol"><span class="errorStar">*</span> <?php echo $_data['text_3'];?> :</label>
            <input type="text" name="txtSymbol" value="<?php echo $symbol;?>" id="txtSymbol" class="form-control" />
          </div>
		  <div class="form-group">
            <label for="txtName"><span class="errorStar">*</span> <?php echo $_data['text_33'];?> :</label>
            <input type="text" name="txtName" value="<?php echo $name;?>" id="txtName" class="form-control" />
          </div>
          <div class="form-group pull-right">
			<button type="submit" name="submit" class="btn btn-success" ><i class="fa fa-save"></i> <?php echo $button_text; ?></button>
			&nbsp;
            <input type="reset" onClick="javascript:window.location.href='<?php echo WEB_URL; ?>setting/currency_setup.php';" name="btnReset" id="btnReset" value="<?php echo $_data['reset'];?>" class="btn btn-warning"/>
          </div>
        </div>
        <input type="hidden" name="hdnSpid" value="<?php echo $hval; ?>"/>
      </form>
      <h4 style="text-align:center; color:red;"><?php echo $_data['reset_text'];?></h4>
      <!-- /.box-body -->
<?php
$delinfo = 'none';
$addinfo = 'none';
$msg = "";
if(isset($_GET['delid']) && $_GET['delid'] != '' && $_GET['delid'] > 0){
	$sqlx= "DELETE FROM `tbl_currency` WHERE cid = ".$_GET['delid'];
	mysqli_query($link,$sqlx); 
	$delinfo = 'block';
}
if(isset($_GET['m']) && $_GET['m'] == 'add'){
	$addinfo = 'block';
	$msg = $_data['text_5'];
}
if(isset($_GET['m']) && $_GET['m'] == 'up'){
	$addinfo = 'block';
	$msg = $_data['text_6'];
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
            <?php echo $_data['text_7'];?> </div>
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
				$result = mysqli_query($link,"SELECT * FROM tbl_currency order by name ASC ");
				while($row = mysqli_fetch_array($result)){?>
                  <tr>
					<td><?php echo $row['symbol']; ?></td>
					<td><?php echo $row['name']; ?></td>
                    <td><a class="btn btn-warning ams_btn_special" data-toggle="tooltip" href="<?php echo WEB_URL;?>setting/currency_setup.php?spid=<?php echo $row['cid']; ?>" data-original-title="<?php echo $_data['edit_text'];?>"><i class="fa fa-pencil"></i></a> <a class="btn btn-danger ams_btn_special" data-toggle="tooltip" onclick="deleteYear(<?php echo $row['cid']; ?>);" href="javascript:;" data-original-title="<?php echo $_data['delete_text'];?>"><i class="fa fa-trash-o"></i></a></td>
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
  function deleteYear(Id){
  	var iAnswer = confirm("<?php echo $_data['confirm_delete']; ?>");
	if(iAnswer){
		window.location = '<?php echo WEB_URL;?>setting/currency_setup.php?delid=' + Id;
	}
  }
  function validateMe() {
  	if($("#txtSymbol").val() == ''){
		alert('<?php echo $_data['required_1']; ?>');
		$("#txtSymbol").focus();
		return false;
	} else if($("#txtName").val() == ''){
		alert('<?php echo $_data['required_2']; ?>');
		$("#txtName").focus();
		return false;
	} else {
		return true;
	}
  }
  </script>

<?php include('../footer.php'); ?>
