<div class="content-wrapper">
    <section class="content-header">
		<h1><?=$this->lang->line('storebook')?></h1>
		<ol class="breadcrumb">
            <li><a href="<?=base_url('dashboard/index')?>"><i class="fa fa-dashboard"></i><?=$this->lang->line('dashboard')?></a></li>
			<li class="active"><?=$this->lang->line('storebook')?></li>
		</ol>
    </section>
    <section class="content">
        <div class="box box-mytheme">
            <?php if(permissionChecker('storebook_add')) { ?>
            <div class="box-header">
                <a href="<?=base_url('storebook/add')?>" class="btn btn-inline btn-mytheme btn-md"><i class="fa fa-plus"></i> <?=$this->lang->line('storebook_add_book')?></a>
            </div>
            <?php } ?>
            <div class="box-body">
                <div id="hide-table">
                    <table id="example1" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th><?=$this->lang->line('storebook_slno')?></th>
                                <th><?=$this->lang->line('storebook_cover_photo')?></th>
                                <th><?=$this->lang->line('storebook_name')?></th>
                                <th><?=$this->lang->line('storebook_author')?></th>
                                <th><?=$this->lang->line('storebook_quantity')?></th>
                                <th><?=$this->lang->line('storebook_code_no')?></th>
                                <?php if(permissionChecker('storebook_edit') || permissionChecker('storebook_delete')) { ?>
                                    <th><?=$this->lang->line('storebook_action')?></th>
                                <?php } ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(calculate($storebooks)) { $i=0; foreach($storebooks as $storebook) { $i++; ?>
                                <tr>
                                    <td data-title="<?=$this->lang->line('storebook_slno')?>"><?=$i?></td>
                                    <td data-title="<?=$this->lang->line('storebook_cover_photo')?>"><img src="<?=app_image_link($storebook->coverphoto,'uploads/storebook/','storebook.jpg')?>" class="profile_img" alt=""></td>
                                    <td data-title="<?=$this->lang->line('storebook_name')?>"><?=$storebook->name?></td>
                                    <td data-title="<?=$this->lang->line('storebook_author')?>"><?=$storebook->author?></td>
                                    <td data-title="<?=$this->lang->line('storebook_quantity')?>"><?=$storebook->quantity?></td>
                                    <td data-title="<?=$this->lang->line('storebook_code_no')?>"><?=$storebook->codeno?></td>
                                    <?php if(permissionChecker('storebook_edit') || permissionChecker('storebook_delete')) { ?>
                                        <td data-title="<?=$this->lang->line('storebook_action')?>">
                                            <?=btn_view('storebook/view/'.$storebook->storebookID,$this->lang->line('storebook_view')); ?>
                                            <?php if($storebook->deleted_at == 0) {
                                                echo btn_edit('storebook/edit/'.$storebook->storebookID,$this->lang->line('storebook_edit')). " ";
                                                echo btn_delete('storebook/delete/'.$storebook->storebookID,$this->lang->line('storebook_delete'));
                                            } ?>
                                        </td>
                                    <?php } ?>
                                </tr>
                            <?php } } ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <th><?=$this->lang->line('storebook_slno')?></th>
                                <th><?=$this->lang->line('storebook_cover_photo')?></th>
                                <th><?=$this->lang->line('storebook_name')?></th>
                                <th><?=$this->lang->line('storebook_author')?></th>
                                <th><?=$this->lang->line('storebook_quantity')?></th>
                                <th><?=$this->lang->line('storebook_code_no')?></th>
                                <?php if(permissionChecker('storebook_edit') || permissionChecker('storebook_delete')) { ?>    
                                    <th><?=$this->lang->line('storebook_action')?></th>
                                <?php } ?>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
          </div>
    </section>
</div>