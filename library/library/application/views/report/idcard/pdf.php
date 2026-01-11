<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>ID Card Report</title>
</head>
<body>
	<div class="reportheader">
		<h2><?=$generalsetting->sitename?></h2>
		<p><?=$generalsetting->phone?></p>
		<p><?=$generalsetting->email?></p>
		<p><?=$generalsetting->address?></p>
	</div>
	<div>
	<?php if(calculate($members)) { 
		$sitenames = str_split($generalsetting->sitename);
		foreach($members as $member) { if($type==1) { ?>
			<div class="singleidcard">
				<div class="topbar"></div>
				<div class="titlebar">
					<?php 
						if(calculate($sitenames) > 7) {
							$margindefault = 0;
						} else {
							$letter        = calculate($sitenames)-3;
							$margindefault = 80 - ($letter * 18);
						}
					?>	
					<div class="titlebarcenter" style="margin-left: <?=$margindefault?>px">
						<?php if(calculate($sitenames) && calculate($sitenames) <= 7) { foreach($sitenames as $sitename) {
							echo "<div class='titlebarletter'>".$sitename."</div> ";
						} } else { 
							echo "<div class='titlebarletterwidth'>".$generalsetting->sitename."</div> ";
						} ?>
					</div>
				</div>
				<div class="infobar">
					<img style="width: 70px;height: 75px;border: 1px solid #ddd;border-radius: 5px;" src="<?=profile_img($member->photo)?>" alt="">
					<h3><?=$member->name?></h3>
					<p><?=$this->lang->line('idcardreport_member_ID')?>: <span><?=generate_memberID($member->memberID)?></span></p>
					<p><?=$this->lang->line('idcardreport_blood_group')?>: <span><?=$member->bloodgroup?></span></p>
					<p><?=$this->lang->line('idcardreport_phone_no')?>: <span><?=$member->phone?></span></p>
					<p><?=$this->lang->line('idcardreport_email_no')?>: <span><?=$member->email?></span></p>
				</div>
				<div class="bottombar">
					<div class="bottombarborder"></div>
					<div class="bottombaraddress">
						<span><?=$generalsetting->web_address?></span>
					</div>
				</div>
			</div>
		<?php } elseif($type==2) { ?>
			<div class="singleidcard">
				<div class="topbar"></div>
				<div class="titlebar" style="padding-bottom: 0px;"></div>
				<div class="infobar backinfobar">
					<p><?=$this->lang->line('idcardreport_card_property')?> <?=$generalsetting->sitename?></p>
					<p><u><?=$this->lang->line('idcardreport_found_please_return_to')?>:</u></p>
					<p><b><?=$generalsetting->sitename?></b></p>
					<p><i><?=$generalsetting->address?></i></p>
					<p><?=$this->lang->line('idcardreport_valid_till')?>: <span><?=date('d.m.Y', strtotime('Dec 31'))?></span></p>
				</div>
				<div class="signaturebar">
					<div class="bar">
						<img src="<?=base_url('uploads/idcard/'.generate_memberID($member->memberID).'.jpg')?>" alt="">
					</div>
					<div class="signature">
						<span><?=$this->lang->line('idcardreport_authorized')?></span>
					</div>
				</div>
				<div class="bottombar">
					<div class="bottombarborder"></div>
					<div class="bottombaraddress">
						<span><?=$generalsetting->web_address?></span>
					</div>
				</div>
			</div>
	<?php } } } else { ?>
		<div class="reportnotfound">
			<?=$this->lang->line('idcardreport_data_not_available')?>
		</div>
	<?php } ?>
	</div>
	<div class="reportfooter">
		<h4><?=$generalsetting->sitename?></h4>
		<p><?=$generalsetting->address?></p>
	</div>
</body>
</html>