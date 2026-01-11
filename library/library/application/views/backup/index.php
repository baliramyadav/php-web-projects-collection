<div class="content-wrapper">
    <section class="content-header">
		<h1><?=$this->lang->line('backup')?></h1>
		<ol class="breadcrumb">
			<li><a href="<?=base_url('dashboard/index')?>"><i class="fa fa-dashboard"></i><?=$this->lang->line('dashboard')?></a></li>
            <li class="active"><?=$this->lang->line('backup')?></li>
		</ol>
    </section>
    <section class="content">
        <div class="box box-mytheme">
            <div class="box-header">
                <a href="<?=base_url('backup/backup')?>" class="btn btn-danger btn-lg"><i class="fa fa-download"></i> <?=$this->lang->line('backup_database_backup')?></a>
            </div>
        </div>
    </section>
</div>