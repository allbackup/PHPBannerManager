<?php
include(DIR_LANGUAGES.$language.'/removed_banners_form.php');
?>
<center><font face="Verdana, Arial" size="2" color="#000000"><b>Restore Banners</b></font></center>
<table align="center" border="0" cellspacing="5" cellpadding="1" width="100%" bgcolor="#F2F8FF">
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
	<td align="right">
				<a href="delete_banner.php?delete=all&user_id=<?=$user_id?>" onClick="return confirm('<?php echo TEXT_CONFIRM_DELETE_BANNERS?>');"><?php echo TEXT_DELETE_ALL_BANNERS?></a>
	</td>
</tr>
<tr>
	<td bgcolor="#9FCBFF">
		<table align="center" cellpadding="8" cellspacing="0" width="100%" bgcolor="#F2F8FF" border="0">
<?
	$selectSQL = "select * from $bx_db_table_banner_deleted_banners where user_id='".$user_id."'";
	$select_query = bx_db_query($selectSQL);
	SQL_CHECK(0,"SQL Error at ".__FILE__.":".(__LINE__-1));
	while ($select_result = bx_db_fetch_array($select_query))
	{
?>
	<tr>
		<td>
<?
		if($select_result['format']=="image")
			 {	
				echo "<center><a href='".$select_result['url']."' target=\"_parent\"><img src='".HTTP_BANNERS.$select_result['banner_name']."' border=\"0\" align=\"middle\" hspace=\"5\" vspace=\"5\" alt=\"".$select_result['alt']."\"></a></center>";
			 }
			 elseif ($select_result['format']=="html") 
			 {
				echo "<center>".stripslashes($select_result['banner_name'])."</center>";
			 }
			 elseif ($select_result['format']=="swf")
			 {
				$banner_types = explode("|", $select_result['type_id']);
				array_shift($banner_types);
				array_pop($banner_types);
				$type_selectSQL = "select * from $bx_db_table_banner_types where type_id='".$banner_types[0]."'";
				$type_select_query = bx_db_query($type_selectSQL);
				SQL_CHECK(0,"SQL Error at ".__FILE__.":".(__LINE__-1));
				$type_select_result = bx_db_fetch_array($type_select_query);

				?>
				<center><object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=4,0,0,0" width="<?=$type_select_result['width']?>" height="<?=$type_select_result['height']?>">
				<param name="movie" value="<?=HTTP_BANNERS.$select_result['banner_name']?>">
				<param name="quality" value="autohigh">
				<param name="bgcolor" value="#efefef">
				<embed quality="autohigh" bgcolor="#f0f0f0" width="120" height="300" type="application/x-shockwave-flash" pluginspage="http://www.macromedia.com/shockwave/download/index.cgi?P1_Prod_Version=ShockwaveFlash">
				</embed>
				</object></center>
				<?
			 }
			 else
			 {}
?>
			 </td>
		</tr>
		<tr>
			<td>
				<table align="center" border="0" cellspacing="0" cellpadding="0" width="100%" bgcolor="#ffffff">
				<tr>
					<td width="5%">
						<font size="2" face="helvetica, verdana, arial">#<?=$select_result['banner_id']?></font>
					</td>
					<td>
						&nbsp;&nbsp;
					</td>
					<td>
						<font size="2" face="helvetica, verdana, arial"><?php echo TEXT_VIEWED?>: <?=$select_result['views']?>
					</td>
					<td>
						<font size="2" face="helvetica, verdana, arial"><?php echo TEXT_CLICKED?>: <?=$select_result['clicks']?>
					</td>
					<td width="24%" align="center">
						<a href="delete_banner.php?banner_id=<?=$select_result['banner_id']?>&user_id=<?=$user_id?>" onClick="return confirm('<?php echo TEXT_CONFIRM_DELETE_THIS_BANNER?>');"><?php echo TEXT_DELETE?></a>&nbsp;&nbsp;&nbsp;&nbsp;
						<a href="restore_banner.php?banner_id=<?=$select_result['banner_id']?>&user_id=<?=$user_id?>"><?php echo TEXT_RESTORE_BANNER?></a>
					</td>
				</tr>
				</table>
			</td>
		</tr>
		<tr>
			<td colspan="2" bgcolor="#9FCBFF" height="1"></td>
		</tr>
<?
	}
	if (bx_db_num_rows($select_query) =='0')
	{
?>
<tr>
	<td align="center">
		<font size="3" color=""><?php echo TEXT_NO_BANNERS?></font>
	</td>
</tr>
<?
	}
?>
		</table>

	</td>
</tr>
</table>