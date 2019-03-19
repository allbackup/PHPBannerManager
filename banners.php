<?
include ("application_config_file.php");
if ($HTTP_SESSION_VARS['pbm_userid']) 
{
	include (DIR_SERVER_ROOT."header.php");

	$selectSQL = "SELECT * FROM $bx_db_table_banner_banners WHERE user_id='".$HTTP_SESSION_VARS['pbm_userid']."' and permission='allow'";
	$select_query = bx_db_query($selectSQL);
	SQL_CHECK(0,"SQL Error at ".__FILE__.":".(__LINE__-1));

	while ($select_result = bx_db_fetch_array($select_query))
	{
		$select_result['url'];
	}

	include (BANNER_FORMS."banners_form.php");
	include(DIR_SERVER_ROOT."footer.php");
}
else
{
	include('header.php');
	include(DIR_FORMS. 'login_form.php');
	include('footer.php');
} //end else
?>
