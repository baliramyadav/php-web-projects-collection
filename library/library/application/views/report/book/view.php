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
<?php if(calculate($books)) { ?>
	<table class="table table-hover table-striped table-bordered reporttable">
		<thead>
			<tr class="info">
				<th>#</th>
				<th><?=$this->lang->line('bookreport_cover_photo')?></th>
				<th><?=$this->lang->line('bookreport_name')?></th>
				<th><?=$this->lang->line('bookreport_author')?></th>
				<th><?=$this->lang->line('bookreport_code')?></th>
				<th><?=$this->lang->line('bookreport_category')?></th>
				<th><?=$this->lang->line('bookreport_status')?></th>
				<th><?=$this->lang->line('bookreport_quantity')?></th>
			</tr>
		</thead>
		<tbody>
			<?php $i=0; foreach($books as $book) { $i++;?>
			<tr>
				<td><?=$i?></td>
				<td><img src="<?=app_image_link($book->coverphoto, 'uploads/book/')?>" class="profile_img"></td>
				<td><?=$book->name?></td>
				<td><?=$book->author?></td>
				<td><?=$book->codeno?></td>
				<td><?=isset($bookcategorys[$book->bookcategoryID]) ? $bookcategorys[$book->bookcategoryID]->name : ''?></td>
				<td><?=($book->status == 0) ? $this->lang->line('bookreport_available') : $this->lang->line('bookreport_not_available')?></td>
				<td><?=isset($bookQuantity[$book->bookID]) ? $bookQuantity[$book->bookID] : 0?></td>
			</tr>
			<?php } ?>
		</tbody>
	</table>
<?php } else { ?>
	<div class="reportnotfound">
		<?=$this->lang->line('bookreport_book_not_available')?>
	</div>
<?php } ?>
<div class="reportfooter">
	<h4><?=$generalsetting->sitename?></h4>
	<p><?=$generalsetting->address?></p>
</div>