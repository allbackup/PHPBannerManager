<?php
define('DB_CONNECT','no');
include("application_config_file.php");
if ($HTTP_GET_VARS['type']=="ns") {
    echo fread(fopen(DIR_LANGUAGES.$language."/html/jobn.css","r"),filesize(DIR_LANGUAGES.$language."/html/jobn.css")); 
}
else {
    echo fread(fopen(DIR_LANGUAGES.$language."/html/job.css","r"),filesize(DIR_LANGUAGES.$language."/html/job.css")); 
}
bx_exit();
?>