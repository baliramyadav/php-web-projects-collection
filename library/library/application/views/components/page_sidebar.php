<aside class="main-sidebar">
    <section class="sidebar">
        <div class="user-panel">
            <div class="pull-left image">
                <a href="<?=base_url('profile/index')?>">
                    <img src="<?=profile_img($this->session->userdata('photo'))?>" class="img-circle" style="height: 45px; width: 50px" alt="User Image">
                </a>
            </div>
            <div class="pull-left info">
                <p><?=$this->session->userdata('name')?></p>
                <a href="<?=base_url('profile/index')?>"><i class="fa fa-circle text-success"></i> Online</a>
            </div>
        </div>
        <ul class="sidebar-menu" data-widget="tree">
            <li class="header">MAIN NAVIGATION</li>
            <?php if(calculate($sidebarmenus)) { foreach($sidebarmenus as $sidebarmenu) {
                if(isset($sidebarmenu['menulink']) && ($sidebarmenu['menulink']== '#') && !(isset($sidebarmenu['child']) && calculate($sidebarmenu['child']))) { continue; } ?>

                <li class="<?=(isset($sidebarmenu['child']) && calculate($sidebarmenu['child'])) ? menu_treeview_show($sidebarmenu['child'],$activemenu,'treeview active menu-open','treeview') : ''?> <?=($activemenu==$sidebarmenu['menulink']) ? 'active' : ''?>">
                    <a href="<?=base_url($sidebarmenu['menulink'])?>">
                        <i class="<?=$sidebarmenu['menuicon']?>"></i> <span><?=$this->lang->line('menubar_'.$sidebarmenu['menuname'])?></span>
                        <?php if(isset($sidebarmenu['child']) && calculate($sidebarmenu['child'])) { ?>
                            <span class="pull-right-container">
                              <i class="fa fa-angle-left pull-right"></i>
                            </span>
                        <?php } ?>
                    </a>
                    <?php if(isset($sidebarmenu['child']) && calculate($sidebarmenu['child'])) { ?>
                        <ul class="treeview-menu" style="<?=menu_treeview_show($sidebarmenu['child'],$activemenu,'display: block','display: none')?>">
                        <?php foreach($sidebarmenu['child'] as $subsidebarmenu) { ?>
                            <li class="<?=($activemenu==$subsidebarmenu['menulink']) ? 'active' : ''?>"><a href="<?=base_url($subsidebarmenu['menulink'])?>"><i class="<?=$subsidebarmenu['menuicon']?>"></i><?=$this->lang->line('menubar_'.$subsidebarmenu['menuname'])?></a></li>
                        <?php } ?>
                        </ul>
                    <?php } ?>
                </li>
            <?php } } ?>
        </ul>
    </section>
</aside>