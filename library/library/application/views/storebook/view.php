<div class="content-wrapper">
    <section class="content-header">
        <h1><?=$this->lang->line('storebook')?></h1>
        <ol class="breadcrumb">
            <li><a href="<?=base_url('dashboard/index')?>"><i class="fa fa-dashboard"></i><?=$this->lang->line('dashboard')?></a></li>
            <li><a href="<?=base_url('storebook/index')?>"><?=$this->lang->line('storebook')?></a></li>
            <li class="active"><?=$this->lang->line('view')?></li>
        </ol>
    </section>
    <section class="content">
        <div class="box box-mytheme">
            <div class="box-body">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="profile_view_item">
                            <p><b><?=$this->lang->line('storebook_name')?></b>: <?=$storebook->name?></p>
                        </div>
                        <div class="profile_view_item">
                            <p><b><?=$this->lang->line('storebook_author')?></b>: <?=$storebook->author?></p>
                        </div>
                        <div class="profile_view_item">
                            <p><b><?=$this->lang->line('storebook_quantity')?></b>: <?=$storebook->quantity?></p>
                        </div>
                        <div class="profile_view_item">
                            <p><b><?=$this->lang->line('storebook_price')?></b>: <?=$storebook->price?></p>
                        </div>
                        <div class="profile_view_item">
                            <p><b><?=$this->lang->line('storebook_code_no')?></b>: <?=$storebook->codeno?></p>
                        </div>
                        <div class="profile_view_item">
                            <p><b><?=$this->lang->line('storebook_storebook_category')?></b>: <?=calculate($storebookcategory) ? $storebookcategory->name : ''?></p>
                        </div>
                        <div class="profile_view_item">
                            <p><b><?=$this->lang->line('storebook_isbn_no')?></b>: <?=$storebook->isbnno?></p>
                        </div>
                        <div class="profile_view_item">
                            <p><b><?=$this->lang->line('storebook_edition_number')?></b>: <?=$storebook->editionnumber?></p>
                        </div>
                        <div class="profile_view_item">
                            <p><b><?=$this->lang->line('storebook_edition_date')?></b>: <?=app_date($storebook->editiondate)?></p>
                        </div>
                        <div class="profile_view_item">
                            <p><b><?=$this->lang->line('storebook_publisher')?></b>: <?=$storebook->publisher?></p>
                        </div>
                        <div class="profile_view_item">
                            <p><b><?=$this->lang->line('storebook_published_date')?></b>: <?=app_date($storebook->publisheddate)?></p>
                        </div>
                        <div class="profile_view_item">
                            <p><b><?=$this->lang->line('storebook_notes')?></b>: <?=$storebook->notes?></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>