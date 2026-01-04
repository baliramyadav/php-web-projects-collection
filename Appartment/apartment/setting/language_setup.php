<?php 
include('../header.php');
include(ROOT_PATH.'language/'.$lang_code_global.'/lang_language_setup.php');
if(!isset($_SESSION['objLogin'])){
	header("Location: " . WEB_URL . "logout.php");
	die();
}
if(isset($_SESSION['login_type']) && ((int)$_SESSION['login_type'] != 5)){
	header("Location: ".WEB_URL."logout.php");
	die();
}
/******************************************/
if(isset($_GET['lang_text']) && !empty($_GET['lang_text'])){
	$lang_code_global = trim($_GET['lang_text']);
}
if(!empty($lang_code_global)){
	$files = array_values(array_filter(scandir(ROOT_PATH.'language/'.$lang_code_global), function($file) {
    	return !is_dir($file);
	}));
}
$file_lang_data = array();
if(isset($_GET['file_name']) && !empty($_GET['file_name'])){
	$file_lang_data = $ams_helper->getLangFileArray(ROOT_PATH.'language/'.$lang_code_global.'/'.trim($_GET['file_name']));
	print_r($file_lang_data);
	die();
}
/*******************************************/

$xyear ='';
$button_text = $_data['save_button_text'];
$form_url = WEB_URL . "setting/year_setup.php";
$hval = 0;

if(isset($_POST['txtXYear'])){
	if($_POST['hdnSpid'] == '0'){
		$sql="INSERT INTO `tbl_add_year_setup`(`xyear`) VALUES ('$_POST[txtXYear]')";	
		mysqli_query($link,$sql);
		mysqli_close($link);
		$url = WEB_URL . 'setting/year_setup.php?m=add';
		header("Location: $url");
	} else {
		$sql_update="UPDATE `tbl_add_year_setup` set xyear = '$_POST[txtXYear]' where y_id= '" . (int)$_POST['hdnSpid'] . "'";	
		mysqli_query($link,$sql_update);
		mysqli_close($link);
		$url = WEB_URL . 'setting/year_setup.php?m=up';
		header("Location: $url");
	}
	$success = "block";
}

if(isset($_GET['spid']) && $_GET['spid'] != ''){
	$result_location = mysqli_query($link,"SELECT * FROM tbl_add_year_setup where y_id= '" . (int)$_GET['spid'] . "'");
	if($row = mysqli_fetch_array($result_location)){
		$xyear = $row['xyear'];
		$button_text = $_data['update_button_text'];
		$form_url = WEB_URL . "setting/year_setup.php?id=".$_GET['spid'];
		$hval = $row['y_id'];
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
        <h3 class="box-title">&nbsp;</h3>
      </div>
	  
	  
	  <div class="clearfix">
	  
		  <div class="col-md-8">
			
			<div style="height:650px;overflow:auto;width:100%;border:solid 1px #ccc;">
				
			</div>
			
			
		  </div>
	  
		  <div class="col-md-4">
				
				
				<div style="margin-bottom:10px;">
					
					<select onchange="change_lang_folder(this.value);" name="ddlLanguage" id="ddlLanguage" class="form-control">
					<option value="-1">--Language--</option>
					<?php
					$dir    = ROOT_PATH . 'language/';
					$files1 = scandir($dir);
					foreach($files1 as $folder){
						if($folder != '' && $folder != '.' && $folder != '..'){
							if($lang_code_global==$folder){
								echo '<option selected value="'.$folder.'">'.$folder.'</option>';
							} else {
								echo '<option value="'.$folder.'">'.$folder.'</option>';
							}
						}
					}
					?>
					</select>
				</div>
				
				<div style="height:600px;overflow:auto;width:100%;border:solid 1px #ccc;">
					<ul>
					<?php
					foreach($files as $file){
						echo '<li><a href="'.WEB_URL.'setting/language_setup.php?lang_text='.$lang_code_global.'&file_name='.$file.'">'.$file.'</a></li>';
					}
					?>
					</ul>
				</div>
				
		  </div>
	  </div>
	  
	 

<?php
$delinfo = 'none';
$addinfo = 'none';
$msg = "";
if(isset($_GET['delid']) && $_GET['delid'] != '' && $_GET['delid'] > 0){
	$sqlx= "DELETE FROM `tbl_add_year_setup` WHERE y_id = ".$_GET['delid'];
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
                    <th><?php echo $_data['action_text'];?></th>
                  </tr>
                </thead>
                <tbody>
                  <?php
				$result = mysqli_query($link,"SELECT * FROM tbl_add_year_setup order by y_id ASC ");
				while($row = mysqli_fetch_array($result)){?>
                  <tr>
					<td><?php echo $row['xyear']; ?></td>
                    <td><a class="btn btn-warning ams_btn_special" data-toggle="tooltip" href="<?php echo WEB_URL;?>setting/year_setup.php?spid=<?php echo $row['y_id']; ?>" data-original-title="<?php echo $_data['edit_text'];?>"><i class="fa fa-pencil"></i></a> <a class="btn btn-danger ams_btn_special" data-toggle="tooltip" onclick="deleteYear(<?php echo $row['y_id']; ?>);" href="javascript:;" data-original-title="<?php echo $_data['delete_text'];?>"><i class="fa fa-trash-o"></i></a></td>
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
		window.location = '<?php echo WEB_URL;?>setting/year_setup.php?delid=' + Id;
	}
  }
  function change_lang_folder(folder_name) {
  	if(folder_name != ''){
		window.location = '<?php echo WEB_URL;?>setting/language_setup.php?lang_text='+folder_name;
	}
  }
  </script>

<?php include('../footer.php'); ?>
