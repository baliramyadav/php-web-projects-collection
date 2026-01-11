<div class="content-wrapper">
    <section class="content-header">
        <h1><?=$this->lang->line('bookissuereport')?></h1>
        <ol class="breadcrumb">
            <li><a href="<?=base_url('dashboard/index')?>"><i class="fa fa-dashboard"></i><?=$this->lang->line('dashboard')?></a></li>
            <li class="active"><?=$this->lang->line('bookissuereport')?></li>
        </ol>
    </section>
    <section class="content">
        <div class="box box-mytheme">
            <div class="box-body">
                <form method="POST" action="<?=base_url('bookissuereport/index')?>">
                    <div class="row">
                        <div class="col-sm-4">
                            <div class="form-group <?=form_error('bookcategoryID') ? 'has-error' : ''?>">
                                <label><?=$this->lang->line('bookissuereport_book_category')?></label>
                                <?php 
                                    $bookcategoryArray[0]   = $this->lang->line('bookissuereport_please_select');
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
                                <label><?=$this->lang->line('bookissuereport_book')?></label>
                                <?php 
                                    $bookArray[0]   = $this->lang->line('bookissuereport_please_select');
                                    echo form_dropdown('bookID', $bookArray, set_value('bookID'),'id="bookID" class="form-control"');
                                ?>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group <?=form_error('roleID') ? 'has-error' : ''?>">
                                <label><?=$this->lang->line('bookissuereport_role')?></label>
                                <?php 
                                    $roleArray[0]   = $this->lang->line('bookissuereport_please_select');
                                    if(calculate($roles)) {
                                        foreach($roles as $role) {
                                            $roleArray[$role->roleID]   = $role->role;
                                        }
                                    }
                                    echo form_dropdown('roleID', $roleArray, set_value('roleID'),'id="roleID" class="form-control"');
                                ?>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group <?=form_error('memberID') ? 'has-error' : ''?>">
                                <label><?=$this->lang->line('bookissuereport_member')?></label>
                                <?php 
                                    $memberArray[0]   = $this->lang->line('bookissuereport_please_select');
                                    echo form_dropdown('memberID', $memberArray, set_value('memberID'),'id="memberID" class="form-control"');
                                ?>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group <?=form_error('status') ? 'has-error' : ''?>">
                                <label><?=$this->lang->line('bookissuereport_status')?></label>
                                <?php 
                                    $statusArray[0]   = $this->lang->line('bookissuereport_please_select');
                                    $statusArray[1]   = $this->lang->line('bookissuereport_issued');
                                    $statusArray[2]   = $this->lang->line('bookissuereport_return');
                                    $statusArray[3]   = $this->lang->line('bookissuereport_lost');
                                    echo form_dropdown('status', $statusArray, set_value('status'),'id="status" class="form-control"');
                                ?>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group <?=form_error('fromdate') ? 'has-error' : ''?>">
                                <label><?=$this->lang->line('bookissuereport_from_date')?></label>
                                <input type="text" class="form-control datepicker" name="fromdate" value="<?=set_value('fromdate')?>" autocomplete="off">
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group <?=form_error('todate') ? 'has-error' : ''?>">
                                <label><?=$this->lang->line('bookissuereport_to_date')?></label>
                                <input type="text" class="form-control datepicker" name="todate" value="<?=set_value('todate')?>" autocomplete="off">
                            </div>
                        </div>
                        <div class="col-sm-8">
                            <div class="form-group">
                                <button class="btn btn-mytheme" style="margin-top: 25px"><?=$this->lang->line('bookissuereport_get_book_issue')?></button>
                                <?php if($flag) { ?>
                                    <button class="btn btn-mytheme divhide" onclick="printDiv()" style="margin-top: 25px"><?=$this->lang->line('bookissuereport_print_book_issue')?></button>
                                    <a target="_blank" href="<?=base_url('bookissuereport/pdf/'.$bookcategoryID.'/'.$bookID.'/'.$roleID.'/'.$memberID.'/'.$status.'/'.$fromdate.'/'.$todate)?>" class="btn btn-mytheme divhide" style="margin-top: 25px"><?=$this->lang->line('bookissuereport_pdf_book_issue')?></a>
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
                            <?php $this->load->view('report/bookissue/view')?>
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