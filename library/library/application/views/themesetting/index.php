<div class="content-wrapper">
    <section class="content-header">
  		<h1><?=$this->lang->line('theme_setting')?></h1>
  		<ol class="breadcrumb">
        	<li><a href="<?=base_url('dashboard/index')?>"><i class="fa fa-dashboard"></i> <?=$this->lang->line('dashboard')?></a></li>
  			<li class="active"><?=$this->lang->line('theme_setting')?></li>
  		</ol>
    </section>
    <section class="content">
		<div class="box box-mytheme">
			<div class="box-body">
				<fieldset class="setting-fieldset">
	                <legend class="setting-legend"><?=$this->lang->line('themesetting_theme_setting')?></legend>
	                <div class="row">
					    
					    <div class="col-sm-3">
			            	<div class="single_theme <?=($generalsetting->settheme== 'mytheme') ? 'single_theme_active' : ''?>" data-theme="mytheme">
				                <div class="theme_thumnail">
				                	<img src="<?=base_url('assets/theme/mytheme.png')?>" alt="" class="img-thumbnail" />
				                </div>
				                <?=($generalsetting->settheme== 'mytheme') ? '<div class="theme_active"><span class="fa fa-check fa-2x"></span></div>' : ''?>
			            	</div>
			            </div>
						<div class="col-sm-3">
			            	<div class="single_theme <?=($generalsetting->settheme== 'blue') ? 'single_theme_active' : ''?>" data-theme="blue">
				                <div class="theme_thumnail">
				                	<img src="<?=base_url('assets/theme/blue.png')?>" alt="" class="img-thumbnail" />
				                </div>
				                <?=($generalsetting->settheme== 'blue') ? '<div class="theme_active"><span class="fa fa-check fa-2x"></span></div>' : ''?>
			            	</div>
			            </div>
			            <div class="col-sm-3">
			            	<div class="single_theme <?=($generalsetting->settheme== 'blue-light') ? 'single_theme_active' : ''?>" data-theme="blue-light">
				                <div class="theme_thumnail">
				                	<img src="<?=base_url('assets/theme/blue-light.png')?>" alt="" class="img-thumbnail" />
				                </div>
				                <?=($generalsetting->settheme== 'blue-light') ? '<div class="theme_active"><span class="fa fa-check fa-2x"></span></div>' : ''?>
			            	</div>
			            </div>
			            <div class="col-sm-3">
			            	<div class="single_theme <?=($generalsetting->settheme== 'yellow') ? 'single_theme_active' : ''?>" data-theme="yellow">
				                <div class="theme_thumnail">
				                	<img src="<?=base_url('assets/theme/yellow.png')?>" alt="" class="img-thumbnail" />
				                </div>
				                <?=($generalsetting->settheme== 'yellow') ? '<div class="theme_active"><span class="fa fa-check fa-2x"></span></div>' : ''?>
			            	</div>
			            </div>
			            <div class="col-sm-3">
			            	<div class="single_theme <?=($generalsetting->settheme== 'yellow-light') ? 'single_theme_active' : ''?>" data-theme="yellow-light">
				                <div class="theme_thumnail">
				                	<img src="<?=base_url('assets/theme/yellow-light.png')?>" alt="" class="img-thumbnail" />
				                </div>
				                <?=($generalsetting->settheme== 'yellow-light') ? '<div class="theme_active"><span class="fa fa-check fa-2x"></span></div>' : ''?>
			            	</div>
			            </div>
			            <div class="col-sm-3">
			            	<div class="single_theme <?=($generalsetting->settheme== 'green') ? 'single_theme_active' : ''?>" data-theme="green">
				                <div class="theme_thumnail">
				                	<img src="<?=base_url('assets/theme/green.png')?>" alt="" class="img-thumbnail" />
				                </div>
				                <?=($generalsetting->settheme== 'green') ? '<div class="theme_active"><span class="fa fa-check fa-2x"></span></div>' : ''?>
			            	</div>
			            </div>
			            <div class="col-sm-3">
			            	<div class="single_theme <?=($generalsetting->settheme== 'green-light') ? 'single_theme_active' : ''?>" data-theme="green-light">
				                <div class="theme_thumnail">
				                	<img src="<?=base_url('assets/theme/green-light.png')?>" alt="" class="img-thumbnail" />
				                </div>
				                <?=($generalsetting->settheme== 'green-light') ? '<div class="theme_active"><span class="fa fa-check fa-2x"></span></div>' : ''?>
			            	</div>
			            </div>
			            <div class="col-sm-3">
			            	<div class="single_theme <?=($generalsetting->settheme== 'purple') ? 'single_theme_active' : ''?>" data-theme="purple">
				                <div class="theme_thumnail">
				                	<img src="<?=base_url('assets/theme/purple.png')?>" alt="" class="img-thumbnail" />
				                </div>
				                <?=($generalsetting->settheme== 'purple') ? '<div class="theme_active"><span class="fa fa-check fa-2x"></span></div>' : ''?>
			            	</div>
			            </div>
			            <div class="col-sm-3">
			            	<div class="single_theme <?=($generalsetting->settheme== 'purple-light') ? 'single_theme_active' : ''?>" data-theme="purple-light">
				                <div class="theme_thumnail">
				                	<img src="<?=base_url('assets/theme/purple-light.png')?>" alt="" class="img-thumbnail" />
				                </div>
				                <?=($generalsetting->settheme== 'purple-light') ? '<div class="theme_active"><span class="fa fa-check fa-2x"></span></div>' : ''?>
			            	</div>
			            </div>
			            <div class="col-sm-3">
			            	<div class="single_theme <?=($generalsetting->settheme== 'red') ? 'single_theme_active' : ''?>" data-theme="red">
				                <div class="theme_thumnail">
				                	<img src="<?=base_url('assets/theme/red.png')?>" alt="" class="img-thumbnail" />
				                </div>
				                <?=($generalsetting->settheme== 'red') ? '<div class="theme_active"><span class="fa fa-check fa-2x"></span></div>' : ''?>
			            	</div>
			            </div>
			            <div class="col-sm-3">
			            	<div class="single_theme <?=($generalsetting->settheme== 'red-light') ? 'single_theme_active' : ''?>" data-theme="red-light">
				                <div class="theme_thumnail">
				                	<img src="<?=base_url('assets/theme/red-light.png')?>" alt="" class="img-thumbnail" />
				                </div>
				                <?=($generalsetting->settheme== 'red-light') ? '<div class="theme_active"><span class="fa fa-check fa-2x"></span></div>' : ''?>
			            	</div>
			            </div>
			            <div class="col-sm-3">
			            	<div class="single_theme <?=($generalsetting->settheme== 'black') ? 'single_theme_active' : ''?>" data-theme="black">
				                <div class="theme_thumnail">
				                	<img src="<?=base_url('assets/theme/black.png')?>" alt="" class="img-thumbnail" />
				                </div>
				                <?=($generalsetting->settheme== 'black') ? '<div class="theme_active"><span class="fa fa-check fa-2x"></span></div>' : ''?>
			            	</div>
			            </div>
			            <div class="col-sm-3">
			            	<div class="single_theme <?=($generalsetting->settheme== 'black-light') ? 'single_theme_active' : ''?>" data-theme="black-light">
				                <div class="theme_thumnail">
				                	<img src="<?=base_url('assets/theme/black-light.png')?>" alt="" class="img-thumbnail" />
				                </div>
				                <?=($generalsetting->settheme== 'black-light') ? '<div class="theme_active"><span class="fa fa-check fa-2x"></span></div>' : ''?>
			            	</div>
			            </div>

					</div>
	            </fieldset>
			</div>
		</div>
    </section>
</div>