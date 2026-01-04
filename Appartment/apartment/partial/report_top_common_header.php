<?php

defined('_AMSCODESECURITY') or exit('Restricted Access');

$localization = array();
$lang_code_global = "English";
$global_currency = '$';
$query_ams_settings = mysqli_query($link,"SELECT * FROM tbl_settings");
if($row_query_ams_core = mysqli_fetch_array($query_ams_settings)){
	$localization = $row_query_ams_core;
	$lang_code_global = $row_query_ams_core['lang_code'];
	$global_currency = $row_query_ams_core['currency'];
}

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
