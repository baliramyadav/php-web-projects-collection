<?php
include("config.php");

include("classes/sfs.class.php");
$SFS = new SFS($config);

$config = $SFS->config;

include("functions.php");

include_once("lang/" . $config->lang . "/main.lang.php");

$jsVars = array("maxFileSizeMB" => $config->maxFileSize, 
				"maxFileSizeB" => intval($config->maxFileSize * 1024 * 1024), 
				"shortUrlbaseDownloadUrl" => $config->instUrl . "/",
				"baseDownloadUrl" => $config->baseDownloadUrl, 
				"baseDeleteUrl" => $config->baseDeleteUrl,
				"extDenied" => $config->extDeniedArray,
				"extAllowed" => $config->extAllowedArray,
				"downloadSeconds" => intval($config->downloadSeconds),
				"multiUpload" => $config->multiUpload,
				"maxMultiFiles" => intval($config->maxMultiFiles),
				"isMSIE" => $config->isMSIE,
				"MSIE_version" => $config->MSIE_version,
				"maxRcpt" => $config->maxRcpt,
				"addAnotherFiles" => $config->addAnotherFiles,
				"siteName" => $config->siteName
				);

$jsLangs = array("lang_success_info_sent" => lang("success_info_sent"),
				 "lang_success_mess_sent" => lang("success_mess_sent"),
				 "lang_error_just_one_file" => lang("error_just_one_file"),
				 "lang_error_max_size" => sprintf(lang("error_max_size"), $config->maxFileSize),
				 "lang_error_both_fields_required" => lang("error_both_fields_required"),
				 "lang_error_extension_denied" => lang("error_extension_denied"),
				 "lang_password_modal_hl" => lang("password_modal_hl"),
				 "lang_password_modal_placeholder" => lang("password_modal_placeholder"),
				 "lang_cancel" => lang("cancel"),
				 "lang_verify_pwd" => lang("verify_pwd"),
				 "lang_error_enter_password" => lang("error_enter_password"),
				 "lang_error_max_files" => sprintf(lang("error_max_files"), $config->maxMultiFiles),
				 "lang_error_max_size_multi" => sprintf(lang("error_max_size_multi"),$config->maxFileSize),
				 "lang_error_extension_denied_multi" => lang("error_extension_denied_multi"),
				 "lang_leaving_site_info" => lang("leaving_site_info"),
				 "lang_download_has_started" => lang("download_has_started"),
				 "lang_hl_qr_code" => lang("hl_qr_code"),
				 "lang_descr_finishing_upload" => lang("descr_finishing_upload"),
				 "lang_add_file_description" => lang("add_file_description"),
				 "lang_remove_file_description" => lang("remove_file_description"),
				 "lang_success_updated_file_description" => lang("success_updated_file_description"),
				 "lang_success_removed_file_description" => lang("success_removed_file_description"),
				 "lang_errors_occurred" => lang("errors_occurred"),
				 "lang_error_continue_session" => lang("error_continue_session")
				);


if ($_POST["return"] == "json") {
	echo json_encode(array_merge($jsVars,$jsLangs));
}

?>