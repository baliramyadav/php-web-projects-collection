<?php
include("config.php");
include("library/encryption.php");
$converter = new Encryption;
$msg = false;
$sql = '';
///////////////////////////////////////////////////////////////////////////////
$query_ams_settings = mysqli_query($link,"SELECT * FROM tbl_settings");
if($row_query_ams_core = mysqli_fetch_array($query_ams_settings)){
	$lang_code_global = $row_query_ams_core['lang_code'];
	$global_currency = $row_query_ams_core['currency'];
	$currency_position = $row_query_ams_core['currency_position'];
	$currency_sep = $row_query_ams_core['currency_seperator'];
}

include(ROOT_PATH.'language/'.$lang_code_global.'/lang_index.php');

$xMsg = $_data['no_info_found'];
if(isset($_POST['username']) && !empty($_POST['username'])){
	$user_name = make_safe($link, $_POST['username']); //Escaping Strings
	//
	$password = '';
	$name = '';
	//
	if($_POST['ddlLoginType'] == '1'){
		//here for admin
		$sql = mysqli_query($link, "SELECT * FROM tbl_add_admin WHERE email = '".$user_name."'");
		if($row = mysqli_fetch_assoc($sql)){
			$password = $converter->decode($row['password']);
			$name = $row['name'];
		}
	}
	else if($_POST['ddlLoginType'] == '2'){
		//here for owner
		$sql = mysqli_query($link, "SELECT * FROM tbl_add_owner WHERE o_email = '".$user_name."'");
		if($row = mysqli_fetch_assoc($sql)){
			$password = $converter->decode($row['o_password']);
			$name = $row['o_name'];
		}
	}
	else if($_POST['ddlLoginType'] == '3'){
		//here for employee
		$sql = mysqli_query($link, "SELECT * FROM tbl_add_employee WHERE e_email = '".$user_name."'");
		if($row = mysqli_fetch_assoc($sql)){
			$password = $converter->decode($row['e_password']);
			$name = $row['e_name'];
		}
	}
	else if($_POST['ddlLoginType'] == '4'){
		//here for renter
		$sql = mysqli_query($link, "SELECT * FROM tbl_add_rent WHERE r_email = '".$user_name."'");
		if($row = mysqli_fetch_assoc($sql)){
			$password = $converter->decode($row['r_password']);
			$name = $row['r_name'];
		}
	}
	else if($_POST['ddlLoginType'] == '5'){
		//here for renter
		$sql = mysqli_query($link, "SELECT * FROM tblsuper_admin WHERE email = '".$user_name."'");
		if($row = mysqli_fetch_assoc($sql)){
			$password = $converter->decode($row['password']);
			$name = $row['name'];
		}
	}
	if(!empty($password)){
		$xMsg = $_data['send_email_text'];
		$msg = true;
		$query_set = mysqli_query($link, "SELECT * FROM tblsuper_admin");
		if($row = mysqli_fetch_assoc($query_set)){
			$replyEmail = $row['email'];
			//email body
			$to = trim($_POST['username']);
			$subject = 'User Login Details';
			$headers = "From: " . strip_tags($replyEmail) . "\r\n";
			$headers .= "Reply-To: ". strip_tags($replyEmail) . "\r\n";
			$headers .= "MIME-Version: 1.0\r\n";
			$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
			$variables = array(
				'subject'	=> $subject,
				'message'	=> $msg,
				'site_url'	=> WEB_URL,
				'user_name'	=> $_POST['username'],
				'password'	=> $password,
				'name'		=> $name
			);
			$message = loadForgotEmailTemplate('tmp_forget.html', $variables);
			mail($to, $subject, $message, $headers);
		}
	}
	else{
		$msg = true;
	}
}
function make_safe($con, $variable){
	$variable = mysqli_real_escape_string($con, strip_tags(trim($variable)));
	return $variable; 
}
function loadForgotEmailTemplate($temp_name, $variables = array()) {
	$template = file_get_contents(ROOT_PATH."partial/email_templates/".$temp_name);
	foreach($variables as $key => $value){
		$template = str_replace('{{ '.$key.' }}', $value, $template);
	}
	return $template;
}
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<title><?php echo $_data['application_title']; ?></title>
<!-- BOOTSTRAP STYLES-->
<link href="assets/css/bootstrap.css" rel="stylesheet" />
<!-- FONTAWESOME STYLES-->
<link href="assets/css/font-awesome.css" rel="stylesheet" />
<!-- CUSTOM STYLES-->
<link href="assets/css/custom.css" rel="stylesheet" />
<!-- GOOGLE FONTS-->
<link href="https://fonts.googleapis.com/css?family=Roboto+Condensed" rel="stylesheet">
</head>
<body>
<div class="container">
  <br/><br/><br/><br/>
  <div class="row text-center ">
    <div class="col-md-12"><br/>
      <span style="font-size:50px;font-weight:bold;color:#fff;"><?php echo $_data['application_heading_1']; ?></span> <span style="font-size:18px;color:#fff;"><?php echo $_data['application_heading_2']; ?></span></div>
  </div>
  <br/>
  <div class="row ">
    <div class="col-md-4 col-md-offset-4 col-sm-6 col-sm-offset-3 col-xs-10 col-xs-offset-1">
      <?php if($msg) { ?>
	  <div id="login_error" align="center"><?php echo $xMsg; ?></div>
	  <?php } ?>
      <div class="panel panel-default" id="loginBox">
        <div class="panel-heading"> <?php echo $_data['forgot_your_password']; ?> </div>
        <div class="panel-body">
          <form role="form" id="form" method="post">
            <br />
            <div class="form-group input-group"> <span class="input-group-addon"><i class="fa fa-envelope-o"></i></span>
              <input type="email" name="username" id="username" class="form-control" placeholder="<?php echo $_data['your_email']; ?>" required />
            </div>
            <div class="form-group input-group"> <span class="input-group-addon"><i class="fa fa-users"></i></span>
              <select name="ddlLoginType" id="ddlLoginType" class="form-control" required>
                <option value="">--<?php echo $_data['select_type']; ?>--</option>
                <option value="1"><?php echo $_data['user_1']; ?></option>
                <option value="2"><?php echo $_data['user_2']; ?></option>
                <option value="3"><?php echo $_data['user_3']; ?></option>
                <option value="4"><?php echo $_data['user_4']; ?></option>
                <option value="5"><?php echo $_data['user_5']; ?></option>
              </select>
            </div>
			<hr/>
            <div class="form-group">
              <button style="width:100%;background-color:#00a65a !important;border-color:#00a65a !important;" type="submit" id="login" class="btn btn-primary"><?php echo $_data['btn_submit']; ?></button>
            </div>
            <div class="form-group"> <a style="width:100%;background-color:orange !important;" type="submit" id="login" class="btn btn-warning" href="<?php echo WEB_URL;?>index.php"><i class="fa fa-reply" aria-hidden="true"></i>&nbsp; <?php echo $_data['btn_back_login']; ?></a> </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
<!-- SCRIPTS -AT THE BOTOM TO REDUCE THE LOAD TIME-->
<!-- JQUERY SCRIPTS -->
<script src="assets/js/jquery-1.10.2.js"></script>
<!-- BOOTSTRAP SCRIPTS -->
<script src="assets/js/bootstrap.min.js"></script>
</body>
</html>
