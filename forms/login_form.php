<?php
include(DIR_LANGUAGES.$language.'/login_form.php');
?>
<table align="center" cellpadding="8" cellspacing="0" width="100%" bgcolor="#F2F8FF" border="0"  style="border: 1px solid #000000">
<tr>
	<td align="center" colspan="2"><font size="2">&nbsp;</font><?
if ($HTTP_GET_VARS['error']=='1')
{
	echo '<font size="2" color="#FF0000"><center><b>'.TEXT_LOGIN_ERROR.'</b></center></font><br>';
}
?></td>
</tr>
<tr>
	<td width="40%" align="right">
	<form method="post" action="<?php echo bx_make_url(HTTP_SERVER.'login_process.php', "auth_sess", $bx_session);?>" style="margin-top: 0px;margin-bottom: 0px;" name="login_form">
	<font face="verdana" size="2" color="#000000"><b><?php echo TEXT_USERNAME?>:</b></td>
	<td align="left"><input type="text" name="username" value="<?=$HTTP_COOKIE_VARS['pbm_loginuser'] && !$changepasswd ? $HTTP_COOKIE_VARS['pbm_loginuser'] : ""?>" size="30"></td>
</tr>
<tr>
	<td width="40%" align="right"><font face="verdana" size="2" color="#000000"><b><?php echo TEXT_PASSWORD?>:</b></td>
	<td align="left"><input type="password" name="password" value="" size="30"></td>
</tr>
<tr>
	<td colspan="2" align="center"><br><input type="submit" value="<?php echo TEXT_LOGIN?>"><br><br>
	</form>
	</td>
</tr>
<tr>
	<td><a href="<?=FILENAME_REGISTER?>" style="font-size:13px; font-weight:bold"><?php echo TEXT_REGISTER_FREE?></a></td>
	<td align="right"><a href="mailto:<?=SITE_MAIL?>" style="font-size:13px; font-weight:bold"><?php echo TEXT_CONTACT_US?></a></td>
</tr>
</table>