<div class="content-wrapper">
    <section class="content-header">
      <h1><?=$this->lang->line('menu')?></h1>
        <ol class="breadcrumb">
            <li><a href="<?=base_url('dashboard/index')?>"><i class="fa fa-dashboard"></i><?=$this->lang->line('dashboard')?></a></li>
            <li><a href="<?=base_url('menu/index')?>"><?=$this->lang->line('menu')?></a></li>
            <li class="active"><?=$this->lang->line('edit')?></li>
        </ol>
    </section>
    <section class="content">
        <div class="box box-mytheme">
            <div class="row">
                <div class="col-md-6">
                    <form role="form" method="POST">
                        <div class="box-body">
                            <div class="form-group <?=form_error('menuname') ? 'has-error' : ''?>">
                              <label for="menuname"><?=$this->lang->line('menu_menuname')?></label> <span class="text-red">*</span>
                              <input type="text" class="form-control" value="<?=set_value('menuname', $menu->menuname)?>" id="menuname" name="menuname"/>
                              <?=form_error('menuname')?>
                            </div>
                            <div class="form-group <?=form_error('menulink') ? 'has-error' : ''?>">
                              <label for="menulink"><?=$this->lang->line('menu_menulink')?></label> <span class="text-red">*</span>
                              <input type="text" class="form-control" value="<?=set_value('menulink', $menu->menulink)?>" id="menulink" name="menulink"/>
                              <?=form_error('menulink')?>
                            </div>
                            <div class="form-group <?=form_error('menuicon') ? 'has-error' : ''?>">
                              <label for="menuicon"><?=$this->lang->line('menu_menuicon')?></label> <span class="text-red">*</span>
                              <input type="text" class="form-control" value="<?=set_value('menuicon', $menu->menuicon)?>" id="menuicon" name="menuicon"/>
                              <?=form_error('menuicon')?>
                            </div>
                            <div class="form-group <?=form_error('priority') ? 'has-error' : ''?>">
                              <label for="priority"><?=$this->lang->line('menu_priority')?></label> <span class="text-red">*</span>
                              <input type="text" class="form-control" value="<?=set_value('priority', $menu->priority)?>" id="priority" name="priority"/>
                              <?=form_error('priority')?>
                            </div>
                            <div class="form-group <?=form_error('parentmenuID') ? 'has-error' : ''?>">
                              <label for="parentmenuID"><?=$this->lang->line('menu_parentmenu')?></label> <span class="text-red">*</span>
                              <?php
                                $parentmenuArray['0'] = $this->lang->line('menu_please_select');
                                if(calculate($parentmenus)) {
                                  foreach($parentmenus as $parentmenu) {
                                    $parentmenuArray[$parentmenu->menuID] = $parentmenu->menuname;
                                  }
                                }
                                echo form_dropdown('parentmenuID', $parentmenuArray, set_value('status', $menu->parentmenuID) , 'class="form-control"');
                                form_error('parentmenuID');
                              ?>
                            </div>
                            <div class="form-group <?=form_error('status') ? 'has-error' : ''?>">
                              <label for="status"><?=$this->lang->line('menu_status')?></label> <span class="text-red">*</span>
                              <?php 
                                $statusArray['0'] = $this->lang->line('menu_please_select'); 
                                $statusArray['1'] = $this->lang->line('menu_active'); 
                                $statusArray['2'] = $this->lang->line('menu_disable'); 
                                echo form_dropdown('status', $statusArray, set_value('status', $menu->status) , 'class="form-control"'); ?>
                              <?=form_error('status')?>
                            </div>
                        </div>
                        <div class="box-footer">
                            <button type="submit" class="btn btn-mytheme"><?=$this->lang->line('menu_update_menu')?></button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
</div>
