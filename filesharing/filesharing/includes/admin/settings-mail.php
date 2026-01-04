<form action='?as=<?php echo $adminSection; ?>' id='fConfigMail'>
	<input type='hidden' name='ass' value='<?php echo $adminSubSection; ?>' />

<?php

	$admin_mail = $SFS->config->admin_mail;
	$automaileraddr = $SFS->config->automaileraddr;
	$contact_mail = $SFS->config->contact_mail;
	$mailParams = $SFS->config->mailParams;

?>


	<div class='panel panel-default panel-sfs-settings'>
		<div class='panel-heading'><h3 class='panel-title'>Mail Settings<i class='fa fa-chevron-up fa-fw pull-right'></i></h3></div>
		<div class='panel-body'>

			<h4>Email Addresses</h4>
			<div class='form-horizontal'>

				<div class='form-group'>
					<label class='col-md-4 col-lg-3 col-sm-5 control-label col-xs-12'>Admin Email</label>
					<div class='col-sm-7 col-md-8 col-lg-9 col-xs-12'>
						<div class='row'>
							<div class='col-xs-8 col-lg-5'>
								<input name='admin_mail' type='email' value='<?php echo $admin_mail; ?>' class='form-control' />
							</div>
							<div class='col-xs-4 save-status-block'>
					  		<button type='button' class='btn btn-primary js-btn-save-admin_mail js-hide'><i class='fa fa-save'></i> Save</button>
					  		<strong class='text-success js-hide'><i class='fa fa-check'></i> saved</strong>
							</div>
						</div>
						<p class="help-block">Administrator's email for debug messages (DB-Errors, not existing language keys, ...)</p>
					</div>
			 	</div>

				<div class='form-group'>
					<label class='col-md-4 col-lg-3 col-sm-5 control-label col-xs-12'>Automailer Email</label>
					<div class='col-sm-7 col-md-8 col-lg-9 col-xs-12'>
						<div class='row'>
							<div class='col-xs-8 col-lg-5'>
								<input name='automaileraddr' type='email' value='<?php echo $automaileraddr; ?>' class='form-control' />
							</div>
							<div class='col-xs-4 save-status-block'>
					  		<button type='button' class='btn btn-primary js-btn-save-automaileraddr js-hide'><i class='fa fa-save'></i> Save</button>
					  		<strong class='text-success js-hide'><i class='fa fa-check'></i> saved</strong>
							</div>
						</div>
						<p class="help-block">Email address used as from address for file sharing email messages</p>
					</div>
			 	</div>

				<div class='form-group'>
					<label class='col-md-4 col-lg-3 col-sm-5 control-label col-xs-12'>Contact Email</label>
					<div class='col-sm-7 col-md-8 col-lg-9 col-xs-12'>
						<div class='row'>
							<div class='col-xs-8 col-lg-5'>
								<input name='contact_mail' type='email' value='<?php echo $contact_mail; ?>' class='form-control' />
							</div>
							<div class='col-xs-4 save-status-block'>
					  		<button type='button' class='btn btn-primary js-btn-save-contact_mail js-hide'><i class='fa fa-save'></i> Save</button>
					  		<strong class='text-success js-hide'><i class='fa fa-check'></i> saved</strong>
							</div>
						</div>
						<p class="help-block">Target for the contact form</p>
					</div>
			 	</div>

		 	</div> 


			<h4>Additional Mail Parameters</h4>
			<div class='form-horizontal'>

				If applicable or needed: <a href='https://secure.php.net/manual/en/function.mail.php'>https://secure.php.net/manual/en/function.mail.php <i class='fa fa-external-link'></i></a><br />
				e.g. <code>-f sender@address.com</code><br />
				leave it empty if not needed<br /><br />
				<div class='form-group'>
					<label class='col-md-4 col-lg-3 col-sm-5 control-label col-xs-12'>Mail parameters</label>
					<div class='col-sm-7 col-md-8 col-lg-9 col-xs-12'>
						<div class='row'>
							<div class='col-xs-8 col-lg-5'>
								<input name='mailParams' type='text' value='<?php echo $mailParams; ?>' class='form-control' />
							</div>
							<div class='col-xs-4 save-status-block'>
					  		<button type='button' class='btn btn-primary js-btn-save-mailparams js-hide'><i class='fa fa-save'></i> Save</button>
					  		<strong class='text-success js-hide'><i class='fa fa-check'></i> saved</strong>
							</div>
						</div>
					</div>
			 	</div>


			</div>


		</div>
	</div>

	
</form>

