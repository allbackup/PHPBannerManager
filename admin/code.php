<?php
include('admin_design.php');
include('../application_config_file.php');
include('admin_auth.php');
include(DIR_LANGUAGES.$language."/".FILENAME_ADMIN_FORM);
include("header.php");
$page_title = "Generate Banner Code";

include(DIR_LANGUAGES.$language.'/generate_banner_code_form.php');
?>
<center><font face="helvetica" size="2"><b><?=$page_title?></b></font><br></center><br><br>
<table align="center" border="0" cellspacing="2" cellpadding="1" width="<?php echo BIG_TABLE_WIDTH;?>" bgcolor="#F2F8FF">
<?php
	if($HTTP_GET_VARS['error']) 
	{?>
<tr>
	<td align="center"><font face="helvetica"><b><?=bx_msg('Please enter how many banner do you want to display')?></b></font><br></td>
</tr>
	<?}
?>
<tr>
	<td bgcolor="#9FCBFF">
		<table align="center" cellpadding="3" cellspacing="0" width="100%" bgcolor="#ffffff" border="0">
<?
if ($HTTP_POST_VARS['code_generate'] != "yes")
{
?>
		<tr bgcolor="#9FCBFF">
			<td align="center"><form method=post action="<?php echo HTTP_SERVER_ADMIN;?>code.php" style="margin-width:0px;margin-height:0px">
				<font face="verdana" size="2" color="#ffffff"><?php echo TEXT_BANNER_TYPE?></font>
			</td>
			<td align="center">
				<font face="verdana" size="2" color="#ffffff"><?php echo TEXT_NR_BANNERS?></font>
			</td>
			<td align="center">
				<font face="verdana" size="2" color="#ffffff"><?php echo TEXT_ALIGNMENT?></font>
			</td>
			<td align="center">
				<font face="verdana" size="2" color="#ffffff"><?php echo TEXT_CODE_FORMAT?></font>
			</td>
		</tr>
		<tr>
			<td colspan="4" bgcolor="#9FCBFF" height="1"></td>
		</tr>
		
<?
	$i = 0;
	//$selectSQL = "select * from $bx_db_table_banner_types";
	$selectSQL = "select * from ".$bx_db_table_banner_types." as zones, ".$bx_db_table_planning." as plannings where zones.type_id=plannings.p_zone_id group by p_zone_id";
	$select_type_query = bx_db_query($selectSQL);
	SQL_CHECK(0,"SQL Error at ".__FILE__.":".(__LINE__-1));
	while ($select_type_result = bx_db_fetch_array($select_type_query))
	{
		$i++;
?>
		<tr>
			<td align="right">
				<font face="verdana" size="2" color="#000000"><?=$select_type_result['typename'.$slng];?></font>
			</td>
			<td align="center">
				<input type="text" name="<?=$select_type_result['typename'.$slng];?>" value="0" class="" size="1" onClick="this.select()" style="text-align:center">
			</td>
			<td align="left">
				<input type="hidden" name="type_id<?=$i?>" value="<?=$select_type_result['type_id'];?>">
				<select name="position<?=$i?>">
					<option value="h" selected><?php echo TEXT_HORIZONTAL?></option>
					<option value="v"><?php echo TEXT_VERTICAL?></option>
				</select>
			</td>
			<td align="left">
				<select name="codetype_id<?=$i?>">
					<option value="js" selected><?php echo TEXT_JAVASCRIPT?></option>
					<option value="php"><?php echo TEXT_PHP?></option>
				</select>
			</td>
		</tr>
<?		
	}
?>
		<tr>
			<td colspan="4" bgcolor="#9FCBFF" height="1"></td>
		</tr>
<?
	if (!bx_db_num_rows($select_type_query))
	{
?>
	<tr>
		<td align="center" colspan="4">
			<font size="3" color=""><font size="2" color="#FF0000"><b><?php echo TEXT_NO_ZONES_DEFINED?></font>
		</td>
	</tr>

	</table>
<?
		include(BANNER_FORMS. 'footer.php');
		exit;
	}
?>

		<tr>
			<td colspan="4" align="center">
				<input type="hidden" name="code_generate" value="yes">
				<input type="submit" name="generate" value="Generate" class="">
				</form>
			</td>
		</tr>
<?
}
else
{

?>
		<tr>
			<td>
				<form method="post" action="index.php">
			</td>
		</tr>
<?	
	$i = 0;
	$selectSQL = "select * from $bx_db_table_banner_types";
	$select_type_query = bx_db_query($selectSQL);
	SQL_CHECK(0,"SQL Error at ".__FILE__.":".(__LINE__-1));
	$total_zones = bx_db_num_rows($select_type_query);

	$z = 0;
	while ($select_type_result = bx_db_fetch_array($select_type_query))
	{
		$i++;
		if ($HTTP_POST_VARS[ereg_replace(" ", "_",$select_type_result['typename'.$slng])] == '0' || $HTTP_POST_VARS[ereg_replace(" ", "_",$select_type_result['typename'.$slng])] == '' )
		{
			if ($i == $total_zones && $z == 0)
			{
				refresh(HTTP_SERVER_ADMIN.'code.php?error=1');
				exit;
			}
			continue;
		}
		$z = 1;
?>
		<tr>
			<td width="30%" align="right">
				<b><font face="verdana" size="2" color="#000000"><?=$select_type_result['typename'.$slng];?> (<?=$HTTP_POST_VARS['codetype_id'.$i]?>)</font></b>
			</td>
			<td>
				<?php echo TEXT_DONT_MODIFY?><br><br>
				<textarea name="<?=$select_type_result['typename'.$slng];?>" rows="10" cols="70"><!-- begin <?=$select_type_result['typename'.$slng];?> banner -  width:<?=$select_type_result['width'];?>, height:<?=$select_type_result['height'];?> -->
<?
	if ($HTTP_POST_VARS['codetype_id'.$i] == 'php')
	{
?>
&lt;?
$pbm_type = <?=$select_type_result['type_id'];?>;
$pbm_nr_banner = <?=$HTTP_POST_VARS[ereg_replace(" ", "_",$select_type_result['typename'.$slng])];?>;
$pbm_position = "<?=$HTTP_POST_VARS['position'.$i]?>";
include ("<?=DIR_BANNER?>banner_display.php");
?&gt;
<?
	}
	else
	{
?>
<script language="Javascript" src="<?=HTTP_BANNER?>banner_display.php?style=js&t=<?=$select_type_result['type_id'];?>&p=<?=substr($HTTP_POST_VARS['position'.$i], 0, 1)?>&n=<? echo $HTTP_POST_VARS[ereg_replace(" ", "_",$select_type_result['typename'.$slng])];?>">
</script>
<?
	}
?>
<!-- end <?=$select_type_result['typename'.$slng];?> banner --></textarea>
			</form>
			</td>
		</tr>
			
<?
	}
	if(!bx_db_num_rows($select_type_query))
	{
?>
	<tr>
	<td align="center" colspan="2">
		<font size="3" color=""><font size="2" color="#FF0000"><b><?php echo TEXT_NO_ZONES_DEFINED?></font>
	</td>	
	</tr>
<?
	}
	elseif ($z==0)
	{
?>
		<tr>
			<td colspan="2" align="center">
				<font size="2" color="#FF0000"><b><?php echo TEXT_NO_ZERO?></b></font>
			</td>
		</tr>	
<?
	}
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
<?php
include('footer.php'); 
?>