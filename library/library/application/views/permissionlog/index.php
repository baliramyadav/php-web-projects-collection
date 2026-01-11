<div class="content-wrapper">
    <section class="content-header">
		<h1><?=$this->lang->line('permissionlog')?></h1>
		<ol class="breadcrumb">
            <li><a href="<?=base_url('dashboard/index')?>"><i class="fa fa-dashboard"></i><?=$this->lang->line('dashboard')?></a></li>
			<li class="active"><?=$this->lang->line('permissionlog')?></li>
		</ol>
    </section>
    <section class="content">
        <div class="box box-mytheme">
            <?php if(permissionChecker('permissionlog_add')) { ?>
            <div class="box-header">
                <a href="<?=base_url('permissionlog/add')?>" class="btn btn-inline btn-mytheme btn-md"><i class="fa fa-plus"></i> <?=$this->lang->line('permissionlog_add_permissionlog')?></a>
            </div>
            <?php } ?>
            <div class="box-body">
                <div id="hide-table">
                    <table id="example1" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th><?=$this->lang->line('permissionlog_slno')?></th>
                                <th><?=$this->lang->line('permissionlog_name')?></th>
                                <th><?=$this->lang->line('permissionlog_description')?></th>
                                <th><?=$this->lang->line('permissionlog_status')?></th>
                                <?php if(permissionChecker('permissionlog_edit') || permissionChecker('permissionlog_delete')) { ?>
                                    <th><?=$this->lang->line('permissionlog_action')?></th>
                                <?php } ?>
                            </tr>
                        </thead>
                        <tbody>
                          <?php $i=0; if(calculate($permissionlogs)) { foreach($permissionlogs as $permissionlog) { $i++; ?>
                            <tr>
                                <td data-title="<?=$this->lang->line('permissionlog_slno')?>"><?=$i?></td>
                                <td data-title="<?=$this->lang->line('permissionlog_name')?>"><?=$permissionlog->name?></td>
                                <td data-title="<?=$this->lang->line('permissionlog_description')?>"><?=$permissionlog->description?></td>
                                <td data-title="<?=$this->lang->line('permissionlog_status')?>"><?=$permissionlog->active?></td>
                                <?php if(permissionChecker('permissionlog_edit') || permissionChecker('permissionlog_delete')) { ?>
                                    <td data-title="<?=$this->lang->line('permissionlog_action')?>">
                                        <?=btn_edit('permissionlog/edit/'.$permissionlog->permissionlogID, $this->lang->line('permissionlog_edit'))?>
                                        <?=btn_delete('permissionlog/delete/'.$permissionlog->permissionlogID, $this->lang->line('permissionlog_delete'))?>
                                    </td>
                                <?php } ?>
                            </tr>
                          <?php } } ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <th><?=$this->lang->line('permissionlog_slno')?></th>
                                <th><?=$this->lang->line('permissionlog_name')?></th>
                                <th><?=$this->lang->line('permissionlog_description')?></th>
                                <th><?=$this->lang->line('permissionlog_status')?></th>
                                <?php if(permissionChecker('permissionlog_edit') || permissionChecker('permissionlog_delete')) { ?>
                                    <th><?=$this->lang->line('permissionlog_action')?></th>
                                <?php } ?>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </section>
</div>