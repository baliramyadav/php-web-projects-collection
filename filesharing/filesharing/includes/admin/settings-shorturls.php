<form action='?as=<?php echo $adminSection; ?>' id='fConfigShortUrls'>
	<input type='hidden' name='ass' value='<?php echo $adminSubSection; ?>' />

<?php
	$shortUrls = $SFS->config->shortUrls;
	$bitlyUser = $SFS->config->bitlyUser;
	$bitlyKey = $SFS->config->bitlyKey;
	$adflyUid = $SFS->config->adflyUid;
	$adflyKey = $SFS->config->adflyKey;
	$adflyAdvertType = $SFS->config->adflyAdvertType;
	$connectionMethod = $SFS->config->connectionMethod;

	$shortUrlsOptions = "<option value='0'>Don't offer any URL Shortener</option>
		<option value='bitly'>Use bitly URL shortener</option>
		<option value='adfly'>Use adfly URL shortener</option>";
	if (!$shortUrls) $shortUrls = 0;
	$shortUrlsOptions = str_replace("value='$shortUrls'","value='$shortUrls' selected='selected'", $shortUrlsOptions);

	$adflyAdvertTypeOptions = "<option value='int'>INT - less aggressive</option>
		<option value='banner'>BANNER - more aggressive</option>";
	$adflyAdvertTypeOptions = str_replace("value='$adflyAdvertType'","value='$adflyAdvertType' selected='selected'", $adflyAdvertTypeOptions);

	$connectionMethodOptions = "<option value='auto'>auto</option>
		<option value='url_fopen'>allow_url_fopen</option>
		<option value='curl'>CURL</option>";
	$connectionMethodOptions = str_replace("value='$connectionMethod'","value='$connectionMethod' selected='selected'", $connectionMethodOptions);

?>


	<div class='panel panel-default panel-sfs-settings'>
		<div class='panel-heading'><h3 class='panel-title'> URL Shortening System<i class='fa fa-chevron-up fa-fw pull-right'></i></h3></div>
		<div class='panel-body'>

			By default the Download and Deletion URLs of SFS are quite long to make them hard to reproduce - but you can offer uploaders to generate short versions of these urls in example for copy reasons.<br />
			Please select either to disabling this option or choose and configure a service you like to offer your uploaders to shorten URLs.<br /><br />

			<div class='alert alert-info'>Since version 3.50 the download URLs are already shortened by default (5 digits per default)</div>


				<div class='form-horizontal disable-shortener'>
					<div class='form-group'>
						<label class='col-md-4 col-lg-3 col-sm-5 control-label col-xs-6'>URL Shortener</label>
						<div class='col-sm-7 col-md-5 col-lg-6 col-xs-6'>
							<div class='row'>
								<div class='col-xs-8'>
									<select name='shortUrls' class='form-control'><?php echo $shortUrlsOptions; ?></select>
								</div>
								<div class='save-status-block col-lg-4 col-xs-12'>
						  		<button type='button' class='btn btn-primary js-btn-save-shortener js-hide'><i class='fa fa-save'></i> Save</button>
						  		<strong class='text-success js-hide'><i class='fa fa-check'></i> saved</strong>
								</div>
							</div>
						</div>
					</div>
				</div>

				<div class='shortener-block panel panel-primary <?php echo $shortUrls !== "bitly" ? "js-hide" : ""; ?>' data-shortener='bitly'>
					<div class='panel-heading'><h4 class='panel-title'>Bitly URL Shortener Settings <a href='https://bitly.com/' class='small pull-right open-link-external'>https://bitly.com/ <i class='fa fa-external-link'></i></a></h4></div>
					<div class='panel-body'>
					you should get the required information <a href='https://app.bitly.com/' class='open-link-external'>here <i class='fa fa-external-link'></i></a> when registered and logged in<br />
					<i class='fa fa-angle-right'></i> click on the drop down in the upper right<br />
					<i class='fa fa-angle-right'></i> Profile Settings<br />
					<i class='fa fa-angle-right'></i> Generic Access Token<br />
					<i class='fa fa-angle-right'></i> Generate Token (or copy generated token)<br />
						<div class='form-horizontal'>
							<div class='form-group'>
								<label class='col-md-4 col-lg-3 col-sm-5 control-label col-xs-12'><u>bitly</u> User</label>
								<div class='col-sm-7 col-md-5 col-lg-4 col-xs-6'>
									<input name='bitlyUser' type='text' value='<?php echo $bitlyUser; ?>' class='form-control' />
								</div>
						 	</div>
					 	</div>
					 	<div class='form-horizontal'>
							<div class='form-group'>
								<label class='col-md-4 col-lg-3 col-sm-5 control-label col-xs-12'><u>bitly</u> Access Token</label>
								<div class='col-sm-7 col-md-5 col-lg-4 col-xs-6'>
									<input name='bitlyKey' type='text' value='<?php echo $bitlyKey; ?>' class='form-control' />
								</div>
						 	</div>
					 	</div>
					 	<div class='form-horizontal'>
							<div class='form-group'>
								<label class='col-md-4 col-lg-3 col-sm-5 control-label col-xs-12'>Connection Method</label>
								<div class='col-sm-7 col-md-5 col-lg-4 col-xs-6'>
									<select name='connectionMethod' class='form-control'><?php echo $connectionMethodOptions; ?></select>
								</div>
						 	</div>
						 </div>

					 	<div class='row'>
							<div class='save-status-block col-sm-6 col-sm-offset-5 col-md-offset-4 col-lg-offset-3 col-xs-offset-0'>
					  		<button type='button' class='btn btn-primary js-btn-save-shortener js-hide'><i class='fa fa-save'></i> Save</button>
					  		<strong class='text-success js-hide'><i class='fa fa-check'></i> saved</strong>
							</div>
						</div>
					
					</div>
				</div>

				<div class='shortener-block panel panel-primary <?php echo $shortUrls !== "adfly" ? "js-hide" : ""; ?>' data-shortener='adfly'>
					<div class='panel-heading'><h4 class='panel-title'>Adfly URL Shortener Settings <a href='https://adf.ly/' class='small open-link-external pull-right'>https://adf.ly/ <i class='fa fa-external-link'></i></a></h4></div>
					<div class='panel-body'>
					you should get/create the required information <a href='https://adf.ly/publisher/tools#tools-api' class='open-link-external'>here <i class='fa fa-external-link'></i></a> when logged in<br />
					or there (logged in too) via their Menue: Tools <i class='fa fa-long-arrow-right'></i> API-Documentation<br /><br />
						<div class='form-horizontal'>
							<div class='form-group'>
								<label class='col-md-4 col-lg-3 col-sm-5 control-label col-xs-12'><u>adfly</u> UID</label>
								<div class='col-sm-7 col-md-5 col-lg-4 col-xs-6'>
									<input name='adflyUid' type='text' value='<?php echo $adflyUid; ?>' class='form-control' />
								</div>
						 	</div>
					 	</div>
					  <div class='form-horizontal'>
							<div class='form-group'>
								<label class='col-md-4 col-lg-3 col-sm-5 control-label col-xs-12'><u>adfly</u> API Key</label>
								<div class='col-sm-7 col-md-5 col-lg-4 col-xs-6'>
									<input name='adflyKey' type='text' value='<?php echo $adflyKey; ?>' class='form-control' />
								</div>
						 	</div>
					 	</div>
					 	<div class='form-horizontal'>
							<div class='form-group'>
								<label class='col-md-4 col-lg-3 col-sm-5 control-label col-xs-12'><u>adfly</u> UID</label>
								<div class='col-sm-7 col-md-5 col-lg-4 col-xs-6'>
									<select name='adflyAdvertType' class='form-control'><?php echo $adflyAdvertTypeOptions; ?></select>
								</div>
						 	</div>
					 	</div>
					 	<div class='form-horizontal'>
							<div class='form-group'>
								<label class='col-md-4 col-lg-3 col-sm-5 control-label col-xs-12'>Connetcion Method</label>
								<div class='col-sm-7 col-md-5 col-lg-4 col-xs-6'>
									<select name='connectionMethod' class='form-control'><?php echo $connectionMethodOptions; ?></select>
								</div>
						 	</div>
					 	</div>

					 	<div class='row'>
							<div class='save-status-block col-sm-6 col-sm-offset-5 col-md-offset-4 col-lg-offset-3 col-xs-offset-0'>
					  		<button type='button' class='btn btn-primary js-btn-save-shortener js-hide'><i class='fa fa-save'></i> Save</button>
					  		<strong class='text-success js-hide'><i class='fa fa-check'></i> saved</strong>
							</div>
						</div>

					 </div>
					</div>

		</div>
	</div>

	

	
	

 	




</form>

