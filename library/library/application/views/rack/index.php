<div class="content-wrapper">
    <section class="content-header">
		<h1><?=$this->lang->line('rack')?></h1>
		<ol class="breadcrumb">
            <li><a href="<?=base_url('dashboard/index')?>"><i class="fa fa-dashboard"></i><?=$this->lang->line('dashboard')?></a></li>
			<li class="active"><?=$this->lang->line('rack')?></li>
		</ol>
    </section>
    <section class="content">
        <div class="box box-mytheme">
            <?php if(permissionChecker('rack_add')) { ?>
            <div class="box-header">
                <a href="<?=base_url('rack/add')?>" class="btn btn-inline btn-mytheme btn-md"><i class="fa fa-plus"></i> <?=$this->lang->line('rack_add_rack')?></a>
            </div>
            <?php } ?>
            <div class="box-body">
                <div id="hide-table">
                    <table id="example1" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th><?=$this->lang->line('rack_slno')?></th>
                                <th><?=$this->lang->line('rack_name')?></th>
                                <th><?=$this->lang->line('rack_description')?></th>
                                <?php if(permissionChecker('rack_edit') || permissionChecker('rack_delete')) { ?>
                                    <th><?=$this->lang->line('rack_action')?></th>
                                <?php } ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(calculate($racks)) { $i=0; foreach($racks as $rack) { $i++; ?>
                                <tr>
                                    <td data-title="<?=$this->lang->line('rack_slno')?>"><?=$i?></td>
                                    <td data-title="<?=$this->lang->line('rack_name')?>"><?=$rack->name?></td>
                                    <td data-title="<?=$this->lang->line('rack_description')?>"><?=$rack->description?></td>
                                    <?php if(permissionChecker('rack_edit') || permissionChecker('rack_delete')) { ?>
                                        <td data-title="<?=$this->lang->line('rack_action')?>">
                                            <?=btn_edit('rack/edit/'.$rack->rackID,$this->lang->line('rack_edit')); ?>
                                            <?=btn_delete('rack/delete/'.$rack->rackID,$this->lang->line('rack_delete')); ?>
                                        </td>
                                    <?php } ?>
                                </tr>
                            <?php } } ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <th><?=$this->lang->line('rack_slno')?></th>
                                <th><?=$this->lang->line('rack_name')?></th>
                                <th><?=$this->lang->line('rack_description')?></th>
                                <?php if(permissionChecker('rack_edit') || permissionChecker('rack_delete')) { ?>    
                                    <th><?=$this->lang->line('rack_action')?></th>
                                <?php } ?>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
          </div>
    </section>
</div>