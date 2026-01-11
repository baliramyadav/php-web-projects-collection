<div class="content-wrapper">
    <section class="content-header">
		<h1><?=$this->lang->line('bookcategory')?></h1>
		<ol class="breadcrumb">
            <li><a href="<?=base_url('dashboard/index')?>"><i class="fa fa-dashboard"></i><?=$this->lang->line('dashboard')?></a></li>
			<li class="active"><?=$this->lang->line('bookcategory')?></li>
		</ol>
    </section>
    <section class="content">
        <div class="box box-mytheme">
            <?php if(permissionChecker('bookcategory_add')) { ?>
            <div class="box-header">
                <a href="<?=base_url('bookcategory/add')?>" class="btn btn-inline btn-mytheme btn-md"><i class="fa fa-plus"></i> <?=$this->lang->line('bookcategory_add_book_category')?></a>
            </div>
            <?php } ?>
            <div class="box-body">
                <div id="hide-table">
                    <table id="example1" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th><?=$this->lang->line('bookcategory_slno')?></th>
                                <th><?=$this->lang->line('bookcategory_cover_photo')?></th>
                                <th><?=$this->lang->line('bookcategory_name')?></th>
                                <th><?=$this->lang->line('bookcategory_description')?></th>
                                <th><?=$this->lang->line('bookcategory_status')?></th>
                                <?php if(permissionChecker('bookcategory_edit') || permissionChecker('bookcategory_delete')) { ?>
                                    <th><?=$this->lang->line('bookcategory_action')?></th>
                                <?php } ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(calculate($bookcategory)) { $i=0; foreach($bookcategory as $category) { $i++; ?>
                                <tr>
                                    <td data-title="<?=$this->lang->line('bookcategory_slno')?>"><?=$i?></td>
                                    <td data-title="<?=$this->lang->line('bookcategory_cover_photo')?>"><img src="<?=app_image_link($category->coverphoto,'uploads/bookcategory/','bookcategory.jpg')?>" class="profile_img" alt=""></td>
                                    <td data-title="<?=$this->lang->line('bookcategory_name')?>"><?=$category->name?></td>
                                    <td data-title="<?=$this->lang->line('bookcategory_description')?>"><?=namesorting($category->description, 50)?></td>
                                    <td data-title="<?=$this->lang->line('bookcategory_status')?>"><?=status_button($category->status)?></td>
                                    <?php if(permissionChecker('bookcategory_edit') || permissionChecker('bookcategory_delete')) { ?>
                                        <td data-title="<?=$this->lang->line('bookcategory_action')?>">
                                            <?=btn_edit('bookcategory/edit/'.$category->bookcategoryID,$this->lang->line('bookcategory_edit')); ?>
                                            <?=btn_delete('bookcategory/delete/'.$category->bookcategoryID,$this->lang->line('bookcategory_delete')); ?>
                                        </td>
                                    <?php } ?>
                                </tr>
                            <?php } } ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <th><?=$this->lang->line('bookcategory_slno')?></th>
                                <th><?=$this->lang->line('bookcategory_cover_photo')?></th>
                                <th><?=$this->lang->line('bookcategory_name')?></th>
                                <th><?=$this->lang->line('bookcategory_description')?></th>
                                <th><?=$this->lang->line('bookcategory_status')?></th>
                                <?php if(permissionChecker('bookcategory_edit') || permissionChecker('bookcategory_delete')) { ?>    
                                    <th><?=$this->lang->line('bookcategory_action')?></th>
                                <?php } ?>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
          </div>
    </section>
</div>