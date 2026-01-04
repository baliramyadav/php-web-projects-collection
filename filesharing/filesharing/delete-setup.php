<?php 
  $currPage = "setup";
  include("includes/header.php"); 
  $todo = isset($_REQUEST["todo"]) ? $_REQUEST["todo"] : null;
?>
<div class='container'>
  <div class='row'>
    <div class='col-xs-12'>
		  <div class="page-header">
		    <h1>Simple File Sharer V<?php echo $config->version; ?> Setup</h1>
		  </div>

<?php

//uploaddircheck
$error = array();
if (!is_dir($config->uploadDir)) {
	$error[] = "The upload directory <b>" . $config->uploadDir . "</b> <code>\$config->uploadDir</code> cannot be not found. Directory can be changed in the config.php";
} elseif (!is_writable($config->uploadDir)) {
	$error[] = "The upload directory <b>" . $config->uploadDir . "</b> <code>\$config->uploadDir</code> isn't writeable.<br/>Please use <b>chmod 757</b> to fix it.";
} elseif (!file_exists(".htaccess")) {
	$error[] = "The <b>.htaccess</b> file is missing, please upload all files of the htdocs folder.<br />
		It's directly in the htdocs folder, maybe the file is hidden on your System, on MAC try <i>Finder <i class='icon-arrow-right'></i> View <i class='icon-arrow-right'></i> Show System Files</i>.";
} else {
	$success[] = "Upload directory <b>" . $config->uploadDir . "</b> <code>\$config->uploadDir</code> found and writeable.";
	$success[] = "<b>.htaccess</b> file found.";
}

//databasechecks
//db credentialscheck via the config.php
//structure checks
if (!$error) {
	$neededTables = array($config->tablePrefix . "files",$config->tablePrefix . "download_handler",$config->tablePrefix . "messages",$config->tablePrefix . "overall_stats",$config->tablePrefix . "config",$config->tablePrefix . "modules");
	$SQL_queries[] = "CREATE TABLE `" . $config->tablePrefix . "config` (
	  `id` int(4) NOT NULL AUTO_INCREMENT,
	  `timezone` varchar(100) NOT NULL DEFAULT 'Europe/Vienna',
	  `db_timezoneCorrection` varchar(8) NOT NULL DEFAULT '+00:00',
	  `siteName` varchar(100) NOT NULL,
	  `maxFileSize` float NOT NULL DEFAULT '15',
	  `multiUpload` tinyint(1) NOT NULL DEFAULT '1',
	  `maxMultiFiles` smallint(5) unsigned NOT NULL DEFAULT '3',
	  `addAnotherFiles` tinyint(1) NOT NULL DEFAULT '1',
	  `delDays` smallint(6) NOT NULL DEFAULT '14',
	  `delOn` varchar(30) NOT NULL DEFAULT 'download' COMMENT 'download, upload',
	  `delSettingsByUploader` tinyint(1) NOT NULL DEFAULT '1',
	  `delDownloadsNumbers` varchar(150) DEFAULT NULL COMMENT '1,2,3,5,10,15,...',
	  `maxRcpt` tinyint(4) NOT NULL DEFAULT '3',
	  `downloadProtection` varchar(15) NOT NULL DEFAULT 'SESSION' COMMENT '0, IP, SESSION',
	  `passwordProtection` tinyint(1) NOT NULL DEFAULT '0',
	  `extDenied` varchar(200) NOT NULL DEFAULT 'exe' COMMENT 'exe,bat,...',
	  `extAllowed` varchar(200) DEFAULT NULL COMMENT 'jpg,jpeg,xml,doc,...',
	  `downloadSeconds` smallint(5) unsigned NOT NULL DEFAULT '10',
	  `imagePreview` tinyint(1) NOT NULL DEFAULT '1',
	  `prevWidth` smallint(5) unsigned NOT NULL DEFAULT '400',
	  `prevHeight` smallint(5) unsigned NOT NULL DEFAULT '300',
	  `XSendFile` tinyint(1) NOT NULL DEFAULT '0',
	  `kbps` int(10) unsigned NOT NULL DEFAULT '0',
	  `captchaContact` tinyint(1) NOT NULL DEFAULT '0',
	  `shortUrls` varchar(20) DEFAULT NULL COMMENT 'bitly,adfly,linkpay,scb,sfs,...',
	  `bitlyUser` varchar(100) DEFAULT NULL,
	  `bitlyKey` varchar(100) DEFAULT NULL,
	  `adflyUid` varchar(100) DEFAULT NULL,
	  `adflyKey` varchar(100) DEFAULT NULL,
	  `adflyAdvertType` varchar(100) DEFAULT NULL,
	  `connectionMethod` varchar(20) NOT NULL DEFAULT 'auto' COMMENT 'auto,curl,url_fopen',
	  `adminOnlyUploads` tinyint(1) NOT NULL DEFAULT '0',
	  `admin_mail` varchar(100) NOT NULL DEFAULT 'admin@yourdomain.com',
	  `automaileraddr` varchar(100) NOT NULL DEFAULT 'no-reply@yourdomain.com',
	  `contact_mail` varchar(100) NOT NULL DEFAULT 'office@yourdomain.com',
	  `mailParams` varchar(100) DEFAULT NULL,
	  `defaultLanguage` varchar(10) NOT NULL DEFAULT 'en' COMMENT 'en,de-Du,de-Sie,...',
	  `version` float DEFAULT '" . floatval($config->version) . "',
	  `created` datetime NOT NULL,
	  `edited` datetime DEFAULT NULL,
	  PRIMARY KEY (`id`))";
	$SQL_queries[] = "INSERT INTO `" . $config->tablePrefix . "config` set siteName = 'SimpleFileSharer', created = now()";
	$SQL_queries[] = "CREATE TABLE `" . $config->tablePrefix . "files` (
	  `id` int(11) NOT NULL AUTO_INCREMENT,
	  `uid` int(11) NOT NULL DEFAULT '0',
	  `fname` varchar(50) NOT NULL,
	  `ftype` varchar(255) NOT NULL,
	  `fsize` bigint(20) unsigned NOT NULL,
	  `descr` varchar(150) NOT NULL,
	  `descr_long` varchar(250) DEFAULT NULL,
	  `status` tinyint(1) NOT NULL DEFAULT '1',
	  `created` datetime NOT NULL,
	  `downloads` int(11) NOT NULL DEFAULT '0',
	  `d_ip` varchar(50) DEFAULT NULL,
	  `d_sid` varchar(50) DEFAULT NULL,
	  `u_key` varchar(50) DEFAULT NULL,
	  `d_time` datetime DEFAULT NULL,
	  `last_download` datetime DEFAULT NULL,
	  `pwd_protected` tinyint(1) NOT NULL DEFAULT '0',
	  `pwd` varchar(20) DEFAULT NULL,
	  `del_days` int(11) NOT NULL DEFAULT '-1',
	  `del_downloads` int(11) NOT NULL DEFAULT '-1',
	  `locked` tinyint(1) NOT NULL DEFAULT '0',
	  `adfly_dele` varchar(30) DEFAULT NULL,
	  `adfly_down` varchar(30) DEFAULT NULL,
	  `shortkey` varchar(30) DEFAULT NULL,
	  PRIMARY KEY (`id`))";
	$SQL_queries[] = "CREATE TABLE `" . $config->tablePrefix . "download_handler` (
	  `id` int(11) NOT NULL AUTO_INCREMENT,
	  `files_id` INT(11) NOT NULL ,
	  `d_ip` VARCHAR(50) NOT NULL ,
	  `d_sid` VARCHAR( 50 ) NOT NULL ,
	  `d_time` DATETIME NOT NULL,
	  PRIMARY KEY (`id`))";
	$SQL_queries[] = "CREATE TABLE `" . $config->tablePrefix . "messages` (
	  `u_key` VARCHAR( 50 ) NOT NULL ,
	  `message` TEXT NOT NULL ,
	  PRIMARY KEY ( `u_key` ))";
	$SQL_queries[] = "CREATE TABLE `" . $config->tablePrefix . "overall_stats` (
	  `id` INT NOT NULL AUTO_INCREMENT,
	  `downloads` INT NOT NULL DEFAULT '0',
	  `d_size` bigint(20) unsigned NOT NULL DEFAULT '0',
	  `uploads` INT NOT NULL DEFAULT '0',
	  `u_size` bigint(20) unsigned NOT NULL DEFAULT '0',
	  PRIMARY KEY (`id`))";
	$SQL_queries[] = "INSERT INTO `" . $config->tablePrefix . "overall_stats` SET downloads = '0'";
	$SQL_queries[] = "CREATE TABLE `" . $config->tablePrefix . "modules` (
	  `id` INT NOT NULL AUTO_INCREMENT,
	  `modname` varchar(50) NOT NULL,
	  `installed` tinyint(1) NOT NULL DEFAULT '0',
	  `installed_version` float NOT NULL DEFAULT '0',
	  `status` tinyint(1) NOT NULL DEFAULT '0',
	  PRIMARY KEY (`id`), 
	  UNIQUE `mod` (`modname`))";
	$SQL_queries[] = "CREATE TABLE `" . $config->tablePrefix . "error_log` (
	  `id` int(11) NOT NULL AUTO_INCREMENT,
	  `message` varchar(255) NOT NULL,
	  `file` varchar(255) NOT NULL,
	  `line` int(11) NOT NULL,
	  `url` varchar(255) NOT NULL,
	  `referer` varchar(255) NOT NULL,
	  `ip` varchar(50) NOT NULL,
	  `created` datetime NOT NULL,
	  PRIMARY KEY (`id`))";

	if ($todo == "createtables") {
	  for ($i=0;$i<count($SQL_queries);$i++) {
	    $SFS->dbquery($SQL_queries[$i]);
	  }
	}
	$res = $SFS->dbquery("show tables from `" . $config->db_name . "`");
    $foundTables = 0;
    while ($row = mysqli_fetch_object($res)) {
      if (in_array($row->{"Tables_in_".$config->db_name}, $neededTables)) $foundTables++;
    }
    if ($foundTables < count($neededTables)) {
      $this_error = "<div class='setuperror'><b>necessary tables not found.</b><br>
        Please create these table with these queries or click <a href='?todo=createtables' class='btn btn-default btn-sm'>here</a> to create the tables automatically.<br /><br />";
       for ($i=0;$i<count($SQL_queries);$i++) {
        $this_error .= "<pre>" . $SQL_queries[$i] . ";</pre>\n";
      } 
      $this_error .= "</div>";
      $error[] = $this_error;
    } else {
    	$success[] = "Necessary database structure found.";
    }

}



if ($error) {
	echo "<div class='alert alert-danger'><strong>Errors occured</strong><ul><li>" . implode("</li><li>",$error) . "</li></ul></div>";
} elseif ($success) {
	echo "<div class='alert alert-success'><strong>Setup completed</strong><ul><li>" . implode(" <i class='icon-ok'></i></li><li>",$success) . " <i class='icon-ok'></i></li></ul>Now you have to rename or delete setup.php.</div>";
}
if ($config->pass == "pass" || $config->user == "admin") $secnotes[] = "You should change <code>\$config->user</code> and/or <code>\$config->pass</code> for security issues.";
if (file_exists("sfs-admin.php")) $secnotes[] = "You should rename the admin-file (sfs-admin.php).";
if ($secnotes) {
	echo "<div class='alert alert-warning'><strong>Security Hints</strong><ul><li>" . implode("</li><li>",$secnotes) . "</li></ul></div>";
}

//nginx notes
if (preg_match('/nginx/i',$_SERVER["SERVER_SOFTWARE"])) {

	$nginxConf = '# SFS configuration

# essential for the maximum upload size (according to the post_max_size and upload_max_size)
# !!! client_max_body_size should/can only be defined ONCE in a server section!!!
client_max_body_size 32M;

error_page 404 /SFSBASEDIR/notfound;

# !!! autoindex should/can only be defined ONCE in a server section!!!
autoindex off;

#download site
location /SFSBASEDIR/download {
	rewrite ^/SFSBASEDIR/download/([a-z0-9]+)\.html$ /SFSBASEDIR/download.php?key=$1&$query_string;
	rewrite ^/SFSBASEDIR/download/([a-z0-9]+)\.jpg$ /SFSBASEDIR/preview.img.php?key=$1&$query_string;
}
#download files
location /SFSBASEDIR/files {
	rewrite ^/SFSBASEDIR/files/([a-z0-9]+)\..*$ /SFSBASEDIR/files.php?key=$1;
}
#deletion site
location /SFSBASEDIR/delete {
	rewrite ^/SFSBASEDIR/delete/([a-z0-9]+)\.html$ /SFSBASEDIR/delete.php?key=$1&$query_string;
}
#grouped files
location /SFSBASEDIR/filesgroup {
	rewrite ^/SFSBASEDIR/filesgroup/([a-z0-9]+)\.html$ /SFSBASEDIR/filesgroup.php?key=$1;
}
#captcha image
location = ^/SFSBASEDIR/img/cap1.png {
	rewrite ^(.*)$ /sfs/cpc/captcha.php?$query_string;
}
#Error 404 not found site - see error_page above
location = /SFSBASEDIR/notfound {
	rewrite ^(.*)$ /SFSBASEDIR/notfound.php;
}
#short urls (please make sure that the path information is correct)
location /SFSBASEDIR {
	if (!-e $request_filename){
		rewrite "^/SFSBASEDIR/([a-zA-Z2-9]{5,})$" /SFSBASEDIR/download.php?shortkey=$1&$query_string;
	}
}';
$nginxConf = str_replace('/SFSBASEDIR',$config->instDir,$nginxConf);
$nginxConf = preg_replace('/location  {/','location / {',$nginxConf);

	echo "<div class='alert alert-info'><h4>It seems you're running an nginx Webserver</h4>below you'll find some useful information regarding to your webserver<br /><br />
		<strong>These lines should be added to your website config</strong> inside the <code>server { }</code>, maybe right before the right curly bracket<br /><small>without a 100% guarantee ;) while nginx isn't supported by SFS officially</small><br /><br />
		<pre>$nginxConf</pre>
	</div>";
}

$otherNotes[] = "Please take a look to the settings in the admin interface to define other settings, such as the correct timezone.";
if ($otherNotes) {
	echo "<div class='alert alert-info'><strong>Other Hints</strong><ul><li>" . implode("</li><li>",$otherNotes) . "</li></ul></div>";
}


?>
</div></div></div>

<?php include("includes/footer.php"); ?>
