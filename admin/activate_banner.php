<?
include ("../application_config_file.php");

$activateSQL = "update $bx_db_table_banner_banners set active='".$HTTP_GET_VARS['value']."'".($restricted_user && $HTTP_GET_VARS['value']=='true' ? ", permission='deny'" : "")." where banner_id='".$HTTP_GET_VARS['banner_id']."'";

$activate_query = bx_db_query($activateSQL);
SQL_CHECK(0,"SQL Error at ".__FILE__.":".(__LINE__-1));

$updateCounterSQL = "update $bx_db_table_banner_banners set weight_counter='0' where 1";
$updateCounter_query = bx_db_query($updateCounterSQL);
SQL_CHECK(0,"SQL Error at ".__FILE__.":".(__LINE__-1));

refresh(HTTP_SERVER_ADMIN."banners.php"."?user_id=".$HTTP_GET_VARS['user_id']);
?>