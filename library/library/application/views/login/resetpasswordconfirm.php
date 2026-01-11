<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title><?=$get_title?></title>
        <!-- Tell the browser to be responsive to screen width -->
        <meta content="width=device-width, initial-scale=1, maximum-scale=1, member-scalable=no" name="viewport">
        <!-- Bootstrap 3.3.7 -->
        <link rel="stylesheet" href="<?=base_url('assets/plugins/bootstrap/dist/css/bootstrap.min.css')?>">
        <!-- Theme style -->
        <link rel="stylesheet" href="<?=base_url('assets/dist/css/AdminLTE.min.css')?>">

        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->
        <!-- Google Font -->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
    </head>
    <body class="hold-transition login-page" style="background: url('<?=base_url('uploads/default/loginbg.jpg')?>') no-repeat center center fixed;background-size: 100% 100%; ">
        <div class="login-box">
            <div class="login-logo">
                 <a style="color: #fff" href="<?=base_url('/')?>"><b><?=$generalsetting->sitename?></b></a>
            </div>
            <div class="login-box-body">
                <p class="login-box-msg text-bold"><?=$this->lang->line('login_provide_valid_information_to_verification')?></p>
                <form method="post" enctype="multipart/form-data">
                    <div class="form-group <?=form_error('username_or_email') ? 'has-error' : ''?>">
                        <label><?=$this->lang->line('login_username_or_email')?></label> <span class="text-red">*</span>
                        <input type="text" class="form-control" name="username_or_email" value="<?=set_value('username_or_email', $username_or_email)?>"/>
                        <?=form_error('username_or_email')?>
                    </div>
                    <div class="form-group <?=form_error('verification_code') ? 'has-error' : ''?>">
                        <label><?=$this->lang->line('login_verification_code')?></label> <span class="text-red">*</span>
                        <input type="text" class="form-control" name="verification_code" value="<?=set_value('verification_code')?>"/>
                        <?=form_error('verification_code')?>
                    </div>
                    <div class="form-group <?=form_error('password') ? 'has-error' : ''?>">
                        <label><?=$this->lang->line('login_password')?></label> <span class="text-red">*</span>
                        <input type="text" class="form-control" name="password" value="<?=set_value('password')?>"/>
                    </div>
                    <div class="form-group <?=form_error('confirm_password') ? 'has-error' : ''?>">
                        <label><?=$this->lang->line('login_confirm_password')?></label> <span class="text-red">*</span>
                        <input type="text" class="form-control" name="confirm_password" value="<?=set_value('confirm_password')?>"/>
                        <?=form_error('confirm_password')?>
                    </div>
                    <div class="row">
                        <div class="col-xs-6">
                            <a type="submit" href="<?=base_url('login/index')?>" class="btn btn-danger btn-block"><?=$this->lang->line('login_back_to_login')?></a>
                        </div>
                        <div class="col-xs-6">
                            <button type="submit" class="btn btn-primary btn-block"><?=$this->lang->line('login_submit')?></button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </body>
</html>

