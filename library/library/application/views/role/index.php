<div class="content-wrapper">
    <section class="content-header">
		<h1><?=$this->lang->line('role')?></h1>
		<ol class="breadcrumb">
			<li><a href="<?=base_url('dashboard/index')?>"><i class="fa fa-dashboard"></i><?=$this->lang->line('dashboard')?></a></li>
            <li class="active"><?=$this->lang->line('role')?></li>
		</ol>
    </section>
    <section class="content">
        <div class="box box-mytheme">
            <?php if(permissionChecker('role_add')) { ?>
            <div class="box-header">
                <a href="<?=base_url('role/add')?>" class="btn btn-inline btn-mytheme btn-md"><i class="fa fa-plus"></i> <?=$this->lang->line('role_add_role')?></a>
            </div>
            <?php } ?>
            <div class="box-body">
                <div id="hide-table">
                    <table id="example1" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th><?=$this->lang->line('role_slno')?></th>
                                <th><?=$this->lang->line('role_role')?></th>
                                <th><?=$this->lang->line('role_create_date')?></th>
                                <?php if(permissionChecker('role_edit') || permissionChecker('role_delete')) { ?>
                                    <th><?=$this->lang->line('role_action')?></th>
                                <?php } ?>
                            </tr>
                        </thead>
                        <tbody>
                        <?php if(calculate($roles)) { $i=0; foreach($roles as $role) { $i++; ?>
                            <tr>
                                <td data-title="<?=$this->lang->line('role_slno')?>"><?=$i?></td>
                                <td data-title="<?=$this->lang->line('role_role')?>"><?=$role->role?></td>
                                <td data-title="<?=$this->lang->line('role_create_date')?>"><?=app_date($role->create_date)?></td>
                                <?php if(permissionChecker('role_edit') || permissionChecker('role_delete')) { ?>
                                    <td data-title="<?=$this->lang->line('role_action')?>">
                                        <?=btn_edit('role/edit/'.$role->roleID, $this->lang->line('role_edit'))?>
                                        <?=btn_delete('role/delete/'.$role->roleID, $this->lang->line('role_delete'))?>
                                    </td>
                                <?php } ?>
                            </tr>
                        <?php } } ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <th><?=$this->lang->line('role_slno')?></th>
                                <th><?=$this->lang->line('role_role')?></th>
                                <th><?=$this->lang->line('role_create_date')?></th>
                                <?php if(permissionChecker('role_edit') || permissionChecker('role_delete')) { ?>
                                    <th><?=$this->lang->line('role_action')?></th>
                                <?php } ?>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </section>
</div>