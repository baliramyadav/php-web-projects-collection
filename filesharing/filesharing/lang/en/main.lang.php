<?php

  /********************************
   * SFS 3.30
   * English Language File
   ********************************/       

  $LANG = array_merge($LANG, array( 

      'select_file' => 'Select file...',
      'select_files' => 'Select files...',
      'select_or_drag_file' => 'just select file or <i>Drag and Drop</i> any file here',
      'select_or_drag_files' => 'just select files or <i>Drag and Drop</i> any files here',
      'just_select_file' => 'just select file and upload starts automatically',
      'just_select_files' => 'just select files and upload starts automatically',
      'cancel_upload' => 'Cancel upload',

      'follow_link' => 'Follow link',
      'download_link' => 'Download-Link',
      'delete_link' => 'Link to delete the file',
      'upload_succeeded' => 'Upload succeeded',
      'upload_another_file' => 'Upload another file',
      'download_in' => 'Download in %1$s seconds', //seconds
      'upload_other_files' => 'upload other files',
      'group_link' => 'Link to all Files',
      'all_files' => 'All Files',
      'list_of_files' => 'List of Files',
      'download_has_started' => 'Download has started ...',
      'x_downloads_of_y' => '%1$s of %2$s',     //actual number of downloads, maximum downloads before deletion
      'get_qr_code' => 'get QR Code for this URL',
      'set_short_url' => 'set short URL for this URL',
      'set_short_urls' => 'set short URL for this URLs',
      'hl_qr_code' => 'QR-Code for your mobile device',
      'copy_to_clipboard' => 'Copy this URL to clipboard',
      'share_on_facebook' => 'Share on facebook',
      'share_on_twitter' => 'Share on twitter',
      'share_on_google' => 'Share on goolge+',
      'add_file' => 'Add file',
      'add_files' => 'Add files',
      'back_to_uploads' => 'back to your uploaded files',

      'hl_file_description' => 'Short File Description',
      'add_file_description' => 'add file description',
      'remove_file_description' => 'remove file description',
      'save' => 'Save',

      'send_link' => 'Send link',
      'send_links' => 'Send links',
      'from' => 'From',
      'to' => 'To',
      'message' => 'Message',
      'send_download_link' => 'Send Download-Link',
      'send_download_links' => 'Send Download-Links',
      'agree_to_terms' => 'With submitting the form data I agree with the <a href="#terms">terms and conditions</a>',
      'descr_finishing_upload' => 'finishing upload - please wait ...',
      'show_message' => 'Show the message above on download pages',
      'max_recipients' => 'up to %1$s recipients', //max recipients
      'mailto' => 'recipient email address',
      'mailfrom' => 'sender email address',

      'descr_uploaded' => 'Uploaded on', 
      'descr_downloads' => 'Downloads', 
      'descr_accessible_until' => 'Accessible until', 
      'descr_days_remaining' => '%1$s days remaining', //days until the file will be deleted
      'descr_day_remaining' => 'One day remaining',
      'description' => 'Short description',

      'password_protect' => 'Password protection',
      'en_password_protection' => 'Activate to protect download link with a auto generated password',
      'en_password_protections' => 'Activate to protect all of the download links with one auto generated password',
      'password_protection_OFF' => 'Password protection is OFF',
      'password_protection_ON' => 'Password protection is ON (password: %1$s)',     //password
      'password_line_mailings' => 'Password: %1$s', //password
      'password_modal_hl' => 'This file is password protected',
      'password_modal_placeholder' => 'Password',
      'cancel' => 'Cancel',
      'verify_pwd' => 'Verify Password', 
      'error_enter_password' => 'Please enter the required password.',
      'error_wrong_password' => 'The given password is incorrect.',
      'password' => 'Password',
      'leaving_site_info' => 'All links saved or sent?',

      'del_after_x_days' => 'Delete uploaded file in approximately %1$s days', //form field for the days
      'del_after_x_downloads' => 'Delete uploaded file after %1$s downloads', //form field for the downloads
      'del_after_x_days_multi' => 'Delete uploaded files in approximately %1$s days', //form field for the days
      'del_after_x_downloads_multi' => 'Delete each file after %1$s downloads', //form field for the downloads

      'hl_terms' => 'Terms and Conditions',
      'add_message' => 'add message',

      'success_info_sent' => 'The download information were sent successfully.',
      'success_mess_sent' => 'Your message was sent successfully. We will contact you as soon as possible.',
      'success_delfile' => 'The file was removed successfully.',

      'success_updated_file_description' => 'The file description was updated successfully.',
      'success_removed_file_description' => 'The file description was removed successfully.',

      'errors_occurred' => 'Some errors occurred',
      'error_just_one_file' => 'Just one file can be uploaded at once.',
      'error_max_size' => 'The maximum filesize is about %1$s MB!',   //maximum allowed size
      'error_from_address_failure' => 'The mail-address in the From-field seems to be incorrect.',
      'error_to_address_failure' => 'The mail-address in the To-field seems to be incorrect.',
      'error_both_fields_required' => 'Please fill in both fields.',
      'error_file_failure' => 'Errors occurred with the uploaded file.',
      'error_extension_denied' => 'The extension of your file is not allowed to upload.',
      'error_max_files' => 'Just %1$s files can be uploaded at once.',  //maximum number of multiple file uploads at once
      'error_max_size_multi' => 'At least one of the files exceeds the maximum allowed filesize of %1$s MB! Affected file(s): ', //maximum allowed size
      'error_extension_denied_multi' => 'At least one of the files has a not allowed file extension! Affected file(s): ',
      'error_mailto_troubles' => 'Some of your email addresses seem to be malformed: %1$s', //malformed email addresses
      'error_mailto_none_valid' => 'None of your email addresses seems to be correct.', 
      'error_mailto_max_one' => 'You are only allowed to send to one recipient once.',
      'error_mailto_max_X' => 'You have exceeded the maximum number of %1$s recipients.', //max recipients
      'error_continue_session' => 'You are not allowed to add files to your current upload session.',

      'error_noname' => 'Please type in your name.',
      'error_noemail' => 'Please type in your mail address.',
      'error_email_failure' => 'The email adresse seems to be incorrect.',
      'error_nomessage' => 'Please leave a message.',
      'error_nocaptcha' => 'Please type in the captcha code.',
      'error_wrongcaptcha' => 'The given Captcha Code is incorrect.',
      'error_shortener_no_url' => 'No URL was given to shorten.',
      'error_shortener_failure' => 'Errors occured during generating the short URL - the site administrator has been informed right now about this issue.',

      'info_404notfound' => 'The site you\'re looking for could not be found.',

      'conf_delete_file' => 'Are you sure to delete this file?',

      'no_cancel' => 'No, cancel',
      'yes_delete' => 'Yes, delete file',

      'subject_upload_information' => '%1$s Upload information',        //project(site) name
      'subject_download_information' => 'A file has been sent through %1$s to you',    //project(site) name
      'subject_download_information_multi' => '%1$s files have been sent through %2$s to you',    //number of files, project(site) name

      'mail_file_description' => 'File Description',

      'date_time_format' => 'Y/d/m G:i',     //more information on http://de3.php.net/manual/en/function.date.php
      'date_format' => 'Y/d/m',     //more information on http://de3.php.net/manual/en/function.date.php

      'men_home' => 'Home',
      'men_contact' => 'Contact',

      'hl_faqs' => 'FAQs - frequently asked questions',

      //Some special page content
      //index page
      'index1text' => 'can be defined in the config<br />and will be <strong>recalculated</strong> and decreased if necessary, depending on defined PHP values.',
      'index2hl' => 'Share Files',
      'index2text' => 'share them via email, send emails to you or your friends :) - The sender gets the link to delete the file too.',
      'index3hl' => 'Autodelete',
      'index3text' => 'upped files will be deleted automatically after <strong>%1$s days</strong>, this value can be changed in the config too.',    //days of file's maxage

      //contact page - abuse page
      'hl_contact' => 'Contact',
      'hl_contactform' => 'Contact form',

      'hl_reportfile' => 'Report File',
      'hl_reportfileform' => 'Abuse form',
      'info_abuse' => 'If you are think that on of the files we are hosting is violating applicable law or rights of third parties you are able to report the file with the form below.',

      'descr_fname' => 'File name',
      'descr_fsize' => 'File size',
      'descr_reportfile' => 'Report file',

      'descr_name' => 'Your name',
      'descr_email' => 'Email',
      'descr_tel' => 'Telephone',
      'descr_message' => 'Your message',
      'descr_sendmess' => 'Send message',
      'descr_spam_protection' => 'Spam Protection',
      'info_spam_protection' => 'Please type in the word in the image (Captcha)',
      'info_js_needed' => 'This page requires Javascript and cookies in order to work properly.',

      'placeholder_spam_protection' => 'Spam Protection',
      'placeholder_name' => 'First and last name',
      'placeholder_email' => 'Your email address',
      'placeholder_tel' => 'z.B +1 123 44556678',
      'placeholder_message' => 'Place for some words ...',



      
    ));
?>