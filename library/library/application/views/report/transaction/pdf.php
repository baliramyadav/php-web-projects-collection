<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Transaction Report</title>
</head>
<body>
	<div class="reportheader">
		<h2><?=$generalsetting->sitename?></h2>
		<p><?=$generalsetting->phone?></p>
		<p><?=$generalsetting->email?></p>
		<p><?=$generalsetting->address?></p>
	</div>
	<?php if(calculate($transactions)) { ?>
		<table class="reporttable">
			<thead>
				<tr class="info">
					<th>#</th>
					<th><?=$this->lang->line('transactionreport_type')?></th>
					<?php if($reportfor!=1 && $reportfor!=2) { ?>
						<th><?=$this->lang->line('transactionreport_role')?></th>
						<th><?=$this->lang->line('transactionreport_member')?></th>
					<?php } ?>
					<th><?=$this->lang->line('transactionreport_date')?></th>
					<th><?=$this->lang->line('transactionreport_amount')?></th>
				</tr>
			</thead>
			<tbody>
				<?php $i=0; foreach($transactions as $transaction) { $i++;?>
				<tr>
					<td><?=$i?></td>
					<td><?=$transaction['type']?></td>
					<?php if($reportfor !=1 && $reportfor !=2) { ?>
						<td><?=$transaction['role']?></td>
						<td><?=$transaction['member']?></td>
					<?php } ?>
					<td><?=app_date($transaction['date'])?></td>
					<td><?=$transaction['amount']?></td>
				</tr>
				<?php } ?>
			</tbody>
		</table>
		<?php } else { ?>
		<div class="reportnotfound">
			<?=$this->lang->line('transactionreport_transaction_not_available')?>
		</div>
	<?php } ?>
	<div class="reportfooter">
		<h4><?=$generalsetting->sitename?></h4>
		<p><?=$generalsetting->address?></p>
	</div>
</body>
</html>