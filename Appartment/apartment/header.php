<?php 
ob_start();
session_start();
include(__DIR__ . "/config.php");
$page_name = '';
$lang_code_global = "English";
$global_currency = "$";
$currency_position = "left";
$currency_sep = ".";
$localization = array();
$cookie_name = "ams_lang_code";
$cookie_name_branch = "ams_branch_code";

if(!isset($_SESSION['objLogin'])){
	header("Location: ".WEB_URL."logout.php");
	die();
}
//
if(isset($_SESSION['login_type']) && ((int)$_SESSION['login_type'] != 5 && (int)$_SESSION['login_type'] != 1)){
	header("Location: ".WEB_URL."logout.php");
	die();
}
$super_admin_image = 'img/no_image.jpg';
$query_ams_settings = mysqli_query($link,"SELECT * FROM tbl_settings");
if($row_query_ams_core = mysqli_fetch_array($query_ams_settings)){
	$localization = $row_query_ams_core;
	$lang_code_global = $row_query_ams_core['lang_code'];
	$global_currency = $row_query_ams_core['currency'];
	$currency_position = $row_query_ams_core['currency_position'];
	$currency_sep = $row_query_ams_core['currency_seperator'];
	if($row_query_ams_core['super_admin_image'] != ''){
		$super_admin_image = WEB_URL . 'img/upload/' . $row_query_ams_core['super_admin_image'];
	}
}
//set lang from cookie
if(isset($_COOKIE[$cookie_name]) && !empty($_COOKIE[$cookie_name])) {
	$lang_code_global = $_COOKIE[$cookie_name];
}

//set branch from cookie
if(isset($_COOKIE[$cookie_name_branch]) && !empty($_COOKIE[$cookie_name_branch])) {
	$_SESSION['objLogin']['branch_id'] = $_COOKIE[$cookie_name_branch];
}

include(ROOT_PATH.'language/'.$lang_code_global.'/lang_left_menu.php');
include(ROOT_PATH.'language/'.$lang_code_global.'/lang_common.php');
include(ROOT_PATH.'library/helper.php');
include(ROOT_PATH."library/maxPower.php");
//instance 
$ams_helper = new ams_helper();

////////////////////////////////////////////APARTMENT DETAILS/////////////////////////////////////////////////////////////////////////////////////
$building_rules = '';
$building_name = '';
$moderator_mobile = '';
$secrataty_mobile = '';
$security_guard_mobile = '';
$result_apartment = mysqli_query($link,"SELECT * FROM tblbranch where branch_id=".(int)$_SESSION['objLogin']['branch_id']);
if($row_apartment = mysqli_fetch_array($result_apartment)){
	$building_rules = $row_apartment['building_rule'];
	$building_name = $row_apartment['branch_name'];
	$moderator_mobile = $row_apartment['moderator_mobile'];
	$secrataty_mobile = $row_apartment['secrataty_mobile'];
	$security_guard_mobile = $row_apartment['security_guard_mobile'];
}
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

////////////////////admin image/////////////////////////
$image = WEB_URL . 'img/no_image.jpg';	
if(isset($_SESSION['objLogin']['image'])){
	if(file_exists(ROOT_PATH . '/img/upload/' . $_SESSION['objLogin']['image']) && $_SESSION['objLogin']['image'] != ''){
		$image = WEB_URL . 'img/upload/' . $_SESSION['objLogin']['image'];
	}
}
if(isset($_SESSION['login_type']) && ((int)$_SESSION['login_type'] == 5)){
	$image = $super_admin_image;
}
/////////////////////////////////////////////////////////////

include(ROOT_PATH."library/encryption.php");
$converter = new Encryption;
$page_name = $ams_helper->curPageUrlInfo('page');
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title><?php echo $building_name; ?></title>
<meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
<?php include(ROOT_PATH.'/partial/header_script.php'); ?>
</head>
<body class="skin-green sidebar-mini">
<!-- Site wrapper -->
<div class="wrapper">
<header class="main-header">
  <!-- Logo -->
  <a class="logo">
  <!-- mini logo for sidebar mini 50x50 pixels -->
  <span class="logo-mini"><i class="fa fa-bank"></i></span> <span class="logo-lg" style="text-transform:uppercase;font-weight:bold;overflow:hidden;"><i class="fa fa-bank"></i> <?php echo $building_name; ?> </a>
  <!-- Header Navbar: style can be found in header.less -->
  <nav class="navbar navbar-static-top" role="navigation">
    <!-- Sidebar toggle button-->
    <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button"> <span class="sr-only">Toggle navigation</span> <span class="icon-bar"></span> <span class="icon-bar"></span> <span class="icon-bar"></span> </a>
    <div class="navbar-custom-menu">
      <ul class="nav navbar-nav">
        <li class="hidden-xs" style="background:#000;"> <a href="javascript:;" data-toggle="modal" data-target="#available_room"><i style="font-size:18px;" class="fa fa-home"></i></a> </li>
		<li class="hidden-xs" style="background:#d73925;"> <a href="<?php echo WEB_URL; ?>visitor/visitorlist.php"><i style="font-size:18px;" class="fa fa-user-o"></i></a> </li>
		<li class="hidden-xs" style="background:#00c0ef;"> <a href="<?php echo WEB_URL; ?>complain/complainlist.php"><i style="font-size:18px;" class="fa fa-comments"></i></a> </li>
		<li class="hidden-xs" style="background:#e08e0b;"> <a href="<?php echo WEB_URL; ?>mailsms/mailsms.php"><i style="font-size:18px;" class="fa fa-envelope-o"></i></a> </li>
		<!-- User Account: style can be found in dropdown.less -->
        <li class="dropdown user user-menu"> <a href="#" class="dropdown-toggle" data-toggle="dropdown"> <img class="user-image" src="<?php echo !empty($image) ? $image : 'img/no_image.jpg'; ?>"> <span class="hidden-xs"> <?php echo $_SESSION['objLogin']['name']; ?> </span> </a>
          <ul class="dropdown-menu">
            <!-- User image -->
            <li class="user-header"> <img src="<?php echo $image; ?>" class="img-circle" alt="User Image" />
              <p> <?php echo $_SESSION['objLogin']['name']; ?> <small>
                <?php if($_SESSION['login_type'] == '1'){echo $_data['user_admin'];} else if($_SESSION['login_type'] == '2'){echo $_data['user_owner'];} else if($_SESSION['login_type'] == '3'){echo $_data['user_employee'];} else if($_SESSION['login_type'] == '4'){echo $_data['user_tenant'];} else {echo $_data['user_super_admin'];}?>
                <br/>
                <span style="font-size:14px;color:#fff;"><?php echo $building_name; ?><span></p>
              </small> </li>
            <!-- Menu Body -->
            <!-- Menu Footer-->
            <li class="user-footer">
              <div class="col-md-12" style="margin-bottom: 10px;">
                <div style="margin-bottom:7px;"><a href="javascript:;" data-toggle="modal" data-target="#switch_language" class="btn btn-info btn-flat" style="width:100%"><?php echo $_data['user_lang_switch']; ?></a></div>
                <?php if((int)$_SESSION['login_type'] == 5){ ?><div><a href="javascript:;" data-toggle="modal" data-target="#switch_branch" class="btn btn-warning btn-flat" style="width:100%"><?php echo $_data['user_branch_switcher']; ?></a></div><?php } ?>
              </div>
              <div class="pull-left"><a data-target="#user_profile" data-toggle="modal" class="btn btn-success btn-flat"><?php echo $_data['lang_profile']; ?></a></div>
              <div class="pull-right"> <a href="<?php echo WEB_URL; ?>logout.php" class="btn btn-danger btn-flat"><?php echo $_data['lang_signout']; ?></a> </div>
            </li>
          </ul>
        </li>
        <?php if((int)$_SESSION['login_type'] == 5){ ?>
        <li class="hidden-xs"> <a href="<?php echo WEB_URL; ?>setting/language.php"><i class="fa fa-gears"></i></a> </li>
        <?php } ?>
      </ul>
    </div>
  </nav>
</header>
<!-- =============================================== -->
<!-- Left side column. contains the sidebar -->
<aside class="main-sidebar">
  <!-- sidebar: style can be found in sidebar.less -->
  <div class="user-panel" style="background:#000;">
    <div class="pull-left image"> <img src="<?php echo $image; ?>" class="img-circle" alt="User Image"> </div>
    <div class="pull-left info">
      <p><?php echo $_SESSION['objLogin']['name']; ?></p>
      <a href="#"><i class="fa fa-circle text-success" style="color:#00a65a !important;"></i> <?php echo $_data['lang_online']; ?></a> </div>
  </div>
  <section class="sidebar">
    <!-- sidebar menu: : style can be found in sidebar.less -->
    <ul class="sidebar-menu">
      <li class="<?php if($page_name != '' && $page_name == 'dashboard'){echo 'active';}?>"><a href="<?php echo WEB_URL; ?>dashboard.php"><i class="fa fa-line-chart"></i> <span><?php echo $_data['menu_dashboard']; ?></span></a> </li>
      <li class="treeview <?php if($page_name != '' && $page_name == 'addfloor' || $page_name == 'floorlist'){echo 'active';}?>"> <a href="#"> <i class="fa fa-clone"></i> <span><?php echo $_data['menu_floor']; ?></span> <i class="fa fa-angle-left pull-right"></i> </a>
        <ul class="treeview-menu">
          <li class="<?php if($page_name != '' && $page_name == 'floorlist'){echo 'active';}?>"><a href="<?php echo WEB_URL; ?>floor/floorlist.php"><i class="fa fa-angle-double-right"></i><?php echo $_data['menu_floor_list']; ?></a></li>
          <li class="<?php if($page_name != '' && $page_name == 'addfloor'){echo 'active';}?>"><a href="<?php echo WEB_URL; ?>floor/addfloor.php"><i class="fa fa-angle-double-right"></i><?php echo $_data['menu_floor_add']; ?></a></li>
        </ul>
      </li>
      <li class="treeview <?php if($page_name != '' && $page_name == 'addunit' || $page_name == 'unitlist'){echo 'active';}?>"> <a href="#"> <i class="fa fa-cubes"></i> <span><?php echo $_data['menu_unit_information']; ?></span> <i class="fa fa-angle-left pull-right"></i> </a>
        <ul class="treeview-menu">
          <li class="<?php if($page_name != '' && $page_name == 'unitlist'){echo 'active';}?>"><a href="<?php echo WEB_URL; ?>unit/unitlist.php"><i class="fa fa-angle-double-right"></i><?php echo $_data['menu_unit_list']; ?></a></li>
          <li class="<?php if($page_name != '' && $page_name == 'addunit'){echo 'active';}?>"><a href="<?php echo WEB_URL; ?>unit/addunit.php"><i class="fa fa-angle-double-right"></i><?php echo $_data['menu_add_unit']; ?></a></li>
        </ul>
      </li>
      <li class="treeview <?php if($page_name != '' && $page_name == 'addowner' || $page_name == 'ownerlist'){echo 'active';}?>"> <a href="#"> <i class="fa fa-user-o"></i> <span><?php echo $_data['menu_owner_information']; ?></span> <i class="fa fa-angle-left pull-right"></i> </a>
        <ul class="treeview-menu">
          <li class="<?php if($page_name != '' && $page_name == 'ownerlist'){echo 'active';}?>"><a href="<?php echo WEB_URL; ?>owner/ownerlist.php"><i class="fa fa-angle-double-right"></i><?php echo $_data['menu_owner_list']; ?></a></li>
          <li class="<?php if($page_name != '' && $page_name == 'addowner'){echo 'active';}?>"><a href="<?php echo WEB_URL; ?>owner/addowner.php"><i class="fa fa-angle-double-right"></i><?php echo $_data['menu_add_owner']; ?></a></li>
        </ul>
      </li>
      <li class="treeview <?php if($page_name != '' && $page_name == 'addrent' || $page_name == 'rentlist'){echo 'active';}?>"> <a href="#"> <i class="fa fa-users"></i> <span><?php echo $_data['menu_renter_information']; ?></span> <i class="fa fa-angle-left pull-right"></i> </a>
        <ul class="treeview-menu">
          <li class="<?php if($page_name != '' && $page_name == 'rentlist'){echo 'active';}?>"><a href="<?php echo WEB_URL; ?>rent/rentlist.php"><i class="fa fa-angle-double-right"></i><?php echo $_data['menu_renter_list']; ?></a></li>
          <li class="<?php if($page_name != '' && $page_name == 'addrent'){echo 'active';}?>"><a href="<?php echo WEB_URL; ?>rent/addrent.php"><i class="fa fa-angle-double-right"></i><?php echo $_data['menu_add_renter']; ?></a></li>
        </ul>
      </li>
      <li class="treeview <?php if($page_name != '' && $page_name == 'addemployee' || $page_name == 'employeelist' || $page_name == 'employee_salary_setup' || $page_name == 'leave_request_list'){echo 'active';}?>"> <a href="#"> <i class="fa fa-user" aria-hidden="true"></i> <span><?php echo $_data['menu_employee_information']; ?></span> <i class="fa fa-angle-left pull-right"></i> </a>
        <ul class="treeview-menu">
          <li class="<?php if($page_name != '' && $page_name == 'employeelist'){echo 'active';}?>"><a href="<?php echo WEB_URL; ?>employee/employeelist.php"><i class="fa fa-angle-double-right"></i><?php echo $_data['menu_employee_list']; ?></a></li>
          <li class="<?php if($page_name != '' && $page_name == 'addemployee'){echo 'active';}?>"><a href="<?php echo WEB_URL; ?>employee/addemployee.php"><i class="fa fa-angle-double-right"></i><?php echo $_data['menu_add_employee']; ?></a></li>
          <li class="<?php if($page_name != '' && $page_name == 'employee_salary_setup'){echo 'active';}?>"><a href="<?php echo WEB_URL; ?>employee/employee_salary_setup.php"><i class="fa fa-angle-double-right"></i><?php echo $_data['menu_employee_setup'];?></a></li>
          <li class="<?php if($page_name != '' && $page_name == 'leave_request_list'){echo 'active';}?>"><a href="<?php echo WEB_URL; ?>employee/leave_request_list.php"><i class="fa fa-angle-double-right"></i><?php echo $_data['menu_employee_leave'];?></a></li>
        </ul>
      </li>
      <li class="treeview <?php if($page_name != '' && $page_name == 'addfair' || $page_name == 'fairlist'){echo 'active';}?>"> <a href="#"> <i class="fa fa-money"></i> <span><?php echo $_data['menu_rent_collection']; ?></span> <i class="fa fa-angle-left pull-right"></i> </a>
        <ul class="treeview-menu">
          <li class="<?php if($page_name != '' && $page_name == 'fairlist'){echo 'active';}?>"><a href="<?php echo WEB_URL; ?>fair/fairlist.php"><i class="fa fa-angle-double-right"></i><?php echo $_data['menu_rent_list']; ?></a></li>
          <li class="<?php if($page_name != '' && $page_name == 'addfair'){echo 'active';}?>"><a href="<?php echo WEB_URL; ?>fair/addfair.php"><i class="fa fa-angle-double-right"></i><?php echo $_data['menu_add_rent']; ?></a></li>
        </ul>
      </li>
      <li class="treeview <?php if($page_name != '' && $page_name == 'add_owner_utility' || $page_name == 'owner_utility_list'){echo 'active';}?>"> <a href="#"> <i class="fa fa-gear"></i> <span><?php echo $_data['menu_owner_utility']; ?></span> <i class="fa fa-angle-left pull-right"></i> </a>
        <ul class="treeview-menu">
          <li class="<?php if($page_name != '' && $page_name == 'owner_utility_list'){echo 'active';}?>"><a href="<?php echo WEB_URL; ?>owner_utility/owner_utility_list.php"><i class="fa fa-angle-double-right"></i><?php echo $_data['menu_owner_utility_list']; ?></a></li>
          <li class="<?php if($page_name != '' && $page_name == 'add_owner_utility'){echo 'active';}?>"><a href="<?php echo WEB_URL; ?>owner_utility/add_owner_utility.php"><i class="fa fa-angle-double-right"></i><?php echo $_data['menu_add_owner_utility']; ?></a></li>
        </ul>
      </li>
      <li class="treeview <?php if($page_name != '' && $page_name == 'add_maintenance_cost' || $page_name == 'maintenance_cost_list'){echo 'active';}?>"> <a href="#"> <i class="fa fa-cut"></i> <span><?php echo $_data['menu_maintenance_cost']; ?></span> <i class="fa fa-angle-left pull-right"></i> </a>
        <ul class="treeview-menu">
          <li class="<?php if($page_name != '' && $page_name == 'maintenance_cost_list'){echo 'active';}?>"><a href="<?php echo WEB_URL; ?>maintenance/maintenance_cost_list.php"><i class="fa fa-angle-double-right"></i><?php echo $_data['menu_maintenance_cost_list']; ?></a></li>
          <li class="<?php if($page_name != '' && $page_name == 'add_maintenance_cost'){echo 'active';}?>"><a href="<?php echo WEB_URL; ?>maintenance/add_maintenance_cost.php"><i class="fa fa-angle-double-right"></i><?php echo $_data['menu_add_maintenance_cost']; ?></a></li>
        </ul>
      </li>
      <li class="treeview <?php if($page_name != '' && $page_name == 'add_m_committee' || $page_name == 'm_committee_list'){echo 'active';}?>"> <a href="#"> <i class="fa fa-user"></i> <span><?php echo $_data['menu_management_committee']; ?></span> <i class="fa fa-angle-left pull-right"></i> </a>
        <ul class="treeview-menu">
          <li class="<?php if($page_name != '' && $page_name == 'm_committee_list'){echo 'active';}?>"><a href="<?php echo WEB_URL; ?>management/m_committee_list.php"><i class="fa fa-angle-double-right"></i><?php echo $_data['menu_member_list']; ?></a></li>
          <li class="<?php if($page_name != '' && $page_name == 'add_m_committee'){echo 'active';}?>"><a href="<?php echo WEB_URL; ?>management/add_m_committee.php"><i class="fa fa-angle-double-right"></i><?php echo $_data['menu_add_member']; ?></a></li>
        </ul>
      </li>
      <li class="treeview <?php if($page_name != '' && $page_name == 'add_fund' || $page_name == 'fund_list'){echo 'active';}?>"> <a href="#"> <i class="fa fa-money"></i> <span><?php echo $_data['menu_fund']; ?></span> <i class="fa fa-angle-left pull-right"></i> </a>
        <ul class="treeview-menu">
          <li class="<?php if($page_name != '' && $page_name == 'fund_list'){echo 'active';}?>"><a href="<?php echo WEB_URL; ?>fund/fund_list.php"><i class="fa fa-angle-double-right"></i><?php echo $_data['menu_fund_list']; ?></a></li>
          <li class="<?php if($page_name != '' && $page_name == 'add_fund'){echo 'active';}?>"><a href="<?php echo WEB_URL; ?>fund/add_fund.php"><i class="fa fa-angle-double-right"></i><?php echo $_data['menu_add_fund']; ?></a></li>
        </ul>
      </li>
      <li class="treeview <?php if($page_name != '' && $page_name == 'add_bill' || $page_name == 'bill_list'){echo 'active';}?>"> <a href="#"> <i class="fa fa-bank"></i> <span><?php echo $_data['menu_bill'];?></span> <i class="fa fa-angle-left pull-right"></i> </a>
        <ul class="treeview-menu">
          <li class="<?php if($page_name != '' && $page_name == 'bill_list'){echo 'active';}?>"><a href="<?php echo WEB_URL; ?>bill/bill_list.php"><i class="fa fa-angle-double-right"></i><?php echo $_data['menu_bill_list'];?></a></li>
          <li class="<?php if($page_name != '' && $page_name == 'add_bill'){echo 'active';}?>"><a href="<?php echo WEB_URL; ?>bill/add_bill.php"><i class="fa fa-angle-double-right"></i><?php echo $_data['menu_add_bill'];?></a></li>
        </ul>
      </li>
      <li class="treeview <?php if($page_name != '' && $page_name == 'addcomplain' || $page_name == 'complainlist'){echo 'active';}?>"> <a href="#"> <i class="fa fa-comments"></i> <span><?php echo $_data['menu_complain'];?></span> <i class="fa fa-angle-left pull-right"></i> </a>
        <ul class="treeview-menu">
          <li class="<?php if($page_name != '' && $page_name == 'complainlist'){echo 'active';}?>"><a href="<?php echo WEB_URL; ?>complain/complainlist.php"><i class="fa fa-angle-double-right"></i><?php echo $_data['menu_complain_list'];?></a></li>
          <li class="<?php if($page_name != '' && $page_name == 'addcomplain'){echo 'active';}?>"><a href="<?php echo WEB_URL; ?>complain/addcomplain.php"><i class="fa fa-angle-double-right"></i><?php echo $_data['menu_add_complain'];?></a></li>
        </ul>
      </li>
      <li class="treeview <?php if($page_name != '' && $page_name == 'addvisitor' || $page_name == 'visitorlist'){echo 'active';}?>"> <a href="#"> <i class="fa fa-car"></i> <span><?php echo $_data['menu_visitor'];?></span> <i class="fa fa-angle-left pull-right"></i> </a>
        <ul class="treeview-menu">
          <li class="<?php if($page_name != '' && $page_name == 'visitorlist'){echo 'active';}?>"><a href="<?php echo WEB_URL; ?>visitor/visitorlist.php"><i class="fa fa-angle-double-right"></i><?php echo $_data['menu_visitor_list'];?></a></li>
          <li class="<?php if($page_name != '' && $page_name == 'addvisitor'){echo 'active';}?>"><a href="<?php echo WEB_URL; ?>visitor/addvisitor.php"><i class="fa fa-angle-double-right"></i><?php echo $_data['menu_add_visitor'];?></a></li>
        </ul>
      </li>
      <li class="treeview <?php if($page_name != '' && $page_name == 'addmeeting' || $page_name == 'meetinglist'){echo 'active';}?>"> <a href="#"> <i class="fa fa-handshake-o"></i> <span><?php echo $_data['menu_meeting'];?></span> <i class="fa fa-angle-left pull-right"></i> </a>
        <ul class="treeview-menu">
          <li class="<?php if($page_name != '' && $page_name == 'meetinglist'){echo 'active';}?>"><a href="<?php echo WEB_URL; ?>meeting/meetinglist.php"><i class="fa fa-angle-double-right"></i><?php echo $_data['menu_meeting_list'];?></a></li>
          <li class="<?php if($page_name != '' && $page_name == 'addmeeting'){echo 'active';}?>"><a href="<?php echo WEB_URL; ?>meeting/addmeeting.php"><i class="fa fa-angle-double-right"></i><?php echo $_data['menu_add_meeting'];?></a></li>
        </ul>
      </li>
      <li class="treeview <?php if($page_name != '' && $page_name == 'notice' || $page_name == 'employee_notice' || $page_name == 'owner_notice'){echo 'active';}?>"> <a href="#"> <i class="fa fa-bullhorn" aria-hidden="true"></i> <span><?php echo $_data['notice_board'];?></span> <i class="fa fa-angle-left pull-right"></i> </a>
        <ul class="treeview-menu">
          <li class="<?php if($page_name != '' && $page_name == 'notice'){echo 'active';}?>"><a href="<?php echo WEB_URL; ?>notice/notice.php"><i class="fa fa-angle-double-right"></i><?php echo $_data['notice_board_1'];?></a></li>
          <li class="<?php if($page_name != '' && $page_name == 'employee_notice'){echo 'active';}?>"><a href="<?php echo WEB_URL; ?>notice/employee_notice.php"><i class="fa fa-angle-double-right"></i><?php echo $_data['notice_board_2'];?></a></li>
          <li class="<?php if($page_name != '' && $page_name == 'owner_notice'){echo 'active';}?>"><a href="<?php echo WEB_URL; ?>notice/owner_notice.php"><i class="fa fa-angle-double-right"></i><?php echo $_data['notice_board_3'];?></a></li>
        </ul>
      </li>
      <li class="treeview <?php if($page_name != '' && $page_name == 'mailsms'){echo 'active';}?>"> <a href="<?php echo WEB_URL; ?>mailsms/mailsms.php"> <i class="fa fa-envelope-o" aria-hidden="true"></i> <span><?php echo $_data['email_sms'];?></span></a></li>
      <li class="treeview <?php if($page_name != '' && $page_name == 'fair_report' || $page_name == 'rented_report' || $page_name == 'visitors_report' || $page_name == 'complain_report' || $page_name == 'unit_report' || $page_name == 'fund_status' || $page_name == 'bill_report' || $page_name == 'salary_report'){echo 'active';}?>"> <a href="#"> <i class="fa fa-bar-chart-o"></i> <span><?php echo $_data['menu_report'];?></span> <i class="fa fa-angle-left pull-right"></i> </a>
        <ul class="treeview-menu">
          <li class="<?php if($page_name != '' && $page_name == 'fair_report'){echo 'active';}?>"><a href="<?php echo WEB_URL; ?>report/fair_report.php"><i class="fa fa-angle-double-right"></i><?php echo $_data['menu_fair_report'];?></a></li>
          <li class="<?php if($page_name != '' && $page_name == 'rented_report'){echo 'active';}?>"><a href="<?php echo WEB_URL; ?>report/rented_report.php"><i class="fa fa-angle-double-right"></i><?php echo $_data['menu_rented_report'];?></a></li>
          <li class="<?php if($page_name != '' && $page_name == 'visitors_report'){echo 'active';}?>"><a href="<?php echo WEB_URL; ?>report/visitors_report.php"><i class="fa fa-angle-double-right"></i><?php echo $_data['menu_visitors_report'];?></a></li>
          <li class="<?php if($page_name != '' && $page_name == 'complain_report'){echo 'active';}?>"><a href="<?php echo WEB_URL; ?>report/complain_report.php"><i class="fa fa-angle-double-right"></i><?php echo $_data['menu_complain_report'];?></a></li>
          <li class="<?php if($page_name != '' && $page_name == 'unit_report'){echo 'active';}?>"><a href="<?php echo WEB_URL; ?>report/unit_report.php"><i class="fa fa-angle-double-right"></i><?php echo $_data['menu_unit_status_report'];?></a></li>
          <li class="<?php if($page_name != '' && $page_name == 'fund_status'){echo 'active';}?>"><a target="_blank" href="<?php echo WEB_URL; ?>report/fund_status.php"><i class="fa fa-angle-double-right"></i><?php echo $_data['menu_fund_status'];?></a></li>
          <li class="<?php if($page_name != '' && $page_name == 'bill_report'){echo 'active';}?>"><a href="<?php echo WEB_URL; ?>report/bill_report.php"><i class="fa fa-angle-double-right"></i><?php echo $_data['menu_bill_report'];?></a></li>
          <li class="<?php if($page_name != '' && $page_name == 'salary_report'){echo 'active';}?>"><a href="<?php echo WEB_URL; ?>report/salary_report.php"><i class="fa fa-angle-double-right"></i><?php echo $_data['salary_report_text'];?></a></li>
        </ul>
      </li>
      
	   <?php if((int)$_SESSION['login_type'] == 5){ ?>
	  <li class="treeview <?php if($page_name != '' && $page_name == 'cleardata' || $page_name == 'visitorlist'){echo 'active';}?>"> <a href="#"> <i class="fa fa-database"></i> <span><?php echo $_data['database_left_menu'];?></span> <i class="fa fa-angle-left pull-right"></i> </a>
        <ul class="treeview-menu">
          <li class="<?php if($page_name != '' && $page_name == 'cleardata'){echo 'active';}?>"><a href="<?php echo WEB_URL; ?>database/cleardata.php"><i class="fa fa-angle-double-right"></i><?php echo $_data['database_clear_dummy_data'];?></a></li>
        </ul>
      </li>
	  <li class="treeview <?php if($page_name != '' && $page_name == 'bill_setup' || $page_name != '' && $page_name == 'utility_bill_setup' || $page_name == 'member_type_setup' || $page_name == 'month_setup' || $page_name == 'year_setup' || $page_name == 'language' || $page_name == 'admin' || $page_name == 'add_building_info' || $page_name == 'branchlist' || $page_name == 'addbranch' || $page_name == 'currency_setup' || $page_name == 'language_setup'){echo 'active';}?>"> <a href="#"> <i class="fa fa-wrench"></i> <span><?php echo $_data['menu_settings'];?></span> <i class="fa fa-angle-left pull-right"></i> </a>
        <ul class="treeview-menu">
          <li class="<?php if($page_name != '' && $page_name == 'admin'){echo 'active';}?>"><a href="<?php echo WEB_URL; ?>setting/admin.php"><i class="fa fa-angle-double-right"></i><?php echo $_data['menu_admin_setup'];?></a></li>
          <li style="display:none;" class="<?php if($page_name != '' && $page_name == 'add_building_info'){echo 'active';}?>"><a href="<?php echo WEB_URL; ?>building/add_building_info.php"><i class="fa fa-angle-double-right"></i><?php echo $_data['menu_building_info'];?></a> </li>
          <li class="<?php if($page_name != '' && $page_name == 'branchlist' || $page_name == 'addbranch'){echo 'active';}?>"><a href="#"><i class="fa fa-angle-double-right"></i><?php echo $_data['branch'];?> <i class="fa fa-angle-left pull-right"></i> </a>
            <ul class="treeview-menu">
              <li class="<?php if($page_name != '' && $page_name == 'branchlist'){echo 'active';}?>"><a href="<?php echo WEB_URL; ?>branch/branchlist.php"><i class="fa fa-angle-double-right"></i><?php echo $_data['branch_list'];?></a></li>
              <li class="<?php if($page_name != '' && $page_name == 'addbranch'){echo 'active';}?>"><a href="<?php echo WEB_URL; ?>branch/addbranch.php"><i class="fa fa-angle-double-right"></i><?php echo $_data['add_branch'];?></a></li>
            </ul>
          </li>
          <li class="<?php if($page_name != '' && $page_name == 'bill_setup'){echo 'active';}?>"><a href="<?php echo WEB_URL; ?>setting/bill_setup.php"><i class="fa fa-angle-double-right"></i><?php echo $_data['menu_bill_setup'];?></a></li>
		  <li class="<?php if($page_name != '' && $page_name == 'utility_bill_setup'){echo 'active';}?>"><a href="<?php echo WEB_URL; ?>setting/utility_bill_setup.php"><i class="fa fa-angle-double-right"></i><?php echo $_data['menu_utility_bill'];?></a></li>
          <li class="<?php if($page_name != '' && $page_name == 'member_type_setup'){echo 'active';}?>"><a href="<?php echo WEB_URL; ?>setting/member_type_setup.php"><i class="fa fa-angle-double-right"></i><?php echo $_data['menu_management_member_type'];?></a></li>
          <li class="<?php if($page_name != '' && $page_name == 'month_setup'){echo 'active';}?>"><a href="<?php echo WEB_URL; ?>setting/month_setup.php"><i class="fa fa-angle-double-right"></i><?php echo $_data['menu_month_setup'];?></a></li>
          <li class="<?php if($page_name != '' && $page_name == 'year_setup'){echo 'active';}?>"><a href="<?php echo WEB_URL; ?>setting/year_setup.php"><i class="fa fa-angle-double-right"></i><?php echo $_data['menu_year_setup'];?></a></li>
          <li class="<?php if($page_name != '' && $page_name == 'currency_setup'){echo 'active';}?>"><a href="<?php echo WEB_URL; ?>setting/currency_setup.php"><i class="fa fa-angle-double-right"></i><?php echo $_data['menu_currency_setup'];?></a></li>
          <li class="<?php if($page_name != '' && $page_name == 'language'){echo 'active';}?>"><a href="<?php echo WEB_URL; ?>setting/language.php"><i class="fa fa-angle-double-right"></i><?php echo $_data['menu_language_setup'];?></a></li>
        </ul>
      </li>
	   <?php } ?>
    </ul>
  </section>
  <!-- /.sidebar -->
</aside>
<!-- =============================================== -->
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
<!-- Add the sidebar's background. This div must be placed
 immediately after the control sidebar -->
<!--common lang text for js-->
<input type="hidden" id="email_validate_text" value="<?php echo $_data['valid_email'];?>">
<form id="updateprofile" action="<?php echo WEB_URL; ?>ajax/updateProfile.php" method="post">
  <div class="modal fade" role="dialog" id="user_profile" aria-labelledby="gridSystemModalLabel">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title" id="gridSystemModalLabel"><?php echo $_data['profile_update_title']; ?></h4>
        </div>
        <div class="modal-body">
          <?php 
			$image = WEB_URL . 'img/no_image.jpg';	
			if(isset($_SESSION['objLogin']['image'])){
				if(file_exists(ROOT_PATH . '/img/upload/' . $_SESSION['objLogin']['image']) && $_SESSION['objLogin']['image'] != ''){
					$image = WEB_URL . 'img/upload/' . $_SESSION['objLogin']['image'];
				}
			}
			if(isset($_SESSION['login_type']) && ((int)$_SESSION['login_type'] == 5)){
				$image = $super_admin_image;
			}
		  ?>
          <div align="center"><img class="photo_img_round" style="width:100px;height:100px;" src="<?php echo $image;  ?>" /></div>
          <h4 align="center"><?php echo $_SESSION['objLogin']['name']; ?></h4>
          <h5 align="center">
            <?php if($_SESSION['login_type'] == '1'){echo $_data['user_admin'];} else if($_SESSION['login_type'] == '2'){echo $_data['user_owner'];} else if($_SESSION['login_type'] == '3'){echo $_data['user_employee'];} else if($_SESSION['login_type'] == '4'){echo $_data['user_tenant'];} else {echo $_data['user_super_admin'];}?>
          </h5>
          <div class="form-group">
            <label class="control-label"><?php echo $_data['profile_update_name']; ?> :&nbsp;&nbsp;</label>
            <input type="text" class="form-control" id="txtProfileName" name="txtProfileName" value="<?php echo $_SESSION['objLogin']['name']; ?>">
          </div>
          <div class="form-group">
            <label class="control-label"><?php echo $_data['profile_update_email']; ?> :&nbsp;&nbsp;</label>
            <input type="text" class="form-control" id="txtProfileEmail" name="txtProfileEmail" value="<?php echo $_SESSION['objLogin']['email']; ?>">
          </div>
          <div class="form-group">
            <label class="control-label"><?php echo $_data['profile_update_contact']; ?> :&nbsp;&nbsp;</label>
            <input type="text" class="form-control" id="txtProfileContact" name="txtProfileContact" value="<?php echo $_SESSION['objLogin']['contact']; ?>">
          </div>
          <div class="form-group">
            <label class="control-label"><?php echo $_data['profile_update_password']; ?> :&nbsp;&nbsp;</label>
            <input type="text" class="form-control" id="txtProfilePassword" name="txtProfilePassword" value="<?php echo $_SESSION['objLogin']['password']; ?>">
          </div>
          <div style="color:orange;font-weight:bold;text-align:left;font-size:15px;"><?php echo $_data['profile_update_information']; ?></div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-success" onClick="javascript:$('#updateprofile').submit();"><i class="fa fa-save"></i> <?php echo $_data['profile_update_button']; ?></button>
        </div>
      </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
  </div>
  <!-- /.modal -->
  <?php if($_SESSION['login_type'] == '1'){ ?>
  <input type="hidden" name="user_id" value="<?php echo $_SESSION['objLogin']['aid']; ?>" >
  <?php } else { ?>
  <input type="hidden" name="user_id" value="<?php echo $_SESSION['objLogin']['user_id']; ?>" >
  <?php } ?>
</form>
<!-- Language Switch Modal -->
<div class="modal fade" id="switch_language" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-sm">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" align="center"><strong><?php echo $_data['user_lang_switcher']; ?></strong></h4>
      </div>
      <div class="modal-body">
        <?php
			$dir    = ROOT_PATH . 'language/';
			$files1 = scandir($dir);
			foreach($files1 as $folder){
				if($folder != '' && $folder != '.' && $folder != '..'){
					if(trim($folder) == $lang_code_global){
						echo '<button onclick="set_language(this);" lang="'.$folder.'" class="btn btn-default btn-lg btn-lang-selected"><img style="width:24px;" src="'.WEB_URL.'language/'.$folder.'/flag.png"> '.$folder.'</button>&nbsp;&nbsp;&nbsp;';
					}
					else{
						echo '<button onclick="set_language(this);" lang="'.$folder.'" class="btn btn-default btn-lg"><img style="width:24px;" src="'.WEB_URL.'language/'.$folder.'/flag.png"> '.$folder.'</button>&nbsp;&nbsp;&nbsp;';
					}
				}
			}
			?>
      </div>
      <div class="modal-footer"> </div>
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>
<!-- /.modal -->


<!-- Language Switch Modal -->
<div class="modal fade" id="switch_branch" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" align="center"><strong style="text-transform:uppercase;"><?php echo $_data['user_branch_switcher']; ?></strong></h4>
      </div>
      <div class="modal-body clearfix">
        <?php
			$result_branch = mysqli_query($link,"SELECT * FROM tblbranch where b_status = 1 order by branch_name ASC");
			while($row_branch = mysqli_fetch_array($result_branch)){ 
				$img_branch = '';
				if(file_exists(ROOT_PATH . '/img/upload/' . $row_branch['building_image']) && $row_branch['building_image'] != ''){
					$img_branch = WEB_URL . 'img/upload/' . $row_branch['building_image'];
				}
			?>
        <?php if($_SESSION['objLogin']['branch_id']==$row_branch['branch_id']) { ?>
        <div class="col-md-4" style="margin-bottom:20px;border:solid 2px #d73925;padding:15px;cursor:pointer;text-align:center;" onClick="setBranch(<?php echo $row_branch['branch_id']; ?>);">
          <?php if(!empty($img_branch)) { ?>
          <div><img class="img-thumbnail" style="width:200px;height:200px;" src="<?php echo $img_branch; ?>"></div>
          <?php }?>
          <div style="text-align:center;font-weight:bold;font-size:18px;"><?php echo $row_branch['branch_name']; ?></div>
        </div>
        <?php } else { ?>
        <div class="col-md-4" style="margin-bottom:20px;padding:15px;cursor:pointer;text-align:center;" onClick="setBranch(<?php echo $row_branch['branch_id']; ?>);">
          <?php if(!empty($img_branch)) { ?>
          <div><img class="img-thumbnail" style="width:200px;height:200px;" src="<?php echo $img_branch; ?>"></div>
          <?php }?>
          <div style="text-align:center;font-weight:bold;font-size:18px;"><?php echo $row_branch['branch_name']; ?></div>
        </div>
        <?php }?>
        <?php }?>
      </div>
      <div class="modal-footer"> </div>
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>
<!-- /.modal -->

<!-- Available room -->
<div class="modal fade" id="available_room" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-sm">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" align="center"><strong><?php echo $_data['available_rooms']; ?></strong></h4>
      </div>
      <div class="modal-body clearfix">
        <table class="table table-bordered table-striped table-condensed">
		<thead>
			<tr>
				<th><?php echo $_data['available_rooms_floor']; ?></th>
				<th><?php echo $_data['available_rooms_unit']; ?></th>
			</tr>
		</thead>
		<tbody>
		<?php
			$result_unit_list = mysqli_query($link,"SELECT f.floor_no as floor_name, u.unit_no as unit_name FROM tbl_add_unit u inner join tbl_add_floor f on f.fid = u.floor_no where u.status = 0 and u.branch_id=".(int)$_SESSION['objLogin']['branch_id'].' order by u.unit_no ASC');
			while($row_unit_list = mysqli_fetch_array($result_unit_list)){?>
			<tr>
				<td><?php echo $row_unit_list['floor_name']; ?></td>
				<td><?php echo $row_unit_list['unit_name']; ?></td>
			</tr>
		<?php } ?>
		<tbody>
		</table>
      </div>
      <div class="modal-footer"> </div>
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>
<!-- /.modal -->


<?php if(!maxPower::verifyInstalledDomain($link)){ ?>
<div class="modal fade" id="modalVerify" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title" id="exampleModalLabel">Unregistered Version Alert !!!!</h4>
      </div>
      <div class="modal-body"> This version is unregistered and you need to buy our license version to continue using our application. Another thing if you try to use same application for multiple domain you can loose your system and important information so kindly use valid license version. <a href="https://codecanyon.net/item/responsive-apartment-management-system/16343942" target="_blank">Click here</a> to purchase our application and follow our installation step or contact with me via email address <a href="mailto:devsolver@gmail.com">devsolver@gmail.com</a> for any help or customization. </div>
    </div>
  </div>
</div>
<?php } ?>
