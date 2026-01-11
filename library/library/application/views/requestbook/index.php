<div class="content-wrapper">
    <section class="content-header">
		<h1><?=$this->lang->line('requestbook')?></h1>
		<ol class="breadcrumb">
            <li><a href="<?=base_url('dashboard/index')?>"><i class="fa fa-dashboard"></i><?=$this->lang->line('dashboard')?></a></li>
			<li class="active"><?=$this->lang->line('requestbook')?></li>
		</ol>
    </section>
    <section class="content">
        <div class="box box-mytheme">
            <?php if(permissionChecker('requestbook_add')) { ?>
            <div class="box-header">
                <a href="<?=base_url('requestbook/add')?>" class="btn btn-inline btn-mytheme btn-md"><i class="fa fa-plus"></i> <?=$this->lang->line('requestbook_add_requestbook')?></a>
            </div>
            <?php } ?>
            <div class="box-body">
                <div id="hide-table">
                    <table id="example1" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th><?=$this->lang->line('requestbook_slno')?></th>
                                <th><?=$this->lang->line('requestbook_cover_photo')?></th>
                                <th><?=$this->lang->line('requestbook_name')?></th>
                                <th><?=$this->lang->line('requestbook_author')?></th>
                                <th><?=$this->lang->line('requestbook_book_category')?></th>
                                <th><?=$this->lang->line('requestbook_status')?></th>
                                <?php if(permissionChecker('requestbook_edit') || permissionChecker('requestbook_delete')) { ?>
                                    <th><?=$this->lang->line('requestbook_action')?></th>
                                <?php } ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(calculate($requestbooks)) { $i=0; foreach($requestbooks as $requestbook) { $i++; ?>
                                <tr>
                                    <td data-title="<?=$this->lang->line('requestbook_slno')?>"><?=$i?></td>
                                    <td data-title="<?=$this->lang->line('requestbook_cover_photo')?>"><img src="<?=app_image_link($requestbook->coverphoto,'uploads/book/','requestbook.jpg')?>" class="profile_img" alt=""></td>
                                    <td data-title="<?=$this->lang->line('requestbook_name')?>"><?=$requestbook->name?></td>
                                    <td data-title="<?=$this->lang->line('requestbook_author')?>"><?=$requestbook->author?></td>
                                    <td data-title="<?=$this->lang->line('requestbook_book_category')?>"><?=isset($bookcategorys[$requestbook->bookcategoryID]) ? $bookcategorys[$requestbook->bookcategoryID] : ''?></td>
                                    <td data-title="<?=$this->lang->line('requestbook_status')?>">
                                        <span class="btn btn-danger btn-xs mrg">
                                        <?php 
                                            if($requestbook->status ==0) {
                                                echo $this->lang->line('requestbook_requested');
                                            } elseif($requestbook->status == 1) {
                                                echo $this->lang->line('requestbook_accepted');
                                            } elseif($requestbook->status == 2) {
                                                echo $this->lang->line('requestbook_rejected');
                                            }

                                            if($requestbook->deleted_at) {
                                                echo " - ".$this->lang->line('requestbook_deleted');
                                            }
                                        ?>
                                        </span>
                                    </td>
                                    <?php if(permissionChecker('requestbook_edit') || permissionChecker('requestbook_delete')) { ?>
                                        <td data-title="<?=$this->lang->line('requestbook_action')?>">
                                            <?=btn_view('requestbook/view/'.$requestbook->requestbookID, $this->lang->line('requestbook_view')); ?>
                                            <?php if(($requestbook->status==0) && ($requestbook->deleted_at==0)) {
                                                echo btn_edit('requestbook/edit/'.$requestbook->requestbookID, $this->lang->line('requestbook_edit'))." "; 
                                                echo btn_delete('requestbook/delete/'.$requestbook->requestbookID, $this->lang->line('requestbook_delete')); 
                                            } ?>
                                            <?php if(permissionChecker('book_add') && ($requestbook->status==0) && ($requestbook->deleted_at==0)) { ?>
                                                <a href="<?=base_url('requestbook/bookadd/'.$requestbook->requestbookID)?>" class="btn btn-mytheme btn-xs mrg" data-placement="auto" data-toggle="tooltip" data-original-title="<?=$this->lang->line('requestbook_book_add')?>"><i class="fa fa-external-link"></i></a>
                                            <?php } 
                                            if(($requestbook->status == 0) && ($requestbook->deleted_at==0)) { ?>
                                                <a href="<?=base_url('requestbook/rejected/'.$requestbook->requestbookID)?>" class="btn btn-info btn-xs mrg" data-placement="auto" data-toggle="tooltip" data-original-title="<?=$this->lang->line('requestbook_rejected')?>"><i class="fa fa-ban"></i></a>
                                            <?php } ?>
                                        </td>
                                    <?php } ?>
                                </tr>
                            <?php } } ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <th><?=$this->lang->line('requestbook_slno')?></th>
                                <th><?=$this->lang->line('requestbook_cover_photo')?></th>
                                <th><?=$this->lang->line('requestbook_name')?></th>
                                <th><?=$this->lang->line('requestbook_author')?></th>
                                <th><?=$this->lang->line('requestbook_book_category')?></th>
                                <th><?=$this->lang->line('requestbook_status')?></th>
                                <?php if(permissionChecker('requestbook_edit') || permissionChecker('requestbook_delete')) { ?>
                                    <th><?=$this->lang->line('requestbook_action')?></th>
                                <?php } ?>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
          </div>
    </section>
</div>
