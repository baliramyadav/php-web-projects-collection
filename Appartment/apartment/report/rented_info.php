<?php
ob_start();
session_start();
include("../config.php");
if(!isset($_SESSION['objLogin'])){
	header("Location: ".WEB_URL."logout.php");
	die();
}

include(ROOT_PATH.'partial/report_top_common.php');
include(ROOT_PATH.'language/'.$lang_code_global.'/lang_renter_info.php');
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
            <h3 style="text-decoration:underline;font-weight:bold;color:#000" class="box-title"><?php echo $_data['text_1'];?></h3>
          </div>
          <div class="box-body">
            <div style="overflow:auto;">
			<table style="font-size:13px;" class="table sakotable table-bordered table-striped dt-responsive">
              <thead>
               <tr>
              <th>Image</th>
              <th><?php echo $_data['text_5'];?></th>
              <th><?php echo $_data['text_6'];?></th>
              <th><?php echo $_data['text_7'];?></th>
              <th><?php echo $_data['text_8'];?></th>
              <th><?php echo $_data['text_14'];?></th>
              <th><?php echo $_data['text_9'];?></th>
              <th><?php echo $_data['text_10'];?></th>
              <th><?php echo $_data['text_11'];?></th>
              <th><?php echo $_data['text_12'];?></th>
              <th><?php echo $_data['text_13'];?></th>
              <th><?php echo $_data['text_15'];?></th>
            </tr>
              </thead>
              <tbody>
            <?php
				$query = "Select *,f.floor_no as ffloor,u.unit_no from tbl_add_rent r inner join tbl_add_floor f on f.fid = r.r_floor_no inner join tbl_add_unit u on u.uid = r.r_unit_no where r.branch_id = '" . (int)$_SESSION['objLogin']['branch_id'] . "'";
				if($_GET['rsid'] != ''){
					$query .= " and r.r_status='".$_GET['rsid']."'";
				}
				$result = mysqli_query($link,$query);
				while($row = mysqli_fetch_array($result)){
				$image = WEB_URL . 'img/no_image.jpg';	
					if(file_exists(ROOT_PATH . '/img/upload/' . $row['image']) && $row['image'] != ''){
						$image = WEB_URL . 'img/upload/' . $row['image'];
					}
				?>
                <tr>
                    <td><img class="photo_img_round" style="width:50px;height:50px;" src="<?php echo $image;  ?>" /></td>
                    <td><?php echo $row['r_name']; ?></td>
                    <td><?php echo $row['r_email']; ?></td>
                    <td><?php echo $row['r_contact']; ?></td>
                    <td><?php echo $row['r_address']; ?></td>
                    <td><?php echo $row['r_nid']; ?></td>
                    <td><?php echo $row['ffloor']; ?></td>
                    <td><?php echo $row['unit_no']; ?></td>
                    <td><?php echo $ams_helper->currency($localization, $row['r_advance']); ?></td>
					<td><?php echo $ams_helper->currency($localization, $row['r_rent_pm']); ?></td>
                    <td><?php echo $row['r_date']; ?></td>
                    <td><?php if($row['r_status'] == '1'){echo $_data['text_17'];} else{echo $_data['text_18'];}?></td>
                </tr>
                <?php } mysqli_close($link); ?>
              </tbody>
            </table>
			</div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<!-- /.row -->
<div align="center" style="position:fixed;top:0;right:0;margin:10px;"><a class="btn btn-success btn_save" title="<?php echo $_data['text_16'];?>" onClick="javascript:printContent('printable','Visitors Report');" href="javascript:void(0);"><i class="fa fa-print"></i> </a></div>
</body>
</html>
