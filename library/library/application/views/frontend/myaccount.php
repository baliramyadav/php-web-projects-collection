<section class="main-slider">
    <div class="container">
        <div class="row">

            <?php $this->load->view('_layouts/myaccount-sidebar');?>

            <div class="col-sm-9">
                <div class="user-profile">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="user-profile-image">
                                <img class="img-thumbnail" src="<?=profile_img($member->photo)?>" alt="<?=$member->name?> profile picture">
                                <h3><?=$member->name?></h3>
                                <p class="text-muted text-center"><?=calculate($role) ? $role->role : ''?></p>
                            </div>
                        </div>
                        <div class="col-md-6">
	                        <div class="profile_view_item">
	                            <p><b><?=$this->lang->line('frontend_email')?></b>: <?=$member->email?></p>
	                        </div>
	                        <div class="profile_view_item">
	                            <p><b><?=$this->lang->line('frontend_gender')?></b>: <?=$member->gender?></p>
	                        </div>
	                        <div class="profile_view_item">
	                            <p><b><?=$this->lang->line('frontend_blood_group')?></b>: <?=$member->bloodgroup?></p>
	                        </div>
	                        <div class="profile_view_item">
	                            <p><b><?=$this->lang->line('frontend_religion')?></b>: <?=ucfirst($member->religion)?></p>
	                        </div>
	                        <div class="profile_view_item">
	                            <p><b><?=$this->lang->line('frontend_joinning_date')?></b>: <?=app_date($member->joinningdate)?></p>
	                        </div>
	                        <div class="profile_view_item">
	                            <p><b><?=$this->lang->line('frontend_date_of_birth')?></b>: <?=app_date($member->dateofbirth)?></p>
	                        </div>
                        </div>
                        <div class="col-md-6">
	                        <div class="profile_view_item">
	                            <p><b><?=$this->lang->line('frontend_phone')?></b>: <?=$member->phone?></p>
	                        </div>
	                        <div class="profile_view_item">
	                            <p><b><?=$this->lang->line('frontend_address')?></b>: <?=$member->address?></p>
	                        </div>
	                        <div class="profile_view_item">
	                            <p><b><?=$this->lang->line('frontend_role')?></b>: <?=calculate($role) ? $role->role : ''?></p>
	                        </div>
	                        <div class="profile_view_item">
	                            <p><b><?=$this->lang->line('frontend_username')?></b>: <?=$member->username?></p>
	                        </div>
	                        <div class="profile_view_item">
	                            <p><b><?=$this->lang->line('frontend_status')?></b>: <span class="text-danger text-bold"><?=($member->status == 1) ? $this->lang->line('frontend_active') : $this->lang->line('frontend_block')?></span></p>
	                        </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
