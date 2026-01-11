<style type="text/css">
	.reportnotfound {
		text-align: center;
		font-size: 20px;
		border: 1px solid #ddd;
		padding: 15px 10px;
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