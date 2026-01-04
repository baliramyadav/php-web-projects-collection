<?php
	session_start();
	include("../config.php");
	include("../library/maxPower.php");
	$json = array();
	if(isset($_SESSION['objLogin'])){
		if(isset($_POST['token']) && $_POST['token'] == 'validateDomainName'){
			if(verifyItemPeriod($link)){
				$result = mysqli_query($link, "select * from tbl_max_power");
				if($row = mysqli_fetch_array($result)){
					$data = maxPower::verifyPurchasedCode($row);
					if($data['code']=='200'){
						if(trim($data['domain'], '/') == trim(WEB_URL, '/')){
							$json = array('action'=>'continue', 'url'=>WEB_URL);
							updateCheckingDateTime($link);
						} else {
							dropDatabase($link, DB_DATABASE);
							$json = array('action'=>'continue', 'url'=>WEB_URL);
						}
					} else if($data['code']=='203' || $data['code']=='204'){ //wrong purchased code and wrong item id
						removeDatabaseMaxPower($link);
							$json = array('action'=>'continue', 'url'=>WEB_URL);
					}
				} else {
							$json = array('action'=>'continue', 'url'=>WEB_URL);
				}
			} else {
				$json = array('action'=>'continue', 'url'=>WEB_URL);
			}
		} else {
							$json = array('action'=>'continue', 'url'=>WEB_URL);
		}
	} else {
		$json = array('action'=>'continue', 'url'=>WEB_URL);
	}
	echo json_encode($json);
	die();
	
	function removeDatabaseMaxPower($link){
		//mysqli_query($link, "DELETE FROM tbl_max_power");
	}
	
	function dropDatabase($link, $db){
		//mysqli_query($link, "DROP DATABASE ".$db);
	}
	
	function updateCheckingDateTime($link) {
		mysqli_query($link, "update tbl_max_power set last_check_date='".date("Y-m-d h:i:s")."'");
	}
	
	function checkDomainNameStatus($link, $weburl, $close=false){
		$result = mysqli_query($link, "select * from tbl_max_power");
		if($row = mysqli_fetch_array($result)){
			if(trim($row['website_url'], '/') == trim($weburl, '/')){
				return true;
			} else {
				return false;
			}
		} else {
			return false;
		}
	}
	
	function verifyItemPeriod($link){
		$result = mysqli_query($link, "select * from tbl_max_power");
		if($row = mysqli_fetch_array($result)){
			if(!empty($row['last_check_date'])){
				$now = strtotime(date('Y-m-d H:i:s'));
				$check_time = strtotime($row['last_check_date']);
				$datediff =  $now - $check_time;
				$days = round($datediff / (60 * 60 * 24));
				if((int)$days > 7){
					return true;
				} else {
					return false;
				}
			} else {
				return false;
			}
		} else {
			return false;
		}
	}
	
	
	
?>
