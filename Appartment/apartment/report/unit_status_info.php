<?php
ob_start();
session_start();
include("../config.php");
if(!isset($_SESSION['objLogin'])){
	header("Location: ".WEB_URL."logout.php");
	die();
}
include(ROOT_PATH.'partial/report_top_common.php');
include(ROOT_PATH.'language/'.$lang_code_global.'/lang_unit_status_info.php');
include(ROOT_PATH.'language/'.$lang_code_global.'/lang_common.php');
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
            <h3 style="text-decoration:underline;font-weight:bold;color:black;" class="box-title"><?php echo $_data['text_1'];?></h3>
          </div>
          <div class="box-body">
            <table style="font-size:13px;" class="table sakotable table-bordered table-striped dt-responsive">
              <thead>
               <tr>
                  <th><?php echo $_data['text_2'];?></th>
                  <th><?php echo $_data['text_3'];?></th>
                  <th><?php echo $_data['text_4'];?></th>
            	</tr>
              </thead>
              <tbody>
            <?php
				$query = "Select *,f.floor_no as ffloorno from tbl_add_unit u inner join tbl_add_floor f on f.fid = u.floor_no where u.branch_id = '" . (int)$_SESSION['objLogin']['branch_id'] . "'";
				if($_GET['usid'] != ''){
					$query .= " and u.status='".$_GET['usid']."'";
				}
				$result = mysqli_query($link,$query);
				while($row = mysqli_fetch_array($result)){?>
                <tr>
                    <td><?php echo $row['ffloorno']; ?></td>
            		<td><?php echo $row['unit_no']; ?></td>
                    <td><?php if($row['status'] == '1'){echo $_data['text_5'];} else{echo $_data['text_6'];}?></td>
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
<div align="center" style="position:fixed;top:0;right:0;margin:10px;"><a class="btn btn-success btn_save" title="<?php echo $_data['print'];?>" onClick="javascript:printContent('printable','Unit Status Report');" href="javascript:void(0);"><i class="fa fa-print"></i> </a></div>
</body>
</html>
