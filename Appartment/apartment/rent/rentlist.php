<?php include('../header.php');
if(!isset($_SESSION['objLogin'])){
	header("Location: " . WEB_URL . "logout.php");
	die();
}
?>
<?php
include(ROOT_PATH.'language/'.$lang_code_global.'/lang_rented_list.php');
$delinfo = 'none';
$addinfo = 'none';
$msg = "";
if(isset($_GET['id']) && $_GET['id'] != '' && $_GET['id'] > 0){
	$result = mysqli_query($link,"SELECT * FROM tbl_add_rent where rid = '" . $_GET['id'] . "'");
	if($row = mysqli_fetch_array($result)){
		$sqlx = "UPDATE `tbl_add_unit` set status = 0 where floor_no = '".(int)$row['r_floor_no']."' and uid = '".(int)$row['r_unit_no']."'";
		mysqli_query($link,$sqlx);
	}
	$sqlx= "DELETE FROM `tbl_add_rent` WHERE rid = ".$_GET['id'];
	mysqli_query($link,$sqlx); 
	$delinfo = 'block';
}
if(isset($_GET['m']) && $_GET['m'] == 'add'){
	$addinfo = 'block';
	$msg = $_data['added_renter_successfully'];
}
if(isset($_GET['m']) && $_GET['m'] == 'up'){
	$addinfo = 'block';
	$msg = $_data['update_renter_successfully'];
}
?>
<!-- Content Header (Page header) -->

<section class="content-header">
  <h1><?php echo $_data['renter_list'];?></h1>
  <ol class="breadcrumb">
    <li><a href="<?php echo WEB_URL?>dashboard.php"><i class="fa fa-dashboard"></i><?php echo $_data['home_breadcam'];?></a></li>
    <li class="active"><?php echo $_data['add_new_renter_information_breadcam'];?></li>
	<li class="active"><?php echo $_data['renter_list'];?></li>
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
      <?php echo $_data['delete_renter_information'];?> </div>
    <div id="you" class="alert alert-success alert-dismissable" style="display:<?php echo $addinfo; ?>">
      <button aria-hidden="true" data-dismiss="alert" class="close" type="button"><i class="fa fa-close"></i></button>
      <h4><i class="icon fa fa-check"></i> <?php echo $_data['success'];?> !</h4>
      <?php echo $msg; ?> </div>
    <div align="right" style="margin-bottom:1%;"> <a class="btn btn-success" data-toggle="tooltip" href="<?php echo WEB_URL; ?>rent/addrent.php" data-original-title="<?php echo $_data['add_new_rent_breadcam'];?>"><i class="fa fa-plus"></i></a> <a class="btn btn-success" data-toggle="tooltip" href="<?php echo WEB_URL; ?>dashboard.php" data-original-title="<?php echo $_data['home_breadcam'];?>"><i class="fa fa-dashboard"></i></a> </div>
    <div class="box box-success">
      <div class="box-header">
        <h3 class="box-title"><?php echo $_data['renter_list'];?></h3>
      </div>
      <!-- /.box-header -->
      <div class="box-body">
        <table class="table sakotable table-bordered table-striped dt-responsive">
          <thead>
            <tr>
              <th><?php echo $_data['add_new_form_field_text_0'];?></th>
              <th><?php echo $_data['add_new_form_field_text_1'];?></th>
              <th><?php echo $_data['add_new_form_field_text_4'];?></th>
              <th><?php echo $_data['add_new_form_field_text_8'];?></th>
              <th><?php echo $_data['add_new_form_field_text_9'];?></th>
              <th><?php echo $_data['add_new_form_field_text_10'];?></th>
              <th><?php echo $_data['add_new_form_field_text_14'];?></th>
              <th><?php echo $_data['action_text'];?></th>
            </tr>
          </thead>
          <tbody>
            <?php
				$result = mysqli_query($link,"Select *,f.floor_no as ffloor,u.unit_no from tbl_add_rent r inner join tbl_add_floor f on f.fid = r.r_floor_no inner join tbl_add_unit u on u.uid = r.r_unit_no where r.branch_id = " . (int)$_SESSION['objLogin']['branch_id'] . " order by r.r_unit_no asc");
				while($row = mysqli_fetch_array($result)){
					$image = WEB_URL . 'img/no_image.jpg';	
					if(file_exists(ROOT_PATH . '/img/upload/' . $row['image']) && $row['image'] != ''){
						$image = WEB_URL . 'img/upload/' . $row['image'];
					}
				
				?>
            <tr>
              <td><img class="photo_img_round" style="width:50px;height:50px;" src="<?php echo $image;  ?>" /></td>
              <td><?php echo $row['r_name']; ?></td>
              <td><?php echo $row['r_contact']; ?></td>
              <td><label class="label label-success ams_label"><?php echo $row['unit_no']; ?></label></td>
              <td><?php echo $ams_helper->currency($localization, $row['r_advance']); ?></td>
			  <td><?php echo $ams_helper->currency($localization, $row['r_rent_pm']); ?></td>
              <td><?php if($row['r_status'] == '1'){echo '<label class="label label-success ams_label">'.$_data['add_new_form_field_text_16'].'</label>';} else{echo '<label class="label label-danger ams_label">'.$_data['add_new_form_field_text_17'].'</label>';}?>
              <td><a class="btn btn-success ams_btn_special" data-toggle="tooltip" href="javascript:;" onClick="$('#nurse_view_<?php echo $row['rid']; ?>').modal('show');" data-original-title="<?php echo $_data['view_text'];?>"><i class="fa fa-eye"></i></a> <a class="btn btn-warning ams_btn_special" data-toggle="tooltip" href="<?php echo WEB_URL;?>rent/addrent.php?id=<?php echo $row['rid']; ?>" data-original-title="<?php echo $_data['edit_text'];?>"><i class="fa fa-pencil"></i></a> <a class="btn btn-danger ams_btn_special" data-toggle="tooltip" onClick="deleteRent(<?php echo $row['rid']; ?>);" href="javascript:;" data-original-title="<?php echo $_data['delete_text'];?>"><i class="fa fa-trash-o"></i></a>
                <div id="nurse_view_<?php echo $row['rid']; ?>" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                  <div class="modal-dialog">
                    <div class="modal-content">
                      <div class="modal-header green_header">
                        <button aria-label="Close" data-dismiss="modal" class="close" type="button"><span aria-hidden="true"><i class="fa fa-close"></i></span></button>
                        <h3 class="modal-title"><?php echo $_data['rented_details'];?></h3>
                      </div>
                      <div class="modal-body model_view" align="center">&nbsp;
                        <div><img style="width:200px;height:200px;border-radius:200px;" src="<?php echo $image;  ?>" /></div>
                        <div class="model_title"><?php echo $row['r_name']; ?></div>
                      </div>
                      <div class="modal-body">
                        <h3 style="text-decoration:underline;"><?php echo $_data['details_information'];?></h3>
                        <div class="row">
                          <div class="col-xs-6"> <b><?php echo $_data['add_new_form_field_text_1'];?> :</b> <?php echo $row['r_name']; ?><br/>
                            <b><?php echo $_data['add_new_form_field_text_2'];?> :</b> <?php echo $row['r_email']; ?><br/>
                            <b><?php echo $_data['add_new_form_field_text_3'];?> :</b> <?php echo $converter->decode($row['r_password']); ?><br/>
                            <b><?php echo $_data['add_new_form_field_text_4'];?> :</b> <?php echo $row['r_contact']; ?><br/>
                            <b><?php echo $_data['add_new_form_field_text_5'];?> :</b> <?php echo $row['r_address']; ?><br/>
                            <b><?php echo $_data['add_new_form_field_text_6'];?> :</b> <?php echo $row['r_nid']; ?><br/>
                          </div>
                          <div class="col-xs-6"> <b><?php echo $_data['add_new_form_field_text_7'];?> :</b> <?php echo $row['ffloor']; ?><br/>
                            <b><?php echo $_data['add_new_form_field_text_8'];?> :</b> <?php echo $row['unit_no']; ?><br/>
                            <b><?php echo $_data['add_new_form_field_text_9'];?> : </b> <?php echo $ams_helper->currency($localization, $row['r_advance']); ?><br/>
							<b><?php echo $_data['add_new_form_field_text_10'];?> : </b> <?php echo $ams_helper->currency($localization, $row['r_rent_pm']); ?><br/>
                            <b><?php echo $_data['add_new_form_field_text_11'];?> :</b> <?php echo $row['r_date']; ?><br/>
                            <b><?php echo $_data['add_new_form_field_text_14'];?> :</b>
                            <?php if($row['r_status'] == '1'){echo $_data['add_new_form_field_text_16'];} else{echo $_data['add_new_form_field_text_17'];}?>
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
function deleteRent(Id){
  	var iAnswer = confirm("<?php echo $_data['confirm']; ?>");
	if(iAnswer){
		window.location = '<?php echo WEB_URL; ?>rent/rentlist.php?id=' + Id;
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
