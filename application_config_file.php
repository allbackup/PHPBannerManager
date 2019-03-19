<?php
############################################################
# php-Banner-Manager(TM)                                   #
# Copyright © 2002-2003 BitmixSoft. All rights reserved.   #
#                                                          #
#  /			                   #
# File: application_config_file.php                        #
# Last update: 8/02/2002                                   #
############################################################
define('HTTP_SERVER','/');
define('HTTPS_SERVER','https://www.scriptdemo.c0m/php-banner-manager/ver2.0/');
define('HTTP_SERVER_ADMIN','/admin/');
define('DIR_SERVER_ROOT','/home/bitmix/public_html/php-banner-manager/ver2.0/');
define('DIR_SERVER_ADMIN','/home/bitmix/public_html/php-banner-manager/ver2.0/admin/');

define('DB_SERVER_TYPE', 'mysql');
define('DB_SERVER','localhost');
define('DB_SERVER_USERNAME','bitmix_scrdemo');
define('DB_SERVER_PASSWORD','25yas09');
define('DB_DATABASE','bitmix_demo2');

  #######################################################
#                  !!!!!!!!!!!                            #
#  Do not edit below only if you know what are you doing  #
#                  !!!!!!!!!!!                            #
  #######################################################

define('DIR_FUNCTIONS',DIR_SERVER_ROOT.'functions/');
define('DIR_LANGUAGES', DIR_SERVER_ROOT.'languages/');
define('DIR_FORMS',DIR_SERVER_ROOT.'forms/');
define('DIR_ADMIN',DIR_SERVER_ROOT.'admin/');
define('DIR_HTML',DIR_SERVER_ROOT.'html/');
define('DIR_JS',DIR_SERVER_ROOT.'js/');
define('DIR_LOGO',DIR_SERVER_ROOT.'logo/');
define('HTTP_LOGO',HTTP_SERVER.'logo/');
define('HTTP_IMAGES',HTTP_SERVER.'other/');
define('DIR_IMAGES',DIR_SERVER_ROOT.'other/');
define('HTTP_FLAG',HTTP_SERVER.'other/flags/');
define('DIR_FLAG',DIR_SERVER_ROOT.'other/flags/');
define('DIR_PHOTO',DIR_SERVER_ROOT.'photos/');
define('HTTP_PHOTO',HTTP_SERVER.'photos/');
define('DIR_BANNER',DIR_SERVER_ROOT.'');
define('HTTP_BANNER',HTTP_SERVER.'');
define("DIR_BANNERS",DIR_BANNER."banners/");
define("HTTP_BANNERS",HTTP_BANNER."banners/");
define("BANNER_FORMS", DIR_BANNER."forms/");

include(DIR_SERVER_ROOT."application_settings.php");

define('FILENAME_INDEX','index.php');
define('FILENAME_LEFT_NAVIGATION','left_navigation.php');
define('FILENAME_LOGIN','login.php');
define('FILENAME_LOGOUT','logout.php');
define('FILENAME_LOGIN_PROCESS','login_process.php');
define('FILENAME_EMPLOYER','employer.php');
define('FILENAME_EMPLOYER_FORM','employer_form.php');
define('FILENAME_PERSONAL','personal.php');
define('FILENAME_PERSONAL_FORM','personal_form.php');
define('FILENAME_DELACCOUNT','delaccount.php');
define('FILENAME_DELACCOUNT_FORM','delaccount_form.php');
define('FILENAME_COMPANY','company.php');
define('FILENAME_COMPANY_FORM','company_form.php');

define('FILENAME_LOGIN_FORM','login_form.php');
define('FILENAME_RIGHT_NAVIGATION','right_navigation.php');
define('FILENAME_ERROR_LOGIN','error_login.php');
define('FILENAME_MEMBERSHIP','planning.php');
define('FILENAME_MEMBERSHIP_FORM','planning_form.php');
define('FILENAME_INVOICES','invoices.php');
define('FILENAME_INVOICES_FORM','invoices_form.php');
define('FILENAME_SEARCH_RESUMES','search_resumes.php');
define('FILENAME_PAYMENT','payment.php');
define('FILENAME_PAYMENT_FORM','payment_form.php');
define('FILENAME_CC_BILLING_FORM','cc_billing_form.php');
define('FILENAME_PROCESSING','processing.php');
define('FILENAME_PROCESSING_FORM','processing_form.php');
define('FILENAME_JOBMAIL','jobmail.php');
define('FILENAME_JOBMAIL_FORM','jobmail_form.php');
define('FILENAME_STATISTICS','statistics.php');
define('FILENAME_STATISTICS_FORM','statistics_form.php');
define('FILENAME_CONFIRM_JOB_DELETE_FORM','confirm_job_delete_form.php');
define('FILENAME_CONFIRM_RESUME_DELETE_FORM','confirm_resume_delete_form.php');
define('FILENAME_MYCOMPANY','mycompany.php');
define('FILENAME_MYCOMPANY_FORM','mycompany_form.php');
define('FILENAME_MYINVOICES','myinvoices.php');
define('FILENAME_MYINVOICES_FORM','myinvoices_form.php');
define('FILENAME_ERROR_FORM','error_form.php');
define('FILENAME_MESSAGE_FORM','message_form.php');
define('FILENAME_FORGOT_PASSWORDS','forgot_passwords.php');
define('FILENAME_FORGOT_PASSWORDS_FORM','forgot_passwords_form.php');
define('FILENAME_SUPPORT','support.php');
define('FILENAME_SUPPORT_FORM','support_form.php');
define('FILENAME_USERS','users.php');
define('FILENAME_REGISTER','register.php');

define('FILENAME_ADMIN_BANNERS','admin_banners.php');
define('FILENAME_ADMIN_BANNER_MONTHS','admin_banner_months.php');

$index_page_includes.="include('".DIR_FORMS."quick_search_form.php');";
$index_page_includes.="include('".DIR_FORMS."jobcategory_list_form.php');";
// customization for the design layout
include(DIR_SERVER_ROOT."design_configuration.php");

$bx_table_prefix.='banner2';

$bx_db_table_prefix = "banner2_";
$bx_db_table_banner_users = $bx_db_table_prefix.'users';
$bx_db_table_banner_deleted_banners = $bx_db_table_prefix.'deleted_banners';
$bx_db_table_banner_clicks = $bx_db_table_prefix.'clicks';
$bx_db_table_banner_banners = $bx_db_table_prefix.'banners';
$bx_db_table_banner_types = $bx_db_table_prefix.'zones';
$bx_db_table_banner_stats = $bx_db_table_prefix.'stats';
$bx_db_table_banner_update = $bx_db_table_prefix.'update';
$bx_db_table_planning = $bx_db_table_prefix.'planning';
$bx_db_table_planning_months = $bx_db_table_prefix.'planning_months';
$bx_db_table_banner_invoices = $bx_table_prefix.'_invoices';

/*
cctransactions
invoices
ladmin
*/

include(DIR_FUNCTIONS . 'database.php');
include(DIR_FUNCTIONS . 'general.php');
include(DIR_FUNCTIONS . 'sessions.php');

// make a connection to the database... now
bx_db_connect() or die('Unable to connect to database server!');

//start the session management
if ($employerid) {
  $employerid='';
}
if ($userid) {
  $userid='';
}
session_cache_limiter("none");
bx_session_start();
//parse time log creation
define('STORE_PAGE_PARSE_TIME_LOG', DIR_SERVER_ROOT . 'logs/parse_time_log');
define('STORE_PARSE_DATE_TIME_FORMAT', '%d/%m/%Y %H:%M:%S');
define('PHP_BM_VERSION','2.0');
define('INSTALLATION_DATE','2003-08-27');
if (STORE_PAGE_PARSE_TIME == 'on') {
  $parse_start_time = microtime();
}
//language support
if(CRON_JOB!="yes") {
		if(MULTILANGUAGE_SUPPORT == "on" && CRON_JOB!="yes") {
                if ($HTTP_GET_VARS['language'] && file_exists(DIR_LANGUAGES.$HTTP_GET_VARS['language'].".php")) {
                         $language=urldecode($HTTP_GET_VARS['language']);
                         bx_session_unregister('language');
                         bx_session_register('language');
                         setcookie("phpjob_lng", $language, mktime(0,0,0,date('m')+3,date('d'),date('Y')));
                }
                else {
                         if (!bx_session_is_registered('language') && $HTTP_COOKIE_VARS['phpjob_lng'] && file_exists(DIR_LANGUAGES.$HTTP_COOKIE_VARS['phpjob_lng'].".php")) {
                              $language = strtolower($HTTP_COOKIE_VARS['phpjob_lng']);
                              bx_session_register('language');
                              setcookie("phpjob_lng", $language, mktime(0,0,0,date('m')+3,date('d'),date('Y')));
                         }
                         elseif (!bx_session_is_registered('language')) {
                              $language = DEFAULT_LANGUAGE;
                              bx_session_register('language');
                              setcookie("phpjob_lng", $language, mktime(0,0,0,date('m')+3,date('d'),date('Y')));
                         }
                         elseif($HTTP_SESSION_VARS['language']){
                              $language = $HTTP_SESSION_VARS['language'];
                         }
                }
		}
		else {
                $language = DEFAULT_LANGUAGE;
		}
        if ($HTTP_SESSION_VARS['sessiontime']) {
                if (time()>$HTTP_SESSION_VARS['sessiontime']+(60*SESSION_EXPIRES)) {
                    if ($HTTP_SESSION_VARS['userid']) {
                         $login="jobseeker";
                         bx_session_destroy();
                         header("Location: login.php?log=".$login);
                         bx_exit();
                    }
                    else if ($HTTP_SESSION_VARS['employerid']) {
                         $login="employer";
                         bx_session_destroy();
                         header("Location: login.php?log=".$login);
                         bx_exit();
                    }
                    else {
                        $sessiontime = time();
                        bx_session_register("sessiontime");
                    }
                }
                else {
                    $sessiontime = time();
                    bx_session_register("sessiontime");
                }
        }
        $bx_session = bx_session_id();
}
else {
    $language = DEFAULT_LANGUAGE;
}

if (DEBUG_MODE == "yes") {
    @set_time_limit(2);
}
include(DIR_LANGUAGES.$language.'.php');
$bx_table_lng = substr($language,0,2);
$slng = "_".substr($language,0,2);
if ($DATE_FORMAT) {define('DATE_FORMAT', $DATE_FORMAT);}else{define('DATE_FORMAT', 'mm/dd/YYYY');}
if ($PRICE_FORMAT) {define('PRICE_FORMAT', $PRICE_FORMAT);}else{define('PRICE_FORMAT', '1,234.56');}
if(USE_IP_FILTER=="yes") {
     include(DIR_SERVER_ROOT."ip_banned.php");
}
$employerid = $HTTP_SESSION_VARS['employerid'];
include(DIR_BANNER. 'settings.php');
$url_target = array('_blank','_parent');
$ignore_hosts = array('192.168.10.x', '192.168.10.1');

?>