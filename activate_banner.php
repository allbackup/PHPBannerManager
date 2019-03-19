<?
TO_DELETE_VAR
include ("application_config_file.php");
if($HTTP_SESSION_VARS['pbm_userid'])
{
	$activateSQL = "update $bx_db_table_banner_banners set active='".$HTTP_GET_VARS['value']."'".($HTTP_GET_VARS['value']=='true' ? ", permission='deny'" : "")." where banner_id='".$HTTP_GET_VARS['banner_id']."'";

	$activate_query = bx_db_query($activateSQL);
	SQL_CHECK(0,"SQL Error at ".__FILE__.":".(__LINE__-1));

	$updateCounterSQL = "update $bx_db_table_banner_banners set weight_counter='0' where 1";
	$updateCounter_query = bx_db_query($updateCounterSQL);
	SQL_CHECK(0,"SQL Error at ".__FILE__.":".(__LINE__-1));
	refresh(bx_make_url(HTTP_SERVER."banners.php"."?user_id=".$HTTP_GET_VARS['user_id'], "auth_sess", $bx_session));
}
else
{
	include('header.php');
	include(DIR_FORMS. 'login_form.php');
	include('footer.php');
} //end else
?>