<div class="content-wrapper">
    <section class="content-header">
  		<h1><?=$this->lang->line("email_setting")?></h1>
  		<ol class="breadcrumb">
        	<li><a href="<?=base_url('dashboard/dashboard')?>"><i class="fa fa-dashboard"></i><?=$this->lang->line("dashboard")?></a></li>
  			<li class="active"> <?=$this->lang->line("email_setting")?></li>
  		</ol>
    </section>
    <!-- Main content -->
    <section class="content">
		<div class="box box-mytheme">
			<div class="box-body">	
				<form method="post">
					<fieldset class="setting-fieldset">
		                <legend class="setting-legend"><?=$this->lang->line("emailsetting_email_setting")?></legend>
		                <div class="row">
		                    <div class="col-sm-4">
		                   		<div class="form-group <?=form_error('mail_driver') ? 'has-error' : ''?>">
		                            <label for="mail_driver">
		                                <?=$this->lang->line("emailsetting_mail_driver")?> <i class="fa fa-question-circle" data-toggle="tooltip" data-placement="top" title="Set your smtp mail driver"></i>
		                            </label>
		                            <input type="text" class="form-control" id="mail_driver" name="mail_driver" value="<?=set_value('mail_driver', $emailsetting->mail_driver)?>" />
		                            <?=form_error('mail_driver'); ?>
				                </div>
				            </div>
				            <div class="col-sm-4">
		                    	<div class="form-group <?=form_error('mail_host') ? 'has-error' : ''?>">
		                            <label for="mail_host">
		                                <?=$this->lang->line("emailsetting_mail_host")?> <i class="fa fa-question-circle" data-toggle="tooltip" data-placement="top" title="Set your smtp mail host"></i>
		                            </label>
		                            <input type="text" class="form-control" id="mail_host" name="mail_host" value="<?=set_value('mail_host', $emailsetting->mail_host)?>" />
		                            <?=form_error('mail_host'); ?>
				                </div>
		                    </div>
				            <div class="col-sm-4">
		                    	<div class="form-group <?=form_error('mail_port') ? 'has-error' : ''?>">
		                            <label for="mail_port">
		                                <?=$this->lang->line("emailsetting_mail_port")?> <i class="fa fa-question-circle" data-toggle="tooltip" data-placement="top" title="Set your smtp mail port"></i>
		                            </label>
		                            <input type="text" class="form-control" id="mail_port" name="mail_port" value="<?=set_value('mail_port', $emailsetting->mail_port)?>" />
		                            <?=form_error('mail_port'); ?>
				                </div>
		                    </div>
				            <div class="col-sm-4">
		                    	<div class="form-group <?=form_error('mail_username') ? 'has-error' : ''?>">
		                            <label for="mail_username">
		                                <?=$this->lang->line("emailsetting_mail_username")?> <i class="fa fa-question-circle" data-toggle="tooltip" data-placement="top" title="Set your smtp mail password"></i>
		                            </label>
		                            <input type="text" class="form-control" id="mail_username" name="mail_username" value="<?=set_value('mail_username', $emailsetting->mail_username)?>" />
		                            <?=form_error('mail_username'); ?>
				                </div>
		                    </div>
				            <div class="col-sm-4">
		                    	<div class="form-group <?=form_error('mail_password') ? 'has-error' : ''?>">
		                            <label for="mail_password">
		                                <?=$this->lang->line("emailsetting_mail_password")?> <i class="fa fa-question-circle" data-toggle="tooltip" data-placement="top" title="Set your smtp mail password"></i>
		                            </label>
		                            <input type="text" class="form-control" id="mail_password" name="mail_password" value="<?=set_value('mail_password', $emailsetting->mail_password)?>" />
		                            <?=form_error('mail_password'); ?>
				                </div>
		                    </div>
				            <div class="col-sm-4">
		                    	<div class="form-group <?=form_error('mail_encryption') ? 'has-error' : ''?>">
		                            <label for="mail_encryption">
		                                <?=$this->lang->line("emailsetting_mail_encryption")?> <i class="fa fa-question-circle" data-toggle="tooltip" data-placement="top" title="Set your smtp mail encryption"></i>
		                            </label>
		                            <input type="text" class="form-control" id="mail_encryption" name="mail_encryption" value="<?=set_value('mail_encryption', $emailsetting->mail_encryption)?>" />
		                            <?=form_error('mail_encryption'); ?>
				                </div>
		                    </div>
		                </div>
		            </fieldset>
		            <div class="row">
			            <div class="col-sm-12">
			            	<div class="form-group">
			                    <button class="btn btn-mytheme btn-md" type="submit"><?=$this->lang->line('emailsetting_update_email_setting')?></button>
			                </div>
			            </div>
		            </div>
		        </form>
			</div>
		</div>
    </section>
</div>