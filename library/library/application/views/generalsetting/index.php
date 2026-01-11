<div class="content-wrapper">
    <section class="content-header">
  		<h1><?=$this->lang->line('general_setting')?></h1>
  		<ol class="breadcrumb">
        	<li><a href="<?=base_url('dashboard/index')?>"><i class="fa fa-dashboard"></i> <?=$this->lang->line('dashboard')?></a></li>
  			<li class="active"><?=$this->lang->line('general_setting')?></li>
  		</ol>
    </section>
    <section class="content">
		<div class="box box-mytheme">
			<div class="box-body">
				<form action="<?=base_url('generalsetting/index')?>" method="POST" enctype="multipart/form-data">
					<fieldset class="setting-fieldset">
		                <legend class="setting-legend"><?=$this->lang->line('generalsetting_general_setting')?></legend>
		                <div class="row">
		                    <div class="col-sm-6 sitename">
		                   		<div class="form-group <?=form_error('sitename') ? 'has-error' : ''?>">
		                            <label for="sitename">
		                                <?=$this->lang->line("generalsetting_sitename")?> <i class="fa fa-question-circle" data-toggle="tooltip" data-placement="top" title="Set your sitename"></i>
		                            </label>
		                            <input type="text" class="form-control" id="sitename" name="sitename" value="<?=set_value('sitename', $generalsetting->sitename)?>" />
		                            <?=form_error('sitename'); ?>
				                </div>
				            </div>
		                    <div class="col-sm-6 logo">
				                <div class="form-group <?=form_error('logo') ? 'has-error' : ''?>">
							        <label for="logo">
		                                <?=$this->lang->line("generalsetting_logo")?> <i class="fa fa-question-circle" data-toggle="tooltip" data-placement="top" title="Set your logo"></i>
		                            </label>
							        <div class="input-group image-preview">
							            <input type="text" class="form-control fileuploadname" value="<?=set_value('logo', $generalsetting->logo)?>" disabled="disabled" />
							            <span class="input-group-btn">
							                <div class="btn btn-success image-preview-input">
							                    <span class="fa fa-repeat"></span>
							                    <span class="image-preview-input-title"><?=$this->lang->line('generalsetting_filebrowse')?></span>
							                    <input type="file" name="logo" id="fileupload"/>
							                </div>
							            </span>
							        </div>
							      	<?=form_error('logo');?>
							    </div>
				            </div>
		                    <div class="col-sm-6 address">
		                   		<div class="form-group <?=form_error('address') ? 'has-error' : ''?>">
		                            <label for="address">
		                                <?=$this->lang->line("generalsetting_address")?> <i class="fa fa-question-circle" data-toggle="tooltip" data-placement="top" title="Set your address"></i>
		                            </label>
		                            <textarea class="form-control" name="address" id="address" cols="30" rows="2"><?=set_value('address', $generalsetting->address)?></textarea>
		                            <?=form_error('address'); ?>
				                </div>
				            </div>
		                    <div class="col-sm-6 copyright_by">
		                   		<div class="form-group <?=form_error('copyright_by') ? 'has-error' : ''?>">
		                            <label for="copyright_by">
		                                <?=$this->lang->line("generalsetting_copyright_by")?> <i class="fa fa-question-circle" data-toggle="tooltip" data-placement="top" title="Set your copyright_by"></i>
		                            </label>
		                            <textarea class="form-control" name="copyright_by" id="copyright_by" cols="30" rows="2"><?=set_value('copyright_by', $generalsetting->copyright_by)?></textarea>
		                            <?=form_error('copyright_by'); ?>
				                </div>
				            </div>
		                	<div class="col-sm-6 email">
		                   		<div class="form-group <?=form_error('email') ? 'has-error' : ''?>">
		                            <label for="email">
		                                <?=$this->lang->line("generalsetting_email")?> <i class="fa fa-question-circle" data-toggle="tooltip" data-placement="top" title="Set your email"></i>
		                            </label>
		                            <input type="text" class="form-control" id="email" name="email" value="<?=set_value('email', $generalsetting->email)?>" />
		                            <?=form_error('email'); ?>
				                </div>
				            </div>
		                	<div class="col-sm-6 phone">
		                   		<div class="form-group <?=form_error('phone') ? 'has-error' : ''?>">
		                            <label for="phone">
		                                <?=$this->lang->line("generalsetting_phone")?> <i class="fa fa-question-circle" data-toggle="tooltip" data-placement="top" title="Set your phone"></i>
		                            </label>
		                            <input type="text" class="form-control" id="phone" name="phone" value="<?=set_value('phone', $generalsetting->phone)?>" />
		                            <?=form_error('phone'); ?>
				                </div>
				            </div>
		                	<div class="col-sm-6 web_address">
		                   		<div class="form-group <?=form_error('web_address') ? 'has-error' : ''?>">
		                            <label for="web_address">
		                                <?=$this->lang->line("generalsetting_web_address")?> <i class="fa fa-question-circle" data-toggle="tooltip" data-placement="top" title="Set your web address"></i>
		                            </label>
		                            <input type="text" class="form-control" id="web_address" name="web_address" value="<?=set_value('web_address', $generalsetting->web_address)?>" />
		                            <?=form_error('web_address'); ?>
				                </div>
				            </div>
		                </div>
		            </fieldset>

		            <fieldset class="setting-fieldset">
		                <legend class="setting-legend"><?=$this->lang->line('generalsetting_frontend_setting')?></legend>
		                <div class="row">
		                    <div class="col-sm-6 ebook_download">
		                   		<div class="form-group <?=form_error('ebook_download') ? 'has-error' : ''?>">
								  	<label><?=$this->lang->line('generalsetting_ebook_download')?></label> <span class="text-red">*</span>
								  	<?php 
										$ebookDownloadArray[1] = $this->lang->line('generalsetting_enable');
										$ebookDownloadArray[0] = $this->lang->line('generalsetting_disable');

										echo form_dropdown('ebook_download', $ebookDownloadArray, set_value('ebook_download', $generalsetting->ebook_download),'id="ebook_download" class="form-control"');
									?>
								  	<?=form_error('ebook_download')?>
								</div>
							</div>
		                    <div class="col-sm-6 registration">
		                   		<div class="form-group <?=form_error('registration') ? 'has-error' : ''?>">
								  	<label><?=$this->lang->line('generalsetting_registration')?></label> <span class="text-red">*</span>
								  	<?php 
										$registrationArray[1] = $this->lang->line('generalsetting_enable');
										$registrationArray[0] = $this->lang->line('generalsetting_disable');

										echo form_dropdown('registration', $registrationArray, set_value('registration', $generalsetting->registration),'id="registration" class="form-control"');
									?>
								  	<?=form_error('registration')?>
								</div>
							</div>
		                    <div class="col-sm-6 frontend">
		                   		<div class="form-group <?=form_error('frontend') ? 'has-error' : ''?>">
								  	<label><?=$this->lang->line('generalsetting_frontend')?></label> <span class="text-red">*</span>
								  	<?php 
										$frontendArray[1] = $this->lang->line('generalsetting_enable');
										$frontendArray[0] = $this->lang->line('generalsetting_disable');

										echo form_dropdown('frontend', $frontendArray, set_value('frontend', $generalsetting->frontend),'id="frontend" class="form-control"');
									?>
								  	<?=form_error('frontend')?>
								</div>
							</div>
		                    <div class="col-sm-6 delivery_charge">
		                   		<div class="form-group <?=form_error('delivery_charge') ? 'has-error' : ''?>">
								  	<label><?=$this->lang->line('generalsetting_delivery_charge')?></label> <span class="text-red">*</span>
								  	<input type="text" class="form-control" id="delivery_charge" name="delivery_charge" value="<?=set_value('delivery_charge', $generalsetting->delivery_charge)?>" />
								  	<?=form_error('delivery_charge')?>
								</div>
							</div>
		                    
		                </div>
		            </fieldset>

		            <div class="row">
			            <div class="col-sm-12">
			            	<div class="form-group">
			                    <input type="submit" class="btn btn-mytheme btn-md" value="<?=$this->lang->line('generalsetting_update_setting')?>">
			                </div>
			            </div>
		            </div>
	        	</form>
			</div>
		</div>
    </section>
</div>