<?php include('header_owner.php'); ?>
<?php
include(ROOT_PATH.'language/'.$lang_code_global.'/lang_left_menu.php');
include(ROOT_PATH.'language/'.$lang_code_global.'/lang_common.php');
include(ROOT_PATH.'language/'.$lang_code_global.'/lang_admin_dashboard.php');

//
if(!isset($_SESSION['objLogin'])){
	header("Location: ".WEB_URL."logout.php");
	die();
}
//
$total_unit = 0;
$total_rented = 0;
$total_employee = 0;
$total_fair = 0;
$total_mc = 0;
$total_fund = 0;
$total_owner_utility = 0;
//unit count for owner
$result_unit = mysqli_query($link,"SELECT count(owner_id) as total_unit FROM tbl_add_owner_unit_relation where owner_id =".(int)$_SESSION['objLogin']['ownid']);
if($row_unit_total = mysqli_fetch_array($result_unit)){
	$total_unit = $row_unit_total['total_unit'];
}

//my rented
$result_rented = mysqli_query($link,"SELECT count(r.rid) as total_rent FROM tbl_add_owner_unit_relation ur inner join tbl_add_rent r on r.r_unit_no = ur.unit_id where ur.owner_id =".(int)$_SESSION['objLogin']['ownid']);
if($row_rented_total = mysqli_fetch_array($result_rented)){
	$total_rented = $row_rented_total['total_rent'];
}

//employee count
$result_employee = mysqli_query($link,"SELECT count(eid) as total_employee FROM tbl_add_employee where branch_id =".(int)$_SESSION['objLogin']['branch_id']);
if($row_employee_total = mysqli_fetch_array($result_employee)){
	$total_employee = $row_employee_total['total_employee'];
}

//fair count
$result_fair = mysqli_query($link,"SELECT sum(f.rent) as total FROM tbl_add_fair f inner join tbl_add_owner_unit_relation ur on ur.unit_id = f.unit_no where ur.owner_id =".(int)$_SESSION['objLogin']['ownid']);
if($row_fair_total = mysqli_fetch_array($result_fair)){
	$total_fair = $row_fair_total['total'];
}

//maintaince count
$result_mc = mysqli_query($link,"SELECT sum(m_amount) as total FROM tbl_add_maintenance_cost where branch_id =".(int)$_SESSION['objLogin']['branch_id']);
if($row_mc_total = mysqli_fetch_array($result_mc)){
	$total_mc = $row_mc_total['total'];
}
//fund count
$result_fund = mysqli_query($link,"SELECT sum(total_amount) as totals FROM tbl_add_fund where branch_id =".(int)$_SESSION['objLogin']['branch_id']);
if($row_fund_total = mysqli_fetch_array($result_fund)){
	$total_fund = $row_fund_total['totals'];
}

//utility count
$result_ou = mysqli_query($link,"SELECT sum(water_bill) as w_bil,sum(electric_bill) as e_bil,sum(gas_bill) as g_bil,sum(security_bill) as s_bil,sum(utility_bill) as u_bil,sum(other_bill) as o_bil FROM tbl_add_fair f inner join tbl_add_owner_unit_relation ur on ur.unit_id = f.unit_no where f.type = 'Owner' and ur.owner_id =".(int)$_SESSION['objLogin']['ownid']);
if($row_ou_total = mysqli_fetch_array($result_ou)){
	$total_owner_utility = (float)(float)$row_ou_total['w_bil'] + (float)$row_ou_total['e_bil'] + (float)$row_ou_total['g_bil'] + (float)$row_ou_total['u_bil'] + (float)$row_ou_total['s_bil'] + (float)$row_ou_total['o_bil'];
}

////////////////////monthly bill deposit graph///////////////////////////////////////////////////////////////////////////////////////////////
$_graph_monthly_rent = array();
$result_monthly_graph = mysqli_query($link,"SELECT *, sum(f.rent) as total_rent FROM tbl_add_fair f inner join tbl_add_owner_unit_relation ur on ur.unit_id = f.unit_no where ur.owner_id =".(int)$_SESSION['objLogin']['ownid']." GROUP BY month_id ORDER BY month_id ASC");
while($row_monthly_total = mysqli_fetch_assoc($result_monthly_graph)){
	$_graph_monthly_rent[$row_monthly_total['month_id']] = $row_monthly_total;
}
$grapth_data = '';
if(!empty($_graph_monthly_rent)){
	for($i=1;$i<=12;$i++){
		if(isset($_graph_monthly_rent[$i])){
			$grapth_data .= $_graph_monthly_rent[$i]['total_rent']. ',';
		} else {
			$grapth_data .='0,';
		}
	}
}
if(!empty($grapth_data)){
	$monthly_bill_data = trim($grapth_data, ',');
}
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

////////////////////////////////////////////NOTICE BOARD/////////////////////////////////////////////////////////////////////////////////////
$notice = '';
$result_notice = mysqli_query($link,"SELECT * FROM tbl_owner_notice_board where notice_status = 1 AND branch_id=".(int)$_SESSION['objLogin']['branch_id']);
if($row_notice_board = mysqli_fetch_array($result_notice)){
	$notice = $row_notice_board['notice_description'];
}
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
?>
<!-- Content Header (Page header) -->

<section class="content-header">
  <h1> <?php echo $_data['owner_dashboard'];?><small><?php echo $_data['control_panel'];?></small> </h1>
  <ol class="breadcrumb">
    <li><a href="<?php echo WEB_URL; ?>o_dashboard.php"><i class="fa fa-dashboard"></i> <?php echo $_data['home_breadcam'];?></a></li>
    <li class="active"><?php echo $_data['home_breadcam'];?></li>
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
          <h3><?php echo $total_unit; ?></h3>
          <p><?php echo $_data['text_1'];?></p>
        </div>
        <div class="icon"> <img height="80" width="80" src="img/room.png"></a> </div>
        <a href="<?php echo WEB_URL; ?>o_dashboard/unitdetails.php" class="small-box-footer"><?php echo $_data['dashboard_more_info']; ?> <i class="fa fa-arrow-circle-right"></i></a> </div>
    </div>
    <!-- ./col end -->
    <!-- col start -->
    <div class="col-lg-3 col-xs-6">
      <div class="small-box bg-green">
        <div class="inner">
          <h3><?php echo $total_rented; ?></h3>
          <p><?php echo $_data['text_2'];?></p>
        </div>
        <div class="icon"> <img height="80" width="80" src="img/owner.png"></a> </div>
        <a href="<?php echo WEB_URL; ?>o_dashboard/tenantdetails.php" class="small-box-footer"><?php echo $_data['dashboard_more_info']; ?> <i class="fa fa-arrow-circle-right"></i></a> </div>
    </div>
    <!-- ./col end -->
    <!-- col start -->
    <div class="col-lg-3 col-xs-6">
      <div class="small-box bg-green">
        <div class="inner">
          <h3><?php echo $total_employee; ?></h3>
          <p><?php echo $_data['text_3'];?></p>
        </div>
        <div class="icon"> <img height="80" width="80" src="img/employee.png"></a> </div>
        <a href="<?php echo WEB_URL; ?>o_dashboard/employeedetails.php" class="small-box-footer"><?php echo $_data['dashboard_more_info']; ?> <i class="fa fa-arrow-circle-right"></i></a> </div>
    </div>
    <!-- ./col end -->
    <!-- col start -->
    <div class="col-lg-3 col-xs-6">
      <div class="small-box bg-green">
        <div class="inner">
          <h3><?php echo $ams_helper->currency($localization, $total_fair); ?></h3>
          <p><?php echo $_data['text_4'];?></p>
        </div>
        <div class="icon"> <img height="80" width="80" src="img/fair.png"></a> </div>
        <a href="<?php echo WEB_URL; ?>o_dashboard/fairdetails.php" class="small-box-footer"><?php echo $_data['dashboard_more_info']; ?> <i class="fa fa-arrow-circle-right"></i></a> </div>
    </div>
    <!-- ./col end -->
    <!-- col start -->
    <div class="col-lg-3 col-xs-6">
      <div class="small-box bg-green">
        <div class="inner">
          <h3><?php echo $ams_helper->currency($localization, $total_owner_utility); ?></h3>
          <p><?php echo $_data['text_5'];?></p>
        </div>
        <div class="icon"> <img height="80" width="80" src="img/cash.png"></a> </div>
        <a href="<?php echo WEB_URL; ?>o_dashboard/owner_utility_details.php" class="small-box-footer"><?php echo $_data['dashboard_more_info']; ?> <i class="fa fa-arrow-circle-right"></i></a> </div>
    </div>
    <!-- ./col end -->
    <!-- col start -->
    <div class="col-lg-3 col-xs-6">
      <div class="small-box bg-green">
        <div class="inner">
          <h3><?php echo $ams_helper->currency($localization, $total_mc); ?></h3>
          <p><?php echo $_data['text_7'];?></p>
        </div>
        <div class="icon"> <img height="80" width="80" src="img/maintenance.png"></a> </div>
        <a href="<?php echo WEB_URL; ?>o_dashboard/maintenance_cost.php" class="small-box-footer"><?php echo $_data['dashboard_more_info']; ?> <i class="fa fa-arrow-circle-right"></i></a> </div>
    </div>
    <!-- ./col end -->
    <!-- col start -->
    <div class="col-lg-3 col-xs-6">
      <div class="small-box bg-green">
        <div class="inner">
          <h3><?php echo $ams_helper->currency($localization, $total_fund); ?></h3>
          <p><?php echo $_data['text_8'];?></p>
        </div>
        <div class="icon"> <img height="80" width="80" src="img/fund.png"></a> </div>
        <a href="<?php echo WEB_URL; ?>o_dashboard/fund_list.php" class="small-box-footer"><?php echo $_data['dashboard_more_info']; ?> <i class="fa fa-arrow-circle-right"></i></a> </div>
    </div>
    <!-- ./col end -->
    <!-- col start -->
    <div class="col-lg-3 col-xs-6">
      <div class="small-box bg-green">
        <div class="inner">
          <h3><?php echo $_data['infomation'];?></h3>
          <p><?php echo $building_name;?></p>
        </div>
        <div class="icon"> <img height="80" width="80" src="img/information.png"></a> </div>
        <a href="javascript:;" data-toggle="modal" data-target="#help_desk" class="small-box-footer"><?php echo $_data['dashboard_more_info'];?> <i class="fa fa-arrow-circle-right"></i></a> </div>
    </div>
    <!-- ./col end -->
  </div>
  <!-- /.row end -->
  <div class="row">
    <div class="col-lg-6 col-xs-12">
      <div class="box box-success">
        <div class="box-header with-border">
          <h3 class="box-title"><i class="fa fa-area-chart" aria-hidden="true"></i>&nbsp;&nbsp;<?php echo $_data['graph_text_11']; ?> <?php echo date('Y');?></h3>
        </div>
        <!-- /.box-header -->
        <div class="box-body">
          <div class="chart">
            <canvas id="salesChart"></canvas>
          </div>
        </div>
      </div>
    </div>
    <div class="col-lg-6 col-xs-12">
      <div class="box box-success">
        <div class="box-header with-border">
          <h3 class="box-title"><i class="fa fa-comments" aria-hidden="true"></i>&nbsp;&nbsp;<?php echo $_data['graph_text_111']; ?></h3>
        </div>
        <!-- /.box-header -->
        <div class="box-body">
          <table class="table table-bordered table-striped dt-responsive">
            <thead>
              <tr>
                <th><?php echo $_data['o_text_5'];?></th>
                <th><?php echo $_data['o_text_7'];?></th>
                <th><?php echo $_data['action_text'];?></th>
              </tr>
            </thead>
            <tbody>
              <?php
				$result = mysqli_query($link,"Select *,m.month_name from tbl_add_complain c inner join tbl_add_month_setup m on m.m_id = c.c_month where c.branch_id = '" . (int)$_SESSION['objLogin']['branch_id'] . "' and c.c_userid = '" . (int)$_SESSION['objLogin']['ownid'] . "' order by c.complain_id DESC");
				while($row = mysqli_fetch_array($result)){?>
              <tr>
                <td><?php echo $row['c_title']; ?></td>
                <td><?php echo $row['c_date']; ?></td>
                <td><a class="btn btn-success ams_btn_special" data-toggle="tooltip" href="javascript:;" onClick="$('#nurse_view_<?php echo $row['complain_id']; ?>').modal('show');" data-original-title="<?php echo $_data['view_text'];?>"><i class="fa fa-eye"></i></a>
                  <div id="nurse_view_<?php echo $row['complain_id']; ?>" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                      <div class="modal-content">
                        <div class="modal-header green_header">
                          <button aria-label="Close" data-dismiss="modal" class="close" type="button"><span aria-hidden="true"><i class="fa fa-close"></i></span></button>
                          <h3 class="modal-title"><?php echo $_data['o_text_1_1_1'];?></h3>
                        </div>
                        <div class="modal-body">
                          <h3 style="text-decoration:underline;"><?php echo $_data['details_information'];?></h3>
                          <div class="row">
                            <div class="col-xs-6"> <b><?php echo $_data['o_text_7'];?> :</b> <?php echo $row['c_date']; ?><br/>
                              <b><?php echo $_data['o_text_5'];?> :</b> <?php echo $row['c_title']; ?><br/>
                              <b><?php echo $_data['o_text_11'];?> :</b> <?php echo $row['month_name']; ?><br/>
                              <b><?php echo $_data['o_text_12'];?> :</b> <?php echo $row['c_year']; ?><br/>
                            </div>
                            <div class="col-xs-6"> <b><?php echo $_data['o_text_6'];?> :</b> <?php echo $row['c_description']; ?><br/>
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
    <div class="col-lg-12 col-xs-12">
      <div class="box box-success">
        <div class="box-header with-border">
          <h3 class="box-title"><i class="fa fa-bullhorn" aria-hidden="true"></i>&nbsp;&nbsp;<?php echo $_data['c_notice_board']; ?></h3>
        </div>
        <div class="box-body"> <?php echo $notice; ?> </div>
      </div>
    </div>
    <div class="col-lg-12 col-xs-12" id="rules">
      <div class="box box-success">
        <div class="box-header with-border">
          <h3 class="box-title"><i class="fa fa-gavel" aria-hidden="true"></i>&nbsp;&nbsp;<?php echo $_data['c_apartment_rules']; ?></h3>
        </div>
        <div class="box-body"> <?php echo $building_rules; ?> </div>
      </div>
    </div>
  </div>
  <div id="help_desk" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header green_header">
          <button aria-label="Close" data-dismiss="modal" class="close" type="button"><span aria-hidden="true"><i class="fa fa-close"></i></span></button>
          <h3 class="modal-title"><?php echo $_data['infomation'];?></h3>
        </div>
        <div class="modal-body">
          <div class="row">
            <div align="center"><img height="100" width="100" src="img/help_desk.png"><br/>
              <h2><u><?php echo $building_name; ?></u></h2>
            </div>
            <div class="col-md-12">
              <table class="table">
                <tr>
                  <td><?php echo $_data['help_desk_1']; ?> </td>
                  <td><?php echo $moderator_mobile; ?></td>
                </tr>
                <tr>
                  <td><?php echo $_data['help_desk_2']; ?> </td>
                  <td><?php echo $secrataty_mobile; ?></td>
                </tr>
                <tr>
                  <td><?php echo $_data['help_desk_3']; ?> </td>
                  <td><?php echo $security_guard_mobile; ?></td>
                </tr>
              </table>
            </div>
          </div>
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
 </script>
<!-- /.content -->
<?php include('footer.php'); ?>
