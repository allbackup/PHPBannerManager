<?
include ("application_config_file.php");

if (trim($HTTP_POST_VARS['username'])!='' && trim($HTTP_POST_VARS['password'])!='')
{
	$SQL= "select * from $bx_db_table_banner_users where username='".$HTTP_POST_VARS['username']."' and password='".$HTTP_POST_VARS['password']."' and user_type!='admin'";
	$user_authentification_query = bx_db_query($SQL);
	SQL_CHECK(0,"SQL Error at ".__FILE__.":".(__LINE__-1));
	$user_authentification_result = bx_db_fetch_array($user_authentification_query);

	if (bx_db_num_rows($user_authentification_query) != 0)
	{
		$pbm_userid = $user_authentification_result['user_id'];
		$pbm_username = $user_authentification_result['user_name'];
		$employerid = $user_authentification_result['user_id'];
		bx_session_register("pbm_userid");
		bx_session_register("pbm_username");
		bx_session_register("employerid");
		$sessiontime = time();
		bx_session_register("sessiontime");

		$logged_in = true;
		bx_session_register("logged_in");
		setcookie("pbm_loginuser", $HTTP_POST_VARS['username'], mktime(0,0,0,0,0,2010), '/');
		refresh("planning.php");
		exit;
	}
	else
	{
		refresh(bx_make_url(HTTP_SERVER."index.php?error=1", "auth_sess", $bx_session));exit;
	}
}
else
{
	refresh(bx_make_url(HTTP_SERVER."index.php?error=1", "auth_sess", $bx_session));exit;
}

?>