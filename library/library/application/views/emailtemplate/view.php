<div class="content-wrapper">
    <section class="content-header">
        <h1><?=$this->lang->line('email_template')?></h1>
        <ol class="breadcrumb">
            <li><a href="<?=base_url('dashboard/index')?>"><i class="fa fa-dashboard"></i><?=$this->lang->line('dashboard')?></a></li>
            <li><a href="<?=base_url('emailtemplate/index')?>"><?=$this->lang->line('email_template')?></a></li>
            <li class="active"><?=$this->lang->line('view')?></li>
        </ol>
    </section>
    <section class="content">
        <div class="box box-mytheme">
            <div class="box-body">
                <h3><?=$emailtemplate->name?></h3>
                <p><?=$emailtemplate->template?></p>
            </div>
        </div>
    </section>
</div>