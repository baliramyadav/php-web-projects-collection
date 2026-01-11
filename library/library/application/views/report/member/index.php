<div class="content-wrapper">
    <section class="content-header">
        <h1><?=$this->lang->line('memberreport')?></h1>
        <ol class="breadcrumb">
            <li><a href="<?=base_url('dashboard/index')?>"><i class="fa fa-dashboard"></i><?=$this->lang->line('dashboard')?></a></li>
            <li class="active"><?=$this->lang->line('memberreport')?></li>
        </ol>
    </section>
    <section class="content">
        <div class="box box-mytheme">
            <div class="box-body">
                <form method="POST" action="<?=base_url('memberreport/index')?>">
                    <div class="row">
                        <div class="col-sm-4">
                            <div class="form-group <?=form_error('roleID') ? 'has-error' : ''?>">
                                <label><?=$this->lang->line('memberreport_role')?></label>
                                <?php 
                                    $roleArray[0]   = $this->lang->line('memberreport_please_select');
                                    if(calculate($roles)) {
                                        foreach($roles as $role) {
                                            $roleArray[$role->roleID] = $role->role;
                                        }
                                    }
                                    echo form_dropdown('roleID', $roleArray, set_value('roleID'),'id="roleID" class="form-control"');
                                ?>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group <?=form_error('memberID') ? 'has-error' : ''?>">
                                <label><?=$this->lang->line('memberreport_member')?></label>
                                <?php 
                                    $memberArray[0]   = $this->lang->line('memberreport_please_select');
                                    if(calculate($members)) {
                                        foreach($members as $member) {
                                            $memberArray[$member->memberID] = $member->name;
                                        }
                                    }
                                    echo form_dropdown('memberID', $memberArray, set_value('memberID'),'id="memberID" class="form-control"');
                                ?>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group <?=form_error('bloodgroupID') ? 'has-error' : ''?>">
                                <label><?=$this->lang->line('memberreport_blood_group')?></label>
                                <?php 
                                    $bloodgroupArray[0]   = $this->lang->line('memberreport_please_select');
                                    if(calculate($bloodgroups)) {
                                        foreach($bloodgroups as $bloodgroupKey=> $bloodgroup) {
                                            $bloodgroupArray[$bloodgroupKey] = $bloodgroup;
                                        }
                                    }
                                    echo form_dropdown('bloodgroupID', $bloodgroupArray, set_value('bloodgroupID'),'id="bloodgroupID" class="form-control"');
                                ?>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group <?=form_error('status') ? 'has-error' : ''?>">
                                <label><?=$this->lang->line('memberreport_status')?></label>
                                <?php 
                                    $statusArray[0]   = $this->lang->line('memberreport_please_select');
                                    $statusArray[1]   = $this->lang->line('memberreport_active');
                                    $statusArray[2]   = $this->lang->line('memberreport_block');
                                    echo form_dropdown('status', $statusArray, set_value('status'),'id="status" class="form-control"');
                                ?>
                            </div>
                        </div>

                        <div class="col-sm-8">
                            <div class="form-group">
                                <button class="btn btn-mytheme" style="margin-top: 25px"><?=$this->lang->line('memberreport_get_member')?></button>
                                <?php if($flag) { ?>
                                    <button class="btn btn-mytheme divhide" onclick="printDiv()" style="margin-top: 25px"><?=$this->lang->line('memberreport_print_member')?></button>
                                    <a href="<?=base_url('memberreport/pdf/'.$roleID.'/'.$memberID.'/'.$bloodgroupID.'/'.$status)?>" class="btn btn-mytheme divhide" style="margin-top: 25px" target="_blank"><?=$this->lang->line('memberreport_pdf_member')?></a>
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
                            <?php $this->load->view('report/member/view')?>
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