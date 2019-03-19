<?
include ("application_config_file.php");
if($HTTP_SESSION_VARS['pbm_userid'])
{
	include (DIR_SERVER_ROOT."header.php");
	include (BANNER_FORMS."client_stats_form.php");
	include (DIR_SERVER_ROOT."footer.php");
}
else
{
	include('header.php');
	include(DIR_FORMS. 'login_form.php');
	include('footer.php');
} //end else
?>