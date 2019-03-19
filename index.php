<?
include("application_config_file.php");
include (DIR_SERVER_ROOT."header.php");
if ($HTTP_GET_VARS['logout']=='yes')
{
	session_destroy();
	refresh(bx_make_url(HTTP_SERVER.'index.php', "auth_sess", $bx_session));
	exit;
}


if ($HTTP_SESSION_VARS['pbm_userid'])
{
	refresh(bx_make_url(HTTP_SERVER."planning.php", "auth_sess", $bx_session));
	exit;
}
else
{
	include (BANNER_FORMS."login_form.php");
}

include (DIR_SERVER_ROOT."footer.php");
?>