<div class="content-wrapper">
    <section class="content-header">
		<h1><?=$this->lang->line('storebookcategory')?></h1>
		<ol class="breadcrumb">
            <li><a href="<?=base_url('dashboard/index')?>"><i class="fa fa-dashboard"></i><?=$this->lang->line('dashboard')?></a></li>
			<li class="active"><?=$this->lang->line('storebookcategory')?></li>
		</ol>
    </section>
    <section class="content">
        <div class="box box-mytheme">
            <?php if(permissionChecker('storebookcategory_add')) { ?>
            <div class="box-header">
                <a href="<?=base_url('storebookcategory/add')?>" class="btn btn-inline btn-mytheme btn-md"><i class="fa fa-plus"></i> <?=$this->lang->line('storebookcategory_add_book_category')?></a>
            </div>
            <?php } ?>
            <div class="box-body">
                <div id="hide-table">
                    <table id="example1" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th><?=$this->lang->line('storebookcategory_slno')?></th>
                                <th><?=$this->lang->line('storebookcategory_cover_photo')?></th>
                                <th><?=$this->lang->line('storebookcategory_name')?></th>
                                <th><?=$this->lang->line('storebookcategory_description')?></th>
                                <th><?=$this->lang->line('storebookcategory_status')?></th>
                                <?php if(permissionChecker('storebookcategory_edit') || permissionChecker('storebookcategory_delete')) { ?>
                                    <th><?=$this->lang->line('storebookcategory_action')?></th>
                                <?php } ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(calculate($storebookcategory)) { $i=0; foreach($storebookcategory as $category) { $i++; ?>
                                <tr>
                                    <td data-title="<?=$this->lang->line('storebookcategory_slno')?>"><?=$i?></td>
                                    <td data-title="<?=$this->lang->line('storebookcategory_cover_photo')?>"><img src="<?=app_image_link($category->coverphoto,'uploads/storebookcategory/','storebookcategory.jpg')?>" class="profile_img" alt=""></td>
                                    <td data-title="<?=$this->lang->line('storebookcategory_name')?>"><?=$category->name?></td>
                                    <td data-title="<?=$this->lang->line('storebookcategory_description')?>"><?=namesorting($category->description, 50)?></td>
                                    <td data-title="<?=$this->lang->line('storebookcategory_status')?>"><?=status_button($category->status)?></td>
                                    <?php if(permissionChecker('storebookcategory_edit') || permissionChecker('storebookcategory_delete')) { ?>
                                        <td data-title="<?=$this->lang->line('storebookcategory_action')?>">
                                            <?=btn_edit('storebookcategory/edit/'.$category->storebookcategoryID,$this->lang->line('storebookcategory_edit')); ?>
                                            <?=btn_delete('storebookcategory/delete/'.$category->storebookcategoryID,$this->lang->line('storebookcategory_delete')); ?>
                                        </td>
                                    <?php } ?>
                                </tr>
                            <?php } } ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <th><?=$this->lang->line('storebookcategory_slno')?></th>
                                <th><?=$this->lang->line('storebookcategory_cover_photo')?></th>
                                <th><?=$this->lang->line('storebookcategory_name')?></th>
                                <th><?=$this->lang->line('storebookcategory_description')?></th>
                                <th><?=$this->lang->line('storebookcategory_status')?></th>
                                <?php if(permissionChecker('storebookcategory_edit') || permissionChecker('storebookcategory_delete')) { ?>    
                                    <th><?=$this->lang->line('storebookcategory_action')?></th>
                                <?php } ?>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
          </div>
    </section>
</div>