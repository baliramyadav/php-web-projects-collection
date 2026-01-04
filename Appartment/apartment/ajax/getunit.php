<?php
	session_start();
	include("../config.php");
	if(isset($_SESSION['objLogin'])){
		if(isset($_POST['token']) && $_POST['token'] == 'getunitinfo'){
			$html = '<option value="">--Select Unit--</option>';
			if(isset($_POST['floor_no']) && (int)$_POST['floor_no'] > 0){
				$unit_no = '';
				$result = mysqli_query($link,"SELECT * from tbl_add_unit where floor_no = '" . (int)$_POST['floor_no'] . "' and status = 0 order by unit_no asc");
				while($rows = mysqli_fetch_array($result)){
					$html .= '<option value="'.$rows['uid'].'">'.$rows['unit_no'] . '</option>';
				}
				echo $html;
				die();
			}
			echo '';
			die();
		}
		else if(isset($_POST['token']) && $_POST['token'] == 'getbookedunit'){
			$html = '<option value="">--Select Unit--</option>';
			if(isset($_POST['floor_no']) && (int)$_POST['floor_no'] > 0){
				$unit_no = '';
				$result = mysqli_query($link,"SELECT * from tbl_add_unit where floor_no = '" . (int)$_POST['floor_no'] . "' and status = 1 order by unit_no asc");
				while($rows = mysqli_fetch_array($result)){
					$html .= '<option value="'.$rows['uid'].'">'.$rows['unit_no'] . '</option>';
				}
				echo $html;
				die();
			}
			echo '';
			die();
		}
		else if(isset($_POST['token']) && $_POST['token'] == 'getunitinforeport'){
			$html = '<option value="">--Select Unit--</option>';
			if(isset($_POST['floor_no']) && (int)$_POST['floor_no'] > 0){
				$unit_no = '';
				$result = mysqli_query($link,"SELECT * from tbl_add_unit where floor_no = '" . (int)$_POST['floor_no'] . "' order by unit_no asc");
				while($rows = mysqli_fetch_array($result)){
					$html .= '<option value="'.$rows['uid'].'">'.$rows['unit_no'] . '</option>';
				}
				echo $html;
				die();
			}
			echo '';
			die();
		}
		else if(isset($_POST['token']) && $_POST['token'] == 'getRentInfo'){
			$html = array(
				'rid'	=> '0',
				'name'	=> '',
				'fair'	=> '0.00'
			);
			if(isset($_POST['floor_id']) && (int)$_POST['floor_id'] > 0 && isset($_POST['unit_id']) && (int)$_POST['unit_id'] > 0){
				$result = mysqli_query($link,"SELECT * from tbl_add_rent where r_floor_no = '" . (int)$_POST['floor_id'] . "' and r_unit_no = '" . (int)$_POST['unit_id'] . "' and r_status = 1");
				if($rows = mysqli_fetch_array($result)){
					$html = array(
						'rid'	=> $rows['rid'],
						'name'	=> $rows['r_name'],
						'fair'	=> $rows['r_rent_pm']
					);
				}
			}
			echo json_encode($html);
			die();
		}
		else if(isset($_POST['token']) && $_POST['token'] == 'getOwnerInfo'){
			$html = array(
				'ownid'	=> '0',
				'name'	=> ''
			);
			if(isset($_POST['unit_id']) && (int)$_POST['unit_id'] > 0){
				$result = mysqli_query($link,"SELECT * from tbl_add_owner_unit_relation ur inner join tbl_add_owner ao on ao.ownid = ur.owner_id where ur.unit_id  = '" . (int)$_POST['unit_id'] . "'");
				if($rows = mysqli_fetch_array($result)){
					$html = array(
						'ownid'	=> $rows['owner_id'],
						'name'	=> $rows['o_name']
					);
				}
			}
			echo json_encode($html);
			die();
		}
		else if(isset($_POST['token']) && $_POST['token'] == 'getDesgInfo'){
			$html = '';
			if(isset($_POST['emp_id']) && (int)$_POST['emp_id'] > 0){
				$result_emp = mysqli_query($link,"SELECT *,mt.member_type from tbl_add_employee e inner join tbl_add_member_type mt on mt.member_id = e.e_designation where eid = '" . (int)$_POST['emp_id'] . "'");
				if($row_emp = mysqli_fetch_array($result_emp)){
					$html = $row_emp['member_type'];
				}
			}
			echo $html;
			die();
		}
		else if(isset($_POST['token']) && $_POST['token'] == 'getDesgInfoWithAll'){
			$output = array('data'=>'');
			if(isset($_POST['emp_id']) && (int)$_POST['emp_id'] > 0){
				$result_emp = mysqli_query($link,"SELECT *,mt.member_type from tbl_add_employee e inner join tbl_add_member_type mt on mt.member_id = e.e_designation where eid = '" . (int)$_POST['emp_id'] . "'");
				if($row_emp = mysqli_fetch_array($result_emp)){
					$_data = array(
						'member_type'	=> $row_emp['member_type'],
						'salary'		=> $row_emp['salary'],
					);
					$output = array('data'=>$_data);
				}
			}
			echo json_encode($output);
			die();
		}
		else if(isset($_POST['token']) && $_POST['token'] == 'owner_email_exist'){
			$json = array('email_exist'=>false);
			if(isset($_POST['email']) && !empty($_POST['email'])){
				$result_owner = mysqli_query($link,"SELECT * FROM tbl_add_owner where o_email = '".trim($_POST['email'])."'");
				if($row_owner = mysqli_fetch_array($result_owner)){
					$json = array('email_exist'=>true);
				}
			}
			echo json_encode($json);
			die();
		}
		else if(isset($_POST['token']) && $_POST['token'] == 'renter_email_exist'){
			$json = array('email_exist'=>false);
			if(isset($_POST['email']) && !empty($_POST['email'])){
				$result_renter = mysqli_query($link,"SELECT * FROM tbl_add_rent where r_email = '".trim($_POST['email'])."'");
				if($row_renter = mysqli_fetch_array($result_renter)){
					$json = array('email_exist'=>true);
				}
			}
			echo json_encode($json);
			die();
		}	
		else if(isset($_POST['token']) && $_POST['token'] == 'employee_email_exist'){
			$json = array('email_exist'=>false);
			if(isset($_POST['email']) && !empty($_POST['email'])){
				$result_emp = mysqli_query($link,"SELECT * FROM tbl_add_employee where e_email = '".trim($_POST['email'])."'");
				if($row_emp = mysqli_fetch_array($result_emp)){
					$json = array('email_exist'=>true);
				}
			}
			echo json_encode($json);
			die();
		}
		else if(isset($_POST['token']) && $_POST['token'] == 'mc_email_exist'){
			$json = array('email_exist'=>false);
			if(isset($_POST['email']) && !empty($_POST['email'])){
				$result_emp = mysqli_query($link,"SELECT * FROM tbl_add_management_committee where mc_email = '".trim($_POST['email'])."'");
				if($row_emp = mysqli_fetch_array($result_emp)){
					$json = array('email_exist'=>true);
				}
			}
			echo json_encode($json);
			die();
		}
		else if(isset($_POST['token']) && $_POST['token'] == 'updateLeaveStatus'){
			$json = array('msg'=>'');
			if(isset($_POST['status']) && !empty($_POST['leave_id'])){
				mysqli_query($link,"UPDATE tbl_employee_leave_request SET request_status = '".$_POST['status']."' where leave_id = ".(int)$_POST['leave_id']);
				$json = array('msg'=>'Updated leave status successfully');
			}
			echo json_encode($json);
			die();
		}
		else if(isset($_POST['token']) && $_POST['token'] == 'updateComplainStatus'){
			$json = array('msg'=>'');
			if(isset($_POST['cid']) && !empty($_POST['cid'])){
				mysqli_query($link,"UPDATE tbl_add_complain SET job_status = '".$_POST['status']."' where complain_id = ".(int)$_POST['cid']);
				$json = array('msg'=>'Updated complain status successfully');
			}
			echo json_encode($json);
			die();
		}
		else if(isset($_POST['token']) && $_POST['token'] == 'updateComplainJob'){
			if(isset($_POST['eid']) && !empty($_POST['eid'])){
				mysqli_query($link,"UPDATE tbl_add_complain SET assign_employee_id = '".$_POST['eid']."' where complain_id = ".(int)$_POST['cid']);
			}
			echo 'success';
			die();
		}
		else if(isset($_POST['token']) && $_POST['token'] == 'updateComplainSolution'){
			if(isset($_POST['cid']) && !empty($_POST['cid'])){
				mysqli_query($link,"UPDATE tbl_add_complain SET job_status = '".$_POST['status']."', solution = '".$_POST['txt']."' where complain_id = ".(int)$_POST['cid']);
			}
			echo 'success';
			die();
		}		
	}
	else{
		echo '-99';
		die();
	}
?>
