<?php 
  $currPage = "abuse";
  include("includes/header.php"); 

  //abuse mods
  $key = isset($_REQUEST["dk"]) ? $_REQUEST["dk"] : null;
  $shortkey = isset($_REQUEST["sk"]) ? $_REQUEST["sk"] : null;
  if ($key || $shortkey) {
  	if ($key) {
	  	$fileInfos = getFileInfos($key);
		} else {
	  	$fileInfos = getFileInfos($shortkey,"downloadFromShort");
		}
		if (!$fileInfos) {
		  include("notfound.php");
		  exit;
		}
  } else {
  	header("location:./");
  	exit;
  }


?>

<div class='container'>
  <div class="jumbotron">
    <h1><?php echo lang("hl_reportfile"); ?></h1>
  </div>

  <div class="row">
  	<div class="col-sm-9">
  		<h3><?php echo lang("hl_reportfileform"); ?></h3>
  		<p><?php echo lang("info_abuse"); ?></p>
  		<div id='cnote'><?php
	if (isset($error) && $error) {
		echo $error;
	}
	if (isset($success) && $success) {
		echo "<div class='alert alert-success'>$success</div>";
	}
?></div>
		<form class="form-horizontal" method="post" action="abuse.php" id="abusef">
			<input type="hidden" name="action" value="abuse" />
			<input type="hidden" name="dk" value="<?php echo $key; ?>" />
			<input type="hidden" name="sk" value="<?php echo $shortkey; ?>" />
	
			<div class='row'>
				<div class='col-md-6'>
					<div class="form-group">
					<label class="control-label col-sm-4"><?php echo lang("descr_fname"); ?></label>
					<div class="col-sm-8">
						<input type="text" name="fname" class="form-control" value="<?php echo he($fileInfos->descr); ?>" readonly="readonly" /></div>
					</div>
				</div>
				<div class='col-md-6'>
					<div class="form-group">
						<label class="control-label col-sm-4"><?php echo lang("descr_fsize"); ?></label>
						<div class="col-sm-8"><input type="text" name="fsize" class="form-control" value="<?php echo fsize($fileInfos->fsize); ?>" readonly="readonly" /></div>
					</div>
				</div>
				<div class='col-md-6'>
					<div class="form-group">
						<label class="control-label col-sm-4"><?php echo lang("descr_name"); ?></label>
						<div class="col-sm-8"><input type="text" name="name" placeholder="<?php echo lang("placeholder_name"); ?>" class="form-control" value="" required="required" /></div>
					</div>
				</div>
				<div class='col-md-6'>
					<div class="form-group">
						<label class="control-label col-sm-4"><?php echo lang("descr_email"); ?></label>
						<div class="col-sm-8"><input type="email" name="email" placeholder="<?php echo lang("placeholder_email"); ?>" class="form-control" value="" required="required" /></div>
					</div>
				</div>
				<div class='col-md-6'>
					<div class='form-group'>
						<div class="col-xs-12"><textarea name="message" placeholder="<?php echo lang("placeholder_message"); ?>" class="form-control" rows="5" required="required"></textarea></div>
					</div>
				</div>
				<?php 
				if ($config->captchaContact) {
				?>
				<div class='col-md-6'>
					<div class="form-group">
						<label class="control-label col-sm-4"><?php echo lang("descr_spam_protection"); ?></label>
						<div class='col-sm-8 pull-right'>
							<img src='img/cap1.png' alt='' class='img-responsive img-rounded img-captcha' />
							<button type='button' class='btn btn-xs btn-default btn-captcha-refresh'><i class='fa fa-refresh'></i></button>
						</div>
						<div class='clearfix'></div>
						<div class='col-sm-8 col-sm-offset-4'><span class='text-muted small nob'><?php echo lang("info_spam_protection"); ?></span>
							<input type="text" name="captcha" placeholder="<?php echo lang("placeholder_spam_protection"); ?>" class="form-control" value="" required='required' />
						</div>
					</div>
				</div>
				<div class='clearfix'></div>

				<?php 
				}
				?>
				<div class='col-xs-12'>
					<div class="form-group">
						<div class="col-md-6">
							<input type="submit" class="btn btn-primary btn-block" value="<?php echo lang("descr_reportfile"); ?>">
						</div>
					</div>
				</div>			
			</div> <!-- row -->
		</form>
  	</div>


  	<div class="col-sm-3">
  		<h4>NanoHard Company Ltd.</h4>
			<p>Johnny Street 10<br />
			Seattle<br />
			United States of America<br /><br />
			<strong>Email</strong>: <a href='mailto:nobody@nowhere.tld'>nobody@nowhere.tld</a><br />
			<strong>Telefon</strong>: +1 2 34455665<br /><br />
			<a href='https://maps.google.com/maps?q=batman+park+Melbourne,+Victoria,+Australien&amp;hl=de&amp;ll=-37.822769,144.956317&amp;spn=0.007822,0.016512&amp;sll=-37.817942,144.964977&amp;sspn=0.031291,0.066047&amp;hq=batman+park+Melbourne,+Victoria,+Australien&amp;t=m&amp;z=17' onclick="window.open(this.href); return false;"><img src='<?php echo $config->instDir; ?>/img/map.gif' alt='' class='img-rounded img-respinsive' /></a>
			</p>
  	</div>
  </div>

</div> <!-- container -->


<?php include("includes/footer.php"); ?>
