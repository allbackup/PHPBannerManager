<?

include('application_config_file.php');
include('application_settings.php');

if ($HTTP_GET_VARS['id']) 
{
	$SQL = "select banner_id, format, url, banner_name from $bx_db_table_banner_banners where banner_id = '".$HTTP_GET_VARS['id']."'";
    $sel = bx_db_query($SQL);
	SQL_CHECK(0,"SQL Error at ".__FILE__.":".(__LINE__-1));
	$result = bx_db_fetch_array($sel);

    if(bx_db_num_rows($sel)!=0)
    {
		$ignore_hosts = split(",",trim(IP_EXCLUDE_LIST));
		if(!in_array ($HTTP_SERVER_VARS['REMOTE_ADDR'], $ignore_hosts))
		{
			$SQL = "update $bx_db_table_banner_banners set clicks = clicks+1 where banner_id = '".$result['banner_id']."'";
			$upd_banners_click = bx_db_query($SQL);

			$upd = mysql_query("update $bx_db_table_banner_stats set clicks = clicks+1 where banner_id = '".$result['banner_id']."' and day='".date("Y-m-d")."'");
			
			if($pl_res['i_type']=='2')
			{
				$selectPlanningSQL = "select * from $bx_db_table_banner_users users, $bx_db_table_banner_banners as banners, $bx_db_table_banner_invoices as invoices where banners.banner_id='".$HTTP_GET_VARS['id']."' and users.user_id=banners.user_id and users.compid=invoices.i_job_user_id";
				$pl_query = bx_db_query($selectPlanningSQL);
				SQL_CHECK(0,"SQL Error at ".__FILE__.":".(__LINE__-1));
				$pl_res = bx_db_fetch_array($pl_query);
			
				$updateSQL = "update $bx_db_table_banner_invoices set i_counted=i_counted+1 where i_id='".$pl_res['i_id']."'";
				$update_query = bx_db_query($updateSQL);
				SQL_CHECK(0,"SQL Error at ".__FILE__.":".(__LINE__-1));
			}
		}

		$url = $result['url'];

		if($result['format'] == 'html')
		{
			if(preg_match_all("|href=[\"\']?([^\"' >]+)|i",stripslashes($result['banner_name']), $matches))
			{
				for ($i_link=0; $i_link < count($matches[0]); $i_link++) 
				{
					$url = (strstr($matches[1][$i_link], "http://") ? "" : "http://").$matches[1][$i_link];
					if($i_link == $HTTP_GET_VARS['ln'])
						break;
				}
			}
		}
        
		refresh($url);
		exit;
	}
}
?>