<section class="main-books">
    <div class="container">
        <div class="card book-header mt-4">
            <div class="card-body">
                <form method="POST" action="<?=base_url('frontend/book')?>">
                    <div class="row">
                        <div class="col-sm-3">
                            <div class="form-group <?=form_error('bookcategoryID') ? 'has-error' : ''?>">
                                <label><?=$this->lang->line('frontend_book_category')?></label>
                                <?php 
                                    $bookcategoryArray[0]   = $this->lang->line('frontend_please_select');
                                    if(calculate($bookcategorys)) {
                                        foreach($bookcategorys as $bookcategory) {
                                            $bookcategoryArray[$bookcategory->bookcategoryID] = $bookcategory->name;
                                        }
                                    }
                                    echo form_dropdown('bookcategoryID', $bookcategoryArray, set_value('bookcategoryID'),'id="bookcategoryID" class="form-control"');
                                ?>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group <?=form_error('bookID') ? 'has-error' : ''?>">
                                <label><?=$this->lang->line('frontend_book')?></label>
                                <?php 
                                    $bookArray[0]   = $this->lang->line('frontend_please_select');
                                    echo form_dropdown('bookID', $bookArray, set_value('bookID'),'id="bookID" class="form-control"');
                                ?>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group <?=form_error('status') ? 'has-error' : ''?>">
                                <label><?=$this->lang->line('frontend_status')?></label>
                                <?php 
                                    $statusArray[0]   = $this->lang->line('frontend_please_select');
                                    $statusArray[1]   = $this->lang->line('frontend_available');
                                    $statusArray[2]   = $this->lang->line('frontend_not_available');
                                    echo form_dropdown('status', $statusArray, set_value('status'),'id="status" class="form-control"');
                                ?>
                            </div>
                        </div>

                        <div class="col-sm-3">
                            <div class="form-group">
                                <button class="btn btn-success" style="margin-top: 30px"><?=$this->lang->line('frontend_get_book')?></button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <?php if($flag) { ?>
            <div class="card my-4 divhide">
                <div class="card-body p-0">
                    <div class="row">
                        <div class="col-sm-12">
                            <?php if(calculate($books)) { ?>
                                <table class="table table-hover table-striped table-bordered booktabble mb-0">
                                    <thead>
                                        <tr class="info">
                                            <th>#</th>
                                            <th><?=$this->lang->line('frontend_cover_photo')?></th>
                                            <th><?=$this->lang->line('frontend_name')?></th>
                                            <th><?=$this->lang->line('frontend_author')?></th>
                                            <th><?=$this->lang->line('frontend_code')?></th>
                                            <th><?=$this->lang->line('frontend_category')?></th>
                                            <th><?=$this->lang->line('frontend_status')?></th>
                                            <th><?=$this->lang->line('frontend_quantity')?></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $i=0; foreach($books as $book) { $i++;?>
                                        <tr>
                                            <td><?=$i?></td>
                                            <td><img src="<?=app_image_link($book->coverphoto, 'uploads/book/', 'book.jpg')?>" class="profile_img"></td>
                                            <td><?=$book->name?></td>
                                            <td><?=$book->author?></td>
                                            <td><?=$book->codeno?></td>
                                            <td><?=isset($bookcategorys[$book->bookcategoryID]) ? $bookcategorys[$book->bookcategoryID]->name : ''?></td>
                                            <td><?=($book->status == 0) ? $this->lang->line('frontend_available') : $this->lang->line('frontend_not_available')?></td>
                                            <td><?=isset($bookQuantity[$book->bookID]) ? $bookQuantity[$book->bookID] : 0?></td>
                                        </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            <?php } else { ?>
                                <div class="not-found">
                                    <?=$this->lang->line('frontend_book_not_available')?>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </div>
        <?php } ?>
    </div>
</section>