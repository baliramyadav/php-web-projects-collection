<div class="content-wrapper">
    <section class="content-header">
        <h1><?=$this->lang->line('ebook')?></h1>
        <ol class="breadcrumb">
            <li><a href="<?=base_url('dashboard/index')?>"><i class="fa fa-dashboard"></i><?=$this->lang->line('dashboard')?></a></li>
            <li class="active"><?=$this->lang->line('ebook')?></li>
        </ol>
    </section>
    <section class="content">
        <div class="box box-mytheme">
            <?php if(permissionChecker('ebook_add')) { ?>
                <div class="box-header">
                    <a href="<?=base_url('ebook/add')?>" class="btn btn-inline btn-mytheme btn-md"><i class="fa fa-plus"></i><?=$this->lang->line('ebook_add_ebook')?></a>
                </div>
            <?php } ?>
            <div class="box-body">
                <div class="mainebook">
                    <?php if(calculate($ebooks)) { ?>
                        <div class="row">
                            <?php foreach($ebooks as $ebook) { ?>
                                <div class="col-lg-4 col-md-6 col-sm-12">
                                    <div class="single-ebook">
                                        <div class="single-ebook-img">
                                            <img src="<?=base_url('uploads/ebook/'.$ebook->coverphoto)?>" class="img-fluid" alt="">
                                            <div class="single-overlay">
                                                <div class="icon-info">
                                                    <h6><b><?=$this->lang->line('ebook_name')?> :</b> <?=$ebook->name?></h6>
                                                    <h6><b><?=$this->lang->line('ebook_author')?> :</b> <?=$ebook->author?></h6>
                                                    <p><?=namesorting($ebook->notes, 200)?></p>
                                                </div>
                                                <div class="icon-item">
                                                    <ul>
                                                        <?php if(permissionChecker('ebook_edit')) { ?>
                                                            <li><a href="<?=base_url('ebook/edit/'.$ebook->ebookID)?>"><i class="fa fa-edit"></i></a></li>
                                                        <?php } if(permissionChecker('ebook_view')) { ?>
                                                            <li><a href="<?=base_url('ebook/view/'.$ebook->ebookID)?>"><i class="fa fa-eye"></i></a></li>
                                                        <?php } if(permissionChecker('ebook_delete')) { ?>
                                                            <li><a href="<?=base_url('ebook/delete/'.$ebook->ebookID)?>"><i class="fa fa-trash"></i></a></li>
                                                        <?php } if(permissionChecker('ebook_view') && ($generalsetting->ebook_download == 1)) { ?>
                                                            <li><a href="<?=base_url('ebook/download/'.$ebook->ebookID)?>"><i class="fa fa-download"></i></a></li>
                                                        <?php } ?>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php } ?>
                        </div>
                        <div class="row">
                            <div class="col-sm-12">
                                <?=$this->pagination->create_links();?>
                            </div>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>
    </section>
</div>