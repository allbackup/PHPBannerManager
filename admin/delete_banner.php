<?
include ("../application_config_file.php");


if ($HTTP_GET_VARS['delete'] == "all")
{
	if($HTTP_GET_VARS['user_id'] != '0')
		 $cond = " where user_id='".$HTTP_GET_VARS['user_id']."'";
	$bannerSQL = "select banner_name, banner_id from $bx_db_table_banner_deleted_banners ".$cond;

	$banner_query = bx_db_query($bannerSQL);
	SQL_CHECK(0,"SQL Error at ".__FILE__.":".(__LINE__-1));
	while($banner_result = bx_db_fetch_array($banner_query))
	{
		$deleteSQL = "delete from $bx_db_table_banner_deleted_banners where banner_id='".$banner_result['banner_id']."'";

		$delete_query = bx_db_query($deleteSQL);
		SQL_CHECK(0,"SQL Error at ".__FILE__.":".(__LINE__-1));

		if (file_exists(DIR_BANNERS.$banner_result['banner_name']) && $banner_result['banner_name'] != "")
		{
			unlink(DIR_BANNERS.$banner_result['banner_name']);
		}

	}
	
	$updateCounterSQL = "update $bx_db_table_banner_banners set weight_counter='0' where 1";
	$updateCounter_query = bx_db_query($updateCounterSQL);
	SQL_CHECK(0,"SQL Error at ".__FILE__.":".(__LINE__-1));

	refresh($HTTP_SERVER_VARS['HTTP_REFERER']."?".$QUERY_STRING);
	exit;
}
else
{
	if ($HTTP_GET_VARS['banner_id'] == 0)
		refresh($HTTP_BANNER_VARS['HTTP_REFERER']);

	if ($HTTP_GET_VARS['admin'] == "yes" || $HTTP_GET_VARS['val_del'])
		$table = $bx_db_table_banner_banners;
	else
		$table = $bx_db_table_banner_deleted_banners;


	$bannerSQL = "select banner_name, banner_id from $table where banner_id='".$HTTP_GET_VARS['banner_id']."'";
	$banner_query = bx_db_query($bannerSQL);
	SQL_CHECK(0,"SQL Error at ".__FILE__.":".(__LINE__-1));
	$banner_result = bx_db_fetch_array($banner_query);

	if (file_exists(DIR_BANNERS.$banner_result['banner_name']) && $banner_result['banner_name'] != "")
	{
		unlink(DIR_BANNERS.$banner_result['banner_name']);
	}

	$deleteSQL = "delete from $table where banner_id='".$HTTP_GET_VARS['banner_id']."'";

	$delete_query = bx_db_query($deleteSQL);
	SQL_CHECK(0,"SQL Error at ".__FILE__.":".(__LINE__-1));

	$updateCounterSQL = "update $bx_db_table_banner_banners set weight_counter='0' where 1";
	$updateCounter_query = bx_db_query($updateCounterSQL);
	SQL_CHECK(0,"SQL Error at ".__FILE__.":".(__LINE__-1));
    
//echo (HTTP_SERVER_ADMIN.ereg_replace('\?(.*)','',basename($HTTP_SERVER_VARS['HTTP_REFERER']))."?user_id=".$HTTP_GET_VARS['user_id']);exit;
	refresh(HTTP_SERVER_ADMIN.ereg_replace('\?(.*)','',basename($HTTP_SERVER_VARS['HTTP_REFERER']))."?user_id=".$HTTP_GET_VARS['user_id']);
	exit;
}
?>