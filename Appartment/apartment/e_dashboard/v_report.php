<?php include('../header_emp.php');?>
<?php
include(ROOT_PATH.'language/'.$lang_code_global.'/lang_employee_e_report.php');
$month_id = "";
$xyear = '';
$button_text = $_data['submit'];

if(isset($_GET['mid'])){
	$month_id = $_GET['mid'];
}
if(isset($_GET['xyear'])){
	$yid = $_GET['xyear'];
}
if(!isset($_SESSION['objLogin'])){
	header("Location: ".WEB_URL."logout.php");
	die();
}
?>

<section class="content-header">
  <h1><?php echo $_data['text_1_1'];?></h1>
  <ol class="breadcrumb">
    <li><a href="<?php echo WEB_URL?>e_dashboard.php"><i class="fa fa-dashboard"></i> <?php echo $_data['home_breadcam'];?></a></li>
    <li class="active"><?php echo $_data['text_1_1'];?></li>
  </ol>
</section>
<!-- Main content -->
<section class="content">
<!-- Full Width boxes (Stat box) -->
<div class="row">
  <div class="col-md-12">
    <div align="right" style="margin-bottom:1%;"> <a class="btn btn-success" data-toggle="tooltip" href="<?php echo WEB_URL; ?>e_dashboard.php" data-original-title="<?php echo $_data['dashboard'];?>"><i class="fa fa-dashboard"></i></a> </div>
    <div class="box box-success">
      <div class="box-header">
        <h3 class="box-title"><?php echo $_data['text_2_2'];?></h3>
      </div>
      <div class="box-body row">
		<div class="form-group col-md-12">
          <label for="txtIssueDate"><?php echo $_data['text_3_3'];?> :</label>
          <input type="text" class="form-control datepicker" id="txtIssueDate" name="txtIssueDate" />
        </div>
		<div class="form-group col-md-6">
          <label for="ddlMonth"><?php echo $_data['text_3'];?> :</label>
          <select name="ddlMonth" id="ddlMonth" class="form-control">
            <option value="">--<?php echo $_data['text_3'];?>--</option>
            <?php 
			  $result_month = mysqli_query($link,"SELECT * FROM tbl_add_month_setup order by m_id ASC");
					while($row_month = mysqli_fetch_array($result_month)){?>
            <option value="<?php echo $row_month['m_id'];?>"><?php echo $row_month['month_name'];?></option>
            <?php } ?>
          </select>
        </div>
        <div class="form-group col-md-6">
          <label for="ddlYear"><?php echo $_data['text_4'];?> :</label>
          <select name="ddlYear" id="ddlYear" class="form-control">
            <option value="">--<?php echo $_data['text_4'];?>--</option>
            <?php 
				  	$result_month = mysqli_query($link,"SELECT * FROM tbl_add_year_setup order by y_id ASC");
					while($row_year = mysqli_fetch_array($result_month)){?>
            <option value="<?php echo $row_year['xyear'];?>"><?php echo $row_year['xyear'];?></option>
            <?php } ?>
          </select>
        </div>
        <div class="form-group col-md-12" align="right">
          <input type="button" onclick="getFairInfo()" value="<?php echo $button_text;?>" class="btn btn-success"/>
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
		var issue_date = $("#txtIssueDate").val();
		var month = $("#ddlMonth").val();
		var xyear = $("#ddlYear").val();
		window.open('<?php echo WEB_URL;?>e_dashboard/e_visitor_report.php?mid=' + month + '&yid=' + xyear + '&issue_date=' + issue_date,'_blank');
	}
</script>
<?php include('../footer.php'); ?>
