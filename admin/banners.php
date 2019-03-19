<?
include('admin_design.php');
include('../application_config_file.php');
include('admin_auth.php');
include(DIR_LANGUAGES.$language."/".FILENAME_ADMIN_FORM);
include("header.php");

$selectSQL = "SELECT * FROM $bx_db_table_banner_banners WHERE user_id='".$HTTP_POST_VARS['user_id']."' and permission='allow'";
$select_query = bx_db_query($selectSQL);
SQL_CHECK(0,"SQL Error at ".__FILE__.":".(__LINE__-1));

while ($select_result = bx_db_fetch_array($select_query))
{
	$select_result['url'];
}

include (BANNER_FORMS."banners_form.php");
include(DIR_SERVER_ADMIN."footer.php"); 
?>
