<div class="content-wrapper">
    <section class="content-header">
        <h1><?=$this->lang->line('email_send')?></h1>
        <ol class="breadcrumb">
            <li><a href="<?=base_url('dashboard/index')?>"><i class="fa fa-dashboard"></i><?=$this->lang->line('dashboard')?></a></li>
            <li><a href="<?=base_url('emailsend/index')?>"><?=$this->lang->line('email_send')?></a></li>
            <li class="active"><?=$this->lang->line('view')?></li>
        </ol>
    </section>
    <section class="content">
        <div class="box box-mytheme">
            <div class="box-body">
                <div class="row" style="padding-top: 0px;">
                    <div class="col-sm-12">
                        <div class="panel-body profile_view_des">
                            <div class="row">
                                <div class="profile_view_item">
                                    <p><b><?=$this->lang->line('emailsend_subject')?></b>: <?=$emailsend->subject?></p>
                                </div>
                                <?php if(isset($emailtemplates[$emailsend->emailtemplateID])) { ?>
                                    <div class="profile_view_item">
                                        <p><b><?=$this->lang->line('emailsend_email_template')?></b>: <?=$emailtemplates[$emailsend->emailtemplateID]?></p>
                                    </div>
                                <?php } ?>
                                <div class="profile_view_item">
                                    <p><b><?=$this->lang->line('emailsend_message')?></b>: <?=$emailsend->message?></p>
                                </div>
                                <div class="profile_view_item">
                                    <p><b><?=$this->lang->line('emailsend_sender_name')?></b>: <?=$emailsend->sender_name?></p>
                                </div>
                                <div class="profile_view_item">
                                    <p><b><?=$this->lang->line('emailsend_email')?></b>: <?=$emailsend->email?></p>
                                </div>
                                <div class="profile_view_item">
                                    <p><b><?=$this->lang->line('emailsend_create_date')?></b>: <?=app_date($emailsend->create_date)?></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
