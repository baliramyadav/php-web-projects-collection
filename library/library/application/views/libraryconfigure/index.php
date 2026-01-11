<div class="content-wrapper">
    <section class="content-header">
		<h1><?=$this->lang->line('libraryconfigure')?></h1>
		<ol class="breadcrumb">
			<li><a href="<?=base_url('dashboard/index')?>"><i class="fa fa-dashboard"></i><?=$this->lang->line('dashboard')?></a></li>
            <li class="active"><?=$this->lang->line('libraryconfigure')?></li>
		</ol>
    </section>
    <section class="content">
        <div class="box box-mytheme">
            <?php if(permissionChecker('libraryconfigure_add')) { ?>
            <div class="box-header">
                <a href="<?=base_url('libraryconfigure/add')?>" class="btn btn-inline btn-mytheme btn-md"><i class="fa fa-plus"></i>  <?=$this->lang->line('libraryconfigure_add_libraryconfigure')?></a>
            </div>
            <?php } ?>
            <div class="box-body">
                <div id="hide-table">
                    <table id="example1" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th><?=$this->lang->line('libraryconfigure_slno')?></th>
                                <th><?=$this->lang->line('libraryconfigure_role')?></th>
                                <th><?=$this->lang->line('libraryconfigure_max_issue_book')?></th>
                                <th><?=$this->lang->line('libraryconfigure_max_renewed_limit')?></th>
                                <th><?=$this->lang->line('libraryconfigure_per_renew_limit_day')?></th>
                                <th><?=$this->lang->line('libraryconfigure_book_fine_per_day')?></th>
                                <th><?=$this->lang->line('libraryconfigure_issue_off_limit_amount')?></th>
                                <?php if(permissionChecker('libraryconfigure_edit') || permissionChecker('libraryconfigure_delete')) { ?>
                                    <th><?=$this->lang->line('libraryconfigure_action')?></th>
                                <?php } ?>
                            </tr>
                        </thead>
                        <tbody>
                        <?php if(calculate($libraryconfigures)) { $i=0; foreach($libraryconfigures as $libraryconfigure) { $i++; ?>
                            <tr>
                                <td data-title="<?=$this->lang->line('libraryconfigure_slno')?>"><?=$i?></td>
                                <td data-title="<?=$this->lang->line('libraryconfigure_role')?>"><?=isset($roles[$libraryconfigure->roleID]) ? $roles[$libraryconfigure->roleID] : '&nbsp;' ?></td>
                                <td data-title="<?=$this->lang->line('libraryconfigure_max_issue_book')?>"><?=$libraryconfigure->max_issue_book?></td>
                                <td data-title="<?=$this->lang->line('libraryconfigure_max_renewed_limit')?>"><?=$libraryconfigure->max_renewed_limit?></td>
                                <td data-title="<?=$this->lang->line('libraryconfigure_per_renew_limit_day')?>"><?=$libraryconfigure->per_renew_limit_day?></td>
                                <td data-title="<?=$this->lang->line('libraryconfigure_book_fine_per_day')?>"><?=$libraryconfigure->book_fine_per_day?></td>
                                <td data-title="<?=$this->lang->line('libraryconfigure_issue_off_limit_amount')?>"><?=$libraryconfigure->issue_off_limit_amount?></td>
                                <?php if(permissionChecker('libraryconfigure_edit') || permissionChecker('libraryconfigure_delete')) { ?>
                                    <td data-title="<?=$this->lang->line('libraryconfigure_action')?>">
                                        <?=btn_edit('libraryconfigure/edit/'.$libraryconfigure->libraryconfigureID, $this->lang->line('libraryconfigure_edit'))?>
                                        <?=btn_delete('libraryconfigure/delete/'.$libraryconfigure->libraryconfigureID, $this->lang->line('libraryconfigure_delete'))?>
                                    </td>
                                <?php } ?>
                            </tr>
                        <?php } } ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <th><?=$this->lang->line('libraryconfigure_slno')?></th>
                                <th><?=$this->lang->line('libraryconfigure_role')?></th>
                                <th><?=$this->lang->line('libraryconfigure_max_issue_book')?></th>
                                <th><?=$this->lang->line('libraryconfigure_max_renewed_limit')?></th>
                                <th><?=$this->lang->line('libraryconfigure_per_renew_limit_day')?></th>
                                <th><?=$this->lang->line('libraryconfigure_book_fine_per_day')?></th>
                                <th><?=$this->lang->line('libraryconfigure_issue_off_limit_amount')?></th>
                                <?php if(permissionChecker('libraryconfigure_edit') || permissionChecker('libraryconfigure_delete')) { ?>
                                    <th><?=$this->lang->line('libraryconfigure_action')?></th>
                                <?php } ?>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </section>
</div>