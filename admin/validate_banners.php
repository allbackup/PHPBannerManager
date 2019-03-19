<?
include('admin_design.php');
include('../application_config_file.php');
include('admin_auth.php');
include(DIR_LANGUAGES.$language."/".FILENAME_ADMIN_FORM);
include("header.php");
if ($HTTP_GET_VARS['validate_all'] =="yes")
{
	$updateSQL = "update $bx_db_table_banner_banners set permission='allow', active='true' where user_id='".$HTTP_GET_VARS['user_id']."'";
	$update_query = bx_db_query($updateSQL);
	SQL_CHECK(0,"SQL Error at ".__FILE__.":".(__LINE__-1));
	
	$updateCounterSQL = "update $bx_db_table_banner_banners set weight_counter='0' where 1";
	$updateCounter_query = bx_db_query($updateCounterSQL);
	SQL_CHECK(0,"SQL Error at ".__FILE__.":".(__LINE__-1));

	refresh($HTTP_BANNER_VARS['HTTP_REFERER']."?user_id=".$HTTP_GET_VARS['user_id']);
	exit();

}
elseif ($HTTP_GET_VARS['validate'] == "yes")
{
	$updateSQL = "update $bx_db_table_banner_banners set permission='allow', active='true' where banner_id='".$HTTP_GET_VARS['banner_id']."'";
	$update_query = bx_db_query($updateSQL);
	SQL_CHECK(0,"SQL Error at ".__FILE__.":".(__LINE__-1));
	
	$updateCounterSQL = "update $bx_db_table_banner_banners set weight_counter='0' where 1";
	$updateCounter_query = bx_db_query($updateCounterSQL);
	SQL_CHECK(0,"SQL Error at ".__FILE__.":".(__LINE__-1));

	refresh($HTTP_BANNER_VARS['HTTP_REFERER']."?user_id=".$HTTP_GET_VARS['user_id']);
	exit();
}
else{}

include (DIR_SERVER_ADMIN."validate_banners_form.php");

include (DIR_SERVER_ADMIN."footer.php");
?>