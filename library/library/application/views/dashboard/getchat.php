<?php 
if(calculate($chats)) { foreach($chats as $chat) { ?>
    <div class="item chatID" data-chatid="<?=$chat->chatID?>">
        <?php $memberimage = isset($members[$chat->create_memberID]) ? $members[$chat->create_memberID]->photo : ''?>
        <img src="<?=profile_img($memberimage)?>" alt="member image" class="offline">
        <p class="message">
            <a href="#" class="name">
                <small class="text-muted pull-right"><i class="fa fa-clock-o"></i> <?=date("d M Y H:i", strtotime($chat->create_date))?></small>
                <?=isset($members[$chat->create_memberID]) ? $members[$chat->create_memberID]->name : ""?>
            </a>
            <?=$chat->message?>
        </p>
    </div>
<?php } } ?>