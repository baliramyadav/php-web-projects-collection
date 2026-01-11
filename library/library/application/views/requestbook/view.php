<div class="content-wrapper">
    <section class="content-header">
        <h1><?=$this->lang->line('requestbook')?></h1>
        <ol class="breadcrumb">
            <li><a href="<?=base_url('dashboard/index')?>"><i class="fa fa-dashboard"></i><?=$this->lang->line('dashboard')?></a></li>
            <li><a href="<?=base_url('requestbook/index')?>"><?=$this->lang->line('requestbook')?></a></li>
            <li class="active"><?=$this->lang->line('view')?></li>
        </ol>
    </section>
    <section class="content">
        <div class="box box-mytheme">
            <div class="box-body">
                <div class="row" style="padding-top: 0px;">
                    <div class="col-sm-12">
                        <div class="panel-body profile_view_des">
                            <div class="row">
                                <div class="profile_view_item">
                                    <p><b><?=$this->lang->line('requestbook_name')?></b>: <?=$requestbook->name?></p>
                                </div>
                                <div class="profile_view_item">
                                    <p><b><?=$this->lang->line('requestbook_author')?></b>: <?=$requestbook->author?></p>
                                </div>
                                <div class="profile_view_item">
                                    <p><b><?=$this->lang->line('requestbook_book_category')?></b>: <?=calculate($bookcategory) ? $bookcategory->name : ''?></p>
                                </div>
                                <div class="profile_view_item">
                                    <p><b><?=$this->lang->line('requestbook_isbn_no')?></b>: <?=$requestbook->isbnno?></p>
                                </div>
                                <div class="profile_view_item">
                                    <p><b><?=$this->lang->line('requestbook_edition_number')?></b>: <?=$requestbook->editionnumber?></p>
                                </div>
                                <div class="profile_view_item">
                                    <p><b><?=$this->lang->line('requestbook_edition_date')?></b>: <?=app_date($requestbook->editiondate)?></p>
                                </div>
                                <div class="profile_view_item">
                                    <p><b><?=$this->lang->line('requestbook_publisher')?></b>: <?=$requestbook->publisher?></p>
                                </div>
                                <div class="profile_view_item">
                                    <p><b><?=$this->lang->line('requestbook_published_date')?></b>: <?=app_date($requestbook->publisheddate)?></p>
                                </div>
                                <div class="profile_view_item" width="100%">
                                    <p><b><?=$this->lang->line('requestbook_notes')?></b>: <?=$requestbook->notes?></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
