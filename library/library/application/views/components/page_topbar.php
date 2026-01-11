<header class="main-header">
    <!-- Logo -->
    <a href="<?=base_url('/dashboard')?>" class="logo">
        <!-- mini logo for sidebar mini 50x50 pixels -->
        <span class="logo-mini"><b><?=character_limiter($generalsetting->sitename,3)?></b></span>
        <!-- logo for regular state and mobile devices -->
        <span class="logo-lg"><b><?=$generalsetting->sitename?></b></span>
    </a>
    <!-- Header Navbar: style can be found in header.less -->
    <nav class="navbar navbar-static-top">
        <!-- Sidebar toggle button-->
        <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
            <span class="sr-only">Toggle navigation</span>
        </a>

        <div class="navbar-custom-menu">
            <ul class="nav navbar-nav">
                <?php if($generalsetting->frontend == 1) { ?>
                	<li class="dropdown tasks-menu">
                		<a href="<?=base_url('/')?>" target="_blank" title="View Frontend" aria-expanded="true">
    		              <i class="fa fa-globe"></i>
    		            </a>
                    </li>
                <?php } ?>
                <li class="dropdown tasks-menu">
                  <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                    <?php $language = $this->session->userdata("language"); ?>
                    <img class="languageimage" src="<?=base_url("uploads/language/$language.png")?>" alt="">
                    <span class="label label-danger">4</span>
                  </a>
                  <ul class="dropdown-menu">
                    <li class="header">Please select your language</li>
                    <li>
                      <ul class="menu">
                        <li>
                          <a href="<?=base_url('dashboard/langswitch/arabic')?>">
                            <h3>
                                <img class="languageimage" src="<?=base_url('uploads/language/arabic.png')?>" alt="">
                                Arabic <?=$language=='arabic' ? "<i class='fa fa-check'></i>" : ""?>
                            </h3>
                          </a>
                        </li>
                        <li>
                          <a href="<?=base_url('dashboard/langswitch/bengali')?>">
                            <h3>
                                <img class="languageimage" src="<?=base_url('uploads/language/bengali.png')?>" alt="">
                                Bangla <?=$language=='bengali' ? "<i class='fa fa-check'></i>" : ""?> 
                            </h3>
                          </a>
                        </li>
                        <li>
                          <a href="<?=base_url('dashboard/langswitch/english')?>">
                            <h3>
                                <img class="languageimage" src="<?=base_url('uploads/language/english.png')?>" alt="">
                                English <?=$language=='english' ? "<i class='fa fa-check'></i>" : ""?>
                            </h3>
                          </a>
                        </li>
                        <li>
                          <a href="<?=base_url('dashboard/langswitch/hindi')?>">
                            <h3>
                                <img class="languageimage" src="<?=base_url('uploads/language/hindi.png')?>" alt="">
                                Hindi <?=$language=='hindi' ? "<i class='fa fa-check'></i>" : ""?>
                            </h3>
                          </a>
                        </li>
                        
                      </ul>
                    </li>
                  </ul>
                </li>
                <!-- user Account: style can be found in dropdown.less -->
                <li class="dropdown user user-menu">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <img src="<?=profile_img($this->session->userdata('photo'))?>" class="user-image" alt="user Image">
                      <span class="hidden-xs"><?=$this->session->userdata('name')?></span>
                    </a>
                    <ul class="dropdown-menu">
                        <!-- user image -->
                        <li class="user-header">
                            <img src="<?=profile_img($this->session->userdata('photo'))?>" class="img-circle" alt="user Image">
                            <p><?=$this->session->userdata('name')?> - <?=$this->session->userdata('role')?><small> <?=$this->lang->line('member_since')?> - <?=date('d F Y',strtotime($this->session->userdata('joinningdate')))?></small></p>
                        </li>
                        <!-- Menu Footer-->
                        <li class="user-footer">
                            <div class="pull-left">
                                <a href="<?=base_url('profile/index')?>" class="btn btn-default btn-flat">Profile</a>
                            </div>
                            <div class="pull-right">
                                <a href="<?=base_url('login/logout')?>" class="btn btn-default btn-flat">Sign out</a>
                            </div>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </nav>
</header>