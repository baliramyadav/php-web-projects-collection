<div class="content-wrapper">
    <section class="content-header">
  		<h1><?=$this->lang->line('expense')?></h1>
  		<ol class="breadcrumb">
        	<li><a href="<?=base_url('dashboard/index')?>"><i class="fa fa-dashboard"></i><?=$this->lang->line('dashboard')?></a></li>
  			<li><a href="<?=base_url('expense/index')?>"><?=$this->lang->line('expense')?></a></li>
  			<li class="active"><?=$this->lang->line('add')?></li>
  		</ol>
    </section>
    <section class="content">
		<div class="box box-mytheme">
			<div class="row">
				<div class="col-md-6">
					<form role="form" method="post" enctype="multipart/form-data" autocomplete="off">
						<div class="box-body">
							<div class="form-group <?=form_error('name') ? 'has-error' : ''?>">
							 	<label for="name"><?=$this->lang->line('expense_name')?></label> <span class="text-red">*</span>
							  	<input type="text" class="form-control" id="name" name="name" value="<?=set_value('name')?>" placeholder="Enter Name">
							  	<?=form_error('name')?>
							</div>
							<div class="form-group <?=form_error('date') ? 'has-error' : ''?>">
								<label for="date"><?=$this->lang->line('expense_date')?></label> <span class="text-red">*</span>
							  	<input type="text" class="form-control datepicker" id="date" name="date" value="<?=set_value('date')?>" placeholder="Enter Date">
							  	<?=form_error('date')?>
							</div>
							<div class="form-group <?=form_error('amount') ? 'has-error' : ''?>">
							  	<label for="amount"><?=$this->lang->line('expense_amount')?></label> <span class="text-red">*</span>
							  	<input type="text" class="form-control" id="amount" name="amount" value="<?=set_value('amount')?>" placeholder="Enter Amount">
							  	<?=form_error('amount')?>
							</div>
							<div class="form-group <?=form_error('file') ? 'has-error' : ''?>">
						        <label for="file"><?=$this->lang->line("expense_file")?></label>
						        <div class="input-group image-preview">
						            <input type="text" class="form-control image-preview-filename" disabled="disabled">
						            <span class="input-group-btn">
						                <button type="button" class="btn btn-default image-preview-clear" style="display:none;">
						                    <span class="fa fa-remove"></span><?=$this->lang->line('expense_clear')?>
						                </button>
						                <div class="btn btn-success image-preview-input">
						                    <span class="fa fa-repeat"></span>
						                    <span class="image-preview-input-title"><?=$this->lang->line('expense_filebrowse')?></span>
						                    <input type="file" accept="image/png, image/jpeg, image/gif" name="file"/>
						                </div>
						            </span>
						        </div>
						      	<?=form_error('file');?>
						    </div>
							<div class="form-group <?=form_error('note') ? 'has-error' : ''?>">
							  	<label for="note"><?=$this->lang->line('expense_note')?></label>
							  	<textarea name="note" id="note" class="form-control" cols="30" rows="5"><?=set_value('form-control')?></textarea>
							  	<?=form_error('note')?>
							</div>
						</div>
						<div class="box-footer">
							<button type="submit" class="btn btn-mytheme"><?=$this->lang->line('expense_add_expense')?></button>
						</div>
					</form>
				</div>
			</div>
		</div>
    </section>
</div>