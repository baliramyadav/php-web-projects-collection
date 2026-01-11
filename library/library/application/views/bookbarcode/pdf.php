<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Book Barcode</title>
</head>
<body>
	<?php if(calculate($bookitems)) { ?>
		<div class="booklist">
			<?php foreach ($bookitems as $bookitem) { ?>
				<div class="bookitem">
					<p><?=$book->codeno.'-'.$bookitem->bookno?></p>
					<img class="bookitemimg" src="<?=base_url('uploads/bookbarcode/'.$book->codeno.'-'.$bookitem->bookno.'.jpg')?>" alt="">
				</div>
			<?php } ?>
		</div>
	<?php } else { ?>
		<div class="reportnotfound">
			<?=$this->lang->line('bookreport_book_not_available')?>
		</div>
	<?php } ?>
</body>
</html>