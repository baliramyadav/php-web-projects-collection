<div class="content-wrapper">
    <section class="content-header">
        <h1><?=$this->lang->line('expense')?></h1>
        <ol class="breadcrumb">
            <li><a href="<?=base_url('dashboard/index')?>"><i class="fa fa-dashboard"></i><?=$this->lang->line('dashboard')?></a></li>
            <li class="active"><?=$this->lang->line('expense')?></li>
        </ol>
    </section>
    <section class="content">
        <div class="box box-mytheme">
            <?php if(permissionChecker('expense_add')) { ?>
            <div class="box-header">
                <a href="<?=base_url('expense/add')?>" class="btn btn-inline btn-mytheme btn-md"><i class="fa fa-plus"></i> <?=$this->lang->line('expense_add_expense')?></a>
            </div>
            <?php } ?>
            <div class="box-body">
                <div id="hide-table">
                    <table id="example1" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th><?=$this->lang->line('expense_slno')?></th>
                                <th><?=$this->lang->line('expense_name')?></th>
                                <th><?=$this->lang->line('expense_date')?></th>
                                <th><?=$this->lang->line('expense_amount')?></th>
                                <th><?=$this->lang->line('expense_file')?></th>
                                <th><?=$this->lang->line('expense_note')?></th>
                                <?php if(permissionChecker('expense_view') || permissionChecker('expense_edit') || permissionChecker('expense_delete')) { ?>
                                    <th><?=$this->lang->line('expense_action')?></th>
                                <?php } ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(calculate($expenses)) { $i=0; foreach($expenses as $expense) { $i++; ?>
                                <tr>
                                    <td data-title="<?=$this->lang->line('expense_slno')?>"><?=$i?></td>
                                    <td data-title="<?=$this->lang->line('expense_name')?>"><?=$expense->name?></td>
                                    <td data-title="<?=$this->lang->line('expense_date')?>"><?=app_date($expense->date)?></td>
                                    <td data-title="<?=$this->lang->line('expense_amount')?>"><?=number_format($expense->amount, 2)?></td>
                                    <td data-title="<?=$this->lang->line('expense_file')?>">
                                        <?php 
                                            if($expense->file != '') {
                                                echo btn_download('expense/download/'.$expense->expenseID, $expense->fileoriginalname);
                                            }
                                        ?>
                                    </td>
                                    <td data-title="<?=$this->lang->line('expense_note')?>"><?=$expense->note?></td>
                                    <?php if(permissionChecker('expense_view') || permissionChecker('expense_edit') || permissionChecker('expense_delete')) { ?>
                                        <td data-title="<?=$this->lang->line('expense_action')?>">
                                            <?=btn_view('expense/view/'.$expense->expenseID,$this->lang->line('expense_view')); ?>
                                            <?=btn_edit('expense/edit/'.$expense->expenseID,$this->lang->line('expense_edit')); ?>
                                            <?=btn_delete('expense/delete/'.$expense->expenseID,$this->lang->line('expense_delete')); ?>
                                        </td>
                                    <?php } ?>
                                </tr>
                            <?php } } ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <th><?=$this->lang->line('expense_slno')?></th>
                                <th><?=$this->lang->line('expense_name')?></th>
                                <th><?=$this->lang->line('expense_date')?></th>
                                <th><?=$this->lang->line('expense_amount')?></th>
                                <th><?=$this->lang->line('expense_file')?></th>
                                <th><?=$this->lang->line('expense_note')?></th>
                                <?php if(permissionChecker('expense_view') || permissionChecker('expense_edit') || permissionChecker('expense_delete')) { ?>
                                    <th><?=$this->lang->line('expense_action')?></th>
                                <?php } ?>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
          </div>
    </section>
</div>