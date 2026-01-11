<div class="content-wrapper">
    <section class="content-header">
      <h1><?=$this->lang->line('permissionlog')?></h1>
      <ol class="breadcrumb">
            <li><a href="<?=base_url('dashboard/index')?>"><i class="fa fa-dashboard"></i><?=$this->lang->line('dashboard')?></a></li>
            <li><a href="<?=base_url('permissionlog/index')?>"><?=$this->lang->line('permissionlog')?></a></li>
            <li class="active"><?=$this->lang->line('edit')?></li>
      </ol>
    </section>
    <section class="content">
        <div class="box box-mytheme">
            <div class="row">
                <div class="col-md-6">
                    <form role="form" method="POST">
                        <div class="box-body">
                            <div class="form-group <?=form_error('name') ? 'has-error' : ''?>">
                                <label for="name"><?=$this->lang->line('permissionlog_name')?></label> <span class="text-red">*</span>
                                <input type="text" class="form-control" value="<?=set_value('name', $permissionlog->name)?>" id="name" name="name"/>
                                <?=form_error('name')?>
                            </div>
                            <div class="form-group <?=form_error('description') ? 'has-error' : ''?>">
                                <label for="description"><?=$this->lang->line('permissionlog_description')?></label> <span class="text-red">*</span>
                                <textarea name="description" id="description" cols="30" rows="3" class="form-control"><?=set_value('description', $permissionlog->description)?></textarea>
                                <?=form_error('description')?>
                            </div>
                            <div class="form-group <?=form_error('active') ? 'has-error' : ''?>">
                                <label for="active"><?=$this->lang->line('permissionlog_action')?></label> <span class="text-red">*</span>
                                <input type="text" class="form-control" value="<?=set_value('active',$permissionlog->active)?>" id="active" name="active" />
                                <?=form_error('active')?>
                            </div>
                        </div>
                        <div class="box-footer">
                            <button type="submit" class="btn btn-mytheme"><?=$this->lang->line('permissionlog_update_permissionlog')?></button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
</div>
