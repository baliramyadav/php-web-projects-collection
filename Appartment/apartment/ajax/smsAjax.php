<?php
	session_start();
	include("../config.php");
	if(isset($_SESSION['objLogin'])){
		if(isset($_GET['token']) && $_GET['token'] == 'getTenantData'){
			$json = array();
			if(!empty($_REQUEST['term'])){
				$result = mysqli_query($link,"SELECT * from tbl_add_rent where r_name LIKE '%".$_REQUEST['term']."%' and branch_id = " . (int)$_SESSION['objLogin']['branch_id'] . " and r_status=1 ORDER BY r_name ASC");
				while($rows = mysqli_fetch_array($result)){
					$json[] = array(
						'id'		=> $rows['rid'],
						'name'		=> $rows['r_name'],
						'mobile'	=> $rows['r_contact'],
						'email'		=> $rows['r_email']
					);
				}
			}
			echo json_encode($json);
			die();
	   }
	   if(isset($_GET['token']) && $_GET['token'] == 'getOwnerData'){
			$json = array();
			if(!empty($_REQUEST['term'])){
				$result = mysqli_query($link,"SELECT * from tbl_add_owner where o_name LIKE '%".$_REQUEST['term']."%' and branch_id = " . (int)$_SESSION['objLogin']['branch_id'] . " ORDER BY o_name ASC");
				while($rows = mysqli_fetch_array($result)){
					$json[] = array(
						'id'		=> $rows['ownid'],
						'name'		=> $rows['o_name'],
						'mobile'	=> $rows['o_contact'],
						'email'		=> $rows['o_email']
					);
				}
			}
			echo json_encode($json);
			die();
	   }
	   if(isset($_GET['token']) && $_GET['token'] == 'getEmpData'){
			$json = array();
			if(!empty($_REQUEST['term'])){
				$result = mysqli_query($link,"SELECT * from tbl_add_employee where e_name LIKE '%".$_REQUEST['term']."%' and branch_id = " . (int)$_SESSION['objLogin']['branch_id'] . " ORDER BY e_name ASC");
				while($rows = mysqli_fetch_array($result)){
					$json[] = array(
						'id'		=> $rows['eid'],
						'name'		=> $rows['e_name'],
						'mobile'	=> $rows['e_contact'],
						'email'		=> $rows['e_email']
					);
				}
			}
			echo json_encode($json);
			die();
	   }
	} else{
		echo '-99';
		die();
	}
?>
