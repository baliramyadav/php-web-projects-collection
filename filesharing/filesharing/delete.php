<?php 
  $currPage = "delete"; 

  include_once("includes/header.php"); 

  $key = $_REQUEST["key"];
  
  if (!$key) {
    include("notfound.php");
    exit;
  }

  $fileInfos = getFileInfos($key,"delete");

  if (!$fileInfos || !$fileInfos->id) {
    include("notfound.php");
    exit;
  }


  if (isset($_REQUEST["proceed"]) && $_REQUEST["proceed"] == 1) {
    $sql = "delete from `" . $config->tablePrefix . "files` where id = '" . $fileInfos->id . "' and uid = '0'";
    $SFS->dbquery($sql);

    $file_path = $config->uploadDir . $fileInfos->uid . "/" . $fileInfos->id;
    if (file_exists($file_path)) xrmdir($file_path);

    $success = lang("success_delfile");

  }

?>


    <div class='container'>
      <div class="jumbotron">        
        <h1><?php echo $config->siteName; ?> <small>File Deletion Page</small></h1>
        <a href='<?php echo $config->instDir; ?>/' class='btn btn-lg btn-primary mt20 btn-wrap'>upload and share files for free</a>
      </div>

      <div class="row">
        <div class="col-sm-9">
          <?php if (isset($success) && $success) { 
            echo "<div class='alert alert-success'>$success</div>";
          } else { ?>

          <div class='text-center'>
            <div class="panel panel-info panel-deletefile">
              <div class="panel-heading"><h4><?php echo lang("conf_delete_file"); ?></h4></div>
              <div class="panel-body">
                <h2 class='dfname'><?php echo $fileInfos->descr . " <span>" . fsize($fileInfos->fsize) . "</span>"; ?></h2>
                <p class='text-left small'>

                  <?php echo lang("descr_uploaded"); ?>: <strong><?php echo date("d.m.Y G:i",strtotime($fileInfos->created)); ?></strong><br />
                  <?php if ($config->delDays > -1) { ?>
                    <?php echo lang("descr_accessible_until"); ?>: <strong><?php echo date(lang("date_format"),strtotime($fileInfos->accessible_until)); ?></strong> <small><i><?php echo sprintf(lang($fileInfos->days_remaining == 1 ? "descr_day_remaining" : "descr_days_remaining"),$fileInfos->days_remaining); ?></i></small><br />
                  <?php } ?>
                  <?php echo lang("descr_downloads"); ?>: <strong><?php echo $fileInfos->downloads; ?></strong><br />
                  <?php
                  if ($config->passwordProtection && $fileInfos->pwd_protected && $fileInfos->pwd) {
                    echo lang("password") . ": <strong>" . $fileInfos->pwd . "</strong><br />";
                  }
                  list($dwnKey) = $SFS->genFileKeys($fileInfos->id);
                  ?>
                  <?php echo lang("download_link"); ?>: <strong><?php echo $config->baseDownloadUrl . $dwnKey; ?>.html</strong>
                </p>
                <p class='text-center'><a href='<?php echo $config->instDir; ?>/files/<?php echo $fileInfos->downloadFileName; ?>' class='btn btn-xs btn-success btn-download'><i class="fa fa-download fa-fw"></i> Download <i class="fa fa-download fa-fw"></i></a></p>
              </div>
              <div class='panel-footer text-center'>
                <a href='<?php echo $config->instDir; ?>/' class='btn btn-primary'><i class="fa fa-home"></i> <?php echo lang("no_cancel"); ?></a>
                <a href='?proceed=1' class='btn btn-danger'><i class="fa fa-trash-o"></i> <?php echo lang("yes_delete"); ?></a>
              </div>
            </div>
          </div>

          <?php
          } //!success 
          ?>
        </div> <!-- col-sm-9 -->


        <div class="col-sm-3">
          <h3>Place for some Ads</h3>
          <p class='text-center'><a href='http://themeforest.net?ref=themac' onclick="window.open(this.href); return false;"><img src='<?php echo $config->instDir; ?>/img/tf_300x250_v2.gif' alt='' class='img-rounded img-responsive' /></a><br /><br />
            <a href='http://codecanyon.net?ref=themac' onclick="window.open(this.href); return false;"><img src='<?php echo $config->instDir; ?>/img/cc_260x120_v3.gif' alt='' class='img-rounded img-responsive' /></a>
          </p>
        </div>
      </div>

    </div> <!-- container -->

    

<?php include("includes/footer.php"); ?>
