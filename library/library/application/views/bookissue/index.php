<div class="content-wrapper">
    <section class="content-header">
        <h1><?=$this->lang->line('bookissue')?></h1>
        <ol class="breadcrumb">
            <li><a href="<?=base_url('dashboard/index')?>"><i class="fa fa-dashboard"></i><?=$this->lang->line('dashboard')?></a></li>
            <li class="active"><?=$this->lang->line('bookissue')?></li>
        </ol>
    </section>
    <section class="content">
        <div class="box box-mytheme">
            <div class="row">
                <div class="bookissuesearchbox">
                    <?php if(permissionChecker('bookissue_add')) { ?>
                    <div class="col-sm-2 col-sm-offset-3">
                        <div class="box-header">
                            <a href="<?=base_url('bookissue/add')?>" class="btn btn-inline btn-mytheme btn-md"><i class="fa fa-plus"></i>  <?=$this->lang->line('bookissue_add_book_issue')?></a>
                        </div>
                    </div>
                    <?php } ?>
                    <div class="col-sm-4 <?=!(permissionChecker('bookissue_add')) ? 'col-sm-offset-4' : ''?>">
                        <div class="box-body">
                            <form method="POST" action="<?=base_url('bookissue/index')?>">
                                <div class="input-group">
                                    <input type="text" class="form-control" value="<?=set_value('memberID', $memberID)?>" name="memberID" placeholder="Filter By Member ID">
                                    <div class="input-group-btn">
                                        <button type="submit" class="btn btn-default"><i class="fa fa-search-plus" aria-hidden="true"></i> <?=$this->lang->line('bookissue_search')?></button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="box box-mytheme">
            <div class="box-body">
                <div id="hide-table">
                    <table id="example1" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th><?=$this->lang->line('bookissue_slno')?></th>
                                <th><?=$this->lang->line('bookissue_memberID')?></th>
                                <th><?=$this->lang->line('bookissue_member')?></th>
                                <th><?=$this->lang->line('bookissue_category')?></th>
                                <th><?=$this->lang->line('bookissue_book')?></th>
                                <th><?=$this->lang->line('bookissue_book_no')?></th>
                                <th><?=$this->lang->line('bookissue_status')?></th>
                                <?php if(permissionChecker('bookissue_view') || permissionChecker('bookissue_edit') || permissionChecker('bookissue_delete')) { ?>
                                    <th><?=$this->lang->line('bookissue_action')?></th>
                                <?php } ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(calculate($bookissues)) { $i=0; foreach($bookissues as $bookissue) { $i++; ?>
                            <tr>
                                <td data-title="<?=$this->lang->line('bookissue_slno')?>"><?=$i?></td>
                                <td data-title="<?=$this->lang->line('bookissue_memberID')?>"><?=$bookissue->memberID?></td>
                                <td data-title="<?=$this->lang->line('bookissue_member')?>"><?=isset($members[$bookissue->memberID]) ? $members[$bookissue->memberID] : ''?></td>
                                <td data-title="<?=$this->lang->line('bookissue_category')?>"><?=isset($bookcategory[$bookissue->bookcategoryID]) ? $bookcategory[$bookissue->bookcategoryID] : 'Uncategorized'?></td>
                                <td data-title="<?=$this->lang->line('bookissue_book')?>"><?=isset($book[$bookissue->bookID]) ? $book[$bookissue->bookID] : ''?></td>
                                <td data-title="<?=$this->lang->line('bookissue_book_no')?>"><?=$bookissue->bookno?></td>
                                <td data-title="<?=$this->lang->line('bookissue_status')?>">
                                    <span class="text-bold text-success">
                                        <?php 
                                            if($bookissue->status == 0) {
                                                echo $this->lang->line('bookissue_issued');              
                                            } elseif ($bookissue->status == 1) {
                                                echo $this->lang->line('bookissue_return');              
                                            } elseif ($bookissue->status == 2) {
                                                echo $this->lang->line('bookissue_lost');
                                            }
                                        ?>
                                    </span>
                                </td>
                                
                                <?php if(permissionChecker('bookissue_view') || permissionChecker('bookissue_edit') || permissionChecker('bookissue_delete')) { ?>
                                <td data-title="<?=$this->lang->line('bookissue_action')?>">
                                    <?=btn_view('bookissue/view/'.$bookissue->bookissueID,$this->lang->line('bookissue_view')); ?>
                                    <?php if(($bookissue->status == 0) && ($bookissue->deleted_at == 0) && ($bookissue->renewed == 1)) { 
                                        echo btn_edit('bookissue/edit/'.$bookissue->bookissueID, $this->lang->line('bookissue_edit')). " ";
                                        echo btn_delete('bookissue/delete/'.$bookissue->bookissueID, $this->lang->line('bookissue_delete'));
                                    } ?>
                            
                                    <?php if($bookissue->status == 0) { ?>
                                        <a href="<?=base_url('bookissue/renewandreturn/'.$bookissue->bookissueID)?>" class="btn btn-info btn-xs mrg" data-placement="auto" data-toggle="tooltip" data-original-title="<?=$this->lang->line('bookissue_renew_or_return')?>"><i class="fa fa-retweet"></i></a>
                                    <?php } ?>
    
                                    <?php if(permissionChecker('bookissue_add') && ($bookissue->paidstatus != 2) && ($bookissue->fineamount > 0)) { ?>
                                        <span data-toggle="tooltip" data-original-title="<?=$this->lang->line('bookissue_payment')?>"><button class="btn btn-mytheme btn-xs mrg paymentamount" data-bookissueid="<?=$bookissue->bookissueID?>" data-placement="auto" data-toggle="modal" data-target="#paymentmodal"><i class="fa fa-money"></i></button></span>
                                    <?php } ?>
                                    
                                </td>
                                <?php } ?>
                            </tr>
                            <?php } } ?>
                        </tbody>
                        <tfoot>
                        <tr>
                            <th><?=$this->lang->line('bookissue_slno')?></th>
                            <th><?=$this->lang->line('bookissue_memberID')?></th>
                            <th><?=$this->lang->line('bookissue_member')?></th>
                            <th><?=$this->lang->line('bookissue_category')?></th>
                            <th><?=$this->lang->line('bookissue_book')?></th>
                            <th><?=$this->lang->line('bookissue_book_no')?></th>
                            <th><?=$this->lang->line('bookissue_status')?></th>
                            <?php if(permissionChecker('bookissue_view') || permissionChecker('bookissue_edit') || permissionChecker('bookissue_delete')) { ?>
                                <th><?=$this->lang->line('bookissue_action')?></th>
                            <?php } ?>
                        </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </section>
</div>
<?php if(permissionChecker('bookissue_add')) { ?>
    <div class="modal fade" id="paymentmodal" tabindex="-1" role="dialog" aria-labelledby="paymentmodaltitle">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form method="POST" id="paymentform">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="paymentmodaltitle"><?=$this->lang->line('bookissue_add_payment')?></h4>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group" id="paymentamounterrorDiv">
                                    <label for="paymentamount"><?=$this->lang->line('bookissue_payment_amount')?></label> <span class="text-red">*</span>
                                    <input type="text" class="form-control" data-paymentamount="0" id="paymentamount" name="paymentamount">
                                    <span class="help-block totalfineamount" style="color: #a94442"></span>
                                    <span id="paymentamounterror"></span>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group" id="discountamounterrorDiv">
                                    <label for="discountamount"><?=$this->lang->line('bookissue_discount_amount')?></label> <span class="text-red">*</span>
                                    <input type="text" class="form-control" id="discountamount" name="discountamount">
                                    <span id="discountamounterror"></span>
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <div class="form-group" id="noteserrorDiv">
                                    <label for="notes"><?=$this->lang->line('bookissue_notes')?></label>
                                    <textarea class="form-control" name="notes" id="notes" cols="30" rows="3"></textarea>
                                    <span id="noteserror"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal"><?=$this->lang->line('bookissue_close')?></button>
                        <button type="submite" class="btn btn-mytheme submitpaymentamount"><?=$this->lang->line('bookissue_submit')?></button>
                    </div>
                </form>
            </div>
        </div>
    </div>
<?php } ?>