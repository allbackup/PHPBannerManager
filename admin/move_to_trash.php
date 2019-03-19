<?
include ("../application_config_file.php");

$selectSQL = "insert into $bx_db_table_banner_deleted_banners select * from $bx_db_table_banner_banners where banner_id='".$HTTP_GET_VARS['banner_id']."'";
$select_query = bx_db_query($selectSQL);
SQL_CHECK(0,"SQL Error at ".__FILE__.":".(__LINE__-1));

$deleteSQL = "delete from $bx_db_table_banner_banners where banner_id='".$HTTP_GET_VARS['banner_id']."'";

$delete_query = bx_db_query($deleteSQL);
SQL_CHECK(0,"SQL Error at ".__FILE__.":".(__LINE__-1));

$updateCounterSQL = "update $bx_db_table_banner_banners set weight_counter='0' where 1";
$updateCounter_query = bx_db_query($updateCounterSQL);
SQL_CHECK(0,"SQL Error at ".__FILE__.":".(__LINE__-1));

refresh(HTTP_SERVER_ADMIN."banners.php?user_id=".$HTTP_GET_VARS['user_id']);
?>