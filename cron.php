<?
include('application_config_file.php');
include(DIR_LANGUAGES.$language."/".'cron.php');

if($HTTP_GET_VARS['test_mode']=='1')
	define('CRON_TEST','yes');
else
	define('CRON_TEST','no');


$zone_email = $adv_email = 0;
//send advertisers emails to clients
for ($startType=1;$startType<4;$startType++)
{
	$last_report = "< '".date('Y-m-d')."'";
	if($startType == '1')
	{
		$pre_report = "< '".date('Y-m-d', mktime(0,0,0,date('m'), date('d'),date('Y')))."'";
		$previous_report = "> '".date('Y-m-d', mktime(0,0,0,date('m'), date('d')-2,date('Y')))."'";
	}
	elseif($startType=='2')
	{
		$pre_report = "< '".date('Y-m-d', mktime(0,0,0,date('m'), date('d')-6,date('Y')))."'";
		$previous_report = "> '".date('Y-m-d', mktime(0,0,0,date('m'), date('d')-8,date('Y')))."'";
	}
	elseif($startType=='3')
	{
		
		$pre_report = "< '".date('Y-m-d', mktime(0,0,0,date('m'), date('d')-29,date('Y')))."'";
		$previous_report = "> '".date('Y-m-d', mktime(0,0,0,date('m'), date('d')-31,date('Y')))."'";
	}
	else{}

	//echo"<br><br>".
	$selectSQL = "select username,users.user_id, users.user_type from $bx_db_table_banner_users as users, $bx_db_table_banner_stats as stats where users.report='".$startType."' and last_report ".$pre_report." and users.user_id=stats.user_id group by stats.user_id order by day asc";
	$select_query = bx_db_query($selectSQL);
	SQL_CHECK(0,"SQL Error at ".__FILE__.":".(__LINE__-1));
	//echo bx_db_num_rows($select_query);

	if(bx_db_num_rows($select_query) > 0)
	{
		while ($res = bx_db_fetch_array($select_query))
		{
			$toWrite = chr(13).TEXT_DAY.','.TEXT_ADVIEWS.','.TEXT_ADCLICKS.','.TEXT_CTR.chr(13).'----------------------------------'.chr(13);
			$tAdsViews = $tAdsClicks = 0;

			//echo "<br>".
			$selectSQL1 = "select *, sum(views) as totalViews, sum(clicks) as totalClicks from $bx_db_table_banner_stats where user_id='".$res['user_id']."' and day ".$last_report." and day ".$previous_report." group by day";
			$select_query1 = bx_db_query($selectSQL1);
			SQL_CHECK(0,"SQL Error at ".__FILE__.":".(__LINE__-1));
			//echo bx_db_num_rows($select_query1);
			if(bx_db_num_rows($select_query1) == 0)
				continue;
			while ($res1 = bx_db_fetch_array($select_query1))
			{
				$toWrite .= ''.$res1['day'].': '.$res1['totalViews'].', '.$res1['totalClicks'].', '.round($res1['totalClicks']*100/$res1['totalViews']).'%'.chr(13);	
				$tAdsViews += $res1['totalViews'];
				$tAdsClicks += $res1['totalClicks'];
			}

			$toWrite .= '----------------------------------'.chr(13).TEXT_TOTAL.$tAdsViews.', '.$tAdsClicks.', '.@round($tAdsClicks*100/$tAdsViews).'%'.chr(13);
		
			if($startType=='1')
				$mail_subject = TEXT_DAILY;
			if($startType=='2')
				$mail_subject = TEXT_WEEKLY;
			if($startType=='3')
				$mail_subject = TEXT_MONTHLY;
			else{}
			
			if($res['user_type']=='admin')
				$res['username'] = SITE_MAIL;
			$mail_message = TEXT_DEAR." ".$res['username'].",".chr(13).TEXT_STAT_HERE.chr(13).($toWrite).chr(13).chr(13);
			$mail_subject .= TEXT_STAT_FROM.' '.SITE_NAME;
			$adv_email++;
			if(CRON_TEST=='no')
			{
				bx_mail(SITE_NAME, SITE_MAIL, $res['username'], stripslashes($mail_subject), stripslashes($mail_message), $html_mail);
				$updateSQL = "update $bx_db_table_banner_users set last_report='".date("Y-m-d")."' where user_id='".$res['user_id']."'";
				$update_query = bx_db_query($updateSQL);
				SQL_CHECK(0,"SQL Error at ".__FILE__.":".(__LINE__-1));
			}
			else
				echo nl2br($mail_message);
		}	
	}
}


//send zone emails
for ($startType=1;$startType<4;$startType++)
{
	$last_report = "< '".date('Y-m-d')."'";
	if($startType == '1')
	{
		$pre_report = "< '".date('Y-m-d', mktime(0,0,0,date('m'), date('d'),date('Y')))."'";
		$previous_report = "> '".date('Y-m-d', mktime(0,0,0,date('m'), date('d')-2,date('Y')))."'";
	}
	elseif($startType=='2')
	{
		$pre_report = "< '".date('Y-m-d', mktime(0,0,0,date('m'), date('d')-6,date('Y')))."'";
		$previous_report = "> '".date('Y-m-d', mktime(0,0,0,date('m'), date('d')-8,date('Y')))."'";
	}
	elseif($startType=='3')
	{
		
		$pre_report = "< '".date('Y-m-d', mktime(0,0,0,date('m'), date('d')-29,date('Y')))."'";
		$previous_report = "> '".date('Y-m-d', mktime(0,0,0,date('m'), date('d')-31,date('Y')))."'";
	}
	else{}

	//echo "<br><br>".
	$selectSQL = "select * from $bx_db_table_banner_types as zones where zone_report_type='".$startType."' and last_report ".$pre_report." order by type_id asc";
	$select_query = bx_db_query($selectSQL);
	SQL_CHECK(0,"SQL Error at ".__FILE__.":".(__LINE__-1));
	//echo bx_db_num_rows($select_query);

	if(bx_db_num_rows($select_query) > 0)
	{
		while ($res = bx_db_fetch_array($select_query))
		{
			chr(13).TEXT_DAY.','.TEXT_ADVIEWS.','.TEXT_ADCLICKS.','.TEXT_CTR.chr(13).'----------------------------------'.chr(13);
			$tAdsViews = $tAdsClicks = 0;
			
			$selectSQL1 = "select *, sum(stats.views) as totalViews, sum(stats.clicks) as totalClicks from $bx_db_table_banner_stats as stats, $bx_db_table_banner_banners as banners where banners.type_id='|".$res['type_id']."|' and banners.banner_id=stats.banner_id and day ".$last_report." and day ".$previous_report." group by day";
			$select_query1 = bx_db_query($selectSQL1);
			SQL_CHECK(0,"SQL Error at ".__FILE__.":".(__LINE__-1));
//			echo bx_db_num_rows($select_query1);
	
			if(bx_db_num_rows($select_query1) == 0)
				continue;
			while ($res1 = bx_db_fetch_array($select_query1))
			{
				$toWrite .= ''.$res1['day'].': '.$res1['totalViews'].', '.$res1['totalClicks'].', '.round($res1['totalClicks']*100/$res1['totalViews']).'%'.chr(13);	
				$tAdsViews += $res1['totalViews'];
				$tAdsClicks += $res1['totalClicks'];
			}

			$toWrite .= '----------------------------------'.chr(13).'Total: '.$tAdsViews.', '.$tAdsClicks.', '.@round($tAdsClicks*100/$tAdsViews).'%'.chr(13);
		
			if($startType=='1')
				$mail_subject = TEXT_DAILY;
			if($startType=='2')
				$mail_subject = TEXT_WEEKLY;
			if($startType=='3')
				$mail_subject = TEXT_MONTHLY;
			else{}
			
			if($res['user_type']=='admin')
				$res['username'] = SITE_MAIL;
			$mail_message = "Dear ".$res['zone_email'].",".chr(13).TEXT_STAT_HERE2." \""
			.$res['typename'.$slng]."\" ".TEXT_ZONE_ADV.chr(13).($toWrite).chr(13).chr(13);
			$mail_subject .= TEXT_STAT_FROM.' '.SITE_NAME;

			$zone_email++;
			if(CRON_TEST=='no')
			{
				bx_mail(SITE_NAME, SITE_MAIL, $res['zone_email'], stripslashes($mail_subject), stripslashes($mail_message), $html_mail);
				$updateSQL = "update $bx_db_table_banner_types set last_report='".date("Y-m-d")."' where type_id='".$res['type_id']."'";
				$update_query = bx_db_query($updateSQL);
				SQL_CHECK(0,"SQL Error at ".__FILE__.":".(__LINE__-1));
			}
			else
				echo nl2br($mail_message);
		}	
	}
}

echo '<center><br><br><b>Client Advertising Emails ('.$adv_email.') Have Been Sent!</b></center><br><br>';
echo '<center><br><b>Zone Advertising Emails ('.$zone_email.') Have Been Sent!</b></center><br><br>';
exit;
?>