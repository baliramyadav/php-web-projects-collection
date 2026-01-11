<style type="text/css">
	.reportheader {
		text-align: center;
		margin-bottom: 10px;
	}
	.reportheader p{
		margin-bottom: 0px;
	}
	.reporttable {
		overflow: hidden;
	}
	.reportnotfound {
		text-align: center;
		font-size: 20px;
		border: 1px solid #ddd;
		padding: 15px 10px;
	}

	.reportfooter {
		text-align: center;
	}

	.reportfooter h4 {
		margin-bottom: 2px;
	}

	.table-bordered {
		border: 1px solid #ddd;
	}

	@media print {
		body {
			-webkit-print-color-adjust: exact !important;
		}

		tr.info th {
			background: #d9edf7 !important;
		}
	}
</style>
<div class="reportheader">
	<h2><?=$generalsetting->sitename?></h2>
	<p><?=$generalsetting->phone?></p>
	<p><?=$generalsetting->email?></p>
	<p><?=$generalsetting->address?></p>
</div>
<?php if(calculate($transactions)) { ?>
	<table class="table table-hover table-striped table-bordered reporttable">
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