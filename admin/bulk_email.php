<?php
include('admin_design.php');
include('../application_config_file.php');
include('admin_auth.php');
@set_time_limit(0);
include(DIR_LANGUAGES.$language."/".FILENAME_ADMIN_FORM);
$jsfile="bulk_email.js";
if ($HTTP_POST_VARS['todo'] != "test_mail" && $HTTP_POST_VARS['todo'] != "test_mail_html" && $HTTP_GET_VARS['todo']!="preview" && $HTTP_POST_VARS['todo']!="preview") {
    include("header.php");
}    
include(DIR_ADMIN.FILENAME_ADMIN_BULK_EMAIL_FORM);
if ($HTTP_POST_VARS['todo'] != "test_mail" && $HTTP_POST_VARS['todo'] != "test_mail_html" && $HTTP_GET_VARS['todo']!="preview" && $HTTP_POST_VARS['todo']!="preview") {
    include("footer.php");
}    
else {
    bx_exit();
}
?>