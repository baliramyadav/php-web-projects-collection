<div class="content-wrapper">
    <section class="content-header">
        <h1><?=$this->lang->line('dashboard')?></h1>
        <ol class="breadcrumb">
            <li><a href="<?=base_url('dashboard/index')?>"><i class="fa fa-dashboard"></i><?=$this->lang->line('dashboard')?></a></li>
            <li class="active"><?=$this->lang->line('dashboard')?></li>
        </ol>
    </section>
    <section class="content">

        <div class="box box-mytheme">
            <div class="row">
                <div class="col-sm-12">
                    <div class="box-body" style="width: 100%">
                        <canvas id="canvas" width="600" height="200"></canvas>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="row">
            <div class="col-lg-6">
                <div class="box box-mytheme">
                    <div class="box-header">
                        <i class="fa fa-comments-o"></i>
                        <h3 class="box-title"><?=$this->lang->line('dashboard_chat')?></h3>
                    </div>
                    <div class="box-body chat mainchatbox" id="chat-box">
                        <div class="text-center">
                            <button class="btn btn-xs btn-mytheme loadmore"><i class="fa fa-spinner fa-spin" aria-hidden="true"></i> <?=$this->lang->line('dashboard_load_more')?></button>
                        </div>
                        <div class="chatboxmessage">
                          <!-- chat item -->
                          <?php if(calculate($chats)) { foreach($chats as $chat) { ?>
                            <div class="item chatID" data-chatid="<?=$chat->chatID?>">
                                <?php $memberimage = isset($members[$chat->create_memberID]) ? $members[$chat->create_memberID]->photo : '';?>
                                <img src="<?=profile_img($memberimage)?>" alt="member image" class="offline">
                                <p class="message">
                                    <a href="#" class="name">
                                        <small class="text-muted pull-right"><i class="fa fa-clock-o"></i> <?=date('d M Y H:i', strtotime($chat->create_date))?></small>
                                        <?=isset($members[$chat->create_memberID]) ? $members[$chat->create_memberID]->name : ''?>
                                    </a>
                                    <?=$chat->message?>
                                </p>
                            </div>
                            <?php } } ?>
                            <!-- chat item -->
                        </div>
                    </div>
                    <!-- /.chat -->
                    <div class="box-footer">
                        <div class="input-group">
                            <input class="form-control" type="text" id="chatmessage" name="chatmessage" placeholder="<?=$this->lang->line('dashboard_type_message')?>">
                            <div class="input-group-btn">
                                <button type="button" class="btn btn-mytheme" id="chatmessagesend"><i class="fa fa-send"></i></button>
                                <button type="button" class="btn btn-danger" id="chatmessagerefresh"><i class="fa fa-refresh fa-spin"></i></button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="box box-mytheme">
                    <form action="<?=base_url('dashboard/quickmail')?>" method="post">
                        <div class="box-header">
                            <i class="fa fa-envelope"></i>
                            <h3 class="box-title"><?=$this->lang->line('dashboard_quick_email')?></h3>
                        </div>
                        <div class="box-body">
                            <div class="form-group">
                                <input type="email" class="form-control" name="email" placeholder="<?=$this->lang->line('dashboard_email')?>">
                            </div>
                            <div class="form-group">
                                <input type="text" class="form-control" name="subject" placeholder="<?=$this->lang->line('dashboard_subject')?>">
                            </div>
                            <div class="form-group">
                                <textarea class="form-control" name="message" id="message" cols="30" rows="10" placeholder="<?=$this->lang->line('dashboard_message')?>"></textarea>
                            </div>
                        </div>
                        <div class="box-footer clearfix">
                            <button type="submit" class="pull-right btn btn-default" id="sendEmail"> <?=$this->lang->line('dashboard_send')?> <i class="fa fa-arrow-circle-right"></i></button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
</div>

<script type="text/javascript">
    var dashboard_provide_message = "<?=$this->lang->line('dashboard_provide_message')?>";
    var dashboard_income          = [<?=$income?>];
    var dashboard_expense         = [<?=$expense?>];
</script>