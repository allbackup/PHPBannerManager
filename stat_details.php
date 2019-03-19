<?
include ("application_config_file.php");
if(($HTTP_SESSION_VARS['pbm_userid'] || is_admin) and $HTTP_GET_VARS['banner_id'])
{
	include (BANNER_FORMS."stat_details_form.php");
}
else
{
	include(DIR_SERVER_ROOT."header.php");
    include(DIR_FORMS.FILENAME_LOGIN_FORM);
    include(DIR_SERVER_ROOT."footer.php");
} //end else
?>