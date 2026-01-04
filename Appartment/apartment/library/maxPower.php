<?php

class maxPower {
	public static function verifyInstalledDomain($link, $close=false){
		$result = mysqli_query($link, "select * from tbl_max_power");
		if($row = mysqli_fetch_array($result)){
			if($close){
				mysqli_close($link);
			}
			return true;
		} else {
			if($close){
				mysqli_close($link);
			}
			return false;
		}
		die();
	}
	
	private static function callAPI($url, $data){
		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$response_json = curl_exec($ch);
		curl_close($ch);
		return json_decode($response_json, true);
	}
	
	public static function verifyPurchasedCode($data){
		$response = maxPower::callAPI('http://sakocart.com/api/verify.php', array('apikey'=>'b7aac11a-5b9a-11e8-9c2d-fa7ae01bbebc','email' => trim($data['email']),'domain' => trim($data['website_url']),'pcode' =>trim($data['purchase_code']),'ip_address'=>$_SERVER['REMOTE_ADDR']));
		return $response;
	}
	
	/*private function checkDomainNameStatus($link, $weburl, $close=false){
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
	}*/
	
	
}

?>