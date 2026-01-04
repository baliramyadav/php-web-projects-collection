<?php

/**
 * retrieve some information about download/deletetion file
 * 
 * @param string $key     -> secret download or delete key
 *        string $type    -> what kind of key??? download/delete/file/upload default: download
 *                            downloadFromShort ... short key given to get download information
 *        string $return  -> which information do we need exactly???
 * 
 * @return object with file information
 **/
function getFileInfos($key,$type = "download",$return = false) {
  global $SFS;
  if (!$key) return false;
  $sql = null;
  switch ($type) {
    case 'download':
      $sql = "select *, md5(concat(created,'~',created," . $SFS->dbquote($SFS->config->secretKey) . ",'][',id*3)) as fkey, date_add(" . ($SFS->config->delOn=="download"?"last_download":"created") . ", interval " . intval($SFS->config->delDays) . " day) as accessible_until, date_add(created, interval del_days day) as accessible_until_by_user, datediff(date_add(" . ($SFS->config->delOn=="download"?"last_download":"created") . ", interval " . intval($SFS->config->delDays) . " day),now()) as days_remaining, datediff(date_add(created, interval del_days day),now()) as days_remaining_by_user from `" . $SFS->config->tablePrefix . "files` where md5(concat(id,'~'," . $SFS->dbquote($SFS->config->secretKey) . ",'##',created)) = " . $SFS->dbquote($key) . " and status = 1";
      break;
    case 'delete':
      $sql = "select *, md5(concat(created,'~',created," . $SFS->dbquote($SFS->config->secretKey) . ",'][',id*3)) as fkey, date_add(" . ($SFS->config->delOn=="download"?"last_download":"created") . ", interval " . intval($SFS->config->delDays) . " day) as accessible_until, date_add(created, interval del_days day) as accessible_until_by_user, datediff(date_add(" . ($SFS->config->delOn=="download"?"last_download":"created") . ", interval " . intval($SFS->config->delDays) . " day),now()) as days_remaining, datediff(date_add(created, interval del_days day),now()) as days_remaining_by_user from `" . $SFS->config->tablePrefix . "files` where md5(concat(id,'DE33LE'," . $SFS->dbquote($SFS->config->secretKey) . ",'}}',uid)) = " . $SFS->dbquote($key) . " and status = 1";
      break;
    case 'file':
      $sql = "select * from `" . $SFS->config->tablePrefix . "files` where md5(concat(created,'~',created," . $SFS->dbquote($SFS->config->secretKey) . ",'][',id*3)) = " . $SFS->dbquote($key) . " and status = 1";
      break;
    case 'upload':
      $sql = "select * from `" . $SFS->config->tablePrefix . "files` where u_key = " . $SFS->dbquote($key) . " and status = 1 limit 1";
      break;
    case 'downloadFromShort':
      $sql = "select *, md5(concat(id,'~'," . $SFS->dbquote($SFS->config->secretKey) . ",'##',created)) as longkey, md5(concat(created,'~',created," . $SFS->dbquote($SFS->config->secretKey) . ",'][',id*3)) as fkey, date_add(" . ($SFS->config->delOn=="download"?"last_download":"created") . ", interval " . intval($SFS->config->delDays) . " day) as accessible_until, date_add(created, interval del_days day) as accessible_until_by_user, datediff(date_add(" . ($SFS->config->delOn=="download"?"last_download":"created") . ", interval " . intval($SFS->config->delDays) . " day),now()) as days_remaining, datediff(date_add(created, interval del_days day),now()) as days_remaining_by_user from `" . $SFS->config->tablePrefix . "files` where shortkey = " . $SFS->dbquote($key) . " and status = 1";
      break;
  }
  if (!$sql) return false;
  $res = $SFS->dbquery($sql);
  if (!mysqli_num_rows($res)) return false;
  $row = mysqli_fetch_object($res);

  if ($row->del_days > -1) {
    $row->days_remaining = isset($row->days_remaining_by_user) ? $row->days_remaining_by_user : false;
    $row->accessible_until = isset($row->accessible_until_by_user) ? $row->accessible_until_by_user : false;
  }

  $row->downloadFileName = (isset($row->fkey) ? $row->fkey : "") . "." . pathinfo($row->fname, PATHINFO_EXTENSION);
  //add download handler information if file is requested
  $d_ips = $d_sids = array();
  if ($type == "file") {
    $sql = "select * from `" . $SFS->config->tablePrefix . "download_handler` where files_id = '" . $row->id . "' order by d_time";
    $res = $SFS->dbquery($sql);
    while ($rowdh = mysqli_fetch_object($res)) {
      $d_ips[] = $rowdh->d_ip;
      $d_sids[] = $rowdh->d_sid;
      //last d_time
      $row->d_time = $rowdh->d_time;
    }
  } 
  $row->d_ips = array_unique($d_ips);
  $row->d_sids = array_unique($d_sids);

  //any message to display?
  $sql = "select message from `" . $SFS->config->tablePrefix . "messages` where u_key = " . $SFS->dbquote($row->u_key);
  $res = $SFS->dbquery($sql);
  $rowmess = mysqli_fetch_object($res);
  if ($rowmess) {
    $row->message = $rowmess->message;
  }
  
  if ($return == "descr") return $row->descr;
  //or not that nice :)
  // if ($return) return $row->{$return};
  
  return $row;
}

/**
 * retrieve some information about download/deletetion links of a group of files
 * 
 * @param string $key     -> upload_key
 * 
 * @return array with message and array with objects with file information
 **/
function getMultiFileInfos($key) {
  global $SFS;
  $message = false;
  if (!$key) return false;
  $sql = "select *,md5(concat(id,'~'," . $SFS->dbquote($SFS->config->secretKey) . ",'##',created)) as skey,md5(concat(created,'~',created," . $SFS->dbquote($SFS->config->secretKey) . ",'][',id*3)) as fkey from `" . $SFS->config->tablePrefix . "files` where u_key = " . $SFS->dbquote($key) . " and status = 1";
  $res = $SFS->dbquery($sql);
  if (!mysqli_num_rows($res)) return false;
  while ($row = mysqli_fetch_object($res)) {
    $row->downloadFileName = $row->fkey . "." . pathinfo($row->fname, PATHINFO_EXTENSION);
    $files[] = $row;
  }

  //any message to display?
  $sql = "select message from `" . $SFS->config->tablePrefix . "messages` where u_key = " . $SFS->dbquote($key);
  $res = $SFS->dbquery($sql);
  $row = mysqli_fetch_object($res);
  $message = $row ? $row->message : false;

  return array($files,$message);
}

/**
 * the language function, displays correct language strings for activated language
 * the language files can be found in the lang-directory   
 *  
 * @param string $key - the key of the language to display
 * @global boolean $show_lang_keys - can be set in the conf.php - show additional to the language string the appropriate key too
 * 
 * @return correct translated language string if found
 *         if not - the key will be returned width two leading and trailing underscores instead and the $deblang array will be increased
 **/
function lang($key) {
  global $LANG, $deblang, $show_lang_keys;
  if (array_key_exists($key, $LANG)) return $LANG[$key] . ($show_lang_keys ? " [$key]" : "");// . " [$key]";
  else {
    $deblang[] = $key;
    return "__" . $key . "__";
  }
}

/**
 * to inform site administrators if some language keys are not available
 *  
 * @param NONE
 * @global array $deblang - not found language keys - filled with the help of lang()
 * 
 * @return nothing but sends an email to administrator if languages keys not available
 **/
function deblang() {
  //mail notfound language keys to debug mailaddr
  global $config, $deblang;
  if (!$deblang) return false;
  $deblang = array_unique($deblang);
  $included_files = get_included_files();
  foreach ($included_files as $filename) {
    $fname = basename($filename);
    if (preg_match('/lang\.php$/',$fname)) break;
  }
  mail($config->admin_mail, strip_tags($config->siteName) . ' Language-Error',$_SERVER['PHP_SELF']."?".$_SERVER['QUERY_STRING']."\n\nLanguage: " . $config->lang . "\nLanguagefile: " . $config->lang . "/$fname\nMissing Keys:\n\t" . implode("\n\t",$deblang) . "\n");  
}

/**
 * converts bytes in human readable outpts
 *  
 * @param integer $int      -> bytes
 *
 * @return string -> human readable filesize
 **/
function fsize($int) {
  // $int = intval($int);  //doesn't work as expected on super high numbers 32bit/64bit and unsigned
  $int = preg_replace('/\D/', '', $int);  //just remove non numeric characters
  if (!$int) $int = 0;
  $kb = 1024;
  $mb = $kb*1024;
  $gb = $mb*1024;
  $tb = $gb*1024;
  $pb = $tb*1024;
  if ($int < $kb) return $int . " B";
  elseif ($int < $mb) return round($int/$kb,2) . " KB";
  elseif ($int < $gb) return round($int/$mb,2) . " MB";
  elseif ($int < $tb) return round($int/$gb,2) . " GB";
  elseif ($int < $pb) return round($int/$tb,2) . " TB";
  else return round($int/$pb,2) . " PB";
}

/**
 * used in is_email() - on Windows machines this function is not available
 * so this function will be used instead 
 * 
 * @param NONE
 * 
 * @return true if it's valid or false if not    
 **/ 
if (!function_exists("checkdnsrr")) {
  function checkdnsrr() {
    return true;
  }
}

/**
 * Checks if email address is valid and host exists
 * 
 * @param string $email - the email to check
 * 
 * @return true if it's valid or false if not    
 **/ 
function is_email($email) {
  $email = trim($email);
  if (!$email) return false;
  if (!preg_match("!^[\w|\.|\-|_]+@\w[\w|\.|\-]+\.[a-zA-Z]{2,6}$!",$email)) return false;
  else {
    list($user, $host) = explode("@", $email);
    if (checkdnsrr($host, "MX") or checkdnsrr($host, "A")) return true;
    else return false;
  }
}

/**
 * recursive removing of directories on server
 * 
 * @param string $location - the path to the directory to delete
 * 
 * @return nothing but removes the directory
 **/
function xrmdir($location){
   $rc=0;
   if (is_dir($location)){
      $dp=opendir("$location");
      while(false !== ($file = readdir($dp))){
         if ($file=="."||$file=="..") continue;
         xrmdir("$location/$file");
      }
      closedir($dp);
      unset($dp);
      $rc=rmdir($location);
      unset($location);
   }
   else{
      $rc=unlink($location);

      unset($location);
   }
   return $rc;
}


/**
 * short for htmlentities($str,ENT_QUOTES) especially for the use in form fields and title and alt tags
 * htmlentities: all characters which have HTML character entity equivalents are translated into these entities 
 * ENT_QUOTES converts both double and single quotes
 *  
 * @param string $str - the string to convert
 * 
 * @return converted string
 **/
function he($str) {
  $str = htmlentities($str, ENT_QUOTES, "UTF-8");
  return $str;
}



/**
 * check if it's a image, when it's not toooo big
 *  
 * @param $imgPath .... full path to image
 * 
 * @return boolean ... true if it's an image, false if not
**/
function is_image($imgPath) {
  if (filesize($imgPath) > 10*1024*1024) return false;  //to big to handle gd reduction 
  if (!filesize($imgPath)) return false;  //zero byte files
  if (function_exists("finfo_file")) {
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $fileMimeType = finfo_file($finfo, $imgPath);
    finfo_close($finfo);
    if (!preg_match('/^image\//', $fileMimeType)) {
      return false;
    }
  }
  list($w, $h) = getimagesize($imgPath);
  if (!$w || !$h) return false;
  return true;
}

/**
 * generate password for password protected files
 *  
 * @param $length .... length of the password (default: 9)
 *        $strength .. which characters should be used, the higer the stronger
 * 
 * @return string ... the password
**/
function genPWD($length=9, $strength=0) {
  $vowels = 'aeuy';
  $consonants = 'bdghjmnpqrstvz';
  if ($strength & 1) $consonants .= 'BDGHJLMNPQRSTVWXZ';
  if ($strength & 2) $vowels .= "AEUY";
  if ($strength & 4) $consonants .= '23456789';
  if ($strength & 8) $consonants .= '@#$%';
  $password = '';
  $alt = time() % 2;
  for ($i = 0; $i < $length; $i++) {
      if ($alt == 1) {
          $password .= $consonants[(rand() % strlen($consonants))];
          $alt = 0;
      } else {
          $password .= $vowels[(rand() % strlen($vowels))];
          $alt = 1;
      }
  }
  return $password;
}


/**
 * to enable UTF-8 encoded subjects
 *  
 * @param $subject
 * 
 * @return UTF-8 spelling for the encoded subjects
**/
function UTF8subject($subject) {
  if (!$subject) return false;
  $subject = strip_tags($subject);
  return '=?UTF-8?B?'.base64_encode($subject).'?=';
}



/**
 * used for downloads on android devices (usage on download.php and files.php)
 * just to fix the double calls by android browsers
 *
 * @param $todo
 *          "init" to set counter on adroid devices
 *          "countDown" to reduce counter to enable downlad on second call
 * 
 * @return nothing
 **/
function androidDoubleCallFix($todo = "init") {
  if(
    stripos(isset($_SERVER['HTTP_USER_AGENT']) && $_SERVER['HTTP_USER_AGENT'],'android') !== false
    // || (preg_match('/AppleWebKit/',$_SERVER['HTTP_USER_AGENT']) && !preg_match('/(ipad)|(iphone)|(macintosh)|(blackberry)/i',$_SERVER['HTTP_USER_AGENT']))  //because some andoid browsers on some android devices doesn't send an "ANDROID" string and shouldn't be a Apple Device
    ) {
    if ($todo == "init") {
      $_SESSION["androCount"] = 2;
    }
    elseif ($todo == "countDown" && isset($_SESSION["androCount"])) {
      $_SESSION["androCount"]--;
    }
  }
}

?>