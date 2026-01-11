<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>Install</title>
        <!-- Tell the browser to be responsive to screen width -->
        <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
        <!-- Bootstrap 3.3.7 -->
        <link rel="stylesheet" type="text/css" href="<?=base_url('assets/frontend/css/font-awesome.min.css')?>" media="all" />
        <link rel="stylesheet" href="<?=base_url('assets/plugins/bootstrap/dist/css/bootstrap.min.css')?>">
        <link rel="stylesheet" href="<?=base_url('assets/custom/css/install.css')?>">
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
    <body style="background: url('<?=base_url('uploads/default/loginbg.jpg')?>') no-repeat center center fixed;background-size: 100% 100%; ">
        <div class="header-area">
            <div class="container">
                <div class="row">
                    <div class="col-sm-12">
                        <h2 class="install-logo">Green Lms</h2>
                    </div>
                </div>
            </div>
        </div>
        <div class="body-area">
            <div class="container">
                <div class="row">
                    <div class="col-sm-10 col-sm-offset-1 ">
                        <div class="wizard">
                            <div class="wizard-inner">
                                <div class="connecting-line"></div>
                                <?php $this->load->view('install/tabitem')?>
                            </div>

                            <div class="tab-content wizard-tabcontent">
                                <div class="tab-pane active" role="tabpanel" id="purchase">
                                    <form action="<?=base_url('install/purchase')?>" method="POST">
                                        <div class="form-group <?=form_error('username') ? 'has-error' : ''?>">
                                            <label for="username">User Name</label>
                                            <span class="text-danger">*</span>
                                            <input type="text" class="form-control" name="username" value="<?=set_value('username')?>" placeholder="Envato User Name">
                                            <span class="help-block"><?=form_error('username')?></span>
                                        </div>
                                        <div class="form-group <?=form_error('purchasecode') ? 'has-error' : ''?>">
                                            <label for="purchasecode">Purchase Code</label>
                                            <span class="text-danger">*</span>
                                            <input type="text" class="form-control" name="purchasecode" value="<?=set_value('purchasecode')?>" placeholder="Item Purchase Code">
                                            <span class="help-block"><?=form_error('purchasecode')?></span>
                                        </div>
                                        <ul class="list-inline pull-right">
                                            <li><button type="submit" class="btn btn-primary">Save and continue</button></li>
                                        </ul>
                                    </form>
                                    <div class="clearfix"></div>
                                </div>
                            </div>
                        </div>
                    </div>
               </div>
            </div>
        </div>
        <div class="footer-area" style="margin-bottom: 100px"></div>

        <!-- jQuery 3 -->
        <script src="<?=base_url('assets/plugins/jquery/dist/jquery.min.js')?>"></script>
        <script src="<?=base_url('assets/plugins/bootstrap/dist/js/bootstrap.min.js')?>"></script>
        <script src="<?=base_url('assets/plugins/toastr/toastr.min.js')?>"></script>

        <script type="text/javascript">
            $(document).ready(function () {
                $('.nav-tabs > li a[title]').tooltip();
            });
        </script>

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
