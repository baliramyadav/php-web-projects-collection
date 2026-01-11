<div class="content-wrapper">
    <section class="content-header">
        <h1><?=$this->lang->line('libraryconfigure')?></h1>
        <ol class="breadcrumb">
            <li><a href="<?=base_url('dashboard')?>"><i class="fa fa-dashboard"></i><?=$this->lang->line('dashboard')?></a></li>
            <li><a href="<?=base_url('libraryconfigure')?>"><?=$this->lang->line('libraryconfigure')?></a></li>
            <li class="active"><?=$this->lang->line('edit')?></li>
        </ol>
    </section>
    <section class="content">
        <div class="box box-mytheme">
            <div class="row">
                <div class="col-md-6">
                    <form role="form" method="POST">
                        <div class="box-body">
                            <div class="form-group <?=form_error('roleID') ? 'has-error' : ''?>">
                                <label for="roleID"><?=$this->lang->line('libraryconfigure_role')?></label> <span class="text-red">*</span>
                                <?php 
                                    $roleArray[0] = $this->lang->line('libraryconfigure_please_select');
                                    if(calculate($roles)) {
                                      foreach ($roles as $role) {
                                        $roleArray[$role->roleID] = $role->role;
                                      }
                                    }
                                    echo form_dropdown('roleID', $roleArray,set_value('roleID', $libraryconfigure->roleID),'id="roleID" class="form-control"');
                                ?>
                                <?=form_error('roleID')?>
                            </div>
                            <div class="form-group <?=form_error('max_issue_book') ? 'has-error' : ''?>">
                                <label for="max_issue_book"><?=$this->lang->line('libraryconfigure_max_issue_book')?></label> <span class="text-red">*</span>
                                <input type="text" class="form-control" value="<?=set_value('max_issue_book', $libraryconfigure->max_issue_book)?>" id="max_issue_book" name="max_issue_book"/>
                                <?=form_error('max_issue_book')?>
                            </div>
                            <div class="form-group <?=form_error('max_renewed_limit') ? 'has-error' : ''?>">
                                <label for="max_renewed_limit"><?=$this->lang->line('libraryconfigure_max_renewed_limit')?></label> <span class="text-red">*</span>
                                <input type="text" class="form-control" value="<?=set_value('max_renewed_limit', $libraryconfigure->max_renewed_limit)?>" id="max_renewed_limit" name="max_renewed_limit"/>
                                <?=form_error('max_renewed_limit')?>
                            </div>
                            <div class="form-group <?=form_error('per_renew_limit_day') ? 'has-error' : ''?>">
                                <label for="per_renew_limit_day"><?=$this->lang->line('libraryconfigure_per_renew_limit_day')?></label> <span class="text-red">*</span>
                                <input type="text" class="form-control" value="<?=set_value('per_renew_limit_day', $libraryconfigure->per_renew_limit_day)?>" id="per_renew_limit_day" name="per_renew_limit_day"/>
                                <?=form_error('per_renew_limit_day')?>
                            </div>
                            <div class="form-group <?=form_error('book_fine_per_day') ? 'has-error' : ''?>">
                                <label for="book_fine_per_day"><?=$this->lang->line('libraryconfigure_book_fine_per_day')?></label> <span class="text-red">*</span>
                                <input type="text" class="form-control" value="<?=set_value('book_fine_per_day', $libraryconfigure->book_fine_per_day)?>" id="book_fine_per_day" name="book_fine_per_day"/>
                                <?=form_error('book_fine_per_day')?>
                            </div>
                            <div class="form-group <?=form_error('issue_off_limit_amount') ? 'has-error' : ''?>">
                                <label for="issue_off_limit_amount"><?=$this->lang->line('libraryconfigure_issue_off_limit_amount')?></label> <span class="text-red">*</span>
                                <input type="text" class="form-control" value="<?=set_value('issue_off_limit_amount', $libraryconfigure->issue_off_limit_amount)?>" id="issue_off_limit_amount" name="issue_off_limit_amount"/>
                                <?=form_error('issue_off_limit_amount')?>
                            </div>
                        </div>
                        <div class="box-footer">
                            <button type="submit" class="btn btn-mytheme"><?=$this->lang->line('libraryconfigure_update_libraryconfigure')?></button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
</div>

<script>
    $('.form-control').on('keyup', function() {
        var value = convertNumber($(this).val());
        $(this).val(value);
    });
   
    function convertNumber(data) {
        if(parseInt(data)) {
            return parseInt(data);
        }
        return 0;
    }
</script>
