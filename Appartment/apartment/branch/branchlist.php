<?php
include('../header.php');
include(ROOT_PATH.'language/'.$lang_code_global.'/lang_branch_list.php');
if(!isset($_SESSION['objLogin'])){
	header("Location: " . WEB_URL . "logout.php");
	die();
}
if(isset($_SESSION['login_type']) && ((int)$_SESSION['login_type'] != 5)){
	header("Location: ".WEB_URL."logout.php");
	die();
}
?>
<?php
$delinfo = 'none';
$addinfo = 'none';
$image = WEB_URL . 'img/no_image.jpg';
$msg = "";
 if(isset($_GET['id']) && $_GET['id'] != '' && $_GET['id'] > 0){
		if(branchCount($link)){
			$sqlx= "DELETE FROM `tblbranch` WHERE branch_id = ".$_GET['id'];
			mysqli_query($link,$sqlx); 
			$delinfo = 'block';
		}
	}
if(isset($_GET['m']) && $_GET['m'] == 'add'){
	$addinfo = 'block';
	$msg = $_data['text_11'];
}
if(isset($_GET['m']) && $_GET['m'] == 'up'){
	$addinfo = 'block';
	$msg = $_data['text_12'];
}

function branchCount($link){
	$sql = mysqli_query($link, "SELECT count(*) as total_rows from tblbranch");
	if($row = mysqli_fetch_assoc($sql)){
		if($row['total_rows'] > 1){
			return true;
		} else {
			return false;
		}
	}
	return false;
}
?>
<!-- Content Header (Page header) -->

<section class="content-header">
  <h1> <?php echo $_data['text_14'];?> </h1>
  <ol class="breadcrumb">
    <li><a href="<?php echo WEB_URL?>/dashboard.php"><i class="fa fa-dashboard"></i> <?php echo $_data['text_19'];?></a></li>
	<li class="active"><?php echo $_data['text_20'];?></li>
    <li class="active"><?php echo $_data['text_14'];?></li>
  </ol>
</section>
<!-- Main content -->
<section class="content">
<!-- Full Width boxes (Stat box) -->
<div class="row">
  <div class="col-xs-12">
    <div class="alert alert-danger alert-dismissable" style="display:<?php echo $delinfo; ?>">
      <button aria-hidden="true" data-dismiss="alert" class="close" type="button"><i class="fa fa-close"></i></button>
      <h4><i class="icon fa fa-ban"></i> <?php echo $_data['delete_text'];?>!</h4>
      <?php echo $_data['text_13'];?> </div>
    <div class="alert alert-success alert-dismissable" style="display:<?php echo $addinfo; ?>">
      <button aria-hidden="true" data-dismiss="alert" class="close" type="button"><i class="fa fa-close"></i></button>
      <h4><i class="icon fa fa-check"></i> <?php echo $_data['success'];?>!</h4>
      <?php echo $msg; ?> </div>
    <div align="right" style="margin-bottom:1%;"> <a class="btn btn-success" data-toggle="tooltip" href="<?php echo WEB_URL; ?>branch/addbranch.php" data-original-title="<?php echo $_data['text_1'];?>"><i class="fa fa-plus"></i></a> <a class="btn btn-success" data-toggle="tooltip" href="<?php echo WEB_URL; ?>dashboard.php" data-original-title="<?php echo $_data['text_17'];?>"><i class="fa fa-dashboard"></i></a> </div>
    <div class="box box-success">
      <div class="box-header">
        <h3 class="box-title"><?php echo $_data['text_14'];?></h3>
      </div>
	  
      <!-- /.box-header -->
      <div class="box-body">
        <div style="margin-bottom:10px;color:#d73925;">**If you delete all buildings system will be stop working, so must be one building exist here but you can rename building information.</div>
		<table class="table sakotable table-bordered table-striped dt-responsive">
          <thead>
            <tr>
              <th><?php echo $_data['text_4'];?></th>
              <th><?php echo $_data['text_5'];?></th>
              <th><?php echo $_data['text_6'];?></th>
              <th><?php echo $_data['text_7'];?></th>
			  <th><?php echo $_data['text_233'];?></th>
              <th><?php echo $_data['action_text'];?></th>
            </tr>
          </thead>
          <tbody>
            <?php
          	$result = mysqli_query($link,"Select *,y.xyear from tblbranch b left join tbl_add_year_setup y on b.building_make_year = y.y_id order by branch_id desc");
				while($row = mysqli_fetch_array($result)){	
				if(file_exists(ROOT_PATH . '/img/upload/' . $row['building_image']) && $row['building_image'] != ''){
					$image = WEB_URL . 'img/upload/' . $row['building_image'];
				}
			?>
            <tr>
              <td><?php echo $row['branch_name']; ?></td>
              <td><?php echo $row['b_email']; ?></td>
              <td><?php echo $row['b_contact_no']; ?></td>
              <td><?php echo $row['b_address']; ?></td>
			  <td><?php if((bool)$row['b_status']){echo '<span class="label label-success"><i class="fa fa-check"></i></span>';} else {echo '<span class="label label-danger"><i class="fa fa-times"></i></span>';} ?></td>
              <td><a class="btn btn-success ams_btn_special" data-toggle="tooltip" href="javascript:;" onclick="$('#employee_view_<?php echo $row['branch_id']; ?>').modal('show');" data-original-title="<?php echo $_data['view_text'];?>"><i class="fa fa-eye"></i></a> <a class="btn btn-warning ams_btn_special" data-toggle="tooltip" href="<?php echo WEB_URL;?>branch/addbranch.php?id=<?php echo $row['branch_id']; ?>" data-original-title="<?php echo $_data['edit_text'];?>"><i class="fa fa-pencil"></i></a> <a class="btn btn-danger ams_btn_special" data-toggle="tooltip" onclick="deleteBranch(<?php echo $row['branch_id']; ?>);" href="javascript:;" data-original-title="<?php echo $_data['delete_text'];?>"><i class="fa fa-trash-o"></i></a>
                <div id="employee_view_<?php echo $row['branch_id']; ?>" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                  <div class="modal-dialog">
                    <div class="modal-content">
                      <div class="modal-header orange_header">
                        <button aria-label="Close" data-dismiss="modal" class="close" type="button"><span aria-hidden="true"><i class="fa fa-close"></i></span></button>
                        <h3 class="modal-title"><?php echo $_data['text_16'];?></h3>
                      </div>
                      <div class="modal-body model_view" align="center">&nbsp;
                        <div><img class="photo_img_round" style="width:100px;height:100px;" src="<?php echo $image;  ?>" /></div>
                        <div class="model_title"><?php echo $row['branch_name']; ?></div>
                      </div>
                      <div class="modal-body">
                        <h3 style="text-decoration:underline;"><?php echo $_data['details_information'];?></h3>
                        <div class="row">
                          <div class="col-xs-6"> <b><?php echo $_data['text_4'];?> : </b> <?php echo $row['branch_name']; ?><br/>
                            <b><?php echo $_data['text_5'];?> : </b> <?php echo $row['b_email']; ?><br/>
                            <b><?php echo $_data['text_6'];?> : </b> <?php echo $row['b_contact_no']; ?><br/>
							<b><?php echo $_data['text_144'];?> : </b> <?php echo $row['security_guard_mobile']; ?><br/>
							<b><?php echo $_data['text_155'];?> : </b> <?php echo $row['secrataty_mobile']; ?><br/>
							<b><?php echo $_data['text_166'];?> : </b> <?php echo $row['moderator_mobile']; ?><br/>
							<b><?php echo $_data['text_177'];?> : </b> <?php echo $row['xyear']; ?><br/>
                            <b><?php echo $_data['text_7'];?> : </b> <?php echo $row['b_address']; ?><br/> </div>
							
							
							<div class="col-xs-6">
								<b><?php echo $_data['text_200'];?> : </b> <?php echo $row['builder_company_name']; ?><br/>
								<b><?php echo $_data['text_211'];?> : </b> <?php echo $row['builder_company_phone']; ?><br/>
								<b><?php echo $_data['text_222'];?> : </b> <?php echo $row['builder_company_address']; ?><br/>
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
      <!-- /.box-body -->
    </div>
    <!-- /.box -->
  </div>
  <!-- /.col -->
</div>
<!-- /.row -->
 <script type="text/javascript">
  function deleteBranch(Id){
  	var iAnswer = confirm("Are you sure you want to delete this branch ?");
	if(iAnswer){
		window.location = 'branchlist.php?id=' + Id;
	}
  }
  </script>
<?php include('../footer.php'); ?>
