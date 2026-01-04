<?php 
include('../header.php');
if(!isset($_SESSION['objLogin'])){
	header("Location: " . WEB_URL . "logout.php");
	die();
}
?>
<?php
include(ROOT_PATH.'language/'.$lang_code_global.'/lang_leave_request.php');
$delinfo = 'none';
$addinfo = 'none';
$msg = "";
if(isset($_GET['id']) && $_GET['id'] != '' && $_GET['id'] > 0){
	$sqlx= "DELETE FROM `tbl_employee_leave_request` WHERE leave_id = ".$_GET['id'];
	mysqli_query($link,$sqlx); 
	$delinfo = 'block';
}
if(isset($_GET['m']) && $_GET['m'] == 'add'){
	$addinfo = 'block';
	$msg = $_data['text_8'];
}
if(isset($_GET['m']) && $_GET['m'] == 'up'){
	$addinfo = 'block';
	$msg = $_data['text_9'];
}
?>
<!-- Content Header (Page header) -->

<section class="content-header">
  <h1><?php echo $_data['t_text_1'];?></h1>
  <ol class="breadcrumb">
    <li><a href="<?php echo WEB_URL?>dashboard.php"><i class="fa fa-dashboard"></i><?php echo $_data['home_breadcam'];?></a></li>
    <li class="active"><?php echo $_data['text_2_2'];?></li>
    <li class="active"><?php echo $_data['text_1'];?></li>
  </ol>
</section>
<!-- Main content -->
<section class="content">
<!-- Full Width boxes (Stat box) -->
<div class="row">
  <div class="col-xs-12">
    <div id="me" class="alert alert-danger alert-dismissable" style="display:<?php echo $delinfo; ?>">
      <button aria-hidden="true" data-dismiss="alert" class="close" type="button"><i class="fa fa-close"></i></button>
      <h4><i class="icon fa fa-ban"></i> <?php echo $_data['delete_text'];?> !</h4>
      <?php echo $_data['text_10'];?> </div>
    <div id="you" class="alert alert-success alert-dismissable" style="display:<?php echo $addinfo; ?>">
      <button aria-hidden="true" data-dismiss="alert" class="close" type="button"><i class="fa fa-close"></i></button>
      <h4><i class="icon fa fa-check"></i> <?php echo $_data['success'];?> !</h4>
      <?php echo $msg; ?> </div>
    <div align="right" style="margin-bottom:1%;"> <a class="btn btn-success" data-toggle="tooltip" href="<?php echo WEB_URL; ?>dashboard.php" data-original-title="<?php echo $_data['home_breadcam'];?>"><i class="fa fa-dashboard"></i></a> </div>
    <div class="box box-success">
      <div class="box-header">
        <h3 class="box-title"><?php echo $_data['text_1'];?></h3>
      </div>
      <!-- /.box-header -->
      <div class="box-body">
        <table class="table sakotable table-bordered table-striped dt-responsive">
          <thead>
            <tr>
              <th><?php echo $_data['requested_date']; ?></th>
			  <th><?php echo $_data['emp_name']; ?></th>
			  <th><?php echo $_data['emp_designation']; ?></th>
			  <th><?php echo $_data['leave_days']; ?></th>
			  <th><?php echo $_data['leave_status']; ?></th>
              <th><?php echo $_data['action_text'];?></th>
            </tr>
          </thead>
          <tbody>
            <?php
				$result = mysqli_query($link,"Select * from tbl_employee_leave_request r inner join tbl_add_employee e on e.eid = r.employee_id inner join tbl_add_member_type m on m.member_id = e.e_designation where r.branch_id = ".(int)$_SESSION['objLogin']['branch_id']." order by r.request_date ASC");
				while($row = mysqli_fetch_array($result)){?>
            <tr>
              <td><?php echo date('d/m/Y', strtotime($row['request_date'])); ?></td>
			  <td><?php echo $row['e_name']; ?></td>
			  <td><label class="label label-success ams_label"><?php echo $row['member_type']; ?></label></td>
			  <td><label class="label label-success ams_label"><?php echo $ams_helper->daysBetween($row['from'], $row['to']);?></label></td>
			  <!--<td><label class="label label-info ams_label"><?php //echo $row['request_status']; ?></label></td>-->
			  <td><select style="font-size:13px;" onchange="change_leave_status(this.value,<?php echo $row['leave_id']; ?>);"><option <?php if($row['request_status']=='Accepted'){echo 'selected';}?> value="Accepted">Accepted</option><option <?php if($row['request_status']=='Pending'){echo 'selected';}?> value="Pending">Pending</option><option <?php if($row['request_status']=='Rejected'){echo 'selected';}?> value="Rejected">Rejected</option></select></td>
              <td><a class="btn btn-success ams_btn_special" data-toggle="tooltip" href="javascript:;" onClick="$('#nurse_view_<?php echo $row['leave_id']; ?>').modal('show');" data-original-title="<?php echo $_data['view_text'];?>"><i class="fa fa-eye"></i></a> <a class="btn btn-danger ams_btn_special" data-toggle="tooltip" onClick="deleteLeaveData(<?php echo $row['leave_id']; ?>);" href="javascript:;" data-original-title="<?php echo $_data['delete_text'];?>"><i class="fa fa-trash-o"></i></a>
                <div id="nurse_view_<?php echo $row['leave_id']; ?>" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                  <div class="modal-dialog">
                    <div class="modal-content">
                      <div class="modal-header green_header">
                        <button aria-label="Close" data-dismiss="modal" class="close" type="button"><span aria-hidden="true"><i class="fa fa-close"></i></span></button>
                        <h3 class="modal-title"><?php echo $_data['text_1_1_1'];?></h3>
                      </div>
                      <div class="modal-body">
                        <h3 style="text-decoration:underline;"><?php echo $_data['details_information'];?></h3>
                        <div class="row">
                          <div class="col-xs-6"> <b><?php echo $_data['leave_from']; ?> :</b> <?php echo $ams_helper->mySqlToDatePicker($row['from']); ?><br/>
                            <b><?php echo $_data['leave_to'];?> :</b> <?php echo $ams_helper->mySqlToDatePicker($row['to']); ?><br/>
                            <b><?php echo $_data['leave_days'];?> :</b> <?php echo $ams_helper->daysBetween($row['from'], $row['to']); ?><br/>
                            <b><?php echo $_data['leave_status'];?> :</b> <?php echo $row['request_status']; ?><br/></div>
							<div class="col-xs-6">
							<b><?php echo $_data['leave_description'];?> :</b> <?php echo $row['leave_text']; ?><br/>
                          </div>
                        </div>
                      </div>
                    </div>
                    <!-- /.modal-content -->
                  </div>
                </div></td>
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
<script type="text/javascript">
function deleteLeaveData(Id){
  	var iAnswer = confirm("<?php echo $_data['text_100']; ?>");
	if(iAnswer){
		window.location = '<?php echo WEB_URL; ?>employee/leave_request_list.php?id=' + Id;
	}
  }
  $( document ).ready(function() {
	setTimeout(function() {
		$("#me").hide(300);
		$("#you").hide(300);
	}, 3000);
});
</script>
<?php include('../footer.php'); ?>
