<?php 
include('../header.php');
include(ROOT_PATH.'language/'.$lang_code_global.'/lang_language.php');
if(!isset($_SESSION['objLogin'])){
	header("Location: " . WEB_URL . "logout.php");
	die();
}
if(isset($_SESSION['login_type']) && ((int)$_SESSION['login_type'] != 5)){
	header("Location: ".WEB_URL."logout.php");
	die();
}
//$lang_code_current = "English";
$lang_code = "English";
$currency = '';
$currency_seperator = '';
$currency_position = '';
$currency_decimal = '2';
$mail_protocol = 'mail';
$smtp_hostname = '';
$smtp_username = '';
$smtp_password = '';
$smtp_port = '';
$smtp_secure = '';
$cat_username = '';
$cat_password = '';
$cat_apikey = '';

$button_text = $_data['save_button_text'];
$success = 'none';
$msg = '';
$image_rnt = WEB_URL . 'img/no_image.jpg';
$img_track = '';

if(isset($_POST['ddlLanguage'])){
	$sqlx = "DELETE FROM `tbl_settings`";
	mysqli_query($link,$sqlx);
	$image_url = uploadImage();
	if($image_url == ''){
		$image_url = $_POST['img_exist'];
	}
	uploadLanguageFile();//upload lang zip file
	$sql = "INSERT INTO tbl_settings(`lang_code`,`currency`,`currency_seperator`,`currency_position`,`currency_decimal`,`mail_protocol`,`super_admin_image`,`smtp_hostname`,`smtp_username`,`smtp_password`,`smtp_port`,`smtp_secure`,`cat_username`,`cat_password`,`cat_apikey`) values('$_POST[ddlLanguage]','$_POST[ddlCurrency]','$_POST[ddlCurrencySeparator]','$_POST[ddlCurrencyPosition]','$_POST[ddlCurrencyDecimal]','$_POST[ddlMailOption]','$image_url','$_POST[smtp_hostname]','$_POST[smtp_username]','$_POST[smtp_password]','$_POST[smtp_port]','$_POST[smtp_secure]','$_POST[cat_username]','$_POST[cat_password]','$_POST[cat_apikey]')";
	mysqli_query($link,$sql);
	$success = 'block';
	$msg = $_data['text_4'];
}
$query = mysqli_query($link,"SELECT * FROM tbl_settings");
if($row = mysqli_fetch_array($query)){
	$lang_code = $row['lang_code'];
	$currency = $row['currency'];
	$currency_seperator = $row['currency_seperator'];
	$currency_position = $row['currency_position'];
	$currency_decimal = $row['currency_decimal'];
	$mail_protocol = $row['mail_protocol'];
	$smtp_hostname = $row['smtp_hostname'];
	$smtp_username = $row['smtp_username'];
	$smtp_password = $row['smtp_password'];
	$smtp_port = $row['smtp_port'];
	$smtp_secure = $row['smtp_secure'];
	$cat_username = $row['cat_username'];;
	$cat_password = $row['cat_password'];;
	$cat_apikey = $row['cat_apikey'];;
	
	if($row['super_admin_image'] != ''){
		$image_rnt = WEB_URL . 'img/upload/' . $row['super_admin_image'];
		$img_track = $row['super_admin_image'];
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

function uploadLanguageFile(){
	if((!empty($_FILES["uploaded_language_file"])) && ($_FILES['uploaded_language_file']['error'] == 0)) {
	  	$filename = basename($_FILES['uploaded_language_file']['name']);
		move_uploaded_file($_FILES["uploaded_language_file"]["tmp_name"], ROOT_PATH . '/language/'.$filename);
		$zip = new ZipArchive;
		$res = $zip->open(ROOT_PATH . '/language/'.$filename);
		if ($res === TRUE) {
		  $zip->extractTo(ROOT_PATH . '/language/');
		  $zip->close();
		}
		unlink(ROOT_PATH . '/language/'.$filename);
	}
}	
?>
<!-- Content Header (Page header) -->

<section class="content-header">
  <h1><?php echo $_data['text_1'];?></h1>
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
    <div id="you" class="alert alert-success alert-dismissable" style="display:<?php echo $success; ?>">
      <button aria-hidden="true" data-dismiss="alert" class="close" type="button"><i class="fa fa-close"></i></button>
      <h4><i class="icon fa fa-check"></i> <?php echo $_data['success'];?> !</h4>
      <?php echo $msg; ?> </div>
    <div class="box box-success">
      <form method="post" enctype="multipart/form-data">
        <div class="box-body">
          <ul class="nav nav-tabs">
            <li class="active"><a data-toggle="tab" href="#1">
              <h5><?php echo $_data['text_222'];?></h5>
              </a></li>
            <li><a data-toggle="tab" href="#2">
              <h5><?php echo $_data['text_2'];?></h5>
              </a></li>
            <li><a data-toggle="tab" href="#3">
              <h5><?php echo $_data['text_22'];?></h5>
              </a></li>
			<li><a data-toggle="tab" href="#4">
              <h5><?php echo $_data['text_x33'];?></h5>
              </a></li>
          </ul>
          <div class="tab-content">
            <div id="1" class="tab-pane fade in active">
              <p>
              <div class="form-group">
                <label for="ddlCurrency"><?php echo $_data['text_5'];?> :</label>
                <select name="ddlCurrency" id="ddlCurrency" class="form-control">
                  <option value=""><?php echo $_data['text_8'];?></option>
                  <?php
			  $result_currency = mysqli_query($link,"SELECT * FROM tbl_currency order by name ASC");
			  while($row_currency = mysqli_fetch_array($result_currency)){?>
                  <option <?php if($currency == $row_currency['symbol']){echo 'selected';}?> value="<?php echo $row_currency['symbol']; ?>"><?php echo $row_currency['name']; ?>(<?php echo $row_currency['symbol']; ?>)</option>
                  <?php } ?>
                </select>
              </div>
              <div class="form-group">
                <label for="ddlCurrencySeparator"><?php echo $_data['text_15'];?> :</label>
                <select name="ddlCurrencySeparator" id="ddlCurrencySeparator" class="form-control">
                  <option value=""><?php echo $_data['text_8'];?></option>
                  <option <?php if($currency_seperator == "."){echo 'selected';}?> value="."><?php echo $_data['text_9'];?></option>
                  <option <?php if($currency_seperator == ","){echo 'selected';}?> value=","><?php echo $_data['text_10'];?></option>
                </select>
              </div>
              <div class="form-group">
                <label for="ddlCurrencyPosition"><?php echo $_data['text_14'];?> :</label>
                <select name="ddlCurrencyPosition" id="ddlCurrencyPosition" class="form-control">
                  <option value=""><?php echo $_data['text_8'];?></option>
                  <option <?php if($currency_position == "left"){echo 'selected';}?> value="left"><?php echo $_data['text_11'];?></option>
                  <option <?php if($currency_position == "right"){echo 'selected';}?> value="right"><?php echo $_data['text_12'];?></option>
                </select>
              </div>
              <div class="form-group">
                <label for="ddlCurrencyDecimal"><?php echo $_data['text_144'];?> :</label>
                <select name="ddlCurrencyDecimal" id="ddlCurrencyDecimal" class="form-control">
                  <option value=""><?php echo $_data['text_8'];?></option>
                  <option <?php if($currency_decimal == "1"){echo 'selected';}?> value="1">1 (0.0)</option>
                  <option <?php if($currency_decimal == "2"){echo 'selected';}?> value="2">2 (0.00)</option>
                  <option <?php if($currency_decimal == "3"){echo 'selected';}?> value="3">3 (0.000)</option>
                  <option <?php if($currency_decimal == "4"){echo 'selected';}?> value="4">4 (0.0000)</option>
                </select>
              </div>
              <div class="form-group">
                <label for="Prsnttxtarea"><?php echo $_data['text_16'];?> :</label>
                <img class="form-control" src="<?php echo $image_rnt; ?>" style="height:100px;width:100px;" id="output"/>
                <input type="hidden" name="img_exist" value="<?php echo $img_track; ?>" />
              </div>
              <div class="form-group"> <span class="btn btn-file btn btn-default"><?php echo $_data['upload_image'];?>
                <input type="file" name="uploaded_file" onchange="loadFile(event)" />
                </span> </div>
              </p>
            </div>
            <div id="2" class="tab-pane fade">
              <p>
              <div class="form-group">
                <label for="ddlLanguage"><?php echo $_data['text_3'];?> :</label>
                <select name="ddlLanguage" id="ddlLanguage" class="form-control">
                  <option value="-1">---<?php echo $_data['text_3'];?>---</option>
                  <?php
			
			$dir    = ROOT_PATH . 'language/';
			$files1 = scandir($dir);
			foreach($files1 as $folder){
				if($folder != '' && $folder != '.' && $folder != '..'){
					if(trim($folder) == $lang_code){
						echo '<option selected value="'.$folder.'">'.$folder.'</option>';
					}
					else{
						echo '<option value="'.$folder.'">'.$folder.'</option>';
					}
				}
			}
			?>
                </select>
              </div>
              <div class="form-group">
                <label><?php echo $_data['text_17'];?> :</label>
                <br/>
                <span class="btn btn-file btn btn-default"><?php echo $_data['text_18'];?>
                <input type="file" name="uploaded_language_file" />
                </span> </div>
              <br/>
              <br/>
              <div class="form-group">
                <div style="font-weight:bold;text-decoration:underline;font-size:18px;"><?php echo $_data['text_lang_1'];?> </div>
                <div style="margin-top:5px;">* <a href="<?php echo WEB_URL.'img/English.zip'; ?>"><?php echo $_data['text_lang_22'];?> </a><?php echo $_data['text_lang_2'];?> </div>
                <div style="margin-top:5px;">* <?php echo $_data['text_lang_3'];?> </div>
                <div style="margin-top:5px;">* <?php echo $_data['text_lang_4'];?> </div>
                <div style="margin-top:5px;">* <?php echo $_data['text_lang_5'];?> </div>
              </div>
              </p>
            </div>
            <div id="3" class="tab-pane fade">
              <p>
			  <div class="form-group">
                <label for="ddlMailOption"><?php echo $_data['text_19'];?> :</label>
                <select name="ddlMailOption" id="ddlMailOption" class="form-control">
                  <option value=""><?php echo $_data['text_8'];?></option>
                  <option <?php if($mail_protocol == "mail"){echo 'selected';}?> value="mail">Mail</option>
                  <option <?php if($mail_protocol == "smtp"){echo 'selected';}?> value="smtp">SMTP</option>
                </select>
              </div>
			  
			  <div class="form-group">
                <label for="smtp_hostname"><?php echo $_data['text_20'];?> :</label>
                <input type="text" name="smtp_hostname" id="smtp_hostname" value="<?php echo $smtp_hostname; ?>" class="form-control" />
              </div>
			  
			  <div class="form-group">
                <label for="smtp_username"><?php echo $_data['text_21'];?> :</label>
                <input type="text" name="smtp_username" id="smtp_username" value="<?php echo $smtp_username; ?>" class="form-control" />
              </div>
			  
			  <div class="form-group">
                <label for="smtp_password"><?php echo $_data['text_22x'];?> :</label>
                <input type="text" name="smtp_password" id="smtp_password" value="<?php echo $smtp_password; ?>" class="form-control" />
              </div>
			  
			  <div class="form-group">
                <label for="smtp_port"><?php echo $_data['text_23'];?> :</label>
                <input type="text" name="smtp_port" id="smtp_port" value="<?php echo $smtp_port; ?>" class="form-control" />
              </div>
			  
			  <div class="form-group">
                <label for="smtp_secure"><?php echo $_data['text_24'];?> :</label>
                <select name="smtp_secure" id="smtp_secure" class="form-control">
                  <option value=""><?php echo $_data['text_8'];?></option>
                  <option <?php if($smtp_secure == "tls"){echo 'selected';}?> value="tls">TLS</option>
                  <option <?php if($smtp_secure == "ssl"){echo 'selected';}?> value="ssl">SSL</option>
                </select>
              </div>
			  
			  
			  </p>
            </div>
			
			<div id="4" class="tab-pane fade">
              <p>
			  	<div style="border-bottom:solid 2px #000;"><a href="https://www.clickatell.com" target="_blank"><img src="<?php echo WEB_URL.'img/click-a-tel.png'; ?>"></a></div>
			<br/>
			  <div class="form-group">
					<label for="cat_username"><?php echo $_data['text_25'];?> :</label>
					<input type="text" name="cat_username" id="cat_username" value="<?php echo $cat_username; ?>" class="form-control" />
              </div>
			  
			  <div class="form-group">
                <label for="cat_password"><?php echo $_data['text_26'];?> :</label>
                <input type="text" name="cat_password" id="cat_password" value="<?php echo $cat_password; ?>" class="form-control" />
              </div>
			  
			  <div class="form-group">
                <label for="cat_apikey"><?php echo $_data['text_27'];?> :</label>
                <input type="text" name="cat_apikey" id="cat_apikey" value="<?php echo $cat_apikey; ?>" class="form-control" />
              </div>
			  
			  
			  </p>
			</div>
			
			
          </div>
          <div class="form-group pull-right">
            <input type="submit" name="submit" class="btn btn-success" value="<?php echo $button_text; ?>"/>
          </div>
        </div>
      </form>
    </div>
    <!-- /.box -->
  </div>
</div>
<?php 
mysqli_close($link);
$link = NULL;
?>
<!-- /.row -->
<script type="text/javascript">
  function deleteYear(Id){
  	var iAnswer = confirm("Are you sure you want to delete this Year ?");
	if(iAnswer){
		window.location = '<?php echo WEB_URL;?>setting/year_setup.php?delid=' + Id;
	}
  }
  </script>
<?php include('../footer.php'); ?>
