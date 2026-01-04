<?php

include_once("../config.php");


include("../classes/sfs.class.php");
$SFS = new SFS($config);

$config = $SFS->config;

include_once("../functions.php");

if (!preg_match("|^" . $config->instUrl . '/|', $_SERVER["HTTP_REFERER"])) {
  exit("illegal access");
}

/*
 * jQuery File Upload Plugin PHP
 * https://github.com/blueimp/jQuery-File-Upload
 *
 * Copyright 2010, Sebastian Tschan
 * https://blueimp.net
 *
 * Licensed under the MIT license:
 * http://www.opensource.org/licenses/MIT
 */

error_reporting(E_ALL | E_STRICT);


//SFS PHP ERROR CHECKS [+]
$numFiles = isset($_FILES["files"]) ? count($_FILES["files"]["name"]) : 0;
//multiple file upload not allowed
if (!$config->multiUpload && $numFiles > 1) {
	exit("Files Count Error: You are only allowed to upload one single file at once!");
}
//maximum number exceeded
elseif ($numFiles > $config->maxMultiFiles) {
	exit("Files Count Error: Only " . $config->maxMultiFiles . " files can be uploaded at once.");
}
//file size and extension error checks
$sizeErrorFiles = $extDeniedFiles = array();
for ($i=0;$i<$numFiles;$i++) {
	if ($_FILES["files"]["size"][$i]/1024/1024 > $config->maxFileSize) $sizeErrorFiles[] = $_FILES["files"]["name"][$i];
	//extensions check
	$matches = array();
	preg_match('/\.([^\.]*)$/',$_FILES["files"]["name"][$i],$matches);
	$ext = null;
	if (isset($matches[1])) {
		$ext = strtolower($matches[1]);
	} else {
		$ext = "unknown";
	}
	//denied extensions
	if ($config->extDeniedArray && in_array($ext, $config->extDeniedArray)) $extDeniedFiles[] = $_FILES["files"]["name"][$i];
	//not allowed extension
	elseif ($config->extAllowedArray && !in_array($ext, $config->extAllowedArray)) $extDeniedFiles[] = $_FILES["files"]["name"][$i];
}
//file size errors found
if ($sizeErrorFiles) {
	exit ("At least one of the files exceeds the maximum allowed filesize of " . $config->maxFileSize . " MB! Affected file(s): " . implode(", ",$sizeErrorFiles));
}
if ($extDeniedFiles) {
	exit ("At least one of the files has a not allowed file extension! Affected file(s): " . implode(", ",$extDeniedFiles));
}
//SFS PHP ERROR CHECKS [-]


$SFS->sendLastPHPError();

require('UploadHandler.php');

class CustomUploadHandler extends UploadHandler {
    protected function get_user_id() {
        return 0;
    }
}

$upload_handler = new CustomUploadHandler(array(
		'SFS' => $SFS,
    'user_dirs' => true,
    'upload_dir' => $config->uploadDir,
    'u_key' => trim($_REQUEST["u_key"])
));



?>