<?php
	include("../config.php");
	include("../library/encryption.php");
	$converter = new Encryption;
	session_start();
	if(isset($_SESSION['objLogin'])){
		if(isset($_POST['txtProfilePassword'])){
			$name = !empty($_POST['txtProfileName']) ? $_POST['txtProfileName'] : '';
			$email = !empty($_POST['txtProfileEmail']) ? $_POST['txtProfileEmail'] : '';
			$contact = !empty($_POST['txtProfileContact']) ? $_POST['txtProfileContact'] : '';
			$password = $converter->encode($_POST['txtProfilePassword']);
			$sql = '';
			if($_SESSION['login_type'] == '1'){
				$sql = "UPDATE `tbl_add_admin` set name = '$name', email = '$email', contact = '$contact', password = '$password' where aid = '$_POST[user_id]'";
			}
			else if($_SESSION['login_type'] == '2'){
				$sql = "UPDATE `tbl_add_owner` set o_name = '$name', o_email = '$email',o_contact = '$contact', o_password = '$password' where ownid = '$_POST[user_id]'";
			}
			else if($_SESSION['login_type'] == '3'){
				$sql = "UPDATE `tbl_add_employee` set e_password = '$password' where eid = '$_POST[user_id]'";
			}
			else if($_SESSION['login_type'] == '4'){
				$sql = "UPDATE `tbl_add_rent` set r_password = '$password' where rid = '$_POST[user_id]'";
			}
			else if($_SESSION['login_type'] == '5'){
				$sql = "UPDATE `tblsuper_admin` set name = '$name', email = '$email', contact = '$contact', password = '$password' where user_id = '$_POST[user_id]'";
			}
			mysqli_query($link,$sql);
			echo "1";
			die();
		}
		else{
			echo '-99';
		}
	}
	else{
		echo '-99';
		die();
	}
?>
