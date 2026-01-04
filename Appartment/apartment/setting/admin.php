<?php 
include('../header.php');
if(!isset($_SESSION['objLogin'])){
	header("Location: " . WEB_URL . "logout.php");
	die();
}
if(isset($_SESSION['login_type']) && ((int)$_SESSION['login_type'] != 5)){
	header("Location: ".WEB_URL."logout.php");
	die();
}
$lang_code_global = "English";
$global_currency = "$";
$currency_position = "left";
$currency_sep = ".";

$query_ams_settings = mysqli_query($link,"SELECT * FROM tbl_settings");
while($row_query_ams_core = mysqli_fetch_array($query_ams_settings)){
	$lang_code_global = $row_query_ams_core['lang_code'];
	$global_currency = $row_query_ams_core['currency'];
	$currency_position = $row_query_ams_core['currency_position'];
	$currency_sep = $row_query_ams_core['currency_seperator'];
}
//
include(ROOT_PATH.'language/'.$lang_code_global.'/lang_admin_setup.php');
$name ='';
$email = '';
$contact = '';
$password = '';
$branch_id = '';
$bname = '';
$button_text = $_data['button_text_save'];
$form_url = WEB_URL . "setting/admin.php";
$hval = 0;
$__alert = false;
$image_rnt = WEB_URL . 'img/no_image.jpg';
$img_track = '';

if(isset($_POST['txtAdminName'])){
	if($_POST['hdnSpid'] == '0'){		
		$result = mysqli_query($link,"SELECT * from tbl_add_admin where email= '" .trim($_POST['txtAdminEmail']). "'");
		//$result_2 = mysqli_query($link,"SELECT * from tbl_add_admin where branch_id= '".(int)$_POST['ddlBranch']."'");
		if($row = mysqli_fetch_array($result)){
			//email exist
			$__alert = true;
		//} else if($row_2 = mysqli_fetch_array($result_2)){
			//branch exist
			//$__alert = true;
		} else {
			$image_url = uploadImage();
			$sql="INSERT INTO `tbl_add_admin`(`name`,`email`,`contact`,`password`,`image`,`branch_id`) VALUES ('$_POST[txtAdminName]','$_POST[txtAdminEmail]','$_POST[txtAdminContact]','".$converter->encode($_POST['txtAdminPassword'])."','$image_url','$_POST[ddlBranch]')";	
			mysqli_query($link,$sql);
			mysqli_close($link);
			$url = WEB_URL . 'setting/admin.php?m=add';
			header("Location: $url");
		}
	}
	else{		
		$image_url = uploadImage();
		if($image_url == ''){
			$image_url = $_POST['img_exist'];
		}
		$sql_update="UPDATE `tbl_add_admin` set name = '".$_POST['txtAdminName']."',email = '".$_POST['txtAdminEmail']."',contact = '".$_POST['txtAdminContact']."',password = '".$converter->encode($_POST['txtAdminPassword'])."',image = '".$image_url."',branch_id = '".$_POST['ddlBranch']."' where aid= '" . (int)$_POST['hdnSpid'] . "'";	
		mysqli_query($link,$sql_update);
		mysqli_close($link);
		$url = WEB_URL . 'setting/admin.php?m=up';
		header("Location: $url");
	}
}

if(isset($_GET['spid']) && $_GET['spid'] != ''){
	$result = mysqli_query($link,"SELECT *,a.added_date as p_added_date,b.branch_name from tbl_add_admin a inner join tblbranch b on b.branch_id = a.branch_id where a.aid= '" . (int)$_GET['spid'] . "'");
	if($row = mysqli_fetch_array($result)){
		$name = $row['name'];
		$email = $row['email'];
		$contact = $row['contact'];
		$password = $row['password'];
		$branch_id = $row['branch_id'];
		$bname = $row['branch_name'];
		$button_text = $_data['button_text_update'];
		$form_url = WEB_URL . "setting/admin.php?id=".$_GET['spid'];
		$hval = $row['aid'];
		if($row['image'] != ''){
			$image_rnt = WEB_URL . 'img/upload/' . $row['image'];
			$img_track = $row['image'];
		}
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
  <h1> <?php echo $_data['page_title']; ?> </h1>
  <ol class="breadcrumb">
    <li><a href="<?php echo WEB_URL?>dashboard.php"><i class="fa fa-dashboard"></i> <?php echo $_data['home']; ?></a></li>
    <li class="active"><?php echo $_data['settings']; ?></li>
    <li class="active"> <?php echo $_data['page_title']; ?></li>
  </ol>
</section>
<!-- Main content -->
<section class="content">
<!-- Full Width boxes (Stat box) -->
<div class="row">
  <div class="col-md-12">
    <div align="right" style="margin-bottom:1%;"> </div>
    <?php if($__alert) { ?>
    <div class="alert alert-warning alert-dismissable">
      <button aria-hidden="true" data-dismiss="alert" class="close" type="button"><i class="fa fa-close"></i></button>
      <h4><i class="icon fa fa-ban"></i> <?php echo $_data['email_exist_warning']; ?> !</h4>
      <?php echo $_data['email_exist']; ?> </div>
    <?php } ?>
    <div class="box box-success">
      <div class="box-header">
        <h3 class="box-title"><?php echo $_data['page_form']; ?></h3>
      </div>
      <form onSubmit="return validateMe();" action="<?php echo $form_url; ?>" method="post" enctype="multipart/form-data">
        <div class="box-body row">
          <div class="form-group col-md-6">
            <label for="txtAdminEmail"><span class="errorStar">*</span> <?php echo $_data['label_email']; ?> :</label>
            <input type="email" name="txtAdminEmail" id="txtAdminEmail" value="<?php echo $email; ?>" class="form-control" required />
          </div>
          <div class="form-group col-md-6">
            <label for="txtAdminName"><span class="errorStar">*</span> <?php echo $_data['label_name']; ?> :</label>
            <input type="text" name="txtAdminName" id="txtAdminName" value="<?php echo $name; ?>" class="form-control" />
          </div>
		  <div class="form-group col-md-6">
            <label for="txtAdminContact"> <?php echo $_data['profile_update_contact']; ?> :</label>
            <input type="text" name="txtAdminContact" id="txtAdminContact" value="<?php echo $contact; ?>" class="form-control" />
          </div>
          <div class="form-group col-md-6">
            <label for="txtAdminPassword"><span class="errorStar">*</span> <?php echo $_data['label_password']; ?> :</label>
            <input type="text" name="txtAdminPassword" value="<?php echo $converter->decode($password); ?>" id="txtAdminPassword" class="form-control" />
          </div>
          <div class="form-group col-md-12">
            <label for="ddlBranch"><span class="errorStar">*</span> <?php echo $_data['label_branch']; ?> :</label>
            <select name="ddlBranch" id="ddlBranch" class="form-control">
              <option value="">--<?php echo $_data['label_branch']; ?>--</option>
              <?php
				$result_page = mysqli_query($link,"SELECT * FROM tblbranch order by branch_name ASC" );
				while($row_page = mysqli_fetch_array($result_page)){
					if($branch_id  == $row_page['branch_id']){
						echo '<option selected="selected" value="'.$row_page['branch_id'].'">'.$row_page['branch_name'].'</option>';
					}
					else{
						echo '<option value="'.$row_page['branch_id'].'">'.$row_page['branch_name'].'</option>';
					}
				}
				?>
            </select>
          </div>
		  
		  <div class="form-group col-md-12">
            <label for="Prsnttxtarea"><?php echo $_data['label_image'];?> : </label>
            <img class="form-control" src="<?php echo $image_rnt; ?>" style="height:100px;width:100px;" id="output"/>
            <input type="hidden" name="img_exist" value="<?php echo $img_track; ?>" />
          </div>
          <div class="form-group col-md-12"> <span class="btn btn-file btn btn-default"><?php echo $_data['upload_image'];?>
            <input type="file" name="uploaded_file" onchange="loadFile(event)" />
            </span> </div>
		  
		  
          <div class="form-group col-md-12" align="right">
            <input type="submit" name="submit" class="btn btn-success" value="<?php echo $button_text; ?>"/>
            &nbsp;
            <input type="reset" onClick="javascript:window.location.href='<?php echo WEB_URL; ?>setting/admin.php';" name="btnReset" id="btnReset" value="<?php echo $_data['btn_reset']; ?>" class="btn btn-warning"/>
          </div>
        </div>
        <input type="hidden" name="hdnSpid" value="<?php echo $hval; ?>"/>
      </form>
      <h4 style="text-align:center; color:red;"><?php echo $_data['label_warning']; ?></h4>
      <!-- /.box-body -->
      <?php
$delinfo = 'none';
$addinfo = 'none';
$msg = "";
 if(isset($_GET['delid']) && $_GET['delid'] != '' && $_GET['delid'] > 0){
		$sqlx= "DELETE FROM `tbl_add_admin` WHERE aid = ".$_GET['delid'];
		mysqli_query($link,$sqlx); 
		$delinfo = 'block';
	}
if(isset($_GET['m']) && $_GET['m'] == 'add'){
	$addinfo = 'block';
	$msg = $_data['save_success'];
}
if(isset($_GET['m']) && $_GET['m'] == 'up'){
	$addinfo = 'block';
	$msg = $_data['save_update'];
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
            <?php echo $_data['delete_success'];?> </div>
          <div class="alert alert-success alert-dismissable" style="display:<?php echo $addinfo; ?>">
            <button aria-hidden="true" data-dismiss="alert" class="close" type="button"><i class="fa fa-close"></i></button>
            <h4><i class="icon fa fa-check"></i> <?php echo $_data['success'];?> !</h4>
            <?php echo $msg; ?> </div>
          <div class="box box-success">
            <div class="box-header">
              <h3 class="box-title"><?php echo $_data['admin_list']; ?></h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <table class="table sakotable table-bordered table-striped dt-responsive">
                <thead>
                  <tr>
                    <th><?php echo $_data['column_0']; ?></th>
					<th><?php echo $_data['column_1']; ?></th>
                    <th><?php echo $_data['column_2']; ?></th>
					<th><?php echo $_data['column_33']; ?></th>
                    <th><?php echo $_data['column_3']; ?></th>
                    <th><?php echo $_data['column_4']; ?></th>
                  </tr>
                </thead>
                <tbody>
                  <?php
				$result = mysqli_query($link,"SELECT *,a.added_date as p_addted_date,b.branch_name from tbl_add_admin a inner join tblbranch b on b.branch_id = a.branch_id order by a.aid DESC");
				while($row = mysqli_fetch_array($result)){
					$phpdate = strtotime( $row['p_addted_date'] );
                 	$date = date( 'd/m/Y H:i:s', $phpdate );
					$image = WEB_URL . 'img/no_image.jpg';	
					if(file_exists(ROOT_PATH . '/img/upload/' . $row['image']) && $row['image'] != ''){
						$image = WEB_URL . 'img/upload/' . $row['image'];
					}
					?>
                  <tr>
                    <td><img class="photo_img_round" style="width:50px;height:50px;" src="<?php echo $image;  ?>" /></td>
					<td><?php echo $row['name']; ?></td>
                    <td><?php echo $row['email']; ?></td>
					<td><?php echo $row['contact']; ?></td>
                    <td><?php echo $row['branch_name']; ?></td>
                    <td><a class="btn btn-success ams_btn_special" data-toggle="tooltip" href="javascript:;" onclick="$('#employee_view_<?php echo $row['aid']; ?>').modal('show');" data-original-title="View"><i class="fa fa-eye"></i></a> <a class="btn btn-warning ams_btn_special" data-toggle="tooltip" href="<?php echo WEB_URL;?>setting/admin.php?spid=<?php echo $row['aid']; ?>" data-original-title="Edit"><i class="fa fa-pencil"></i></a> <a class="btn btn-danger ams_btn_special" data-toggle="tooltip" onclick="deleteAdmin(<?php echo $row['aid']; ?>);" href="javascript:;" data-original-title="Delete"><i class="fa fa-trash-o"></i></a>
                      <div id="employee_view_<?php echo $row['aid']; ?>" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                      <div class="modal-dialog">
                        <div class="modal-content">
                          <div class="modal-header green_header">
                            <button aria-label="Close" data-dismiss="modal" class="close" type="button"><span aria-hidden="true"><i class="fa fa-close"></i></span></button>
                            <h3 class="modal-title"><?php echo $_data['admin_details']; ?></h3>
                          </div>
                          <div class="modal-body model_view" align="center">&nbsp;
                            <div>
                              <img class="photo_img_round" style="width:100px;height:100px;" src="<?php echo $image;  ?>" />
                            </div>
                            <div class="model_title"><?php echo $row['name']; ?></div>
                          </div>
                          <div class="modal-body">
                            <h3 style="text-decoration:underline;"><?php echo $_data['details_information'];?></h3>
                            <div class="row">
                              <div class="col-xs-12"> <?php echo $_data['column_1']; ?> : <?php echo $row['name']; ?><br/>
                                <?php echo $_data['column_2']; ?> : <?php echo $row['email']; ?><br/>
                                <?php echo $_data['column_33']; ?> : <?php echo $row['contact']; ?><br/>
								<?php echo $_data['column_3']; ?> : <?php echo $row['branch_name']; ?><br/>
                                <?php echo $_data['label_password']; ?> : <?php echo $converter->decode($row['password']); ?><br/>
                              </div>
                            </div>
                          </div>
                          <!-- /.modal-content -->
                        </div>
                      </div></td>
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
  function validateMe() {
  	if($("#txtAdminEmail").val() == ''){
		alert('<?php echo $_data['required_1']; ?>');
		$("#txtAdminEmail").focus();
		return false;
	} else if($("#txtAdminName").val() == ''){
		alert('<?php echo $_data['required_2']; ?>');
		$("#txtAdminName").focus();
		return false;
	} else if($("#txtAdminPassword").val() == ''){
		alert('<?php echo $_data['required_3']; ?>');
		$("#txtAdminPassword").focus();
		return false;
	} else if($("#ddlBranch").val() == ''){
		alert('<?php echo $_data['required_4']; ?>');
		$("#ddlBranch").focus();
		return false;
	} else {
		return true;
	}
  }
  function deleteAdmin(Id){
  	var iAnswer = confirm("<?php echo $_data['confirm_delete']; ?>");
	if(iAnswer){
		window.location = '<?php echo WEB_URL;?>setting/admin.php?delid=' + Id;
	}
  }
  </script>
<?php include('../footer.php'); ?>
