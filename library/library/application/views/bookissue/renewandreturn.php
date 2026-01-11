<div class="content-wrapper">
    <section class="content-header">
  		<h1><?=$this->lang->line('bookissue')?></h1>
  		<ol class="breadcrumb">
        	<li><a href="<?=base_url('dashboard/index')?>"><i class="fa fa-dashboard"></i><?=$this->lang->line('dashboard')?></a></li>
  			<li><a href="<?=base_url('bookissue/index')?>"><?=$this->lang->line('bookissue')?></a></li>
  			<li class="active"><?=$this->lang->line('return_and_renew')?></li>
  		</ol>
    </section>
    <section class="content">
		<div class="row">
			<div class="col-md-3">
				<div class="box box-mytheme">
					<form role="form" method="post" enctype="multipart/form-data">
						<div class="box-body">
							<div class="form-group <?=form_error('bookstatusID') ? 'has-error' : ''?>">
							  	<label for="bookstatusID"><?=$this->lang->line('bookissue_book_status')?></label> <span class="text-red">*</span>
								<?php 
									$statusArray[0] = $this->lang->line('bookissue_please_select');
									$statusArray[1] = $this->lang->line('bookissue_renew');
									$statusArray[2] = $this->lang->line('bookissue_return');
									$statusArray[3] = $this->lang->line('bookissue_lost');
									echo form_dropdown('bookstatusID', $statusArray, set_value('bookstatusID'),' id="bookstatusID" class="form-control"');
								?>
								<?=form_error('bookstatusID')?>
							</div>
							<div class="form-group <?=form_error('fineamount') ? 'has-error' : ''?>">
								<label for="fineamount"><?=$this->lang->line('bookissue_fine_amount')?></label> <span class="text-red">*</span>
							  	<input type="text" class="form-control" id="fineamount" name="fineamount" value="<?=set_value('fineamount')?>" placeholder="Enter fine amount">
							  	<span class="help-block lostbookerror" style="color: #a94442"></span>
							  	<?=form_error('fineamount')?>
							</div>
							<div class="form-group <?=form_error('notes') ? 'has-error' : ''?>">
							  	<label for="notes"><?=$this->lang->line('bookissue_notes')?></label>
							  	<textarea name="notes"  id="notes" cols="30" rows="5" class="form-control" placeholder="Enter Notes"><?=set_value('notes')?></textarea>
							  	<?=form_error('notes')?>
							</div>
						</div>
						<div class="box-footer">
							<button type="submit" class="btn btn-mytheme"><?=$this->lang->line('bookissue_add_fine_and_return')?></button>
						</div>
					</form>
				</div>
				<div class="box box-mytheme">
                    <div class="box-body box-profile">
                        <img class="profile-user-img img-responsive img-circle" src="<?=profile_img($member->photo)?>" alt="<?=$member->name?> profile picture">
                        <h3 class="profile-username text-center"><?=$member->name?></h3>
                        <p class="text-muted text-center"><?=calculate($role) ? $role->role : ''?></p>
                        <ul class="list-group list-group-unbordered">
                            <li class="list-group-item">
                                <b><?=$this->lang->line('bookissue_gender')?></b> <span class="pull-right"><?=$member->gender?></span>
                            </li>
                            <li class="list-group-item">
                                <b><?=$this->lang->line('bookissue_phone')?></b> <span class="pull-right"><?=$member->phone?></span>
                            </li>
                            <li class="list-group-item">
                                <b><?=$this->lang->line('bookissue_email')?></b> <span class="pull-right"><?=$member->email?></span>
                            </li>
                        </ul>
                    </div>
                </div>
			</div>

			<div class="col-md-9">
                <div class="nav-tabs-custom">
                    <ul class="nav nav-tabs">
                    	<?php if(calculate($finehistory)) { ?>
                        	<li class="active"><a href="#renewhistory" data-toggle="tab"><?=$this->lang->line('bookissue_renew_history')?></a></li>
                       	<?php } ?>
                        <li class="<?=calculate($finehistory) ? '' : 'active'?>"><a href="#bookissue" data-toggle="tab"><?=$this->lang->line('bookissue_book_issue')?></a></li>
                        <li><a href="#paymentinformation" data-toggle="tab"><?=$this->lang->line('bookissue_payment_information')?></a></li>
                    </ul>
                    <div class="tab-content">
                    	<?php if(calculate($finehistory)) { ?>
	                        <div class="active tab-pane" id="renewhistory">
	                        	<div class="row">
	                        		<div class="col-md-12">
	                        			<h3 style="margin-top: 0px"><?=$this->lang->line('bookissue_book_renew_history')?> </h3>
										<div id="hide-table">
						                    <table id="example1" class="table table-bordered table-striped">
						                        <thead>
						                            <tr>
						                                <th><?=$this->lang->line('bookissue_slno')?></th>
						                                <th><?=$this->lang->line('bookissue_book_status')?></th>
						                                <th><?=$this->lang->line('bookissue_renewed')?></th>
						                                <th><?=$this->lang->line('bookissue_fine_amount')?></th>
						                            </tr>
						                        </thead>
						                        <tbody>
						                            <?php if(calculate($finehistory)) { $i=0; foreach($finehistory as $fine) { $i++; ?>
						                                <tr>
						                                    <td data-title="<?=$this->lang->line('bookissue_slno')?>"><?=$i?></td>
						                                    <td data-title="<?=$this->lang->line('bookissue_book_status')?>">
						                                    	<?php 
						                                    		if($fine->bookstatusID == 0) {
						                                    			echo $this->lang->line('bookissue_issued');
						                                    		} else if($fine->bookstatusID == 1) {
						                                    			echo $this->lang->line('bookissue_return');
						                                    		} else if($fine->bookstatusID == 2) {
						                                    			echo $this->lang->line('bookissue_lost');
						                                    		}
						                                    	?>		
						                                    </td>
						                                    <td data-title="<?=$this->lang->line('bookissue_renewed')?>"><?=$fine->renewed?></td>
						                                    <td data-title="<?=$this->lang->line('bookissue_fine_amount')?>"><?=$fine->fineamount?></td>
						                                </tr>
						                            <?php } } ?>
						                        </tbody>
						                        <tfoot>
						                            <tr>
						                                <th><?=$this->lang->line('bookissue_slno')?></th>
						                                <th><?=$this->lang->line('bookissue_book_status')?></th>
						                                <th><?=$this->lang->line('bookissue_renewed')?></th>
						                                <th><?=$this->lang->line('bookissue_fine_amount')?></th>
						                            </tr>
						                        </tfoot>
						                    </table>
						                </div>
	                        		</div>
	                        	</div>
	                        </div>
                    	<?php } ?>
                        <div class="<?=calculate($finehistory) ? '' : 'active'?> tab-pane" id="bookissue">
                            <div class="row">
                                <div class="col-md-12">
                                    <h3 style="margin-top: 10px"><?=$this->lang->line('bookissue_book_issue_information')?> :</h3>
                                    <div class="profile_view_item">
                                        <p><b><?=$this->lang->line('bookissue_book_name')?></b> : <?=calculate($book) ? $book->name : ''?></p>
                                    </div>
                                    <div class="profile_view_item">
                                        <p><b><?=$this->lang->line('bookissue_book_code_no')?></b> : <?=calculate($book) ? $book->codeno : ''?></p>
                                    </div>
                                    <div class="profile_view_item">
                                        <p><b><?=$this->lang->line('bookissue_book_no')?></b> : <?=$bookissue->bookno?></p>
                                    </div>
                                    <div class="profile_view_item">
                                        <p><b><?=$this->lang->line('bookissue_book_status')?></b>:
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
                                        </p>
                                    </div>
                                    <div class="profile_view_item">
                                        <p><b><?=$this->lang->line('bookissue_issue_date')?></b>: <?=app_date($bookissue->issue_date)?></p>
                                    </div>
                                    <div class="profile_view_item">
                                        <p><b><?=$this->lang->line('bookissue_expire_date')?></b>: <?=app_date($bookissue->expire_date)?></p>
                                    </div>
                                    <div class="profile_view_item">
                                        <p><b><?=$this->lang->line('bookissue_renewed_time')?></b>: <?=$bookissue->renewed." / ".$bookissue->max_renewed_limit?></p>
                                    </div>
                                    <div class="profile_view_item">
                                        <p><b><?=$this->lang->line('bookissue_fine_per_day')?></b>: <?=$bookissue->book_fine_per_day?></p>
                                    </div>
                                    <div class="profile_view_item">
                                        <p><b><?=$this->lang->line('bookissue_fine_amount')?></b>: <?=number_format($bookissue->fineamount, 2)?></p>
                                    </div>
                                    <div class="profile_view_item">
                                        <p><b><?=$this->lang->line('bookissue_discount_amount')?></b>: <?=number_format($bookissue->discountamount, 2)?></p>
                                    </div>
                                    <div class="profile_view_item">
                                        <p><b><?=$this->lang->line('bookissue_payment_amount')?></b>: <?=number_format($bookissue->paymentamount, 2)?></p>
                                    </div>
                                    <div class="profile_view_item">
                                        <p><b><?=$this->lang->line('bookissue_due_amount')?></b>: 
                                            <?php
                                                $totaldueamount = $bookissue->fineamount - ($bookissue->paymentamount + $bookissue->discountamount);
                                                echo number_format($totaldueamount, 2);
                                            ?>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="tab-pane" id="paymentinformation">
                            <div class="row">
                                <div class="col-md-12">
                                    <h3 style="margin-top: 0px"><?=$this->lang->line('bookissue_book_issue_payment_information')?> </h3>
                                    <?php if(calculate($paymentanddiscounts)) { ?>
                                        <div id="hide-table">
                                            <table id="example1" class="table table-bordered table-striped">
                                                <thead>
                                                    <tr>
                                                        <th><?=$this->lang->line('bookissue_slno')?></th>
                                                        <th><?=$this->lang->line('bookissue_date')?></th>
                                                        <th><?=$this->lang->line('bookissue_payment_amount')?></th>
                                                        <th><?=$this->lang->line('bookissue_discount_amount')?></th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php $i=0; foreach($paymentanddiscounts as $paymentanddiscount) { $i++; ?>
                                                        <tr>
                                                            <td data-title="<?=$this->lang->line('bookissue_slno')?>"><?=$i?></td>
                                                            <td data-title="<?=$this->lang->line('bookissue_date')?>">
                                                                <?=app_date($paymentanddiscount->create_date)?>
                                                            </td>
                                                            <td data-title="<?=$this->lang->line('bookissue_payment_amount')?>"><?=number_format($paymentanddiscount->paymentamount, 2)?></td>
                                                            <td data-title="<?=$this->lang->line('bookissue_discount_amount')?>"><?=number_format($paymentanddiscount->discountamount, 2)?></td>
                                                        </tr>
                                                        <?php if($paymentanddiscount->notes) { ?>
                                                        <tr>
                                                            <td class="text-bold"><?=$this->lang->line('bookissue_notes')?></td>
                                                            <td colspan="3" data-title="<?=$this->lang->line('bookissue_notes')?>"><?=$paymentanddiscount->notes?></td>
                                                        </tr>
                                                    <?php } } ?>
                                                </tbody>
                                                <tfoot>
                                                    <tr>
                                                        <th><?=$this->lang->line('bookissue_slno')?></th>
                                                        <th><?=$this->lang->line('bookissue_date')?></th>
                                                        <th><?=$this->lang->line('bookissue_payment_amount')?></th>
                                                        <th><?=$this->lang->line('bookissue_discount_amount')?></th>
                                                    </tr>
                                                </tfoot>
                                            </table>
                                        </div>
                                    <?php } else { ?>
                                        <p class="text-danger"><?=$this->lang->line('bookissue_payment_and_discount_not_found')?></p>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
		</div>
    </section>
</div>

<script type="text/javascript">
    var bookissueID  = "<?=$bookissue->bookissueID?>";
</script>