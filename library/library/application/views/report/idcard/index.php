<?php header("Cache-Control: no cache");?>
<div class="content-wrapper">
    <section class="content-header">
        <h1><?=$this->lang->line('idcardreport')?></h1>
        <ol class="breadcrumb">
            <li><a href="<?=base_url('dashboard/index')?>"><i class="fa fa-dashboard"></i><?=$this->lang->line('dashboard')?></a></li>
            <li class="active"><?=$this->lang->line('idcardreport')?></li>
        </ol>
    </section>
    <section class="content">
        <div class="box box-mytheme">
            <div class="box-body">
                <div class="row">
                    <form method="POST" action="<?=base_url('idcardreport/index')?>">
                        <div class="col-sm-4">
                            <div class="form-group <?=form_error('roleID') ? 'has-error' : ''?>">
                                <label><?=$this->lang->line('idcardreport_role')?></label> <span class="text-red">*</span>
                                <?php 
                                    $roleArray[0]   = $this->lang->line('idcardreport_please_select');
                                    if(calculate($roles)) {
                                        foreach($roles as $role) {
                                            $roleArray[$role->roleID] = $role->role;
                                        }
                                    }
                                    echo form_dropdown('roleID', $roleArray, set_value('roleID'),'id="roleID" class="form-control"');
                                ?>
                                <?=form_error('roleID')?>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group <?=form_error('memberID') ? 'has-error' : ''?>">
                                <label><?=$this->lang->line('idcardreport_member')?></label>
                                <?php 
                                    $memberArray[0]   = $this->lang->line('idcardreport_please_select');
                                    if(calculate($members)) {
                                        foreach($members as $member) {
                                            $memberArray[$member->memberID] = $member->name;
                                        }
                                    }
                                    echo form_dropdown('memberID', $memberArray, set_value('memberID'),'id="memberID" class="form-control"');
                                ?>
                                <?=form_error('memberID')?>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group <?=form_error('type') ? 'has-error' : ''?>">
                                <label><?=$this->lang->line('idcardreport_type')?></label> <span class="text-red">*</span>
                                <?php 
                                    $typeArray[0]   = $this->lang->line('idcardreport_please_select');
                                    $typeArray[1]   = $this->lang->line('idcardreport_front_part');
                                    $typeArray[2]   = $this->lang->line('idcardreport_back_part');
                                    echo form_dropdown('type', $typeArray, set_value('type'),'id="type" class="form-control"');
                                ?>
                                <?=form_error('type')?>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <button class="btn btn-mytheme" style="margin-top: 5px"><?=$this->lang->line('idcardreport_get_card')?></button>
                                <?php if($flag) { ?>
                                    <button onclick="printDiv()" class="btn btn-mytheme divhide" style="margin-top: 5px"><?=$this->lang->line('idcardreport_print_card')?></button>
                                    <a target="_blank" href="<?=base_url('idcardreport/pdf/'.$roleID.'/'.$memberID.'/'.$type)?>" class="btn btn-mytheme divhide" style="margin-top: 5px"><?=$this->lang->line('idcardreport_pdf_card')?></a>
                                <?php } ?>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <?php if($flag) { ?>
            <div class="box box-mytheme divhide">
                <div class="box-body">
                    <div class="row">
                        <div class="col-sm-12" id="printDiv">
                            <?php $this->load->view('report/idcard/view')?>
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
        document.body.innerHTML = "<html><head><title>"+document.title+"</title></head><body>"+printDiv+"</body></html>";
        window.print();
        document.body.innerHTML = oldPage;
        window.location.reload();
    }
   
</script>