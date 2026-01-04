<?php 
include('../header.php');
if(!isset($_SESSION['objLogin'])){
	header("Location: " . WEB_URL . "logout.php");
	die();
}
?>
<?php
include(ROOT_PATH.'language/'.$lang_code_global.'/lang_mailsms_list.php');
$delinfo = 'none';
$addinfo = 'none';
$msg = "";
if(isset($_GET['id']) && $_GET['id'] != '' && $_GET['id'] > 0){
	$sqlx= "DELETE FROM `tbl_notification_alert` WHERE notification_Id = ".$_GET['id'];
	mysqli_query($link,$sqlx); 
	$delinfo = 'block';
}
if(isset($_GET['m']) && $_GET['m'] == 'add'){
	$addinfo = 'block';
	$msg = $_data['add_msg'];
}
if(isset($_GET['m']) && $_GET['m'] == 'up'){
	$addinfo = 'block';
	$msg = $_data['update_msg'];
}

//post checking
if( isset($_POST['notification_title']) ){
	
	$users = array();
	
	$subject = $_POST['notification_title'];
	$msg = $_POST['message'];
	$msg_type = '';
	if(isset($_POST['sms']) && $_POST['sms']=='on' && isset($_POST['email']) && $_POST['email']=='on'){
		$msg_type = 3;
	} else if(isset($_POST['email']) && $_POST['email']=='on'){
		$msg_type = 2;
	} else if(isset($_POST['sms']) && $_POST['sms']=='on'){
		$msg_type = 1;
	}
	
	//check all 
	if(isset($_POST['sender'])){
		foreach($_POST['sender'] as $sender){
			if($sender=='tenant'){
				$result = mysqli_query($link,"SELECT * from tbl_add_rent where branch_id = " . (int)$_SESSION['objLogin']['branch_id'] . " and r_status=1 ORDER BY r_name ASC");
				while($rows = mysqli_fetch_array($result)){
					if(isset($_POST['sms']) && $_POST['sms']=='on'){
						$ams_helper->sendSMS($localization, $rows['r_contact'], $msg);//sms
					}
					if(isset($_POST['email']) && $_POST['email']=='on'){
						$ams_helper->sendEmail($localization, $rows['r_email'], $subject, $msg);//mail
					}
				}
				$users['shop'] = array('All Tenant');
			}
			if($sender=='owner'){
				$result = mysqli_query($link,"SELECT * from tbl_add_owner where branch_id = " . (int)$_SESSION['objLogin']['branch_id'] . " ORDER BY o_name ASC");
				while($rows = mysqli_fetch_array($result)){
					if(isset($_POST['sms']) && $_POST['sms']=='on'){
						$ams_helper->sendSMS($localization, $rows['o_contact'], $msg);//sms
					}
					if(isset($_POST['email']) && $_POST['email']=='on'){
						$ams_helper->sendEmail($localization, $rows['o_email'], $subject, $msg);//mail
					}
				}
				$users['owner'] = array('All Owner');
			}
			if($sender=='employee'){
				$result = mysqli_query($link,"SELECT * from tbl_add_employee where branch_id = " . (int)$_SESSION['objLogin']['branch_id'] . " ORDER BY e_name ASC");
				while($rows = mysqli_fetch_array($result)){
					if(isset($_POST['sms']) && $_POST['sms']=='on'){
						$ams_helper->sendSMS($localization, $rows['e_contact'], $msg);//sms
					}
					if(isset($_POST['email']) && $_POST['email']=='on'){
						$ams_helper->sendEmail($localization, $rows['e_email'], $subject, $msg);//mail
					}
				}
				$users['employee'] = array('All Employee');
			}
		}
	}
	//end check all
	//**check selected
	if(isset($_POST['_items'])){
		
		if(isset($_POST['shop_data']) && !empty($_POST['shop_data'])){
			$users['shop'] = $_POST['shop_data'];
		}
		if(isset($_POST['owner_data']) && !empty($_POST['owner_data'])){
			$users['owner'] = $_POST['owner_data'];
		}
		if(isset($_POST['emp_data']) && !empty($_POST['emp_data'])){
			$users['employee'] = $_POST['emp_data'];
		}
		
		foreach($_POST['_items'] as $item){
			$type = explode('-', $item);
			$id = $type[1];
			if($type[0]=='S'){
				$result = mysqli_query($link,"SELECT * from tbl_add_rent where rid = ".(int)$id." and branch_id = " . (int)$_SESSION['objLogin']['branch_id'] . " and r_status=1");
				if($row = mysqli_fetch_array($result)){
					if(isset($_POST['sms']) && $_POST['sms']=='on'){
						$ams_helper->sendSMS($localization, $row['r_contact'], $msg);//sms
					}
					if(isset($_POST['email']) && $_POST['email']=='on'){
						$ams_helper->sendEmail($localization, $row['r_email'], $subject, $msg);//mail
					}
				}
			}
			if($type[0]=='O'){
				$result = mysqli_query($link,"SELECT * from tbl_add_owner where ownid = ".(int)$id." and branch_id = ".(int)$_SESSION['objLogin']['branch_id']);
				if($row = mysqli_fetch_array($result)){
					if(isset($_POST['sms']) && $_POST['sms']=='on'){
						$ams_helper->sendSMS($localization, $row['o_contact'], $msg);//sms
					}
					if(isset($_POST['email']) && $_POST['email']=='on'){
						$ams_helper->sendEmail($localization, $row['o_email'], $subject, $msg);//mail
					}
				}
			}
			if($type[0]=='E'){
				$result = mysqli_query($link,"SELECT * from tbl_add_employee where eid =".(int)$id." and branch_id = ".(int)$_SESSION['objLogin']['branch_id']);
				if($row = mysqli_fetch_array($result)){
					if(isset($_POST['sms']) && $_POST['sms']=='on'){
						$ams_helper->sendSMS($localization, $row['e_contact'], $msg);//sms
					}
					if(isset($_POST['email']) && $_POST['email']=='on'){
						$ams_helper->sendEmail($localization, $row['e_email'], $subject, $msg);//mail
					}
				}
			}
		}
	}
	/*** end Selected*/
	//save into database just for tracking
	$users = json_encode($users);
	saveNotification($link, $subject, $msg, $msg_type, $users);
}

function saveNotification($link, $subject, $message, $type, $users) {
	mysqli_query($link, "INSERT INTO `tbl_notification_alert`(`subject`, `message`, `type`, `user_details`, `branch_id`) VALUES ('$subject','$message','$type','$users','".(int)$_SESSION['objLogin']['branch_id']."')");
}


?>
<!-- Content Header (Page header) -->

<section class="content-header">
  <h1> <?php echo $_data['list_title']; ?> </h1>
  <ol class="breadcrumb">
    <li><a href="<?php echo WEB_URL?>dashboard.php"><i class="fa fa-dashboard"></i><?php echo $_data['home_breadcam']; ?></a></li>
    <li class="active"><?php echo $_data['add_new_floor_information_breadcam']; ?></li>
    <li class="active"><?php echo $_data['list_title']; ?></li>
  </ol>
</section>
<!-- Main content -->
<section class="content">
<!-- Full Width boxes (Stat box) -->
<div class="row">
  <div class="col-xs-12">
    <div id="me" class="alert alert-danger alert-dismissable" style="display:<?php echo $delinfo; ?>">
      <button aria-hidden="true" data-dismiss="alert" class="close" type="button"><i class="fa fa-close"></i></button>
      <h4><i class="icon fa fa-ban"></i><?php echo $_data['delete_text'];?> !</h4>
      <?php echo $_data['delete_floor_information']; ?> </div>
    <div id="you" class="alert alert-success alert-dismissable" style="display:<?php echo $addinfo; ?>">
      <button aria-hidden="true" data-dismiss="alert" class="close" type="button"><i class="fa fa-close"></i></button>
      <h4><i class="icon fa fa-check"></i> <?php echo $_data['success'];?> !</h4>
      <?php echo $msg; ?> </div>
    <div align="right" style="margin-bottom:1%;"> <a href="#addModal" class="btn btn-success" data-toggle="modal" data-original-title="<?php echo $_data['add_sms'];?>"><i class="fa fa-plus"></i></a> <a class="btn btn-success" data-toggle="tooltip" href="<?php echo WEB_URL; ?>dashboard.php" data-original-title="<?php echo $_data['home_breadcam'];?>"><i class="fa fa-dashboard"></i></a> </div>
    
	
	<div class="alert alert-danger alert-dismissable">
	  <p style="color:#fff !important;font-size:20px !important;"><i class="icon fa fa-bullhorn"></i> <?php echo $_data['text_21'];?> <a target="_blank" href="<?php echo WEB_URL ?>setting/language.php"><?php echo $_data['text_23'];?></a>. <?php echo $_data['text_22'];?></p>
    </div>
	
	
	<div class="box box-success">
      <div class="box-header">
        <h3 class="box-title"><?php echo $_data['list_title']; ?></h3>
      </div>
      <!-- /.box-header -->
      <div class="box-body">
        <table class="table sakotable table-bordered table-striped dt-responsive">
          <thead>
            <tr>
              <th><?php echo $_data['list_1'];?></th>
			  <th><?php echo $_data['list_2'];?></th>
			  <th><?php echo $_data['list_3'];?></th>
			  <th><?php echo $_data['list_4'];?></th>
              <th><?php echo $_data['list_5'];?></th>
			  <th>&nbsp;</th>
            </tr>
          </thead>
          <tbody>
            <?php
			$result = mysqli_query($link,"Select * from tbl_notification_alert where branch_id = " . (int)$_SESSION['objLogin']['branch_id'] . " order by notification_Id DESC");
			while($row = mysqli_fetch_array($result)){
			$users = json_decode($row['user_details'], true);
			?>
            <tr>
              <td><?php echo date( 'd-m-Y', strtotime($row['sent_date']) ); ?></td>
			  <td><?php echo $row['subject']; ?></td>
			  <td><?php echo $row['message']; ?></td>
			  <td><?php if($row['type']=='1'){echo '<label class="label label-success">'.$_data['text_12'].'</label>';} else if($row['type']=='2') {echo '<label class="label label-warning">'.$_data['text_13'].'</label>';} else {echo '<label class="label label-primary">'.$_data['text_17'].'</label>';}?></td>
			  <td>
			  		<?php
						if(!empty($users['shop'])){
							echo '<div style="font-weight:bold;text-decoration:underline;">'.$_data['text_18'].'</div>';
							$i=1;
							foreach($users['shop'] as $shop) {
								echo $i.') '.$shop;
								$i++;
							}
							echo '<div>----------------------------</div>';
						}
						
						if(!empty($users['owner'])){
							echo '<div style="font-weight:bold;text-decoration:underline;">'.$_data['text_19'].'</div>';
							$i=1;
							foreach($users['owner'] as $owner) {
								echo $i.') '.$owner;
								$i++;
							}
							echo '<div>----------------------------</div>';
						}
						
						if(!empty($users['employee'])){
							echo '<div style="font-weight:bold;text-decoration:underline;">'.$_data['text_20'].'</div>';
							$i=1;
							foreach($users['employee'] as $employee) {
								echo $i.') '.$employee;
								$i++;
							}
						}
					?> 
			  	
			  </td>
              <td><a class="btn btn-danger ams_btn_special" data-toggle="tooltip" onclick="deleteSMS(<?php echo $row['notification_Id']; ?>);" href="javascript:;" data-original-title="<?php echo $_data['delete_text'];?>"><i class="fa fa-trash-o"></i></a></td>
            </tr>
            <?php } mysqli_close($link);$link = NULL; ?>
          </tbody>
        </table>
      </div>
      <!-- /.box-body -->
    </div>
    <!-- /.box -->
  </div>
  <!-- /.col -->
</div>
<!-- /.row -->
<!-- Modal -->
<form method="post" enctype="multipart/form-data">
  <div class="modal fade" id="addModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg">
      <div class="modal-content" style="width: 550px; margin: 0 auto;">
        <div class="modal-header">
          <h4 class="modal-title">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <i class="icon-envelope-open" style="color: #00A65A"></i>&nbsp; <strong> <?php echo $_data['add_sms']; ?></strong> </h4>
        </div>
        <div class="modal-body clearfix">
          <div class="msg">
            <p style="color:#CC0000;font-size:18px;"><?php echo $_data['text_1']; ?></p>
          </div>
          <div class="form-group">
            <label for="notification_title" style="font-weight:normal;"><?php echo $_data['text_2']; ?><span class="validate-field">*</span> : </label>
            <input type="text" class="form-control" id="notification_title" name="notification_title" placeholder="Message Title" required>
          </div>
          <div class="form-group">
            <label for="message" style="font-weight:normal;"><?php echo $_data['text_3']; ?> <span class="validate-field">*</span> : </label>
            <textarea class="form-control" name="message" id="message" placeholder="Message" required></textarea>
          </div>
          <div style="border:solid 1px #d2d6de;padding:10px;clear:both;">
            <div>
              <div class="clearfix">
                <ul class="nav nav-tabs">
                  <li class="active"><a data-toggle="tab" href="#menu0"><?php echo $_data['text_4']; ?></a></li>
                  <li><a data-toggle="tab" href="#menu1"><?php echo $_data['text_5']; ?> </a></li>
                  <li><a data-toggle="tab" href="#menu2"><?php echo $_data['text_6']; ?></a></li>
                  <li><a data-toggle="tab" href="#menu3"><?php echo $_data['text_7']; ?></a></li>
                </ul>
                <div class="tab-content">
                  <div id="menu0" class="tab-pane fade in active">
                    <div>
                      <div style="border:solid 1px #d2d6de;padding:5px;width:100%;margin-top:15px;margin-bottom:10px;">
                        <input type="checkbox" class="sender_all" value="tenant" name="sender[]" />
                        &nbsp;All Tenant &nbsp;&nbsp;&nbsp;&nbsp;
                        <input type="checkbox" class="sender_all" value="owner" name="sender[]" />
                        &nbsp;All Owner&nbsp;&nbsp;&nbsp;&nbsp;
                        <input type="checkbox" class="sender_all" value="employee" name="sender[]" />
                        &nbsp;All Employee&nbsp;&nbsp; </div>
                    </div>
                  </div>
                  <div id="menu1" class="tab-pane fade" style="margin-top:15px;">
                    <p>
                      <input type="text" class="form-control" id="shop_filter" name="shop_filter" placeholder="<?php echo $_data['text_8']; ?>">
                    </p>
                  </div>
                  <div id="menu2" class="tab-pane fade" style="margin-top:15px;">
                    <p>
                      <input type="text" class="form-control" id="owner_filter" name="owner_filter" placeholder="<?php echo $_data['text_9']; ?>">
                    </p>
                  </div>
                  <div id="menu3" class="tab-pane fade" style="margin-top:15px;">
                    <p>
                      <input type="text" class="form-control" id="employee_filter" name="employee_filter" placeholder="<?php echo $_data['text_10']; ?>">
                    </p>
                  </div>
                </div>
              </div>
            </div>
            <div style="border:solid 1px #d2d6de;padding:10px;height:150px;overflow: auto;" id="fixed-item-list"> </div>
          </div>
          <br/>
          <div>
            <div class="col-md-12 row">
              <label for="message" style="font-weight:normal;"><?php echo $_data['text_11']; ?> : </label>
              &nbsp;
              <input type="checkbox" checked="checked" name="sms" />
              &nbsp;<?php echo $_data['text_12']; ?>&nbsp;&nbsp;
              <input type="checkbox" name="email" />
              &nbsp;<?php echo $_data['text_13']; ?> </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-success btn-sm"><?php echo $_data['text_14']; ?></button>
          <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal"><?php echo $_data['text_15']; ?></button>
        </div>
      </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
  </div>
  <!-- /.modal -->
</form>

<script src="<?php echo WEB_URL; ?>assets/js/jquery-ui.js"></script>
<link href="//cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.css" rel="stylesheet">
<script type="text/javascript">
function deleteSMS(Id){
  	var iAnswer = confirm("<?php echo $_data['confirm']; ?>");
	if(iAnswer){
		window.location = '<?php echo WEB_URL; ?>mailsms/mailsms.php?id=' + Id;
	}
  }
  
  $( document ).ready(function() {
	setTimeout(function() {
		  $("#me").hide(300);
		  $("#you").hide(300);
	}, 3000);
	
	$('#shop_filter').autocomplete({
		'source': function(request, response) {
			$.ajax({
				url: "<?php echo WEB_URL; ?>ajax/smsAjax.php",
				data: {term:request.term,token:"getTenantData"},
				dataType: 'json',
				success: function(json) {
					response($.map(json, function(item) {
						return {
							label: item.name,
							id: item.id
						}
					}));
				}
			});
		},
		select: function (event, ui) {
			event.preventDefault();
			$('#shop_filter').val(ui.item.label);
			if(jQuery('#S-'+ui.item.id).length==0){
				var _xhtml = '<div style="padding:2px;"><input type="hidden" name="shop_data[]" value="'+ui.item.label+'"> <input id="S-'+ui.item.id+'" type="hidden" name="_items[]" value="S-'+ui.item.id+'"><a onclick="_remove_item(this);" href="javascript:;"><i style="color:red;" class="fa fa-close"></i></a> &nbsp;&nbsp;'+ui.item.label+' <b>(Tenant)</b></div>';
				$("#fixed-item-list").append(_xhtml);
			} else {
				$.toaster('<?php echo $_data['text_16']; ?>', 'Alert ', 'warning');
			}
			$('input[name="sender[]"]').prop('checked', false);
		}
	});
	
	
	$('#owner_filter').autocomplete({
		'source': function(request, response) {
			$.ajax({
				url: "<?php echo WEB_URL; ?>ajax/smsAjax.php",
				data: {term:request.term,token:"getOwnerData"},
				dataType: 'json',
				success: function(json) {
					response($.map(json, function(item) {
						return {
							label: item.name,
							id: item.id
						}
					}));
				}
			});
		},
		select: function (event, ui) {
			event.preventDefault();
			$('#owner_filter').val(ui.item.label);
			if(jQuery('#O-'+ui.item.id).length==0){
				var _xhtml = '<div style="padding:2px;"><input type="hidden" name="owner_data[]" value="'+ui.item.label+'"> <input id="O-'+ui.item.id+'" type="hidden" name="_items[]" value="O-'+ui.item.id+'"><a onclick="_remove_item(this);" href="javascript:;"><i style="color:red;" class="fa fa-close"></i></a> &nbsp;&nbsp;'+ui.item.label+' <b>(Owner)</b></div>';
				$("#fixed-item-list").append(_xhtml);
			} else {
				$.toaster('<?php echo $_data['text_16']; ?>', 'Alert ', 'warning');
			}
			$('input[name="sender[]"]').prop('checked', false);
		}
	});
	
	$('#employee_filter').autocomplete({
		'source': function(request, response) {
			$.ajax({
				url: "<?php echo WEB_URL; ?>ajax/smsAjax.php",
				data: {term:request.term,token:"getEmpData"},
				dataType: 'json',
				success: function(json) {
					response($.map(json, function(item) {
						return {
							label: item.name,
							id: item.id
						}
					}));
				}
			});
		},
		select: function (event, ui) {
			event.preventDefault();
			$('#employee_filter').val(ui.item.label);
			if(jQuery('#E-'+ui.item.id).length==0){
				var _xhtml = '<div style="padding:2px;"><input type="hidden" name="emp_data[]" value="'+ui.item.label+'"> <input id="E-'+ui.item.id+'" type="hidden" name="_items[]" value="E-'+ui.item.id+'"><a onclick="_remove_item(this);" href="javascript:;"><i style="color:red;" class="fa fa-close"></i></a> &nbsp;&nbsp;'+ui.item.label+' <b>(Employee)</b></div>';
				$("#fixed-item-list").append(_xhtml);
			} else {
				$.toaster('<?php echo $_data['text_16']; ?>', 'Alert ', 'warning');
			}
			$('input[name="sender[]"]').prop('checked', false);
		}
	});
	
	
});

$(document).ready(function() {
	$('.sender_all').change(function() {
		if($(this).is(":checked")) {
			$("#fixed-item-list").html('');  
		}   
	});
});

function _remove_item(_this) {
	jQuery(_this).parent().remove();
}
</script>
<style type="text/css">
.ui-autocomplete { z-index:2147483647; }
}
</style>
<?php include('../footer.php'); ?>
