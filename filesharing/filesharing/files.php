<?php 

include_once("config.php");

include("classes/sfs.class.php");
$SFS = new SFS($config);

$config = $SFS->config;

include_once("functions.php");


$key = $_REQUEST["key"];


if (!$key) {
  include("notfound.php");
  exit;
}

$fileInfos = getFileInfos($key,"file");

androidDoubleCallFix("countDown");


if (!$fileInfos) {
  include("notfound.php");
  exit;
}

$directAdminDownload = isset($_REQUEST["ddl"]) && $_REQUEST["ddl"] && $sfs_auth;

//    (!in_array(session_id(),$fileInfos->d_sids) && !in_array($_COOKIE["PHPSESSID"],$fileInfos->d_sids))

//download protection
if (!$sfs_auth && ($config->downloadProtection || $config->downloadSeconds || $config->passwordProtection)) {
   list($skey) = $SFS->genFileKeys($fileInfos->id);
   if ($config->downloadProtection == "IP") {
      if (!in_array($_SERVER["REMOTE_ADDR"],$fileInfos->d_ips)) {
         header("location: ../download/$skey.html");
         $SFS->sendLastPHPError();
         exit;
      }
   }
   if ($config->downloadProtection == "SESSION") {
      if (!in_array(session_id(),$fileInfos->d_sids)) {
         header("location: ../download/$skey.html");
         $SFS->sendLastPHPError();
         exit;
      }
   }
   if ($config->downloadSeconds) {
      if (!$fileInfos->d_time || time() < strtotime($fileInfos->d_time) + $config->downloadSeconds || !in_array(session_id(),$fileInfos->d_sids)) {
         header("location: ../download/$skey.html");
         $SFS->sendLastPHPError();
         exit;
      }
   }
   if ($config->passwordProtection && $fileInfos->pwd_protected && $fileInfos->pwd && 
      (!isset($_SESSION["pwdVerified"][$fileInfos->id]) || (isset($_SESSION["pwdVerified"][$fileInfos->id]) && !$_SESSION["pwdVerified"][$fileInfos->id]))
   ) {
      header("location: ../download/$skey.html");
      $SFS->sendLastPHPError();
      exit;
   }
}




$file_path = $config->uploadDir . $fileInfos->uid . "/" . $fileInfos->id . "/" . $fileInfos->fname;
if (!file_exists($file_path)) {
  include("notfound.php");
   $SFS->sendLastPHPError();
  exit;
}


$pathInfo = pathinfo($file_path);
$file_len = filesize($file_path);
$file_extension = strtolower($pathInfo["extension"]);

//bandwidthcheck and XSendFile reset
$utime = $SFS->calcBW();

//apache huge file downloader mod avail???
if ($config->XSendFile && function_exists("apache_get_modules")) {
   if (apache_getenv("XSendFile") == "enabled" && in_array("mod_xsendfile", apache_get_modules())) {
      header("X-Sendfile: $file_path");
      header("Content-Type: application/octet-stream");
      header("Content-Disposition: attachment; filename=\"".$fileInfos->descr."\"");

      if (!isset($_SESSION["androCount"]) || (isset($_SESSION["androCount"]) && !$_SESSION["androCount"])) {
         $sql = "update `" . $config->tablePrefix . "files` set downloads = downloads + 1, last_download = now() where id = '" . $fileInfos->id . "'";
         if (!$directAdminDownload) $SFS->dbquery($sql);
         $sql = "update `" . $config->tablePrefix . "overall_stats` set downloads = downloads + 1, d_size = d_size + " . intval($file_len) . " where id = 1";
         if (!$directAdminDownload) $SFS->dbquery($sql);
         //drop download protections for current User (IP&Session...)
         $sql = "delete from `" . $config->tablePrefix . "download_handler` where files_id = '" . $fileInfos->id . "' and d_ip = '" . $_SERVER["REMOTE_ADDR"] . "' and d_sid = " . $SFS->dbquote(session_id());
         $SFS->dbquery($sql);
      }
      $SFS->sendLastPHPError();
      exit;
   }
}

//downloads w/o XSendFile starting here

// Content-Type
switch( $file_extension ) {
   case "exe":  $ctype="application/octet-stream"; break;
   case "zip":  $ctype="application/zip"; break;
   case "mp3":  $ctype="audio/mpeg"; break;
   case "mpg":  $ctype="video/mpeg"; break;
   case "avi":  $ctype="video/x-msvideo"; break;
   case "gz":   $ctype="application/gzip"; break;
   case "xls":  $ctype="application/msexcel"; break;
   case "xla":  $ctype="application/msexcel"; break;
   case "hlp":  $ctype="application/mshelp"; break;
   case "chm":  $ctype="application/mshelp"; break;
   case "ppt":  $ctype="application/mspowerpoint"; break;
   case "pps":  $ctype="application/mspowerpoint"; break;
   case "doc":  $ctype="application/msword"; break;
   case "dot":  $ctype="application/msword"; break;
   case "dot":  $ctype="application/msword"; break;
   case "pdf":  $ctype="application/pdf"; break;
   case "ps":   $ctype="application/postscript"; break;
   case "rtf":  $ctype="application/rtf"; break;
   case "xml":  $ctype="application/xml"; break;
   case "swf":  $ctype="application/x-shockwave-flash"; break;
   case "wav":  $ctype="application/x-wav"; break;
   case "gif":  $ctype="application/gif"; break;
   case "jpeg": $ctype="application/jpeg"; break;
   case "jpg":  $ctype="application/jpeg"; break;
   case "png":  $ctype="application/png"; break;
   case "tiff": $ctype="application/tiff"; break;
   case "tif":  $ctype="application/tiff"; break;
   case "csv":  $ctype="text/comma-separated-values"; break;
   case "txt":  $ctype="text/plain"; break;
   default: $ctype="application/force-download";
}
   //Begin writing headers
   header("Pragma: public");
   header("Expires: 0");
   header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
   header("Cache-Control: public");
   header("Content-Description: File Transfer");
  
   //android and IOS Devices
   if(
      stripos($_SERVER['HTTP_USER_AGENT'],'android') !== false || //Android devices
      preg_match('/(ipad)|(iphone)/i',$_SERVER['HTTP_USER_AGENT'])  //IOS Devices   
      ) {
      header("Content-Type: application/octet-stream");
   } else {
      //Use the switch-generated Content-Type
      header("Content-Type: $ctype");
   }


   //Force the download
   $header="Content-Disposition: attachment; filename=\"".$fileInfos->descr."\"";
   header($header);
   header("Content-Transfer-Encoding: binary");
   header("Content-Length: ".$file_len);

   $fp = fopen($file_path, 'rb');

   // ob_end_clean();
   ob_start();   
   if ($utime) {
      while(!feof($fp)) {
         echo fread($fp, 8*1024);
         ob_flush();
         usleep($utime);
     }
   } else {
      while(!feof($fp)) {
         echo fread($fp, 8*1024);
         ob_flush();
     }
   }

   fclose($fp);

   //drop download protections for current User (IP&Session...)
   if (!isset($_SESSION["androCount"]) || (isset($_SESSION["androCount"]) && !$_SESSION["androCount"])) {
      $sql = "delete from `" . $config->tablePrefix . "download_handler` where files_id = '" . $fileInfos->id . "' and d_ip = '" . $_SERVER["REMOTE_ADDR"] . "' and d_sid = " . $SFS->dbquote(session_id());
      if (!$directAdminDownload) $SFS->dbquery($sql);
      $sql = "update `" . $config->tablePrefix . "files` set downloads = downloads + 1, last_download = now() where id = '" . $fileInfos->id . "'";
      if (!$directAdminDownload) $SFS->dbquery($sql);
      $sql = "update `" . $config->tablePrefix . "overall_stats` set downloads = downloads + 1, d_size = d_size + " . intval($file_len) . " where id = 1";
      $SFS->dbquery($sql);
   }
   $SFS->sendLastPHPError();
   exit;

?>