<div class="content-wrapper">
    <section class="content-header">
  		<h1><?=$this->lang->line('email_template')?></h1>
        <ol class="breadcrumb">
            <li><a href="<?=base_url('dashboard/index')?>"><i class="fa fa-dashboard"></i><?=$this->lang->line('dashboard')?></a></li>
            <li><a href="<?=base_url('emailtemplate/index')?>"><?=$this->lang->line('email_template')?></a></li>
            <li class="active"><?=$this->lang->line('add')?></li>
        </ol>
    </section>
    <section class="content">
        <div class="box box-mytheme">
            <div class="row">
                <div class="col-md-6">
                    <form role="form" method="POST" enctype="multipart/form-data">
                        <div class="box-body">
                            <div class="form-group <?=form_error('name') ? 'has-error' : ''?>">
                                <label for="name"><?=$this->lang->line('emailtemplate_name')?></label> <span class="text-red">*</span>
                                <input type="text" class="form-control" value="<?=set_value('name')?>" id="name" name="name"/>
                                <?=form_error('name')?>
                            </div>
                            <div class="form-group">
                                <label for="name"><?=$this->lang->line('emailtemplate_attribute')?></label><br/>
                                <div class="btn-group">
                                    <span class="btn btn-default single_email_tag" data-emailtag="[memberID]"><?=$this->lang->line('emailtemplate_memberID')?></span>
                                    <span class="btn btn-default single_email_tag" data-emailtag="[name]"><?=$this->lang->line('emailtemplate_name')?></span>
                                    <span class="btn btn-default single_email_tag" data-emailtag="[dateofbirth]"><?=$this->lang->line('emailtemplate_dateofbirth')?></span>
                                    <span class="btn btn-default single_email_tag" data-emailtag="[gender]"><?=$this->lang->line('emailtemplate_gender')?></span>
                                    <span class="btn btn-default single_email_tag" data-emailtag="[religion]"><?=$this->lang->line('emailtemplate_religion')?></span>
                                    <span class="btn btn-default single_email_tag" data-emailtag="[email]"><?=$this->lang->line('emailtemplate_email')?></span>
                                    <span class="btn btn-default single_email_tag" data-emailtag="[phone]"><?=$this->lang->line('emailtemplate_phone')?></span>
                                    <span class="btn btn-default single_email_tag" data-emailtag="[address]"><?=$this->lang->line('emailtemplate_address')?></span>
                                    <span class="btn btn-default single_email_tag" data-emailtag="[joinningdate]"><?=$this->lang->line('emailtemplate_joinning_date')?></span>
                                    <span class="btn btn-default single_email_tag" data-emailtag="[photo]"><?=$this->lang->line('emailtemplate_photo')?></span>
                                    <span class="btn btn-default single_email_tag" data-emailtag="[username]"><?=$this->lang->line('emailtemplate_username')?></span>
                                    <span class="btn btn-default single_email_tag" data-emailtag="[current_date]"><?=$this->lang->line('emailtemplate_current_date')?></span>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="template"><?=$this->lang->line('emailtemplate_template')?></label> <span class="text-red">*</span>
                                <textarea name="template" id="template" cols="30" class="form-control"><?=set_value('template')?></textarea>
                                <?=form_error('template','<p class="text-red">','</p>')?>
                            </div>
                            <div class="form-group <?=form_error('name') ? 'has-error' : ''?>">
                                <label for="status"><?=$this->lang->line('emailtemplate_status')?></label> <span class="text-red">*</span>
                                <?php 
                                    $statusArray['0'] = $this->lang->line('emailtemplate_please_select'); 
                                    $statusArray['1'] = $this->lang->line('emailtemplate_active');
                                    $statusArray['2'] = $this->lang->line('emailtemplate_disable');
                                    echo form_dropdown('status', $statusArray, set_value('status') , 'class="form-control"'); 
                                ?>
                                <?=form_error('status')?>
                            </div>
                        </div>
                        <div class="box-footer">
                            <button type="submit" class="btn btn-mytheme"><?=$this->lang->line('emailtemplate_add_email_template')?></button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
</div>