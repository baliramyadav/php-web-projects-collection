<?php 
include('../header_ten.php');
if(!isset($_SESSION['objLogin'])){
	header("Location: " . WEB_URL . "logout.php");
	die();
}
?>
<?php
include(ROOT_PATH.'language/'.$lang_code_global.'/lang_complain_list.php');
$delinfo = 'none';
$addinfo = 'none';
$msg = "";
if(isset($_GET['id']) && $_GET['id'] != '' && $_GET['id'] > 0){
	$sqlx= "DELETE FROM `tbl_add_complain` WHERE complain_id = ".$_GET['id'];
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
$employee_list = array();
$result_emp = mysqli_query($link,"Select *,m.member_type from tbl_add_employee e inner join tbl_add_member_type m on m.member_id = e. 	e_designation where e.branch_id = '" . (int)$_SESSION['objLogin']['branch_id'] . "' order by e.e_name ASC");
while($row_emp = mysqli_fetch_assoc($result_emp)){
	$employee_list[$row_emp['eid']] = $row_emp;
}
?>
<!-- Content Header (Page header) -->

<section class="content-header">
  <h1><?php echo $_data['t_text_1'];?></h1>
  <ol class="breadcrumb">
    <li><a href="<?php echo WEB_URL?>dashboard.php"><i class="fa fa-dashboard"></i><?php echo $_data['home_breadcam'];?></a></li>
    <li class="active"><?php echo $_data['text_2'];?></li>
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
    <div align="right" style="margin-bottom:1%;"> <a class="btn btn-success" data-toggle="tooltip" href="<?php echo WEB_URL; ?>t_dashboard/addcomplain.php" data-original-title="<?php echo $_data['text_3'];?>"><i class="fa fa-plus"></i></a></div>
    <div class="box box-success">
      <div class="box-header">
        <h3 class="box-title"><?php echo $_data['text_1'];?></h3>
      </div>
      <!-- /.box-header -->
      <div class="box-body">
        <table class="table sakotable table-bordered table-striped dt-responsive">
          <thead>
            <tr>
              <th><?php echo $_data['text_5'];?></th>
              <th><?php echo $_data['text_7'];?></th>
              <th><?php echo $_data['t_text_2'];?></th>
              <th><?php echo $_data['assign_job'];?></th>
              <th><?php echo $_data['action_text'];?></th>
            </tr>
          </thead>
          <tbody>
            <?php
				$result = mysqli_query($link,"Select *,m.month_name from tbl_add_complain c inner join tbl_add_month_setup m on m.m_id = c.c_month where c.branch_id = '" . (int)$_SESSION['objLogin']['branch_id'] . "' and c.c_userid = '" . (int)$_SESSION['objLogin']['rid'] . "' order by complain_id DESC");
				while($row = mysqli_fetch_array($result)){?>
            <tr>
              <td><?php echo $row['c_title']; ?></td>
              <td><?php echo $row['c_date']; ?></td>
              <td><?php if($row['job_status']=='0'){echo '<span class="label label-info ams-label">'.$_data['t_status_pending'].'</span>';} else if($row['job_status']=='1'){echo '<span class="label label-warning ams-label">'.$_data['t_status_in_progress'].'</span>';} else if($row['job_status']=='2'){echo '<span class="label label-danger ams-label">'.$_data['t_status_on_hold'].'</span>';} else if($row['job_status']=='3'){echo '<span class="label label-success ams-label">'.$_data['t_status_completed'].'</span>';} ?></td>
              <td><?php if(!empty($row['assign_employee_id']) && isset($employee_list[$row['assign_employee_id']])) { ?>
                <?php echo $employee_list[$row['assign_employee_id']]['e_name'] . ' (' . $employee_list[$row['assign_employee_id']]['member_type'].' )'; ?>
                <?php } ?></td>
              <td><a class="btn btn-success ams_btn_special" data-toggle="tooltip" href="javascript:;" onClick="$('#nurse_view_<?php echo $row['complain_id']; ?>').modal('show');" data-original-title="<?php echo $_data['view_text'];?>"><i class="fa fa-eye"></i></a> <a class="btn btn-warning ams_btn_special" data-toggle="tooltip" href="<?php echo WEB_URL;?>t_dashboard/addcomplain.php?id=<?php echo $row['complain_id']; ?>" data-original-title="<?php echo $_data['edit_text'];?>"><i class="fa fa-pencil"></i></a> <a class="btn btn-danger ams_btn_special" data-toggle="tooltip" onClick="deleteComplain(<?php echo $row['complain_id']; ?>);" href="javascript:;" data-original-title="<?php echo $_data['delete_text'];?>"><i class="fa fa-trash-o"></i></a>
                <div id="nurse_view_<?php echo $row['complain_id']; ?>" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                  <div class="modal-dialog">
                    <div class="modal-content">
                      <div class="modal-header green_header">
                        <button aria-label="Close" data-dismiss="modal" class="close" type="button"><span aria-hidden="true"><i class="fa fa-close"></i></span></button>
                        <h3 class="modal-title"><?php echo $_data['text_1_1_1'];?></h3>
                      </div>
                      <div class="modal-body model_view" align="center">&nbsp;
                        <div>
                          <!--<img class="photo_img_round" style="width:100px;height:100px;" src="<?php //echo $image;  ?>" />-->
                        </div>
                        <div class="model_title"><?php echo $row['c_title']; ?></div>
                      </div>
                      <div class="modal-body">
                        <h3 style="text-decoration:underline;"><?php echo $_data['details_information'];?></h3>
                        <div class="row">
                          <div class="col-xs-6"> <b><?php echo $_data['text_7'];?> :</b> <?php echo $row['c_date']; ?><br/>
                            <b><?php echo $_data['text_5'];?> :</b> <?php echo $row['c_title']; ?><br/>
                            <b><?php echo $_data['text_11'];?> :</b> <?php echo $row['month_name']; ?><br/>
                            <b><?php echo $_data['text_12'];?> :</b> <?php echo $row['c_year']; ?><br/>
                            <b><?php echo $_data['t_text_2'];?> :</b>
                            <?php if($row['job_status']=='0'){echo '<span class="label label-info ams-label">'.$_data['t_status_pending'].'</span>';} else if($row['job_status']=='1'){echo '<span class="label label-warning">'.$_data['t_status_in_progress'].'</span>';} else if($row['job_status']=='2'){echo '<span class="label label-danger">'.$_data['t_status_on_hold'].'</span>';} else if($row['job_status']=='3'){echo '<span class="label label-success">'.$_data['t_status_completed'].'</span>';} ?>
                            <br/>
                          </div>
                          <div class="col-xs-6"> <b><?php echo $_data['text_6'];?> :</b> <?php echo $row['c_description']; ?><br/>
                          </div>
                          <div class="row" align="center">
                            <div class="col-xs-12">
                              <h3 style="text-decoration:underline;"><?php echo $_data['solution_label'];?></h3>
                              <div><?php echo $row['solution']; ?></div>
                            </div>
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
function deleteComplain(Id){
  	var iAnswer = confirm("<?php echo $_data['confirm']; ?>");
	if(iAnswer){
		window.location = '<?php echo WEB_URL; ?>t_dashboard/complainlist.php?id=' + Id;
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
