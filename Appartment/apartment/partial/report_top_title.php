<?php defined('_AMSCODESECURITY') or exit('Restricted Access'); ?>
<?php if(!empty($image_building)) { ?>
	<div><img class="img-thumbnail" height="150" width="100" src="<?php echo $image_building; ?>"></div>
<?php } ?>
<?php if(!empty($building_name)) { ?>
	<div><h2><?php echo $building_name; ?></h2></div>
	<div><?php echo $address; ?></div>
	<div><?php echo $report_email; ?></div>
	<div><?php echo $report_phone; ?></div>
	<br/>
<?php } ?>