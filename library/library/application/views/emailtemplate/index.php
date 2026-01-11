<div class="content-wrapper">
    <section class="content-header">
		<h1><?=$this->lang->line('email_template')?></h1>
		<ol class="breadcrumb">
			<li><a href="<?=base_url('dashboard/index')?>"><i class="fa fa-dashboard"></i><?=$this->lang->line('dashboard')?></a></li>
            <li class="active"><?=$this->lang->line('email_template')?></li>
		</ol>
    </section>
    <section class="content">
        <div class="box box-mytheme">
            <?php if(permissionChecker('emailtemplate_add')) { ?>
            <div class="box-header">
                <a href="<?=base_url('emailtemplate/add')?>" class="btn btn-inline btn-mytheme btn-md"><i class="fa fa-plus"></i> <?=$this->lang->line('emailtemplate_add_email_template')?></a>
            </div>
            <?php } ?>
            <div class="box-body">
                <div id="hide-table">
                    <table id="example1" class="table table-bordered table-striped">
                        <thead>
                        <tr>
                            <th><?=$this->lang->line('emailtemplate_slno')?></th>
                            <th><?=$this->lang->line('emailtemplate_name')?></th>
                            <th><?=$this->lang->line('emailtemplate_status')?></th>
                            <?php if(permissionChecker('emailtemplate_edit') || permissionChecker('emailtemplate_delete')) { ?>
                                <th><?=$this->lang->line('emailtemplate_action')?></th>
                            <?php } ?>
                        </tr>
                        </thead>
                        <tbody>
                            <?php if(calculate($emailtemplates)) { $i=0; foreach($emailtemplates as $emailtemplate) { $i++; ?>
                                <tr>
                                    <td data-title="<?=$this->lang->line('emailtemplate_slno')?>"><?=$i?></td>
                                    <td data-title="<?=$this->lang->line('emailtemplate_name')?>"><?=$emailtemplate->name?></td>
                                    <td data-title="<?=$this->lang->line('emailtemplate_status')?>"><?=status_button($emailtemplate->status)?></td>
                                    <?php if(permissionChecker('emailtemplate_edit') || permissionChecker('emailtemplate_delete')) { ?>
                                        <td data-title="<?=$this->lang->line('emailtemplate_action')?>">
                                            <?=btn_view('emailtemplate/view/'.$emailtemplate->emailtemplateID, $this->lang->line('view'))?>
                                            <?=btn_edit('emailtemplate/edit/'.$emailtemplate->emailtemplateID, $this->lang->line('edit'))?>
                                            <?=btn_delete('emailtemplate/delete/'.$emailtemplate->emailtemplateID, $this->lang->line('delete'))?>
                                        </td>
                                    <?php } ?>
                                </tr>
                            <?php } } ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <th><?=$this->lang->line('emailtemplate_slno')?></th>
                                <th><?=$this->lang->line('emailtemplate_name')?></th>
                                <th><?=$this->lang->line('emailtemplate_status')?></th>
                                <?php if(permissionChecker('emailtemplate_edit') || permissionChecker('emailtemplate_delete')) { ?>
                                    <th><?=$this->lang->line('emailtemplate_action')?></th>
                                <?php } ?>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </section>
</div>