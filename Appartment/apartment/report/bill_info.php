<?php
ob_start();
session_start();
include("../config.php");
if(!isset($_SESSION['objLogin'])){
	header("Location: ".WEB_URL."logout.php");
	die();
}
include(ROOT_PATH.'partial/report_top_common.php');
include(ROOT_PATH.'language/'.$lang_code_global.'/lang_bill_info.php');
include(ROOT_PATH.'language/'.$lang_code_global.'/lang_common.php');
include(ROOT_PATH.'library/helper.php');
$ams_helper = new ams_helper();
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title><?php echo $building_name; ?></title>
<meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
<?php include(ROOT_PATH.'/partial/header_script.php'); ?>
<script type="text/javascript">
function printContent(area,title){
	$("#"+area).printThis({
		 pageTitle: title
	});
}
</script>
<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
<!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<body>
<section class="content">
<!-- Main content -->
<div id="printable">
  <div align="center" style="margin:20px 50px 50px 50px;">
    <input type="hidden" id="web_url" value="<?php echo WEB_URL; ?>" />
    <div class="row">
      <div class="col-xs-12">
	  	<?php include(ROOT_PATH.'partial/report_top_title.php'); ?>
        <div class="box box-success">
          <div class="box-header">
            <h3 style="text-decoration:underline;font-weight:bold;color:black" class="box-title"><?php echo $_data['text_1'];?> </h3>
          </div>
          <div class="box-body">
            <table style="font-size:13px;" class="table sakotable table-bordered table-striped dt-responsive">
              <thead>
                <tr>
                  <th><?php echo $_data['text_3'];?></th>
				  <th><?php echo $_data['text_2'];?></th>
                  <th><?php echo $_data['text_4'];?></th>
                  <th><?php echo $_data['text_5'];?></th>
                  <th><?php echo $_data['text_6'];?></th>
                  <th><?php echo $_data['text_7'];?></th>
                  <th><?php echo $_data['text_8'];?></th>
                </tr>
              </thead>
              <tbody>
            <?php
				$query = "Select *,bt.bill_type as bt_type,m.month_name,y.xyear,bt.bt_id from tbl_add_bill b inner join tbl_add_bill_type bt on bt.bt_id = b.bill_type inner join tbl_add_month_setup m on m.m_id = b.bill_month inner join tbl_add_year_setup y on y.y_id = b.bill_year where b.branch_id = '" . (int)$_SESSION['objLogin']['branch_id'] . "'";
				if(!empty($_GET['vid'])){
				  	$query .= " and b.bill_date='".$_GET['vid']."'";
				}
				if(!empty($_GET['mid'])){
				  	$query .= " and b.bill_month='".$_GET['mid']."'";
				}
				if(!empty($_GET['yid'])){
				  	$query .= " and b.bill_year='".$_GET['yid']."'";
				}
				
				$result = mysqli_query($link,$query);
				while($row = mysqli_fetch_array($result)){?>
                    <td><?php echo $row['bill_date']; ?></td>
					<td><?php echo $row['bt_type']; ?></td>
                    <td><?php echo $row['month_name']; ?></td>
                    <td><?php echo $row['xyear']; ?></td>
                    <td><?php echo $ams_helper->currency($localization, $row['total_amount']); ?></td>
                    <td><?php echo $row['deposit_bank_name']; ?></td>
                    <td><?php echo $row['bill_details']; ?></td>
                </tr>
                <?php } mysqli_close($link);$link = NULL; ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<!-- /.row -->
<div align="center" style="position:fixed;top:0;right:0;margin:10px;"><a class="btn btn-success btn_save" title="<?php echo $_data['print'];?>" onClick="javascript:printContent('printable','Visitors Report');" href="javascript:void(0);"><i class="fa fa-print"></i> </a></div>
</body>
</html>
