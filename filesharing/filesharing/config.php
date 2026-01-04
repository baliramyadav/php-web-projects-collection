<?php
error_reporting(E_ALL ^ E_NOTICE);

ini_set("session.gc_maxlifetime",84600);
@session_start();

$config = new stdClass();


//database settings
$config->db_host = "localhost";                             //change!!
$config->db_user = "software_filesharinguser22";                               //change!!
$config->db_pass = "8@T)vtCrv&-4";                               //change!!
$config->db_name = "software_filesharingdb222";                               //change!!
//prefix for the genereated tables
$config->tablePrefix = "SFS355_";
//$config->tablePrefix = "";

//port for database server - please set this value only when it doesn't run on default port
// $config->db_port = 3306;

//name of your project
$config->siteName = "Simple<wbr />File<wbr />Sharer";

//secret key for crypting/enrypting the URLs
//should be changed once and will be used to create an encoded strings for the uploaded files
$config->secretKey = "TGZlAhwB0fGR7Y0L";


//themes for SFS - default bootstrap 3.3.6 and bootswatch themes
// $config->bootstrapTheme = "default";
$config->bootstrapTheme = "default-themed";
// $config->bootstrapTheme = "cerulean";
// $config->bootstrapTheme = "cosmo";
// $config->bootstrapTheme = "darkly";
// $config->bootstrapTheme = "flatly";
// $config->bootstrapTheme = "journal";
// $config->bootstrapTheme = "lumen";
// $config->bootstrapTheme = "paper";
// $config->bootstrapTheme = "readable";
// $config->bootstrapTheme = "sandstone";
// $config->bootstrapTheme = "simplex";
// $config->bootstrapTheme = "slate";
// $config->bootstrapTheme = "spacelab";
// $config->bootstrapTheme = "superhero";
// $config->bootstrapTheme = "united";
// $config->bootstrapTheme = "yeti";

//these are the automatically set navbar styles, depending on used bootstrap theme
$config->navbar_style =  "navbar-inverse";
if (in_array($config->bootstrapTheme,array("darkly","flatly","cerulean","cosmo","lumen","paper","sandstone","slate","superhero","united"))) $config->navbar_style = "navbar-default";


//Simple File Sharer admin credentials
$config->user = "admin";
$config->pass = "pass";



$LANG = array();
$faqs = array();


/*****
 * auto setup block [+]
 * can be removed after setup succeeded
 *****/
$onSetupPage = false;
if (preg_match('~/setup\.php$~',$_SERVER["SCRIPT_NAME"])) $onSetupPage = true;
if (file_exists("setup.php") && !$onSetupPage) {
	$thisDir = dirname($_SERVER["SCRIPT_NAME"]);
	if ($thisDir == "/") unset($thisDir);
	header("location: $thisDir/setup.php");
	exit;
}
/*****
 * auto setup block [-]
 * can be removed after setup succeeded
 *****/

$sfs_auth = isset($_SESSION["sfs_auth"]) ? $_SESSION["sfs_auth"] : false;

//language parts incl. language switcher
$config->defaultLanguage = "en"; //the default language, if none is selected
//for the language dropdown - if not needed, just set it to false - dropdown itself can be found in includes/header.php
//key => Description, where key is the exact name of the directory inside the lang-folder
// $config->languages = false;
$config->languages = array("en" => "English",
													 "de-Du" => "Deutsch (Du)",	//the familiar German version - you should just use one German version and remove (Sie) or (Du) from the description
													 "de-Sie" => "Deutsch (Sie)",	//the formal German version - you should just use one German version and remove (Sie) or (Du) from the description
													 );


//don't modify lines from here [+]
$config->lang = isset($_SESSION["sfsLang"]) ? $_SESSION["sfsLang"] : null;
if (isset($_REQUEST["setLang"]) && $_REQUEST["setLang"] && file_exists("lang/" . $_REQUEST["setLang"] . "/main.lang.php")) {
	$config->lang = $_SESSION["sfsLang"] = $_REQUEST["setLang"];
}
//default language
if (!$config->lang) $config->lang = $config->defaultLanguage;
//don't modify lines to here [-]


//feel free to add any other option to extend config object or to overrule any database settings, that can be made in the Settings Page in the administration
//BUT don't remove the initially added key-value pairs 
//By default the data directory within your installation directory is the upload directory, if you need another directory please add/use this to the array below
//something like
//	"uploadDir" => "/home/mac/www/files.envato.net/data/"
// AND please don't forget the trailing slash
$config->options = array("user" => $config->user,
												 "pass" => $config->pass,

												 // "admin_mail" => "admin@yourDomain.com",

												 "secretKey" => $config->secretKey,

												 "siteName" => $config->siteName,

												 "bootstrapTheme" => $config->bootstrapTheme,
												 "navbar_style" => $config->navbar_style,

												 // "uploadDir" => "/home/mac/www/files.envato.net/data/",

												 "defaultLanguage" => $config->defaultLanguage,
												 "languages" => $config->languages,
												 "lang" => $config->lang);
												 
?>
