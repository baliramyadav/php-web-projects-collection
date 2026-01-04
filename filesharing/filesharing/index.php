<?php
  $add2header = '<script src="js/vendor/jquery.ui.widget.js"></script>
    <script src="js/jquery.iframe-transport.js"></script>
    <script src="js/jquery.fileupload.js"></script>';
    
  include("includes/header.php"); 


  //for the "only SFS Admins" uploads
  if ($config->adminOnlyUploads && !$sfs_auth) {
    $adminPage = $_SERVER["PHP_SELF"];
    $action = isset($_REQUEST["action"]) ? $_REQUEST["action"] : null;
    echo "<div class='container'><div class='row'><div class='col-xs-12'>";
    include("includes/admin/login.php");
    if ($sfs_auth) {
      echo '<script type="text/javascript">location.href="' . $adminPage . '"</script>';
    }
    echo "</div></div></div>";
    include("includes/footer.php");
    exit;
  }

  $u_key = md5($_SERVER["REMOTE_ADDR"] . microtime(true));

?>

    <div class='container'>
      <div class="jumbotron">

        <div class='row hide0' id='singleUploader'> <!-- and multi :) -->
          <h1><?php echo $config->siteName; ?> <small>File Uploader</small> <button type='button' class='btn btn-xs btn-default pull-right js-hide js-btn-backto'><i class='fa fa-reply fa-fw'></i><?php echo lang("back_to_uploads"); ?></button></h1>

          <div class='col-sm-4 col-md-3'>
            <input type='hidden' name='u_key' value="<?php echo $u_key; ?>" />
            <input type='hidden' name='filesUpped' value="0" />
            <input type='hidden' name='filesUppedTotal' value="0" />
            <input type='hidden' name='backto' value="" />
             <span class="btn btn-primary btn-lg btn-block fileinput-button">
                <i class="fa fa-plus"></i>
                <span><?php echo lang($config->multiUpload ? "select_files" : "select_file"); ?></span>
                <input type="file" id="fileupload" name="files[]" data-url="jqu/" <?php if ($config->multiUpload) echo "multiple "; ?>/>
            </span>
            
            <button type='button' class="btn btn-danger btn-lg btn-block cancelUpload js-hide">
                <i class="fa fa-trash-o"></i>
                <span><?php echo lang("cancel_upload"); ?></span>
            </button>
          </div>

          <div class='col-sm-8 col-md-9'>
            <div id='uploadInfo'><?php
              if ($config->dragndrop) echo lang($config->multiUpload ? "select_or_drag_files" : "select_or_drag_file"); 
              else echo lang($config->multiUpload ? "just_select_files" : "just_select_file");
              ?></div>
            <div class='visible-xs h24'></div>
            <div id='progress' class='progress js-hide mt18'>
              <div class='progress-bar progress-bar-striped active'>
              </div>
              <div class='pct'>
              </div>
            </div>
            <?php if (!$config->isMSIE || $config->MSIE_version > 9) { ?>
              <div class='row speedIndicator js-hide'>
                <div class='col-xs-6 col-sm-3 text-right'><h3>&nbsp;<i class='fa fa-dashboard fa-fw'></i></h3></div>
                <div class='col-xs-6 col-sm-3'><h3><span class='upload-speed'>Speed</span></h3></div>
                <div class='col-xs-6 col-sm-3 text-right'><h3>&nbsp;<i class='fa fa-clock-o fa-fw'></i></h3></div>
                <div class='col-xs-6 col-sm-3'><h3><span class='upload-time'>Time</span></h3></div>
              </div>
            <?php } ?>
            <!-- <div class='speedIndicator js-hide'><h3><i class='fa fa-dashboard fa-fw'></i><span></span></h3></div> -->
          </div>
        </div> <!-- Uploader row -->






          <div class='row js-hide' id='singleUploadSucceeded'>
            <h1><?php echo lang("upload_succeeded"); ?> <a href='<?php echo $config->instDir; ?>/' class='btn nob btn-primary'><?php echo lang($config->multiUpload ? "upload_other_files" : "upload_another_file"); ?></a>
            <?php
              //adding files to current upload session??
              if ($config->addAnotherFiles) {
            ?>
                <a href='<?php echo $config->instDir; ?>/?u_key=<?php echo $u_key; ?>' class='btn nob btn-primary js-btn-add-files'><i class='fa fa-plus fa-fw'></i><?php echo lang($config->multiUpload ? "add_files" : "add_file"); ?></a>
            <?php
              }
            ?>
            </h1>
            <h3 class='susFName'><span class='js-susDataHeadline'>susName <i class='small'>susSize</i></span> <button class='btn btn-primary btn-xs js-btn-add-file-description'><i class='fa fa-pencil fa-fw'></i><span><?php echo lang("add_file_description"); ?></span></button></h3>

            <div class='col-xs-12'>

              <div class='js-file-description-wrapper js-hide'>
                <h4><?php echo lang("hl_file_description"); ?></h4>
                <div class="input-group input-group-lg">
                  <input type='text' name='susFileDescription' class='form-control' value='' />
                  <div class="input-group-btn">
                    <button class='btn btn-default js-btn-save-file-description disabled'><i class="fa fa-save fa-fw"></i><?php echo lang("save"); ?></button>
                  </div>
                </div>
              </div>

              <h4><?php echo lang("download_link"); ?></h4>
              <div class="input-group input-group-lg">
                <input type='text' name='susDownloadLink' class='form-control' value='susDownloadLink' readonly='readonly' />
                <div class="input-group-btn">
                  <button class='btn btn-primary btndown'><i class="fa fa-globe fa-fw"></i><?php echo lang("follow_link"); ?></button>
                  <button class='btn btn-primary dropdown-toggle' data-toggle="dropdown"><i class="fa fa-chevron-down"></i></button>
                  <ul class="dropdown-menu pull-right" role="menu">
                    <li><a href="#" class='js-follow-link'><i class="fa fa-globe fa-fw"></i><?php echo lang("follow_link"); ?></a></li>
                    <li><a href="#" class='js-generate-QR'><i class='fa fa-qrcode fa-fw'></i><?php echo lang("get_qr_code"); ?></a></li>
                  <?php
                    if ($config->shortUrls) {
                  ?>
                    <li><a href="#" class="js-shorten-URL"><i class='fa fa-compress fa-fw'></i><?php echo lang("set_short_url"); ?></a></li>
                  <?php
                    }
                  ?>
                    <li class='js-clipboard-holder'><a href="#" class="js-copy-URL"><i class='fa fa-copy fa-fw'></i><?php echo lang("copy_to_clipboard"); ?></a></li>
                    <li><a href="#" class="js-share-facebook"><i class='fa fa-facebook fa-fw'></i><?php echo lang("share_on_facebook"); ?></a></li>
                    <li><a href="#" class="js-share-twitter"><i class='fa fa-twitter fa-fw'></i><?php echo lang("share_on_twitter"); ?></a></li>
                  </ul>
                </div>
              </div>

              <h4><?php echo lang("delete_link"); ?></h4>
              <div class="input-group input-group-lg">
                <input type='text' name='susDeleteLink' class='form-control' value='susDeleteLink' readonly='readonly' />
                <div class="input-group-btn">
                  <button class='btn btn-danger btndel'><i class="fa fa-trash-o fa-fw"></i><?php echo lang("follow_link"); ?></button>
                  <button class='btn btn-danger dropdown-toggle' data-toggle="dropdown"><i class="fa fa-chevron-down"></i></button>
                  <ul class="dropdown-menu pull-right" role="menu">
                    <li><a href="#" class='js-follow-link'><i class="fa fa-globe fa-fw"></i><?php echo lang("follow_link"); ?></a></li>
                    <li><a href="#" class='js-generate-QR'><i class='fa fa-qrcode fa-fw'></i><?php echo lang("get_qr_code"); ?></a></li>
                  <?php
                    if ($config->shortUrls) {
                  ?>
                    <li><a href="#" class="js-shorten-URL"><i class='fa fa-compress fa-fw'></i><?php echo lang("set_short_url"); ?></a></li>
                  <?php
                    }
                  ?>
                    <li class='js-clipboard-holder'><a href="#" class="js-copy-URL"><i class='fa fa-copy fa-fw'></i><?php echo lang("copy_to_clipboard"); ?></a></li>
                  </ul>
                </div>
              </div>

             
              <?php
              if ($config->passwordProtection) {
              ?>
              <div>
                  <h4><?php echo lang("password_protect"); ?> - <small class='susPassword muted'><?php echo lang("password_protection_OFF"); ?></small></h4>
                  <div class='form-inline'>
                    <label class='checkbox nob pointer'><input type="checkbox" name="passwordProtection" id="passwordProtection" value="1" /> <?php echo lang("en_password_protection"); ?></label>
                  </div>
              </div>
              <div class='clearfix'></div>
              <?php
                }
                if ($config->delSettingsByUploader) {
                  $delXdownloads = preg_split("/\s*,\s*/",$config->delDownloadsNumbers,0,PREG_SPLIT_NO_EMPTY);

                  if ($delXdownloads || $config->delDays > 1) {
              ?>
                <hr class='hr-color1' />
                  <div class='col-xs-12'>
                  <?php          
                  $delXdays_options = $delXdownloads_options = "<option value='-1'>-----</option>";  
                  foreach ($delXdownloads as $delXdownload) {
                    $delXdownloads_options .= "<option>$delXdownload</option>";
                   }
                  //del after X days
                  if ($config->delDays > 1) { 
                    for ($i=$config->delDays;$i>=0;$i--) {
                      $delXdays_options .= "<option>$i</option>";
                    }
                  ?>
                    <div class='delXdays form-inline col-sm-6'><?php echo sprintf(lang("del_after_x_days"),"<select name='delXdays' class='form-control nice-select0'>$delXdays_options</select>"); ?></div>
                  <?php
                  }
                  if ($delXdownloads) {
                  ?>
                  <div class='delXdownloads form-inline col-sm-6'><?php echo sprintf(lang("del_after_x_downloads"),"<select name='delXdownloads' class='form-control nice-select0'>$delXdownloads_options</select>"); ?></div>
                  <?php
                    } //$delXdownloads
                  ?>
                </div>
                <div class='clearfix'></div>
                <hr class='hr-color1' />
               
              <?php
                  } //$delXdownloads || $config->delDays > 1
                } //$config->delSettingsByUploader
              ?>
              <div id='sendLinkInfoForm' class='row'>
                <form method='post' action='index.php' class='form-horizontal'>
                  <h4><?php echo lang("send_link"); ?> <small class='sendLinkMsgs text-danger'></small></h4>

                  <div class='col-md-6'>
                    <div class='form-group'>
                      <label class='control-label col-xs-4'><?php echo lang("from"); ?></label>
                      <div class='col-xs-8'>
                        <input type='email' name='mailFrom' value='' placeholder="<?php echo lang("mailfrom"); ?>" class='form-control' required='required' />
                      </div>
                    </div>
                  </div>
                  <div class='col-md-6'>
                    <div class='form-group'>
                      <label class='control-label col-xs-4'><?php echo lang("to"); ?></label>
                      <div class='col-xs-8'>
                        <input type='email' name='mailTo' value='' placeholder="<?php echo sprintf(lang($config->maxRcpt > 1 ? "max_recipients" : "mailto"),$config->maxRcpt); ?>" class='form-control js-tagsinput' required='required' />
                      </div>
                    </div>
                  </div>

                  <div class='js-message-wrapper js-hide'>
                    <div class='col-xs-12'>
                      <div class='form-group'>
                        <label class='control-label col-sm-2'><?php echo lang("message"); ?></label>
                        <div class='col-sm-10'>
                          <textarea name='message' class='form-control' rows='4' placeholder='<?php echo lang("placeholder_message"); ?>'></textarea>
                        </div>
                      </div>
                    </div>
                    <div class='col-xs-12'> 
                      <div class='form-group'>
                        <div class='col-sm-10 col-sm-offset-2 form-inline'> 
                          <label class='checkbox nob pointer'><input type="checkbox" name="show_message" value="1" /> <?php echo lang("show_message"); ?></label>
                        </div>
                      </div>
                    </div>
                  </div>

                  <div class='col-xs-12'> 
                    <div class='form-group'>
                      <div class='col-sm-10 col-sm-offset-2 form-inline'> 
                        <label class='checkbox nob pointer'><input type="checkbox" name="agree2terms" id="agree2terms" value="1" /> <?php echo lang("agree_to_terms"); ?></label>
                      </div>
                    </div>
                  </div>
                  <div class='col-xs-12'> 
                    <div class='form-group'>
                      <div class='col-sm-10 col-sm-offset-2'> 
                        <button class='btn btn-primary sendLinkInfo' disabled='disabled'><i class="fa fa-send fa-fw"></i><?php echo lang("send_download_link"); ?></button>
                        <button class='btn btn-default addmessage'><i class='fa fa-plus fa-fw'></i><?php echo lang("add_message"); ?></button>
                      </div>
                    </div>
                  </div>                
                </form>
              </div>

            </div> <!-- col-xs-12 -->
            
          </div> <!-- singleSuccess row -->






          <div class='row js-hide' id='multiUploadSucceeded'>
            <h1><?php echo lang("upload_succeeded"); ?> <a href='<?php echo $config->instDir; ?>/' class='btn nob btn-primary'><?php echo lang("upload_other_files"); ?></a>
            <?php
              //adding files to current upload session??
              if ($config->addAnotherFiles) {
            ?>
                <a href='<?php echo $config->instDir; ?>/?u_key=<?php echo $u_key; ?>' class='btn nob btn-primary js-btn-add-files'><i class='fa fa-plus fa-fw'></i><?php echo lang("add_files"); ?></a>
            <?php
              }
            ?>
            </h1>

            <h2><?php echo lang("all_files"); ?></h2>

            <div class='col-xs-12'>

              <h4><?php echo lang("group_link"); ?></h4>
              <div class="input-group input-group-lg">
                <input type='text' name='musGroupLink' class='form-control grouplink' value='<?php echo $config->baseGroupUrl . $u_key . ".html"; ?>' readonly='readonly' />
                <div class="input-group-btn">
                  <button class='btn btn-primary btngrp'><i class="fa fa-globe fa-fw"></i><?php echo lang("follow_link"); ?></button>
                  <button class='btn btn-primary dropdown-toggle' data-toggle="dropdown"><i class="fa fa-chevron-down"></i></button>
                  <ul class="dropdown-menu pull-right" role="menu">
                    <li><a href="#" class='js-follow-link'><i class="fa fa-globe fa-fw"></i><?php echo lang("follow_link"); ?></a></li>
                    <li><a href="#" class='js-generate-QR'><i class='fa fa-qrcode fa-fw'></i><?php echo lang("get_qr_code"); ?></a></li>
                  <?php
                    if ($config->shortUrls) {
                  ?>
                    <li><a href="#" class="js-shorten-URL"><i class='fa fa-compress fa-fw'></i><?php echo lang("set_short_url"); ?></a></li>
                    <!-- <li><a href="#" class="js-shorten-URL-all"><i class='fa fa-compress fa-fw'></i><?php echo lang("set_short_url"); ?>ALL</a></li> -->
                  <?php
                    }
                  ?>
                    <li class='js-clipboard-holder'><a href="#" class="js-copy-URL"><i class='fa fa-copy fa-fw'></i><?php echo lang("copy_to_clipboard"); ?></a></li>
                    <li><a href="#" class="js-share-facebook"><i class='fa fa-facebook fa-fw'></i><?php echo lang("share_on_facebook"); ?></a></li>
                    <li><a href="#" class="js-share-twitter"><i class='fa fa-twitter fa-fw'></i><?php echo lang("share_on_twitter"); ?></a></li>
                  </ul>
                </div>
              </div>

              <div class='multiItems mt20'>
                <div class='panel panel-default multiItem js-hide'>
                  <div class='panel-heading'><h2 class='musFName panel-title'><span class='js-musDataHeadline text-primary'>susName <i class='small'>susSize</i></span> <button class='btn btn-primary btn-xs js-btn-add-file-description'><i class='fa fa-pencil fa-fw'></i><span><?php echo lang("add_file_description"); ?></span></button></h2></div>

                  <div class='panel-body'>
                    <div class='js-file-description-wrapper js-hide col-xs-12'>
                      <h4><?php echo lang("hl_file_description"); ?></h4>
                      <div class="input-group">
                        <input type='text' name='musFileDescription' class='form-control' value='' />
                        <div class="input-group-btn">
                          <div class="input-group-btn">
                            <button class='btn btn-default js-btn-save-file-description disabled'><i class="fa fa-save fa-fw"></i><?php echo lang("save"); ?></button>
                          </div>
                        </div>
                      </div>
                    </div>

                    <div class='col-md-6'>
                      <h4><?php echo lang("download_link"); ?></h4>
                      <div class="input-group">
                        <input type='text' name='musDownloadLink' class='form-control' value='musDownloadLink' readonly='readonly' />
                        <div class="input-group-btn">
                          <button class='btn btn-primary btndown'><i class="fa fa-globe fa-fw"></i><?php echo lang("follow_link"); ?></button>
                          <button class='btn btn-primary dropdown-toggle' data-toggle="dropdown"><i class="fa fa-chevron-down"></i></button>
                          <ul class="dropdown-menu pull-right" role="menu">
                            <li><a href="#" class='js-follow-link'><i class="fa fa-globe fa-fw"></i><?php echo lang("follow_link"); ?></a></li>
                            <li><a href="#" class='js-generate-QR'><i class='fa fa-qrcode fa-fw'></i><?php echo lang("get_qr_code"); ?></a></li>
                          <?php
                            if ($config->shortUrls) {
                          ?>
                            <li><a href="#" class="js-shorten-URL"><i class='fa fa-compress fa-fw'></i><?php echo lang("set_short_url"); ?></a></li>
                          <?php
                            }
                          ?>
                            <li class='js-clipboard-holder'><a href="#" class="js-copy-URL"><i class='fa fa-copy fa-fw'></i><?php echo lang("copy_to_clipboard"); ?></a></li>
                            <li><a href="#" class="js-share-facebook"><i class='fa fa-facebook fa-fw'></i><?php echo lang("share_on_facebook"); ?></a></li>
                            <li><a href="#" class="js-share-twitter"><i class='fa fa-twitter fa-fw'></i><?php echo lang("share_on_twitter"); ?></a></li>
                          </ul>
                        </div>
                      </div>
                    </div>
                    <div class='col-md-6'><h4><?php echo lang("delete_link"); ?></h4>
                      <div class="input-group">
                        <input type='text' name='musDeleteLink' class='form-control' value='musDeleteLink' readonly='readonly' />
                        <div class="input-group-btn">
                          <button class='btn btn-danger btndel'><i class="fa fa-globe fa-fw"></i><?php echo lang("follow_link"); ?></button>
                          <button class='btn btn-danger dropdown-toggle' data-toggle="dropdown"><i class="fa fa-chevron-down"></i></button>
                          <ul class="dropdown-menu pull-right" role="menu">
                            <li><a href="#" class='js-follow-link'><i class="fa fa-globe fa-fw"></i><?php echo lang("follow_link"); ?></a></li>
                            <li><a href="#" class='js-generate-QR'><i class='fa fa-qrcode fa-fw'></i><?php echo lang("get_qr_code"); ?></a></li>
                          <?php
                            if ($config->shortUrls) {
                          ?>
                            <li><a href="#" class="js-shorten-URL"><i class='fa fa-compress fa-fw'></i><?php echo lang("set_short_url"); ?></a></li>
                          <?php
                            }
                          ?>
                            <li class='js-clipboard-holder'><a href="#" class="js-copy-URL"><i class='fa fa-copy fa-fw'></i><?php echo lang("copy_to_clipboard"); ?></a></li>
                          </ul>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <?php
              if ($config->passwordProtection) {
              ?>
              <div class='row'>
                <h3><?php echo lang("password_protect"); ?> - <small class='musPassword muted'><?php echo lang("password_protection_OFF"); ?></small></h3>
                <div class='col-xs-12'>
                  <div class='form-inline'>
                    <label class='checkbox nob pointer'><input type="checkbox" name="passwordProtectionMulti" id="passwordProtectionMulti" value="1" /> <?php echo lang("en_password_protection"); ?></label>
                  </div>
                </div>
              </div>
              <div class='clearfix'></div>
              <?php
                }
              ?>
              <?php
                if ($config->delSettingsByUploader) {
                  $delXdownloads = preg_split("/\s*,\s*/",$config->delDownloadsNumbers,0,PREG_SPLIT_NO_EMPTY);
  
                  if ($delXdownloads || $config->delDays > 1) {

                  ?>
                    <hr class='hr-color1' />
                      <div class='col-xs-12'>

                        <?php          
                        //del after X days
                        if ($config->delDays > 1) { 
                          $delXdays_options = $delXdownloads_options = "<option value='-1'>-----</option>";  
                          for ($i=$config->delDays;$i>=0;$i--) {
                            $delXdays_options .= "<option>$i</option>";
                          }
                        ?>
                        <div class='delXdaysMulti form-inline col-sm-6'><?php echo sprintf(lang("del_after_x_days_multi"),"<select name='delXdaysMulti' class='form-control nice-select0'>$delXdays_options</select>"); ?></div>
                        <?php
                        } //$config->delDays


                    if ($delXdownloads) {
                      foreach ($delXdownloads as $delXdownload) {
                        $delXdownloads_options .= "<option>$delXdownload</option>";
                       }
                      ?>
                      <div class='delXdownloadsMulti form-inline col-sm-6'><?php echo sprintf(lang("del_after_x_downloads_multi"),"<select name='delXdownloadsMulti' class='form-control nice-select0'>$delXdownloads_options</select>"); ?></div>
                      <?php
                      }
                    ?>

                    
                    </div>
                    <div class='clearfix'></div>
                    <hr class='hr-color1' />
                   
              <?php
                  } //$delXdownloads || $config->delDays > 1
                } //delSettingsByUploader
              ?>
              <div id='sendLinkInfoFormMulti' class='row'>
               <form method='post' action='index.php' class='form-horizontal'>
                  <input type='hidden' name='multi_u_key' value='<?php echo $u_key; ?>' />
                  <h3><?php echo lang("send_links"); ?> <small class='sendLinkMsgs text-danger'></small></h3>

                  <div class='col-md-6'>
                    <div class='form-group'>
                      <label class='control-label col-xs-4'><?php echo lang("from"); ?></label>
                      <div class='col-xs-8'>
                        <input type='email' name='mailFrom' value='' placeholder="<?php echo lang("mailfrom"); ?>" class='form-control' required='required' />
                      </div>
                    </div>
                  </div>
                  <div class='col-md-6'>
                    <div class='form-group'>
                      <label class='control-label col-xs-4'><?php echo lang("to"); ?></label>
                      <div class='col-xs-8'>
                        <input type='email' name='mailTo' value='' placeholder="<?php echo sprintf(lang($config->maxRcpt > 1 ? "max_recipients" : "mailto"),$config->maxRcpt); ?>" class='form-control js-tagsinput' required='required' />
                      </div>
                    </div>
                  </div>

                  <div class='js-message-wrapper js-hide'>
                    <div class='col-xs-12'>
                      <div class='form-group'>
                        <label class='control-label col-sm-2'><?php echo lang("message"); ?></label>
                        <div class='col-sm-10'>
                          <textarea name='message' class='form-control' rows='4' placeholder='<?php echo lang("placeholder_message"); ?>'></textarea>
                        </div>
                      </div>         
                    </div>
                    <div class='col-xs-12'> 
                      <div class='form-group'>
                        <div class='col-sm-10 col-sm-offset-2 form-inline'> 
                          <label class='checkbox nob pointer'><input type="checkbox" name="show_message" value="1" /> <?php echo lang("show_message"); ?></label>
                        </div>
                      </div>
                    </div>
                  </div>

                  <div class='col-xs-12'> 
                    <div class='form-group'>
                      <div class='col-sm-10 col-sm-offset-2 form-inline'> 
                        <label class='checkbox nob pointer'><input type="checkbox" name="agree2terms" id="agree2termsMulti" value="1" /> <?php echo lang("agree_to_terms"); ?></label>
                      </div>
                    </div>
                  </div>
                  <div class='col-xs-12'> 
                    <div class='form-group'>
                      <div class='col-sm-10 col-sm-offset-2'> 
                        <button class='btn btn-primary sendLinkInfoMulti' disabled='disabled'><i class="fa fa-send fa-fw"></i><?php echo lang("send_download_links"); ?></button>
                        <button class='btn btn-default addmessage'><i class='fa fa-plus fa-fw'></i><?php echo lang("add_message"); ?></button>
                      </div>
                    </div>
                  </div> 



                </form>
              </div>
            </div> <!-- col-xs-12 -->

          </div> <!-- multiSuccess row -->

      </div>
        </div> <!-- container -->

      <div id='landingInfoRow' class='container'>
        <div class="row">
          <div class="col-sm-4">
            <h2><i class='fa fa-hdd-o fa-fw fa-lg'></i><?php echo $config->maxFileSize; ?> MB </h2>
            <p class='clearfix'></p>
            <p><?php echo lang("index1text"); ?></p>
          </div>
          <div class="col-sm-4">
            <h2><i class='fa fa-globe fa-fw fa-lg'></i><?php echo lang("index2hl"); ?></h2>
            <p class='clearfix'></p>
            <p><?php echo lang("index2text"); ?></p>
         </div>
          <div class="col-sm-4">
            <h2><i class='fa fa-calendar fa-fw fa-lg'></i><?php echo lang("index3hl"); ?></h2>
            <p class='clearfix'></p>
            <p><?php echo sprintf(lang("index3text"),$config->delDays); ?></p>
          </div>
        </div>
        <hr />
      </div>
    

<div id="terms" class="js-hide">
  <h3><?php echo lang("hl_terms"); ?></h3>
  <?php include("lang/" . $config->lang . "/terms.lang.php"); ?>
</div>

<?php include("includes/footer.php"); ?>