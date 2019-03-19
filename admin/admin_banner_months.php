<?
include ('admin_design.php');
include ('../application_config_file.php');

if ($HTTP_GET_VARS['del']=='1' and isset($HTTP_GET_VARS['id']))
{
	$deleteSQL = "delete from $bx_db_table_planning_months where m_id='".$HTTP_GET_VARS['id']."'";
	$delete_query = bx_db_query($deleteSQL);
	SQL_CHECK(0,"SQL Error at ".__FILE__.":".(__LINE__-1));
}elseif ($HTTP_POST_VARS['upd']=='1' and isset($HTTP_POST_VARS['m_id']))
{
	$updateSQL = "update $bx_db_table_planning_months set m_number='".$HTTP_POST_VARS['m_number']."', m_active='".(($HTTP_POST_VARS['m_active']=='1')?'1':'0')."' where m_id='".$HTTP_POST_VARS['m_id']."'";
	$update_query = bx_db_query($updateSQL);
	SQL_CHECK(0,"SQL Error at ".__FILE__.":".(__LINE__-1));
}elseif ($HTTP_POST_VARS['new']=='1')
{
	bx_db_insert($bx_db_table_planning_months, "m_number,m_active", "'".$HTTP_POST_VARS['new_number']."','".(($HTTP_POST_VARS['new_active']=='1')?'1':'0')."'");
	SQL_CHECK(0,"SQL Error at ".__FILE__.":".(__LINE__-1));
}

include("header.php");
include(DIR_ADMIN."admin_banner_months_form.php");
include("footer.php");
?>