<?php

defined('_AMSCODESECURITY') or exit('Restricted Access');

$lang_code_global = "English";
$localization = array();

$query_ams_settings = mysqli_query($link,"SELECT * FROM tbl_settings");
if($row_query_ams_core = mysqli_fetch_array($query_ams_settings)){
	$localization = $row_query_ams_core;
	$lang_code_global = $row_query_ams_core['lang_code'];
}

//building info
$image_building = '';
$building_name = '';
$address = '';
$report_phone = '';
$report_email = '';

$result = mysqli_query($link,"SELECT *,y.y_id,y.xyear FROM tblbranch bi left join tbl_add_year_setup y on y.y_id = bi.building_make_year where bi.branch_id = " . (int)$_SESSION['objLogin']['branch_id']);
if($row = mysqli_fetch_array($result)){
	$building_name = $row['branch_name'];
	$address = $row['b_address'];
	$b_name = $row['branch_name'];
	$report_phone = $row['b_contact_no'];
	$report_email = $row['b_email'];
	if($row['building_image'] != ''){
		$image_building = WEB_URL . 'img/upload/' . $row['building_image'];
	}
}
