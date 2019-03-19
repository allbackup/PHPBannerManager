<?
include('admin_design.php');
include('../application_config_file.php');
include('admin_auth.php');
include(DIR_LANGUAGES.$language."/".FILENAME_ADMIN_FORM);
include("header.php");

include (BANNER_FORMS."client_stats_form.php");
include (DIR_SERVER_ADMIN."footer.php");
?>