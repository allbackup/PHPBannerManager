<?
include('admin_design.php');
include('../application_config_file.php');
include('admin_auth.php');
include(DIR_LANGUAGES.$language."/".FILENAME_ADMIN_FORM);
include("header.php");
?>

<table border="0" cellpadding="0" cellspacing="0" width="<?=BIG_TABLE_WIDTH?>" align="center">
<tr>
	<td align="center"><font face="helvetica"><b>Crontab settings</b></font></td>
</tr>
</table>
<table border="0" cellpadding="0" cellspacing="0" width="<?=BIG_TABLE_WIDTH?>" align="center">
<tr>
	<td align="center"><font face="helvetica"><b><?=$page_title?><br><br></b></font></td>
</tr>
<tr>
	<td>
		<blockquote>Use "crontab -e" to edit crontab file or use Control Panel of your site. Write these lines into this file. Save file and advertiser emails will be generated automatically.</blockquote>
	</td>
</tr>
<tr>
	<td bgcolor="<?=TABLE_BORDERCOLOR?>" align="center"> 
	<form method=post action="<?=$this_file_name?>">
		<table width="<?=INSIDE_TABLE_WIDTH?>" border="0" cellspacing="1" cellpadding="2" align="center">
		<tr bgcolor="<?=TABLE_HEAD_BGCOLOR?>">
			<td align="center" colspan="2"><font color="<?=TABLE_HEAD_FONT_COLOR?>"><b>Copy these lines into linux crontab file</b></font></td>
		</tr>
		<tr bgcolor="<?=INSIDE_TABLE_BG_COLOR?>" valign="top" align="center">
			<td>
				<b>If php is installed as executable use these lines </b>
				<form method=post action="<?=$this_file_name?>">
<textarea name="message" rows="8" cols="120">10 0 * * * php <?=DIR_SERVER_ROOT?>cron.php.php >/dev/null
</textarea>
<br><br><b>If php is installed as a module use these lines</b> <br>
<textarea name="message" rows="8" cols="120">10 0 * * * lynx <?=HTTP_SERVER?>cron.php >/dev/null
</textarea>
				</form>
			</td>
		</tr>
		</table>
	</form>
	</td>
</tr>

</table>
<br>

<?
include (DIR_SERVER_ADMIN."footer.php");
?>