<section class="main-login">
    <div class="container">
        <div class="row">
            <div class="col-sm-6 offset-md-3">
                <div class="my-login">
                    <h2 class="text-center"><?=$this->lang->line('frontend_register')?></h2>
                    <hr>
                    <?php if(calculate($errors)) {
                        foreach($errors as $error) {
                            echo "<p class='text-danger'>".$error."</p>";
                        }
                    } ?>
                    <form action="<?=base_url('myaccount/register')?>" method="post" enctype="multipart/form-data">
                        <div class="form-group">
                            <label><?=$this->lang->line('frontend_name')?></label> <span class="text-danger">*</span>
                            <input type="text" class="form-control <?=form_error('name') ? 'is-invalid' : ''?>" name="name" value="<?=set_value('name')?>" />
                        </div>
                        <div class="form-group">
                            <label><?=$this->lang->line('frontend_email')?></label> <span class="text-danger">*</span>
                            <input type="text" class="form-control <?=form_error('email') ? 'is-invalid' : ''?>" name="email" value="<?=set_value('email')?>" />
                        </div>
                        <div class="form-group">
                            <label><?=$this->lang->line('frontend_phone')?></label> <span class="text-danger">*</span>
                            <input type="text" class="form-control <?=form_error('phone') ? 'is-invalid' : ''?>" name="phone" value="<?=set_value('phone')?>" />
                        </div>
                        <div class="form-group">
                            <label><?=$this->lang->line('frontend_photo')?></label> <span class="text-danger">*</span>
                            <div class="custom-file">
                                <input type="file" name="photo" class="custom-file-input" id="photo">
                                <label class="custom-file-label" for="photo"><?=$this->lang->line('frontend_choose_file')?></label>
                            </div>
                        </div>
                        <div class="form-group">
                            <label><?=$this->lang->line('frontend_username')?></label> <span class="text-danger">*</span>
                            <input type="text" class="form-control <?=form_error('username') ? 'is-invalid' : ''?>" name="username" value="<?=set_value('username')?>" />
                        </div>
                        <div class="form-group">
                            <label><?=$this->lang->line('frontend_password')?></label> <span class="text-danger">*</span>
                            <input type="text" class="form-control <?=form_error('password') ? 'is-invalid' : ''?>" name="password" value="<?=set_value('password')?>" />
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-success btn-block"><?=$this->lang->line('frontend_register')?></button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</section>