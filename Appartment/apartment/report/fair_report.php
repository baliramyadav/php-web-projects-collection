<?php
include('../header.php');
include(ROOT_PATH.'language/'.$lang_code_global.'/lang_fair_report.php');
if(!isset($_SESSION['objLogin'])){
	header("Location: " . WEB_URL . "logout.php");
	die();
}
?>
<section class="content-header">
  <h1><?php echo $_data['text_1'];?></h1>
  <ol class="breadcrumb">
    <li><a href="<?php echo WEB_URL?>dashboard.php"><i class="fa fa-dashboard"></i><?php echo $_data['home_breadcam'];?></a></li>
    <li class="active"><a href="<?php echo WEB_URL?>report/fair_report.php"><?php echo $_data['text_2'];?></a></li>
    <li class="active"><?php echo $_data['text_1'];?></li>
  </ol>
</section>
<!-- Main content -->
<section class="content">
<!-- Full Width boxes (Stat box) -->
<div class="row">
  <div class="col-md-12">
    <div align="right" style="margin-bottom:1%;"> </div>
    <div class="box box-success">
      <div class="box-header">
        <h3 class="box-title"><?php echo $_data['text_3'];?></h3>
      </div>
        <div class="box-body row">
          <div class="form-group col-md-6">
            <label for="ddlFloorNo"><?php echo $_data['text_4'];?> :</label>
            <select onchange="getUnitReport(this.value)" name="ddlFloorNo" id="ddlFloorNo" class="form-control">
              <option value="">--<?php echo $_data['text_4'];?>--</option>
              <?php 
			  $result_floor = mysqli_query($link,"SELECT * FROM tbl_add_floor where branch_id = ".(int)$_SESSION['objLogin']['branch_id']." order by fid ASC");
					while($row_floor = mysqli_fetch_array($result_floor)){?>
              <option  value="<?php echo $row_floor['fid'];?>"><?php echo $row_floor['floor_no'];?></option>
              <?php } ?>
            </select>
          </div>
          <div class="form-group col-md-6">
            <label for="ddlUnitNo"><?php echo $_data['text_5'];?> :</label>
            <select name="ddlUnitNo" id="ddlUnitNo" class="form-control">
              <option value="">--<?php echo $_data['text_5'];?>--</option>
            </select>
          </div>
          <div class="form-group col-md-6">
            <label for="ddlMonth"><?php echo $_data['text_6'];?> :</label>
            <select name="ddlMonth" id="ddlMonth" class="form-control">
              <option value="">--<?php echo $_data['text_6'];?>--</option>
              <?php 
			  $result_month = mysqli_query($link,"SELECT * FROM tbl_add_month_setup order by m_id ASC");
					while($row_month = mysqli_fetch_array($result_month)){?>
              <option value="<?php echo $row_month['m_id'];?>"><?php echo $row_month['month_name'];?></option>
              <?php } ?>
            </select>
          </div>
		  <div class="form-group col-md-6">
            <label for="ddlYear"><?php echo $_data['text_66'];?> :</label>
            <select name="ddlYear" id="ddlYear" class="form-control">
              <option value="">--<?php echo $_data['text_66'];?>--</option>
              <?php 
				  	$rs = mysqli_query($link,"SELECT * FROM tbl_add_year_setup order by y_id ASC");
					while($rows = mysqli_fetch_array($rs)){?>
              <option  value="<?php echo $rows['xyear'];?>"><?php echo $rows['xyear'];?></option>
              <?php }mysqli_close($link);$link = NULL; ?>
            </select>
          </div>
		  
		  <div class="form-group col-md-6">
            <label for="ddlPaymentStatus"><?php echo $_data['text_8'];?> :</label>
            <select name="ddlPaymentStatus" id="ddlPaymentStatus" class="form-control">
              <option value="">--<?php echo $_data['text_88'];?>--</option>
			  <option value="1"><?php echo $_data['text_99'];?></option>
			  <option value="0"><?php echo $_data['text_100'];?></option>
             
            </select>
          </div>
		  
		  
        </div>
        <div class="box-footer">
          <div class="form-group pull-right">
            <input type="button" onclick="getFairInfo()" value="<?php echo $_data['text_7'];?>" class="btn btn-success"/>
          </div>
        </div>
      <!-- /.box-body -->
    </div>
    <!-- /.box -->
  </div>
</div>
<!-- /.row -->
<script type="text/javascript">
	function getFairInfo(){
		var floor_id = $("#ddlFloorNo").val();
		var unit_id = $("#ddlUnitNo").val();
		var month_id = $("#ddlMonth").val();
		var year_id = $("#ddlYear").val();
		var status = $("#ddlPaymentStatus").val();
		if(floor_id == '' && unit_id == '' && month_id == '' && year_id == ''){
			alert('<?php echo $_data['validation']; ?>');
		} else {
			window.open('<?php echo WEB_URL;?>report/fair_info_floor.php?fid=' + floor_id + '&uid=' + unit_id + '&mid=' + month_id + '&yid=' + year_id + '&ps=' + status,'_blank');
		}
		
		/*if(floor_id != '' && unit_id != '' && month_id != '' && year_id != ''){
			window.open('report/fair_info_all.php?fid=' + floor_id + '&uid=' + unit_id + '&mid=' + month_id + '&yid=' + year_id,'_blank');
		}
		else if(floor_id != '' && unit_id != ''){
			window.open('report/fair_info_floor_unit.php?fid=' + floor_id + '&uid=' + unit_id,'_blank');
		}
		else if(floor_id != ''){
			window.open('report/fair_info_floor.php?fid=' + floor_id,'_blank');
		}
		else if(month_id != ''){
			window.open('report/fair_info_month.php?mid=' + month_id,'_blank');
		}
		
		else{
			alert('Please select at least one or more fields');
		}*/
	}
</script>
<?php include('../footer.php'); ?>
