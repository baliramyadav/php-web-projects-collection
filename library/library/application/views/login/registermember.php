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
        <link rel="stylesheet" href="<?=base_url('assets/custom/css/style.css')?>">

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
                <p class="login-box-msg text-bold"><?=$this->lang->line('login_provide_validinformation')?></p>
                <form action="<?=base_url('login/registermember')?>" method="post" enctype="multipart/form-data">
                    <div class="form-group <?=form_error('name') ? 'has-error' : ''?>">
                        <label><?=$this->lang->line('login_name')?></label> <span class="text-red">*</span>
                        <input type="text" class="form-control" name="name" value="<?=set_value('name')?>"/>
                        <?=form_error('name')?>
                    </div>
                    <div class="form-group <?=form_error('email') ? 'has-error' : ''?>">
                        <label><?=$this->lang->line('login_email')?></label> <span class="text-red">*</span>
                        <input type="text" class="form-control" name="email" value="<?=set_value('email')?>"/>
                        <?=form_error('email')?>
                    </div>
                    <div class="form-group <?=form_error('phone') ? 'has-error' : ''?>">
                        <label><?=$this->lang->line('login_phone')?></label> <span class="text-red">*</span>
                        <input type="text" class="form-control" name="phone" value="<?=set_value('phone')?>"/>
                        <?=form_error('phone')?>
                    </div>
                    <div class="form-group <?=form_error('photo') ? 'has-error' : ''?>">
                        <label for="photo"><?=$this->lang->line("login_photo")?></label> <span class="text-red">*</span>
                        <div class="input-group image-preview">
                            <input type="text" class="form-control image-preview-filename" disabled="disabled">
                            <span class="input-group-btn">
                                <button type="button" class="btn btn-default image-preview-clear" style="display:none;">
                                    <span class="fa fa-remove"></span><?=$this->lang->line('login_clear')?>
                                </button>
                                <div class="btn btn-success image-preview-input">
                                    <span class="fa fa-repeat"></span>
                                    <span class="image-preview-input-title"><?=$this->lang->line('login_filebrowse')?></span>
                                    <input type="file" accept="image/png, image/jpeg, image/gif" name="photo"/>
                                </div>
                            </span>
                        </div>
                        <?=form_error('photo')?>
                    </div>
                    <div class="form-group <?=form_error('username') ? 'has-error' : ''?>">
                        <label><?=$this->lang->line('login_username')?></label> <span class="text-red">*</span>
                        <input type="text" class="form-control" name="username" value="<?=set_value('username')?>"/>
                        <?=form_error('username')?>
                    </div>
                    <div class="form-group <?=form_error('password') ? 'has-error' : ''?>">
                        <label><?=$this->lang->line('login_password')?></label> <span class="text-red">*</span>
                        <input type="text" class="form-control" name="password" value="<?=set_value('password')?>"/>
                        <?=form_error('password')?>
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
        <!-- jQuery 3 -->
        <script src="<?=base_url('assets/plugins/jquery/dist/jquery.min.js')?>"></script>
        <script src="<?=base_url('assets/plugins/bootstrap/dist/js/bootstrap.min.js')?>"></script>
        <script type="text/javascript">
            var globalFilebrowse = "<?=$this->lang->line('filebrowse')?>";
        </script>
        <script src="<?=base_url('assets/custom/js/fileupload.js')?>"></script>
    </body>
</html>

