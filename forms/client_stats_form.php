<?php
include(DIR_LANGUAGES.$language.'/client_stats_form.php');
$tbl1 = ($HTTP_SESSION_VARS['pbm_userid'] ? '': '#F2F8FF');
$tbl2 = ($HTTP_SESSION_VARS['pbm_userid'] ?  LIST_BORDER_COLOR : '#9FCBFF');
$tbl3 = ($HTTP_SESSION_VARS['pbm_userid'] ? DISPLAY_LINE_BGCOLOR_SECOND : '#F2F8FF');
$tbl4 = ($HTTP_SESSION_VARS['pbm_userid'] ? TABLE_HEADING_BGCOLOR : '#FF3300');
$tbl5 = ($HTTP_SESSION_VARS['pbm_userid'] ? TABLE_HEADING_BGCOLOR : '#9ccbef');
$tbl6 = ($HTTP_SESSION_VARS['pbm_userid'] ? DISPLAY_LINE_BGCOLOR_FIRST : '#9ccbef');

?>
<table align="center" border="0" cellspacing="5" cellpadding="1" width="100%" bgcolor="<?php echo $tbl1;?>">
<tr>
	<td>
		<table align="center" border="0" cellspacing="0" cellpadding="0" width="100%">
		<tr>
			<td>
<?
if (is_admin())
{
	$selectUsernameSQL = "select * from $bx_db_table_banner_users where user_id='".$user_id."'";
	$selectUsername_query = bx_db_query($selectUsernameSQL);
	SQL_CHECK(0,"SQL Error at ".__FILE__.":".(__LINE__-1));
	$selectUsername_res = bx_db_fetch_array($selectUsername_query);
	echo "Client: <b>".$selectUsername_res['name']."</b>";
}
?>
			</td>
		</tr>
		</table>
	</td>
</tr>
<tr>
	<td >
<?
	$job_user_id = $HTTP_SESSION_VARS['pbm_userid'];
	if (is_admin())
	{
		//$sql_plus = " and permission='allow'";
		$job_user_id = $selectUsername_res['compid'];
	}
	
	$selectInvoiceSQL = "select * from ".$bx_db_table_banner_invoices." where compid='".$job_user_id."'";
	
	$select_invoice_query = bx_db_query($selectInvoiceSQL);
	SQL_CHECK(0,"SQL Error at ".__FILE__.":".(__LINE__-1));

	if(bx_db_num_rows($select_invoice_query) =='0' && !$HTTP_SESSION_VARS['pbm_userid'])
	{
		$selectInvoiceSQL = "select * from ".$bx_db_table_banner_types." as zones, ".$bx_db_table_planning." as plannings where zones.type_id=plannings.p_zone_id group by p_zone_id";
		$select_invoice_query = bx_db_query($selectInvoiceSQL);
		SQL_CHECK(0,"SQL Error at ".__FILE__.":".(__LINE__-1));
		$not_from_jobsite = 1;
	}
	if(bx_db_num_rows($select_invoice_query)=='0')
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
		}
?>
		<table align="center" cellpadding="8" cellspacing="0" width="100%" bgcolor="<?php echo $tbl3;?>" border="0" style="border:1px solid <?php echo $tbl5;?>;">

		<tr>
			<td bgcolor="<?php echo $tbl5;?>">
				<font size="2" color="<?php echo HEADING_FONT_COLOR;?>"><b><?php echo $zone_res['typename'.$slng]." (".$zone_res['width']."x".$zone_res['height'].")";?> - </b> <?php if($not_from_jobsite!=1){?><?php echo TEXT_MAX_BANNER_TO_UPLOAD?> <?php echo $invoice_res['i_max_banners'];?></font><?php }?>
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
		$total_views = $total_clicks = $total_banner = 0;
		while ($select_result = bx_db_fetch_array($select_query))
		{
			++$total_banner;
			if ($restricted_user && $select_result['permission']=='deny')
			{?>
		<tr>
			<td align="center">
				<font size="2" color="#FF0000"><b><?php echo TEXT_NOT_VALIDATED?></b></font>
			</td>
		</tr>
			<?}

				elseif($select_result['active']=='false')
			{?>
		<tr>
			<td align="center">
				<font size="2" color="#FF0000"><b><?php echo TEXT_NOT_ACTIVE?></b></font>
			</td>
		</tr>

		<?}
		
		
		if ($select_result['format'] == "image")
		{
?>
		<tr>
			<td align="center">
				<a href="<?php echo $select_result['url']?>"><img src="<?=HTTP_BANNERS.$select_result['banner_name']?>" border="0" alt="<?=$select_result['alt']?>"></a>
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
		else{}
?>
		<tr>
		<tr>
			<td>
				<table width="100%" border="0" bgcolor="#ffffff">
					<td>
						<font face="verdana" style="font-size:11px" color="#3399CC"><b><?php echo TEXT_AD_VIEWS?>:</b> </font>&nbsp;
						<font face="verdana"  style="font-size:11px" color="#3399CC">
<?
	$selectSQL = "select views from $bx_db_table_banner_banners where banner_id='".$select_result['banner_id']."'";
	$count_select_query = bx_db_query($selectSQL);
	SQL_CHECK(0,"SQL Error at ".__FILE__.":".(__LINE__-1));
	$count_select_result = bx_db_fetch_array($count_select_query);
	
	if ($count_select_result['views'])
	{
		echo "<b>".$count_select_result['views'].",</b>";
		$total_views += $count_select_result['views'];
	}
	else
	{
		echo "<b>0,</b>";
		$total_views += 0; 
	}
	
?>
	&nbsp;&nbsp;&nbsp;&nbsp;<font face="verdana"  style="font-size:11px" color="#3399CC"><b><?php echo TEXT_AD_CLICKS?>: </b></font>&nbsp;
	<font face="verdana"  style="font-size:11px" color="#3399CC">
<?
	$selectSQL = "select clicks as counted from $bx_db_table_banner_banners where banner_id='".$select_result['banner_id']."'";
	$count_select_query = bx_db_query($selectSQL);
	SQL_CHECK(0,"SQL Error at ".__FILE__.":".(__LINE__-1));
	$count_select_result = bx_db_fetch_array($count_select_query);
	echo "<b>".$count_select_result['counted']."</b>";

	$total_clicks += $count_select_result['counted'];
?>
					</td><td>&nbsp;
					<?php
						if ($total_views != 0)
						{
					?><a href="javascript: ;" onClick="window.open('<?php echo HTTP_SERVER?>stat_details.php?banner_id=<?php echo $select_result['banner_id']?>','details','toolbar=no,location=no,directories=no,status=no,scrollbars=yes,resizable=yes,copyhistory=no,width=600,height=400');"><?php echo TEXT_DETAILS; ?></a>
					<?php
						} 
					?>
					</td>
				</tr>
				</table>
			</td>
		</tr>
		<tr>
			<td colspan="2" bgcolor="<?php echo TABLE_HEADING_BGCOLOR;?>" height="1"></td>
		</tr>
<?
		}
?>
		<tr>
			<td colspan="2" bgcolor="<?php echo LIST_HEADER_COLOR;?>" height="1"></td>
		</tr>
		<tr>
			<td bgcolor="<?php echo $tbl6;?>">
				<font face="verdana" style="font-size:11px" color="#000000"><b><?php echo TEXT_TOTAL_BANNERS?>: </b></font>
				<font face="verdana" style="font-size:11px" color="#000000"><b>
<?
	echo $total_banner;
?>
						,&nbsp;&nbsp;&nbsp;&nbsp;</b></font>
				<font face="verdana" style="font-size:11px" color="#000000"><b><?php echo TEXT_TOTAL_ADVIEWS?>: </b></font>&nbsp;
				<font face="verdana" style="font-size:11px" color="#000000"><b>
<?
	echo $total_views;
?>
				,&nbsp;&nbsp;&nbsp;&nbsp;<font face="verdana" style="font-size:11px" color="#000000"><b><?php echo TEXT_TOTAL_ADCLICKS?>:</b> </font>&nbsp;
				<font face="verdana" style="font-size:11px" color="#000000"><b>
<?
	echo $total_clicks;
?>
				</b></font>
			</td>
		</tr>
	</table><br><br>
<?
	}
?>


	</td>
</tr>
</table>
