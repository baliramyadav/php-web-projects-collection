<?php
  header('Content-Type: text/html; charset=utf-8');

  if (!isset($SFS)) {
    include_once("config.php");

    include("classes/sfs.class.php");
    $SFS = new SFS($config);

    $config = $SFS->config;

    include_once("functions.php");
  }

  include_once("lang/" . $config->lang . "/main.lang.php");
  //faqs are used in the footer too
  include("lang/" . $config->lang . "/faqs.lang.php");


  //expand title on download page
  $add2title = null;
  if (isset($currPage) && $currPage == "download" && ($key || $shortkey)) {
    if ($key) {
      $fileDescr = getFileInfos($key,"download","descr");
    } elseif ($shortkey) {
      $fileDescr = getFileInfos($shortkey,"downloadFromShort","descr");
    }
    if ($fileDescr) {
      $add2title = " | $fileDescr";
    }
  }
?>
<!DOCTYPE html>
<html lang="<?php echo substr($config->lang,0,2); ?>">
  <head>
    <meta charset="utf-8">
    <title><?php echo strip_tags($config->siteName); ?><?php echo he($add2title); ?></title>
    
    <link rel="shortcut icon" href="<?php echo $config->instDir; ?>/favicon.ico" type="image/x-icon">
		<link rel="icon" href="<?php echo $config->instDir; ?>/favicon.ico" type="image/x-icon">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="Mac Winter | http://codecanyon.net/user/themac/">

    <!-- bootstrap styles... -->
    <link href="<?php echo $config->instDir; ?>/css/bootstrap.<?php echo $config->bootstrapTheme; ?>.min.css" rel="stylesheet">
    
    <!-- sfs styles... -->
    <link href="<?php echo $config->instDir; ?>/css/sfs.min.css" rel="stylesheet">

    <script src="<?php echo $config->instDir; ?>/js/jquery.1.11.0.min.js" type="text/javascript"></script>

    <script src="<?php echo $config->instDir; ?>/js/bootstrap.min.js" type="text/javascript"></script>

    <!-- notifications [+] -->
    <script src="<?php echo $config->instDir; ?>/js/pnotify.custom.min.js" type="text/javascript"></script>
    <!-- notifications [-] -->

    <?php if ($config->downloadSeconds) { ?>
    <!-- wait seconds for download [+] -->
    <script src="<?php echo $config->instDir; ?>/js/jquery.countDown.min.js" type="text/javascript"></script>    
    <!-- wait seconds for download [+] -->
    <?php } ?>

    <!-- copy to clipboard feature [+] -->
      <script src="<?php echo $config->instDir; ?>/js/clipboard.min.js" type="text/javascript"></script>    
    <!-- copy to clipboard feature [-] -->

    <!-- social shares on download pages -->
    <script src="<?php echo $config->instDir; ?>/js/social-likes.min.js" type="text/javascript"></script>    

    <!-- easier modals [+] -->
    <script src='<?php echo $config->instDir; ?>/js/bootbox.min.js' type='text/javascript'></script>
    <!-- easier modals [-] -->

    <?php
      if (isset($config->maxRcpt) && $config->maxRcpt > 1) { ?>
      <!-- multiple mail recipients [+] -->
      <script src="<?php echo $config->instDir; ?>/js/bootstrap-tagsinput.min.js" type="text/javascript"></script>
      <!-- multiple mail recipients [+] -->
    <?php } ?>

    <script src='<?php echo $config->instDir; ?>/js/chosen.jquery.min.js' type='text/javascript'></script>

    <script src="<?php echo $config->instDir; ?>/js/sfs.min.js?<?php echo date("Ymd"); ?>" type="text/javascript"></script>
<?php

  if (isset($add2header)) {
    echo $add2header;
  }

  $actMen[isset($currPage) && $currPage ? $currPage : "home"] = " class='active'";


?>

  </head>

  <body id='BS-<?php echo $config->bootstrapTheme; ?>'>

    <div id="wrapper_main">

    <!-- Fixed navbar -->
    <div class="navbar <?php echo $config->navbar_style; ?> navbar-not-rounded">
      <div class="container">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="<?php echo $config->instDir; ?>/"><?php echo $config->siteName; ?></a>
        </div>
        <div class="navbar-collapse collapse">
          <ul class="nav navbar-nav">
            <li<?php echo isset($actMen["home"]) ? $actMen["home"] : ""; ?>><a href="<?php echo $config->instDir; ?>/"><?php echo lang("men_home"); ?></a></li>
            <li<?php echo isset($actMen["contact"]) ? $actMen["contact"] : ""; ?>><a href="<?php echo $config->instDir; ?>/contact.php"><?php echo lang("men_contact"); ?></a></li>
            <li<?php echo isset($actMen["faqs"]) ? $actMen["faqs"] : ""; ?>><a href="<?php echo $config->instDir; ?>/faqs.php">FAQs</a></li>
          </ul>
          <!-- DropDown [+] -->
    <?php if ($config->languages) { ?> 
          <ul class='nav navbar-nav navbar-right'>
            <li class="dropdown">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown">Options <span class="caret"></span></a>
              <ul class="dropdown-menu">
                <li class="dropdown-header">Languages</li>
                <?php
                  foreach ($config->languages as $lkey => $ldescr) {
                    echo "<li><a href='?setLang=$lkey'>$ldescr</a></li>\n";
                  }
                ?>
              </ul>
            </li>
          </ul>
    <?php } ?>
          <!-- DropDown [-] -->
        </div><!-- .nav-collapse -->
      </div>
    </div> <!-- navbar -->

<noscript>
  <div class='container-fluid'>
    <div class='alert alert-info'><?php echo lang("info_js_needed"); ?></div>
  </div>
</noscript>