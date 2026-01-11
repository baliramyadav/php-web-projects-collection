<div class="content-wrapper">
    <section class="content-header">
		<h1><?=$this->lang->line('income')?></h1>
		<ol class="breadcrumb">
            <li><a href="<?=base_url('dashboard/index')?>"><i class="fa fa-dashboard"></i><?=$this->lang->line('dashboard')?></a></li>
			<li class="active"><?=$this->lang->line('income')?></li>
		</ol>
    </section>
    <section class="content">
        <div class="box box-mytheme">
            <?php if(permissionChecker('income_add')) { ?>
            <div class="box-header">
                <a href="<?=base_url('income/add')?>" class="btn btn-inline btn-mytheme btn-md"><i class="fa fa-plus"></i> <?=$this->lang->line('income_add_income')?></a>
            </div>
            <?php } ?>
            <div class="box-body">
                <div id="hide-table">
                    <table id="example1" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th><?=$this->lang->line('income_slno')?></th>
                                <th><?=$this->lang->line('income_name')?></th>
                                <th><?=$this->lang->line('income_date')?></th>
                                <th><?=$this->lang->line('income_amount')?></th>
                                <th><?=$this->lang->line('income_file')?></th>
                                <th><?=$this->lang->line('income_note')?></th>
                                <?php if(permissionChecker('income_view') || permissionChecker('income_edit') || permissionChecker('income_delete')) { ?>
                                    <th><?=$this->lang->line('income_action')?></th>
                                <?php } ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(calculate($incomes)) { $i=0; foreach($incomes as $income) { $i++; ?>
                                <tr>
                                    <td data-title="<?=$this->lang->line('income_slno')?>"><?=$i?></td>
                                    <td data-title="<?=$this->lang->line('income_name')?>"><?=$income->name?></td>
                                    <td data-title="<?=$this->lang->line('income_date')?>"><?=app_date($income->date)?></td>
                                    <td data-title="<?=$this->lang->line('income_amount')?>"><?=number_format($income->amount, 2)?></td>
                                    <td data-title="<?=$this->lang->line('income_file')?>">
                                        <?php 
                                            if($income->file != '') {
                                                echo btn_download('income/download/'.$income->incomeID, $income->fileoriginalname);
                                            }
                                        ?>
                                    </td>
                                    <td data-title="<?=$this->lang->line('income_note')?>"><?=$income->note?></td>
                                    <?php if(permissionChecker('income_view') || permissionChecker('income_edit') || permissionChecker('income_delete')) { ?>
                                        <td data-title="<?=$this->lang->line('income_action')?>">
                                            <?=btn_view('income/view/'.$income->incomeID,$this->lang->line('income_view')); ?>
                                            <?=btn_edit('income/edit/'.$income->incomeID,$this->lang->line('income_edit')); ?>
                                            <?=btn_delete('income/delete/'.$income->incomeID,$this->lang->line('income_delete')); ?>
                                        </td>
                                    <?php } ?>
                                </tr>
                            <?php } } ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <th><?=$this->lang->line('income_slno')?></th>
                                <th><?=$this->lang->line('income_name')?></th>
                                <th><?=$this->lang->line('income_date')?></th>
                                <th><?=$this->lang->line('income_amount')?></th>
                                <th><?=$this->lang->line('income_file')?></th>
                                <th><?=$this->lang->line('income_note')?></th>
                                <?php if(permissionChecker('income_view') || permissionChecker('income_edit') || permissionChecker('income_delete')) { ?>
                                    <th><?=$this->lang->line('income_action')?></th>
                                <?php } ?>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
          </div>
    </section>
</div>