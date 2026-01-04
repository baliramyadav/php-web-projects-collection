<?php include('../header_owner.php');
include(ROOT_PATH.'language/'.$lang_code_global.'/lang_owner_rented_report.php');
include(ROOT_PATH.'language/'.$lang_code_global.'/lang_common.php');
?>
<?php
$floor_id =  "";
$unit_id = "";
$month_id = "";
$xyear = '';
$button_text = $_data['submit'];

if(isset($_GET['fid'])){
	$floor_id = $_GET['fid'];
}
if(isset($_GET['uid'])){
	$unit_id = $_GET['uid'];
}
if(isset($_GET['mid'])){
	$month_id = $_GET['mid'];
}
if(isset($_GET['xyear'])){
	$yid = $_GET['xyear'];
}
?>

<section class="content-header">
  <h1><?php echo $_data['text_1'];?> </h1>
  <ol class="breadcrumb">
    <li><a href="<?php echo WEB_URL?>o_dashboard.php"><i class="fa fa-dashboard"></i> <?php echo $_data['home_breadcam'];?></a></li>
    <li class="active"><a href="<?php echo WEB_URL?>o_dashboard/rented_report.php"><?php echo $_data['text_2'];?></a></li>
    <li class="active"><?php echo $_data['text_1'];?></li>
  </ol>
</section>
<!-- Main content -->
<section class="content">
<!-- Full Width boxes (Stat box) -->
<div class="row">
  <div class="col-md-12">
    <div align="right" style="margin-bottom:1%;"> <a class="btn btn-success" data-toggle="tooltip" href="<?php echo WEB_URL; ?>o_dashboard.php" data-original-title="<?php echo $_data['owner_dashboard'];?>"><i class="fa fa-dashboard"></i></a> </div>
    <div class="box box-success">
      <div class="box-header">
        <h3 class="box-title"><?php echo $_data['text_3'];?></h3>
      </div>
      <form onSubmit="return validateMe();" action="<?php echo $form_url; ?>" method="post" enctype="multipart/form-data">
        <div class="box-body">
          <div class="form-group">
            <label for="ddlUnitNo"><?php echo $_data['text_4'];?> :</label>
            <select name="ddlUnitNo" id="ddlUnitNo" class="form-control">
              <option value="">--<?php echo $_data['text_4'];?>--</option>
              <?php 
			  $result_unit = mysqli_query($link,"SELECT *,our.owner_id FROM tbl_add_unit u inner join tbl_add_owner_unit_relation our on u.uid = our.unit_id where our.owner_id = '". (int)$_SESSION['objLogin']['ownid'] . "' order by u.uid ASC");
					while($row_unit = mysqli_fetch_array($result_unit)){?>
              <option <?php if($unit_id == $row_unit['uid']){echo 'selected';}?> value="<?php echo $row_unit['uid'];?>"><?php echo $row_unit['unit_no'];?></option>
              <?php } ?>
            </select>
          </div>
          <div class="form-group">
            <label for="ddlMonth"><?php echo $_data['text_5'];?> :</label>
            <select name="ddlMonth" id="ddlMonth" class="form-control">
              <option value="">--<?php echo $_data['text_5'];?>--</option>
              <?php 
			  $result_month = mysqli_query($link,"SELECT * FROM tbl_add_month_setup order by m_id ASC");
					while($row_month = mysqli_fetch_array($result_month)){?>
              <option <?php if($month_id == $row_month['m_id']){echo 'selected';}?> value="<?php echo $row_month['m_id'];?>"><?php echo $row_month['month_name'];?></option>
              <?php } ?>
            </select>
          </div>
           <div class="form-group">
            <label for="ddlYear"><?php echo $_data['text_6'];?> :</label>
            <select name="ddlYear" id="ddlYear" class="form-control">
              <option value="">--<?php echo $_data['text_6'];?>--</option>
              <?php 
				  	$result_month = mysqli_query($link,"SELECT * FROM tbl_add_year_setup order by y_id ASC");
					while($row_month = mysqli_fetch_array($result_month)){?>
              <option value="<?php echo $row_month['xyear'];?>"><?php echo $row_month['xyear'];?></option>
              <?php } ?>
            </select>
          </div>
          <div class="form-group pull-right">
            <input type="button" onclick="getFairInfo()" value="<?php echo $button_text;?>" class="btn btn-success"/>
          </div>
        </div>
        <input type="hidden" value="<?php echo $hdnid; ?>" name="hdn"/>
      </form>
      <!-- /.box-body -->
    </div>
    <!-- /.box -->
  </div>
</div>
<!-- /.row -->

<script type="text/javascript">
	function getFairInfo(){
		var unit_id = $("#ddlUnitNo").val();
		var month_id = $("#ddlMonth").val();
		var xyear = $("#ddlYear").val();
		window.open('<?php echo WEB_URL;?>o_dashboard/rented_all_info.php?uid=' + unit_id + '&mid=' + month_id + '&yid=' + xyear,'_blank');
	}
</script>

<?php include('../footer.php'); ?>
