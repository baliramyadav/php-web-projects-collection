<div class="content-wrapper">
    <section class="content-header">
        <h1><?=$this->lang->line('bookbarcode')?></h1>
        <ol class="breadcrumb">
            <li><a href="<?=base_url('dashboard/index')?>"><i class="fa fa-dashboard"></i><?=$this->lang->line('dashboard')?></a></li>
            <li class="active"><?=$this->lang->line('bookbarcode')?></li>
        </ol>
    </section>
    <section class="content">
        <div class="box box-mytheme">
            <div class="box-body">
                <form method="POST" action="<?=base_url('bookbarcode/index')?>">
                    <div class="row">
                        <div class="col-sm-4">
                            <div class="form-group <?=form_error('bookcategoryID') ? 'has-error' : ''?>">
                                <label><?=$this->lang->line('bookbarcode_book_category')?></label> <span class="text-red">*</span>
                                <?php 
                                    $bookcategoryArray[0]   = $this->lang->line('bookbarcode_please_select');
                                    if(calculate($bookcategorys)) {
                                        foreach($bookcategorys as $bookcategory) {
                                            $bookcategoryArray[$bookcategory->bookcategoryID] = $bookcategory->name;
                                        }
                                    }
                                    echo form_dropdown('bookcategoryID', $bookcategoryArray, set_value('bookcategoryID'),'id="bookcategoryID" class="form-control"');
                                ?>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group <?=form_error('bookID') ? 'has-error' : ''?>">
                                <label><?=$this->lang->line('bookbarcode_book')?></label> <span class="text-red">*</span>
                                <?php
                                    $bookArray[0]   = $this->lang->line('bookbarcode_please_select');
                                    if(calculate($books)) {
                                        foreach($books as $book) {
                                            $bookArray[$book->bookID] = $book->name . ' - ' . $book->codeno;
                                        }
                                    } 
                                    echo form_dropdown('bookID', $bookArray, set_value('bookID'),'id="bookID" class="form-control"');
                                ?>
                            </div>
                        </div>

                        <div class="col-sm-4">
                            <div class="form-group">
                                <button class="btn btn-mytheme" style="margin-top: 23px"><?=$this->lang->line('bookbarcode_get_barcode')?></button>
                                <?php if($flag) { ?>
                                    <button class="btn btn-mytheme divhide" onclick="printDiv()" style="margin-top: 23px"><?=$this->lang->line('bookbarcode_print_barcode')?></button>
                                    <a href="<?=base_url('bookbarcode/pdf/'.$bookcategoryID.'/'.$bookID)?>" class="btn btn-mytheme divhide" style="margin-top: 23px" target="_blank"><?=$this->lang->line('bookbarcode_pdf_barcode')?></a>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <?php if($flag) { ?>
            <div class="box box-mytheme divhide">
                <div class="box-body">
                    <div class="row">
                        <div class="col-sm-12" id="printDiv">
                            <?php $this->load->view('bookbarcode/view')?>
                        </div>
                    </div>
                </div>
            </div>
        <?php } ?>
    </section>
</div>

<script type="text/javascript">
    function printDiv() {
        var oldPage  = document.body.innerHTML;
        var printDiv = document.getElementById('printDiv').innerHTML;
        document.body.innerHTML = '<html><head><title>'+document.title+'</title></head><body>'+printDiv+'</body></html>';
        window.print();
        document.body.innerHTML = oldPage;
        window.location.reload();
    }
</script>