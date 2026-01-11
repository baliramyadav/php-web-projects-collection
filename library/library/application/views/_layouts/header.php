<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title><?=$get_title?></title>
    <link rel="shortcut icon" type="image/x-icon" href="<?=app_image_link($generalsetting->logo, 'uploads/images/', 'logo.jpg')?>">
	<link rel="stylesheet" href="<?=base_url('assets/frontend/css/font-awesome.min.css')?>">
	<link rel="stylesheet" href="<?=base_url('assets/frontend/css/bootstrap.min.css')?>">
	
	<!--Owl.carousel Code Add Here-->
	<link rel="stylesheet" type="text/css" href="<?=base_url('assets/frontend/css/owl.carousel.min.css')?>" media="all" />
	<link rel="stylesheet" type="text/css" href="<?=base_url('assets/frontend/css/owl.theme.default.min.css')?>" media="all" />
	<link rel="stylesheet" href="<?=base_url('assets/plugins/toastr/toastr.min.css')?>">

	<!-- All Css files Load Here -->
    <?php 
      if(isset($headerassets['css']) && calculate($headerassets['css'])) {
        foreach ($headerassets['css'] as $css) { ?>
          <link rel="stylesheet" href="<?=base_url($css)?>">
        <?php }
      }
    ?>

	<link rel="stylesheet" href="<?=base_url('assets/frontend/style.css')?>">
	<link rel="stylesheet" href="<?=base_url('assets/frontend/css/responsive.css')?>">
	<script type="text/javascript" src="<?=base_url('assets/frontend/js/jquery.min.js')?>"></script>
	<?php 
      if(isset($headerassets['headerjs']) && calculate($headerassets['headerjs'])) {
        foreach ($headerassets['headerjs'] as $headerjs) { ?>
          <script src="<?=base_url($headerjs)?>"></script>
        <?php }
      }
    ?>
    <script type="text/javascript">
        var THEME_BASE_URL = "<?=base_url('/')?>";
    </script>
</head>
<body>
	<section class="header-top">
		<div class="container">
			<div class="row">
				<div class="col-lg-5 col-md-5 col-sm-12 col-xs-12">
					<a href="mailto:<?=$generalsetting->email?>" class="header-email">
						<i class="fa fa-envelope"></i> <?=$generalsetting->email?>
					</a>
					<a href="" class="header-phone">
						<i class="fa fa-phone"></i> <?=$generalsetting->phone?>
					</a>
				</div>
				<div class="col-lg-7 col-md-7 col-sm-12 col-xs-12">
					<ul class="header-items">

						<?php if($this->session->userdata('loggedin')) { ?>
							<li><a href="<?=base_url('dashboard/index')?>" target="_blank"><i class="fa fa-dashboard"></i> <?=$this->lang->line('frontend_dashboard')?></a></li>
							<?php if (calculate($this->data["cart_contents"])) { ?>
								<li><a href="<?=base_url('frontend/checkout')?>"><i class="fa fa-cart-arrow-down"></i> <?=$this->lang->line('frontend_checkout')?></a></li>
								<li><a href="<?=base_url('frontend/cart')?>"><i class="fa fa-shopping-bag"></i> <?=$this->lang->line('frontend_cart')?> (<?=$this->cart->total_items()?>)</a></li>
							<?php } ?>
							<li><a href="<?=base_url('myaccount/index')?>"><i class="fa fa-lock"></i> <?=$this->lang->line('frontend_my_account')?></a></li>
							<li><a href="<?=base_url('myaccount/logout')?>"><i class="fa fa-sign-out"></i> <?=$this->lang->line('frontend_logout')?></a></li>
						<?php } else { ?>
							<li><a href="<?=base_url('myaccount/login')?>"><i class="fa fa-sign-in"></i> <?=$this->lang->line('frontend_login')?></a></li>
							<?php if($generalsetting->registration) { ?>
								<li><a href="<?=base_url('myaccount/register')?>"><i class="fa fa-user-plus"></i> <?=$this->lang->line('frontend_register')?></a></li>
						<?php } } ?>
					</ul>
				</div>
			</div>
		</div>
	</section>
	<section class="header">
		<div class="container">
			<div class="row">
				<div class="col-sm-3">
					<a href="<?=base_url('/')?>">
						<img class="header-logo" src="<?=app_image_link($generalsetting->logo, 'uploads/images/', 'logo.jpg')?>" alt="">
					</a>
				</div>
				<div class="col-sm-6">
					<div class="header-search">
						<form action="<?=base_url('frontend/shop')?>" method="GET">
							<div class="input-group input-group-search-form">
							  	<input type="text" placeholder="Search...." class="form-control form-control-lg input-group-search" name="search" value="<?=$search ?? ''?>" />
							  	<div class="input-group-append search-btn">
							  		<input type="submit" class="input-group-text" value="Search">
							  	</div>
							</div>
						</form>
					</div>
				</div>
				<div class="col-sm-3">
					<div class="carts float-right">
						<button class="btn btn-success btn btn-lg cart-item-badge" type="button" data-toggle="dropdown">
						    <i class="fa fa-shopping-bag"></i> <b><?=app_amount_format($this->cart->total()); ?></b>
						    <span class="badge badge-danger cart-item-badge-count"><?=$this->cart->total_items()?></span>
						</button>
						<div class="dropdown-menu dropdown-menu-right">
							<div class="mini-cart">
							    <?php 
							    $cart_contents = $this->cart->contents();
							    if(calculate($cart_contents)) { foreach($cart_contents as $cart_content) { ?>
								    <div class="cart-item">
								        <div class="cart-item-image">
								            <a href="<?=base_url('frontend/single/'.$cart_content['id'])?>">
								            	<img src="<?=$cart_content['images']?>" alt="<?=$cart_content['name']?>" />
								            </a>
								        </div>
								        <div class="cart-item-info">
								            <a href="<?=base_url('frontend/single/'.$cart_content['id'])?>">
								            	<?=$cart_content['name']?> 
								            </a>
								            <p>Qty: <?=$cart_content['qty']?> X <span> <?=$cart_content['price']?> </span></p>
								        </div>
								        <div class="cart-item-remove">
								            <a class="btn btn-danger btn-sm" href="<?=base_url('frontend/removecart/'.$cart_content['rowid'])?>"><i class="fa fa-trash"></i></a>
								        </div>
								    </div>
								<?php } ?>
								    <div class="mini-cart-total">
								        <div class="cart-total">
								            <span>Delivery Charge:</span>
								            <span class="price"><?=app_amount_format($generalsetting->delivery_charge); ?></span>
								        </div>
								        <div class="cart-total">
								            <span>Total:</span>
								            <span class="price"><?=app_amount_format($this->cart->total()); ?></span>
								        </div>
								    </div>

								    <div class="mini-cart-footer">
							            <a class="btn btn-success btn-sm float-left" href="<?=base_url('frontend/cart')?>">View cart</a>
							            <a class="btn btn-success btn-sm float-right" href="<?=base_url('frontend/checkout')?>">Checkout</a>
								    </div>
								<?php } else { ?>
									<h6 class="p-3"><?=$this->lang->line('frontend_cart_empty')?></h6>
								<?php } ?>

							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>
	<section class="mainmenu">
		<div class="container">
			<ul>
				<li><a class="<?=$activemenu=='index' ? 'active' : ''?>" href="<?=base_url('frontend/index')?>"><i class="fa fa-home"></i><?=$this->lang->line('frontend_home')?></a></li>
				<li><a class="<?=$activemenu=='ebook' ? 'active' : ''?>" href="<?=base_url('frontend/ebook')?>"><?=$this->lang->line('frontend_ebook')?></a></li>
				<li><a class="<?=$activemenu=='book' ? 'active' : ''?>" href="<?=base_url('frontend/book')?>"><?=$this->lang->line('frontend_book')?></a></li>
				<li><a class="<?=$activemenu=='shop' ? 'active' : ''?>" href="<?=base_url('frontend/shop')?>"><?=$this->lang->line('frontend_shop')?></a></li>
				<li><a class="<?=$activemenu=='contact' ? 'active' : ''?>" href="<?=base_url('frontend/contact')?>"><?=$this->lang->line('frontend_contact')?></a></li>
			</ul>
		</div>
	</section>