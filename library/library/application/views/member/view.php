<div class="content-wrapper">
    <section class="content-header">
        <h1><?=$this->lang->line('member')?></h1>
        <ol class="breadcrumb">
            <li><a href="<?=base_url('dashboard/index')?>"><i class="fa fa-dashboard"></i><?=$this->lang->line('dashboard')?></a></li>
            <li><a href="<?=base_url('member/index')?>"><?=$this->lang->line('member')?></a></li>
            <li class="active"><?=$this->lang->line('view')?></li>
        </ol>
    </section>
    <section class="content">
        <div class="row">
            <div class="col-md-3">
                <div class="box box-mytheme">
                    <div class="box-body box-profile">
                        <img class="profile-user-img img-responsive img-circle" src="<?=profile_img($member->photo)?>" alt="<?=$member->name?> profile picture">
                        <h3 class="profile-username text-center"><?=$member->name?></h3>
                        <p class="text-muted text-center"><?=calculate($role) ? $role->role : ''?></p>
                        <ul class="list-group list-group-unbordered">
                            <li class="list-group-item">
                                <b><?=$this->lang->line('member_gender')?></b> <span class="pull-right"><?=$member->gender?></span>
                            </li>
                            <li class="list-group-item">
                                <b><?=$this->lang->line('member_phone')?></b> <span class="pull-right"><?=$member->phone?></span>
                            </li>
                            <li class="list-group-item">
                                <b><?=$this->lang->line('member_email')?></b> <span class="pull-right"><?=$member->email?></span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-md-9">
                <div class="nav-tabs-custom">
                    <ul class="nav nav-tabs">
                        <li class="active"><a href="#profile" data-toggle="tab"><?=$this->lang->line('member_profile')?></a></li>
                        <li><a href="#bookissue" data-toggle="tab"><?=$this->lang->line('member_book_issue')?></a></li>
                    </ul>
                    <div class="tab-content">
                        <div class="active tab-pane" id="profile">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="profile_view_item">
                                        <p><b><?=$this->lang->line('member_blood_group')?></b>: <?=$member->bloodgroup?></p>
                                    </div>
                                    <div class="profile_view_item">
                                        <p><b><?=$this->lang->line('member_religion')?></b>: <?=ucfirst($member->religion)?></p>
                                    </div>
                                    <div class="profile_view_item">
                                        <p><b><?=$this->lang->line('member_joinning_date')?></b>: <?=app_date($member->joinningdate)?></p>
                                    </div>
                                    <div class="profile_view_item">
                                        <p><b><?=$this->lang->line('member_date_of_birth')?></b>: <?=app_date($member->dateofbirth)?></p>
                                    </div>
                                    <div class="profile_view_item">
                                        <p><b><?=$this->lang->line('member_address')?></b>: <?=$member->address?></p>
                                    </div>
                                    <div class="profile_view_item">
                                        <p><b><?=$this->lang->line('member_role')?></b>: <?=calculate($role) ? $role->role : ''?></p>
                                    </div>
                                    <div class="profile_view_item">
                                        <p><b><?=$this->lang->line('member_username')?></b>: <?=$member->username?></p>
                                    </div>
                                    <div class="profile_view_item">
                                        <p><b><?=$this->lang->line('member_status')?></b>: <span class="text-danger text-bold"><?=($member->status == 0) ? $this->lang->line('member_new') : (($member->status == 1) ? $this->lang->line('member_active') : $this->lang->line('member_block')) ?></span></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane" id="bookissue">
                            <div class="row">
                                <div class="col-md-12">
                                    <div id="hide-table">
                                        <table id="example1" class="table table-bordered table-striped">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th><?=$this->lang->line('member_category')?></th>
                                                    <th><?=$this->lang->line('member_book')?></th>
                                                    <th><?=$this->lang->line('member_book_no')?></th>
                                                    <th><?=$this->lang->line('member_status')?></th>
                                                    <?php if(permissionChecker('bookissue_view') || permissionChecker('bookissue_edit') || permissionChecker('bookissue_delete')) { ?>
                                                        <th><?=$this->lang->line('member_action')?></th>
                                                    <?php } ?>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php if(calculate($bookissues)) { $i=0; foreach($bookissues as $bookissue) { $i++; ?>
                                                <tr>
                                                    <td data-title="#"><?=$i?></td>
                                                    <td data-title="<?=$this->lang->line('member_category')?>"><?=isset($bookcategory[$bookissue->bookcategoryID]) ? $bookcategory[$bookissue->bookcategoryID] : 'Uncategorized'?></td>
                                                    <td data-title="<?=$this->lang->line('member_book')?>"><?=isset($book[$bookissue->bookID]) ? $book[$bookissue->bookID] : ''?></td>
                                                    <td data-title="<?=$this->lang->line('member_book_no')?>"><?=$bookissue->bookno?></td>
                                                    <td data-title="<?=$this->lang->line('member_status')?>">
                                                        <span class="text-bold text-success">
                                                            <?php 
                                                                if($bookissue->status == 0) {
                                                                    echo $this->lang->line('member_issued');              
                                                                } elseif ($bookissue->status == 1) {
                                                                    echo $this->lang->line('member_return');              
                                                                } elseif ($bookissue->status == 2) {
                                                                    echo $this->lang->line('member_lost');
                                                                }
                                                            ?>
                                                        </span>
                                                    </td>
                                                    
                                                    <?php if(permissionChecker('bookissue_view') || permissionChecker('bookissue_edit') || permissionChecker('bookissue_delete')) { ?>
                                                    <td data-title="<?=$this->lang->line('member_action')?>">
                                                        <?=btn_view('bookissue/view/'.$bookissue->bookissueID,$this->lang->line('member_view')); ?>
                                                        <?php if($bookissue->status == 0) { ?>
                                                            <a href="<?=base_url('bookissue/renewandreturn/'.$bookissue->bookissueID)?>" class="btn btn-info btn-xs mrg" data-placement="auto" data-toggle="tooltip" data-original-title="<?=$this->lang->line('member_renew_or_return')?>"><i class="fa fa-retweet"></i></a>
                                                        <?php } ?>
                                                    </td>
                                                    <?php } ?>
                                                </tr>
                                                <?php } } ?>
                                            </tbody>
                                            <tfoot>
                                            <tr>
                                                <th>#</th>
                                                <th><?=$this->lang->line('member_category')?></th>
                                                <th><?=$this->lang->line('member_book')?></th>
                                                <th><?=$this->lang->line('member_book_no')?></th>
                                                <th><?=$this->lang->line('member_status')?></th>
                                                <?php if(permissionChecker('bookissue_view') || permissionChecker('bookissue_edit') || permissionChecker('bookissue_delete')) { ?>
                                                    <th><?=$this->lang->line('member_action')?></th>
                                                <?php } ?>
                                            </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
