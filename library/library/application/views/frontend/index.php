<?php  if(calculate($storebookcategorys)) { ?>
<div class="main-book-category-list">
	<div class="container">
		<div class="row">
			<div class="col-sm-12">
				<h2 class="theme_heading_green mb-0"><?=$this->lang->line('frontend_store_books_category')?></h2>
			</div>
		</div>
		<div class="row">
			<?php foreach($storebookcategorys as $storebookcategory) { ?>
				<div class="col-sm-2">
				    <div class="single-book-category">
				        <div class="book-category-image">
				            <a href="<?=base_url('frontend/shop?category='.$storebookcategory->storebookcategoryID)?>">
				                <img class="book-category-thumbnail-image" src="<?=app_image_link($storebookcategory->coverphoto,'uploads/storebookcategory/','storebookcategory.jpg')?>" alt="<?=$storebookcategory->name?>" />
				            </a>
				        </div>
				        <div class="book-category-content">
				            <a href="<?=base_url('frontend/shop?category='.$storebookcategory->storebookcategoryID)?>" class="book-category-title"><?=$storebookcategory->name?></a>
				        </div>
				    </div>
				</div>
			<?php } ?>
		</div>
	</div>
</div>
<hr class="hr-border">
<?php } if(calculate($bookcategorys)) { ?>
<div class="main-book-category-list">
	<div class="container">
		<div class="row">
			<div class="col-sm-12">
				<h2 class="theme_heading_green mb-0"><?=$this->lang->line('frontend_books_category')?></h2>
			</div>
		</div>
		<div class="row">
			<?php foreach($bookcategorys as $bookcategory) { ?>
				<div class="col-sm-2">
				    <div class="single-book-category">
				        <div class="book-category-image">
				            <img class="book-category-thumbnail-image" src="<?=app_image_link($bookcategory->coverphoto,'uploads/bookcategory/','bookcategory.jpg')?>" alt="<?=$bookcategory->name?>" />
				        </div>
				        <div class="book-category-content">
				            <a class="book-category-title"><?=$bookcategory->name?></a>
				        </div>
				    </div>
				</div>
			<?php } ?>
		</div>
	</div>
</div>
<?php } ?>