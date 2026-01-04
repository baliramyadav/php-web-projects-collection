<?php
include('header_emp.php');
include(ROOT_PATH.'language/'.$lang_code_global.'/lang_admin_dashboard.php');
//
if(!isset($_SESSION['objLogin'])){
	header("Location: ".WEB_URL."logout.php");
	die();
}

//
$total_amount = 0; //default
$result_amount = mysqli_query($link,"SELECT sum(amount) as total FROM  tbl_add_employee_salary_setup where emp_name =".(int)$_SESSION['objLogin']['eid']." AND branch_id =".(int)$_SESSION['objLogin']['branch_id']);
if($row_amount_total = mysqli_fetch_array($result_amount)){
	$total_amount = $row_amount_total['total'];
}

//
$total_renter = 0; //default
$result_renter = mysqli_query($link,"Select count(r.rid) as total FROM tbl_add_rent r inner join tbl_add_floor f on f.fid = r.r_floor_no inner join tbl_add_unit u on u.uid = r.r_unit_no WHERE r.branch_id =".(int)$_SESSION['objLogin']['branch_id']." order by r.r_unit_no asc");
if($row_renter = mysqli_fetch_array($result_renter)){
	$total_renter = $row_renter['total'];
}

//
$total_employee = 0; //default
$result_employee = mysqli_query($link,"Select count(*) as total from tbl_add_management_committee mc inner join tbl_add_member_type mt on mt.member_id = mc.member_type where mc.branch_id = '". (int)$_SESSION['objLogin']['branch_id'] . "' order by mc_id desc");
if($row_employee = mysqli_fetch_array($result_employee)){
	$total_employee = $row_employee['total'];
}

//
/*$total_leave = 0;
$result_leave = mysqli_query($link,"Select count(*) as total from tbl_employee_leave_request r where r.branch_id = '" . (int)$_SESSION['objLogin']['branch_id'] . "' and r.employee_id = '" . (int)$_SESSION['objLogin']['eid'] . "' and request_status='Accepted'");
if($row_leave = mysqli_fetch_array($result_leave)){
	$total_leave = $row_leave['total'];
}*/

$total_leave = 0;
$result_leave = mysqli_query($link,"Select count(*) as total from tbl_add_owner o where o.branch_id=".(int)$_SESSION['objLogin']['branch_id']);
if($row_leave = mysqli_fetch_array($result_leave)){
	$total_leave = $row_leave['total'];
}

////////////////////monthly leave graph///////////////////////////////////////////////////////////////////////////////////////////////
/*$yearly_leave_data = '';
$year_label = '';

$_graph_yearly_leave = array();
$result_yearly_graph = mysqli_query($link,"SELECT *, YEAR(request_date) as leave_year, SUM(DATEDIFF(`to`,`from`))  as total_days FROM tbl_employee_leave_request where branch_id = '" . (int)$_SESSION['objLogin']['branch_id'] . "' and employee_id = '" . (int)$_SESSION['objLogin']['eid'] . "' AND request_status = 'Accepted' group by leave_year ORDER BY leave_year ASC");
while($row_yearly_data = mysqli_fetch_assoc($result_yearly_graph)){
	$_graph_yearly_leave[$row_yearly_data['leave_year']] = $row_yearly_data['total_days'];
}

$grapth_data = '';
if(!empty($_graph_yearly_leave)){
	$year_label = '';
	$yearly_leave_data = '';
	foreach($_graph_yearly_leave as $key => $value){
		$year_label .= $key.',';
		$yearly_leave_data .= $value.',';
	}
	$year_label = trim($year_label, ',');
	$yearly_leave_data = trim($yearly_leave_data, ',');
}*/

///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

////////////////////monthly leave graph///////////////////////////////////////////////////////////////////////////////////////////////
$monthly_salary_graph = '0,0,0,0,0,0,0,0,0,0,0,0';
$_salary_graph = array();
$result_salary_graph = mysqli_query($link,"SELECT * FROM tbl_add_employee_salary_setup where emp_name =".(int)$_SESSION['objLogin']['eid']." AND branch_id =".(int)$_SESSION['objLogin']['branch_id']);
while($row_monthly_salary_graph = mysqli_fetch_assoc($result_salary_graph)){
	$_salary_graph[$row_monthly_salary_graph['month_id']] = $row_monthly_salary_graph;
}
$grapth_data = '';
if(!empty($_salary_graph)){
	for($i=1;$i<=12;$i++){
		if(isset($_salary_graph[$i])){
			$grapth_data .= $_salary_graph[$i]['amount']. ',';
		} else {
			$grapth_data .='0,';
		}
	}
}
if(!empty($grapth_data)){
	$monthly_salary_graph = trim($grapth_data, ',');
}
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

////////////////////Monthly Visitor Graph///////////////////////////////////////////////////////////////////////////////////////////////
$monthly_visitor_graph = '0,0,0,0,0,0,0,0,0,0,0,0';
$_visitor_graph = array();
$result_visitor_graph = mysqli_query($link,"SELECT count(*) as total_visitor,xmonth FROM `tbl_visitor` WHERE xyear = ".date('Y')." and branch_id = ".(int)$_SESSION['objLogin']['branch_id']." group by xmonth order by xmonth ASC");
while($row_monthly_visitor_graph = mysqli_fetch_assoc($result_visitor_graph)){
	$_visitor_graph[$row_monthly_visitor_graph['xmonth']] = $row_monthly_visitor_graph;
}
$visitor_graph_data = '';
if(!empty($_visitor_graph)){
	for($i=1;$i<=12;$i++){
		if(isset($_visitor_graph[$i])){
			$visitor_graph_data .= $_visitor_graph[$i]['total_visitor']. ',';
		} else {
			$visitor_graph_data .='0,';
		}
	}
}
if(!empty($visitor_graph_data)){
	$monthly_visitor_graph = trim($visitor_graph_data, ',');
}
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////


////////////////////////////////////////////NOTICE BOARD/////////////////////////////////////////////////////////////////////////////////////
$notice = '';
$result_notice = mysqli_query($link,"SELECT * FROM tbl_employee_notice where notice_status = 1 AND branch_id=".(int)$_SESSION['objLogin']['branch_id']);
if($row_notice_board = mysqli_fetch_array($result_notice)){
	$notice = $row_notice_board['notice_description'];
}
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

?>
<!-- Content Header (Page header) -->

<section class="content-header">
  <h1><?php echo $_data['dashboard_emp'];?><small><?php echo $_data['control'];?></small> </h1>
  <ol class="breadcrumb">
    <li><a href="<?php echo WEB_URL; ?>e_dashboard.php"><i class="fa fa-dashboard"></i> <?php echo $_data['home_breadcam'];?></a></li>
    <li class="active"><?php echo $_data['dash'];?></li>
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
          <h3><?php echo $ams_helper->currency($localization, $total_amount); ?></h3>
          <p><?php echo $_data['salary_statement'];?></p>
        </div>
        <div class="icon"> <img height="80" width="80" src="img/fund.png"></a> </div>
        <a href="<?php echo WEB_URL; ?>e_dashboard/salary_details.php" class="small-box-footer"><?php echo $_data['dashboard_more_info'];?> <i class="fa fa-arrow-circle-right"></i></a> </div>
    </div>
    <!-- ./col end -->
    <!-- col start -->
    <div class="col-lg-3 col-xs-6">
      <div class="small-box bg-green">
        <div class="inner">
          <h3><?php echo $total_renter; ?></h3>
          <p><?php echo $_data['rented_details'];?></p>
        </div>
        <div class="icon"> <img height="80" width="80" src="img/rented.png"></a> </div>
        <a href="<?php echo WEB_URL; ?>e_dashboard/rented_details.php" class="small-box-footer"><?php echo $_data['dashboard_more_info'];?> <i class="fa fa-arrow-circle-right"></i></a> </div>
    </div>
    <!-- ./col end -->
    <!-- col start -->
    <div class="col-lg-3 col-xs-6">
      <div class="small-box bg-green">
        <div class="inner">
          <h3><?php echo $total_employee; ?></h3>
          <p><?php echo $_data['member_details'];?></p>
        </div>
        <div class="icon"> <img height="80" width="80" src="img/user.png"></a> </div>
        <a href="<?php echo WEB_URL; ?>e_dashboard/member_details.php" class="small-box-footer"><?php echo $_data['dashboard_more_info'];?> <i class="fa fa-arrow-circle-right"></i></a> </div>
    </div>
    <!-- ./col end -->
    <!-- col start -->
    <div class="col-lg-3 col-xs-6">
      <div class="small-box bg-green">
        <div class="inner">
          <h3><?php echo $total_leave; ?></h3>
          <p><?php echo $_data['e_total_owner'];?></p>
        </div>
        <div class="icon"> <img height="80" width="80" src="img/owner.png"></a> </div>
        <a href="<?php echo WEB_URL; ?>e_dashboard/ownerlist.php" class="small-box-footer"><?php echo $_data['dashboard_more_info'];?> <i class="fa fa-arrow-circle-right"></i></a> </div>
    </div>
    <!-- ./col end -->
  </div>
  <!-- /.row end -->
  <div class="row">
    <div class="col-lg-6 col-xs-12">
      <div class="box box-success">
        <div class="box-header with-border">
          <h3 class="box-title"><i class="fa fa-line-chart" aria-hidden="true"></i>&nbsp;&nbsp;<?php echo $_data['leave_graph_header_text']; ?></h3>
        </div>
        <!-- /.box-header -->
        <div class="box-body">
          <div class="chart">
            <?php if(!empty($monthly_visitor_graph)) { ?>
            <canvas id="salesChart"></canvas>
            <?php } else { ?>
            <h4 align="center"><?php echo $_data['leave_graph_no_record']; ?></h4>
            <?php }?>
          </div>
        </div>
      </div>
    </div>
    <div class="col-lg-6 col-xs-12">
      <div class="box box-success">
        <div class="box-header with-border">
          <h3 class="box-title"><i class="fa fa-area-chart" aria-hidden="true"></i>&nbsp;&nbsp;<?php echo $_data['salary_header_text']; ?></h3>
        </div>
        <!-- /.box-header -->
        <div class="box-body">
          <div class="chart">
            <canvas id="salaryChart"></canvas>
          </div>
        </div>
      </div>
    </div>
	
	
	<div class="col-lg-12 col-xs-12">
      <div class="box box-success">
        <div class="box-header with-border">
          <h3 class="box-title"><i class="fa fa-car" aria-hidden="true"></i>&nbsp;&nbsp;<?php echo $_data['e_last_5_visitors']; ?></h3>
        </div>
        <div class="box-body">
			
			<table class="table table-bordered table-striped dt-responsive">
          <thead>
            <tr>
              <th><?php echo $_data['ee_text_5'];?></th>
              <th><?php echo $_data['ee_text_6'];?></th>
              <th><?php echo $_data['ee_text_7'];?></th>
              <th><?php echo $_data['ee_text_8'];?></th>
              <th><?php echo $_data['ee_text_9'];?></th>
              <th class="text-center"><?php echo $_data['ee_text_11'];?></th>
              <th class="text-center"><?php echo $_data['ee_text_13'];?></th>
              <th class="text-center"><?php echo $_data['ee_text_14'];?></th>
            </tr>
          </thead>
          <tbody>
            <?php
				$result = mysqli_query($link,"Select *,fr.floor_no,u.unit_no from tbl_visitor v inner join tbl_add_floor fr on fr.fid = v.floor_id inner join tbl_add_unit u on u.uid = v.unit_id where v.branch_id = " . (int)$_SESSION['objLogin']['branch_id'] . " order by v.vid desc LIMIT 5");
				while($row = mysqli_fetch_array($result)){?>
            <tr>
              <td><label class="label label-info ams_label"><?php echo $row['issue_date']; ?></label></td>
              <td><?php echo $row['name']; ?></td>
              <td><?php echo $row['mobile']; ?></td>
              <td><?php echo $row['address']; ?></td>
              <td><label class="label label-primary ams_label"><?php echo $row['floor_no']; ?></label></td>
              <td align="center"><label class="label label-warning ams_label"><?php echo $row['unit_no']; ?></label></td>
              <td align="center"><label class="label label-success ams_label"><?php echo $row['intime']; ?></label></td>
              <td align="center"><label class="label label-danger ams_label"><?php echo $row['outtime']; ?></label></td>
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
</section>
<!-- /.content -->
<script type="text/javascript">
  if($('#salesChart').length > 0){
  // Get context with jQuery - using jQuery's .get() method.
  var salesChartCanvas = $('#salesChart').get(0).getContext('2d');
  // This will get the first returned node in the jQuery collection.
  var salesChart       = new Chart(salesChartCanvas);

  var salesChartData = {
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
        data                : [<?php echo $monthly_visitor_graph; ?>]
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
    tooltipTemplate: "<%if (label){%><%=label %>: <%}%><%= value + ' Visitor' %>",
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
  
  
  
  if($('#salaryChart').length > 0){
  // Get context with jQuery - using jQuery's .get() method.
  var salesChartCanvas = $('#salaryChart').get(0).getContext('2d');
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
        data                : [<?php echo $monthly_salary_graph; ?>]
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
<?php include('footer.php'); ?>
