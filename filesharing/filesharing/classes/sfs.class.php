<?php


class SFS {

	var $version = "3.60";
	var $error = false;
	var $success = false;
	var $json = false;



	/***
	 * $config ... (object) mainly used for the db credentials and the secret key
	 * $config->options ... (assoc array) used to overrule/expand config
	 ***/
	function __construct($config) {
		$this->config = $config;
		$this->options = $config->options;
		unset($this->config->options);

		$this->HTTP_USER_AGENT = isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : null;

		$this->dbconnect();

		$this->setGetDBversion();
		
    $this->setConfig();

		$this->setTimezone();

		$this->sendLastPHPError();


   }

  //connect to database - they mysqli way
  function dbconnect() {
	  $this->db = mysqli_connect($this->config->db_host, $this->config->db_user, $this->config->db_pass, null, isset($this->config->db_port) ? $this->config->db_port : null) or die("<b>Database-connection failed</b>,<br>please use the correct data for your database.");
	  mysqli_set_charset($this->db,'utf8');
	  mysqli_select_db($this->db,$this->config->db_name) or $error[] = "Database " . $this->config->db_name . " not found or no rights for access.";

		//avoid possible group by incompatibilities by setting SESSION based sql mode
	  $sql = "show variables like 'sql_mode'";
	  $res = $this->dbquery($sql);
	  $row = mysqli_fetch_object($res);
	  $SQLMode = $row->Value;
	  //enable group by functionality
	  $newSQLMode = preg_replace('/only_full_group_by,?/i', '', $SQLMode);
	  //enable inserts exceeding fieldlength by truncating
	  $newSQLMode = preg_replace('/strict_trans_tables,?/i', '', $newSQLMode);

	  $sql = "SET SESSION sql_mode = " . $this->dbquote($newSQLMode);
	  $this->dbquery($sql);

	  $this->options["tablePrefix"] = $this->config->tablePrefix;
	  $this->options["db_name"] = $this->config->db_name;
  }


	/**
	 * This functions is used to handle all database queries of the project
	 * and if an error occurs an usefull email to an administrator will be sent
	 * 
	 * @param string $sql - the SQL Query
	 * 
	 * @return the MYSQL result identifier, send's an email on error
	 **/ 
	function dbquery($sql) {
	  //own db debugger

	  $res = mysqli_query($this->db,$sql);
	  if (mysqli_errno($this->db)) {
	    mail($this->config->admin_mail, strip_tags($this->config->projectName) . ' DB-Error',$this->config->projecturl . $_SERVER['PHP_SELF']."?".$_SERVER['QUERY_STRING']."\n\nMySQL error ".mysqli_errno($this->db).": ".mysqli_error($this->db)."\nWhen executing:\n$sql\nHTTP_USER_AGENT: " . $this->HTTP_USER_AGENT . "\nREMOTE_ADDR: ".$_SERVER['REMOTE_ADDR']."\nHTTP_HOST: ".$_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"]);
	  }
	  return $res;
	}


	/**
	 * quotes and escape quotes of single strings, or returns NULL for the use in mysql queries
	 * makes only sense for strings 
	 * Attention: the Quotes are part of the output:
	 *    dbquote("hallo")    ->    "'hallo'"
	 *    dbquote("c'mon")    ->    "'c\'mon'"
	 *    dbquote("")         ->    "NULL"  
	 *    use
	 *      field = " . dbquote($val) . "
	 *    don't use 
	 *      field = '" . dbquote($val) . "' 
	 * 
	 * @param string $str - the string to check
	 * 
	 * @return formated string for the use in sql statements
	 **/ 
	function dbquote($str) {
		$str = "'" . mysqli_real_escape_string($this->db,$str) . "'";
	  return $str;
	}


	/** for some compatibility issues ***/
	function setGetDBversion() {
		if (isset($_SESSION["dbVersion"])) {
			$this->dbVersion = $_SESSION["dbVersion"];
		} else {
			$sql = "select version() as v";
			$res = $this->dbquery($sql);
			$row = mysqli_fetch_object($res);
			$this->dbVersion = $_SESSION["dbVersion"] = floatval($row->v);
		}
	}

	/**
	 * send notification of latest PHP errors if occur to find troubles with the installation more efficient
	 ***/
	function sendLastPHPError() {
		$error = error_get_last();
		if ($error) {
			$HTTP_REFERER = isset($_SERVER["HTTP_REFERER"]) ? $_SERVER["HTTP_REFERER"] : "";
			$url = $this->config->projecturl . $_SERVER['PHP_SELF'] . ($_SERVER['QUERY_STRING'] ? "?" . $_SERVER['QUERY_STRING'] : "");
			$sql = "insert into `" . $this->config->tablePrefix . "error_log` set
				message = " . $this->dbquote($error["message"]) . ",
				file = " . $this->dbquote($error["file"]) . ",
				line = " . intval($error["line"]) . ",
				url = " . $this->dbquote($url) . ",
				referer = " . $this->dbquote($HTTP_REFERER) . ",
				ip = " . $this->dbquote($_SERVER["REMOTE_ADDR"]) . ",
				created = now()";
			$this->dbquery($sql);
	    // mail($this->config->admin_mail, strip_tags($this->config->projectName) . ' PHP-Site-Error', $this->config->projecturl . $_SERVER['PHP_SELF']."?".$_SERVER['QUERY_STRING']."\n---\n" .print_r($error,true));
		}
	}

	/**
	* show saved errors
	 ***/
	function getErrorLogEntries() {
		$logEntries = array();
		//group entries to get groups :)
		$sql = "select count(id) as cnt, file, message, max(created) as latest from `" . $this->config->tablePrefix . "error_log` group by file, message order by latest desc";
		$res = $this->dbquery($sql);
		while ($row = mysqli_fetch_object($res)) {
			$row->relFile = preg_replace('!^'.realpath($_SERVER["DOCUMENT_ROOT"]).'!', "", str_replace('\\','/',$row->file));
			$logGroupKey = md5($row->file.$row->message);
			$logEntries[$logGroupKey] = $row;
		}
		if ($logEntries) {
			//all the log entries
			$sql = "select * from `" . $this->config->tablePrefix . "error_log` order by file, created desc";
			$res = $this->dbquery($sql);
			while ($row = mysqli_fetch_object($res)) {
				$logGroupKey = md5($row->file.$row->message);
				$row->relFile = preg_replace('!^'.realpath($_SERVER["DOCUMENT_ROOT"]).'!', "", str_replace('\\','/',$row->file));
				$logEntries[$logGroupKey]->logItems[] = $row;
			}		
		}
		return $logEntries;
	}

	/**
	* remove log entries
	 ***/
	function removeLogEntries($logGroup) {
		$logGroup = trim($logGroup);
		$error = array();
		$success = false;
		if (!$logGroup) {
			$error[] = "Insufficient Data sent.";
		} else {
			$sql = "delete from `" . $this->config->tablePrefix . "error_log` where md5(concat(file,message)) = " . $this->dbquote($logGroup);
			$this->dbquery($sql);
			$success = "<strong>" . intval(mysqli_affected_rows($this->db)) . "</strong> Log Entries were successfully deleted.";
		}
		$this->json = json_encode(array("success" => $success, "error" => $error ? implode("<br />",$error) : false));
	}



	function checkEnvironment() {
		$error = $gdError = array();
		$gdError = null;
		//gd check
		if (!extension_loaded('gd')) {
			$gdError[] = "GD (image) library is NOT loaded ";
		}
		if(!function_exists('gd_info')) {
			$gdError[] = "GD function support is NOT available ";
		}
		if ($gdError && $this->config->imagePreview) {
			$error[] = implode(" AND ", $gdError) . "<br />Please fix or consider to deactivate <a href='?as=settings&ass=upload'>Image Preview</a>.";
		}
		return $error;
	}


	/**
	 * set config object for the SFS project
	 * 
	 * @param nothing
	 * 
	 * @return object with extended config data
	 **/ 
	function setConfig() {
		//from the db
		$sql = "select * from `" . $this->config->tablePrefix . "config` where id = 1";
		$res = $this->dbquery($sql);
		if ($res !== false && mysqli_num_rows($res)) {
			$configData = mysqli_fetch_object($res);
			//expand data??
			// $this->config = (object) array_merge((array) $this->config, (array) $configData); // not longer needed, because of the options array
			$this->config = $configData;

			$this->config->maxFileSizeDB = $this->config->maxFileSize;  //for the settings page
			$this->config->multiUploadDB = $this->config->multiUpload;  //for the settings page
		} else {
			$this->config = new stdClass();
		}

		/**
		 * recalculations of maximum upload size [+]
		 **/
		$maxFileSizeByte = $this->config->maxFileSize * 1024 * 1024;

		$pms = ini_get("post_max_size");
		//considerations for K, M and G values instead of the recommended integer in Byte
		//http://php.net/manual/en/ini.core.php#ini.post-max-size
		//Kilo
		if (preg_match('/k$/i',$pms)) $pms = intval($pms) * 1024;
		//mega
		elseif (preg_match('/m$/i',$pms)) $pms = intval($pms) * 1024 * 1024;
		//giga
		elseif (preg_match('/g$/i',$pms)) $pms = intval($pms) * 1024 * 1024 * 1024;
		//integer
		else $pms = intval($pms);
		if ($maxFileSizeByte > $pms) $maxFileSizeByte = $pms - 1024*1024;	//reduce by 1 MB

		//considerations for K, M and G values instead of the recommended integer in Byte
		//http://php.net/manual/en/ini.core.php#ini.upload-max-filesize
		$umf = ini_get("upload_max_filesize");
		//Kilo
		if (preg_match('/k$/i',$umf)) $umf = intval($umf) * 1024;
		//mega
		elseif (preg_match('/m$/i',$umf)) $umf = intval($umf) * 1024 * 1024;
		//giga
		elseif (preg_match('/g$/i',$umf)) $umf = intval($umf) * 1024 * 1024 * 1024;
		//integer
		else $umf = intval($umf);
		if ($maxFileSizeByte > $umf) $maxFileSizeByte = $umf - 1024*1024;	//reduce by 1 MB

		$this->config->maxFileSize = $maxFileSizeByte/1024/1024;
		/**
		 * recalculations of maximum upload size [-]
		 **/

		//reset multiUpload 
		// - for Safari on Windows platforms
		// - files lower than 2
		if ($this->config->multiUpload && ($this->config->maxMultiFiles < 2 || (stripos($this->HTTP_USER_AGENT,"chrome") === false && preg_match('/windows.*safari/i',$this->HTTP_USER_AGENT)))) {
			$this->config->multiUpload = false;
		}

		$this->config->projectName = __CLASS__;

		/** url and path definitions **/
		$this->config->uploadDir = dirname(dirname(__FILE__)) . "/data/";
		$this->config->modulesDir = dirname(dirname(__FILE__)) . "/modules/";

		$this->config->instDir = dirname(preg_replace('!^'.addslashes(realpath($_SERVER["DOCUMENT_ROOT"])).'!', "", str_replace('\\','/',dirname(__FILE__))));

		//add root slash if not avail 
		if (!preg_match('/^\//',$this->config->instDir)) $this->config->instDir = '/' . $this->config->instDir;	

		if ($this->config->instDir == "/") $this->config->instDir = "";

		$this->config->projecturl = (isset($_SERVER["HTTPS"]) && ($_SERVER["HTTPS"] == 1 || $_SERVER["HTTPS"] === "on") ? "https" : "http") . "://" . $_SERVER["HTTP_HOST"];
		$this->config->instUrl = $this->config->projecturl . $this->config->instDir;

		$this->config->instDirServer = dirname($this->config->modulesDir);

		//URLPrefixes...will be calculated autmatically
		$this->config->baseDownloadUrl = $this->config->projecturl . $this->config->instDir . "/download/";
		$this->config->baseDeleteUrl = $this->config->projecturl . $this->config->instDir . "/delete/";
		$this->config->baseFilesUrl = $this->config->projecturl . $this->config->instDir . "/files/";
		$this->config->baseGroupUrl = $this->config->projecturl . $this->config->instDir . "/filesgroup/";



		if (preg_match('/^[\+-]00:00/', $this->config->db_timezoneCorrection)) {
			$this->config->db_timezoneCorrection = false;
		}

		//drag and dropable? just for displaying informations
		$this->config->dragndrop = true;
		if (preg_match_all('/msie\s+(\d+)/i',$this->HTTP_USER_AGENT,$ddarr)){
			if (is_numeric($ddarr[1][0]) && $ddarr[1][0] < 10) {
				$this->config->dragndrop = false;
			}
		}
		

		//Microsoft Internet Explorer checks and resets [+]
		$this->config->isMSIE = false;
		$this->config->MSIE_version = 0;
		if ($this->HTTP_USER_AGENT) { //some agents doesn't send their name
			if (preg_match('/msie \d{1,2}/i',$this->HTTP_USER_AGENT) || preg_match('/trident\/\d.*rv:\d{2}/i',$this->HTTP_USER_AGENT)) {
				$this->config->isMSIE = true;
				preg_match('/msie (\d{1,2})/i',$this->HTTP_USER_AGENT,$matches);
				$this->config->MSIE_version = isset($matches[1]) ? intval($matches[1]) : 0;
				if (!$this->config->MSIE_version) {
					preg_match('/trident\/\d.*rv:(\d{2})/i',$this->HTTP_USER_AGENT,$matches);
					$this->config->MSIE_version = isset($matches[1]) ? intval($matches[1]) : 0;
				}
				if ($this->config->MSIE_version < 10) {
					$this->config->multiUpload = false;
				}
			}
		}
		//Microsoft Internet Explorer checks and resets [-]

		//setup arrays
		$this->config->extDeniedArray = preg_split("/,/", $this->config->extDenied,NULL, PREG_SPLIT_NO_EMPTY);
		$this->config->extAllowedArray = preg_split("/,/", $this->config->extAllowed,NULL, PREG_SPLIT_NO_EMPTY);


		//expand and/or overrule config settings
		if ($this->options) {
			foreach ($this->options as $option => $val) {
				$this->config->{$option} = $val;
			}
		}

		//mailheaders
		$this->config->mailHeaders = "From: " . strip_tags($this->config->siteName) . " <" . $this->config->automaileraddr . ">\r\n";
		$this->config->mailHeaders .= "X-Sender:  " . strip_tags($this->config->siteName) . " <" . $this->config->automaileraddr . ">\r\n"; 
		$this->config->mailHeaders .= "X-Mailer: PHP\r\n";
		$this->config->mailHeaders .= "Return-Path: " . $this->config->automaileraddr . "\r\n";
		$this->config->mailHeaders .= "Reply-To: " . $this->config->automaileraddr . "\r\n";
		$this->config->mailHeaders .= "Mime-Version: 1.0\r\n";
		$this->config->mailHeaders .= "Content-Type: text/html; charset=UTF-8\r\n";
		$this->config->mailHeaders .= "Content-Transfer-Encoding: 8bit\r\n";
		
		//if running setup version might not be set - so set config version to display it on setup page
		if (!$this->config->version) $this->config->version = $this->version;
	}



	/**
	 * used to set project's timezone 
	 * AND equalize webserver time and database time if needed
	 *
	 *
	 * @param none
	 *
	 * @return nothing
	 **/
	function setTimezone() {
		date_default_timezone_set($this->config->timezone);

	  if ($this->config->db_timezoneCorrection) {
	    $this->dbquery("SET time_zone = " . $this->dbquote($this->config->db_timezoneCorrection));
	  }
	}


	/**
	 * to generate the different secret unreproduce-able keys
	 * 
	 * @param integer $fileId - id (database) of the file
	 * 
	 * @return array(
	 *                string skey         -> secret download key
	 *                string deletekey    -> secret deletion key
	 *              )
	 * used in the sql query on the admin page too
	 **/ 
	function genFileKeys($fileId) {
	  $fileId = intval($fileId);
	  if (!$fileId) return false;
	  $sql = "select md5(concat(id,'~'," . $this->dbquote($this->config->secretKey) . ",'##',created)) as skey, md5(concat(id,'DE33LE'," . $this->dbquote($this->config->secretKey) . ",'}}',uid)) as delkey from `" . $this->config->tablePrefix . "files` where id = '$fileId'";
	  $res = $this->dbquery($sql);
	  if (!mysqli_num_rows($res)) return false;
	  $row = mysqli_fetch_object($res);
	  return array($row->skey,$row->delkey);
	}


	/**
	 * removes files upped more than the days defined in the config before ($config->delDays)
	 *  
	 * @param none
	 * 
	 * @return nothing
	**/
	function cleanUp() {  
	  $delDays = intval($this->config->delDays);
	  if ($delDays < 0 && !$this->config->delSettingsByUploader) return false;

	  //days set???
	  if ($delDays > -1) {
	    if ($this->config->delOn == "download") {
	      $add2sql[] = "datediff(now(), last_download) > $delDays";
	    } else {
	      $add2sql[] = "datediff(now(), created) > $delDays";
	    }
	  }

	  //user set deletion information
	  if ($this->config->delSettingsByUploader) {
	    //days
	    if ($delDays > 1) {
	      $add2sql[] = "(del_days > -1 and datediff(now(), created) > del_days)";
	    }
	    //downloads
	    $add2sql[] = "(del_downloads > 0 and downloads = del_downloads)";
	  }

	  if (!$add2sql) return false;

	  $sql = "select id, uid from `" . $this->config->tablePrefix . "files` where uid = 0 and locked = 0 and (" . implode(" or ",$add2sql) . ")";
	  $res = $this->dbquery($sql);
	  while ($row = mysqli_fetch_object($res)) {
	    //delete from filesystem
	    $file_path = $this->config->uploadDir . $row->uid . "/" . $row->id;
	    if (file_exists($file_path)) xrmdir($file_path);
	  }
	  //delete from database
	  $sql = "delete from `" . $this->config->tablePrefix . "files` where uid = 0 and locked = 0 and (" . implode(" or ",$add2sql) . ")";
	  $this->dbquery($sql);
	  //delete orphan download_handler entries
	  $sql = "delete from `" . $this->config->tablePrefix . "download_handler` where datediff(now(), d_time) > 1";
	  $this->dbquery($sql);
	  //delete orphan messages
	  $sql = "delete from `" . $this->config->tablePrefix . "messages` where u_key not in (select u_key from `" . $this->config->tablePrefix . "files` group by u_key)";
	  $this->dbquery($sql); 
	  //delete orphan database entries
	  $sql = "select id, uid from `" . $this->config->tablePrefix . "files`";
	  $res = $this->dbquery($sql);
	  while ($row = mysqli_fetch_object($res)) {
	    $file_path = $this->config->uploadDir . $row->uid . "/" . $row->id;
	    if (!file_exists($file_path)) {
	      $this->dbquery("delete from `" . $this->config->tablePrefix . "files` where id = '" . $row->id . "'");
	    }
	  }
	}

	/**
	 * Bitly URL shortener/expander API V4.0
	 *  
	 * @param $url (bitly or long url)
	 * @param $todo (shorten or expand)
	 * 
	 * @return long or short url
	**/
	function BitlyShortener($url,$todo="shorten") {
		$outputUrl = $result = null;
	  if (!$url) return false;
	  	$bitly_error = array();

	  if (!$this->config->bitlyUser || !$this->config->bitlyKey) $bitly_error[] = "Needed Bitly User and/or Bitly API Key not set in the config.";
	  //getter method
	  if (!$bitly_error) {
	    //method checker
	    if ((!ini_get("allow_url_fopen") || strtolower(ini_get("allow_url_fopen") == "off") || strtolower(ini_get("allow_url_fopen")) == "false") && !function_exists("curl_init")) {
	      $bitly_error[] = "Neither allow_url_fopen is not activated nor CURL is enabled. Please enable allow_url_fopen or CURL (php.ini).";
	    } elseif ((!ini_get("allow_url_fopen") || strtolower(ini_get("allow_url_fopen") == "off") || strtolower(ini_get("allow_url_fopen")) == "false") && $this->config->connectionMethod == "url_fopen") {
	      $bitly_error[] = "allow_url_fopen is not activated. Please use CURL instead, set \$this->config->connectionMethod to curl or auto.";
	    } elseif (!function_exists("curl_init") && $this->config->connectionMethod == "curl") {
	      $bitly_error[] = "CURL is not enabled. Please set \$this->config->connectionMethod to url_fopen or auto.";
	    }
	    //method setter - if needed
	    if (!$bitly_error) {
	      switch ($this->config->connectionMethod) {
	        case "curl": $method = "curl"; break;
	        case "url_fopen": $method = "url_fopen"; break;
	        default:
	          if (function_exists('curl_init')) $method = "curl";
	          else $method = "url_fopen";        
	          break;
	      }
	    }
	  }
	  if (!$bitly_error) {

	  	switch ($todo) {
	  		case 'shorten':
	  			if ($method == "curl") {
	  				// ********************* SHORTEN curl *********************
						$data = array(
						  'long_url' => $url
						);
						$payload = json_encode($data);
						$header = array(
						    'Authorization: Bearer ' . $this->config->bitlyKey,
						    'Content-Type: application/json',
						    'Content-Length: ' . strlen($payload)
						);
						$ch = curl_init('https://api-ssl.bitly.com/v4/bitlinks');
						curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
						curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
						curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
						curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
						$result = curl_exec($ch);
						if ($result) {
							$result = json_decode($result);
							$outputUrl = $result->link;
						}
	  			} else {
	  				// ********************* SHORTEN fget *********************
	  				$bitlyUrl = "https://api-ssl.bitly.com/shorten?version=4.0.0&login=" .  $this->config->bitlyUser . "&access_token=" . $this->config->bitlyKey . "&format=json&longUrl=" . urlencode($url);
						$result = file_get_contents($bitlyUrl);
						if ($result) {
							$result = json_decode($result);
							if (isset($result->results)) {
								foreach ($result->results as $resData) {
									$outputUrl = $resData->shortUrl;
								}
							}
						}
	  			}
	  			break;
	  		case 'expand':
	  			if ($method == "curl") {
	  				// ********************* EXPAND curl *********************
	  				$data = array(
	    				'bitlink_id' => preg_replace('/^https?:\/\//','',$url)
						);
						$payload = json_encode($data);
						$header = array(
						    'Authorization: Bearer ' . $this->config->bitlyKey,
						    'Content-Type: application/json',
						    'Content-Length: ' . strlen($payload)
						);
						$ch = curl_init('https://api-ssl.bitly.com/v4/expand');
						curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
						curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
						curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
						curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
						$result = curl_exec($ch);
						if ($result) {
							$result = json_decode($result);
							$outputUrl = $result->long_url;
						}
	  			} else {
	  				// ********************* EXPAND fget *********************
						$opts = array('http' =>
					    array(
				        'header'  => "Authorization: Bearer " . $this->config->bitlyKey ."\r\n",
				   		)
						);
						$context  = stream_context_create($opts);
	  				$bitlyUrl = 'https://api-ssl.bitly.com/v4/bitlinks/' . preg_replace('/^https:\/\//','',$url);
	  				$result = file_get_contents($bitlyUrl, false, $context);
						if ($result) {
							$result = json_decode($result);
							$outputUrl = $result->long_url;
						}
	  			}
	  			break;
	  	}
	  }
	  if (!$outputUrl) $bitly_error[] = "No valid response from bitly - no URL generated.";
	  if ($bitly_error) {
	    mail($this->config->admin_mail, 
	    	strip_tags($this->config->siteName) . ' bitly URL shortener Troubles',
	    	$_SERVER['PHP_SELF']."?".$_SERVER['QUERY_STRING']."\n\n- " . implode("<br />- ",$bitly_error) . "\n\n" . print_r($result,true) . "\n\nHTTP_USER_AGENT: ".$this->HTTP_USER_AGENT."\nREMOTE_ADDR: ".$_SERVER['REMOTE_ADDR']."\nHTTP_HOST: ".$this->config->projecturl);
	    return false;
	  } else {
	    return $outputUrl;
	  }
	}





	/**
	 * Adfly URL shortener/expander
	 *  
	 * @param $url (adfly or long url)
	 * @param $todo (shorten or expand)
	 * 
	 * @return long or short url
	**/
	function AdflyShortener($url,$todo="shorten") {
	  if (!$url) return false;
	  $adfly_error = array();

	  if (!$this->config->adflyUid || !$this->config->adflyKey) $adfly_error[] = "Needed Adfly UID and/or Adfly API Key not set in the config.";
	  //getter method
	  if (!$adfly_error) {
	    //method checker
	    if ((!ini_get("allow_url_fopen") || strtolower(ini_get("allow_url_fopen") == "off") || strtolower(ini_get("allow_url_fopen")) == "false") && !function_exists("curl_init")) {
	      $adfly_error[] = "Neither allow_url_fopen is not activated nor CURL is enabled. Please enable allow_url_fopen or CURL (php.ini).";
	    } elseif ((!ini_get("allow_url_fopen") || strtolower(ini_get("allow_url_fopen") == "off") || strtolower(ini_get("allow_url_fopen")) == "false") && $this->config->connectionMethod == "url_fopen") {
	      $adfly_error[] = "allow_url_fopen is not activated. Please use CURL instead, set \$this->config->connectionMethod to curl or auto.";
	    } elseif (!function_exists("curl_init") && $this->config->connectionMethod == "curl") {
	      $adfly_error[] = "CURL is not enabled. Please set \$this->config->connectionMethod to url_fopen or auto.";
	    }
	    //method setter - if needed
	    if (!$adfly_error) {
	      switch ($this->config->connectionMethod) {
	        case "curl": $method = "curl"; break;
	        case "url_fopen": $method = "url_fopen"; break;
	        default:
	          if (function_exists('curl_init')) $method = "curl";
	          else $method = "url_fopen";        
	          break;
	      }
	    }
	  }
	  if (!$adfly_error) {
	    $baseUrl = urlencode($url);
	    if ($todo == "shorten") {
	      $adflyUrl = 'http://api.adf.ly/api.php?key=' . $this->config->adflyKey . '&uid=' . $this->config->adflyUid . '&advert_type=' . $this->config->adflyAdvertType . '&domain=adf.ly&url=' . $baseUrl;

	      //curl
	      if ($method == "curl") {
	        $ch = curl_init();
	        curl_setopt($ch,CURLOPT_URL, $adflyUrl);
	        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	        curl_setopt($ch,CURLOPT_CONNECTTIMEOUT,5);
	        $result = curl_exec($ch);
	        curl_close($ch);
	        $shortURL = trim($result);
	      } 
	      //url_fopen
	      elseif ($method == "url_fopen") {
	        $result = file_get_contents($adflyUrl);
	        $shortURL = trim($result);
	      }

	      if (!$shortURL) $adfly_error[] = "No valid response from adfly - no URL generated.";
	      else {
	        //is not an URL??
	        if (!preg_match('|^https?://|', $shortURL)) {
	          $resArr = json_decode($shortURL);
	          $adfly_error[] = $resArr->errors[0]->msg;
	        }
	      }

	      if ($adfly_error) {
	        mail($this->config->admin_mail, strip_tags($this->config->siteName) . ' adfly URL shortener Troubles',$_SERVER['PHP_SELF']."?".$_SERVER['QUERY_STRING']."\n\n- " . implode("<br />- ",$adfly_error) . "\n\n$result\n\nHTTP_USER_AGENT: ".$this->HTTP_USER_AGENT."\nREMOTE_ADDR: ".$_SERVER['REMOTE_ADDR']."\nHTTP_HOST: ".$this->config->projecturl);
	        return false;
	      } else {
	        preg_match('/^.*\/([0-9a-z]+)\.html$/',$url,$d_data);
	        $key = $d_data[1];
	        //download
	        $sql = "update `" . $this->config->tablePrefix . "files` set adfly_down = " . $this->dbquote($shortURL) . " where md5(concat(id,'~'," . $this->dbquote($this->config->secretKey) . ",'##',created)) = " . $this->dbquote($key);
	        $this->dbquery($sql);
	        //delete
	        $sql = "update `" . $this->config->tablePrefix . "files` set adfly_dele = " . $this->dbquote($shortURL) . " where md5(concat(id,'DE33LE'," . $this->dbquote($this->config->secretKey) . ",'}}',uid)) = " . $this->dbquote($key);
	        $this->dbquery($sql);

	        return $shortURL;
	      }



	    } elseif ($todo == "expand") {
	      $sql = "select adfly_dele, adfly_down, md5(concat(id,'~'," . $this->dbquote($this->config->secretKey) . ",'##',created)) as skey, 
	        md5(concat(id,'DE33LE'," . $this->dbquote($this->config->secretKey) . ",'}}',uid)) as delkey 
	        from `" . $this->config->tablePrefix . "files` where adfly_down = " . $this->dbquote($url) . " or adfly_dele = " . $this->dbquote($url);
	      $res = $this->dbquery($sql);
	      $row = mysqli_fetch_object($res);
	      if ($url == $row->adfly_down) {
	        $longUrl = $this->config->baseDownloadUrl . $row->skey . ".html";
	      } else {
	        $longUrl = $this->config->baseDeleteUrl . $row->delkey . ".html";
	      }
	      return $longUrl;
	    }
	  }
	}

	/**
	 * generate short key for short URLs
	 * 
	 * @param int $len - optional, length of the string
	 * @param str $prefix - optional, prefix if needed
	 * 
	 * @return formated string for the use in sql statements
	 **/ 
	function genKey($len=5,$prefix=false) {
		$key = null;
	  $base = preg_split("//","23456789abcdefghijkmnopqrstuvwxyzABCDEFGHJKLMNPQRSTUVWXYZ");
	  $maxi = count($base)-2;
	  for($i=0;$i<$len;$i++) {
	    $key .= $base[mt_rand(1,$maxi)];
	  }
	  return $prefix . $key;
	}

	/**
	 * uses genKey to get Key and checks if Key is already registered, if so new key will be produced, ...
	 * 
	 * @param none
	 * 
	 * @return uniq key
	 **/
		function getUniqKey() {
			$key = $this->genKey();
			$sql = "select id from `" . $this->config->tablePrefix . "files` where shortkey = " . $this->dbquote($key);
			$res = $this->dbquery($sql);
			if (mysqli_num_rows($res)) $this->getUniqKey();
			else return $key;
		}




	/**
	 * to calculate the microseconds for the bandwidth throtteling
	 *  
	 * @param none
	 * 
	 * @return int ... number of microseconds to wait between 8MB blocks during the download
	 *         disables $config->XSendFile if seconds are calculated
	**/
	function calcBW() {
	  $kbps = intval($this->config->kbps);
	  if (!$kbps || $kbps < 0) return false;
	  $this->config->XSendFile = false;
	  return intval(3500000/$kbps);
	}


	/***
	 * get list of modules found for admin config operations
	 ***/
	function getModules() {
		$modules = array();
		$error = false;
		if (!$this->config->modulesDir) {
			$error = "The Modules Directory is not defined, are you running an up-to-date version of SFS?";
		} elseif (!is_dir($this->config->modulesDir)) {
			$error = "The Modules Directory wasn't found, please either create<br /><code>" . $this->config->modulesDir . "</code><br />or set the correct value for<br /><code>config->modulesDir</code>";
		}
		if ($error) {
			$this->error = $error;
			return false;
		}

		//available mods (filebased)
		$dh = opendir($this->config->modulesDir);
		while (false !== ($module = readdir($dh))) {
			if (!preg_match('/^\.\.?$/',$module) && is_dir($this->config->modulesDir . "$module") && file_exists($this->config->modulesDir . "$module/mod.json")) {
				//open mod info
				$fh = fopen($this->config->modulesDir . "$module/mod.json","r");
				$fContent = fread($fh,filesize($this->config->modulesDir . "$module/mod.json"));
				fclose($fh);
				$jsonObj = json_decode($fContent);
				if (is_object($jsonObj) && $jsonObj->name && $jsonObj->mod == $module) {
					$modData[$module] = array("mod" => $module,
																	 	"icon" => $jsonObj->icon,
																	 	"name" => $jsonObj->name,
																	 	"version" => $jsonObj->version,
																	 );

				}
			}
		}
		closedir($dh);

		//available mods (databasebased)
		if (isset($modData) && $modData) {
			$sql = "select * from `" . $this->config->tablePrefix . "modules`";
			$res = $this->dbquery($sql);
			while ($row = mysqli_fetch_object($res)) {
				$modData[$row->modname]["DB_installed"] = $row->installed;
				$modData[$row->modname]["DB_installed_version"] = $row->installed_version;
				$modData[$row->modname]["DB_status"] = $row->status;
				$modData[$row->modname]["inDB"] = true;
			}
		}

		if (isset($modData) && $modData) {
			//just for sorting reasons
			$modShorts = array_keys($modData);
			natcasesort($modShorts);
			foreach ($modShorts as $val) {
				$modules[] = $modData[$val];
			}
		}
		return $modules;
	}



	/***
	 * list of installed MODules
	 ***/
	function mods_installed() {
		$mods_enabled = array();
		$sql = "select * from `" . $this->config->tablePrefix . "modules` where installed = 1";
		$res = $this->dbquery($sql);
		while ($row = mysqli_fetch_object($res)) {
			$mods_enabled[] = $row->modname;
		}
		return $mods_enabled;
	}	

	/***
	 * list of enabled MODules
	 ***/
	function mods_enabled() {
		$mods_enabled = array();
		$sql = "select * from `" . $this->config->tablePrefix . "modules` where installed = 1 and status = 1";
		$res = $this->dbquery($sql);
		while ($row = mysqli_fetch_object($res)) {
			$mods_enabled[] = $row->modname;
		}
		return $mods_enabled;
	}

	/***
	 * install MOD
	 ***/
	function install_mod($modname) {
		$modname = trim($modname);
		$error = array();
		$success = $installationInstructions = false;
		$version = 0;
		if (!$modname) {
			$error[] = "Please provide a Module to install.";
		}
		if (!$error) {
			//already installed?
			$sql = "select * from `" . $this->config->tablePrefix . "modules` where modname = " . $this->dbquote($modname) . " and installed = 1";
			$res = $this->dbquery($sql);
			if (mysqli_num_rows($res)) {
				$error[] = "The mod seems to be already installed.";
			}
		}
		if (!$error) {
			//is there a mod class and if, are there install instructions???
			$modClassFile = $this->config->modulesDir . $modname . "/classes/" . $modname . ".class.php";
			$installationInstructionsFile = $this->config->modulesDir . $modname . "/docs/install.html";
			if (file_exists($modClassFile)) {
				include($modClassFile);
				$MOD = new $modname($this);
				if (method_exists($MOD,"install")) {
					$MOD->install();
					if ($MOD->success) {
						//mark as installed :)
						$sql = "insert into `" . $this->config->tablePrefix . "modules` 
							(modname, installed, installed_version, status) values 
							(" . $this->dbquote($modname) . ", 1, '" . floatval($MOD->modInfo->version) . "', 1) 
							on duplicate key update installed = 1, status = 1";
						$this->dbquery($sql);
						$success = $MOD->success;
						$version = $MOD->modInfo->version;
						if (file_exists($installationInstructionsFile)) {
							$installationInstructions = file_get_contents($installationInstructionsFile);
						}
					} elseif ($MOD->error) {
						$error[] = $MOD->error;
					} else {
						$error[] = "There was no response from the module installer.";
					}
				} else {
					$error[] = "Mod's method to install mod is missing.";
				}
			} else {
				$error[] = "Mod's class file is missing.";
			}	
		}


		$this->json = json_encode(array("success" => $success, "error" => $error ? implode("<br />",$error) : false, "version" => $version, "installationInstructions" => $installationInstructions));


	}


	/***
	 * uninstall MOD
	 ***/
	function uninstall_mod($modname) {
		$modname = trim($modname);
		$error = array();
		$success = $uninstallInstructions = false;
		if (!$modname) {
			$error[] = "Please provide a Module to uninstall.";
		}
		if (!$error) {
			//already uninstalled?
			$sql = "select * from `" . $this->config->tablePrefix . "modules` where modname = " . $this->dbquote($modname);
			$res = $this->dbquery($sql);
			if (!mysqli_num_rows($res)) {
				$error[] = "This mod is not installed.";
			} else {
				$row = mysqli_fetch_object($res);
				if (!$row->installed) {
					$error[] = "This mod has been already uninstalled.";
				}
			}
		}
		if (!$error) {
			//is there a mod class and if, are there uninstall instructions???
			$modClassFile = $this->config->modulesDir . $modname . "/classes/" . $modname . ".class.php";
			$uninstallInstructionsFile = $this->config->modulesDir . $modname . "/docs/uninstall.html";
			if (file_exists($modClassFile)) {
				include($modClassFile);
				$MOD = new $modname($this);
				if (method_exists($MOD,"uninstall")) {
					$MOD->uninstall();
					if ($MOD->success) {
						//mark as uninstalled :)
						$sql = "update `" . $this->config->tablePrefix . "modules` set installed = 0 where modname = " . $this->dbquote($modname);
						$this->dbquery($sql);
						$success = $MOD->success;
						if (file_exists($uninstallInstructionsFile)) {
							$uninstallInstructions = file_get_contents($uninstallInstructionsFile);
						}
					} elseif ($MOD->error) {
						$error[] = $MOD->error;
					} else {
						$error[] = "There was no response from the module uninstaller.";
					}
				} else {
					$error[] = "Mod's method to uninstall mod is missing.";
				}
			} else {
				$error[] = "Mod's class file is missing.";
			}	
		}

		$this->json = json_encode(array("success" => $success, "error" => $error ? implode("<br />",$error) : false, "uninstallInstructions" => $uninstallInstructions));
	}


	/***
	 * enable/disable MOD
	 ***/
	function change_mod_status($modname,$status) {
		$modname = trim($modname);
		$status = $status == 0 ? 0 : 1;
		$error = array();
		$success = false;
		if (!$modname) {
			$error[] = "Please select a module.";
		}
		if (!$error) {
			//installed?
			$sql = "select * from `" . $this->config->tablePrefix . "modules` where modname = " . $this->dbquote($modname);
			$res = $this->dbquery($sql);
			if (!mysqli_num_rows($res)) $error[] = "This mod is not installed.";
		}
		if (!$error) {
			$sql = "update `" . $this->config->tablePrefix . "modules` set status = '$status' where modname = " . $this->dbquote($modname);
			$this->dbquery($sql);
			$success = "The module has been " . ($status ? "enabled" : "disabled") . " successfully.";
		}

		$this->json = json_encode(array("success" => $success, "error" => $error ? implode("<br />",$error) : false));

	}


	/***
	 * remove MOD
	 ***/
	function remove_mod($modname) {
		$modname = trim($modname);
		$error = array();
		$success = $removalInstructions = false;
		if (!$modname) {
			$error[] = "Please provide a Module to remove.";
		}

		if (!$error) {
			//is there a mod class and if, are there removal instructions???
			$removalInstructionsFile = $this->config->modulesDir . $modname . "/docs/remove.html";
			$modClassFile = $this->config->modulesDir . $modname . "/classes/" . $modname . ".class.php";
			if (file_exists($modClassFile)) {
				include($modClassFile);
				$MOD = new $modname($this);
				if (method_exists($MOD,"remove")) {
					$MOD->remove();
				}
			}	
			$sql = "delete  from `" . $this->config->tablePrefix . "modules` where modname = " . $this->dbquote($modname);
			$this->dbquery($sql);
			$success = "The mod has been removed from the SFS database structure.";

			if (file_exists($removalInstructionsFile)) {
				$removalInstructions = file_get_contents($removalInstructionsFile);
			} else {
				$removalInstructions = "To completely remove the mod from your system please remove the whole directory <code>modules/$modname</code> from the modules directory.";
			}
		}

		$this->json = json_encode(array("success" => $success, "error" => $error ? implode("<br />",$error) : false, "removalInstructions" => $removalInstructions));
	}


	/***
	 * healthcheck MOD
	 ***/
	function healthcheck_mod($modname) {
		$modname = trim($modname);
		$error = array();
		$healthCheckResults = false;
		if (!$modname) {
			$error[] = "Please provide a Module to uninstall.";
		}
		if (!$error) {
			//is there a mod class
			$modClassFile = $this->config->modulesDir . $modname . "/classes/" . $modname . ".class.php";
			if (file_exists($modClassFile)) {
				include($modClassFile);
				$MOD = new $modname($this);
				if (method_exists($MOD,"healthCheck")) {
					$healthCheckResults = $MOD->healthCheck();
					if (!$healthCheckResults) {
						$error[] = "There was no response from the module healt check.";
					}
				} else {
					$error[] = "Mod's method to check the mod is missing.";
				}
			} else {
				$error[] = "Mod's class file is missing.";
			}	
		}

		$this->json = json_encode(array("error" => $error ? implode("<br />",$error) : false, "healthCheckResults" => $healthCheckResults));
	}



	/***
	 * manual for MOD
	 ***/
	function mod_manual($modname) {
		$modname = trim($modname);
		$error = array();
		$modManual = false;
		if (!$modname) {
			$error[] = "Please provide a module.";
		}
		if (!$error) {
			//is there a mod class and a modManual class??????
			$modClassFile = $this->config->modulesDir . $modname . "/classes/" . $modname . ".class.php";
			if (file_exists($modClassFile)) {
				include($modClassFile);
				$MOD = new $modname($this);
				if (method_exists($MOD,"modManual")) {
					$modManual = $MOD->modManual();
				}
			}
			//still no manual data???
			//let's search the docs directoy
			if (!$modManual) {
				$modManualFile = $this->config->modulesDir . $modname . "/docs/manual.html";
				if (file_exists($modManualFile)) {
					$modManual = file_get_contents($modManualFile);
				}
			}
			//no manual???
			if (!$modManual) {
				$error[] = "There was no manual data found for this module.";
			}
		}

		$this->json = json_encode(array("error" => $error ? implode("<br />",$error) : false, "modManual" => $modManual));
	}

}
?>