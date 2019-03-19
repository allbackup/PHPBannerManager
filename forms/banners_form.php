<?php
include(DIR_LANGUAGES.$language.'/banners_form.php');
$tbl1 = ($HTTP_SESSION_VARS['pbm_userid'] ? '': '#F2F8FF');
$tbl2 = ($HTTP_SESSION_VARS['pbm_userid'] ?  LIST_BORDER_COLOR : '#9FCBFF');
$tbl3 = ($HTTP_SESSION_VARS['pbm_userid'] ? DISPLAY_LINE_BGCOLOR_SECOND : '#F2F8FF');
$tbl4 = ($HTTP_SESSION_VARS['pbm_userid'] ? TABLE_HEADING_BGCOLOR : '#FF3300');
$tbl5 = ($HTTP_SESSION_VARS['pbm_userid'] ? TABLE_HEADING_BGCOLOR : '#9ccbef');
?>
<table align="center" border="0" cellspacing="5" cellpadding="1" width="100%" bgcolor="<?php echo $tbl1;?>">
<tr>
	<td><table align="center" border="0" cellspacing="0" cellpadding="0" width="100%">
		<tr>
			<td>
<?
if (is_admin())
{
	$selectUsernameSQL = "select * from $bx_db_table_banner_users where user_id='".$user_id."'";
	$selectUsername_query = bx_db_query($selectUsernameSQL);
	SQL_CHECK(0,"SQL Error at ".__FILE__.":".(__LINE__-1));
	$selectUsername_res = bx_db_fetch_array($selectUsername_query);
	echo TEXT_CLIENT.": <b>".$selectUsername_res['name']."</b>";
}
?>
			</td>
		</tr>
		</table>
	</td>
</tr>
<tr>
	<td>
<?
	$job_user_id = $HTTP_SESSION_VARS['pbm_userid'];
	if (is_admin())
	{
		$sql_plus = " and permission='allow'";
		//$job_user_id = $selectUsername_res['compid'];
		$job_user_id = $selectUsername_res['user_id'];
	}
	
	$selectInvoiceSQL = "select *, DATE_ADD(i_start_date, INTERVAL i_period MONTH) as expire_date from ".$bx_db_table_banner_invoices." where compid='".$job_user_id."'";// and paid='Y' and updated='Y'";
	$select_invoice_query = bx_db_query($selectInvoiceSQL);
	SQL_CHECK(0,"SQL Error at ".__FILE__.":".(__LINE__-1));
	//echo bx_db_num_rows($select_invoice_query);
	
	if(bx_db_num_rows($select_invoice_query)=='0' && !is_admin())
	{
?>
<table align="center" cellpadding="8" cellspacing="0" width="100%" bgcolor="<?php echo $tbl3;?>" border="0" style="border:1px solid <?php echo $tbl5;?>;">
<tr>
	<td bgcolor="<?php echo $tbl4;?>" align="center">
		<b><font size="" color="<?php echo HEADING_FONT_COLOR;?>"><?php echo TEXT_NO_PLANNING;?></font></b>
	</td>
</tr>
</table>
	</td>
</tr>
</table>
<?
		include ("footer.php");
		exit;
	}
	if(bx_db_num_rows($select_invoice_query) =='0' || $job_user_id=='1')
	{
		//$selectInvoiceSQL = "select * from ".$bx_db_table_banner_types;
		$selectInvoiceSQL = "select * from ".$bx_db_table_banner_types." as zones, ".$bx_db_table_planning." as plannings where zones.type_id=plannings.p_zone_id group by p_zone_id";
		$select_invoice_query = bx_db_query($selectInvoiceSQL);
		SQL_CHECK(0,"SQL Error at ".__FILE__.":".(__LINE__-1));
		$not_from_jobsite = 1;
	}

	while ($invoice_res = bx_db_fetch_array($select_invoice_query))
	{
		$selectCountSQL = "select *,  DATE_ADD(i_start_date, INTERVAL i_period MONTH) as expire_date from ".$bx_db_table_banner_invoices." where compid='".$job_user_id."' and paid='Y' and updated='Y' and i_zone='".$invoice_res['i_zone']."'  order by opid desc";
		$select_count_query = bx_db_query($selectCountSQL);
		SQL_CHECK(0,"SQL Error at ".__FILE__.":".(__LINE__-1));
		$count_res = bx_db_fetch_array($select_count_query);
		$count_res['zones'] = bx_db_num_rows($select_count_query);
		//echo "<br>".$invoice_res['opid']. " ".$count_res['opid'] . " && ". $count_res['zones'];
		if($invoice_res['opid']!=$count_res['opid'] && $count_res['zones']>1)
			continue;

		if($not_from_jobsite)
		{
			$selectSQL = "select * from $bx_db_table_banner_banners where INSTR(type_id, '|".$invoice_res['type_id']."|') and user_id='".$user_id."' ".$sql_plus."  order by banner_id desc";
			$select_query = bx_db_query($selectSQL);
			SQL_CHECK(0,"SQL Error at ".__FILE__.":".(__LINE__-1));
			$nr_banners = bx_db_num_rows($select_query);
		
			$selectZoneSQL = "select * from $bx_db_table_banner_types where type_id='".$invoice_res['type_id']."'";
			$zone_query = bx_db_query($selectZoneSQL);
			SQL_CHECK(0,"SQL Error at ".__FILE__.":".(__LINE__-1));
			$zone_res = bx_db_fetch_array($zone_query);
			$zone_number = bx_db_num_rows($zone_query);
		}
		elseif(is_admin())
		{
			$selectSQL = "select * from $bx_db_table_banner_banners where INSTR(type_id, '|".$invoice_res['i_zone']."|') and user_id='".$user_id."' ".$sql_plus."  order by banner_id desc";
			$select_query = bx_db_query($selectSQL);
			SQL_CHECK(0,"SQL Error at ".__FILE__.":".(__LINE__-1));
			$nr_banners = bx_db_num_rows($select_query);
		
			$selectZoneSQL = "select * from $bx_db_table_banner_types where type_id='".$invoice_res['i_zone']."'";
			$zone_query = bx_db_query($selectZoneSQL);
			SQL_CHECK(0,"SQL Error at ".__FILE__.":".(__LINE__-1));
			$zone_res = bx_db_fetch_array($zone_query);
			$zone_number = bx_db_num_rows($zone_query);
		}
		else
		{
			$selectSQL = "select * from $bx_db_table_banner_banners where INSTR(type_id, '|".$invoice_res['i_zone']."|') and user_id='".$HTTP_SESSION_VARS['pbm_userid']."' ".$sql_plus."  order by banner_id desc";
			$select_query = bx_db_query($selectSQL);
			SQL_CHECK(0,"SQL Error at ".__FILE__.":".(__LINE__-1));
			$nr_banners = bx_db_num_rows($select_query);
		
			$selectZoneSQL = "select * from $bx_db_table_banner_types where type_id='".$invoice_res['i_zone']."'";
			$zone_query = bx_db_query($selectZoneSQL);
			SQL_CHECK(0,"SQL Error at ".__FILE__.":".(__LINE__-1));
			$zone_res = bx_db_fetch_array($zone_query);
			$zone_number = bx_db_num_rows($zone_query);
		}
		
?>
<table align="center" cellpadding="8" cellspacing="0" width="100%" bgcolor="<?php echo $tbl3;?>" border="0" style="border:1px solid <?php echo $tbl5;?>;">
<tr>
	<td bgcolor="<?php echo $tbl5;?>">
		<font size="2" color="#ffffff"><b><?php echo $zone_res['typename'.$slng]." (".$zone_res['width']."x".$zone_res['height'].")";?> - </b> <?php { echo TEXT_MAX_UPLOAD.$invoice_res['i_max_banners'].", ".TEXT_PURCHASED.":".($invoice_res['i_purchased_nr']!='-1' ? $invoice_res['i_purchased_nr'] : TEXT_UNLIMITED);?></font><?php }?>
	</td>
</tr>
<?php 
	if($invoice_res['paid']!='Y' && !is_admin())
	{//Invoices Waiting for payment
?>
<tr><td>
<table align="center" cellpadding="8" cellspacing="0" width="100%" bgcolor="<?php echo $tbl3;?>" border="0" style="border:1px solid <?php echo $tbl5;?>;">
<tr>
	<td bgcolor="<?php echo $tbl4;?>" align="center">
		<b><font size="" color="<?php echo HEADING_FONT_COLOR;?>"><?php echo TEXT_INVOICES_WAITING_FOR_PAYMENT;?></font></b>
	</td>
</tr>
</table>
	</td>
</tr>
<?
	}
	elseif($invoice_res['validated']!='Y' && !is_admin())
	{//Invoices Waiting for validation
?>
<tr><td>
<table align="center" cellpadding="8" cellspacing="0" width="100%" bgcolor="<?php echo $tbl3;?>" border="0" style="border:1px solid <?php echo $tbl5;?>;">
<tr>
	<td bgcolor="<?php echo $tbl4;?>" align="center">
		<b><font size="" color="<?php echo HEADING_FONT_COLOR;?>"><?php echo TEXT_INVOICES_WAITING_FOR_VALIDATION;?></font></b>
	</td>
</tr>
</table>
	</td>
</tr>
<?
	}
	elseif($invoice_res['updated']!='Y' && !is_admin())
	{//Invoices Waiting for update
?>
<tr><td>
<table align="center" cellpadding="8" cellspacing="0" width="100%" bgcolor="<?php echo $tbl3;?>" border="0" style="border:1px solid <?php echo $tbl5;?>;">
<tr>
	<td bgcolor="<?php echo $tbl4;?>" align="center">
		<b><font size="" color="<?php echo HEADING_FONT_COLOR;?>"><?php echo TEXT_INVOICES_WAITING_FOR_UPDATE;?></font></b>
	</td>
</tr>
</table>
	</td>
</tr>
<?
	}
		elseif(($invoice_res['expire_date'] < date('Y-m-d') && $invoice_res['expire_date']!='') || $invoice_res['i_expired']=='1')
		{
			$expired_planning = 1;
?>
<tr>
	<td bgcolor="#FF3300" align="center">
		<b><font size="" color="#ffffff"><?php echo TEXT_EXPIRED_PLANNING;?></font></b>
	</td>
</tr>
<?
		}
		elseif($invoice_res['expire_date']!='')
		{
?>
<tr>
	<td>
		<?php echo TEXT_EXPIRE_ON;?><?php echo bx_format_date($invoice_res['expire_date'], DATE_FORMAT);?> 
<?php 
			if($invoice_res['i_type']=='1')
				echo TEXT_IMPRESSIONS;
			elseif($invoice_res['i_type']=='2')
				echo TEXT_CLICKS;
			elseif($invoice_res['i_type']=='3')
				echo TEXT_FLAT;
			else{}
	
?>
	</td>
</tr>
<?			
		}
		else{}
		
		if((!$expired_planning && $nr_banners < $invoice_res['i_max_banners'] && $invoice_res['expire_date'] >= date('Y-m-d')) || is_admin())
		{
			if($HTTP_SESSION_VARS['pbm_userid'])
			{
?>
<tr>
	<td>s
<font face="verdana" size="2">&nbsp;<a href="<?php echo bx_make_url('add_banner.php?action=new&user_id='.$user_id.'&zid='.$zone_res['type_id'], "auth_sess", $bx_session);?>" style="text-decoration:none;color:#6633FF;font-weight:bold;font-size:14px;font-family:verdana;background:<?php echo LIST_HEADER_COLOR;?>"><?php echo TEXT_ADD_NEW?> <?php echo $zone_res['typename'.$slng]?> <?php TEXT_BANNER?></a></font>
	</td>
</tr>
<?
			}
			else
			{
?>
<tr>
	<td>
<font face="verdana" size="2">&nbsp;<a href="<?php echo HTTP_SERVER_ADMIN.'add_banner.php?action=new&user_id='.$user_id.'&zid='.$zone_res['type_id'];?>" style="text-decoration:none;color:#6633FF;font-weight:bold;font-size:14px;font-family:verdana"><?php echo TEXT_ADD_NEW?> <?php echo $zone_res['typename'.$slng]?> <?php TEXT_BANNER?></a></font>
	</td>
</tr>
<?
			}
		}

		while ($select_result = bx_db_fetch_array($select_query))
		{echo "<tr><td></td></tr>";
			if ($select_result['permission']=='deny')
			{?>
<tr>
	<td align="center">
		<font size="2" color="#FF0000"><b><?php echo TEXT_BANNER_NOT_VALIDATED?></b></font>
	</td>
</tr>

		<?}
		elseif($select_result['active']=='false')
			{?>
<tr>
	<td align="center">
		<font size="2" color="#FF0000"><b><?php echo TEXT_BANNER_NOT_ACTIVE?></b></font>
	</td>
</tr>

		<?}
		
		if ($select_result['format'] == "image")
		{
?>
<tr>
	<td align="center">
	
		<a href="<?php echo $select_result['url'];?>"><img src="<?=HTTP_BANNERS.$select_result['banner_name']?>" border="0" alt="<?=$select_result['alt']?>"></a>
	</td>
</tr>
<?		
		}
		elseif ($select_result['format'] == "html")
		{
?>

<tr>
	<td align="center">
		<?=stripslashes($select_result['banner_name'])?>
	</td>
</tr>
<?		
		}
		elseif ($select_result['format'] == "swf")
		{
			$banner_types = explode("|", $select_result['type_id']);
			array_shift($banner_types);
			array_pop($banner_types);
			$type_selectSQL = "select * from $bx_db_table_banner_types where type_id='".$banner_types[0]."'";
			$type_select_query = bx_db_query($type_selectSQL);
			SQL_CHECK(0,"SQL Error at ".__FILE__.":".(__LINE__-1));
			$type_select_result = bx_db_fetch_array($type_select_query);
?>
<tr>
	<td align="center">
		<center><object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=4,0,0,0" width="<?=$type_select_result['width']?>" height="<?=$type_select_result['height']?>">
		<param name="movie" value="<?=HTTP_BANNERS.$select_result['banner_name']?>?link=<?=$select_result['url']?>">
		<param name="quality" value="autohigh">
		<param name="bgcolor" value="#efefef">
		<embed quality="autohigh" bgcolor="#f0f0f0" src="<?=HTTP_BANNERS.$select_result['banner_name']?>?link=<?=$select_result['url']?>"  width="<?=$type_select_result['width']?>" height="<?=$type_select_result['height']?>" type="application/x-shockwave-flash" pluginspage="http://www.macromedia.com/shockwave/download/index.cgi?P1_Prod_Version=ShockwaveFlash">
		</embed>
		</object></center>
	</td>
</tr>
<?
		}
        elseif ($select_result['format']=="remote")
        {
?>
<tr>
	<td align="center">
<?
            if($select_result['alt']=="image")
            {
        	    ?><center><a href="<?=$select_result['url']?>" target="<?=$type_select_result['target']?>"><img src="<?=$select_result['banner_name']?>" border="0" align="middle" alt="<?=$result_img['alt']?>" width="<?=$zone_res['width']?>" height="<?=$zone_res['height']?>"><br><?php echo $select_result['banner_text'];?></a></center><?
            }
            elseif($select_result['alt']=="swf")
            {
                ?><center><object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=4,0,0,0" width="<?=$zone_res['width']?>" height="<?=$zone_res['height']?>"><param name="movie" value="<?=$select_result['banner_name']?>?link=<?=$select_result['url']?>"><param name="quality" value="autohigh"><param name="bgcolor" value="#efefef"><embed quality="autohigh" bgcolor="#f0f0f0" width="<?=$zone_res['width']?>" height="<?=$zone_res['height']?>"  src="<?=HTTP_BANNERS.$select_result['banner_name']?>?link=<?=$select_result['url']?>" type="application/x-shockwave-flash" pluginspage="http://www.macromedia.com/shockwave/download/index.cgi?P1_Prod_Version=ShockwaveFlash"></embed></object></center><?
            } //end elseif
        } //end if
?>
    </td>
</tr>
<tr>
	<td>
		<table align="center" border="0" cellspacing="0" cellpadding="0" width="100%" bgcolor="#ffffff">
		<tr>
			<td width="5%">
				<font size="2" face="helvetica, verdana, arial" color="#CCCCCC">#<?=$select_result['banner_id']?></font>&nbsp;&nbsp;&nbsp;
			</td>
			<td>
			<?
			if($select_result['format']!='html')
			{
				if ($select_result['url'] != "" && $select_result['url'] != "http://")
				{?>
				
				<font size="2" face="helvetica, verdana, arial"><a href="<?=$select_result['url']?>"><?=$select_result['url']?></a></font>	
				<?}else
				{
					preg_match_all("/url=(.*?)&/i", stripslashes($select_result['banner_name']), $regs);
					
					for ($i=0; $i < count($regs[0]); $i++) 
					{
						$url = $regs[1][$i];
					}

					if(strstr($url, "http://"))
						$url = $url;
					else
						$url = "http://" . $url;
					
					echo "<a href=\"$url\">".$url."</a>";

				}
			}
			else
				echo "&nbsp;";?>
				
			</td>
			<td width="13%" align="center">
<?	
	if(!$expired_planning)
	{
		if ($select_result['active'] == "true")
			echo "<a href=\"".bx_make_url('activate_banner.php?banner_id='.$select_result['banner_id'].'&value=false&user_id='.$user_id, "auth_sess", $bx_session)."\">".TEXT_DE_ACTIVATE."</a>";
		else
			echo "<a href=\"".bx_make_url('activate_banner.php?banner_id='.$select_result['banner_id'].'&value=true&user_id='.$user_id, "auth_sess", $bx_session)."\">".TEXT_ACTIVATE."</a>";
?>
			</td>
			<td width="14%" align="center">
				<a href="<?php echo bx_make_url('add_banner.php?banner_id='.$select_result['banner_id'].'&user_id='.$user_id.'&format='.$select_result['format'].'&zid='.substr($select_result['type_id'],1,-1), "auth_sess", $bx_session);?>"><?php echo TEXT_MODIFY_BANNER?></a>
			</td>
			<td width="10%" align="center">
				<a href="<?php echo bx_make_url('move_to_trash.php?banner_id='.$select_result['banner_id'].'&user_id='.$user_id, "auth_sess", $bx_session);?>" onClick="return confirm('Are you sure you want to delete this banner?');"><?php echo TEXT_DELETE?></a>
	<?php }?>
			</td>
		</tr>
		</table>
	</td>
</tr>
<tr>
	<td colspan="2" bgcolor="#9FCBFF" height="1"></td>
</tr>
<?
		}?>
		</td>
		</tr>
	</table><br><br>
	<?}
	if (bx_db_num_rows($select_query) =='0')
	{
?>
<tr>
	<td colspan="2" bgcolor="#9FCBFF" height="1"></td>
</tr>
<?
	}
?>

	</td>
</tr>
</table>