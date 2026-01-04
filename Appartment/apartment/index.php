<?php
ob_start();
session_start();
define('DIR_APPLICATION', str_replace('\'', '/', realpath(dirname(__FILE__))) . '/');
if(!file_exists("config.php")){
	header("Location: install/index.php");
	die();
}
unset($_SESSION['step']);
unset($_SESSION['domain']);
include(DIR_APPLICATION."config.php");
include(DIR_APPLICATION."library/maxPower.php");
include(DIR_APPLICATION."library/encryption.php");
include(DIR_APPLICATION."library/helper.php");
$converter = new Encryption;
$helper = new ams_helper;
$helper->removeInstallFolder(ROOT_PATH.'install');

//redirect if session already exist
if(isset($_SESSION['objLogin']) && !empty($_SESSION['objLogin'])){
	if($_SESSION['login_type'] == '1' || $_SESSION['login_type'] == '5'){
		header("Location: dashboard.php");
		die();
	}
	else if($_SESSION['login_type'] == '2'){
		header("Location: o_dashboard.php");
		die();
	}
	else if($_SESSION['login_type'] == '3'){
		header("Location: e_dashboard.php");
		die();
	}
	else if($_SESSION['login_type'] == '4'){
		header("Location: t_dashboard.php");
		die();
	}
}
/*****************************************************************************/
/////////////variables/////////////////////////////////////////////////////////
$msg = false;
$sql = '';
$cookie_name = "ams_lang_code";
//////////////////////////////////////////////////////////////////////////////

if(isset($_POST['username']) && $_POST['username'] != '' && isset($_POST['password']) && $_POST['password'] != ''){
	$user_name = trim(make_safe($link, $_POST['username'])); //Escaping Strings
	$password = $converter->encode(trim(make_safe($link, $_POST['password']))); //Escaping Strings
	//admin
	if($_POST['ddlLoginType'] == '1'){
		$sql = mysqli_query($link,"SELECT *,b.* FROM tbl_add_admin aa left join tblbranch b on b.branch_id = aa.branch_id WHERE aa.email = '".$user_name."' and aa.password = '".$password."'");
	}
	//owner
	if($_POST['ddlLoginType'] == '2'){
		$sql = mysqli_query($link, "SELECT *,b.* FROM tbl_add_owner o left join tblbranch b on b.branch_id = o.branch_id WHERE o.o_email = '".$user_name."' and o.o_password = '".$password."'");
	}
	//employee
	if($_POST['ddlLoginType'] == '3'){
		$sql = mysqli_query($link, "SELECT *,b.* FROM tbl_add_employee e left join tblbranch b on b.branch_id = e.branch_id WHERE e.e_email = '".$user_name."' and e.e_password = '".$password."'");
	}
	//renter
	if($_POST['ddlLoginType'] == '4'){
		$sql = mysqli_query($link, "SELECT *,b.* FROM tbl_add_rent ad left join tblbranch b on b.branch_id = ad.branch_id WHERE ad.r_email = '".$user_name."' and ad.r_password = '".$password."'");
	}
	//super admin
	if($_POST['ddlLoginType'] == '5'){
		$sql = mysqli_query($link, "SELECT * FROM tblsuper_admin WHERE email = '".$user_name."' and password = '".$password."'");
	}
	if(!empty($sql)){
		if($row = mysqli_fetch_assoc($sql)){
			if($_POST['ddlLoginType'] == '5'){
				$branch_list = getBuildingDetails($_POST['ddlBranch'], $link);
				$arr = array(
					'user_id'				=> $row['user_id'],
					'name'					=> $row['name'],
					'email'					=> $row['email'],
					'contact'				=> $row['contact'],
					'password'				=> $_POST['password'],
					'added_date'			=> $row['added_date']
				);
				$arr = array_merge($arr, $branch_list);
				$_SESSION['objLogin'] = $arr;
			}
			else{
				$_SESSION['objLogin'] = $row;
			}
			
			mysqli_close($link);
			$link = NULL;
			
			$_SESSION['login_type'] = $_POST['ddlLoginType'];
			if($_POST['ddlLoginType'] == '1' || $_POST['ddlLoginType'] == '5'){
				header("Location: dashboard.php");
				die();
			}
			else if($_POST['ddlLoginType'] == '2'){
				header("Location: o_dashboard.php");
				die();
			}
			else if($_POST['ddlLoginType'] == '3'){
				header("Location: e_dashboard.php");
				die();
			}
			else if($_POST['ddlLoginType'] == '4'){
				header("Location: t_dashboard.php");
				die();
			}	
		} else {
			$msg = true;
		}
	}
	else{
		$msg = true;
	}
}
//
function getBuildingDetails($bid, $link){
	$sql = mysqli_query($link, "SELECT * from tblbranch WHERE branch_id = ".(int)$bid);
	if($row = mysqli_fetch_assoc($sql)){
		return $row;
	}
	return array();
}
function make_safe($con, $variable){
	$variable = mysqli_real_escape_string($con, strip_tags(trim($variable)));
	return $variable; 
}
$query_ams_settings = mysqli_query($link,"SELECT * FROM tbl_settings");
if($row_query_ams_core = mysqli_fetch_array($query_ams_settings)){
	$lang_code_global = $row_query_ams_core['lang_code'];
	$global_currency = $row_query_ams_core['currency'];
	$currency_position = $row_query_ams_core['currency_position'];
	$currency_sep = $row_query_ams_core['currency_seperator'];
}
$lang_code = "English";
if(isset($_COOKIE[$cookie_name]) && !empty($_COOKIE[$cookie_name])) {
	$lang_code = $_COOKIE[$cookie_name];
	$lang_code_global = $_COOKIE[$cookie_name];
}
include(ROOT_PATH.'language/'.$lang_code_global.'/lang_index.php');
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
<!-- SCRIPTS -AT THE BOTOM TO REDUCE THE LOAD TIME-->
<!-- JQUERY SCRIPTS -->
<script src="assets/js/jquery-1.10.2.js"></script>
<script src="assets/js/bootstrap.min.js"></script>
<script src="assets/js/siteperformance.js"></script>
<!-- BOOTSTRAP SCRIPTS -->
</head>
<body>
<div class="container"> <br/>
  <br/>
  <br/>
  <div class="row text-center ">
    <div class="col-md-12"><br/>
      <span style="font-size:50px;font-weight:bold;color:#fff;"><?php echo $_data['application_heading_1']; ?></span> <span style="font-size:18px;color:#fff;"><?php echo $_data['application_heading_2']; ?></span></div>
  </div>
  <br/>
  <div class="row ">
    <div class="col-md-4 col-md-offset-4 col-sm-6 col-sm-offset-3 col-xs-10 col-xs-offset-1">
      <?php if($msg) { ?>
	  <div id="login_error" align="center"><?php echo $_data['wrong_login_msg']; ?></div>
	  <?php } ?>
      <div class="panel panel-success" id="loginBox">
        <div class="panel-heading">  <?php echo $_data['enter_login_details']; ?></div>
        <div class="panel-body">
          <form onSubmit="return validationForm();" role="form" id="form" method="post">
            <br />
            <div class="form-group input-group"> <span class="input-group-addon"><i class="fa fa-envelope-o"></i></span>
              <input type="email" name="username" id="username" class="form-control" placeholder="<?php echo $_data['your_email']; ?>" />
            </div>
            <div class="form-group input-group"> <span class="input-group-addon"><i class="fa fa-key"></i></span>
              <input type="password" name="password" id="password" class="form-control"  placeholder="<?php echo $_data['your_password']; ?>" />
            </div>
            <div class="form-group input-group"> <span class="input-group-addon"><i class="fa fa-users"></i></span>
              <select name="ddlLoginType" onChange="mewhat(this.value);" id="ddlLoginType" class="form-control">
                <option value="">--<?php echo $_data['select_type']; ?>--</option>
                <option value="1"><?php echo $_data['user_1']; ?></option>
                <option value="2"><?php echo $_data['user_2']; ?></option>
                <option value="3"><?php echo $_data['user_3']; ?></option>
                <option value="4"><?php echo $_data['user_4']; ?></option>
                <option value="5"><?php echo $_data['user_5']; ?></option>
              </select>
            </div>
             <div id="x_branch" style="display:none;" class="form-group input-group"> <span class="input-group-addon"><i class="fa fa-random"  ></i></span>
              <select class="form-control" name="ddlBranch" id="ddlBranch">
              <option value="">--<?php echo $_data['select_branch']; ?>--</option>
              <?php 
				  	$result_branch = mysqli_query($link,"SELECT * FROM tblbranch where b_status = 1 order by branch_name ASC");
					while($row_branch = mysqli_fetch_array($result_branch)){?>
              <option value="<?php echo $row_branch['branch_id'];?>"><?php echo $row_branch['branch_name'];?></option>
              <?php } ?>
            </select>
            </div>
			<div class="form-group input-group"> <span class="input-group-addon"><i class="fa fa-flag-o"></i></span>
              <select name="ddlLanguage" id="ddlLanguage" class="form-control">
                <option value="">--<?php echo $_data['select_language']; ?>--</option>
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
              <label class="checkbox-inline"> </label>
              <span class="pull-right"> <a href="<?php echo WEB_URL;?>forgetpassword.php" ><?php echo $_data['forgot_password'];?> </a> </span> </div>
            <hr />
            <div align="center">
              <button style="width:100%;background-color:#00a65a !important;border-color:#00a65a !important;" type="submit" id="login" class="btn btn-success"><i class="fa fa-key"  ></i>&nbsp;<?php echo $_data['_login'];?></button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
<?php if(!maxPower::verifyInstalledDomain($link, true)){ ?>
<div class="modal fade" id="modalVerify" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title" id="exampleModalLabel">Unregistered Version Alert !!!!</h4>
      </div>
      <div class="modal-body">
		This version is unregistered and you need to buy our license version to continue using our application. Another thing if you try to use same application for multiple domain you can loose your system and important information so kindly use valid license version. <a href="https://codecanyon.net/item/responsive-apartment-management-system/16343942" target="_blank">Click here</a> to purchase our application and follow our installation step or contact with me via email address <a href="mailto:devsolver@gmail.com">devsolver@gmail.com</a> for any help or customization.
      </div>
    </div>
  </div>
</div>
<?php } ?>
<script type="text/javascript">
function validationForm(){
	if($("#username").val() == ''){
		alert("<?php echo $_data['v1'];?>");
		$("#username").focus();
		return false;
	}
	else if($("#password").val() == ''){
		alert("<?php echo $_data['v3'];?>");
		$("#password").focus();
		return false;
	}
	else if($("#ddlLoginType").val() == ''){
		alert("<?php echo $_data['v4'];?>");
		return false;
	}
	else if(!validateEmail($("#username").val())){
		alert("<?php echo $_data['v2'];?>");
		$("#username").focus();
		return false;
	}
	else{
		return true;
	}
}
function validateEmail(email) {
    var re = /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    return re.test(email);
}

function mewhat(val){
	if(val != ''){
		if(val == '5'){
			$("#x_branch").show();
		}
		else{
			$("#x_branch").hide();
		}
	}
	else{
		$("#x_branch").hide();
	}
}
</script>
<input type="hidden" id="web_url" value="<?php echo WEB_URL; ?>" />
</body>
</html>
