<div class="content-wrapper">
    <section class="content-header">
  		<h1><?=$this->lang->line('bookissue')?></h1>
  		<ol class="breadcrumb">
        	<li><a href="<?=base_url('dashboard/index')?>"><i class="fa fa-dashboard"></i><?=$this->lang->line('dashboard')?></a></li>
  			<li><a href="<?=base_url('bookissue/index')?>"><?=$this->lang->line('bookissue')?></a></li>
  			<li class="active"><?=$this->lang->line('edit')?></li>
  		</ol>
    </section>
    <section class="content">
		<div class="box box-mytheme">
			<div class="row">
				<div class="col-md-6">
					<form role="form" method="post" enctype="multipart/form-data">
						<div class="box-body">
							<div class="form-group <?=form_error('memberID') ? 'has-error' : ''?>">
							  	<label for="memberID"><?=$this->lang->line('bookissue_member')?></label> <span class="text-red">*</span>
								<?php 
									$memberArray[0] = $this->lang->line('bookissue_please_select');
									if(calculate($members)) {
									  foreach ($members as $member) {
									    $memberArray[$member->memberID] = $member->name;
									  }
									}
									echo form_dropdown('memberID', $memberArray,set_value('memberID', $bookissue->memberID),'id="memberID" class="form-control"');
								?>
								<?=form_error('memberID')?>
							</div>
							<div class="form-group <?=form_error('bookID') ? 'has-error' : ''?>">
							  	<label for="bookID"><?=$this->lang->line('bookissue_book')?></label> <span class="text-red">*</span>
								<?php 
									$bookArray[0] = $this->lang->line('bookissue_please_select');
									if(calculate($books)) {
									  foreach ($books as $book) {
									    $bookArray[$book->bookID] = $book->name.' - '.$book->codeno;
									  }
									}
									echo form_dropdown('bookID', $bookArray,set_value('bookID', $bookissue->bookID),'id="bookID" class="form-control"');
								?>
								<?=form_error('bookID')?>
							</div>
							<div class="form-group <?=form_error('bookno') ? 'has-error' : ''?>">
							 	<label for="bookno"><?=$this->lang->line('bookissue_book_no')?></label> <span class="text-red">*</span>
							  	<?php 
									$booknoArray[0] = $this->lang->line('bookissue_please_select');
									$booknoArray[$bookissue->bookno]    = $bookissue->bookno;
									if(calculate($bookitems)) {
									  foreach ($bookitems as $bookitem) {
									    $booknoArray[$bookitem->bookno] = $bookitem->bookno;
									  }
									}
									ksort($booknoArray);
									echo form_dropdown('bookno', $booknoArray,set_value('bookno', $bookissue->bookno),'id="bookno" class="form-control"');
								?>
								<?=form_error('bookno')?>
							</div>
							<div class="form-group <?=form_error('issue_date') ? 'has-error' : ''?>">
								<label for="issue_date"><?=$this->lang->line('bookissue_issue_date')?></label> <span class="text-red">*</span>
								<?php $issue_date = date('d-m-Y', strtotime($bookissue->issue_date))?>
							  	<input type="text" class="form-control datepicker" id="issue_date" name="issue_date" value="<?=set_value('issue_date', $issue_date)?>" placeholder="Enter issue date">
							  	<?=form_error('issue_date')?>
							</div>
							<div class="form-group <?=form_error('notes') ? 'has-error' : ''?>">
							  	<label for="notes"><?=$this->lang->line('bookissue_notes')?></label>
							  	<textarea name="notes" id="notes" cols="30" rows="5" class="form-control" placeholder="Enter Notes"><?=set_value('notes', $bookissue->notes)?></textarea>
							  	<?=form_error('notes')?>
							</div>
						</div>
						<div class="box-footer">
							<button type="submit" class="btn btn-mytheme"><?=$this->lang->line('bookissue_update_book_issue')?></button>
						</div>
					</form>
				</div>
			</div>
		</div>
    </section>
</div>