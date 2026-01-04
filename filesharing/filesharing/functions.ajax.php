<?php
include_once("config.php");
include("classes/sfs.class.php");
$SFS = new SFS($config);
$config = $SFS->config;
include_once("functions.php");

include_once("lang/" . $config->lang . "/main.lang.php");

$action = isset($_REQUEST["action"]) ? $_REQUEST["action"] : null;
if (!preg_match("|^" . $config->instUrl . '/|', $_SERVER["HTTP_REFERER"])) {
  exit("illegal access");
}

/****
* SINGLE: send download information after upload [+]
****/
if ($action == "sendFileInfo") {
    $u_key = $_REQUEST["u_key"];
    $DownLink = $_REQUEST["DownLink"];
    $DelLink = $_REQUEST["DelLink"];
    $mailFrom = $_REQUEST["mailFrom"];
    $mailTo = $_REQUEST["mailTo"];
    $message = trim(strip_tags($_REQUEST["message"]));
    $show_message = $_REQUEST["show_message"];

    //filechecks - prevent from spaming
    //shortened???
    if (preg_match('|^https?://bit\.ly/|',$DownLink)) {
      $pinfo = pathinfo($SFS->BitlyShortener($DownLink,"expand"));
    } elseif (preg_match('|^https?://adf\.ly/|',$DownLink)) {
      $pinfo = pathinfo($SFS->AdflyShortener($DownLink,"expand"));
    } else {
      $pinfo = pathinfo($DownLink);
    }

    $finfo = getFileInfos($pinfo["filename"],"downloadFromShort");
    if (!$finfo) exit(lang("error_file_failure"));
   	
    if (!is_email($mailFrom)) exit(lang("error_from_address_failure"));

    if (!$mailTo) exit(lang("error_both_fields_required"));
    else {
      $mailToErrors = array();
      $mailToAddresses = explode(",",$mailTo);
      foreach ($mailToAddresses as $addr) {
        $addr = trim(strtolower($addr));
        if ($addr) {
          if (!is_email($addr)) $mailToErrors[] = $addr;
          else $sendToAddr[] = $addr;
        }
      }
    }
    if ($mailToErrors) exit(sprintf(lang("error_mailto_troubles"),implode(", ",$mailToErrors)));
    if (!$sendToAddr) exit(lang("error_mailto_none_valid"));

    //number of maximum recipientsexceeded?
    $sendToAddrCnt = count($sendToAddr);
    if ($sendToAddrCnt > $config->maxRcpt) {
      exit (sprintf(lang($sendToAddrCnt == 1 ? "error_mailto_max_one" : "error_mailto_max_X"),$config->maxRcpt));
    }


    //Password protected file
    $PasswordLine = null;
    if ($finfo->pwd_protected && $finfo->pwd) $PasswordLine = sprintf(lang("password_line_mailings"),$finfo->pwd) . "<br /><br />";

    $toSenderBody = file_get_contents("lang/" . $config->lang . "/to.sender.mail.html");
    $toRcptBody = file_get_contents("lang/" . $config->lang . "/to.recipient.mail.html");
    
    if ($show_message) {
      $sql = "replace into `" . $config->tablePrefix . "messages` set u_key = " . $SFS->dbquote($u_key) . ", message = " . $SFS->dbquote($message);
      $SFS->dbquery($sql);
    }
    if ($message) $message = "-----<br />" . nl2br($message) . "<br />-----<br /><br />";


    $fileDescription = null;  
    if ($finfo->descr_long) $fileDescription = lang("mail_file_description") . ":<br />" . $finfo->descr_long . "<br /><br />";

    $find = array("[mailFrom]","[mailTo]","[fileName]","[fileSize]","[DownLink]","[DelLink]","[siteName]","[PasswordLine]","[message]","[delDays]","[FileDescription]");
    foreach ($sendToAddr as $addr) {
      $repl = array($mailFrom,$addr,$finfo->descr,fsize($finfo->fsize),$DownLink,$DelLink,$config->siteName,$PasswordLine,$message,$config->delDays,$fileDescription);
      mail($addr,UTF8subject(sprintf(lang("subject_download_information"),$config->siteName)),str_replace($find,$repl,$toRcptBody),$config->mailHeaders,$config->mailParams);
    }
    $repl = array($mailFrom,implode(", ",$sendToAddr),$finfo->descr,fsize($finfo->fsize),$DownLink,$DelLink,$config->siteName,$PasswordLine,$message,$config->delDays,$fileDescription);
    $toSenderBody = str_replace($find,$repl,$toSenderBody);
    mail($mailFrom,UTF8subject(sprintf(lang("subject_upload_information"),$config->siteName)),$toSenderBody,$config->mailHeaders,$config->mailParams);
  
    $SFS->sendLastPHPError();
  
    exit("OK");
  }
/****
* SINGLE: send download information after upload [-]
****/

/****
* MULTI: send download information after upload [+]
****/
if ($action == "sendMultiFileInfo") {
    $u_key = $_REQUEST["u_key"];
    $mailFrom = $_REQUEST["mailFrom"];
    $mailTo = $_REQUEST["mailTo"];
    $message = trim(strip_tags($_REQUEST["message"]));
    $show_message = $_REQUEST["show_message"];

    //filechecks - prevent from spaming
    $fileInfos = getFileInfos($u_key,"upload");
    if (!$fileInfos) exit(lang("error_file_failure"));
    
    if (!is_email($mailFrom)) exit(lang("error_from_address_failure"));

    if (!$mailTo) exit(lang("error_both_fields_required"));
    else {
      $mailToErrors = array();
      $mailToAddresses = explode(",",$mailTo);
      foreach ($mailToAddresses as $addr) {
        $addr = trim(strtolower($addr));
        if ($addr) {
          if (!is_email($addr)) $mailToErrors[] = $addr;
          else $sendToAddr[] = $addr;
        }
      }
    }
    if ($mailToErrors) exit(sprintf(lang("error_mailto_troubles"),implode(", ",$mailToErrors)));
    if (!$sendToAddr) exit(lang("error_mailto_none_valid"));

    //number of maximum recipientsexceeded?
    $sendToAddrCnt = count($sendToAddr);
    if ($sendToAddrCnt > $config->maxRcpt) {
      exit (sprintf(lang($sendToAddrCnt == 1 ? "error_mailto_max_one" : "error_mailto_max_X"),$config->maxRcpt));
    }

    //Password protected file
    $PasswordLine = null;
    if ($fileInfos->pwd_protected && $fileInfos->pwd) $PasswordLine = "<br />" . sprintf(lang("password_line_mailings"),$fileInfos->pwd) . "<br />";

    $toSenderBody = file_get_contents("lang/" . $config->lang . "/to.sender.multi.mail.html");
    $toRcptBody = file_get_contents("lang/" . $config->lang . "/to.recipient.multi.mail.html");

    preg_match_all('/\[filesList\](.*)\[\/filesList\]/s',$toRcptBody,$flistArr);
    $flistBlockR = $flistArr[1][0];
    preg_match_all('/\[filesList\](.*)\[\/filesList\]/s',$toSenderBody,$flistArr);
    $flistBlockS = $flistArr[1][0];

    //get all files
    $i=0;
    $sql = "select * from `" . $config->tablePrefix . "files` where u_key = " . $SFS->dbquote($u_key);
    $res = $SFS->dbquery($sql);
    $numFiles = mysqli_num_rows($res);
    $filesListR = $filesListS = null;
    while ($row = mysqli_fetch_object($res)) {
      list($fileKey,$delFileKey) = $SFS->genFileKeys($row->id);
      $fileDescription = null;
      if ($row->descr_long) $fileDescription = lang("mail_file_description") . ": " . $row->descr_long . "<br />";
      $find = array("[Number]","[Name]","[Size]","[DownLink]","[DelLink]","[FileDescription]");
      $repl = array("#".++$i,$row->descr,fsize($row->fsize),$config->instUrl . "/" . $row->shortkey,$config->baseDeleteUrl . $delFileKey . ".html",$fileDescription);
      $filesListR .= str_replace($find,$repl,$flistBlockR);
      $filesListS .= str_replace($find,$repl,$flistBlockS);
    }

    $toRcptBody = preg_replace('/\[filesList\].*\[\/filesList\]/s',$filesListR,$toRcptBody);
    $toSenderBody = preg_replace('/\[filesList\].*\[\/filesList\]/s',$filesListS,$toSenderBody);

    if ($show_message) {
      $sql = "replace into `" . $config->tablePrefix . "messages` set u_key = " . $SFS->dbquote($u_key) . ", message = " . $SFS->dbquote($message);
      $SFS->dbquery($sql);
    }
    if ($message) $message = "-----<br />" . nl2br($message) . "<br />-----<br /><br />";

    $find = array("[mailFrom]","[mailTo]","[numFiles]","[siteName]","[PasswordLine]","[message]","[delDays]","[groupLink]");
    foreach ($sendToAddr as $addr) {
      $repl = array($mailFrom,$addr,$numFiles,$config->siteName,$PasswordLine,$message,$config->delDays,$config->baseGroupUrl . $u_key . ".html");
      mail($addr,UTF8subject(sprintf(lang("subject_download_information_multi"),$numFiles,$config->siteName)),str_replace($find,$repl,$toRcptBody),$config->mailHeaders,$config->mailParams);
    }
    $repl = array($mailFrom,implode(", ",$sendToAddr),$numFiles,$config->siteName,$PasswordLine,$message,$config->delDays,$config->baseGroupUrl . $u_key . ".html");
    $toSenderBody = str_replace($find,$repl,$toSenderBody);
    mail($mailFrom,UTF8subject(sprintf(lang("subject_upload_information"),$config->siteName)),$toSenderBody,$config->mailHeaders,$config->mailParams);
  
    $SFS->sendLastPHPError();

    exit("OK");
  }
/****
* MULTI: send download information after upload [-]
****/


/****
* Contact [+]
****/
if ($action == "contact") {
    $name = trim(stripslashes($_POST['name']));
    $email = trim(strtolower($_POST['email']));
    $tel = trim(stripslashes($_POST['tel']));
    $message = trim(stripslashes($_POST['message']));
    $captcha = isset($_POST['captcha']) ? trim(strtolower($_POST['captcha'])) : null;

    $error = array();
    if(!$name) $error[] = lang("error_noname");
    if(!$email) $error[] = lang("error_noemail");
    elseif (!is_email($email)) $error[] = lang("error_email_failure");
    if(!$message) $error[] = lang("error_nomessage");
    if ($config->captchaContact) {
      if (!$captcha) $error[] = lang("error_nocaptcha");
      elseif (isset($_SESSION['captcha']) && $_SESSION['captcha'] != $captcha) $error[] = lang("error_wrongcaptcha");
    }
    if(!$error) {
      $mailmess = "Name: $name\nEmail: $email\nTel: $tel\nIP: " . $_SERVER["REMOTE_ADDR"] . "\n\n--\n$message";
      mail($config->contact_mail, strip_tags($config->siteName) . " WebForm", $mailmess,
         "From: ".$name." <".$email.">\r\n"
        ."Reply-To: ".$email."\r\n"
        ."Content-Type: text/plain; charset=utf-8\r\n"
        ."X-Mailer: PHP/" . phpversion(),$config->mailParams);
      echo "OK";
      $success = lang("success_mess_sent");
    } else {
      $error = '<div class="alert alert-danger">' . implode("<br />",$error) . '</div>';
      echo $error;
    }
}
/****
* Contact [-]
****/


/****
* Report File [+]
****/
if ($action == "abuse") {
    $name = trim(stripslashes($_POST['name']));
    $email = trim(strtolower($_POST['email']));
    $message = trim(stripslashes($_POST['message']));
    $key = trim(stripslashes($_POST['dk']));
    $shortkey = trim(stripslashes($_POST['sk']));
    $captcha = isset($_POST['captcha']) ? trim(strtolower($_POST['captcha'])) : null;

    $error = array();
    if(!$name) $error[] = lang("error_noname");
    if(!$email) $error[] = lang("error_noemail");
    elseif (!is_email($email)) $error[] = lang("error_email_failure");
    if(!$message) $error[] = lang("error_nomessage");
    if ($key) {
      $finfo = getFileInfos($key);
    } elseif ($shortkey) {
      $finfo = getFileInfos($shortkey,"downloadFromShort");
    }
    if (!$finfo) exit(lang("error_file_failure"));
    if ($config->captchaContact) {
      if (!$captcha) $error[] = lang("error_nocaptcha");
      elseif (isset($_SESSION['captcha']) && $_SESSION['captcha'] != $captcha) $error[] = lang("error_wrongcaptcha");
    }
    if(!$error) {
      list($fkey,$delkey) = $SFS->genFileKeys($finfo->id);
      $mailmess = "Name: $name\nEmail: $email\n\nIP: " . $_SERVER["REMOTE_ADDR"] . "\n" .
        "File Name: " . $finfo->descr . "\nFile Size: " . fsize($finfo->fsize) . "\nDownload URL: " . ($shortkey ? $config->instUrl . "/" . $shortkey : $config->baseDownloadUrl . $key . ".html") . "\n" .
        "Delete URL: " . $config->baseDeleteUrl . $delkey . ".html\n\n--\n$message";
      mail($config->contact_mail, strip_tags($config->siteName) . " - File reported", $mailmess,
         "From: ".$name." <".$email.">\r\n"
        ."Reply-To: ".$email."\r\n"
        ."Content-Type: text/plain; charset=utf-8\r\n"
        ."X-Mailer: PHP/" . phpversion(),$config->mailParams);
      echo "OK";
      $success = lang("success_mess_sent");
    } else {
      $error = '<div class="alert alert-danger">' . implode("<br />",$error) . '</div>';
      echo $error;
    }
}
/****
* Report File [-]
****/


/****
* Authenticated Admins only [+]
****/

if ($sfs_auth) {

  //delete file
  if ($action == "delFile") {
    $error = null;
    $fid = intval($_REQUEST["fid"]);
    if (!$fid) $error = "Insufficient data provided.";
    if (!$error) {
      $sql = "select * from `" . $config->tablePrefix . "files` where id = '$fid' and uid = '0'";
      $res = $SFS->dbquery($sql);
      if (!mysqli_num_rows($res)) $error = "File cannot be found.";
    }
    if (!$error) {  
      $row = mysqli_fetch_object($res);
      $sql = "delete from `" . $config->tablePrefix . "files` where id = '$fid' and uid = '0'";
      $SFS->dbquery($sql);
      $file_path = $config->uploadDir . $row->uid . "/" . $fid;
      if (file_exists($file_path)) xrmdir($file_path);
      $success = "The file was removed successfully.";
    }
    exit ($error?$error:"OK");
  }

  //(un)lock file
  if ($action == "handleFileLock") {
    $error = null;
    $fid = intval($_REQUEST["fid"]);
    $lockAction = $_REQUEST["lockAction"];
    if (!$fid) $error = "Insufficient data provided.";
    if (!$error) {
      $sql = "select * from `" . $config->tablePrefix . "files` where id = '$fid' and uid = '0'";
      $res = $SFS->dbquery($sql);
      if (!mysqli_num_rows($res)) $error = "File cannot be found.";
    }
    if (!$error) {
      $sql = "update `" . $config->tablePrefix . "files` set locked = '" . ($lockAction == "lockFile" ? 1 : 0) . "' where id = '$fid' and uid = '0'";
      $SFS->dbquery($sql);
    }
    $SFS->sendLastPHPError();
    exit ($error?$error:"OK");
  }

  //open timezone helper
  if ($action == "getTZhelper") {
    $tzHint = false;
    $sql = "select now() as d, @@session.time_zone as tz";
    $res = $SFS->dbquery($sql);
    $row = mysqli_fetch_object($res);
    $dbdate =  $row->d;
    $dbtz = $row->tz;
    $wsdate = date("Y-m-d H:i:s");  

    $tzData = array("wsdate" => $wsdate,
                    "date_default_timezone_get" => date_default_timezone_get(),
                    "dbtz" => $dbtz,
                    "dbdate" => $dbdate,
                    );
    if ($dbdate != $wsdate) {
      $tzHint = "<div class='alert alert-danger'>It seems there are differences between the output times of your Webserver and the time settings of your database server.</div>";
      $wsUTC = date("P");
      $tzHint .= '<div class="alert alert-info">Please try to set <code>Timezone Correction</code> to <code>' . $wsUTC . '</code></div>';
    } else {
      $tzHint = "<div class='alert alert-success'>It seems there are no time differences between your webserver and your database server.</div>";
    }
    echo json_encode(array("tzData" => $tzData, "tzHint" => $tzHint));
    $SFS->sendLastPHPError();
    exit;
  }

  //save db timezone correction
  if ($action == "save_db_timezoneCorrection") {
    $error = $success = false;
    $direction = $_REQUEST["direction"];
    $hours = intval($_REQUEST["hours"]);
    $minutes = intval($_REQUEST["minutes"]);

    if (!$hours && !$minutes) $db_timezoneCorrection = "";
    else {
      if (!in_array($direction,array("+","-"))) {
        $error = "Please select the direction of the timezone correction. (+/-).";
      }
      $db_timezoneCorrection = "$direction" . sprintf("%1$02d:%2$02d",$hours,$minutes); 
    }
    if (!$error) {
      $sql = "update `" . $config->tablePrefix . "config` set db_timezoneCorrection = " . $SFS->dbquote($db_timezoneCorrection) . " where id = 1";
      $SFS->dbquery($sql);
      $success = "The Timezone Correction has been updated successfully";
    }
    echo json_encode(array("success" => $success, "error" => $error));
    $SFS->sendLastPHPError();
    exit;
  }

  //save timezone
  if ($action == "save_timezone") {
    $error = $success = false;

    $timezone = trim($_REQUEST["timezone"]);

    if (!$timezone) $error = "Please select a timezone for your webproject";

    if (!$error) {
      $sql = "update `" . $config->tablePrefix . "config` set timezone = " . $SFS->dbquote($timezone) . " where id = 1";
      $SFS->dbquery($sql);
      $success = "Your Timezone has been updated successfully";
    }

    echo json_encode(array("success" => $success, "error" => $error));
    $SFS->sendLastPHPError();
    exit;
  }

  //save/change XSendFile
  if ($action == "save_xsendfile") {
    $success = false;

    $XSendFile = $SFS->config->XSendFile ? 0 : 1;
    $sql = "update `" . $config->tablePrefix . "config` set XSendFile = '" . intval($XSendFile) . "' where id = 1";
    $SFS->dbquery($sql);
    $success = "XSendFile has been " . ($XSendFile ? "enabled" : "disabled") . " successfully";

    echo json_encode(array("success" => $success, "XSendFile" => $XSendFile));
    $SFS->sendLastPHPError();
    exit;
  }

  //save kbps for bandwidth throtteling
  if ($action == "save_kbps") {
    $success = false;

    $kbps = intval($_REQUEST["kbps"]);

    if ($kbps < 0) $kbps = 0;

    $sql = "update `" . $config->tablePrefix . "config` set kbps = '" . intval($kbps) . "' where id = 1";
    $SFS->dbquery($sql);
    if ($kbps) $success = "Your bandwidth has been set successfully";
    else $success = "Bandwidth throtteling has been disabled successfully";

    echo json_encode(array("success" => $success, "kbps" => $kbps));
    $SFS->sendLastPHPError();
    exit;
  }

  //save file expiration days
  if ($action == "save_deldays") {
    $success = false;

    $delDays = intval($_REQUEST["delDays"]);

    if ($delDays < 0) $delDays = -1;

    $sql = "update `" . $config->tablePrefix . "config` set delDays = '" . intval($delDays) . "' where id = 1";
    $SFS->dbquery($sql);
    if ($delDays > -1) $success = "Your expiration days has been set successfully";
    else $success = "Atomatic deletetion has been disabled successfully";

    echo json_encode(array("success" => $success, "delDays" => $delDays));
    $SFS->sendLastPHPError();
    exit;
  }

  //save auto deletion dependency
  if ($action == "save_delon") {
    $success = false;

    $delOn = trim($_REQUEST["delOn"]);

    if ($delOn != "upload") $delOn = "download";

    $sql = "update `" . $config->tablePrefix . "config` set delOn = " . $SFS->dbquote($delOn) . " where id = 1";
    $SFS->dbquery($sql);
    $success = "Auto deletion is now based on the date of file $delOn.";

    echo json_encode(array("success" => $success));
    $SFS->sendLastPHPError();
    exit;
  }

  //save max number of downloads list
  if ($action == "save_deldownloadsnumbers") {
    $success = $error = false;

    $delDownloadsNumbers = trim($_REQUEST["delDownloadsNumbers"]);

    $delDNArr = preg_split('/[^\d]/', $delDownloadsNumbers, -1, PREG_SPLIT_NO_EMPTY);
    sort($delDNArr);
    $delDownloadsNumbers = implode(",",array_unique($delDNArr));

    if (!$delDownloadsNumbers) {
      $error = "Please define a (valid) list of possible maximum downloads (1,2,3,4,5,10,15).";
    }

    if (!$error) {
      $sql = "update `" . $config->tablePrefix . "config` set delDownloadsNumbers = " . $SFS->dbquote($delDownloadsNumbers) . " where id = 1";
      $SFS->dbquery($sql);
      $success = "The list of possible max downloads was saved successfully.";
    }

    echo json_encode(array("success" => $success, "error" => $error, "delDownloadsNumbers" => $delDownloadsNumbers));
    $SFS->sendLastPHPError();
    exit;
  }


  //enable/disable delSettingsByUploader
  if ($action == "save_delsettingsbyuploader") {
    $success = false;

    $delSettingsByUploader = $SFS->config->delSettingsByUploader ? 0 : 1;
    $sql = "update `" . $config->tablePrefix . "config` set delSettingsByUploader = '" . intval($delSettingsByUploader) . "' where id = 1";
    $SFS->dbquery($sql);
    $success = "Possibility to set deletion options by uploader has been " . ($delSettingsByUploader ? "enabled" : "disabled") . " successfully";

    echo json_encode(array("success" => $success, "delSettingsByUploader" => $delSettingsByUploader));
    $SFS->sendLastPHPError();
    exit;
  }

  //save download protecion dependency
  if ($action == "save_downloadprotection") {
    $success = $error = false;

    $downloadProtection = trim($_REQUEST["downloadProtection"]);
    if (!$downloadProtection) $downloadProtection = 0;

    if (!in_array($downloadProtection,array(0,"IP","SESSION"))) {
      $error = "Please use one of the given download protection options";
    }

    if (!$error) {
      $sql = "update `" . $config->tablePrefix . "config` set downloadProtection = " . $SFS->dbquote($downloadProtection) . " where id = 1";
      $SFS->dbquery($sql);
      $success = "The download protection dependency was updated successfully.";
    }


    echo json_encode(array("success" => $success, "error" => $error));
    $SFS->sendLastPHPError();
    exit;
  }

  
  //enable/disable password protection
  if ($action == "save_passwordprotection") {
    $success = false;

    $passwordProtection = $SFS->config->passwordProtection ? 0 : 1;
    $sql = "update `" . $config->tablePrefix . "config` set passwordProtection = '" . intval($passwordProtection) . "' where id = 1";
    $SFS->dbquery($sql);
    $success = "Possibility to password protect files by uploader has been " . ($passwordProtection ? "enabled" : "disabled") . " successfully";

    echo json_encode(array("success" => $success, "passwordProtection" => $passwordProtection));
    $SFS->sendLastPHPError();
    exit;
  }

  //save seconds before download should be okay
  if ($action == "save_downloadseconds") {
    $success = false;

    $downloadSeconds = intval($_REQUEST["downloadSeconds"]);

    if ($downloadSeconds < 0) $downloadSeconds = 0;

    $sql = "update `" . $config->tablePrefix . "config` set downloadSeconds = '" . intval($downloadSeconds) . "' where id = 1";
    $SFS->dbquery($sql);
    if ($downloadSeconds) $success = "The download seconds has been set successfully";
    else $success = "The download seconds has been disabled successfully";

    echo json_encode(array("success" => $success, "downloadSeconds" => $downloadSeconds));
    $SFS->sendLastPHPError();
    exit;
  }


  //save upload max file size
  if ($action == "save_maxfilesize") {
    $success = false;

    $maxFileSize = floatval(str_replace(",",".",$_REQUEST["maxFileSize"]));

    if ($maxFileSize < 1) $maxFileSize = 1;

    $maxFileSize = round($maxFileSize/5,1) * 5;

    $sql = "update `" . $config->tablePrefix . "config` set maxFileSize = '" . intval($maxFileSize) . "' where id = 1";
    $SFS->dbquery($sql);
    $success = "Your maximum upload file size has been updated successfully";

    echo json_encode(array("success" => $success, "maxFileSize" => $maxFileSize));
    $SFS->sendLastPHPError();
    exit;
  }


  //enable/disable multiuploads
  if ($action == "save_multiupload") {
    $success = false;

    $multiUpload = $SFS->config->multiUploadDB ? 0 : 1;
    $sql = "update `" . $config->tablePrefix . "config` set multiUpload = '" . intval($multiUpload) . "' where id = 1";
    $SFS->dbquery($sql);
    $success = "Possibility to upload multiple files at once has been " . ($multiUpload ? "enabled" : "disabled") . " successfully";

    echo json_encode(array("success" => $success, "multiUpload" => $multiUpload));
    $SFS->sendLastPHPError();
    exit;
  }


  //save max number of multi files uploads
  if ($action == "save_maxmultifiles") {
    $success = false;

    $maxMultiFiles = intval($_REQUEST["maxMultiFiles"]);

    if ($maxMultiFiles < 2) $maxMultiFiles = 2;

    $sql = "update `" . $config->tablePrefix . "config` set maxMultiFiles = '" . intval($maxMultiFiles) . "' where id = 1";
    $SFS->dbquery($sql);
    $success = "Your max number of multiple files for uploads has been updated successfully";

    echo json_encode(array("success" => $success, "maxMultiFiles" => $maxMultiFiles));
    $SFS->sendLastPHPError();
    exit;
  }

  //enable/disable additional files
  if ($action == "save_addanotherfiles") {
    $success = false;

    $addAnotherFiles = $SFS->config->addAnotherFiles ? 0 : 1;
    $sql = "update `" . $config->tablePrefix . "config` set addAnotherFiles = '" . intval($addAnotherFiles) . "' where id = 1";
    $SFS->dbquery($sql);
    $success = "Possibility to add files to current upload sessions has been " . ($addAnotherFiles ? "enabled" : "disabled") . " successfully";

    echo json_encode(array("success" => $success, "addAnotherFiles" => $addAnotherFiles));
    $SFS->sendLastPHPError();
    exit;
  }

  //save allowed file extensions
  if ($action == "save_extallowed") {
    $success = $error = false;

    $extAllowed = trim($_REQUEST["extAllowed"]);

    $extAllowedArr = preg_split('/,/', preg_replace('/[\.\s]/','',strtolower($extAllowed)), -1, PREG_SPLIT_NO_EMPTY);
    sort($extAllowedArr);
    $extAllowed = implode(",",array_unique($extAllowedArr));

    $sql = "update `" . $config->tablePrefix . "config` set extAllowed = " . $SFS->dbquote($extAllowed) . " where id = 1";
    $SFS->dbquery($sql);
    $success = "The list of allowed file extensions has been saved successfully.";

    echo json_encode(array("success" => $success, "error" => $error, "extAllowed" => $extAllowed));
    $SFS->sendLastPHPError();
    exit;
  }


  //save allowed file extensions
  if ($action == "save_extdenied") {
    $success = $error = false;

    $extDenied = trim($_REQUEST["extDenied"]);

    $extDeniedArr = preg_split('/,/', preg_replace('/[\.\s]/','',strtolower($extDenied)), -1, PREG_SPLIT_NO_EMPTY);
    sort($extDeniedArr);
    $extDenied = implode(",",array_unique($extDeniedArr));

    $sql = "update `" . $config->tablePrefix . "config` set extDenied = " . $SFS->dbquote($extDenied) . " where id = 1";
    $SFS->dbquery($sql);
    $success = "The list of denied file extensions has been saved successfully.";

    echo json_encode(array("success" => $success, "error" => $error, "extDenied" => $extDenied));
    $SFS->sendLastPHPError();
    exit;
  }


  //save max number of max recipients
  if ($action == "save_maxrcpt") {
    $success = false;

    $maxRcpt = intval($_REQUEST["maxRcpt"]);

    if (!$maxRcpt) $maxRcpt = 1;

    $sql = "update `" . $config->tablePrefix . "config` set maxRcpt = '" . intval($maxRcpt) . "' where id = 1";
    $SFS->dbquery($sql);
    $success = "Your max number of maximum possible recipients has been updated successfully";

    echo json_encode(array("success" => $success, "maxRcpt" => $maxRcpt));
    $SFS->sendLastPHPError();
    exit;
  }

  //enable/disable imagepreviews
  if ($action == "save_imagepreview") {
    $success = false;

    $imagePreview = $SFS->config->imagePreview ? 0 : 1;
    $sql = "update `" . $config->tablePrefix . "config` set imagePreview = '" . intval($imagePreview) . "' where id = 1";
    $SFS->dbquery($sql);
    $success = "Image Previews has been " . ($imagePreview ? "enabled" : "disabled") . " successfully";

    echo json_encode(array("success" => $success, "imagePreview" => $imagePreview));
    $SFS->sendLastPHPError();
    exit;
  }


  //save image dimensions
  if ($action == "save_imagedimensions") {
    $success = false;

    $prevWidth = intval($_REQUEST["prevWidth"]);
    $prevHeight = intval($_REQUEST["prevHeight"]);

    if ($prevHeight < 100) $prevHeight = 100;
    if ($prevWidth < 100) $prevWidth = 100;

    $sql = "update `" . $config->tablePrefix . "config` set prevWidth = '" . intval($prevWidth) . "', prevHeight = '" . intval($prevHeight) . "' where id = 1";
    $SFS->dbquery($sql);
    $success = "The Preview Image Dimensions have been updated successfully";

    echo json_encode(array("success" => $success, "prevHeight" => $prevHeight, "prevWidth" => $prevWidth));
    $SFS->sendLastPHPError();
    exit;
  }

  //enable/disable admin only uploads
  if ($action == "save_adminonlyuploads") {
    $success = false;

    $adminOnlyUploads = $SFS->config->adminOnlyUploads ? 0 : 1;
    $sql = "update `" . $config->tablePrefix . "config` set adminOnlyUploads = '" . intval($adminOnlyUploads) . "' where id = 1";
    $SFS->dbquery($sql);
    if ($adminOnlyUploads) {
      $success = "Only Admins are able to upload files to your installation.";
    } else {
      $success = "Everyone is now able to upload files to your installation.";
    }
    echo json_encode(array("success" => $success, "adminOnlyUploads" => $adminOnlyUploads));
    $SFS->sendLastPHPError();
    exit;
  }



  //save short urls settings
  if ($action == "save_shorturls") {
    $success = false;
    $shortUrls = trim($_REQUEST["shortUrls"]);
    $bitlyUser = trim($_REQUEST["bitlyUser"]);
    $bitlyKey = trim($_REQUEST["bitlyKey"]);
    $adflyUid = trim($_REQUEST["adflyUid"]);
    $adflyKey = trim($_REQUEST["adflyKey"]);
    $adflyAdvertType = trim($_REQUEST["adflyAdvertType"]);
    $connectionMethod = trim($_REQUEST["connectionMethod"]);

    $add2sql = false;
    switch ($shortUrls) {
      case 'bitly':
        $add2sql = "shortUrls = 'bitly', bitlyUser = " . $SFS->dbquote($bitlyUser) . ", bitlyKey = " . $SFS->dbquote($bitlyKey) . ", connectionMethod = " . $SFS->dbquote($connectionMethod);
        break;
      case 'adfly':
        $add2sql = "shortUrls = 'adfly', adflyUid = " . $SFS->dbquote($adflyUid) . ", adflyKey = " . $SFS->dbquote($adflyKey) . ", adflyAdvertType = " . $SFS->dbquote($adflyAdvertType) . ", connectionMethod = " . $SFS->dbquote($connectionMethod);
        break;
      case 0:
      default:
        $add2sql = "shortUrls = NULL"; 
        break;
    }
    $sql = "update `" . $config->tablePrefix . "config` set $add2sql where id = 1";
    $SFS->dbquery($sql);
    if ($shortUrls) {
      $success = "Your URL shortener settings have been updated successfully.";
    } else {
      $success = "Your URL shortener has been disabled successfully.";
    }
    echo json_encode(array("success" => $success));
    $SFS->sendLastPHPError();
    exit;
  }


  //update admin mail
  if ($action == "save_admin_mail") {
    $success = $error = false;

    $admin_mail = trim(strtolower($_REQUEST["admin_mail"]));

    if (!$admin_mail) $error = "Please type in the admin email address.";
    elseif (!is_email($admin_mail)) {
      $error = "The admin email address seems to be incorrect.";
    }

    if (!$error) {
      $sql = "update `" . $config->tablePrefix . "config` set admin_mail = " . $SFS->dbquote($admin_mail) . " where id = 1";
      $SFS->dbquery($sql);
      $success = "The admin email address has been updated successfully.";
    }
    echo json_encode(array("success" => $success, "error" => $error, "admin_mail" => $admin_mail));
    $SFS->sendLastPHPError();
    exit;
  }

  //update automailer address
  if ($action == "save_automaileraddr") {
    $success = $error = false;

    $automaileraddr = trim(strtolower($_REQUEST["automaileraddr"]));

    if (!$automaileraddr) $error = "Please type in the automailer email address.";
    elseif (!is_email($automaileraddr)) {
      $error = "The automailer email address seems to be incorrect.";
    }

    if (!$error) {
      $sql = "update `" . $config->tablePrefix . "config` set automaileraddr = " . $SFS->dbquote($automaileraddr) . " where id = 1";
      $SFS->dbquery($sql);
      $success = "The automailer email address has been updated successfully.";
    }
    echo json_encode(array("success" => $success, "error" => $error, "automaileraddr" => $automaileraddr));
    $SFS->sendLastPHPError();
    exit;
  }

  //update contact address
  if ($action == "save_contact_mail") {
    $success = $error = false;

    $contact_mail = trim(strtolower($_REQUEST["contact_mail"]));

    if (!$contact_mail) $error = "Please type in the contact email address.";
    elseif (!is_email($contact_mail)) {
      $error = "The contact email address seems to be incorrect.";
    }

    if (!$error) {
      $sql = "update `" . $config->tablePrefix . "config` set contact_mail = " . $SFS->dbquote($contact_mail) . " where id = 1";
      $SFS->dbquery($sql);
      $success = "The contact email address has been updated successfully.";
    }
    echo json_encode(array("success" => $success, "error" => $error, "contact_mail" => $contact_mail));
    $SFS->sendLastPHPError();
    exit;
  }

  //update mail params
  if ($action == "save_mailparams") {
    $success = false;

    $mailParams = trim($_REQUEST["mailParams"]);

    $sql = "update `" . $config->tablePrefix . "config` set mailParams = " . $SFS->dbquote($mailParams) . " where id = 1";
    $SFS->dbquery($sql);
    if ($mailParams) {
      $success = "The mail parameters has been updated successfully.";
    } else {
      $success = "The mail parameters has been disabled successfully.";
    }
    echo json_encode(array("success" => $success, "mailParams" => $mailParams));
    $SFS->sendLastPHPError();
    exit;
  }


  /*********
   * INSTALL SFS MOD
   *********/
  if ($action == "install_mod") {
    $SFS->install_mod($_REQUEST["modname"]);
    if ($SFS->json) {
      echo $SFS->json;
    }
    $SFS->sendLastPHPError();
    exit;
  }

  /*********
   * UNINSTALL SFS MOD
   *********/
  if ($action == "uninstall_mod") {
    $SFS->uninstall_mod($_REQUEST["modname"]);
    if ($SFS->json) {
      echo $SFS->json;
    }
    $SFS->sendLastPHPError();
    exit;
  }
  /*********
   * ENABLE/DISABLE SFS MOD
   *********/
  if ($action == "change_mod_status") {
    $SFS->change_mod_status($_REQUEST["modname"],intval($_REQUEST["status"]));
    if ($SFS->json) {
      echo $SFS->json;
    }
    $SFS->sendLastPHPError();
    exit;
  }
  /*********
   * REMOVE SFS MOD
   *********/
  if ($action == "remove_mod") {
    $SFS->remove_mod($_REQUEST["modname"]);
    if ($SFS->json) {
      echo $SFS->json;
    }
    $SFS->sendLastPHPError();
    exit;
  }

  /*********
   * HEALTCHECK FOR SFS MOD
   *********/
  if ($action == "healthcheck_mod") {
    $SFS->healthcheck_mod($_REQUEST["modname"]);
    if ($SFS->json) {
      echo $SFS->json;
    }
    $SFS->sendLastPHPError();
    exit;
  }

  /*********
   * Display MOD manual
   *********/
  if ($action == "mod_manual") {
    $SFS->mod_manual($_REQUEST["modname"]);
    if ($SFS->json) {
      echo $SFS->json;
    }
    $SFS->sendLastPHPError();
    exit;
  }


  /*********
   * remove Log Entries
   *********/
  if ($action == "removeLogEntries") {
    $SFS->removeLogEntries($_REQUEST["logGroup"]);
    if ($SFS->json) {
      echo $SFS->json;
    }
    $SFS->sendLastPHPError();
    exit;
  }




}
/****
* Admins only [-]
****/


/****
* SINGLE: Password protect shared files [+]
****/
if ($action == "pwdProtection") {
  $error = false;
  $downloadLink = $_REQUEST["downloadLink"];
  $pwd_protected = $_REQUEST["protection"]=="true"?1:0;
  $statmess = null;

  if (preg_match('|^https?://bit\.ly/|',$downloadLink)) $downloadLink = $SFS->BitlyShortener($downloadLink,"expand");
  elseif (preg_match('|^https?://adf\.ly/|',$downloadLink)) $downloadLink = $SFS->AdflyShortener($downloadLink,"expand");

  preg_match('/^.*\/([0-9a-z]+)\.html$/',$downloadLink,$d_data);
  $key = isset($d_data[1]) ? $d_data[1] : null;
  if ($key) {
    $fileInfos = getFileInfos($key);
  } else {
    preg_match('/^.*\/([0-9a-zA-Z]+)$/',$downloadLink,$d_data);
    $shortkey = $d_data[1];
    $fileInfos = getFileInfos($shortkey,"downloadFromShort");
  }
  if (!$fileInfos) $error = lang("error_file_failure");
  if (!$error) {
    if (!$fileInfos->pwd) {
      $pwd = $pwd = genPwd(8,1);
      $sql = "update `" . $config->tablePrefix . "files` set pwd = " . $SFS->dbquote($pwd) . ", pwd_protected = '$pwd_protected' where id = '" . $fileInfos->id . "'";
      $SFS->dbquery($sql);
    } else {
      $pwd = $fileInfos->pwd;
      $sql = "update `" . $config->tablePrefix . "files` set pwd_protected = '$pwd_protected' where id = '" . $fileInfos->id . "'";
      $SFS->dbquery($sql);
    }    
    if ($pwd_protected) $statmess = sprintf(lang("password_protection_ON"),$pwd);
    else $statmess = lang("password_protection_OFF");
  }

  echo json_encode(array("protection" => $pwd_protected, "statmess" => $statmess, "error" => $error));

}
/****
* SINGLE: Password protect shared files [-]
****/

/****
* MULTI: Password protect shared files [+]
****/
if ($action == "pwdProtectionMulti") {
  $u_key = $_REQUEST["u_key"];
  $pwd_protected = $_REQUEST["protection"]=="true"?1:0;
  $fileInfos = getFileInfos($u_key,"upload");
  $error = false;
  if (!$fileInfos) $error = lang("error_file_failure");
  if (!$error) {
    if (!$fileInfos->pwd) {
      $pwd = $pwd = genPwd(8,1);
      $sql = "update `" . $config->tablePrefix . "files` set pwd = " . $SFS->dbquote($pwd) . ", pwd_protected = '$pwd_protected' where u_key = " . $SFS->dbquote($u_key);
      $SFS->dbquery($sql);
    } else {
      $pwd = $fileInfos->pwd;
      $sql = "update `" . $config->tablePrefix . "files` set pwd_protected = '$pwd_protected' where u_key = " . $SFS->dbquote($u_key);
      $SFS->dbquery($sql);
    }    
    if ($pwd_protected) $statmess = sprintf(lang("password_protection_ON"),$pwd);
    else $statmess = lang("password_protection_OFF");
  }

  echo json_encode(array("protection" => $pwd_protected, "statmess" => $statmess, "error" => $error));

}
/****
* MULTI: Password protect shared files [-]
****/


/****
* SINGLE: set autodelete after x days by uploader [+]
****/
if ($action == "setDelXdays") {
  $statmess = $error = false;
  $downloadLink = $_REQUEST["downloadLink"];
  $delXdays = intval($_REQUEST["delXdays"]);
  if ($delXdays > $config->delDays || $delXdays < -1) $delXdays = -1;

  if (preg_match('|^https?://bit\.ly/|',$downloadLink)) $downloadLink = $SFS->BitlyShortener($downloadLink,"expand");
  elseif (preg_match('|^https?://adf\.ly/|',$downloadLink)) $downloadLink = $SFS->AdflyShortener($downloadLink,"expand");

  preg_match('/^.*\/([0-9a-z]+)\.html$/',$downloadLink,$d_data);
  $key = isset($d_data[1]) ? $d_data[1] : null;
  if ($key) {
    $fileInfos = getFileInfos($key);
  } else {
    preg_match('/^.*\/([0-9a-zA-Z]+)$/',$downloadLink,$d_data);
    $shortkey = $d_data[1];
    $fileInfos = getFileInfos($shortkey,"downloadFromShort");
  }
  if (!$fileInfos) $error = lang("error_file_failure");
  if (!$error) {
    $sql = "update `" . $config->tablePrefix . "files` set del_days = '$delXdays' where id = '" . $fileInfos->id . "'";
    $SFS->dbquery($sql);
  }
  echo json_encode(array("statmess" => $statmess, "error" => $error));

}
/****
* SINGLE: set autodelete after x days by uploader  [-]
****/


/****
* MULTI: set autodelete after x days by uploader [+]
****/
if ($action == "setDelXdaysMulti") {
  $statmess = $error = false;
  $u_key = $_REQUEST["u_key"];
  $delXdays = intval($_REQUEST["delXdays"]);
  if ($delXdays > $config->delDays || $delXdays < -1) $delXdays = -1;
  $fileInfos = getFileInfos($u_key,"upload");
  if (!$fileInfos) $error = lang("error_file_failure");
  if (!$error) {
    $sql = "update `" . $config->tablePrefix . "files` set del_days = '$delXdays' where u_key = " . $SFS->dbquote($u_key);
    $SFS->dbquery($sql);
  }
  echo json_encode(array("statmess" => $statmess, "error" => $error));

}
/****
* MULTI: set autodelete after x days by uploader [-]
****/


/****
* SINGLE: set autodelete after x downloads by uploader [+]
****/
if ($action == "setDelXdownloads") {
  $statmess = $error = false;
  $downloadLink = $_REQUEST["downloadLink"];
  $delXdownloads = intval($_REQUEST["delXdownloads"]);

  if (preg_match('|^https?://bit\.ly/|',$downloadLink)) $downloadLink = $SFS->BitlyShortener($downloadLink,"expand");
  elseif (preg_match('|^https?://adf\.ly/|',$downloadLink)) $downloadLink = $SFS->AdflyShortener($downloadLink,"expand");
  
  preg_match('/^.*\/([0-9a-z]+)\.html$/',$downloadLink,$d_data);
  $key = isset($d_data[1]) ? $d_data[1] : null;
  if ($key) {
    $fileInfos = getFileInfos($key);
  } else {
    preg_match('/^.*\/([0-9a-zA-Z]+)$/',$downloadLink,$d_data);
    $shortkey = $d_data[1];
    $fileInfos = getFileInfos($shortkey,"downloadFromShort");
  }
  if (!$fileInfos) $error = lang("error_file_failure");
  if (!$error) {
    $sql = "update `" . $config->tablePrefix . "files` set del_downloads = '$delXdownloads' where id = '" . $fileInfos->id . "'";
    $SFS->dbquery($sql);
  }
  echo json_encode(array("statmess" => $statmess, "error" => $error));
}
/****
* SINGLE: set autodelete after x downloads by uploader  [-]
****/


/****
* MULTI: set autodelete after x downloads by uploader [+]
****/
if ($action == "setDelXdownloadsMulti") {
  $statmess = $error = false;
  $u_key = $_REQUEST["u_key"];
  $delXdownloads = intval($_REQUEST["delXdownloads"]);
  $fileInfos = getFileInfos($u_key,"upload");
  if (!$fileInfos) $error = lang("error_file_failure");
  if (!$error) {
    $sql = "update `" . $config->tablePrefix . "files` set del_downloads = '$delXdownloads' where u_key = " . $SFS->dbquote($u_key);
    $SFS->dbquery($sql);
  }
  echo json_encode(array("statmess" => $statmess, "error" => $error));

}
/****
* MULTI: set autodelete after x downloads by uploader [-]
****/


/****
* Password verification [+]
****/
if ($action == "verifyPwd") {
  $error = $verified = false;
  $downloadLink = $_REQUEST["downloadLink"];
  $pwd = $_REQUEST["pwd"];
  preg_match('/^.*\/([0-9a-z]+)\..*$/',$downloadLink,$d_data);
  $key = isset($d_data[1]) ? $d_data[1] : null;
  $fileInfos = getFileInfos($key,"file");
  if (!$fileInfos) $error = lang("error_file_failure");
  //protected????
  if (!$error && $config->passwordProtection && $fileInfos->pwd_protected && $fileInfos->pwd) {
    $sql = "select id from `" . $config->tablePrefix . "files` where pwd = " . $SFS->dbquote($pwd) . " and id = '" . $fileInfos->id . "'";
    $res = $SFS->dbquery($sql);
    if (!mysqli_num_rows($res)) $error = lang("error_wrong_password");
    else $_SESSION["pwdVerified"][$fileInfos->id] = true;
  }
  echo json_encode(array("error" => $error, "verified" => $error?0:1));

}
/****
* Password verification [-]
****/


/****
* Short URL Generator (Bitly/Adfly) [+]
****/
if ($action == "shortenURL") {
  if ($config->shortUrls == "bitly") {
    $error = $bitly_error = array();
    $url = trim($_POST["url"]); //post to prevent of illegal usage
    if (!$url) $error[] = lang("error_shortener_no_url"); //shouldn't happen
    $shortURL = $SFS->BitlyShortener($url);
    if (!$shortURL) $error[] = lang("error_shortener_failure");
  } elseif ($config->shortUrls == "adfly") {
    $error = $adfly_error = array();
    $url = trim($_POST["url"]); //post to prevent of illegal usage
    if (!$url) $error[] = lang("error_shortener_no_url"); //shouldn't happen
    $shortURL = $SFS->AdflyShortener($url);
    if (!$shortURL) $error[] = lang("error_shortener_failure");
  }
  echo json_encode(array("error" => $error ? implode("<br />",$error) : false, "shortURL" => $shortURL));
}


/****
* save short description to uploaded file [+]
****/
if ($action == "updateFileDescription") {
  $success = $error = false;
  $downloadLink = $_REQUEST["downloadLink"];
  $descr_long = trim(strip_tags($_REQUEST["fileDescription"]));
  // $descr_long = str_replace(array('&','"'),array("&amp;","&quot;"),trim(strip_tags($_REQUEST["fileDescription"])));

  if (preg_match('|^https?://bit\.ly/|',$downloadLink)) $downloadLink = $SFS->BitlyShortener($downloadLink,"expand");
  elseif (preg_match('|^https?://adf\.ly/|',$downloadLink)) $downloadLink = $SFS->AdflyShortener($downloadLink,"expand");

  preg_match('/^.*\/([0-9a-z]+)\.html$/',$downloadLink,$d_data);
  $key = isset($d_data[1]) ? $d_data[1] : null;
  if ($key) {
    $fileInfos = getFileInfos($key);
  } else {
    preg_match('/^.*\/([0-9a-zA-Z]+)$/',$downloadLink,$d_data);
    $shortkey = $d_data[1];
    $fileInfos = getFileInfos($shortkey,"downloadFromShort");
  }
  if (!$fileInfos) $error = lang("error_file_failure");
  if (!$error) {
    $sql = "update `" . $config->tablePrefix . "files` set descr_long = " . $SFS->dbquote($descr_long) . " where id = '" . $fileInfos->id . "'";
    $SFS->dbquery($sql);
    $success = true;
  }
  echo json_encode(array("success" => $success, "error" => $error, "descr_long" => $descr_long));
}
/****
* save short description to uploaded file [-]
****/


/****
* just to validate email (tagsinput) [+]
****/
if ($action == "validateEmail") {
  $email = strtolower(trim($_REQUEST["email"]));
  echo json_encode(array("email" => $email, "isValid" => is_email($email)));
}
/****
* just to validate email (tagsinput) [-]
****/


    $SFS->sendLastPHPError();

?>