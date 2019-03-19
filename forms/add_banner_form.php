<?php
include(DIR_LANGUAGES.$language.'/add_banner_form.php');
?>
<table align="center" border="0" cellspacing="5" cellpadding="1" width="100%" bgcolor="#F2F8FF">
<tr>
	<td bgcolor="#9FCBFF">
		<table align="center" cellpadding="8" cellspacing="10" width="100%" bgcolor="#F2F8FF" border="0">

<?php

if ($HTTP_GET_VARS['banner_id'])
{
	$selectSQL = "select * from $bx_db_table_banner_banners where banner_id='".$HTTP_GET_VARS['banner_id']."'";
	$select_query = bx_db_query($selectSQL);
	SQL_CHECK(0,"SQL Error at ".__FILE__.":".(__LINE__-1));
	$banner_select_result= bx_db_fetch_array($select_query);

	foreach ($HTTP_POST_VARS as $key=>$value)
	{
			if (isset($value))
				$banner_select_result[$key] = $value;
			${$key} = $value;
	}
}

if($banner_select_result['type_id'] == '')
	$selectZoneSQL = "select * from $bx_db_table_banner_types where type_id='".$HTTP_GET_VARS['zid']."'";
else
	$selectZoneSQL = "select * from $bx_db_table_banner_types where type_id='".substr($banner_select_result['type_id'], 1, -1)."'";

$select_zone_query = bx_db_query($selectZoneSQL);
SQL_CHECK(0,"SQL Error at ".__FILE__.":".(__LINE__-1));
$zone_res = bx_db_fetch_array($select_zone_query);


if ($HTTP_GET_VARS['format'] == "image")
{
?>
<tr>
	<td colspan="2">
		<form method=post action="<?php echo bx_make_url('add_banner.php?format='.$HTTP_GET_VARS['format'].'&user_id='.$user_id.'&banner_id='.$HTTP_GET_VARS['banner_id'].'&zid='.$HTTP_GET_VARS['zid'], "auth_sess", $bx_session);?>" enctype="multipart/form-data" name="banner_form">
		<input type="hidden" name="image_insert" value="yes">
<?
if ($HTTP_GET_VARS['who'] == "admin")
{?>
			<input type="hidden" name="who" value="<?=$HTTP_GET_VARS['who']?>">
<?}?>
	</td>
</tr>
		
<?
	if ($banner_select_result['banner_id'])
	{
?>
<tr>
	<td colspan="2" align="center">
		<img src="<?=HTTP_BANNERS.$banner_select_result['banner_name']?>" border="0" alt="">
	</td>
</tr>
<?
	}
}
elseif ($HTTP_GET_VARS['format'] == "swf")
{
?>
<tr>
	<td colspan="2">
		
		<table align="center" border="0" cellspacing="0" cellpadding="0" width="70%">
		<tr>
			<td>
				<font size="2" face="helvetica, verdana, arial"><?php echo TEXT_FLASH_ATTENTION?>
			</td>
		</tr>
			<tr>
				<td><font size="2" face="helvetica, verdana, arial" color="#6600FF"><b>
					<?php echo TEXT_FLASH_REQUIRE_CODE?>
				</td>
			</tr>
		</table>
	
		</font>
		
	</td>
</tr>
<tr>
	<td colspan="2">
		<form method=post action="<?php echo bx_make_url('add_banner.php?format='.$HTTP_GET_VARS['format'].'&user_id='.$user_id.'&banner_id='.$HTTP_GET_VARS['banner_id'].'&zid='.$HTTP_GET_VARS['zid'], "auth_sess", $bx_session);?>" enctype="multipart/form-data" name="banner_form">
			<input type="hidden" name="image_insert" value="yes">
	</td>
</tr>
<?
	if ($HTTP_GET_VARS['banner_id'])
	{
			$banner_types = explode("|", $banner_select_result['type_id']);
			array_shift($banner_types);
			array_pop($banner_types);
			$select_type_SQL = "select * from $bx_db_table_banner_types where type_id='".$banner_types[0]."'";
			$select_type_query = bx_db_query($select_type_SQL);
			SQL_CHECK(0,"SQL Error at ".__FILE__.":".(__LINE__-1));
			$select_type_result = bx_db_fetch_array($select_type_query);
?>
<tr>
	<td colspan="2" align="center">
		<center><object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=4,0,0,0" width="<?=$select_type_result['width']?>" height="<?=$select_type_result['height']?>">
		<param name="movie" value="<?=HTTP_BANNERS.$banner_select_result['banner_name']?>">
		<param name="quality" value="autohigh">
		<param name="bgcolor" value="#efefef">
		<embed quality="autohigh" bgcolor="#f0f0f0" src="<?=HTTP_BANNERS.$banner_select_result['banner_name']?>"  width="<?=$select_type_result['width']?>" height="<?=$select_type_result['height']?>" type="application/x-shockwave-flash" pluginspage="http://www.macromedia.com/shockwave/download/index.cgi?P1_Prod_Version=ShockwaveFlash">
		</embed>
		</object></center>
	</td>
</tr>
<?
	}
}
if($HTTP_GET_VARS['format'] == "image" || $HTTP_GET_VARS['format'] == "swf")
{
?>
<tr>
	<td width="30%" align="right">
		<b><font face="verdana" size="2" color="#000000"><?php echo TEXT_NEW_BANNER_FILE?></font></b>
	</td>
	<td align="center">
		<input type="file" name="banner_file" size="30">
		<br>
		<?
if ($image_size_error=='1')
{
?>
<b><font size="2" color="#FF0000"><?php echo TEXT_BANNER_REQUIRE?> <?php  echo " ".$zone_res['width']." X ".$zone_res['height'] ." ";?>	<?$HTTP_POST_VARS['width'] . " x " . $HTTP_POST_VARS['height'] ?> <?php echo TEXT_SMALLER_THAN?> <?php echo $zone_res['filesize'];?> <?php echo TEXT_KBYTE?></font></b>
<?}?>
	</td>
</tr>
<tr>
	<td width="30%" align="right">
		<b><font face="verdana" size="2" color="#000000"><?php echo TEXT_LINK_TO_URL?></font></b>
	</td>
	<td align="center">
		<input type="text"  size="40" name="url" value="<?=$banner_select_result['url']?>" class="">
		<br>
<?
if ($url_error=='1')
{
?>
	<b><font size="2" color="#FF0000"><?php echo TEXT_ERROR_LINK?></font></b>
<?}?>
	</td>
</tr>
<?
if($HTTP_GET_VARS['format']=="image")
{
?>
<tr>
	<td width="30%" align="right">
		<b><font face="verdana" size="2" color="#000000"><?php echo TEXT_BANNER_ALT?></font></b>
	</td>
	<td align="center">
		<input type="text" size="40" name="alt" value="<?=$banner_select_result['alt']?>" class="">
	</td>
</tr>
<?php }
}// end image
elseif ($HTTP_GET_VARS['format'] == "html")
{
	if ($banner_select_result['banner_id'])
	{
?>
<tr>
	<td colspan="2" align="center">
		<?=stripslashes($banner_select_result['banner_name'])?>
	</td>
</tr>
<?
	}
?>		
<tr>
	<td width="30%" align="right">
		<form method=post action="<?php echo bx_make_url('add_banner.php?format='.$HTTP_GET_VARS['format'].'&user_id='.$user_id.'&banner_id='.$HTTP_GET_VARS['banner_id'].'&zid='.$HTTP_GET_VARS['zid'], "auth_sess", $bx_session);?>" name="banner_form">
		<input type="hidden" name="html_insert" value="yes">
		<b><font face="verdana" size="2" color="#000000"><?php echo TEXT_HTML?></font></b>
	</td>
	<td align="center">
		<textarea name="html_source" rows="10" cols="60"><?=stripslashes($banner_select_result['banner_name'])?></textarea>
		<br>
<?
if ($html_source_error=='1')
{?>
	<b><font size="2" color="#FF0000"><?php echo TEXT_ERROR_HTML_CODE?></font></b>
<?}?>
	</td>
</tr>
<?
}//end html


elseif ($HTTP_GET_VARS['format'] == "remote")
{
	if ($banner_select_result['banner_id'])
	{
?>
<tr>
	<td colspan="2" align="center">
	<?php
		/*if (substr($HTTP_GET_VARS['format'],-1) == "1") //remote image banner
		{?>
			<!-- <img src="<?=stripslashes($banner_select_result['banner_name'])?>" border="0"> -->
		<?/*
		}elseif (substr($HTTP_GET_VARS['format'],-1) == "2") //remote swf banner
		{
			
		}else
		{
			
		} */
	?>
		
	</td>
</tr>
<?
	}
?>		
<tr>
	<td width="40%" align="right" valign="top">
		<form method=post action="<?php echo bx_make_url('add_banner.php?format='.$HTTP_GET_VARS['format'].'&user_id='.$user_id.'&banner_id='.$HTTP_GET_VARS['banner_id'].'&zid='.$HTTP_GET_VARS['zid'], "auth_sess", $bx_session);?>" name="banner_form">
		<input type="hidden" name="remote_insert" value="yes">
		<b><font face="verdana" size="2" color="#000000"><?php echo TEXT_REMOTE_URL?></font></b>
	</td>
	<td align="left"><input type="text" name="banner_name" value="<?=($HTTP_POST_VARS['banner_name'] ? $HTTP_POST_VARS['banner_name'] : $banner_select_result['banner_name'])?>" class="" size="40">

<?
if ($remote_banner_url_error=='1')
{?>
	<b><font size="2" color="#FF0000"><?php echo TEXT_ERROR_REMOTE_BANNER_URL?></font></b>
<?}?>
	</td>
</tr>
<tr>
	<td align="right">
		<b><font face="verdana" size="2" color="#000000"><?php echo TEXT_LINK_TO_URL?></font></b>
	</td>
	<td align="left">
	
        <input type="text"  size="40" name="url" value="<?=($HTTP_POST_VARS['url'] ? $HTTP_POST_VARS['url'] : $banner_select_result['url'])?>" class="">
		<br>
<?
if ($linked_url_error=='1')
{
?>
	<b><font size="2" color="#FF0000"><?php echo TEXT_ERROR_REMOTE_LINK?></font></b>
<?}?>
	</td>
</tr>
<?php
	/*if (substr($HTTP_GET_VARS['format'],-1) != "0" and substr($HTTP_GET_VARS['format'],-1) != "1" and substr($HTTP_GET_VARS['format'],-1) != "2")
	{
		$select_type="1";
	} else
	{
		$select_type=substr($HTTP_GET_VARS['format'],-1);
	}*/
    
    $select_type = $HTTP_POST_VARS['remote_banner_type'] ? $HTTP_POST_VARS['remote_banner_type'] : $banner_select_result['alt'];
?>
<tr>
	<td align="right"><b><font face="verdana" size="2" color="#000000"><?php echo TEXT_REMOTE_BANNER_TYPE?></font></b></td>
	<td ><font face="verdana" size="2" color="#000000">
        
        <input type="radio" class="radio" name="remote_banner_type" value="image" <?php echo (($select_type=="image")?"checked":""); ?>> <?php echo TEXT_IMAGE; ?><br>
        
        <input type="radio" class="radio" name="remote_banner_type" value="swf" <?php echo (($select_type=="swf")?"checked":""); ?>> <?php echo TEXT_SWF; ?>
        
        </font></td>
</tr>
<?
    if ($remote_banner_type_error=='1')
    {?>
    <tr valign="top">
    <td>
    	&nbsp;
    </td>
    <td>
	<b><font size="2" color="#FF0000"><?php echo TEXT_ERROR_REMOTE_TYPE?></font></b>
    </td>
    </tr>
    <?
    }

}//end remote


?>
<tr>
	<td width="30%" align="right">
		<b><font face="verdana" size="2" color="#000000"><?php echo TEXT_ZONE_NAME?></font></b> <br><font size="-1"></font>
	</td>
	<td align="center">
		<?
			echo $zone_res['typename'.$slng]." (".$zone_res['width']." X ".$zone_res['height'] .")";
		?>
		<input type="hidden" name="banner_type" value="<?php echo $zone_res['type_id'];?>">
	</td>
</tr>
<tr><td colspan="2" bgcolor="#9FCBFF" height="1"></td></tr>
<?php if(is_admin())
{
?>
<tr>
	<td width="30%" align="right">
		<b><font face="verdana" size="2" color="#000000"><?php echo TEXT_BANNER_WEIGHT?></b><br> <?php echo TEXT_EXPLAIN_WIGHT ?></font>
	</td>
	<td align="center">
		<select name="banner_weight">
			<?=generate_weight($banner_select_result['weight'])?>
		</select>
	</td>
</tr>
<tr>
	<td colspan="2" bgcolor="#9FCBFF" height="1"></td>
</tr>
<?php }?>
<?
if (!$HTTP_GET_VARS['banner_id'])
{
?>
<tr>
	<td colspan="2" align="center">
		<input type="submit" name="submit" value="<?php echo TEXT_SUBMIT;?>" class="">
	</td>
</tr>
<?
}
else
{
?>
<tr>
	<td colspan="2" align="center">
		<input type="hidden" name="banner_id" value="<?=$HTTP_GET_VARS['banner_id']?>">
		<input type="submit" name="submit" value="<?php echo TEXT_UPDATE;?>" class="">
	</td>
</tr>
<?
}
?>
<tr>
	<td colspan="2">
		</form>
	</td>
</tr>
</table>

</td>
</tr>
</table>