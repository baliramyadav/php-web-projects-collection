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
	}

	.booklist {
		overflow: hidden;
	}

	.bookitem {
		width: 150px;
		margin:0px 15px 25px 0px;
		float: left;
	}

	.bookitem p {
		text-align: center;
		margin-bottom: 2px;	
	}

	.bookitem img {
		width: 150px;
		height: 40px;
	}

</style>
<div class="reportheader">
	<h2><?=$generalsetting->sitename?></h2>
	<p><?=$generalsetting->phone?></p>
	<p><?=$generalsetting->email?></p>
	<p><?=$generalsetting->address?></p>
</div>
<?php if(calculate($bookitems)) { ?>
	<div class="booklist">
		<?php foreach ($bookitems as $bookitem) { ?>
			<div class="bookitem">
				<p><?=$book->codeno.'-'.$bookitem->bookno?></p>
				<img src="<?=base_url('uploads/bookbarcode/'.$book->codeno.'-'.$bookitem->bookno.'.jpg')?>" alt="">
			</div>
		<?php } ?>
	</div>
<?php } else { ?>
	<div class="reportnotfound">
		<?=$this->lang->line('bookbarcode_book_not_available')?>
	</div>
<?php } ?>
<div class="reportfooter">
	<h4><?=$generalsetting->sitename?></h4>
	<p><?=$generalsetting->address?></p>
</div>