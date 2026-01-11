<div class="content-wrapper">
    <section class="content-header">
        <h1><?=$this->lang->line('ebook')?></h1>
        <ol class="breadcrumb">
            <li><a href="<?=base_url('dashboard/index')?>"><i class="fa fa-dashboard"></i><?=$this->lang->line('dashboard')?></a></li>
            <li><a href="<?=base_url('ebook/index')?>"><?=$this->lang->line('ebook')?></a></li>
            <li class="active"><?=$this->lang->line('view')?></li>
        </ol>
    </section>
    <section class="content">
        <div class="box box-mytheme">
            <div class="box-body">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="profile_view_item">
                            <p><b><?=$this->lang->line('ebook_name')?></b>: <?=$ebook->name?></p>
                        </div>
                        <div class="profile_view_item">
                            <p><b><?=$this->lang->line('ebook_author')?></b>: <?=$ebook->author?></p>
                        </div>
                        <div class="profile_view_item">
                            <p><b><?=$this->lang->line('ebook_notes')?></b>: <?=$ebook->notes?></p>
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <div id="pdffile"></div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<script type="text/javascript">
    var options = {
        pdfOpenParams: { view: 'Fit', pagemode: 'none', scrollbar: '1', toolbar: '1', statusbar: '1', messages: '1', navpanes: '1' }
    };

    PDFObject.embed("<?=base_url('uploads/ebook/'.$ebook->file)?>", "#pdffile");
</script>