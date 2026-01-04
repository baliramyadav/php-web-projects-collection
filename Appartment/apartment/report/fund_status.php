<?php
ob_start();
session_start();
include("../config.php");
if(!isset($_SESSION['objLogin'])){
	header("Location: ".WEB_URL."logout.php");
	die();
}

include(ROOT_PATH.'partial/report_top_common.php');
include(ROOT_PATH.'language/'.$lang_code_global.'/lang_fund_status.php');
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
            <h3 style="text-decoration:underline;font-weight:bold;color:black" class="box-title"><?php echo $_data['text_1'];?></h3>
          </div>
          <div class="box-body">
            <table style="font-size:13px;" class="table table-bordered table-striped dt-responsive">
              <thead>
                <tr>
                  <th><?php echo $_data['text_2'];?></th>
                  <th><?php echo $_data['text_3'];?></th>
                  <th><?php echo $_data['text_4'];?></th>
                  <th><?php echo $_data['text_5'];?></th>
                  <th><?php echo $_data['text_6'];?></th>
                  <th><?php echo $_data['text_8'];?></th>
				  <th><?php echo $_data['text_7'];?></th>
                </tr>
              </thead>
              <tbody>
                <?php
			$fund_sub_total = 0;
			$result = mysqli_query($link,"Select *,ow.o_name,ow.image,m.month_name,y.xyear as y_xyear from tbl_add_fund fu inner join tbl_add_owner ow on ow.ownid = fu.owner_id inner join tbl_add_month_setup m on m.m_id = fu.month_id inner join tbl_add_year_setup y on y.y_id = fu.xyear where fu.branch_id = '" . (int)$_SESSION['objLogin']['branch_id'] . "' order by fu.fund_id desc");
			while($row = mysqli_fetch_array($result)){
				$fund_sub_total +=(float)$row['total_amount'];
				$image = WEB_URL . 'img/no_image.jpg';	
				if(file_exists(ROOT_PATH . '/img/upload/' . $row['image']) && $row['image'] != ''){
					$image = WEB_URL . 'img/upload/' . $row['image'];
				}
				?>
                <tr>
                  <td><img class="photo_img_round" style="width:50px;height:50px;" src="<?php echo $image;  ?>" /></td>
                  <td><?php echo $row['f_date']; ?></td>
                  <td><?php echo $row['o_name']; ?></td>
                  <td><?php echo $row['month_name']; ?></td>
                  <td><?php echo $row['y_xyear']; ?></td>
                  <td><?php echo $row['purpose']; ?></td>
				  <td><?php echo $ams_helper->currency($localization, $row['total_amount']); ?></td>
                </tr>
                <?php } ?>
              </tbody>
              <tfoot>
                <tr>
                  <th>&nbsp;</th>
                  <th>&nbsp;</th>
                  <th>&nbsp;</th>
                  <th>&nbsp;</th>
                  <th>&nbsp;</th>
                  <th>&nbsp;</th>
				  <th style="color:red;"><?php echo $ams_helper->currency($localization, $fund_sub_total); ?></th>
                </tr>
              </tfoot>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- /.row -->
  <div align="center" style="margin:20px 50px 50px 50px;">
    <div class="row">
      <div class="col-xs-12">
        <div class="box box-success">
          <div class="box-header">
            <h3 style="text-decoration:underline;font-weight:bold;color:black" class="box-title"><?php echo $_data['text_9'];?></h3>
          </div>
          <div class="box-body">
            <table style="font-size:13px;" class="table table-bordered table-striped dt-responsive">
              <thead>
                <tr>
                  <th><?php echo $_data['text_10'];?></th>
                  <th><?php echo $_data['text_3'];?></th>
                  <th><?php echo $_data['text_11'];?></th>
				  <th><?php echo $_data['text_7'];?></th>
                </tr>
              </thead>
              <tbody>
                <?php
				$cost_sub_total = 0;
				$result_cost = mysqli_query($link,"Select * from tbl_add_maintenance_cost where branch_id = '".(int)$_SESSION['objLogin']['branch_id']."' order by mcid desc");
				while($row_cost = mysqli_fetch_array($result_cost)){
				$cost_sub_total +=(float)$row_cost['m_amount'];
				?>
              	<td><?php echo $row_cost['m_title']; ?></td>
                <td><?php echo $row_cost['m_date']; ?></td>
                <td><?php echo $row_cost['m_details']; ?></td>
				<td><?php echo $ams_helper->currency($localization, $row_cost['m_amount']); ?></td>
              </tr>
              <?php } mysqli_close($link);$link=NULL; ?>
              </tbody>
              
              <tfoot>
                <tr>
                  <th>&nbsp;</th>
                  <th>&nbsp;</th>
                  <th>&nbsp;</th>
				  <th style="color:red;"><?php echo $ams_helper->currency($localization, $cost_sub_total); ?></th>
                </tr>
              </tfoot>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- /.row -->
  <div align="center" style="margin:20px 50px 50px 50px;">
    <div class="row">
      <div class="col-xs-12">
        <div class="box box-success">
          <div class="box-header">
            <h3 style="text-decoration:underline;font-weight:bold;color:black" class="box-title"><?php echo $_data['text_12'];?></h3>
          </div>
          <div class="box-body">
            <table style="font-size:13px;text-align:center" class="table table-bordered table-striped dt-responsive">
              <tbody>
                <?php
           		$remain_balance = $fund_sub_total - $cost_sub_total;?>
			    <tr>
                  <td style="color:red; font-weight:bold; font-size:16px;"><?php echo $ams_helper->currency($localization, $remain_balance); ?></td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<!-- /.row -->
<div align="center" style="position:fixed;top:0;right:0;margin:10px;"><a title="<?php echo $_data['text_13'];?>" class="btn btn-success btn_save" onClick="javascript:printContent('printable','Visitors Report');" href="javascript:void(0);"><i class="fa fa-print"></i> </a></div>
</body>
</html>
