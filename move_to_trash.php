<?
include ("application_config_file.php");

if($HTTP_SESSION_VARS['pbm_userid'])
{
	if ($HTTP_GET_VARS['banner_id'] == 0)
		refresh($HTTP_BANNER_VARS['HTTP_REFERER']);

	$selectSQL = "insert into $bx_db_table_banner_deleted_banners select * from $bx_db_table_banner_banners where banner_id='".$HTTP_GET_VARS['banner_id']."'";
	$select_query = bx_db_query($selectSQL);
	SQL_CHECK(0,"SQL Error at ".__FILE__.":".(__LINE__-1));

	$deleteSQL = "delete from $bx_db_table_banner_banners where banner_id='".$HTTP_GET_VARS['banner_id']."'";

	$delete_query = bx_db_query($deleteSQL);
	SQL_CHECK(0,"SQL Error at ".__FILE__.":".(__LINE__-1));

	$updateCounterSQL = "update $bx_db_table_banner_banners set weight_counter='0' where 1";
	$updateCounter_query = bx_db_query($updateCounterSQL);
	SQL_CHECK(0,"SQL Error at ".__FILE__.":".(__LINE__-1));

	refresh(bx_make_url(HTTP_SERVER."banners.php?user_id=".$HTTP_SESSION_VARS['pbm_userid'], "auth_sess", $bx_session));
}
else
{
	include(DIR_SERVER_ROOT. 'header.php');
	include(DIR_FORMS. 'login_form.php');
	include(DIR_SERVER_ROOT. 'footer.php');
} //end else
?>