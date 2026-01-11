<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Book Report</title>
</head>
<body>
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
					<img class="bookitemimg" src="<?=base_url('uploads/bookbarcode/'.$book->codeno.'-'.$bookitem->bookno.'.jpg')?>" alt="">
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
</body>
</html>