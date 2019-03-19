<?php
if ($HTTP_POST_VARS['todo'] != "backup" && $HTTP_GET_VARS['t'] != "download" && $HTTP_POST_VARS['todo'] != "sel_fields" && CRON_JOB!="yes") {
    if (@ini_get('output_handler') == 'ob_gzhandler' || @get_cfg_var('output_handler') == 'ob_gzhandler' || (@function_exists('ob_get_level') && ob_get_level() > 0)) {
    
    }
    else {
        ob_start("ob_gzhandler");
    }
}
define('LEFT_NAVIGATION_WIDTH','25%');
define('MAIN_NAVIGATION_WIDTH','75%');
define('ADMIN_SAFE_MODE','no');
define('ADMIN_BX_DEMO','no');

define('FILENAME_ADMIN','admin.php');
define('FILENAME_ADMIN_FORM','admin_form.php');
define('FILENAME_ADMIN_DETAILS','details.php');
define('FILENAME_ADMIN_SEARCH','admin_search.php');
define('FILENAME_ADMIN_SEARCH_FORM','admin_search_form.php');
define('FILENAME_ADMIN_SEARCH_RESULT_FORM','admin_search_result_form.php');
define('FILENAME_ADMIN_VALIDATION','validation.php');
define('FILENAME_ADMIN_NOVALIDATION','novalidation.php');
define('FILENAME_ADMIN_DECLINE','decline.php');
define('FILENAME_ADMIN_NOVALIDATION_FORM','novalidation_form.php');
define('FILENAME_ADMIN_VALIDATION_BUY','validation_buy.php');
define('FILENAME_ADMIN_VALIDATION_BUY_FORM','validation_buy_form.php');
define('FILENAME_ADMIN_LAYOUT','layout.php');
define('FILENAME_ADMIN_LAYOUT_FORM','layout_form.php');
define('FILENAME_ADMIN_SETTINGS','admin_settings.php');
define('FILENAME_ADMIN_SETTINGS_FORM','admin_settings_form.php');
define('FILENAME_ADMIN_PAYMENT','admin_payment.php');
define('FILENAME_ADMIN_PAYMENT_FORM','admin_payment_form.php');
define('FILENAME_ADMIN_RESTORE_DB','restore_db.php');
define('FILENAME_ADMIN_RESTORE_DB_FORM','restore_db_form.php');
define('FILENAME_ADMIN_BACKUP_DB','backup_db.php');
define('FILENAME_ADMIN_BACKUP_DB_FORM','backup_db_form.php');
define('FILENAME_ADMIN_EXPORT_DB','export_db.php');
define('FILENAME_ADMIN_EXPORT_DB_FORM','export_db_form.php');
define('FILENAME_ADMIN_BULK_EMAIL','bulk_email.php');
define('FILENAME_ADMIN_BULK_EMAIL_FORM','bulk_email_form.php');
define('FILENAME_ADMIN_ADD_LANGUAGE','add_lng.php');
define('FILENAME_ADMIN_ADD_LANGUAGE_FORM','add_lng_form.php');
define('FILENAME_ADMIN_EDIT_LANGUAGE','edit_lng.php');
define('FILENAME_ADMIN_EDIT_LANGUAGE_FORM','edit_lng_form.php');
define('FILENAME_ADMIN_DELETE_LANGUAGE','del_lng.php');
define('FILENAME_ADMIN_DELETE_LANGUAGE_FORM','del_lng_form.php');
define('FILENAME_ADMIN_PASSWORD','admin_passwd.php');
define('FILENAME_ADMIN_PASSWORD_FORM','admin_passwd_form.php');
define('FILENAME_ADMIN_PLANNING','admin_planning.php');
define('FILENAME_ADMIN_PLANNING_FORM','admin_planning_form.php');
define('FILENAME_ADMIN_EDIT_MAIL','edit_mail.php');
define('FILENAME_ADMIN_EDIT_MAIL_FORM','edit_mail_form.php');
define('FILENAME_ADMIN_HELP','admin_help.php');
define('FILENAME_ADMIN_EDIT_FILE','edit_file.php');
define('FILENAME_ADMIN_EDIT_FILE_FORM','edit_file_form.php');

define('TEXT_SAFE_MODE_ALERT','<font style="font-size: 11px; font-weight: bold; color: #FF0000;">This Feature is not available in Safe Mode.</font>');
define('TEXT_DEMO_MODE_ALERT','<font style="font-size: 11px; font-weight: bold; color: #FF0000;">This Feature is not available in this Demo.</font>');
include("admin_functions.php");
?>