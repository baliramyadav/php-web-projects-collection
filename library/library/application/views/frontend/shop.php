	<section class="main-shop">
		<div class="container">
			<div class="row">
				<div class="col-sm-3">
					<div class="main-slider-menu">
						<span class="sidebar-title"><i class="fa fa-list"></i> All Categories</span>
						<div class="slider-menu">
							<ul>
								<?php foreach($storebookcategorys as $storebookcategory) { ?>
									<li><a href="<?=base_url('frontend/shop?category='.$storebookcategory->storebookcategoryID)?>"><?=$storebookcategory->name?></a></li>
								<?php } ?>
								<li><a href="<?=base_url('/')?>"> + More Category</a></li>
							</ul>
						</div>
					</div>
				</div>
				<div class="col-sm-9">
					<div class="row">
						<?php if(calculate($storebooks)) { foreach ($storebooks as $storebook) { ?>
							<div class="col-sm-4">
							    <div class="single-book">
							        <div class="book-image">
							            <a href="<?=base_url('frontend/single/'.$storebook->storebookID)?>">
							                <img class="book-thumbail-image" src="<?=app_image_link($storebook->coverphoto, 'uploads/storebook/', 'storebook.jpg')?>" alt="single-book" />
							            </a>
							            <span class="book-badge-label">New</span>
							            <span class="book-badge-price"><?=$storebook->price?></span>
							        </div>
							        <div class="book-content">
							            <a href="<?=base_url('frontend/single/'.$storebook->storebookID)?>" class="book-title">
							            	<?=$storebook->name?>
							            </a>
							            <div class="book-actions">
						                    <a class="btn btn-outline-success btn-sm" href="<?=base_url('frontend/single/'.$storebook->storebookID)?>"><i class="fa fa-eye"></i></a>
						                    <a class="btn btn-outline-success btn-sm" href="<?=base_url('frontend/addcart/'.$storebook->storebookID)?>"><i class="fa fa-cart-plus"></i></a>
							            </div>
							        </div>
							    </div>
							</div>
						<?php } } else { ?>
			                <div class="col-sm-12">
			                    <div class="not-found">
			                        <h2><?=$this->lang->line('frontend_storebook_not_found')?></h2>
			                    </div>
			                </div>
						<?php } ?>
					</div>
					<div class="row">
		                <div class="col-sm-12">
		                    <?=$this->pagination->create_links();?>
		                </div>
		            </div>
				</div>
			</div>
		</div>
	</section>