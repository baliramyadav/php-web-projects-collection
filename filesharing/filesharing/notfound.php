<?php

  //just for nicier setup routines
  if (preg_match('|/setup\.php$|',$_SERVER["REQUEST_URI"])) {
    header("location: ./");
    exit;
  }

  if (!headers_sent()) {
    header("HTTP/1.0 404 Not Found");
  }

  $currPage = "notfound";

  include_once("includes/header.php");   

?>
  <div class="container">
    <div class="row">
      <div class='col-xs-12 text-center'>
        <h1 class='text-danger'>Oops! Page not found...</h1>
        <p><?php echo lang("info_404notfound"); ?></p>
        <h2 class='error404'><i class='fa fa-chain-broken'></i> 404 <small class='text-muted'>not found</small></h2>
      </div>
    </div>
  </div>

    

<?php include("includes/footer.php"); ?>
