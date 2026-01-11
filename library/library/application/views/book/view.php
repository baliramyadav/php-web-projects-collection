<div class="content-wrapper">
    <section class="content-header">
        <h1><?=$this->lang->line('book')?></h1>
        <ol class="breadcrumb">
            <li><a href="<?=base_url('dashboard/index')?>"><i class="fa fa-dashboard"></i><?=$this->lang->line('dashboard')?></a></li>
            <li><a href="<?=base_url('book/index')?>"><?=$this->lang->line('book')?></a></li>
            <li class="active"><?=$this->lang->line('view')?></li>
        </ol>
    </section>
    <section class="content">
        <div class="box box-mytheme">
            <div class="box-body">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="profile_view_item">
                            <p><b><?=$this->lang->line('book_name')?></b>: <?=$book->name?></p>
                        </div>
                        <div class="profile_view_item">
                            <p><b><?=$this->lang->line('book_author')?></b>: <?=$book->author?></p>
                        </div>
                        <div class="profile_view_item">
                            <p><b><?=$this->lang->line('book_quantity')?></b>: <?=$book->quantity?></p>
                        </div>
                        <div class="profile_view_item">
                            <p><b><?=$this->lang->line('book_price')?></b>: <?=$book->price?></p>
                        </div>
                        <div class="profile_view_item">
                            <p><b><?=$this->lang->line('book_code_no')?></b>: <?=$book->codeno?></p>
                        </div>
                        <div class="profile_view_item">
                            <p><b><?=$this->lang->line('book_book_category')?></b>: <?=calculate($bookcategory) ? $bookcategory->name : ''?></p>
                        </div>
                        <div class="profile_view_item">
                            <p><b><?=$this->lang->line('book_isbn_no')?></b>: <?=$book->isbnno?></p>
                        </div>
                        <div class="profile_view_item">
                            <p><b><?=$this->lang->line('book_rack')?></b>: <?=calculate($rack) ? $rack->name : ''?></p>
                        </div>
                        <div class="profile_view_item">
                            <p><b><?=$this->lang->line('book_edition_number')?></b>: <?=$book->editionnumber?></p>
                        </div>
                        <div class="profile_view_item">
                            <p><b><?=$this->lang->line('book_edition_date')?></b>: <?=app_date($book->editiondate)?></p>
                        </div>
                        <div class="profile_view_item">
                            <p><b><?=$this->lang->line('book_publisher')?></b>: <?=$book->publisher?></p>
                        </div>
                        <div class="profile_view_item">
                            <p><b><?=$this->lang->line('book_published_date')?></b>: <?=app_date($book->publisheddate)?></p>
                        </div>
                        <div class="profile_view_item">
                            <p><b><?=$this->lang->line('book_notes')?></b>: <?=$book->notes?></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>