<div class="content-wrapper">
    <section class="content-header">
		<h1><?=$this->lang->line('menu')?></h1>
		<ol class="breadcrumb">
			<li><a href="<?=base_url('/dashboard')?>"><i class="fa fa-dashboard"></i><?=$this->lang->line('dashboard')?></a></li>
            <li class="active"><?=$this->lang->line('menu')?></li>
		</ol>
    </section>
    <section class="content">
        <div class="box box-mytheme">
            <?php if(permissionChecker('menu_add')) { ?>
            <div class="box-header">
                <!-- <a href="<?=base_url('menu/add')?>" class="btn btn-inline btn-mytheme btn-md"><i class="fa fa-plus"></i> <?=$this->lang->line('menu_add_menu')?></a> -->
            </div>
            <?php } ?>
            <div class="box-body">
                <div id="hide-table">
                    <table id="example1" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th><?=$this->lang->line('menu_slno')?></th>
                                <th><?=$this->lang->line('menu_menuname')?></th>
                                <th><?=$this->lang->line('menu_menulink')?></th>
                                <th><?=$this->lang->line('menu_menuicon')?></th>
                                <th><?=$this->lang->line('menu_priority')?></th>
                                <th><?=$this->lang->line('menu_parentmenu')?></th>
                                <th><?=$this->lang->line('menu_status')?></th>
                        </thead>
                        <tbody>
                            <?php if(calculate($menus)) { $i=0; foreach($menus as $menu) { $i++; ?>
                            <tr>
                                <td data-title="<?=$this->lang->line('menu_slno')?>"><?=$i?></td>
                                <td data-title="<?=$this->lang->line('menu_menuname')?>"><?=$menu->menuname?></td>
                                <td data-title="<?=$this->lang->line('menu_menulink')?>"><?=$menu->menulink?></td>
                                <td data-title="<?=$this->lang->line('menu_menuicon')?>"><?=$menu->menuicon?></td>
                                <td data-title="<?=$this->lang->line('menu_priority')?>"><?=$menu->priority?></td>
                                <td data-title="<?=$this->lang->line('menu_parentmenu')?>"><?=isset($menusName[$menu->parentmenuID]) ? ucfirst($menusName[$menu->parentmenuID]) : ''?></td>
                                <td data-title="<?=$this->lang->line('menu_status')?>"><?=status_button($menu->status)?></td>
                            </tr>
                            <?php } } ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <th><?=$this->lang->line('menu_slno')?></th>
                                <th><?=$this->lang->line('menu_menuname')?></th>
                                <th><?=$this->lang->line('menu_menulink')?></th>
                                <th><?=$this->lang->line('menu_menuicon')?></th>
                                <th><?=$this->lang->line('menu_priority')?></th>
                                <th><?=$this->lang->line('menu_parentmenu')?></th>
                                <th><?=$this->lang->line('menu_status')?></th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </section>
</div>