<div class="content-wrapper">
    <section class="content-header">
  		<h1><?=$this->lang->line('storebook')?></h1>
  		<ol class="breadcrumb">
        	<li><a href="<?=base_url('dashboard/index')?>"><i class="fa fa-dashboard"></i><?=$this->lang->line('dashboard')?></a></li>
  			<li><a href="<?=base_url('storebook/index')?>"><?=$this->lang->line('storebook')?></a></li>
  			<li class="active"><?=$this->lang->line('edit')?></li>
  		</ol>
    </section>
    <section class="content">
		<div class="box box-mytheme">
			<div class="row">
				<div class="col-md-6">
					<form role="form" method="post" enctype="multipart/form-data">
						<div class="box-body">
							<div class="form-group <?=form_error('name') ? 'has-error' : ''?>">
							 	<label for="name"><?=$this->lang->line('storebook_name')?></label> <span class="text-red">*</span>
							  	<input type="text" class="form-control" id="name" name="name" value="<?=set_value('name', $book->name)?>" placeholder="Enter name">
							  	<?=form_error('name')?>
							</div>

							<div class="form-group <?=form_error('author') ? 'has-error' : ''?>">
							 	<label for="author"><?=$this->lang->line('storebook_author')?></label> <span class="text-red">*</span>
							  	<input type="text" class="form-control" id="author" name="author" value="<?=set_value('author', $book->author)?>" placeholder="Enter Author">
							  	<?=form_error('author')?>
							</div>

							<div class="form-group <?=form_error('quantity') ? 'has-error' : ''?>">
							 	<label for="quantity"><?=$this->lang->line('storebook_quantity')?></label> <span class="text-red">*</span>
							  	<input type="number" class="form-control" id="quantity" name="quantity" value="<?=set_value('quantity', $book->quantity)?>" placeholder="Enter Quantity">
							  	<?=form_error('quantity')?>
							</div>

							<div class="form-group <?=form_error('price') ? 'has-error' : ''?>">
							 	<label for="price"><?=$this->lang->line('storebook_price')?></label> <span class="text-red">*</span>
							  	<input type="text" class="form-control" id="price" name="price" value="<?=set_value('price', $book->price)?>" placeholder="Enter Price">
							  	<?=form_error('price')?>
							</div>

							<div class="form-group <?=form_error('codeno') ? 'has-error' : ''?>">
							 	<label for="codeno"><?=$this->lang->line('storebook_code_no')?></label> <span class="text-red">*</span>
							  	<input type="text" class="form-control" id="codeno" name="codeno" value="<?=set_value('codeno', $book->codeno)?>" placeholder="Enter Code No">
							  	<?=form_error('codeno')?>
							</div>								

							<div class="form-group <?=form_error('coverphoto') ? 'has-error' : ''?>">
						        <label for="coverphoto"><?=$this->lang->line("storebook_cover_photo")?></label> <span class="text-red">*</span>
						        <div class="input-group image-preview">
						            <input type="text" class="form-control image-preview-filename" disabled="disabled">
						            <span class="input-group-btn">
						                <button type="button" class="btn btn-default image-preview-clear" style="display:none;">
						                    <span class="fa fa-remove"></span><?=$this->lang->line('storebook_clear')?>
						                </button>
						                <div class="btn btn-success image-preview-input">
						                    <span class="fa fa-repeat"></span>
						                    <span class="image-preview-input-title"><?=$this->lang->line('storebook_filebrowse')?></span>
						                    <input type="file" accept="image/png, image/jpeg, image/gif" name="coverphoto"/>
						                </div>
						            </span>
						        </div>
						        <div class="input-group">
						        	<img class="userprofileimg" src="<?=app_image_link($book->coverphoto,'uploads/storebook/','requestbook.jpg')?>" alt="">
						        </div>
						      	<?=form_error('coverphoto');?>
						    </div>

							<div class="form-group <?=form_error('storebookcategoryID') ? 'has-error' : ''?>">
							  	<label for="storebookcategoryID"><?=$this->lang->line('storebook_storebook_category')?></label> <span class="text-red">*</span>
								<?php 
									$storebookcategoryArray = [];
									$storebookcategoryArray[0] = $this->lang->line('storebook_please_select');
									if(calculate($storebookcategorys)) {
										foreach($storebookcategorys as $storebookcategory) {
											$storebookcategoryArray[$storebookcategory->storebookcategoryID] = $storebookcategory->name;
										}
									}
									echo form_dropdown('storebookcategoryID', $storebookcategoryArray, set_value('storebookcategoryID', $book->storebookcategoryID), 'id="storebookcategoryID" class="form-control"');
								?>
							  	<?=form_error('storebookcategoryID')?>
							</div>

							<div class="form-group <?=form_error('isbnno') ? 'has-error' : ''?>">
							 	<label for="isbnno"><?=$this->lang->line('storebook_isbn_no')?></label>
							  	<input type="text" class="form-control" id="isbnno" name="isbnno" value="<?=set_value('isbnno', $book->isbnno)?>" placeholder="Enter isbnno">
							  	<?=form_error('isbnno')?>
							</div>
							
						    <div class="form-group <?=form_error('editionnumber') ? 'has-error' : ''?>">
							 	<label for="editionnumber"><?=$this->lang->line('storebook_edition_number')?></label>
							  	<input type="text" class="form-control" id="editionnumber" name="editionnumber" value="<?=set_value('editionnumber', $book->editionnumber)?>" placeholder="Enter Edition Number">
							  	<?=form_error('editionnumber')?>
							</div>
							
							<div class="form-group <?=form_error('editiondate') ? 'has-error' : ''?>">
							 	<label for="editiondate"><?=$this->lang->line('storebook_edition_date')?></label>
							 	<?php $editiondate = isset($book->editiondate) ? date('d-m-Y',strtotime($book->editiondate)) : ''?>
							  	<input type="text" class="form-control" id="editiondate" name="editiondate" value="<?=set_value('editiondate', $editiondate)?>" placeholder="Enter Edition Date">
							  	<?=form_error('editiondate')?>
							</div>
							
							<div class="form-group <?=form_error('publisher') ? 'has-error' : ''?>">
							 	<label for="publisher"><?=$this->lang->line('storebook_publisher')?></label>
							  	<input type="text" class="form-control" id="publisher" name="publisher" value="<?=set_value('publisher', $book->publisher)?>" placeholder="Enter Publisher">
							  	<?=form_error('publisher')?>
							</div>
							
							<div class="form-group <?=form_error('publisheddate') ? 'has-error' : ''?>">
							 	<?php $publisheddate = isset($book->publisheddate) ? date('d-m-Y',strtotime($book->publisheddate)) : ''?>
							 	<label for="publisheddate"><?=$this->lang->line('storebook_published_date')?></label>
							  	<input type="text" class="form-control" id="publisheddate" name="publisheddate" value="<?=set_value('publisheddate', $publisheddate)?>" placeholder="Enter Published Date">
							  	<?=form_error('publisheddate')?>
							</div>
							
							<div class="form-group <?=form_error('notes') ? 'has-error' : ''?>">
							  	<label for="notes"><?=$this->lang->line('storebook_notes')?></label>
							  	<textarea name="notes"  id="notes" cols="30" rows="5" class="form-control" placeholder="Enter Notes"><?=set_value('notes', $book->notes)?></textarea>
							  	<?=form_error('notes')?>
							</div>

							<div class="form-group <?=form_error('description') ? 'has-error' : ''?>">
							  	<label for="description"><?=$this->lang->line('storebook_descrption')?></label> <span class="text-red">*</span>
							  	<textarea name="description"  id="description" cols="30" rows="5" class="form-control" placeholder="Enter description"><?=set_value('description', $book->description)?></textarea>
							  	<?=form_error('description')?>
							</div>
							
							<div class="form-group">
							  	<label for="images"><?=$this->lang->line('storebook_images')?>(<?=$this->lang->line('storebook_multiple')?>)</label>
							  	<input type="file" class="form-control" name="images[]" multiple id="images"/>
		                    </div>
		                    <div id="image_preview">
		                    	<?php if(calculate($storebookimages)) { foreach($storebookimages as $storebookimage) { ?>
		                    		<img class="imgthumbnail" src="<?=app_image_link($storebookimage->file_name,'uploads/storebook/','requestbook.jpg')?>"/>
		                    	<?php } } ?>
		                    </div>
						</div>
						<div class="box-footer">
							<button type="submit" class="btn btn-mytheme"><?=$this->lang->line('storebook_update_book')?></button>
						</div>
					</form>
				</div>
			</div>
		</div>
    </section>
</div>