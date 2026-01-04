<?php
include('../header_ten.php');
include(ROOT_PATH.'language/'.$lang_code_global.'/lang_rented_tenant_details.php');
include(ROOT_PATH.'language/'.$lang_code_global.'/lang_common.php');
if(!isset($_SESSION['objLogin'])){
	header("Location: " . WEB_URL . "logout.php");
	die();
}
?>

<!-- Content Header (Page header) -->
<section class="content-header">
  <h1> <?php echo $_data['text_1'];?> </h1>
  <ol class="breadcrumb">
    <li><a href="<?php echo WEB_URL?>t_dashboard.php"><i class="fa fa-dashboard"></i> <?php echo $_data['home_breadcam'];?></a></li>
    <li class="active"><?php echo $_data['text_1'];?></li>
  </ol>
</section>
<!-- Main content -->
<section class="content">
<!-- Full Width boxes (Stat box) -->
<div class="row">
  <div class="col-xs-12">
    <div align="right" style="margin-bottom:1%;"> </div>
    <div class="box box-success">
      <div class="box-header">
        <h3 class="box-title"><?php echo $_data['text_1'];?></h3>
      </div>
      <!-- /.box-header -->
      <div class="box-body">
        <table class="table sakotable table-bordered table-striped dt-responsive">
          <thead>
            <tr>
              <th><?php echo $_data['text_2'];?></th>
              <th><?php echo $_data['text_11'];?></th>
              <th><?php echo $_data['text_3'];?></th>
              <th><?php echo $_data['text_4'];?></th>
			  <th><?php echo $_data['text_20'];?></th>
			  <th><?php echo $_data['text_24'];?></th>
              <th><?php echo $_data['action_text'];?></th>
            </tr>
          </thead>
          <tbody>
        	<?php
				$result = mysqli_query($link,"Select *,fl.floor_no as fl_floor,u.unit_no as u_unit,r.r_name,m.month_name from tbl_add_fair f inner join tbl_add_floor fl on fl.fid = f.floor_no inner join tbl_add_unit u on u.uid = f.unit_no inner join tbl_add_rent r on r.r_unit_no = f.unit_no inner join tbl_add_month_setup m on m.m_id = f.month_id where f.unit_no = ".(int)$_SESSION['objLogin']['r_unit_no']." and f.branch_id = ".(int)$_SESSION['objLogin']['branch_id']." order by f.month_id ASC");
				while($row = mysqli_fetch_assoc($result)){
					$image = WEB_URL . 'img/no_image.jpg';	
			if(file_exists(ROOT_PATH . '/img/upload/' . $_SESSION['objLogin']['image']) && $_SESSION['objLogin']['image'] != ''){
				$image = WEB_URL . 'img/upload/' . $_SESSION['objLogin']['image'];
			}
			?>
            <tr>
            <td><?php echo $row['month_name']; ?>, <?php echo $row['xyear']; ?></td>
			<td><?php echo $ams_helper->currency($localization, $_SESSION['objLogin']['r_rent_pm']); ?></td>
			<td><?php echo $ams_helper->currency($localization, $row['total_rent']); ?></td>
            <td><?php echo $row['issue_date']; ?></td>
			<td><?php echo ($row['bill_status']=='1') ? '<label class="label label-success">'.$_data['text_21'].'</label>' : '<label class="label label-danger">'.$_data['text_22'].'</label>'; ?></td>
            <td><?php echo $row['paid_date']; ?></td>
			<td>
            <a target="_blank" class="btn btn-info ams_btn_special" data-original-title="<?php echo $_data['text_23'];?>" data-toggle="tooltip" href="<?php echo WEB_URL;?>t_dashboard/invoice.php?rentid=<?php echo $row['f_id']; ?>"><i class="fa fa-file-text-o"></i></a> <a class="btn btn-success ams_btn_special" data-toggle="tooltip" href="javascript:;" onclick="$('#nurse_view_<?php echo $row['f_id']; ?>').modal('show');" data-original-title="Details <?php echo $_data['view_text'];?>"><i class="fa fa-eye"></i></a>
            <div id="nurse_view_<?php echo $row['f_id']; ?>" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
              <div class="modal-dialog">
                <div class="modal-content">
                  <div class="modal-header green_header">
                    <button aria-label="Close" data-dismiss="modal" class="close" type="button"><span aria-hidden="true"><i class="fa fa-close"></i></span></button>
                    <h3 class="modal-title"><?php echo $_data['text_1'];?></h3>
                  </div>
                  <div class="modal-body model_view" align="center">&nbsp;
                    <div><img class="photo_img_round" style="width:100px;height:100px;" src="<?php echo $image;  ?>" /></div>
                    <div class="model_title"><?php echo $_SESSION['objLogin']['r_name']; ?></div>
                  </div>
				  <div class="modal-body">
                    <h3 style="text-decoration:underline;"><?php echo $_data['details_information'];?></h3>
                    <div class="row">
                      <div class="col-xs-6"> 
					    <b><?php echo $_data['text_5'];?> :</b> <?php echo $_SESSION['objLogin']['r_name']; ?><br/>
                        <b><?php echo $_data['text_6'];?> :</b> <?php echo $_SESSION['objLogin']['r_email']; ?><br/>
                        <b><?php echo $_data['text_7'];?> :</b> <?php echo $_SESSION['objLogin']['r_contact']; ?><br/>
                        <b><?php echo $_data['text_8'];?> :</b> <?php echo $_SESSION['objLogin']['r_address']; ?><br/>
                        <b><?php echo $_data['text_9'];?> :</b> <?php echo $row['fl_floor']; ?><br/>
                        <b><?php echo $_data['text_10'];?> :</b> <?php echo $row['u_unit']; ?><br/>
                        <b><?php echo $_data['text_2'];?> :</b> <?php echo $row['month_name']; ?><br/>
                        <b><?php echo $_data['text_11'];?> :</b> <?php echo $ams_helper->currency($localization, $row['rent']); ?><br/>
                        </div>
                        <div class="col-xs-6"> 
                        <b><?php echo $_data['text_12'];?> :</b> <?php echo $ams_helper->currency($localization, $row['water_bill']); ?><br/>
						<b><?php echo $_data['text_13'];?> :</b> <?php echo $ams_helper->currency($localization, $row['electric_bill']); ?><br/>
						<b><?php echo $_data['text_14'];?> :</b> <?php echo $ams_helper->currency($localization, $row['gas_bill']); ?><br/>
						<b><?php echo $_data['text_15'];?> :</b> <?php echo $ams_helper->currency($localization, $row['security_bill']); ?><br/>
						<b><?php echo $_data['text_16'];?> :</b> <?php echo $ams_helper->currency($localization, $row['utility_bill']); ?><br/>
						<b><?php echo $_data['text_17'];?> :</b> <?php echo $ams_helper->currency($localization, $row['other_bill']); ?><br/>
						<b><?php echo $_data['text_18'];?> :</b> <?php echo $ams_helper->currency($localization, $row['total_rent']); ?><br/>
                        <b><?php echo $_data['text_4'];?> :</b> <?php echo $row['issue_date']; ?><br/>
                      </div>
                    </div>
                  </div>
				  
                </div>
                <!-- /.modal-content -->
              </div>
            </div>
            </td>
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
<?php include('../footer.php'); ?>
