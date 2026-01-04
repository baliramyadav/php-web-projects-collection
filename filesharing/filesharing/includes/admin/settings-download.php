<form action='?as=<?php echo $adminSection; ?>' id='fConfigDownload'>
	<input type='hidden' name='ass' value='<?php echo $adminSubSection; ?>' />

<?php

	$kbps = $SFS->config->kbps;
	$delDays = $SFS->config->delDays;
	$delOn = $SFS->config->delOn;
	$delDownloadsNumbers = $SFS->config->delDownloadsNumbers;
	$downloadProtection = $SFS->config->downloadProtection;
	if (!$downloadProtection) $downloadProtection = 0;
	$downloadSeconds = $SFS->config->downloadSeconds;

?>

	
	<div class='panel panel-default panel-sfs-settings'>
		<div class='panel-heading'><h3 class='panel-title'>XSendFile<i class='fa fa-chevron-up fa-fw pull-right'></i></h3></div>
		<div class='panel-body'>
			can only be used if Apache XSendFile module is enabled<br />
			and the possibility to turn of if it's not configured well<br /><br />
			If you got an earlier version of XSendFile <code>&lt;=0.9</code> you could add <code>XSendFileAllowAbove On</code> into your <code>.htaccess</code><br />
			In versions <code>0.10++</code> you have to set the <code>XSendFilePath</code> in the config of your virtual host (can't be done in the .htaccess)<br /><br />
			More information can be found at <a href='https://tn123.org/mod_xsendfile/' class='open-link-external'>https://tn123.org/mod_xsendfile/ <i class='fa fa-external-link'></i></a><br /><br />
			Enable the option below if it works and downloadsize is higher than 0 bytes<br /><br />
			<div class='alert alert-warning alert-sm'><i class='fa fa-exclamation-triangle'></i> XSendFile functionality will be disabled if bandwidth throtteling is enabled</div>
			<div class='alert alert-danger alert-sm'><i class='fa fa-exclamation-triangle'></i> you have to uncomment the setenv line in the .htaccess too to enable XSendFile</div>

			<?php
				if ($SFS->config->XSendFile) {
					echo "<button type='button' class='btn btn-success js-btn-xsendfile'><i class='fa fa-fw fa-check-square-o'></i> use XSendFile</button>";
				} else {
					echo "<button type='button' class='btn btn-default js-btn-xsendfile'><i class='fa fa-fw fa-square-o'></i> use XSendFile</button>";
				}
			?>
		</div>
	</div>
	
	<div class='panel panel-default panel-sfs-settings'>
		<div class='panel-heading'><h3 class='panel-title'>Bandwidth Throtteling<i class='fa fa-chevron-up fa-fw pull-right'></i></h3></div>
		<div class='panel-body'>
			if the value is higher as 0 this value will be used as kilobyte per second<br />
			set it to <code>0</code> or leave it empty to disable bandwidth throtteling<br />
			the calculation result is not exact and can be different from server to server, you've to play around to get the values for your server<br /><br />

			<div class='form-horizontal'>
				<div class='form-group'>

					<label class='col-md-4 col-lg-3 col-sm-5 control-label col-xs-12'>Bandwidth Throtteling</label>
					<div class='col-sm-7 col-md-5 col-xs-12'>
						<div class='row'>
							<div class='col-xs-9'>
								<div class="input-group">
									<input name='kbps' type='number' min='0' value='<?php echo $kbps; ?>' class='form-control text-right' />
									<span class="input-group-addon">kbps</span>
								</div>
							</div>
							<div class='col-xs-3 save-status-block'>
					  		<button type='button' class='btn btn-primary js-btn-save-kbps js-hide'><i class='fa fa-save'></i> Save</button>
					  		<strong class='text-success js-hide'><i class='fa fa-check'></i> saved</strong>
							</div>
						</div>
					</div>
			 	</div>
		 	</div>
		</div>
	</div>

	<div class='panel panel-default panel-sfs-settings'>
		<div class='panel-heading'><h3 class='panel-title'>File Availibility <small>until Auto Deletion</small><i class='fa fa-chevron-up fa-fw pull-right'></i></h3></div>
		<div class='panel-body'>
			<h4>Expiration Days</h4>
			... after which the files should be deleted automatically<br />
			use <code>0</code> or leave it empty to autodelete approximately within one day<br />
			use <code>-1</code> to disable autodeletion<br /><br />

			<div class='form-horizontal'>
				<div class='form-group'>

					<label class='col-md-4 col-lg-3 col-sm-5 control-label col-xs-12'>Expiration Days</label>
					<div class='col-sm-7 col-md-5 col-lg-4 col-xs-12'>
						<div class='row'>
							<div class='col-xs-9'>
								<div class="input-group">
									<input name='delDays' type='number' min='-1' value='<?php echo $delDays; ?>' class='form-control text-right' />
									<span class="input-group-addon">Days</span>
								</div>
							</div>
							<div class='col-xs-3 save-status-block'>
					  		<button type='button' class='btn btn-primary js-btn-save-deldays js-hide'><i class='fa fa-save'></i> Save</button>
					  		<strong class='text-success js-hide'><i class='fa fa-check'></i> saved</strong>
							</div>
						</div>
					</div>
			 	</div>
		 	</div>

		 	<h4>Auto Deletion Reference</h4>
			Auto deletion (after <code>Expiration Days</code>) depending on<br />
			<code>download</code> based on datetime of last download<br />
			<code>upload</code> based on datetime of upload<br /><br />

			<?php
				$delOnOptions = "<option value='upload'>Upload</option>
					<option value='download'>Download</option>";
				$delOnOptions = str_replace("value='$delOn'", "value='$delOn' selected='selected'", $delOnOptions);

			?>

			<div class='form-horizontal'>
				<div class='form-group'>

					<label class='col-md-4 col-lg-3 col-sm-5 control-label col-xs-12'>Autodeletion based on</label>
					<div class='col-sm-7 col-md-5 col-lg-4 col-xs-12'>
						<div class='row'>
							<div class='col-xs-9'>
								<div class="input-group">
									<span class="input-group-addon">Date of</span>
									<select name='delOn' class='form-control chosen' style="width:100%;"><?php echo $delOnOptions; ?></select>
								</div>
							</div>
							<div class='col-xs-3 save-status-block'>
					  		<button type='button' class='btn btn-primary js-btn-save-delon js-hide'><i class='fa fa-save'></i> Save</button>
					  		<strong class='text-success js-hide'><i class='fa fa-check'></i> saved</strong>
							</div>
						</div>
					</div>
			 	</div>
		 	</div>

		 	<h4>List of Maximum Number of possible Downloads</h4>
		 	used to be selected by uploader, if allowed to

			<div class='form-horizontal'>
				<div class='form-group'>

					<label class='col-md-4 col-lg-3 col-sm-5 control-label col-xs-12'><small>&nbsp;</small><br />Download Number List</label>
					<div class='col-sm-7 col-md-8 col-lg-9 col-xs-12'>
						<div class='row'>
							<div class='col-xs-9'><small class='text-muted'>numbers seperated by comma <code>,</code></small><br />
								<input type='text' name='delDownloadsNumbers' class='form-control' value='<?php echo $delDownloadsNumbers; ?>' placeholder='numbers seperated by comma, e.g. 1,2,3,4,5,6,7,8,9,10,15,20' />
							</div>
							<div class='col-xs-3 save-status-block'><small>&nbsp;</small><br />
					  		<button type='button' class='btn btn-primary js-btn-save-deldownloadsnumbers js-hide'><i class='fa fa-save'></i> Save</button>
					  		<strong class='text-success js-hide'><i class='fa fa-check'></i> saved</strong>
							</div>
						</div>
					</div>
			 	</div>
		 	</div>

		 	<h4>Uploader Deletion Settings</h4>
			Enable deletion settings by uploader<br />
			After one of the two options (number of downloads, age of uploaded file ...) has exceeded the file will be deleted automatically<br />
			If <code>Expiration Days</code> are set to a value <code>lower 2</code> the deletion days cannot be set by the uploader<br /><br />

			<?php
				if ($SFS->config->delSettingsByUploader) {
					echo "<button type='button' class='btn btn-success js-btn-delsettingsbyuploader'><i class='fa fa-fw fa-check-square-o'></i> let Uploader select Download File Availibility Options</button>";
				} else {
					echo "<button type='button' class='btn btn-default js-btn-delsettingsbyuploader'><i class='fa fa-fw fa-square-o'></i> let Uploader select Download File Availibility Options</button>";
				}
			?>

		</div>
	</div>


	<div class='panel panel-default panel-sfs-settings'>
		<div class='panel-heading'><h3 class='panel-title'>File Download Protection<i class='fa fa-chevron-up fa-fw pull-right'></i></h3></div>
		<div class='panel-body'>
			Usually the files only can be downloaded through the download page where the link to the file is accessible.<br />
			There are three options available
				<dl class='dl-horizontal'>
					<dt>false/disable protection</dt><dd>when disabling the protection file links can be used to download without redirecting to download page</dd>
					<dt>IP-based</dt><dd>if the download page was accessed by a client the IP will be stored and the file can be downloaded directly on the same client without the need of the download page, even with a new the browser-session/instance</dd>
					<dt>Session-based</dt><dd>ias long the browser session is alive download can be started without accessing the download page as long as it was called at least once</dd>
				</dl>


			<?php
				$downloadProtectionOptions = "<option value='0'>false/disable protection</option>
					<option value='IP'>IP-based</option>
					<option value='SESSION'>SESSION-based</option>";
				$downloadProtectionOptions = str_replace("value='$downloadProtection'", "value='$downloadProtection' selected='selected'", $downloadProtectionOptions);

			?>

			<div class='form-horizontal'>
				<div class='form-group'>

					<label class='col-md-4 col-lg-3 col-sm-5 control-label col-xs-12'>File Download Protection</label>
					<div class='col-sm-7 col-md-6 col-lg-5 col-xs-12'>
						<div class='row'>
							<div class='col-xs-9'>
								<select name='downloadProtection' class='form-control chosen' style="width:100%;"><?php echo $downloadProtectionOptions; ?></select>
							</div>
							<div class='col-xs-3 save-status-block'>
					  		<button type='button' class='btn btn-primary js-btn-save-downloadprotection js-hide'><i class='fa fa-save'></i> Save</button>
					  		<strong class='text-success js-hide'><i class='fa fa-check'></i> saved</strong>
							</div>
						</div>
					</div>
			 	</div>
		 	</div>


		</div>
	</div>


	<div class='panel panel-default panel-sfs-settings'>
		<div class='panel-heading'><h3 class='panel-title'>Password Protection<i class='fa fa-chevron-up fa-fw pull-right'></i></h3></div>
		<div class='panel-body'>
			Files uploads can be protected with a password (unique for each upload session)<br />
			If enabled uploaders can decide if downloads should be password protected, and if enabled by uploadthe downloaders will be prompted to give the correct password<br />
			The passwords will be set automatically<br /><br />
			
			<?php
				if ($SFS->config->passwordProtection) {
					echo "<button type='button' class='btn btn-success js-btn-passwordprotection'><i class='fa fa-fw fa-check-square-o'></i> let Uploaders password protect their Uploads</button>";
				} else {
					echo "<button type='button' class='btn btn-default js-btn-passwordprotection'><i class='fa fa-fw fa-square-o'></i> let Uploaders password protect their Uploads</button>";
				}
			?>


		</div>
	</div>



	<div class='panel panel-default panel-sfs-settings'>
		<div class='panel-heading'><h3 class='panel-title'>Download Delay <small>seconds before download possible</small><i class='fa fa-chevron-up fa-fw pull-right'></i></h3></div>
		<div class='panel-body'>
			Seconds before download should be possible after calling download link<br />
			if the value is higher as 0 this value will be used as value for these waiting seconds<br />
			set it to <code>0</code> to disable the waiting seconds<br /><br />

			<div class='form-horizontal'>
				<div class='form-group'>

					<label class='col-md-4 col-lg-3 col-sm-5 control-label col-xs-12'>Download Seconds</label>
					<div class='col-sm-7 col-md-5 col-xs-12'>
						<div class='row'>
							<div class='col-xs-9'>
								<div class="input-group">
									<input name='downloadSeconds' type='number' min='0' value='<?php echo $downloadSeconds; ?>' class='form-control text-right' />
									<span class="input-group-addon">Seconds</span>
								</div>
							</div>
							<div class='col-xs-3 save-status-block'>
					  		<button type='button' class='btn btn-primary js-btn-save-downloadseconds js-hide'><i class='fa fa-save'></i> Save</button>
					  		<strong class='text-success js-hide'><i class='fa fa-check'></i> saved</strong>
							</div>
						</div>
					</div>
			 	</div>
		 	</div>
		</div>
	</div>


</form>

