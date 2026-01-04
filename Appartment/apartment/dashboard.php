<?php
include('header.php');
if($_SESSION['login_type'] != '1' && $_SESSION['login_type'] != '5'){
	header("Location: " . WEB_URL . "logout.php");
	die();
}

include(ROOT_PATH.'language/'.$lang_code_global.'/lang_admin_dashboard.php');
/*count all sector for dashboard*/
$total_floor = 0;
$total_unit = 0;
$total_owner = 0;
$total_rented = 0;
$total_employee = 0;
$total_fair = 0;
$total_mc = 0;
$total_c = 0;
$total_owner_utility = 0;
$total_fund = 0;
$total_salary = 0;
$total_branch = 0;
$monthly_bill_data = '0,0,0,0,0,0,0,0,0,0,0,0';
$monthly_rent_data = '0,0,0,0,0,0,0,0,0,0,0,0';

//floor count
$result_floor = mysqli_query($link,"SELECT count(fid) as total_floor FROM tbl_add_floor WHERE branch_id = '".(int)$_SESSION['objLogin']['branch_id']."'");
if($row_floor_total = mysqli_fetch_array($result_floor)){
	$total_floor = $row_floor_total['total_floor'];
}
//unit count
$result_unit = mysqli_query($link,"SELECT count(uid) as total_unit FROM tbl_add_unit WHERE branch_id = '".(int)$_SESSION['objLogin']['branch_id']."'");
if($row_unit_total = mysqli_fetch_array($result_unit)){
	$total_unit = $row_unit_total['total_unit'];
}
//owner count
$result_owner = mysqli_query($link,"SELECT count(ownid) as total_owner FROM tbl_add_owner WHERE branch_id = '".(int)$_SESSION['objLogin']['branch_id']."'");
if($row_owner_total = mysqli_fetch_array($result_owner)){
	$total_owner = $row_owner_total['total_owner'];
}
//rented count
$result_rented = mysqli_query($link,"SELECT count(rid) as total_rent FROM tbl_add_rent WHERE branch_id = '".(int)$_SESSION['objLogin']['branch_id']."'");
if($row_rented_total = mysqli_fetch_array($result_rented)){
	$total_rented = $row_rented_total['total_rent'];
}
//employee count
$result_employee = mysqli_query($link,"SELECT count(eid) as total_employee FROM tbl_add_employee WHERE branch_id = '".(int)$_SESSION['objLogin']['branch_id']."'");
if($row_employee_total = mysqli_fetch_array($result_employee)){
	$total_employee = $row_employee_total['total_employee'];
}
//fair count
$result_fair = mysqli_query($link,"SELECT sum(rent) as total FROM tbl_add_fair WHERE branch_id = '".(int)$_SESSION['objLogin']['branch_id']."'");
if($row_fair_total = mysqli_fetch_array($result_fair)){
	if($row_fair_total['total'] > 0){
		$total_fair = $row_fair_total['total'];
	}
}
//maintaince count
$result_mc = mysqli_query($link,"SELECT sum(m_amount) as total FROM tbl_add_maintenance_cost WHERE branch_id = '".(int)$_SESSION['objLogin']['branch_id']."'");
if($row_mc_total = mysqli_fetch_array($result_mc)){
	if($row_mc_total['total'] > 0){
		$total_mc = $row_mc_total['total'];
	}
}
//fund count
$result_fund = mysqli_query($link,"SELECT sum(total_amount) as totals FROM tbl_add_fund WHERE branch_id = '".(int)$_SESSION['objLogin']['branch_id']."'");
if($row_fund_total = mysqli_fetch_array($result_fund)){
	if($row_fund_total['totals'] > 0){
		$total_fund = $row_fund_total['totals'];
	}
}
//comittee count
$result_c = mysqli_query($link,"SELECT count(mc_id) as total FROM tbl_add_management_committee WHERE branch_id = '".(int)$_SESSION['objLogin']['branch_id']."'");
if($row_c_total = mysqli_fetch_array($result_c)){
	$total_c = $row_c_total['total'];
}
//utility count
$result_ou = mysqli_query($link,"SELECT sum(water_bill) as w_bil,sum(electric_bill) as e_bil,sum(gas_bill) as g_bil,sum(security_bill) as s_bil,sum(utility_bill) as u_bil,sum(other_bill) as o_bil FROM tbl_add_fair where type = 'Owner'");
if($row_ou_total = mysqli_fetch_array($result_ou)){
	$total_owner_utility = (float)(float)$row_ou_total['w_bil'] + (float)$row_ou_total['e_bil'] + (float)$row_ou_total['g_bil'] + (float)$row_ou_total['u_bil'] + (float)$row_ou_total['s_bil'] + (float)$row_ou_total['o_bil'];
	$total_utility = $total_owner_utility;
}

//salary count
$result_salary = mysqli_query($link,"SELECT sum(amount) as totals FROM tbl_add_employee_salary_setup WHERE branch_id = '".(int)$_SESSION['objLogin']['branch_id']."'");
if($row_salary_total = mysqli_fetch_array($result_salary)){
	if($row_salary_total['totals'] > 0){
		$total_salary = $row_salary_total['totals'];
	}
}

//branch count
$result_branch = mysqli_query($link,"SELECT count(branch_id) as totals FROM tblbranch");
if($row_branch_total = mysqli_fetch_array($result_branch)){
	if($row_branch_total['totals'] > 0){
		$total_branch = $row_branch_total['totals'];
	}
}

////////////////////monthly bill deposit graph///////////////////////////////////////////////////////////////////////////////////////////////
$_graph_deposit = array();
$result_deposit = mysqli_query($link,"SELECT sum(`total_amount`) as total,m.month_name,y.xyear,b.bill_month
FROM tbl_add_bill b
INNER JOIN tbl_add_bill_type bt ON bt.bt_id = b.bill_type
INNER JOIN tbl_add_month_setup m ON m.m_id = b.bill_month
INNER JOIN tbl_add_year_setup y ON y.y_id = b.bill_year
WHERE b.branch_id=".(int)$_SESSION['objLogin']['branch_id']." and y.xyear=".date('Y')." group by b.`bill_month` order by b.`bill_month` ASC");
while($row_deposit_total = mysqli_fetch_assoc($result_deposit)){
	$_graph_deposit[$row_deposit_total['bill_month']] = $row_deposit_total;
}
$grapth_data = '';
if(!empty($_graph_deposit)){
	for($i=1;$i<=12;$i++){
		if(isset($_graph_deposit[$i])){
			$grapth_data .= $_graph_deposit[$i]['total']. ',';
		} else {
			$grapth_data .='0,';
		}
	}
}
if(!empty($grapth_data)){
	$monthly_bill_data = trim($grapth_data, ',');
}
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

////////////////////monthly rent graph///////////////////////////////////////////////////////////////////////////////////////////////
$_graph_rent = array();
$result_rent = mysqli_query($link,"SELECT sum(total_rent) as total, month_id FROM tbl_add_fair WHERE branch_id = '".(int)$_SESSION['objLogin']['branch_id']."' and xyear =".date('Y')." group by month_id order by month_id asc");
while($row_rent_total = mysqli_fetch_assoc($result_rent)){
	$_graph_rent[$row_rent_total['month_id']] = $row_rent_total;
}
$rent_data = '';
if(!empty($_graph_rent)){
	for($i=1;$i<=12;$i++){
		if(isset($_graph_rent[$i])){
			$rent_data .= $_graph_rent[$i]['total']. ',';
		} else {
			$rent_data .='0,';
		}
	}
}
if(!empty($rent_data)){
	$monthly_rent_data = trim($rent_data, ',');
}
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

//////////////////////////////////////////////////count complain//////////////////////////////////////////////////////////////////////////
$total_complain = 0;
$result = mysqli_query($link,"Select count(complain_id) as total_complain from tbl_add_complain c where c.branch_id = '" . (int)$_SESSION['objLogin']['branch_id'] . "'");
if($row = mysqli_fetch_array($result)){
	$total_complain = $row['total_complain'];
}
?>
<!-- Content Header (Page header) -->
<section class="content-header">
  <h1><?php echo $building_name . ' ' .$_data['dashboard_title']; ?></h1>
  <ol class="breadcrumb">
    <li><a href="<?php echo WEB_URL; ?>dashboard.php"><i class="fa fa-dashboard"></i> <?php echo $_data['home_breadcam']; ?></a></li>
    <li class="active"><?php echo $_data['home_breadcam']; ?></li>
  </ol>
</section>
<!-- Main content -->
<section class="content">
  <!-- /.row start -->
  <div class="row home_dash_box">
    <!-- col start -->
    <div class="col-lg-3 col-xs-6">
      <div class="small-box bg-green">
        <div class="inner">
          <h3><?php echo $total_floor; ?></h3>
          <p><?php echo $_data['dashboard_total_floor']; ?></p>
        </div>
        <div class="icon"> <img height="80" width="80" src="img/floor.png"></a> </div>
        <a href="<?php echo WEB_URL; ?>floor/floorlist.php" class="small-box-footer"><?php echo $_data['dashboard_more_info']; ?> <i class="fa fa-arrow-circle-right"></i></a> </div>
    </div>
    <!-- ./col end -->
    <!-- col start -->
    <div class="col-lg-3 col-xs-6">
      <div class="small-box bg-green">
        <div class="inner">
          <h3><?php echo $total_unit; ?></h3>
          <p><?php echo $_data['dashboard_total_unit']; ?></p>
        </div>
        <div class="icon"> <img height="80" width="80" src="img/room.png"></a> </div>
        <a href="<?php echo WEB_URL; ?>unit/unitlist.php" class="small-box-footer"><?php echo $_data['dashboard_more_info']; ?> <i class="fa fa-arrow-circle-right"></i></a> </div>
    </div>
    <!-- ./col end -->
    <!-- col start -->
    <div class="col-lg-3 col-xs-6">
      <div class="small-box bg-green">
        <div class="inner">
          <h3><?php echo $total_owner; ?></h3>
          <p><?php echo $_data['dashboard_total_owner']; ?></p>
        </div>
        <div class="icon"> <img height="80" width="80" src="img/owner.png"></a> </div>
        <a href="<?php echo WEB_URL; ?>owner/ownerlist.php" class="small-box-footer"><?php echo $_data['dashboard_more_info']; ?> <i class="fa fa-arrow-circle-right"></i></a> </div>
    </div>
    <!-- ./col end -->
    <!-- col start -->
    <div class="col-lg-3 col-xs-6">
      <div class="small-box bg-green">
        <div class="inner">
          <h3><?php echo $total_rented; ?></h3>
          <p><?php echo $_data['dashboard_total_rented']; ?></p>
        </div>
        <div class="icon"> <img height="80" width="80" src="img/rented.png"></a> </div>
        <a href="<?php echo WEB_URL; ?>rent/rentlist.php" class="small-box-footer"><?php echo $_data['dashboard_more_info']; ?> <i class="fa fa-arrow-circle-right"></i></a> </div>
    </div>
    <!-- ./col end -->
    <!-- col start -->
    <div class="col-lg-3 col-xs-6">
      <div class="small-box bg-green">
        <div class="inner">
          <h3><?php echo $total_employee; ?></h3>
          <p><?php echo $_data['dashboard_total_employee']; ?></p>
        </div>
        <div class="icon"> <img height="80" width="80" src="img/employee.png"></a> </div>
        <a href="<?php echo WEB_URL; ?>employee/employeelist.php" class="small-box-footer"><?php echo $_data['dashboard_more_info']; ?> <i class="fa fa-arrow-circle-right"></i></a> </div>
    </div>
    <!-- ./col end -->
    <!-- ./col end -->
    <div class="col-lg-3 col-xs-6">
      <div class="small-box bg-green">
        <div class="inner">
          <h3><?php echo $total_c; ?></h3>
          <p><?php echo $_data['dashboard_total_committee']; ?></p>
        </div>
        <div class="icon"> <img height="80" width="80" src="img/comittee.png"></a> </div>
        <a href="<?php echo WEB_URL; ?>management/m_committee_list.php" class="small-box-footer"><?php echo $_data['dashboard_more_info']; ?> <i class="fa fa-arrow-circle-right"></i></a> </div>
    </div>
    <!-- ./col end -->
    <!-- col start -->
    <div class="col-lg-3 col-xs-6">
      <div class="small-box bg-green">
        <div class="inner">
          <h3><?php echo $ams_helper->currency($localization, $total_fair); ?></h3>
          <p><?php echo $_data['dashboard_total_fare']; ?></p>
        </div>
        <div class="icon"> <img height="80" width="80" src="img/fair.png"></a> </div>
        <a href="<?php echo WEB_URL; ?>fair/fairlist.php" class="small-box-footer"><?php echo $_data['dashboard_more_info']; ?> <i class="fa fa-arrow-circle-right"></i></a> </div>
    </div>
    <!-- ./col end -->
    <!-- col start -->
    <div class="col-lg-3 col-xs-6">
      <div class="small-box bg-green">
        <div class="inner">
          <h3><?php echo $ams_helper->currency($localization, $total_mc); ?></h3>
          <p><?php echo $_data['dashboard_total_maintenance']; ?></p>
        </div>
        <div class="icon"> <img height="80" width="80" src="img/maintenance.png"></a> </div>
        <a href="<?php echo WEB_URL; ?>maintenance/maintenance_cost_list.php" class="small-box-footer"><?php echo $_data['dashboard_more_info']; ?> <i class="fa fa-arrow-circle-right"></i></a> </div>
    </div>
    <!-- ./col end -->
    <!-- col start -->
    <!-- col start -->
    <div class="col-lg-3 col-xs-6">
      <div class="small-box bg-green">
        <div class="inner">
          <h3><?php echo $ams_helper->currency($localization, $total_fund); ?></h3>
          <p><?php echo $_data['dashboard_total_fund']; ?></p>
        </div>
        <div class="icon"> <img height="80" width="80" src="img/fund.png"></a> </div>
        <a href="<?php echo WEB_URL; ?>fund/fund_list.php" class="small-box-footer"><?php echo $_data['dashboard_more_info']; ?> <i class="fa fa-arrow-circle-right"></i></a> </div>
    </div>
    <!-- col start -->
    <div class="col-lg-3 col-xs-6">
      <div class="small-box bg-green">
        <div class="inner">
          <h3><?php echo $ams_helper->currency($localization, $total_utility); ?></h3>
          <p><?php echo $_data['dashboard_total_owner_utility']; ?></p>
        </div>
        <div class="icon"> <img height="80" width="80" src="img/money-cash.png"></a> </div>
        <a href="<?php echo WEB_URL; ?>owner_utility/owner_utility_list.php" class="small-box-footer"><?php echo $_data['dashboard_more_info']; ?> <i class="fa fa-arrow-circle-right"></i></a> </div>
    </div>
    <!-- ./col end -->
    <!-- col start -->
    <div class="col-lg-3 col-xs-6">
      <div class="small-box bg-green">
        <div class="inner">
          <h3><?php echo $ams_helper->currency($localization, $total_salary); ?></h3>
          <p><?php echo $_data['dashboard_report']; ?></p>
        </div>
        <div class="icon"> <img height="80" width="80" src="img/cash-icon.png"></a> </div>
        <a href="<?php echo WEB_URL; ?>employee/employee_salary_setup.php" class="small-box-footer"><?php echo $_data['dashboard_more_info']; ?> <i class="fa fa-arrow-circle-right"></i></a> </div>
    </div>
    <!-- ./col end -->
    <!-- col start -->
    <div class="col-lg-3 col-xs-6">
      <div class="small-box bg-green">
        <div class="inner">
          <h3><?php echo $total_complain; ?></h3>
          <p><?php echo $_data['dashboard_total_complain']; ?></p>
        </div>
        <div class="icon"> <img height="80" width="80" src="img/chat-complain.png"></a> </div>
        <a href="<?php echo WEB_URL; ?>complain/complainlist.php" class="small-box-footer"><?php echo $_data['dashboard_more_info']; ?> <i class="fa fa-arrow-circle-right"></i></a> </div>
    </div>
    <!-- ./col end -->
  </div>
  <!-- /.row end -->
  <div class="row">
    <div class="col-lg-6 col-xs-12">
      <div class="box box-success">
        <div class="box-header with-border">
          <h3 class="box-title"><i class="fa fa-area-chart" aria-hidden="true"></i>&nbsp;&nbsp;<?php echo $_data['graph_text_1']; ?> <?php echo date('Y');?></h3>
        </div>
        <!-- /.box-header -->
        <div class="box-body">
          <div class="chart">
            <canvas id="salesChart" style="height: 180px;"></canvas>
          </div>
        </div>
      </div>
    </div>
	
	<div class="col-lg-6 col-xs-12">
      <div class="box box-success">
        <div class="box-header with-border">
          <h3 class="box-title"><i class="fa fa-area-chart" aria-hidden="true"></i>&nbsp;&nbsp;<?php echo $_data['graph_text_2']; ?> <?php echo date('Y');?></h3>
        </div>
        <!-- /.box-header -->
        <div class="box-body">
          <div class="chart">
            <canvas id="salesChartRent" style="height: 180px;"></canvas>
          </div>
        </div>
      </div>
    </div>
	
	
  </div>
  <!-- /.row end -->
  <div class="row">
    <div class="col-lg-6 col-xs-12">
      <div class="box box-success">
        <div class="box-header with-border">
          <h3 class="box-title">&nbsp;&nbsp;<i class="fa fa-comments" aria-hidden="true"></i> <?php echo $_data['c_table_header']; ?></h3>
        </div>
        <!-- /.box-header -->
        <div class="box-body" style="height:280px;">
          <table class="table table-bordered table-striped dt-responsive">
            <thead>
              <tr>
                <th><?php echo $_data['c_table_column_1']; ?></th>
                <th><?php echo $_data['c_table_column_2']; ?></th>
                <th style="text-align:center;"><?php echo $_data['c_table_column_3']; ?></th>
              </tr>
            </thead>
            <tbody>
              <?php
				$result = mysqli_query($link,"Select *,m.month_name from tbl_add_complain c inner join tbl_add_month_setup m on m.m_id = c.c_month where c.branch_id = '" . (int)$_SESSION['objLogin']['branch_id'] . "' order by complain_id DESC LIMIT 5");
				while($row = mysqli_fetch_array($result)){?>
              <tr>
                <td><?php echo $row['c_title']; ?></td>
                <td><?php echo $row['c_date']; ?></td>
                <td align="center"><a class="btn btn-success ams_btn_special" data-toggle="tooltip" href="javascript:;" onClick="$('#complain_view_<?php echo $row['complain_id']; ?>').modal('show');" data-original-title="<?php echo $_data['view_text'];?>"><i class="fa fa-eye"></i></a>
                  <div style="text-align:left;" id="complain_view_<?php echo $row['complain_id']; ?>" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                      <div class="modal-content">
                        <div class="modal-header green_header">
                          <button aria-label="Close" data-dismiss="modal" class="close" type="button"><span aria-hidden="true"><i class="fa fa-close"></i></span></button>
                          <h3 class="modal-title"><?php echo $_data['c_modal_top_header'];?></h3>
                        </div>
                        <div class="modal-body">
                          <h3 style="text-decoration:underline;"><?php echo $_data['details_information'];?></h3>
                          <div class="row">
                            <div class="col-xs-6"> <b><?php echo $_data['c_table_column_2'];?> :</b> <?php echo $row['c_date']; ?><br/>
                              <b><?php echo $_data['c_table_column_1'];?> :</b> <?php echo $row['c_title']; ?><br/>
                              <b><?php echo $_data['c_table_column_4'];?> :</b> <?php echo $row['month_name']; ?><br/>
                              <b><?php echo $_data['c_table_column_5'];?> :</b> <?php echo $row['c_year']; ?><br/>
                            </div>
                            <div class="col-xs-6"> <b><?php echo $_data['c_table_column_6'];?> :</b> <?php echo $row['c_description']; ?><br/>
                            </div>
                          </div>
                        </div>
                      </div>
                      <!-- /.modal-content -->
                    </div>
                  </div></td>
              </tr>
              <?php } ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
	
	<div class="col-lg-6 col-xs-12">
      <div class="box box-success">
        <div class="box-header with-border">
          <h3 class="box-title">&nbsp;&nbsp;<i class="fa fa-car" aria-hidden="true"></i> <?php echo $_data['v_table_header']; ?></h3>
        </div>
        <!-- /.box-header -->
        <div class="box-body" style="height:280px;">
          <table class="table table-bordered table-striped dt-responsive">
            <thead>
              <tr>
                <th><?php echo $_data['v_table_header']; ?></th>
                <th><?php echo $_data['v_table_header']; ?></th>
                <th style="text-align:center;"><?php echo $_data['c_table_column_3']; ?></th>
              </tr>
            </thead>
            <tbody>
              <?php
				$result = mysqli_query($link,"Select *,fr.floor_no,u.unit_no from tbl_visitor v inner join tbl_add_floor fr on fr.fid = v.floor_id inner join tbl_add_unit u on u.uid = v.unit_id where v.branch_id = " . (int)$_SESSION['objLogin']['branch_id'] . " order by v.vid DESC LIMIT 5");
				while($row = mysqli_fetch_array($result)){?>
              <tr>
                <td><?php echo $row['issue_date']; ?></td>
                <td><?php echo $row['name']; ?></td>
                <td align="center"><a class="btn btn-success ams_btn_special" data-toggle="tooltip" href="javascript:;" onClick="$('#visitor_view_<?php echo $row['vid']; ?>').modal('show');" data-original-title="<?php echo $_data['view_text'];?>"><i class="fa fa-eye"></i></a>
                  <div style="text-align:left;" id="visitor_view_<?php echo $row['vid']; ?>" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                      <div class="modal-content">
                        <div class="modal-header green_header">
                          <button aria-label="Close" data-dismiss="modal" class="close" type="button"><span aria-hidden="true"><i class="fa fa-close"></i></span></button>
                          <h3 class="modal-title"><?php echo $_data['v_modal_top_header'];?></h3>
                        </div>
                        <div class="modal-body">
                          <h3 style="text-decoration:underline;"><?php echo $_data['details_information'];?></h3>
                          <div class="row">
                            <div class="col-xs-6"> <b><?php echo $_data['v_table_column_1'];?> :</b> <?php echo $row['issue_date']; ?><br/>
                              <b><?php echo $_data['v_table_column_2'];?> :</b> <?php echo $row['name']; ?><br/>
                              <b><?php echo $_data['v_table_column_3'];?> :</b> <?php echo $row['mobile']; ?><br/>
                              <b><?php echo $_data['v_table_column_4'];?> :</b> <?php echo $row['address']; ?><br/></div>
							  <div class="col-xs-6"><b><?php echo $_data['v_table_column_5'];?> :</b> <?php echo $row['floor_no']; ?><br/>
							  <b><?php echo $_data['v_table_column_6'];?> :</b> <?php echo $row['unit_no']; ?><br/>
							  <b><?php echo $_data['v_table_column_7'];?> :</b> <?php echo $row['intime']; ?><br/>
							  <b><?php echo $_data['v_table_column_8'];?> :</b> <?php echo $row['outtime']; ?><br/>
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
      </div>
    </div>
  </div>
</section>
<script type="text/javascript">
  if($('#salesChart').length > 0){
  // Get context with jQuery - using jQuery's .get() method.
  var salesChartCanvas = $('#salesChart').get(0).getContext('2d');
  // This will get the first returned node in the jQuery collection.
  var salesChart       = new Chart(salesChartCanvas);

  var salesChartData = {
    //labels  : ['January', 'February', 'March', 'April', 'May', 'June', 'July ', 'August', 'September', 'October', 'November', 'December'],
	labels  : ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
    datasets: [
      {
        label               : 'Bill Report',
        fillColor           : 'rgba(0, 166, 90, 1)',
        strokeColor         : 'rgba(0, 166, 90, 1)',
        pointColor          : '#00a65a',
        pointStrokeColor    : 'rgba(0, 166, 90, 1)',
        pointHighlightFill  : '#fff',
        pointHighlightStroke: 'rgba(0, 166, 90, 1)',
        data                : [<?php echo $monthly_bill_data; ?>]
      }
    ]
  };

  var salesChartOptions = {
    // Boolean - If we should show the scale at all
    showScale               : true,
    // Boolean - Whether grid lines are shown across the chart
    scaleShowGridLines      : true,
    // String - Colour of the grid lines
    scaleGridLineColor      : 'rgba(0,0,0,.05)',
    // Number - Width of the grid lines
    scaleGridLineWidth      : 1,
    // Boolean - Whether to show horizontal lines (except X axis)
    scaleShowHorizontalLines: true,
    // Boolean - Whether to show vertical lines (except Y axis)
    scaleShowVerticalLines  : true,
    // Boolean - Whether the line is curved between points
    bezierCurve             : true,
    // Number - Tension of the bezier curve between points
    bezierCurveTension      : 0.3,
    // Boolean - Whether to show a dot for each point
    pointDot                : true,
    // Number - Radius of each point dot in pixels
    pointDotRadius          : 4,
    // Number - Pixel width of point dot stroke
    pointDotStrokeWidth     : 1,
    // Number - amount extra to add to the radius to cater for hit detection outside the drawn point
    pointHitDetectionRadius : 20,
    // Boolean - Whether to show a stroke for datasets
    datasetStroke           : true,
    // Number - Pixel width of dataset stroke
    datasetStrokeWidth      : 2,
    // Boolean - Whether to fill the dataset with a color
    datasetFill             : true,
    // String - A legend template
    tooltipTemplate: "<%if (label){%><%=label %>: <%}%><%= value + ' <?php echo $global_currency; ?>' %>",
	//legendTemplate          : '<ul class=\'<%=name.toLowerCase()%>-legend\'><% for (var i=0; i<datasets.length; i++){%><li><span style=\'background-color:<%=datasets[i].lineColor%>\'></span><%=datasets[i].label%></li><%}%></ul>',
    // Boolean - whether to maintain the starting aspect ratio or not when responsive, if set to false, will take up entire container
    maintainAspectRatio     : true,
    // Boolean - whether to make the chart responsive to window resizing
    responsive              : true
  };

  // Create the line chart
  salesChart.Line(salesChartData, salesChartOptions);

  // ---------------------------
  // - END MONTHLY SALES CHART -
  // ---------------------------
  }
  
  
  if($('#salesChartRent').length > 0){
  // Get context with jQuery - using jQuery's .get() method.
  var salesChartCanvas = $('#salesChartRent').get(0).getContext('2d');
  // This will get the first returned node in the jQuery collection.
  var salesChart       = new Chart(salesChartCanvas);

  var salesChartData = {
    //labels  : ['January', 'February', 'March', 'April', 'May', 'June', 'July ', 'August', 'September', 'October', 'November', 'December'],
	labels  : ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
    datasets: [
      {
        label               : 'Bill Report',
        fillColor           : 'rgba(0, 166, 90, 1)',
        strokeColor         : 'rgba(0, 166, 90, 1)',
        pointColor          : '#00a65a',
        pointStrokeColor    : 'rgba(0, 166, 90, 1)',
        pointHighlightFill  : '#fff',
        pointHighlightStroke: 'rgba(0, 166, 90, 1)',
        data                : [<?php echo $monthly_rent_data; ?>]
      }
    ]
  };

  var salesChartOptions = {
    // Boolean - If we should show the scale at all
    showScale               : true,
    // Boolean - Whether grid lines are shown across the chart
    scaleShowGridLines      : true,
    // String - Colour of the grid lines
    scaleGridLineColor      : 'rgba(0,0,0,.05)',
    // Number - Width of the grid lines
    scaleGridLineWidth      : 1,
    // Boolean - Whether to show horizontal lines (except X axis)
    scaleShowHorizontalLines: true,
    // Boolean - Whether to show vertical lines (except Y axis)
    scaleShowVerticalLines  : true,
    // Boolean - Whether the line is curved between points
    bezierCurve             : true,
    // Number - Tension of the bezier curve between points
    bezierCurveTension      : 0.3,
    // Boolean - Whether to show a dot for each point
    pointDot                : true,
    // Number - Radius of each point dot in pixels
    pointDotRadius          : 4,
    // Number - Pixel width of point dot stroke
    pointDotStrokeWidth     : 1,
    // Number - amount extra to add to the radius to cater for hit detection outside the drawn point
    pointHitDetectionRadius : 20,
    // Boolean - Whether to show a stroke for datasets
    datasetStroke           : true,
    // Number - Pixel width of dataset stroke
    datasetStrokeWidth      : 2,
    // Boolean - Whether to fill the dataset with a color
    datasetFill             : true,
    // String - A legend template
    tooltipTemplate: "<%if (label){%><%=label %>: <%}%><%= value + ' <?php echo $global_currency; ?>' %>",
	//legendTemplate          : '<ul class=\'<%=name.toLowerCase()%>-legend\'><% for (var i=0; i<datasets.length; i++){%><li><span style=\'background-color:<%=datasets[i].lineColor%>\'></span><%=datasets[i].label%></li><%}%></ul>',
    // Boolean - whether to maintain the starting aspect ratio or not when responsive, if set to false, will take up entire container
    maintainAspectRatio     : true,
    // Boolean - whether to make the chart responsive to window resizing
    responsive              : true
  };

  // Create the line chart
  salesChart.Line(salesChartData, salesChartOptions);

  // ---------------------------
  // - END MONTHLY SALES CHART -
  // ---------------------------
  }
 </script>
<!-- /.content -->
<?php include('footer.php'); ?>
