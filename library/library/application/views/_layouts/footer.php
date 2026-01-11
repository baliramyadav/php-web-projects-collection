	<section class="main-newsletter">
	    <div class="container">
	        <div class="row">
	            <div class="col-xl-6 col-lg-6">
	                <div class="newsletter-content">
	                    <h3><?=$this->lang->line('frontend_newsletter_header')?></h3>
	                    <p><?=$this->lang->line('frontend_newsletter_contant')?></p>
	                </div>
	            </div>
	            <div class="col-xl-6 col-lg-6">
	                <div class="newsletter-box">
	                    <form action="<?=base_url('frontend/subscribe')?>" method="POST">
	                    	<div class="input-group input-group-search-form">
		                        <input type="text" name="email" placeholder="Subscribe Now...." class="form-control form-control-lg input-group-search">
		                        <div class="input-group-append search-btn">
		                            <input type="submit" class="input-group-text" value="Subscribe">
		                        </div>
		                    </div>
	                    </form>
	                </div>
	            </div>
	        </div>
	    </div>
	</section>

	<section class="footer-bottom">
		<div class="container">
			<div class="row">
				<div class="col-sm-6">
					<p><?=$generalsetting->copyright_by?></p>
				</div>
				<div class="col-sm-6 footer-bootom-menu">
					<ul>
						<li><a class="<?=$activemenu=='index' ? 'active' : ''?>" href="<?=base_url('frontend/index')?>"><?=$this->lang->line('frontend_home')?></a></li>
						<li><a class="<?=$activemenu=='ebook' ? 'active' : ''?>" href="<?=base_url('frontend/ebook')?>"><?=$this->lang->line('frontend_ebook')?></a></li>
						<li><a class="<?=$activemenu=='book' ? 'active' : ''?>" href="<?=base_url('frontend/book')?>"><?=$this->lang->line('frontend_book')?></a></li>
						<li><a class="<?=$activemenu=='shop' ? 'active' : ''?>" href="<?=base_url('frontend/shop')?>"><?=$this->lang->line('frontend_shop')?></a></li>
						<li><a class="<?=$activemenu=='contact' ? 'active' : ''?>" href="<?=base_url('frontend/contact')?>"><?=$this->lang->line('frontend_contact')?></a></li>
					</ul>
					
				</div>
			</div>
		</div>
	</section>

	<script type="text/javascript" src="<?=base_url('assets/frontend/js/popper.min.js')?>"></script>
	<script type="text/javascript" src="<?=base_url('assets/frontend/js/bootstrap.min.js')?>"></script>
	<!--Add coursoul.js Jquery-->
	<script type="text/javascript" src="<?=base_url('assets/frontend/js/owl.carousel.min.js')?>"></script>

	<script src="<?=base_url('assets/plugins/toastr/toastr.min.js')?>"></script>

	<!-- All Js files Load Here -->
	<?php 
	    if(isset($headerassets['js']) && calculate($headerassets['js'])) {
	      foreach ($headerassets['js'] as $js) { ?>
	        <script src="<?=base_url($js)?>"></script>
	<?php } } ?>

	<script type="text/javascript" src="<?=base_url('assets/frontend/js/script.js')?>"></script>

	<script>
		<?php 
	    $success = $this->session->flashdata('success');
	    $error   = $this->session->flashdata('error');
	    if($success) { ?>
	        toastr.success('<?=$success?>');
	    <?php } elseif($error) { ?>
	        toastr.error('<?=$error?>');
	    <?php } ?>
	</script>
</body>
</html>