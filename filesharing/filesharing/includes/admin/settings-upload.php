<form action='?as=<?php echo $adminSection; ?>' id='fConfigUpload'>
	<input type='hidden' name='ass' value='<?php echo $adminSubSection; ?>' />

<?php

	$maxFileSize = $SFS->config->maxFileSizeDB;
	$maxMultiFiles = $SFS->config->maxMultiFiles;
	$extAllowed = $SFS->config->extAllowed;
	$extDenied = $SFS->config->extDenied;
	$maxRcpt = $SFS->config->maxRcpt;
	$prevWidth = $SFS->config->prevWidth;
	$prevHeight = $SFS->config->prevHeight;
?>

	<div class='panel panel-default panel-sfs-settings'>
		<div class='panel-heading'><h3 class='panel-title'>Upload Permission<i class='fa fa-chevron-up fa-fw pull-right'></i></h3></div>
		<div class='panel-body'>

			You can deactivate uploading for free users completely.<br />
			Only allow Simple File Sharer admin to upload <i class='fa fa-long-arrow-right'></i> this will show a login form on upload page (index.php by default) - but you could rename it :)<br /><br />
			if enabled only admin will be able to upload<br />
			if disable everyone will be able to upload<br /><br />
			<?php
				if ($SFS->config->adminOnlyUploads) {
					echo "<button type='button' class='btn btn-success js-btn-adminonlyuploads'><i class='fa fa-fw fa-check-square-o'></i> Allow only Admin to upload files</button>";
				} else {
					echo "<button type='button' class='btn btn-default js-btn-adminonlyuploads'><i class='fa fa-fw fa-square-o'></i> Allow only Admin to upload files</button>";
				}
			?>
		</div>
	</div>

	<div class='panel panel-default panel-sfs-settings'>
		<div class='panel-heading'><h3 class='panel-title'>Maximum Upload File Size<i class='fa fa-chevron-up fa-fw pull-right'></i></h3></div>
		<div class='panel-body'>
			the filesize that really can be used for uploads is depending on various settings and server environments. For more information please read the manual (7.b.) - <strong>FastCGI</strong> and <strong>nginx</strong> Information can be found there too.<br />
			The maximum possible value for yur uploads will be recalculated automatically that only would be used which your server is able to handle.<br />
			<div class='alert alert-warning'><strong class='text-danger'>BUT</strong> not all of your server information can be examined by SFS (nginx, FastCGI, ...), so you have to make sure if you adapt your server environment too.</div>
			<h4>Current server Settings</h4>
			<div class='row'>
				<div class='col-md-6'>
					<?php

						$pms = ini_get("post_max_size");
						//Kilo
						if (preg_match('/k$/i',$pms)) $pms = intval($pms) * 1024;
						//mega
						elseif (preg_match('/m$/i',$pms)) $pms = intval($pms) * 1024 * 1024;
						//giga
						elseif (preg_match('/g$/i',$pms)) $pms = intval($pms) * 1024 * 1024 * 1024;
						//integer
						else $pms = intval($pms);

						$maxPossibleSize = $pms;
					?>

					<div class='alert alert-info'><h4>post_max_size <small class='pull-right'><a href='https://php.net/manual/en/ini.core.php#ini.post-max-size' class='open-link-external'>more information here <i class='fa fa-external-link'></i></a></small></h4>
						<dl class='dl-horizontal'>
							<dt>read from the config</dt>
							<dd><?php echo ini_get("post_max_size"); ?></dd>
							<dt>calcuating with</dt>
							<dd><?php echo $pms/1024/1024; ?></dd>
							<dt>max possible size</dt>
							<dd><?php echo fsize($maxPossibleSize); ?></dd>
						</dl>
					</div>
				</div>

				<div class='col-md-6'>
					<?php
						$umf = ini_get("upload_max_filesize");
						//Kilo
						if (preg_match('/k$/i',$umf)) $umf = intval($umf) * 1024;
						//mega
						elseif (preg_match('/m$/i',$umf)) $umf = intval($umf) * 1024 * 1024;
						//giga
						elseif (preg_match('/g$/i',$umf)) $umf = intval($umf) * 1024 * 1024 * 1024;
						//integer
						else $umf = intval($umf);
						if ($maxPossibleSize > $umf) $maxPossibleSize = $umf;

					?>
					<div class='alert alert-info'><h4>upload_max_filesize <small class='pull-right'><a href='https://php.net/manual/en/ini.core.php#ini.upload-max-filesize' class='open-link-external'>more information here <i class='fa fa-external-link'></i></a></small></h4>
						
						<dl class='dl-horizontal'>
							<dt>read from the config</dt>
							<dd><?php echo ini_get("upload_max_filesize"); ?></dd>
							<dt>calcuating with</dt>
							<dd><?php echo $umf/1024/1024; ?></dd>
							<dt>max possible size</dt>
							<dd><?php echo fsize($maxPossibleSize); ?></dd>
						</dl>
					</div>
				</div>
			</div>

		<div class='form-horizontal'>
			<div class='form-group'>

				<label class='col-md-4 col-lg-3 col-sm-5 control-label col-xs-12'><span class='small'>&nbsp;</span><br />Maximum Upload File Size<br /></label>
				<div class='col-sm-7 col-md-5 col-lg-4 col-xs-12'>
					<span class='text-muted small'>currently supported by your Server: <?php echo fsize($maxPossibleSize); ?></span>
					<div class='row'>
						<div class='col-xs-8'>
							<div class="input-group">
								<input name='maxFileSize' type='number' step='.5' min='1' value='<?php echo $maxFileSize; ?>' class='form-control text-right' />
								<span class="input-group-addon">MB</span>
							</div>
						</div>
						<div class='col-xs-4 save-status-block'>
				  		<button type='button' class='btn btn-primary js-btn-save-maxfilesize js-hide'><i class='fa fa-save'></i> Save</button>
				  		<strong class='text-success js-hide'><i class='fa fa-check'></i> saved</strong>
						</div>
					</div>
				</div>
		 	</div>
	 	</div>
	 </div>
	</div>

	<div class='panel panel-default panel-sfs-settings'>
		<div class='panel-heading'><h3 class='panel-title'>Multiple File Uploads<i class='fa fa-chevron-up fa-fw pull-right'></i></h3></div>
		<div class='panel-body'>

			If enabled users will be able to upload more files by drag and drop or file select button,<br />otherwise only one file can be uploaded once<br /><br />

			<?php
				if ($SFS->config->multiUploadDB) {
					echo "<button type='button' class='btn btn-success js-btn-multiupload'><i class='fa fa-fw fa-check-square-o'></i> Uploaders are able to upload multiple files at once</button>";
				} else {
					echo "<button type='button' class='btn btn-default js-btn-multiupload'><i class='fa fa-fw fa-square-o'></i> Uploaders are able to upload multiple files at once</button>";
				}
			?>
			<div class='mt20 additional-multi-upload-settings <?php echo !$SFS->config->multiUploadDB ? "js-hide" : ""; ?>'>
				<h4>Maximum Number of Multiple Files</h4> 

				maximum number of allowed files to upload at once, has to be a positive integer<br /><br />

				<div class='form-horizontal'>
					<div class='form-group'>

						<label class='col-md-4 col-lg-3 col-sm-5 control-label col-xs-12'>Maximum Number of Files</label>
						<div class='col-sm-7 col-md-5 col-lg-4 col-xs-12'>
							<div class='row'>
								<div class='col-xs-8'>
									<input name='maxMultiFiles' type='number' min='2' value='<?php echo $maxMultiFiles; ?>' class='form-control' />
								</div>
								<div class='col-xs-4 save-status-block'>
						  		<button type='button' class='btn btn-primary js-btn-save-maxmultifiles js-hide'><i class='fa fa-save'></i> Save</button>
						  		<strong class='text-success js-hide'><i class='fa fa-check'></i> saved</strong>
								</div>
							</div>
						</div>
				 	</div>
			 	</div>
			</div>

			<div class='mt20'>
			 	<h4>Add Files to current Session</h4>
			 	... first upload(s) finished<br />
				If enabled uploaders have the possibility to add files to their current upload session<br /><br />

				<?php
					if ($SFS->config->addAnotherFiles) {
						echo "<button type='button' class='btn btn-success js-btn-addanotherfiles'><i class='fa fa-fw fa-check-square-o'></i> Uploaders are able to add files to current upload session</button>";
					} else {
						echo "<button type='button' class='btn btn-default js-btn-addanotherfiles'><i class='fa fa-fw fa-square-o'></i> Uploaders are able to add files to current upload session</button>";
					}
				?>

			</div>


		</div>
	</div>

	

 	<div class='panel panel-default panel-sfs-settings'>
		<div class='panel-heading'><h3 class='panel-title'>File Extensions<i class='fa fa-chevron-up fa-fw pull-right'></i></h3></div>
		<div class='panel-body'>

			<h4>Allowed File Extensions</h4>
			File extensions allowed to upload, if set only files with these extensions are allowed to upload<br />Leave it empty for no allowance restrictions
			<div class='form-horizontal'>
				<div class='form-group'>

					<label class='col-md-4 col-lg-3 col-sm-5 control-label col-xs-12'><small>&nbsp;</small><br />Allowed File Extensions</label>
					<div class='col-sm-7 col-md-8 col-lg-9 col-xs-12'>
						<div class='row'>
							<div class='col-xs-9'><small class='text-muted'>file types/extensions seperated by comma <code>,</code></small><br />
								<input type='text' name='extAllowed' class='form-control' value='<?php echo $extAllowed; ?>' placeholder='file types/extensions seperated by comma, e.g. jpg,jpeg,png' />
							</div>
							<div class='col-xs-3 save-status-block'><small>&nbsp;</small><br />
					  		<button type='button' class='btn btn-primary js-btn-save-extallowed js-hide'><i class='fa fa-save'></i> Save</button>
					  		<strong class='text-success js-hide'><i class='fa fa-check'></i> saved</strong>
							</div>
						</div>
					</div>
			 	</div>
		 	</div>

		 	<h4>Prohibited File Extensions</h4>
			File extensions denied to upload - has to be an array, if set files with these extensions are not allowed to upload<br />Leave it empty for no denial restrictions
			<div class='form-horizontal'>
				<div class='form-group'>

					<label class='col-md-4 col-lg-3 col-sm-5 control-label col-xs-12'><small>&nbsp;</small><br />Denied File Extensions</label>
					<div class='col-sm-7 col-md-8 col-lg-9 col-xs-12'>
						<div class='row'>
							<div class='col-xs-9'><small class='text-muted'>file types/extensions seperated by comma <code>,</code></small><br />
								<input type='text' name='extDenied' class='form-control' value='<?php echo $extDenied; ?>' placeholder='file types/extensions seperated by comma, e.g. exe,bat,apk' />
							</div>
							<div class='col-xs-3 save-status-block'><small>&nbsp;</small><br />
					  		<button type='button' class='btn btn-primary js-btn-save-extdenied js-hide'><i class='fa fa-save'></i> Save</button>
					  		<strong class='text-success js-hide'><i class='fa fa-check'></i> saved</strong>
							</div>
						</div>
					</div>
			 	</div>
		 	</div>

		</div>
	</div>


	<div class='panel panel-default panel-sfs-settings'>
		<div class='panel-heading'><h3 class='panel-title'>Email Recipients<i class='fa fa-chevron-up fa-fw pull-right'></i></h3></div>
		<div class='panel-body'>

			Number of maximum possible email recipients for one sending process when sending download information<br /><br />

			<div class='form-horizontal'>
				<div class='form-group'>

					<label class='col-md-4 col-lg-3 col-sm-5 control-label col-xs-12'>Maximum Recipients</label>
					<div class='col-sm-7 col-md-5 col-lg-4 col-xs-12'>
						<div class='row'>
							<div class='col-xs-8'>
								<input name='maxRcpt' type='number' min='1' value='<?php echo $maxRcpt; ?>' class='form-control' />
							</div>
							<div class='col-xs-4 save-status-block'>
					  		<button type='button' class='btn btn-primary js-btn-save-maxrcpt js-hide'><i class='fa fa-save'></i> Save</button>
					  		<strong class='text-success js-hide'><i class='fa fa-check'></i> saved</strong>
							</div>
						</div>
					</div>
			 	</div>

			</div>
		</div>
	</div>


	<div class='panel panel-default panel-sfs-settings'>
		<div class='panel-heading'><h3 class='panel-title'>Image Previews<i class='fa fa-chevron-up fa-fw pull-right'></i></h3></div>
		<div class='panel-body'>

			If enabled and a image was uploaded a smaller version of this image will be displayed on the download page as preview<br />
			requires <code>GD Library</code><br /><br />

			<?php
				if ($SFS->config->imagePreview) {
					echo "<button type='button' class='btn btn-success js-btn-imagepreview'><i class='fa fa-fw fa-check-square-o'></i> Preview Images should be displayed</button>";
				} else {
					echo "<button type='button' class='btn btn-default js-btn-imagepreview'><i class='fa fa-fw fa-square-o'></i> Preview Images should be displayed</button>";
				}
			?>
			<div class='mt20 additional-image-dimensions-settings <?php echo !$SFS->config->imagePreview ? "js-hide" : ""; ?>'>
				<h4>Preview Image Dimensions</h4>

				<div class='form-horizontal'>
					<div class='form-group'>

						<label class='col-sm-2 col-lg-1 control-label col-xs-2'>Width</label>
						<div class='col-lg-2 col-md-3 col-sm-4 col-xs-4'>
							<div class="input-group">
								<input name='prevWidth' type='number' min='100' value='<?php echo $prevWidth; ?>' class='form-control' />
								<span class="input-group-addon">px</span>
							</div>
						</div>

						<label class='col-sm-2 col-lg-1 control-label col-xs-2'>Height</label>
						<div class='col-lg-2 col-md-3 col-sm-4 col-xs-4'>
							<div class="input-group">
								<input name='prevHeight' type='number' min='100' value='<?php echo $prevHeight; ?>' class='form-control' />
								<span class="input-group-addon">px</span>
							</div>
						</div>

						<div class='save-status-block col-lg-4 col-xs-12'>
				  		<button type='button' class='btn btn-primary js-btn-save-image-dimensions js-hide'><i class='fa fa-save'></i> Save</button>
				  		<strong class='text-success js-hide'><i class='fa fa-check'></i> saved</strong>
						</div>
				 	</div>
			 	</div>

			</div>
		</div>
	</div>





</form>

