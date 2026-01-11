<section class="main-login">
    <div class="container">
        <div class="row">
            <div class="col-sm-6 offset-md-3">
                <div class="my-login">
                    <h2 class="text-center"><?=$this->lang->line('frontend_login')?></h2>
                    <hr>
                    <?php if(calculate($errors)) {
                        foreach($errors as $error) {
                            echo "<p class='text-danger'>".$error."</p>";
                        }
                    } ?>
                    <form action="<?=base_url('myaccount/login')?>" method="POST">
                        <div class="form-group">
                            <label for="username_or_email"><?=$this->lang->line('frontend_username_or_email')?></label> <span class="text-danger">*</span>
                            <input name="username_or_email" type="text" class="form-control <?=form_error('username_or_email') ? 'is-invalid' : ''?>" placeholder="Enter email" id="membername" value="<?=set_value('username_or_email')?>">
                        </div>
                        <div class="form-group">
                            <label for="password"><?=$this->lang->line('frontend_password')?></label> <span class="text-danger">*</span>
                            <input name="password" type="password" class="form-control <?=form_error('password') ? 'is-invalid' : ''?>" placeholder="Enter password" id="password" value="<?=set_value('password')?>">
                        </div>
                        <button type="submit" class="btn btn-success btn-block"><?=$this->lang->line('frontend_login')?></button>
                    </form>
                </div>
            </div>
        </div>
        <?php if(config_item('demo')) { ?>
            <div class="row">
                <div class="col-sm-4 offset-md-4">
                    <div class="well text-center">
                        <strong>Login Panel</strong><br/>
                        <button class="btn btn-primary" id="admin">Admin</button>
                        <button class="btn btn-danger" id="librarian">Librarian</button>
                        <button class="btn btn-warning" id="member">Member</button>
                        <button class="btn btn-success" id="guest">Guest</button>
                    </div>
                </div>
            </div>
        <?php } ?>
    </div>
</section>