<div class="content-wrapper">
    <section class="content-header">
		<h1><?=$this->lang->line('book')?></h1>
		<ol class="breadcrumb">
            <li><a href="<?=base_url('dashboard/index')?>"><i class="fa fa-dashboard"></i><?=$this->lang->line('dashboard')?></a></li>
			<li class="active"><?=$this->lang->line('book')?></li>
		</ol>
    </section>
    <section class="content">
        <div class="box box-mytheme">
            <?php if(permissionChecker('book_add')) { ?>
            <div class="box-header">
                <a href="<?=base_url('book/add')?>" class="btn btn-inline btn-mytheme btn-md"><i class="fa fa-plus"></i> <?=$this->lang->line('book_add_book')?></a>
            </div>
            <?php } ?>
            <div class="box-body">
                <div id="hide-table">
                    <table id="example1" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th><?=$this->lang->line('book_slno')?></th>
                                <th><?=$this->lang->line('book_cover_photo')?></th>
                                <th><?=$this->lang->line('book_name')?></th>
                                <th><?=$this->lang->line('book_author')?></th>
                                <th><?=$this->lang->line('book_quantity')?></th>
                                <th><?=$this->lang->line('book_code_no')?></th>
                                <?php if(permissionChecker('book_edit') || permissionChecker('book_delete')) { ?>
                                    <th><?=$this->lang->line('book_action')?></th>
                                <?php } ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(calculate($books)) { $i=0; foreach($books as $book) { $i++; ?>
                                <tr>
                                    <td data-title="<?=$this->lang->line('book_slno')?>"><?=$i?></td>
                                    <td data-title="<?=$this->lang->line('book_cover_photo')?>"><img src="<?=app_image_link($book->coverphoto,'uploads/book/','book.jpg')?>" class="profile_img" alt=""></td>
                                    <td data-title="<?=$this->lang->line('book_name')?>"><?=$book->name?></td>
                                    <td data-title="<?=$this->lang->line('book_author')?>"><?=$book->author?></td>
                                    <td data-title="<?=$this->lang->line('book_quantity')?>"><?=$book->quantity?></td>
                                    <td data-title="<?=$this->lang->line('book_code_no')?>"><?=$book->codeno?></td>
                                    <?php if(permissionChecker('book_edit') || permissionChecker('book_delete')) { ?>
                                        <td data-title="<?=$this->lang->line('book_action')?>">
                                            <?=btn_view('book/view/'.$book->bookID,$this->lang->line('book_view')); ?>
                                            <?php if($book->deleted_at == 0) {
                                                echo btn_edit('book/edit/'.$book->bookID,$this->lang->line('book_edit')). " ";
                                                echo btn_delete('book/delete/'.$book->bookID,$this->lang->line('book_delete'));
                                            } ?>
                                        </td>
                                    <?php } ?>
                                </tr>
                            <?php } } ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <th><?=$this->lang->line('book_slno')?></th>
                                <th><?=$this->lang->line('book_cover_photo')?></th>
                                <th><?=$this->lang->line('book_name')?></th>
                                <th><?=$this->lang->line('book_author')?></th>
                                <th><?=$this->lang->line('book_quantity')?></th>
                                <th><?=$this->lang->line('book_code_no')?></th>
                                <?php if(permissionChecker('book_edit') || permissionChecker('book_delete')) { ?>    
                                    <th><?=$this->lang->line('book_action')?></th>
                                <?php } ?>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
          </div>
    </section>
</div>