<?php 
	$currPage = "contact";
  include("includes/header.php"); 


?>

<div class='container'>
  <div class="jumbotron">
	  <h1><?php echo lang("hl_contact"); ?></h1>
  </div>

  <div class="row">
  	<div class="col-sm-9">
  		<h3><?php echo lang("hl_contactform"); ?></h3>
  		<div id='cnote'>
			</div>
		<form class="form-horizontal" method="post" action="contact.php" id="contactf">
			<input type="hidden" name="action" value="contact" />
			<!-- <div class='col-xs-12'> -->
				<div class='row'>
					<div class='col-md-6'>
						<div class="form-group">
							<label class="control-label col-sm-4"><?php echo lang("descr_name"); ?></label>
							<div class="col-sm-8">
								<input type="text" name="name" placeholder="<?php echo lang("placeholder_name"); ?>" class="form-control" value="" required='required' /></div>
						</div>
						<div class="form-group">
							<label class="control-label col-sm-4"><?php echo lang("descr_email"); ?></label>
							<div class="col-sm-8">
								<input type="email" name="email" placeholder="<?php echo lang("placeholder_email"); ?>" class="form-control" value="" required="required" />
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-sm-4"><?php echo lang("descr_tel"); ?></label>
							<div class="col-sm-8">
								<input type="text" name="tel" placeholder="<?php echo lang("placeholder_tel"); ?>" class="form-control" value="" />
							</div>
						</div>
					</div>
					<div class='col-md-6'>
						<div class="form-group">
							<div class="col-xs-12">
								<textarea name="message" placeholder="<?php echo lang("placeholder_message"); ?>" class="form-control" rows="5" required="required"></textarea>
							</div>
						</div>
					</div>
					<div class='clearfix'></div>
					<?php 
					if ($config->captchaContact) {
					?>
					<div class='col-xs-12'>
						<div class="form-group">
							<label class="control-label col-sm-4 col-md-2 col-xs-4"><?php echo lang("descr_spam_protection"); ?></label>
							<div class="col-sm-8 col-md-10">
								<div class='row'>
									<div class='col-sm-5 col-xs-8'>
										<div class='wrapper-captcha'>
											<img src='cpc/captcha.php' alt='' class='img-responsive img-rounded img-captcha' />
											<button type='button' class='btn btn-xs btn-default btn-captcha-refresh'><i class='fa fa-refresh'></i></button>
										</div>
										<div class='clearfix'></div>
									</div>
									<div class='col-lg-5 col-md-6 col-sm-7'><span class='text-muted small nob'><?php echo lang("info_spam_protection"); ?></span>
										<input type="text" name="captcha" placeholder="<?php echo lang("placeholder_spam_protection"); ?>" class="form-control" value="" required='required' />
									</div>
								</div>
							</div>
						</div>
					</div>
					<?php 
					}
					?>
					<div class='col-xs-12'>
						<div class="form-group">
							<div class="col-sm-8 col-md-4 col-lg-3 col-sm-offset-4 col-md-offset-2">
								<input type="submit" class="btn btn-primary btn-block" value="<?php echo lang("descr_sendmess"); ?>">
							</div>
						</div>
					</div>						

				</div> <!-- row -->
			<!-- </div> col-xs-12 -->


		</form>
  	</div>
  	<div class="col-sm-3">
  		<h4>NanoHard Company Ltd.</h4>
			<p>Johnny Street 10<br />
			Seattle<br />
			United States of America<br /><br />
			<strong>Email</strong>: <a href='mailto:nobody@nowhere.tld'>nobody@nowhere.tld</a><br />
			<strong>Telefon</strong>: +1 2 34455665<br /><br />
			<a href='https://maps.google.com/maps?q=batman+park+Melbourne,+Victoria,+Australien&amp;hl=de&amp;ll=-37.822769,144.956317&amp;spn=0.007822,0.016512&amp;sll=-37.817942,144.964977&amp;sspn=0.031291,0.066047&amp;hq=batman+park+Melbourne,+Victoria,+Australien&amp;t=m&amp;z=17' onclick="window.open(this.href); return false;"><img src='<?php echo $config->instDir; ?>/img/map.gif' alt='' class='img-rounded img-responsive' /></a>
			</p>
  	</div>
  </div>

</div> <!-- container -->

<?php include("includes/footer.php"); ?>
