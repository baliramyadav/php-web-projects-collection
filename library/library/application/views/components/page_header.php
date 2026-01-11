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
    <!-- Font Awesome -->
    <link rel="stylesheet" href="<?=base_url('assets/plugins/font-awesome/css/font-awesome.min.css')?>">
    <!-- Ionicons -->
    <link rel="stylesheet" href="<?=base_url('assets/plugins/Ionicons/css/ionicons.min.css')?>">
    <link rel="stylesheet" type="text/css" href="<?=base_url('assets/iconmoon/style.css')?>">
    <!-- All Controllers css files Load Here -->
    <?php 
      if(isset($headerassets['css']) && calculate($headerassets['css'])) {
        foreach ($headerassets['css'] as $css) { ?>
          <link rel="stylesheet" href="<?=base_url($css)?>">
        <?php }
      }
    ?>
    <!-- Theme style -->
    <link rel="stylesheet" href="<?=base_url('assets/dist/css/AdminLTE.min.css')?>">
    <link rel="stylesheet" href="<?=base_url('assets/plugins/toastr/toastr.min.css')?>">
    <link rel="stylesheet" href="<?=base_url('assets/dist/css/skins/skin-'.trim($generalsetting->settheme).'.css')?>">
    <link rel="stylesheet" href="<?=base_url('assets/custom/css/style.css')?>">


    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

    <!-- jQuery 3 -->
    <script src="<?=base_url('assets/plugins/jquery/dist/jquery.min.js')?>"></script>
    <!-- All Controllers Js files Load Here -->
    <?php 
      if(isset($headerassets['headerjs']) && calculate($headerassets['headerjs'])) {
        foreach ($headerassets['headerjs'] as $headerjs) { ?>
          <script src="<?=base_url($headerjs)?>"></script>
        <?php }
      }
    ?>
    <link rel="shortcut icon" type="image/x-icon" href="<?=app_image_link($generalsetting->logo, 'uploads/images/', 'logo.jpg')?>">
    <script type="text/javascript">
        var THEME_BASE_URL = "<?=base_url('/')?>";
    </script>

    <!-- Google Font -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
    </head>
    <body class="hold-transition skin-<?=$generalsetting->settheme?> sidebar-mini">
        <div class="wrapper">
            