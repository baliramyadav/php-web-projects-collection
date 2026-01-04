<?php 
  $key = isset($_REQUEST["key"]) ? $_REQUEST["key"] : null;
  $shortkey = isset($_REQUEST["shortkey"]) ? $_REQUEST["shortkey"] : null;
  $currPage = "download";

  include_once("includes/header.php"); 

  $SFS->cleanUp();
  if (!$key && !$shortkey) {
    include("notfound.php");
    exit;
  }

  $fileInfos = $key ? getFileInfos($key) : getFileInfos($shortkey,"downloadFromShort");

  if (!$fileInfos) {
    include("notfound.php");
    exit;
  }

  //set ip and session for direct download protections
  $sql = "insert into `" . $config->tablePrefix . "download_handler` set files_id = '" . $fileInfos->id . "', d_ip = '" . $_SERVER["REMOTE_ADDR"] . "', d_sid = " . $SFS->dbquote(session_id()) . ", d_time = now()";
  $SFS->dbquery($sql);

  androidDoubleCallFix();


?>
    <div class='container'>
      <div class="jumbotron">
        <h1><?php echo $config->siteName; ?> <small>Download Page</small></h1>
        <a href='<?php echo $config->instDir; ?>/' class='btn btn-lg btn-primary mt20 btn-wrap'>upload and share files for free</a>
      </div> <!-- jumbotron -->

      <div class="row">
        <div class="col-sm-9">

          <?php 
          //is there a message ???
          if (isset($fileInfos->message) && $fileInfos->message) {
          ?>
            <blockquote class='user-message'><sup><i class='fa fa-quote-left text-muted'></i></sup> <?php echo nl2br($fileInfos->message); ?> <sub><i class='fa fa-quote-right text-muted'></i></sub></blockquote>
          <?php
          }
          ?>


          <div class='text-center'>
          <?php
            //image file??? 
            if ($config->imagePreview && is_image($config->uploadDir . $fileInfos->uid . "/" . $fileInfos->id . "/" . $fileInfos->fname)) {
          ?>
              <div class='thumbnail thumbnail-downloadimage'>
                <img src='<?php echo $key ? $key : $SFS->config->baseDownloadUrl . $fileInfos->longkey; ?>.jpg' alt='<?php echo he($fileInfos->descr); ?>' class='img-rounded img-responsive' />
                <div class='caption'>
                  <h2 class='dfname'><?php echo $fileInfos->descr . " <small>" . fsize($fileInfos->fsize) . "</small>"; ?></h2>
          <?php
            } //image [-]
            else { //not an image with preview
          ?>
          <div class="panel panel-default panel-downloadfile">
            <div class="panel-heading"><h2 class='dfname'><?php echo $fileInfos->descr . " <small>" . fsize($fileInfos->fsize) . "</small>"; ?></h2></div>
            <div class="panel-body">
          
          <?php
          } //regular file [-]
          ?>
              
          <?php
          //file description??? [+]
          if ($fileInfos->descr_long) {
          ?>
            <div class='text-left small alert-info alert'><strong><?php echo lang("description"); ?>:</strong> <?php echo nl2br($fileInfos->descr_long); ?></div>
          <?php
          }
          //file description??? [-]
          ?>




              <a href='<?php echo $config->instDir; ?>/abuse.php?<?php echo $key ? "dk=$key" : "sk=$shortkey"; ?>' class='btn btn-danger btn-xs pull-right btn-report-file'><i class='fa fa-warning'></i> <span>Report File</span></a>

          <!-- file information -->

              <p class='text-left small'><?php echo lang("descr_uploaded"); ?>: <strong><?php echo date(lang("date_time_format"),strtotime($fileInfos->created)); ?></strong><br />
              <?php if (!$fileInfos->locked && $config->delDays > -1 || $fileInfos->del_days > -1) { ?>
                <?php echo lang("descr_accessible_until"); ?>: <strong><?php echo date(lang("date_format"),strtotime($fileInfos->accessible_until)); ?></strong> <small><i><?php echo sprintf(lang($fileInfos->days_remaining == 1 ? "descr_day_remaining" : "descr_days_remaining"),$fileInfos->days_remaining); ?></i></small><br />
              <?php } ?>
              <?php echo lang("descr_downloads"); ?>: <strong><?php 
                if (!$fileInfos->locked && $config->delSettingsByUploader && $fileInfos->del_downloads > 0) {
                  echo sprintf(lang("x_downloads_of_y"),$fileInfos->downloads,$fileInfos->del_downloads);
                } else {
                  echo $fileInfos->downloads;
                }
              ?></strong>
              </p>

              <div class="social-likes" data-url="<?php echo (isset($_SERVER["HTTPS"]) && ($_SERVER["HTTPS"] == 1 || $_SERVER["HTTPS"] === "on") ? "https" : "http") . "://" . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"]; ?>" data-title="<?php echo he($fileInfos->descr); ?>">
                <div class="facebook" title="Share link on Facebook">Facebook</div>
                <div class="twitter" title="Share link on Twitter">Twitter</div>
              </div>

            </div> <!--  caption OR panel-body -->

              <!-- download button -->
              <?php 
                //password protected?????
                $add2class = null;
                if ($config->passwordProtection && $fileInfos->pwd_protected && $fileInfos->pwd) $add2class = ' pwd-protected';
              ?>
              <div class='download-button-wrapper text-center'>
                <?php
                  //seconds to wait
                  if ($config->downloadSeconds) {
                    //to help debugging download-seconds issue
                    $debugFileInfos = getFileInfos($fileInfos->fkey,"file");
                    echo '<script type="text/javascript">
                      console.log("Init-Time (now): ' . $debugFileInfos->d_time . '\nDownload-Ready-Time: ' . date("Y-m-d G:i:s",strtotime($debugFileInfos->d_time) + $config->downloadSeconds) . '");
                    </script>';
                ?>
                  <a href='<?php echo $config->instDir; ?>/files/<?php echo $fileInfos->downloadFileName; ?>' class='btn btn-lg btn-warning btn-download btn-block disabled <?php echo $add2class; ?>'><i class="fa fa-clock-o fa-fw"></i> <span class='dwnin'><?php echo sprintf(lang("download_in"),"<span id='dlCD'><span>" . $config->downloadSeconds . "</span></span>"); ?></span> <i class="fa fa-clock-o fa-fw"></i></a>          
                <?php } else { ?>
                  <a href='<?php echo $config->instDir; ?>/files/<?php echo $fileInfos->downloadFileName; ?>' class='btn btn-lg btn-success btn-download btn-block <?php echo $add2class; ?>'><i class="fa fa-download fa-fw"></i> Download <i class="fa fa-download fa-fw"></i></a>
                <?php } ?>
              </div>


            </div> <!-- thumbnail OR panel-->
          </div> <!-- text-center -->


        </div>
        <div class="col-sm-3">
          <h3>Place for some Ads</h3>
          <p class='text-center'><a href='http://codecanyon.net?ref=themac' onclick="window.open(this.href); return false;"><img src='<?php echo $config->instDir; ?>/img/cc_300x250_v1.gif' alt='' class='img-rounded img-responsive' /></a><br /><br />
            <a href='http://themeforest.net?ref=themac' onclick="window.open(this.href); return false;"><img src='<?php echo $config->instDir; ?>/img/tf_260x120_v1.gif' alt='' class='img-rounded img-responsive' /></a>
          </p>
        </div>
      </div>

</div> <!-- container -->

    

<?php include("includes/footer.php"); ?>
