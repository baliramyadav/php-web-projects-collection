<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title><?=$get_title?></title>
        <!-- Tell the browser to be responsive to screen width -->
        <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
        <!-- Bootstrap 3.3.7 -->
        <link rel="stylesheet" href="<?=base_url('assets/plugins/bootstrap/dist/css/bootstrap.min.css')?>">
        <!-- Theme style -->
        <link rel="stylesheet" href="<?=base_url('assets/dist/css/AdminLTE.min.css')?>">
        <!-- iCheck -->
        <link rel="stylesheet" href="<?=base_url('assets/plugins/iCheck/square/blue.css')?>">
        <link rel="stylesheet" href="<?=base_url('assets/plugins/toastr/toastr.min.css')?>">


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
                <p class="login-box-msg text-bold"><?=$this->lang->line('login_signin_session_start')?></p>
                <?php if(calculate($errors)) {
                    foreach($errors as $error) {
                        echo "<p class='text-red'>".$error."</p>";
                    }
                } ?>
                <form action="<?=base_url('login/index')?>" method="post">
                    <div class="form-group has-feedback <?=form_error('username_or_email') ? 'has-error' : ''?>">
                        <label><?=$this->lang->line('login_username_or_email')?></label> <span class="text-red">*</span>
                        <input type="text" id="membername" class="form-control" name="username_or_email" value="<?=set_value('username_or_email')?>"/>
                        <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
                    </div>
                    <div class="form-group has-feedback <?=form_error('password') ? 'has-error' : ''?>">
                        <label><?=$this->lang->line('login_password')?></label> <span class="text-red">*</span>
                        <input type="password" id="password" class="form-control" name="password" value="<?=set_value('password')?>">
                        <span class="glyphicon glyphicon-lock form-control-feedback"></span>
                    </div>
                    <div class="row">
                        <div class="col-xs-8">
                            <div class="checkbox icheck">
                                <label><input type="checkbox"> <?=$this->lang->line('login_remember_me')?></label>
                            </div>
                        </div>
                        <div class="col-xs-4">
                            <button type="submit" class="btn btn-primary btn-block"><?=$this->lang->line('login_sign_in')?></button>
                        </div>
                    </div>
                </form>
                <div class="row">
                    <p class="text-center">-- OR --</p>
                    <div class="<?=$generalsetting->registration ? 'col-xs-6' : 'col-xs-12 text-center'?>">
                        <a class="btn btn-danger btn-sm <?=$generalsetting->registration ? 'pull-left' : ''?>" href="<?=base_url('login/resetpassword')?>"><?=$this->lang->line('login_reset_your_password')?></a><br>
                    </div>
                    <?php if($generalsetting->registration) { ?>
                        <div class="col-xs-6">
                            <a class="btn btn-success btn-sm pull-right" href="<?=base_url('login/registermember')?>" class="text-center"><?=$this->lang->line('login_register_new_member')?></a>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>

        <?php if(config_item('demo')) { ?>
        <div class="row">
            <div class="col-sm-4 col-md-offset-4">
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
        <!-- jQuery 3 -->
        <script src="<?=base_url('assets/plugins/jquery/dist/jquery.min.js')?>"></script>
        <!-- iCheck -->
        <script src="<?=base_url('assets/plugins/iCheck/icheck.min.js')?>"></script>
        <script src="<?=base_url('assets/plugins/toastr/toastr.min.js')?>"></script>

        <script type="text/javascript">
            $(function () {
                $('input').iCheck({
                    checkboxClass: 'icheckbox_square-blue',
                    radioClass: 'iradio_square-blue',
                    increaseArea: '20%' /* optional */
                });
            });

        <?php if(config_item('demo')) { ?>
            $('#admin').click(function() {
                $('#membername').val('admin');
                $('#password').val('123456');
                $('#password').attr('type','text');
            });

            $('#librarian').click(function() {
                $('#membername').val('librarian');
                $('#password').val('123456');
                $('#password').attr('type','text');
            });

            $('#member').click(function() {
                $('#membername').val('member');
                $('#password').val('123456');
                $('#password').attr('type','text');
            });

            $('#guest').click(function() {
                $('#membername').val('guest');
                $('#password').val('123456');
                $('#password').attr('type','text');
            });
        <?php } ?>

        <?php 
        $success = $this->session->flashdata('success');
        $error   = $this->session->flashdata('error');
        if($success) { ?>
            toastr.success('<?=$success?>');
        <?php } elseif($error) { ?>
            toastr.error('<?=$error?>');
        <?php } ?>
        </script>
    </body>
</html>
