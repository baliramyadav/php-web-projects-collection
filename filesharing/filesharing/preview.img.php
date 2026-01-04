<?php
error_reporting(E_ALL ^ E_NOTICE);
if (!$_REQUEST) exit();

include("config.php");
include("classes/sfs.class.php");
$SFS = new SFS($config);

$config = $SFS->config;

include("functions.php");

$key = $_REQUEST["key"];
  
if (!$key) {
  include("notfound.php");
  exit;
}

$fileInfos = getFileInfos($key);

if (!$fileInfos) {
  include("notfound.php");
  exit;
}

$_img_dir = $config->uploadDir . $fileInfos->uid . "/" . $fileInfos->id;
$_filename = $_img_dir . "/" . $fileInfos->fname;
$_tn_name = $_img_dir . "/tn_" . $fileInfos->fname;

if (!file_exists($_filename)) {
  include("notfound.php");
  exit;
}
list($width_orig, $height_orig) = getimagesize($_filename);
if (!$width_orig || !$height_orig) {
  include("notfound.php");
  exit;
}

$width = $config->prevWidth;
$height = $config->prevHeight;
if (!isset($force)) {
  $force = false;
}

// Content type
header('Content-type: image/jpeg');

if (!file_exists($_tn_name)) {

  if ($width_orig <= $width) $width = $width_orig;
  if ($height_orig <= $height) $height = $height_orig;

  if (!$force || $force == "width") {
    //to prevent width from changing
    if ($width && $width_orig < $height_orig) {
    	$width = ceil(($height / $height_orig) * $width_orig);
    } else {
      if ($height_orig > $width_orig) { //hochformat
        $width = ceil(($height / $height_orig) * $width_orig);
      } else { //landscape
        $height = ceil(($width / $width_orig) * $height_orig);
      }
  //  	$height = ceil(($width / $width_orig) * $height_orig);
    }
  } elseif ($force == "height") {
    $width = ceil(($height / $height_orig) * $width_orig);
  } elseif ($force == "both") {
    //calc both
    $tw = ceil(($height / $height_orig) * $width_orig);
    $th = ceil(($width / $width_orig) * $height_orig);
    
    if ($tw > $width && $th <= $height) {  //too wide but height would be okay
      //set height
      $height = $th;
      $case = 1;
    }
    elseif ($th > $height && $tw <= $width) { //too high but width would be okay
      //set width
      $width = $tw;
      $case = 2;
    } else {  //calculate both, no clue if this would ever happen - give me an example :)
      
    }
  }

  if (!isset($c_width)){
    $c_width = $c_height = false;
  }
  
  // Create & Resample
	if ($c_width) {
    $image_p = imagecreatetruecolor($c_width, $c_height);
    $weiss = imagecolorallocate($image_p, 255, 255, 255);
    imagefilledrectangle($image_p, 0, 0, $c_width, $c_height, 20);
  } else {
    $image_p = imagecreatetruecolor($width, $height);
    $weiss = imagecolorallocate($image_p, 255, 255, 255);
    imagefilledrectangle($image_p, 0, 0, $width, $height, $weiss);
  }
   
  $ext = explode(".",$_filename);
	$ext = strtolower(array_pop($ext));

  if ($ext == "gif") $image = imagecreatefromgif($_filename);
	elseif ($ext == "png") $image = imagecreatefrompng($_filename);
  else $image = imagecreatefromjpeg($_filename);


	if ($c_width) {
    if ($height < $c_height) {
      $height = $c_height;
      $width = ceil(($height / $height_orig) * $width_orig);
    }
    if ($width < $c_width) {
      $width = $c_width;
      $height = ceil(($width / $width_orig) * $height_orig);
    }
    //check widths
    if ($height_orig > $width_orig) {   //portrait
      $width = $c_width;
      
      if (ceil(($width / $width_orig) * $height_orig) > $c_height) {
        $height = $c_height;
        $width = ceil(($height / $height_orig) * $width_orig);
      } else {
        $height = ceil(($width / $width_orig) * $height_orig);
      }
    } else {                            //landscape
      $height = $c_height;
      $width = ceil(($height / $height_orig) * $width_orig);
    }
    
    //forcing part
    
    
    
    //if img too thin - moving into the middle
    if ($width < $c_width) $x_pos = ceil(($c_width-$width)/2);
    else $x_pos = "0";
    imagecopyresampled($image_p, $image, $x_pos, 0, 0, 0, $width, $height, $width_orig, $height_orig);
    imagecopyresampled($image_p, $image_p, 0, 0, 0, 0, $c_width, $c_height, $c_width, $c_height);
    
  } else {
    imagecopyresampled($image_p, $image, 0, 0, 0, 0, $width, $height, $width_orig, $height_orig);
	}
	
	//just copy if width and height are lower or the same
  if ($width_orig <= $width && $height_orig <= $height) {
    copy($_filename, $_tn_name);
    $imagef = fopen ($_tn_name, "rb");
    fpassthru ($imagef);
    fclose ($imagef);
  } else {
    // Output
    switch ($ext) {
      case "png": 
        imagepng($image_p, $_tn_name);
        imagepng($image_p, null); 
        break;
      case "gif":
        imagegif($image_p, $_tn_name);
        imagegif($image_p, null);
        break;
      case "jpg":
      default:
        imagejpeg($image_p, $_tn_name, 80);
        imagejpeg($image_p, null, 80);
    }
  }
	
  imagedestroy($image_p);
	
} else {
  // $imagef = fopen ($_tn_name, "rb");
  // fpassthru ($imagef);
  // fclose ($imagef);
  // OR: 
  readfile ($_tn_name);
}

$SFS->sendLastPHPError();

?> 
