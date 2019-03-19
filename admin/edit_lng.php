<?php
include('admin_design.php');
include('../application_config_file.php');
include('admin_auth.php');
include(DIR_LANGUAGES.$language."/".FILENAME_ADMIN_FORM);
if ($HTTP_GET_VARS['todo'] =="editoptions" || $HTTP_POST_VARS['todo']=="editoptions") {
    $i=1;
    while (${TEXT_DEGREE_OPT.$i}) {
        unset(${TEXT_DEGREE_OPT.$i});
        $i++;
    }
    $i=1;
    while (${TEXT_LANGUAGE_KNOWN_OPT.$i}) {
        unset(${TEXT_LANGUAGE_KNOWN_OPT.$i});
        $i++;
    }
    include(DIR_LANGUAGES.'/'.($HTTP_GET_VARS['folders']?$HTTP_GET_VARS['folders']:$HTTP_POST_VARS['folders']).'.php');
}
$jsfile = "admin_lng.js";
include("header.php");
include(DIR_ADMIN.FILENAME_ADMIN_EDIT_LANGUAGE_FORM);
include("footer.php");
?>