<div class="content-wrapper">
    <section class="content-header">
  		<h1><?=$this->lang->line('email_send')?></h1>
  		<ol class="breadcrumb">
            <li><a href="<?=base_url('dashboard/index')?>"><i class="fa fa-dashboard"></i><?=$this->lang->line('dashboard')?></a></li>
  			<li><a href="<?=base_url('emailsend/index')?>"> <?=$this->lang->line('email_send')?></a></li>
  			<li class="active"><?=$this->lang->line('send_email')?></li>
  		</ol>
    </section>
    <section class="content">
        <div class="box box-mytheme">
            <div class="nav-tabs-custom">
                <ul class="nav nav-tabs">
                    <li class="<?=($active_tab==1) ? 'active' : ''?>"><a href="#email" data-toggle="tab" aria-expanded="false"><?=$this->lang->line('emailsend_email')?></a></li>
                    <li class="<?=($active_tab==2) ? 'active' : ''?>"><a href="#otheremail" data-toggle="tab" aria-expanded="false"><?=$this->lang->line('emailsend_otheremail')?></a></li>
                </ul>
                <div class="tab-content">
                    <div class="tab-pane <?=($active_tab==1) ? 'active' : ''?>" id="email">
                        <div class="row"> 
                            <div class="col-md-6">
                                <form role="form" method="POST" enctype="multipart/form-data">
                                    <input type="hidden" name="emailtype" value="1">
                                    <div class="box-body">
                                        <div class="form-group <?=form_error('subject') ? 'has-error' : ''?>">
                                            <label for="subject"><?=$this->lang->line('emailsend_subject')?></label> <span class="text-red">*</span>
                                            <input type="text" class="form-control" value="<?=set_value('subject')?>" id="subject" name="subject"/>
                                            <?=form_error('subject')?>
                                        </div>
                                        <div class="form-group <?=form_error('sender_roleID') ? 'has-error' : ''?>">
                                            <label for="sender_roleID"><?=$this->lang->line('emailsend_sender_role')?></label> <span class="text-red">*</span>
                                            <?php 
                                                $sender_roleArray['0'] = $this->lang->line('emailsend_please_select');
                                                if(calculate($roles)) {
                                                    foreach($roles as $role) {
                                                        $sender_roleArray[$role->roleID] = $role->role;
                                                    }
                                                }
                                                echo form_dropdown('sender_roleID', $sender_roleArray, set_value('sender_roleID') , 'class="form-control" id="sender_roleID"'); 
                                            ?>
                                            <?=form_error('sender_roleID')?>
                                        </div>
                                        <div class="form-group <?=form_error('sender_memberID') ? 'has-error' : ''?>">
                                            <label for="sender_memberID"><?=$this->lang->line('emailsend_sender_name')?></label>
                                            <?php 
                                                $sender_memberArray['0'] = $this->lang->line('emailsend_all_member'); 
                                                echo form_multiselect('sender_memberID[]', $sender_memberArray, set_value('sender_memberID[]') , 'class="form-control select2" id="sender_memberID"'); 
                                            ?>
                                            <?=form_error('sender_memberID')?>
                                        </div>
                                        <div class="form-group <?=form_error('emailtemplateID') ? 'has-error' : ''?>">
                                            <label for="emailtemplateID"><?=$this->lang->line('emailsend_emailtemplate')?></label>
                                            <?php 
                                                $emailtemplateArray['0'] = $this->lang->line('emailsend_please_select');
                                                if(calculate($emailtemplates)) {
                                                    foreach ($emailtemplates as $emailtemplate) {
                                                        $emailtemplateArray[$emailtemplate->emailtemplateID] = $emailtemplate->name;
                                                    }
                                                }
                                                echo form_dropdown('emailtemplateID', $emailtemplateArray, set_value('emailtemplateID') , 'class="form-control" id="emailtemplateID"'); 
                                            ?>
                                            <?=form_error('emailtemplateID')?>
                                        </div>
                                        <div class="form-group <?=form_error('message') ? 'has-error' : ''?>">
                                            <label for="message"><?=$this->lang->line('emailsend_message')?></label> <span class="text-red">*</span>
                                            <textarea name="message" id="message" cols="30" rows="10" class="form-control"><?=set_value('message')?></textarea>
                                            <?=form_error('message')?>
                                        </div>
                                    </div>
                                    <div class="box-footer">
                                        <button type="submit" class="btn btn-mytheme"><?=$this->lang->line('emailsend_send_email')?></button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    
                    <div class="tab-pane <?=($active_tab==2) ? 'active' : ''?>" id="otheremail">
                        <div class="row"> 
                            <div class="col-md-6">
                                <form role="form" method="POST" enctype="multipart/form-data">
                                    <input type="hidden" name="emailtype" value="2">
                                    <div class="box-body">
                                        <div class="form-group <?=form_error('othersubject') ? 'has-error' : ''?>">
                                            <label for="othersubject"><?=$this->lang->line('emailsend_othersubject')?></label> <span class="text-red">*</span>
                                            <input type="text" class="form-control" value="<?=set_value('othersubject')?>" id="othersubject" name="othersubject"/>
                                            <?=form_error('othersubject')?>
                                        </div>
                                        <div class="form-group <?=form_error('name') ? 'has-error' : ''?>">
                                            <label for="name"><?=$this->lang->line('emailsend_name')?></label> <span class="text-red">*</span>
                                            <input type="text" class="form-control" value="<?=set_value('name')?>" id="name" name="name"/>
                                            <?=form_error('name')?>
                                        </div>
                                        <div class="form-group <?=form_error('email') ? 'has-error' : ''?>">
                                            <label for="email"><?=$this->lang->line('emailsend_email')?></label> <span class="text-red">*</span>
                                            <input type="text" class="form-control" value="<?=set_value('email')?>" id="email" name="email"/>
                                            <?=form_error('email')?>
                                        </div>
                                        <div class="form-group">
                                            <label for="othermessage"><?=$this->lang->line('emailsend_othermessage')?></label> <span class="text-red">*</span>
                                            <textarea name="othermessage" id="othermessage" cols="30" rows="5" class="form-control"><?=set_value('othermessage')?></textarea>
                                            <span class="<?=form_error('othermessage') ? 'has-error' : ''?>"><?=form_error('othermessage')?></span>
                                        </div>
                                    </div>
                                    <div class="box-footer">
                                        <button type="submit" class="btn btn-mytheme"><?=$this->lang->line('emailsend_send_email')?></button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<script type="text/javascript">
    var set_sender_roleID = "<?=set_value('sender_roleID')?>";
</script>