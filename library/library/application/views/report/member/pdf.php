<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Member Report</title>
</head>
<body>
	<div class="reportheader">
		<h2><?=$generalsetting->sitename?></h2>
		<p><?=$generalsetting->phone?></p>
		<p><?=$generalsetting->email?></p>
		<p><?=$generalsetting->address?></p>
	</div>
	<?php if(calculate($members)) { ?>
		<table class="reporttable">
			<thead>
				<tr class="info">
					<th>#</th>
					<th><?=$this->lang->line('memberreport_photo')?></th>
					<th><?=$this->lang->line('memberreport_name')?></th>
					<th><?=$this->lang->line('memberreport_role')?></th>
					<th><?=$this->lang->line('memberreport_blood_group')?></th>
					<th><?=$this->lang->line('memberreport_phone')?></th>
					<th><?=$this->lang->line('memberreport_email')?></th>
				</tr>
			</thead>
			<tbody>
				<?php $i=0; foreach($members as $member) { $i++;?>
				<tr>
					<td><?=$i?></td>
					<td><img src="<?=app_image_link($member->photo, 'uploads/member/')?>" class="profile_img"></td>
					<td><?=$member->name?></td>
					<td><?=isset($roles[$member->roleID]) ? $roles[$member->roleID]->role : ''?></td>
					<td><?=$member->bloodgroup?></td>
					<td><?=$member->phone?></td>
					<td><?=$member->email?></td>
				</tr>
				<?php } ?>
			</tbody>
		</table>
	<?php } else { ?>
		<div class="reportnotfound">
			<?=$this->lang->line('bookissuereport_issue_book_not_available')?>
		</div>
	<?php } ?>
	<div class="reportfooter">
		<h4><?=$generalsetting->sitename?></h4>
		<p><?=$generalsetting->address?></p>
	</div>
</body>
</html>