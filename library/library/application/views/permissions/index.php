<div class="content-wrapper">
    <section class="content-header">
		<h1><?=$this->lang->line('permissions')?></h1>
		<ol class="breadcrumb">
            <li><a href="<?=base_url('dashboard/index')?>"><i class="fa fa-dashboard"></i><?=$this->lang->line('dashboard')?></a></li>
			<li class="active"><?=$this->lang->line('permissions')?></li>
		</ol>
    </section>
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="nav-tabs-custom">
                    <ul class="nav nav-tabs">
                    <?php if(calculate($roles)) { $i=0; foreach($roles as $role) { $i++; ?>
                        <li <?=($urlroleID == $role->roleID) ? 'class="active"' : (($i==1 && $urlroleID == 0) ? 'class="active"' : '')?>><a href="#role<?=$role->roleID?>" data-toggle="tab" aria-expanded="false"><?=$role->role?></a></li>
                    <?php } } ?>
                    </ul>
                    <div class="tab-content">
                        <?php if(calculate($roles)) { $i=0; foreach($roles as $role) { $i++; ?>
                            <div class="tab-pane <?=($urlroleID==$role->roleID) ? 'active' : ($i==1  && $urlroleID == 0) ? 'active' : ''?>" id="role<?=$role->roleID?>">
                                <form method="post" action="<?=base_url('/permissions/save')?>">
                                    <input type="hidden" name="permissionsroleID" value="<?=$role->roleID?>">
                                    <div id="hide-table">
                                        <table class="table table-bordered table-striped mainpermission">
                                            <thead>
                                                <tr>
                                                    <th><?=$this->lang->line('permissions_slno')?></th>
                                                    <th><?=$this->lang->line('permissions_module_name')?></th>
                                                    <th><?=$this->lang->line('permissions_add')?></th>
                                                    <th><?=$this->lang->line('permissions_edit')?></th>
                                                    <th><?=$this->lang->line('permissions_delete')?></th>
                                                    <th><?=$this->lang->line('permissions_view')?></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php $j=0; if(calculate($permissionsModuleArray)) { foreach($permissionsModuleArray as $permission) { $j++; ?>
                                                    <tr>
                                                        <td data-title="<?=$this->lang->line('permissions_slno')?>"> 
                                                            <input type="checkbox" id="<?=$permission->name?>_<?=$role->roleID?>" name="<?=$permission->name?>" value="<?=$permission->permissionlogID?>"  <?=isset($permissions[$role->roleID][$permission->permissionlogID]) ? 'checked' : ''?> class="mainmodule"/> 
                                                        </td>
                                                        <td data-title="<?=$this->lang->line('permissions_module_name')?>"><?=ucfirst($permission->name)?></td>
                                                        <td data-title="<?=$this->lang->line('permissions_add')?>">
                                                        <?php 
                                                            $permissionadd = $permission->name.'_add';
                                                            if(isset($permissionlogsArray[$permissionadd])) { ?>
                                                                <input type="checkbox" id="<?=$permissionadd?>_<?=$role->roleID?>" name="<?=$permissionadd?>" value="<?=$permissionlogsArray[$permissionadd]?>" <?=isset($permissions[$role->roleID][$permissionlogsArray[$permissionadd]]) ? 'checked' : ''?> />
                                                        <?php } else {
                                                            echo "&nbsp;";
                                                        } ?>
                                                        </td>
                                                        <td data-title="<?=$this->lang->line('permissions_edit')?>">
                                                        <?php 
                                                            $permissionedit = $permission->name.'_edit';
                                                            if(isset($permissionlogsArray[$permissionedit])) { ?>
                                                                <input type="checkbox" id="<?=$permissionedit?>_<?=$role->roleID?>" name="<?=$permissionedit?>" value="<?=$permissionlogsArray[$permissionedit]?>" <?=isset($permissions[$role->roleID][$permissionlogsArray[$permissionedit]]) ? 'checked' : ''?> />
                                                        <?php } else {
                                                            echo "&nbsp;";
                                                        } ?>
                                                        </td>
                                                        <td data-title="<?=$this->lang->line('permissions_delete')?>">
                                                        <?php 
                                                            $permissiondelete = $permission->name.'_delete';
                                                            if(isset($permissionlogsArray[$permissiondelete])) { ?>
                                                                <input type="checkbox" id="<?=$permissiondelete?>_<?=$role->roleID?>" name="<?=$permissiondelete?>" value="<?=$permissionlogsArray[$permissiondelete]?>" <?=isset($permissions[$role->roleID][$permissionlogsArray[$permissiondelete]]) ? 'checked' : ''?> />
                                                        <?php } else {
                                                            echo "&nbsp;";
                                                        } ?>
                                                        </td>
                                                        <td data-title="<?=$this->lang->line('permissions_view')?>">
                                                        <?php 
                                                            $permissionview = $permission->name.'_view';
                                                            if(isset($permissionlogsArray[$permissionview])) { ?>
                                                                <input type="checkbox" id="<?=$permissionview?>_<?=$role->roleID?>" name="<?=$permissionview?>" value="<?=$permissionlogsArray[$permissionview]?>" <?=isset($permissions[$role->roleID][$permissionlogsArray[$permissionview]]) ? 'checked' : ''?> />
                                                        <?php } else {
                                                            echo "&nbsp;";
                                                        } ?>
                                                        </td>
                                                    </tr>
                                                <?php } } ?>
                                            </tbody>
                                            <tfoot>
                                                <tr>
                                                    <th><?=$this->lang->line('permissions_slno')?></th>
                                                    <th><?=$this->lang->line('permissions_module_name')?></th>
                                                    <th><?=$this->lang->line('permissions_add')?></th>
                                                    <th><?=$this->lang->line('permissions_edit')?></th>
                                                    <th><?=$this->lang->line('permissions_delete')?></th>
                                                    <th><?=$this->lang->line('permissions_view')?></th>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                    <button class="btn btn-large btn-mytheme"><?=$this->lang->line('permissions_save_permission_for')?> <span class="text-bold bg-black" style="padding: 2px 5px; border-radius: 5px;"><?=$role->role?></span></button>
                                </form>
                            </div>
                        <?php } } ?>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>