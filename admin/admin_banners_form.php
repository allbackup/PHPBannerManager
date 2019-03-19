<?php

if($HTTP_POST_VARS['delete_id'])
{
	$deleteSQL = "delete from $bx_db_table_planning where p_zone_id='".$HTTP_POST_VARS['delete_id']."'";
	$delete_query = bx_db_query($deleteSQL);
	SQL_CHECK(0,"SQL Error at ".__FILE__.":".(__LINE__-1));
}
elseif($HTTP_POST_VARS['form_update'] == '1')
{
	for ($upd=0;$upd < sizeof($HTTP_POST_VARS['id']) ;$upd++ )
	{
		$update_SQL = "update ".$bx_db_table_planning." set p_max_banners = '".$HTTP_POST_VARS['p_max_banners']."',  p_h_price = '".$HTTP_POST_VARS['p_h_price'][$upd]."', p_c_price = '".$HTTP_POST_VARS['p_c_price'][$upd]."', p_p_price = '".$HTTP_POST_VARS['p_p_price'][$upd]."', p_unit = '".$HTTP_POST_VARS['p_unit']."' where p_id='".$HTTP_POST_VARS['id'][$upd]."'";
		$update_query = bx_db_query($update_SQL);
		SQL_CHECK(0,"SQL Error at ".__FILE__.":".(__LINE__-1));
	}
}
elseif ($HTTP_GET_VARS['gen_id'])
{
	$selectSQL1 = "select * from $bx_db_table_planning, $bx_db_table_planning_months where p_zone_id='".$HTTP_GET_VARS['gen_id']."' and p_period_id=m_id and m_active='1'";
	$select_query1 = bx_db_query($selectSQL1);	
	SQL_CHECK(0,"SQL Error at ".__FILE__.":".(__LINE__-1));
	$nr_zone = bx_db_num_rows($select_query1);

	$selectSQL = "select * from $bx_db_table_planning_months where m_active='1'";
	$select_query = bx_db_query($selectSQL);
	SQL_CHECK(0,"SQL Error at ".__FILE__.":".(__LINE__-1));
	$nr_month = bx_db_num_rows($select_query);
	
	if($nr_zone == 0)
		while ($month_res = bx_db_fetch_array($select_query))
		{
			//echo "<br>".
			$selectDefaultValuesSQL = "select * from $bx_db_table_planning where p_period_id=".$month_res['m_id'];
			$selectDefaultValues_query = bx_db_query($selectDefaultValuesSQL);
			SQL_CHECK(0,"SQL Error at ".__FILE__.":".(__LINE__-1));
			$def_res = bx_db_fetch_array($selectDefaultValues_query);
			//echo bx_db_num_rows($select_query);
			bx_db_insert($bx_db_table_planning, "p_id, p_zone_id, p_max_banners, p_period_id, p_h_price, p_c_price, p_p_price, p_unit", "'', '".$HTTP_GET_VARS['gen_id']."', '".$def_res['p_max_banners']."', '".$month_res['m_id']."', '0', '0', '0', '".$def_res['p_unit']."'");
			SQL_CHECK(0,"SQL Error at ".__FILE__.":".(__LINE__-1));
		}
	
}
elseif ($HTTP_GET_VARS['compl_id'])
{
	$selectSQL1 = "select * from $bx_db_table_planning, $bx_db_table_planning_months where p_zone_id='".$HTTP_GET_VARS['compl_id']."' and p_period_id=m_id and m_active='1'";
	$select_query1 = bx_db_query($selectSQL1);	
	SQL_CHECK(0,"SQL Error at ".__FILE__.":".(__LINE__-1));
	$nr_zone = bx_db_num_rows($select_query1);

	$selectSQL = "select * from $bx_db_table_planning_months where m_active='1'";
	$select_query = bx_db_query($selectSQL);
	SQL_CHECK(0,"SQL Error at ".__FILE__.":".(__LINE__-1));
	$nr_month = bx_db_num_rows($select_query);
		
	if($nr_zone != $nr_month)
		while ($month_res = bx_db_fetch_array($select_query))
		{
			$selectSQL3 = "select * from $bx_db_table_planning where p_period_id='".$month_res['m_id']."' and p_zone_id='".$HTTP_GET_VARS['compl_id']."'";
			$select_query3 = bx_db_query($selectSQL3);
			SQL_CHECK(0,"SQL Error at ".__FILE__.":".(__LINE__-1));
			//echo bx_db_num_rows($select_query3)." ".$month_res['m_id']."<br>";
			if(bx_db_num_rows($select_query3) == '0')
			{
				//echo "<br>".
				$selectDefaultValuesSQL = "select * from $bx_db_table_planning where p_zone_id=".$HTTP_GET_VARS['compl_id'];
				$selectDefaultValues_query = bx_db_query($selectDefaultValuesSQL);
				SQL_CHECK(0,"SQL Error at ".__FILE__.":".(__LINE__-1));
				$def_res = bx_db_fetch_array($selectDefaultValues_query);
				//echo bx_db_num_rows($select_query);
				
				bx_db_insert($bx_db_table_planning, "p_id, p_zone_id, p_max_banners, p_period_id, p_h_price, p_c_price, p_p_price, p_unit", "'', '".$HTTP_GET_VARS['compl_id']."', '".$def_res['p_max_banners']."', '".$month_res['m_id']."', '0', '0', '0', '".$def_res['p_unit']."'");
				SQL_CHECK(0,"SQL Error at ".__FILE__.":".(__LINE__-1));
			}
		}
}
elseif ($HTTP_GET_VARS['d_id'])
{
	$deleteSQL = "delete from $bx_db_table_planning where p_id='".$HTTP_GET_VARS['d_id']."'";
	$delete_query = bx_db_query($deleteSQL);
	SQL_CHECK(0,"SQL Error at ".__FILE__.":".(__LINE__-1));
}
else{}

$selectSQL1 = "select * from $bx_db_table_banner_types order by typename".$slng;
$select_query1 = bx_db_query($selectSQL1);	
SQL_CHECK(0,"SQL Error at ".__FILE__.":".(__LINE__-1));
$nr_zone = bx_db_num_rows($select_query1);

?>

<table width="700" cellspacing="0" cellpadding="2" border="0">
 <tr>
     <td bgcolor="#000000" align="center"><font face="Verdana, Arial" size="2" color="white"><b>Banner Planning Manager</b></font></td>
 </tr>
 <tr>
   <td bgcolor="#000000">
<TABLE border="0" cellpadding="2" cellspacing="0" bgcolor="#00CCFF" width="100%" nowrap>
<tr align="center">
	<td>
		<font face="<?=TEXT_FONT_FACE?>" size="<?=TEXT_FONT_SIZE-1?>" color="<?=TEXT_FONT_COLOR?>"><b>Banner Zone</font>
	</td>
	<td>
		<font face="<?=TEXT_FONT_FACE?>" size="<?=TEXT_FONT_SIZE-1?>" color="<?=TEXT_FONT_COLOR?>"><b>Base unit</font>
	</td>
	<td>
		<font face="<?=TEXT_FONT_FACE?>" size="<?=TEXT_FONT_SIZE-1?>" color="<?=TEXT_FONT_COLOR?>"><b>Maximum allowed <br>banners to upload</font>
	</td>
	<td>
		<font face="<?=TEXT_FONT_FACE?>" size="<?=TEXT_FONT_SIZE-1?>" color="<?=TEXT_FONT_COLOR?>"><b>Period
	</td>
	<td>
		<font face="<?=TEXT_FONT_FACE?>" size="<?=TEXT_FONT_SIZE-1?>" color="<?=TEXT_FONT_COLOR?>"><b>Expires by Date<br> or Impressions<br><i>Price/Base Unit/Period</i>
	</td>
	<td>
		<font face="<?=TEXT_FONT_FACE?>" size="<?=TEXT_FONT_SIZE-1?>" color="<?=TEXT_FONT_COLOR?>"><b>Expires based on <br>Date or Clicks<br><i>Price/Base Unit/Period</i>
	</td>
	<td>
		<font face="<?=TEXT_FONT_FACE?>" size="<?=TEXT_FONT_SIZE-1?>" color="<?=TEXT_FONT_COLOR?>"><b>Expires only <br>based on dates<br><i>Price/Period</i>
	</td>
	<td>
		
		<font face="<?=TEXT_FONT_FACE?>" size="<?=TEXT_FONT_SIZE-1?>" color="<?=TEXT_FONT_COLOR?>"><b>Action
	</td>
</tr>

<?php

if (bx_db_num_rows($select_query1)==0)
{?>
<tr>
	<td colspan="8" align="center">There are no "Zones" or "Periodes" exist !</td>
</tr>	
<?}
while ($pl_res1 = bx_db_fetch_array($select_query1) )
{
	$selectSQL =  "select * from $bx_db_table_planning, $bx_db_table_banner_types,$bx_db_table_planning_months where p_zone_id=type_id and p_zone_id='".$pl_res1['type_id']."' and p_period_id=m_id and m_active='1' order by m_number asc";
	// order by p_period_id
	$select_query = bx_db_query($selectSQL);
	SQL_CHECK(0,"SQL Error at ".__FILE__.":".(__LINE__-1));
	if(bx_db_num_rows($select_query) !=0)
	{
?>
<form method=post action="<?php echo HTTP_SERVER_ADMIN;?>admin_banners.php" style="margin-width:0px;margin-height:0px">
<input type="hidden" name="form_update" value="1">
<?
	$loop_value = 0;
	while ($pl_res = bx_db_fetch_array($select_query) )
	{
	$loop_value++;
?>
<tr bgcolor="#ffffff" align="center">
	<td valign="top">
<?php 
	if($loop_value==1)
		echo $pl_res['typename'.$slng]." <br>(".$pl_res['width']."x".$pl_res['height'].")";
?>
	</td>
	<td>
<?php 
	if($loop_value==1){
?>
		<input type="text" name="p_unit" value="<?php echo $pl_res['p_unit'];?>" class="" size="6">
<?}?>
	</td>
<td>
<?php 
	if($loop_value==1){
?>
		<input type="text" name="p_max_banners" value="<?php echo $pl_res['p_max_banners'];?>" class="" size="5">
<?}?>
	</td>
	<td>
		<?php echo $pl_res['m_number'];?> month
	</td>
	<td <?php echo (!eregi("([1-9\.])",$pl_res['p_h_price']) ? 'bgcolor="#ff0000"' : '')?> class="text">
		<?php if (CURRENCY_POSITION=="left") {echo PRICE_CURENCY."&nbsp;";}; ?><input type="text" name="p_h_price[]" value="<?php echo $pl_res['p_h_price'];?>" class="" size="5"><?php if (CURRENCY_POSITION!="left") {echo "&nbsp;".PRICE_CURENCY;}; ?>
	</td>
	<td <?php echo (!eregi("([1-9\.])",$pl_res['p_c_price']) ? 'bgcolor="#ff0000"' : '')?> class="text">
		<?php if (CURRENCY_POSITION=="left") {echo PRICE_CURENCY."&nbsp;";}; ?><input type="text" name="p_c_price[]" value="<?php echo $pl_res['p_c_price'];?>" class="" size="5"><?php if (CURRENCY_POSITION!="left") {echo "&nbsp;".PRICE_CURENCY;}; ?>
	</td>
	<td <?php echo (!eregi("([1-9\.])",$pl_res['p_p_price']) ? 'bgcolor="#ff0000"' : '')?> class="text">
		<?php if (CURRENCY_POSITION=="left") {echo PRICE_CURENCY."&nbsp;";}; ?><input type="text" name="p_p_price[]" value="<?php echo $pl_res['p_p_price'];?>" class="" size="5"><?php if (CURRENCY_POSITION!="left") {echo "&nbsp;".PRICE_CURENCY;}; ?>
	</td>
	<td>
		<a href="<?php echo HTTP_SERVER_ADMIN."admin_banners.php?d_id=".$pl_res['p_id'];?>" onClick="return confirm('Are you sure that you want to delete this entr?')">Delete</a>
	</td>
</tr>
<?php
	if ( !eregi("([1-9\.])",$pl_res['p_h_price']) or !eregi("([1-9\.])",$pl_res['p_c_price']) or !eregi("([1-9\.])",$pl_res['p_p_price']) )
		{
			$show_set_prices=1;
		}else
		{
			$show_set_prices=0;
		}
?>
<tr bgcolor="#ffffff">
	<td colspan="8">
		<input type="hidden" name="id[]" value="<?php echo $pl_res['p_id'];?>">
	</td>
</tr>
<?
	}
?>
<tr>
	<td colspan="2" bgcolor="#ffffff">
<?php
	$selectSQL2 = "select * from $bx_db_table_planning_months where m_active='1'";
	$select_query2 = bx_db_query($selectSQL2);	
	SQL_CHECK(0,"SQL Error at ".__FILE__.":".(__LINE__-1));
	$nr_zone2 = bx_db_num_rows($select_query2);
	if ($nr_zone2 != $loop_value)
	{
?>
	<a href="<?php echo HTTP_SERVER_ADMIN."admin_banners.php?compl_id=".$pl_res1['type_id'];?>">Complete empty lines</a> 
	</td>
	<td colspan="6" bgcolor="#ffffff">
		&nbsp;<!-- <font size="1" color="#FF0000" face="verdana, arial"><b>You have to complete this line, otherway thia will affect your script well working!</b></font> -->
	</td>
<?
	}
	else
		echo "&nbsp;</td><td colspan=\"6\" bgcolor=\"#ffffff\"></td>";
?>
</tr>
<?php
	if ( $show_set_prices==1 )
	{
?>
	<tr bgcolor="#ffffff">
		<td colspan="4">&nbsp;</td>
		<td colspan="4" class="text"><font color="#ff0000"><b>Please set all prices!</b></font></td>
	</tr>
<?php
	} 
?>
<tr bgcolor="#ffffff">
	<td colspan="4" align="right">
		<input type="submit" name="submit" value="Submit" class="">&nbsp;
		</form>
	</td>
	<td colspan="4" align="right">
		<form method=post action="<?php echo HTTP_SERVER_ADMIN;?>admin_banners.php" style="margin-width:0px;margin-height:0px">
			<input type="hidden" name="delete_id" value="<?php echo $pl_res1['type_id'];?>">
			&nbsp;<input type="submit" name="submit" value="Delete" class="" onClick="return confirm('Are you sure you want delete this entry?')">&nbsp;&nbsp;
		</form>
	</td>
</tr>
<tr>
	<td colspan="8"></td>
</tr>
<?
	}
	else
	{
?>
<tr bgcolor="#ffffff" align="center">
	<td valign="top">
<?php 
		echo $pl_res1['typename'.$slng]." <br>(".$pl_res1['width']."x".$pl_res1['height'].")";
?>
	</td>
	<td colspan="7">
		<a href="<?php echo HTTP_SERVER_ADMIN."admin_banners.php?gen_id=".$pl_res1['type_id'];?>">Generate Planning Lines</a>
	</td>
<tr>
	<td colspan="8"></td>
</tr>
<?
	}
}
?>
<!-- 
<tr>
        <td align="right"><font face="<?=TEXT_FONT_FACE?>" size="<?=TEXT_FONT_SIZE?>" color="<?=TEXT_FONT_COLOR?>"><b><?=TEXT_SUBJECT?>:</b></font></td><td><input type="text" name="bulk_subject" size="40"></td>
</tr>
<tr>
        <td align="right" valign="top"><font face="<?=TEXT_FONT_FACE?>" size="<?=TEXT_FONT_SIZE?>" color="<?=TEXT_FONT_COLOR?>"><b><?=TEXT_MESSAGE?>:</b></font></td><td><textarea name="bulk_message" rows="10" cols="40"></textarea></td>
</tr>
 --></table>
</td></tr>
</table>
