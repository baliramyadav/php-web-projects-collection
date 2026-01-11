<div class="content-wrapper">
    <section class="content-header">
  		<h1><?=$this->lang->line('bookcategory')?></h1>
  		<ol class="breadcrumb">
        	<li><a href="<?=base_url('dashboard/index')?>"><i class="fa fa-dashboard"></i><?=$this->lang->line('dashboard')?></a></li>
  			<li><a href="<?=base_url('bookcategory/index')?>"><?=$this->lang->line('bookcategory')?></a></li>
  			<li class="active"><?=$this->lang->line('add')?></li>
  		</ol>
    </section>
    <section class="content">
		<div class="box box-mytheme">
			<div class="row">
				<div class="col-md-6">
					<form role="form" method="post" enctype="multipart/form-data">
						<div class="box-body">
							<div class="form-group <?=form_error('name') ? 'has-error' : ''?>">
							 	<label for="name"><?=$this->lang->line('bookcategory_name')?></label> <span class="text-red">*</span>
							  	<input type="text" class="form-control" id="name" name="name" value="<?=set_value('name')?>" placeholder="Enter name">
							  	<?=form_error('name')?>
							</div>
							<div class="form-group <?=form_error('description') ? 'has-error' : ''?>">
							  	<label for="description"><?=$this->lang->line('bookcategory_description')?></label> <span class="text-red">*</span>
							  	<textarea name="description" value="<?=set_value('description')?>" id="" cols="30" rows="5" class="form-control" placeholder="Enter description"><?=set_value('description')?></textarea>
							  	<?=form_error('description')?>
							</div>
							<div class="form-group <?=form_error('coverphoto') ? 'has-error' : ''?>">
						        <label for="coverphoto"><?=$this->lang->line("bookcategory_coverphoto")?></label>
						        <div class="input-group image-preview">
						            <input type="text" class="form-control image-preview-filename" disabled="disabled">
						            <span class="input-group-btn">
						                <button type="button" class="btn btn-default image-preview-clear" style="display:none;">
						                    <span class="fa fa-remove"></span><?=$this->lang->line('bookcategory_clear')?>
						                </button>
						                <div class="btn btn-success image-preview-input">
						                    <span class="fa fa-repeat"></span>
						                    <span class="image-preview-input-title"><?=$this->lang->line('bookcategory_filebrowse')?></span>
						                    <input type="file" accept="image/png, image/jpeg, image/gif" name="coverphoto"/>
						                </div>
						            </span>
						        </div>
						      	<?=form_error('coverphoto');?>
						    </div>
							<div class="form-group <?=form_error('status') ? 'has-error' : ''?>">
							  	<label for="status"><?=$this->lang->line('bookcategory_status')?></label> <span class="text-red">*</span>
							  	<?php 
									$statusArray[0] = $this->lang->line('bookcategory_please_select');
									$statusArray[1] = $this->lang->line('bookcategory_enable');
									$statusArray[2] = $this->lang->line('bookcategory_disable');

									echo form_dropdown('status', $statusArray, set_value('status'),'id="status" class="form-control"');
								?>
							  	<?=form_error('status')?>
							</div>
						</div>
						<div class="box-footer">
							<button type="submit" class="btn btn-mytheme"><?=$this->lang->line('bookcategory_add_book_category')?></button>
						</div>
					</form>
				</div>
			</div>
		</div>
    </section>
</div>