<?
$bannerPadding = 0;
$bannerSpacing = 2;

if($HTTP_GET_VARS['style']=="js") 
{
	include('application_config_file.php');
	$pbm_type = $HTTP_GET_VARS['t'];
	$pbm_position = $HTTP_GET_VARS['p'] == 'h' ? "h" : "v";
	$pbm_nr_banner = $HTTP_GET_VARS['n'];
}else
{
	ini_set("display_errors",0);	//hide session warnings
	include('application_config_file.php');
}

include(DIR_SERVER_ROOT.'application_settings.php');
$SQL = "select *, banners.weight_counter/banners.weight as ratio from $bx_db_table_banner_invoices as invoice, $bx_db_table_banner_banners as banners, $bx_db_table_banner_users as users where ((invoice.compid=users.user_id  and invoice.i_zone='".$pbm_type."' and invoice.paid='Y' and invoice.updated='Y' and (i_start_date!='0000-00-00' and i_start_date<='".date('Y-m-d')."' and DATE_ADD(i_start_date, INTERVAL invoice.i_period MONTH)>'".date('Y-m-d')."')  and (invoice.i_purchased_nr>invoice.i_counted || invoice.i_purchased_nr='-1') and invoice.i_expired='0') or (banners.user_id=users.user_id and users.user_id='1')) and banners.active='true' and  banners.permission='allow' and users.user_id=banners.user_id and banners.type_id='|".$pbm_type."|' group by banner_id order by ratio asc limit 0, ".$pbm_nr_banner;

$sel=mysql_query($SQL);
$nr_banners = mysql_num_rows($sel);
$i = 0;
if ($nr_banners!=0)
{
	if($HTTP_GET_VARS['style']=="js") 
	{
		header("Content-type: text/html");
?>
		document.write('<?
	}
	echo "<table border=\"0\" cellpadding=\"".$bannerPadding."\" cellspacing=\"".$bannerSpacing."\">";
	if ($pbm_position=="h")
		echo "<tr>";
	while($result_img = mysql_fetch_array($sel))
	{
		$result_img['banner_name'] = ereg_replace("\r\n", " ", $result_img['banner_name']);
		if ($pbm_position=="v") 
			echo "<tr><td></td></tr><tr><td>";
		elseif ($pbm_position=="h") 
			echo "<td>";
		else{}
		
		$type_selectSQL = "select * from $bx_db_table_banner_types where type_id='".$pbm_type."'";
		$type_select_query = mysql_query($type_selectSQL);
		$type_select_result = mysql_fetch_array($type_select_query);
		
        if ($result_img['format']=="remote")
        {
            if($result_img['alt']=="image")
            {
        	    ?><center><a href="<?=HTTP_BANNER."adv.php"."?id=".$result_img['banner_id']?>" target="<?=$type_select_result['target']?>"><img src="<?=$result_img['banner_name']?>" border="0" align="middle" alt="<?=$result_img['alt']?>" width="<?=$type_select_result['width']?>" height="<?=$type_select_result['height']?>"><br><?php echo $result_img['banner_text'];?></a></center><?
            }
            elseif($result_img['alt']=="swf")
            {
                ?><center><object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=4,0,0,0" width="<?=$type_select_result['width']?>" height="<?=$type_select_result['height']?>"><param name="movie" value="<?=$result_img['banner_name']?>?link=<?=HTTP_BANNER."adv.php"."?id=".$result_img['banner_id']?>"><param name="quality" value="autohigh"><param name="bgcolor" value="#efefef"><embed quality="autohigh" bgcolor="#f0f0f0" width="<?=$type_select_result['width']?>" height="<?=$type_select_result['height']?>"  src="<?=$result_img['banner_name']?>?link=<?=HTTP_BANNER."adv.php"."?id=".$result_img['banner_id']?>" type="application/x-shockwave-flash" pluginspage="http://www.macromedia.com/shockwave/download/index.cgi?P1_Prod_Version=ShockwaveFlash"></embed></object></center><?
            } //end elseif
        } //end if
    	elseif($result_img['format']=="image")
		{	 
				?><center><a href="<?=HTTP_BANNER."adv.php"."?id=".$result_img['banner_id']?>" target="<?=$type_select_result['target']?>"><img src="<?=HTTP_BANNERS.$result_img['banner_name']?>" border="0" align="middle" alt="<?=$result_img['alt']?>" width="<?=$type_select_result['width']?>" height="<?=$type_select_result['height']?>"><br><?php echo $result_img['banner_text'];?></a></center><?
		}
		elseif ($result_img['format']=="html") 
		{
			$banner_code = $result_img['banner_name'];
		
			if(preg_match_all("|href=[\"\']?([^\"' >]+)|i",stripslashes($result_img['banner_name']), $matches))
			{
				for ($i_link=0; $i_link < count($matches[0]); $i_link++) 
				{
					$banner_code=ereg_replace($matches[1][$i_link],HTTP_BANNER."adv.php"."?id=".$result_img['banner_id']."&ln=".$i_link, $banner_code);
				}
			}
            
            
			if($HTTP_GET_VARS['style']=="js")
				echo "<center>".ereg_replace('href', 'target="'.$type_select_result['target'].'" href',$banner_code)."</center>";
			else 
				echo "<center>".ereg_replace('href', 'target="'.$type_select_result['target'].'" href',stripslashes($banner_code))."</center>";
		}	
		elseif ($result_img['format']=="swf")
		{
			?><center><object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=4,0,0,0" width="<?=$type_select_result['width']?>" height="<?=$type_select_result['height']?>"><param name="movie" value="<?=HTTP_BANNERS.$result_img['banner_name']?>?link=<?=HTTP_BANNER."adv.php"."?id=".$result_img['banner_id']?>"><param name="quality" value="autohigh"><param name="bgcolor" value="#efefef"><embed quality="autohigh" bgcolor="#f0f0f0" width="<?=$type_select_result['width']?>" height="<?=$type_select_result['height']?>"  src="<?=HTTP_BANNERS.$result_img['banner_name']?>?link=<?=HTTP_BANNER."adv.php"."?id=".$result_img['banner_id']?>" type="application/x-shockwave-flash" pluginspage="http://www.macromedia.com/shockwave/download/index.cgi?P1_Prod_Version=ShockwaveFlash"></embed></object></center><?
		}
		else{}
	 
		if ($pbm_position=="v") 
			echo "</td></tr>";
		elseif ($pbm_position=="h") 
			echo "</td>";

		//update weight counter
		$upd = mysql_query("update $bx_db_table_banner_banners set weight_counter=weight_counter+1  where banner_id = '".$result_img['banner_id']."'");
	
		
		//is the specified IP blocked
		$ignore_hosts =split(",",trim(IP_EXCLUDE_LIST));
		if (!in_array ($HTTP_SERVER_VARS['REMOTE_ADDR'], $ignore_hosts))
		{
			$upd = mysql_query("update $bx_db_table_banner_banners set views = views+1 where banner_id = '".$result_img['banner_id']."'");

			$statSQL = "select * from $bx_db_table_banner_stats where banner_id='".$result_img['banner_id']."' and day='".date("Y-m-d")."'";
			$stat_query = mysql_query($statSQL);

			if(mysql_num_rows($stat_query) > 0)
				$upd = mysql_query("update $bx_db_table_banner_stats set views = views+1 where banner_id = '".$result_img['banner_id']."' and day='".date("Y-m-d")."'");
			else
				mysql_query("insert into ".$bx_db_table_banner_stats." (views, clicks, day, banner_id, user_id) "." values ('1', '0', '".date("Y-m-d")."', '".$result_img['banner_id']."', '".$result_img['user_id']."')");
			
			if($result_img['i_type']=='1')
			{
				$updateSQL = "update $bx_db_table_banner_invoices set i_counted=i_counted+1 where opid='".$result_img['opid']."'";
				$update_query = bx_db_query($updateSQL);
				SQL_CHECK(0,"SQL Error at ".__FILE__.":".(__LINE__-1));
			}
		}

		$i++;
		if($i==$pbm_nr_banner)
			break;
	}
	if ($pbm_position=="h")
		echo "</tr>";

	if($HTTP_GET_VARS['style']=="js") 
	{
		?></table>');
<?
	}
	else
		echo "</table>";
}
if($HTTP_GET_VARS['style']=="js")
	mysql_close();
?>