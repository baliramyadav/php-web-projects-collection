<div class="content-wrapper">
    <section class="content-header">
  		<h1><?=$this->lang->line('role')?></h1>
  		<ol class="breadcrumb">
            <li><a href="<?=base_url('dashboard')?>"><i class="fa fa-dashboard"></i><?=$this->lang->line('dashboard')?></a></li>
  			<li><a href="<?=base_url('role')?>"><?=$this->lang->line('role')?></a></li>
  			<li class="active"><?=$this->lang->line('add')?></li>
  		</ol>
    </section>
    <section class="content">
        <div class="box box-mytheme">
            <div class="row">
                <div class="col-md-6">
                    <form role="form" method="POST">
                        <div class="box-body">
                            <div class="form-group <?=form_error('role') ? 'has-error' : ''?>">
                                <label for="role"><?=$this->lang->line('role_role')?></label> <span class="text-red">*</span>
                                <input type="text" class="form-control" value="<?=set_value('role')?>" id="role" name="role"/>
                                <?=form_error('role')?>
                            </div>
                        </div>
                        <div class="box-footer">
                            <button type="submit" class="btn btn-mytheme"><?=$this->lang->line('role_add_role')?></button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
</div>
