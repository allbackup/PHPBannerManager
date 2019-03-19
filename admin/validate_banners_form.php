<?php
include(DIR_LANGUAGES.$language.'/banners_form.php');
?>
<center><font face="Verdana, Arial" size="2" color="#000000"><b>Validate Banners</b></font></center>
<table align="center" border="0" cellspacing="5" cellpadding="1" width="100%" bgcolor="#F2F8FF">
<tr>
	<td align="right">
		<a href="validate_banners.php?user_id=<?=$user_id?>&validate_all=yes" onClick="return confirm('<?php echo TEXT_CONFIRM_VALIDATE_ALL?>');"><?php echo TEXT_VALIDATE_ALL_BANNERS?></a>
	</td>
</tr>
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
	echo TEXT_CLIENT.": <b>".$selectUsername_res['name']."</b>";
}
?>
			</td>
		</tr>
		</table>
	</td>
</tr>
<tr>
	<td bgcolor="#9FCBFF">
		<table align="center" cellpadding="8" cellspacing="0" width="100%" bgcolor="#F2F8FF" border="0">
<?
	$sql_plus = " and permission='deny'";
	//$job_user_id = $selectUsername_res['compid'];
	$job_user_id = ($selectUsername_res['user_type']=='admin' ? 0 : $selectUsername_res['user_id']);
	
	$selectInvoiceSQL = "select *, DATE_ADD(i_start_date, INTERVAL i_period MONTH) as expire_date from ".$bx_db_table_banner_invoices." where compid='".$job_user_id."' and paid='Y' and validated='Y'";
	$select_invoice_query = bx_db_query($selectInvoiceSQL);
	SQL_CHECK(0,"SQL Error at ".__FILE__.":".(__LINE__-1));
	//echo bx_db_num_rows($select_invoice_query);

	if(bx_db_num_rows($select_invoice_query)=='0')
	{
?>
<tr>
	<td bgcolor="#FF3300" align="center">
		<b><font size="" color="#ffffff"><?php echo TEXT_NOT_PAYED_PLANNING;?></font></b>
	</td>
</tr>
</table>
<?
		include (BANNER_FORMS."footer.php");
		exit;
	}
	if(bx_db_num_rows($select_invoice_query) =='0') /// || $job_user_id=='0')
	{
		$selectInvoiceSQL = "select * from ".$bx_db_table_banner_types;
		$select_invoice_query = bx_db_query($selectInvoiceSQL);
		SQL_CHECK(0,"SQL Error at ".__FILE__.":".(__LINE__-1));
		$not_from_jobsite = 1;
	}

	while ($invoice_res = bx_db_fetch_array($select_invoice_query))
	{
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
		else
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
?>

<tr>
	<td bgcolor="#9ccbef">
		<font size="2" color="#ffffff"><b><?php echo $zone_res['typename'.$slng]." (".$zone_res['width']."x".$zone_res['height'].")";?> - </b> <?php { echo TEXT_MAX_UPLOAD.$invoice_res['i_max_banners'].", ".TEXT_PURCHASED.":".($invoice_res['i_purchased_nr']!='-1' ? $invoice_res['i_purchased_nr'] : TEXT_UNLIMITED);?></font><?php }?>
	</td>
</tr>
<?php 
		if(!$nr_banners)
		{
?>
<tr>
	<td align="center">
	<b><font size="" color="##FF0000"><?php echo TEXT_NO_BANNERS;?></font></b>
	</td>
</tr>

<?			
			//include (DIR_SERVER_ADMIN."footer.php");
			//exit;
		}	
		
?>	
<?
		if($invoice_res['expire_date'] < date('Y-m-d') && $selectUsername_res['user_type']!='admin' && $nr_banners>0)
		{
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
		<?php echo TEXT_EXPIRE_ON;?><?php echo $invoice_res['expire_date'];?></b>
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

		while ($select_result = bx_db_fetch_array($select_query))
		{
			if (($restricted_user && $select_result['permission']=='deny'))
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
		<a href="<?=$select_result['url']?>"><img src="<?=HTTP_BANNERS.$select_result['banner_name']?>" border="0" alt="<?=$select_result['alt']?>"></a>
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
	<td>
		<table align="center" border="0" cellspacing="0" cellpadding="0" width="100%" bgcolor="#ffffff">
		<tr>
			<td width="5%">
				<font size="2" face="helvetica, verdana, arial" color="#CCCCCC">#<?=$select_result['banner_id']?></font>&nbsp;&nbsp;&nbsp;
			</td>
			<td>
			<?
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

				}?>
				
			</td>
			<td width="13%" align="center">
<?	

			echo "<a href=\"validate_banners.php?banner_id=".$select_result['banner_id']."&validate=yes&user_id=".$user_id."\">".TEXT_VALIDATE."</a>";
?>
			</td>
			<td width="14%" align="center">
				<a href="add_banner.php?banner_id=<?=$select_result['banner_id']?>&user_id=<?=$user_id?>&format=<?=$select_result['format']?>&zid=<?php echo substr($select_result['type_id'],1,-1);?>"><?php echo TEXT_MODIFY_BANNER?></a>
			</td>
			<td width="10%" align="center">
				<a href="delete_banner.php?banner_id=<?=$select_result['banner_id']?>&user_id=<?=$user_id?>&val_del=1" onClick="return confirm('Are you sure you want to delete this banner?');"><?php echo TEXT_DELETE?></a>
			</td>
		</tr>
		</table>
	<br><br></td>
</tr>
<tr>
	<td colspan="2" bgcolor="#9FCBFF" height="1"></td>
</tr>
<?
		}
	}
	if (bx_db_num_rows($select_query) =='0')
	{
?>
<tr>
	<td colspan="2" bgcolor="#9FCBFF" height="1"></td>
</tr>
<?
	}
?>
</table>

	</td>
</tr>
</table>