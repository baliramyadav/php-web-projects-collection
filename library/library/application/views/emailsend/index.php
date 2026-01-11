<div class="content-wrapper">
    <section class="content-header">
		<h1><?=$this->lang->line('email_send')?></h1>
		<ol class="breadcrumb">
			<li><a href="<?=base_url('dashboard/index')?>"><i class="fa fa-dashboard"></i><?=$this->lang->line('dashboard')?></a></li>
            <li class="active"><?=$this->lang->line('email_send')?></li>
		</ol>
    </section>
    <section class="content">
        <div class="box box-mytheme">
            <?php if(permissionChecker('emailsend_add')) { ?>
            <div class="box-header">
                <a href="<?=base_url('emailsend/add')?>" class="btn btn-inline btn-mytheme btn-md"><i class="fa fa-plus"></i> <?=$this->lang->line('emailsend_add_email_send')?></a>
            </div>
            <?php } ?>
            <div class="box-body">
                <div id="hide-table">
                    <table id="example1" class="table table-bordered table-striped">
                        <thead>
                        <tr>
                            <th><?=$this->lang->line('emailsend_slno')?></th>
                            <th><?=$this->lang->line('emailsend_subject')?></th>
                            <th><?=$this->lang->line('emailsend_member_role')?></th>
                            <?php if(permissionChecker('emailsend_view') || permissionChecker('emailsend_delete')) { ?>
                                <th><?=$this->lang->line('emailsend_action')?></th>
                            <?php } ?>
                        </tr>
                        </thead>
                        <tbody>
                            <?php if(calculate($emailsends)) { $i=0; foreach($emailsends as $emailsend) { $i++; ?>
                                <tr>
                                    <td data-title="<?=$this->lang->line('emailsend_slno')?>"><?=$i?></td>
                                    <td data-title="<?=$this->lang->line('emailsend_subject')?>"><?=$emailsend->subject?></td>
                                    <td data-title="<?=$this->lang->line('emailsend_member_role')?>"><?=isset($roles[$emailsend->sender_roleID]) ? $roles[$emailsend->sender_roleID] : $this->lang->line('emailsend_guest') ?></td>
                                    <?php if(permissionChecker('emailsend_view') || permissionChecker('emailsend_delete')) { ?>
                                        <td data-title="<?=$this->lang->line('emailsend_action')?>">
                                            <?=btn_view('emailsend/view/'.$emailsend->emailsendID, $this->lang->line('emailsend_view'))?>
                                            <?=btn_delete('emailsend/delete/'.$emailsend->emailsendID, $this->lang->line('emailsend_delete'))?>
                                        </td>
                                    <?php } ?>
                                </tr>
                            <?php } } ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <th><?=$this->lang->line('emailsend_slno')?></th>
                                <th><?=$this->lang->line('emailsend_subject')?></th>
                                <th><?=$this->lang->line('emailsend_member_role')?></th>
                                <?php if(permissionChecker('emailsend_view') || permissionChecker('emailsend_delete')) { ?>
                                    <th><?=$this->lang->line('emailsend_action')?></th>
                                <?php } ?>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </section>
</div>