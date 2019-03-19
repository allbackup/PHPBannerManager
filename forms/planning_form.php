<?php
//include ('application_config_file.php');
include(DIR_LANGUAGES.$language."/".FILENAME_MEMBERSHIP_FORM);
    
?>
<?php if($error!=0)
  {
   echo bx_table_header(ERRORS_OCCURED);
    echo "<font face=\"".ERROR_TEXT_FONT_FACE."\" size=\"".ERROR_TEXT_FONT_SIZE."\" color=\"".ERROR_TEXT_FONT_COLOR."\"><b>";

     echo "</b></font>";
  }//end if error!=0
?>
<script language="Javascript">
<!--
var temp_name = new Array;
var temp_base = new Array;
var temp_number = new Array;
var temp_radio = new Array;
var real_zone_size;
real_zone_size = 0;

function calculate_banner()
{
var v,for_nr,x,yyy_length,temp_error;
	temp_number=document.banner_form['banner_purchase[]'];
	total_price = 0;
	radio_length = document.banner_form['banner_planning1'].length;
	ban_zones = real_zone_size;//temp_radio.length / document.banner_form['banner_planning1'].length;
	for_nr = temp_radio.length / ban_zones;
	temp_total = 0;
	actual_var = 0;

	for (z_nr=1; z_nr<=ban_zones; z_nr++)
	{
		
		for (x=0; x<for_nr; x++)
		{
			radio_length = document.banner_form['banner_planning'+z_nr].length;
			for_nr = document.banner_form['banner_planning'+z_nr].length;
			
			yyy = document.banner_form['banner_planning'+z_nr];
			if (yyy[x].checked)
			{
				radio_sel = (z_nr-1)*radio_length+x;
				nr_sel = Math.floor(((z_nr-1)*radio_length+x)/3);

				if (((x+1)%3)==0)
				{
					temp_total = temp_total + (temp_radio[actual_var*3+x])*1000;
				}else{
					if (temp_base[actual_var] > temp_number[actual_var+x/3].value)	
					{
						alert(temp_name[actual_var]+'\n<?=TEXT_MINIMUM?>'+temp_base[actual_var]);
						temp_error = 1;
					}else if (!isNaN(temp_base[actual_var] && !isNaN(temp_number[actual_var+x/3].value))){
						temp_total = temp_total + ((temp_radio[actual_var*3+x])/temp_base[actual_var])*temp_number[actual_var+x/3].value*1000;
					}
				}
			}
		}
		actual_var += document.banner_form['banner_planning'+z_nr].length/3;
		//	alert(actual_var);
		
	}
	if (!temp_error)
	{
		total_price=Math.round((temp_total/1000)*100)/100;
		if ( isNaN(total_price) ) total_price=0;
		document.banner_form.total_banner_price.value=total_price;
	}

}

//-->
</script>

<table bgcolor="<?php echo TABLE_BGCOLOR;?>" width="100%" border="0" cellspacing="0" cellpadding="2">
<TR bgcolor="#FFFFFF">
		<TD colspan="2" width="100%" align="center" class="headertdjob"><?php echo TEXT_PLANNING;?></TD>
</TR>
<TR><TD colspan="2"><table cellpadding="0" cellspacing="0" border="0" width="100%">
		<tr>
			<td bgcolor="<?php echo TABLE_HEADING_BGCOLOR;?>" height="1"><?php echo bx_image_width(HTTP_IMAGES.$language."/pix-t.gif",1,1,0,"");?></td>
			</tr>
		</table></TD>
</TR>
</table>

<table bgcolor="<?php echo TABLE_BGCOLOR;?>" width="100%" border="0" cellspacing="0" cellpadding="0">

<input type="hidden" name="action" value="upgrade">
<input type="hidden" name="current_pricing" value="<?php echo $company_result['pricing_id'];?>">
</form>
</table>
<br>

<!-- banner statistics **************************************************************-->
<?php
$banstat_selectSQL = "select *,DATE_ADD(i_start_date, INTERVAL i_period MONTH) as expire_date from ".$bx_table_prefix."_invoices, $bx_db_table_banner_types where compid='".$HTTP_SESSION_VARS['employerid']."' and i_zone=type_id and paid='Y' and updated='Y' and i_expired='0' ORDER by i_zone";
$banstat_select_query = bx_db_query($banstat_selectSQL);

SQL_CHECK(0,"SQL Error at ".__FILE__.":".(__LINE__-1));
?>
<table align="center" border="0" cellspacing="0" cellpadding="0">
   <tr>
       <td>
           <table border="1" align="center" cellpadding="3" cellspacing="1">
                 <tr bgcolor="<?echo TABLE_HEADING_BGCOLOR;?>">
                     <td colspan="7" align="center"><b><font face="<?=TEXT_FONT_FACE?>" size="<?=TEXT_FONT_SIZE?>" color="#FFFFFF"><?=TEXT_CURRENTLY_BANNERS?></font></b></td>
                 </tr>
                 <tr>
                     <td align="center">&nbsp;<?=TEXT_BANNER_ZONE?>&nbsp;</td>
                     <td align="center">&nbsp;<?=TEXT_BANNER_UPLOAD?>&nbsp;</td>
                     <td align="center">&nbsp;<?=TEXT_BANNER_TYPE?>&nbsp;</td>
                     <td align="center">&nbsp;<?=TEXT_BANNER_PERIOD?>&nbsp;</td>
                     <td align="center">&nbsp;<?=TEXT_BANNER_EXPIRE?>&nbsp;</td>
                     <td align="center">&nbsp;<?php echo TEXT_BXVIEWS;?>&nbsp;</td>
					 <td align="center">&nbsp;<?php echo TEXT_BXCLICKS;?>&nbsp;</td>
                 </tr>
				 <?
				 while ($banstat_res = bx_db_fetch_array($banstat_select_query))
				 {
				 ?>	
                 <tr>
                     <td align="center"><b><?=$banstat_res['typename'.$slng]?></b></td>
                     <td align="center"><b><?=$banstat_res['i_max_banners']?></b></td>
                     <td align="center"><b><?php 
					 if ($banstat_res['i_type']=='1')
					 {
						echo TEXT_STAT_IMPRESSIONS.$banstat_res['i_purchased_nr'];
					 }
					 elseif ($banstat_res['i_type']=='2')
					 {
					 	echo TEXT_STAT_CLICKS.$banstat_res['i_purchased_nr'];
					 }
					 else
					 {
					 	echo TEXT_STAT_FLAT;
					 }	
					 ?></b></td>
                     <td align="center"><b><?=$banstat_res['i_period']?></b></td>
                     <td align="center"><b><?=bx_format_date($banstat_res['expire_date'], DATE_FORMAT)?></b></td>
                     <td align="center"><b>
<?php 
$countSQL = "select *, sum(views) as count_views, sum(clicks) as count_clicks from $bx_db_table_banner_banners where type_id='|".$banstat_res['i_zone']."|' and user_id='".$HTTP_SESSION_VARS['employerid']."' group by type_id";
$count_query = bx_db_query($countSQL);
SQL_CHECK(0,"SQL Error at ".__FILE__.":".(__LINE__-1));				 
$count_res = bx_db_fetch_array($count_query);
echo ($count_res['count_views'] ? $count_res['count_views'] : '0');
?>					 
					 </b></td>
                     <td align="center"><b><?php echo ($count_res['count_clicks'] ? $count_res['count_clicks'] : '0');?></b></td>

                 </tr>
				 <?}
					if (bx_db_num_rows($banstat_select_query)==0)
					{?>
					<tr>
						<td colspan="7" align="center"><font face="<?=ERROR_TEXT_FONT_FACE?>" size="<?=ERROR_TEXT_FONT_SIZE?>" color="<?=ERROR_TEXT_FONT_COLOR?>"><b><?php echo TEXT_STAT_NO_ERROR;?></b></td>
					</tr>	
					<?}?>
            </table>
       </td>
   </tr>
<!-- banner planning begin **************************************************************-->
   <tr>
   	<td>
		<br>
<?php
if (($banner_planning_error)!='')
{
?>
	<div align="center"><font face="<?=ERROR_TEXT_FONT_FACE?>" size="<?=ERROR_TEXT_FONT_SIZE?>" color="<?=ERROR_TEXT_FONT_COLOR?>"><b><?php echo $banner_planning_error;?></b></font></div>
<?
}
?>

		<table bgcolor="<?echo TABLE_BGCOLOR?>" width="100%" border="2" cellspacing="0" cellpadding="0">
		<form name="banner_form" action="<?=bx_make_url(HTTP_SERVER.FILENAME_MEMBERSHIP, "auth_sess", $bx_session)?>" method="post" onSubmit="calculate_banner(); if (total_price==0) {alert('<?=TEXT_NO_BANNER?>'); return false;}">
			<TR bgcolor="<?echo TABLE_HEADING_BGCOLOR;?>">
			  <TD width="25%" align="center">
				<font face="<?=TEXT_FONT_FACE?>" size="<?=TEXT_FONT_SIZE?>" color="#FFFFFF"><B><?=TEXT_BANNER_ZONE?></B></font></TD>
			  <TD align="center">
				<font face="<?=TEXT_FONT_FACE?>" size="<?=TEXT_FONT_SIZE?>" color="#FFFFFF"><small><B><?=TEXT_BANNER_UNIT?></B></small></font></TD>
			  <TD align="center">
				<font face="<?=TEXT_FONT_FACE?>" size="<?=TEXT_FONT_SIZE?>" color="#FFFFFF"><small><B><?=TEXT_BANNER_NUMBER?></B></small></font></TD>
			  <TD align="center">
				<font face="<?=TEXT_FONT_FACE?>" size="<?=TEXT_FONT_SIZE?>" color="#FFFFFF"><small><B><?=TEXT_BANNER_UPLOAD?></B></small></font></TD>
			  <TD align="center">
				<font face="<?=TEXT_FONT_FACE?>" size="<?=TEXT_FONT_SIZE?>" color="#FFFFFF"><small><B><?=TEXT_BANNER_PERIOD?></B></small></font></TD>
			  <TD align="center">
				<font face="<?=TEXT_FONT_FACE?>" size="<?=TEXT_FONT_SIZE?>" color="#FFFFFF"><small><B><?=TEXT_BANNER_IMPRESSIONS?></B><br>(<?=PRICE_CURENCY?>)</small></font></TD>
			  <TD align="center">
				<font face="<?=TEXT_FONT_FACE?>" size="<?=TEXT_FONT_SIZE?>" color="#FFFFFF"><small><B><?=TEXT_BANNER_CLICKS?></B><br>(<?=PRICE_CURENCY?>)</small></font></TD>
			  <TD align="center">
				<font face="<?=TEXT_FONT_FACE?>" size="<?=TEXT_FONT_SIZE?>" color="#FFFFFF"><small><B><?=TEXT_BANNER_DATES?></B><br>(<?=PRICE_CURENCY?>)</small></font></TD>
			</TR>
<?php
$planning_selectSQL = "select * from $bx_db_table_planning, $bx_db_table_planning_months, $bx_db_table_banner_types where p_period_id=m_id and  type_id=p_zone_id and p_unit!=0 ORDER by p_zone_id,m_number";
$planning_select_query = bx_db_query($planning_selectSQL);
SQL_CHECK(0,"SQL Error at ".__FILE__.":".(__LINE__-1));
$tmp_p_id = '';
$i = 0;
$j = 0;
while ($planning_result=bx_db_fetch_array($planning_select_query))
{ 
	//verify if user already has banner in this zone
	$select_verif_SQL = "select * from $bx_db_table_banner_invoices where compid='".$HTTP_SESSION_VARS['employerid']."' and i_zone='".$planning_result['p_zone_id']."' and (i_start_date='0000-00-00' or DATE_ADD(i_start_date, INTERVAL i_period MONTH)>'".date('Y-m-d')."')";
	$select_verif_query = bx_db_query($select_verif_SQL);
	SQL_CHECK(0,"SQL Error at ".__FILE__.":".(__LINE__-1));
	$verif_user = 0;
	if (bx_db_num_rows($select_verif_query)>0)
	{
		$verif_user = 1; //user has banner in this zone
	}

	if ($tmp_p_id==$planning_result['p_zone_id'])
	{
		$tmp_typename = "&nbsp;";
		$row_bgcolor = " bgcolor= #ffff00";
	}
	else
	{
		$tmp_typename = $planning_result['typename'.$slng].(($verif_user)?'&nbsp;<font color=#ff0000>'.TEXT_NOT_AVAILABLE_ERROR.'</font>':'');
		$row_bgcolor = "";
		$i++;
?>
<script language="Javascript">
<!--
real_zone_size += 1;

//-->
</script>
<?
		$tmp_p_id = $planning_result['p_zone_id'];
		?>
		<input type="hidden" name="banner_p_desact[<?=$i?>]" value="<?=(($verif_user)?'1':'0')?>">		
		<?
	}
					
?>	
			<TR <?=(($i%2==0)?'bgcolor='.DISPLAY_LINE_BGCOLOR_FIRST:'bgcolor='.DISPLAY_LINE_BGCOLOR_SECOND)?>>
			  <TD align="left">
				<font face="<?=TEXT_FONT_FACE?>" size="<?=TEXT_FONT_SIZE?>" color="<?=TEXT_FONT_COLOR?>"><small>&nbsp;<b>
				<? echo $tmp_typename;?>
				</b></small></font>
			  </TD>
			  <TD align="center">
				<font face="<?=TEXT_FONT_FACE?>" size="<?=TEXT_FONT_SIZE?>" color="<?=TEXT_FONT_COLOR?>"><small>
				<?php echo $planning_result['p_unit'];?>
				</small></font>
			  </TD>
			  <TD align="center">
				<font face="<?=TEXT_FONT_FACE?>" size="<?=TEXT_FONT_SIZE?>" color="<?=TEXT_FONT_COLOR?>"><input type="text" name="banner_purchase[]" value="0" class="input" size="8" style="text-align:right" maxlength="7"></font>
			  </TD>
			  <input type="hidden" name="banner_p_id[<?=$j?>]" value="<?=$planning_result['p_id']?>">
			  <input type="hidden" name="banner_p_zone[<?=$j?>]" value="<?=$planning_result['p_zone_id']?>">
			  <TD align="center">
				<font face="<?=TEXT_FONT_FACE?>" size="<?=TEXT_FONT_SIZE?>" color="<?=TEXT_FONT_COLOR?>"><small><? echo $planning_result['p_max_banners']?></small></font>
			  </TD>
			  <TD align="center">
				<font face="<?=TEXT_FONT_FACE?>" size="<?=TEXT_FONT_SIZE?>" color="<?=TEXT_FONT_COLOR?>"><small><? echo $planning_result['m_number']?></small></font>
			  </TD>

			  <TD align="left">
				<font face="<?=TEXT_FONT_FACE?>" size="<?=TEXT_FONT_SIZE?>" color="<?=TEXT_FONT_COLOR?>"><small>&nbsp;<input type="radio" name="banner_planning<?php echo $i?>" value="<?php echo $planning_result['p_id']?>__1" class="radio" <?=(($verif_user)?'disabled':'')?>>&nbsp;&nbsp;<? echo bx_format_price($planning_result['p_h_price'],$pricing['pricing_currency'])?></small></font>
			  </TD>
<script language="JavaScript">
<!--
temp_name[<?=$j?>] = '<?=$planning_result['typename'.$slng]?>';
temp_base[<?=$j?>] = <?=$planning_result['p_unit']?>;
temp_radio[<?=($j*3)?>] = <?=$planning_result['p_h_price']?>;
temp_radio[<?=($j*3)+1?>] = <?=$planning_result['p_c_price']?>;
temp_radio[<?=($j*3)+2?>] = <?=$planning_result['p_p_price']?>;
//-->
</script>
			  <TD align="left">
				<font face="<?=TEXT_FONT_FACE?>" size="<?=TEXT_FONT_SIZE?>" color="<?=TEXT_FONT_COLOR?>"><small>&nbsp;<input type="radio" name="banner_planning<?php echo $i?>" value="<?php echo $planning_result['p_id']?>__2" class="radio" <?=(($verif_user)?'disabled':'')?>>&nbsp;&nbsp;<? echo bx_format_price($planning_result['p_c_price'],$pricing['pricing_currency'])?></small></font>
			  </TD>
			  <TD align="left">
					<font face="<?=TEXT_FONT_FACE?>" size="<?=TEXT_FONT_SIZE?>" color="<?=TEXT_FONT_COLOR?>"><small>&nbsp;<input type="radio" name="banner_planning<?php echo $i?>" value="<?php echo $planning_result['p_id']?>__3" class="radio" <?=(($verif_user)?'disabled':'')?>>&nbsp;&nbsp;<? echo bx_format_price($planning_result['p_p_price'],$pricing['pricing_currency'])?></small></font>
			  </TD>
			</TR>
<?
$j++;					
}
?>

<input type="hidden" name="banner_planning_total" value="<?=$i?>">
		<tr>
		 <td colspan="4" align="right" height="30" valign="bottom"><input type="button" value="<?=TEXT_BANNER_CALCULATE?>" onclick="calculate_banner();">&nbsp;&nbsp;&nbsp;</td>
		 <td colspan="4" align="left" valign="bottom"><font face="<?=TEXT_FONT_FACE?>" size="<?=TEXT_FONT_SIZE?>" color="<?=TEXT_FONT_COLOR?>"><b>&nbsp;<?php echo TEXT_SUBTOTAL;?> (<?php echo PRICE_CURENCY;?>) : </b></font><input type="text" name="total_banner_price" value="0" size="8" readonly style="text-align:right;"></td>
		</tr>
		<input type="hidden" name="action" value="buy_banner">
		<tr>
		 <td colspan="8" align="center"><br><input type="submit" name="buy_banner" value=" <?=TEXT_BANNER_BUY?> "></td>
		</tr>
		<input type="hidden" name="current_pricing" value="<?=$company_result['pricing_id']?>">
		</form>
		</table><br><br><br>
   	</td>
   </tr>
   </table>
<!-- banner planning end **************************************************************-->
</form>