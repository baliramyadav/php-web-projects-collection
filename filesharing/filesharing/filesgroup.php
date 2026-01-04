<?php 
  $currPage = "filesgroup";
  
  include_once("includes/header.php"); 

  $SFS->cleanUp();

  $key = $_REQUEST["key"];
  
  if (!$key) {
    include("notfound.php");
    exit;
  }

  list($files,$message) = getMultiFileInfos($key);

  if (!$files) {
    include("notfound.php");
    exit;
  }
?>
    <div class='container'>
      <div class="jumbotron">          
        <h1><?php echo $config->siteName; ?> <small>Download Page</small></h1>
        <a href='<?php echo $config->instDir; ?>/' class='btn btn-lg btn-primary mt20'>upload and share files for free</a>
      </div>

      <div class="row">
        <div class="col-sm-9 filesgroup">
          <h2 class='dfname'><?php echo lang("list_of_files") ?></h2>
          
          <?php 
          //is there a message ???
          if ($message) {
          ?>
            <blockquote class='user-message'><sup><i class='fa fa-quote-left text-muted'></i></sup> <?php echo nl2br($message); ?> <sub><i class='fa fa-quote-right text-muted'></i></sub></blockquote>
          <?php
          }
          ?>


          <div class="panel-group" id="files">

          <?php 
          $fcnt = count($files);
          for ($i=0;$i<$fcnt;$i++) {
            $file = $files[$i];
            ?>
            <div class="panel panel-primary">
              <div class="panel-heading">
                <h4 class="panel-title"><a data-toggle="collapse" data-parent="#files" href="#file<?php echo $i; ?>"><?php echo $file->descr; ?> <small><?php echo fsize($file->fsize); ?></small></a></h4>
              </div>
              <div id="file<?php echo $i; ?>" class="panel-collapse collapse<?php echo (!$i ? ' in' : ''); ?>">
                <div class='panel-body'>
                  <div class='form-horizontal'>
                    <label class='control-label col-sm-4'><?php echo lang("download_link"); ?></label>
                    <div class='col-sm-8'>
                      <div class="input-group">
                        <input type='text' name='gDownloadLink' class='form-control gDownloadLink' value='<?php echo $SFS->config->instUrl . "/" . $file->shortkey; ?>' readonly='readonly' />
                        <span class="input-group-btn"><button type='button' class='btn btn-primary btndown'><i class="fa fa-globe"></i> <?php echo lang("follow_link"); ?></button></span>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            
          <?php
          }
          ?>

          </div>


        </div>

        <div class="col-sm-3">
          <h3>Place for some Ads</h3>
          <p class='text-center'><a href='http://codecanyon.net?ref=themac' onclick="window.open(this.href); return false;"><img src='<?php echo $config->instDir; ?>/img/cc_300x250_v1.gif' alt='' class='img-rounded img-responsive' /></a><br /><br />
            <a href='http://themeforest.net?ref=themac' onclick="window.open(this.href); return false;"><img src='<?php echo $config->instDir; ?>/img/tf_260x120_v1.gif' alt='' class='img-rounded img-responsive' /></a>
          </p>
        </div>

      </div> <!-- row -->

    </div> <!-- container -->

<?php include("includes/footer.php"); ?>
