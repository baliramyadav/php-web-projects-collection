<?php
include('../header.php');
include(ROOT_PATH.'language/'.$lang_code_global.'/lang_salary_report.php');
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
          <div class="form-group col-md-12">
            <label for="ddlEmpName"><span class="errorStar">*</span> <?php echo $_data['text_4'];?> :</label>
            <select name="ddlEmpName" id="ddlEmpName" class="form-control">
              <option value="">--<?php echo $_data['text_4'];?>--</option>
              <?php 
				  	$result_emp = mysqli_query($link,"SELECT *,m.member_type FROM tbl_add_employee e inner join tbl_add_member_type m on e.e_designation = m.member_id where e.branch_id = ".(int)$_SESSION['objLogin']['branch_id']." order by eid ASC");
					while($row_emp = mysqli_fetch_array($result_emp)){?>
              <option value="<?php echo $row_emp['eid'];?>"><?php echo $row_emp['e_name'] . ' ('.$row_emp['member_type'].')';?></option>
              <?php } ?>
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
              <option  value="<?php echo $rows['y_id'];?>"><?php echo $rows['xyear'];?></option>
              <?php }mysqli_close($link); ?>
            </select>
          </div>
        </div>
        <div class="box-footer">
          <div class="form-group pull-right">
            <input type="button" onclick="getSalaryReport()" value="<?php echo $_data['text_7'];?>" class="btn btn-success"/>
          </div>
        </div>
      <!-- /.box-body -->
    </div>
    <!-- /.box -->
  </div>
</div>
<!-- /.row -->
<script type="text/javascript">
	function getSalaryReport(){
		var employee_id = $("#ddlEmpName").val();
		var month_id = $("#ddlMonth").val();
		var year_id = $("#ddlYear").val();
		if(employee_id == '' && month_id == '' && year_id == ''){
			alert('<?php echo $_data['validation']; ?>');
		} else {
			window.open('<?php echo WEB_URL;?>report/employee_salary_report.php?eid=' + employee_id + '&mid=' + month_id + '&yid=' + year_id,'_blank');
		}
	}
</script>
<?php include('../footer.php'); ?>
