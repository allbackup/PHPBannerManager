<?
include('application_config_file.php');
include(DIR_LANGUAGES.$language."/".FILENAME_USERS);
include (DIR_SERVER_ROOT."header.php");
?>
<table align="center" cellpadding="8" cellspacing="0" width="100%" bgcolor="#F2F8FF" border="0"  style="border: 1px solid #000000">
<tr>
	<td align="center" colspan="2">
<?
$database_table_name = $bx_db_table_banner_users;
$this_file_name = HTTP_SERVER.basename($_SERVER['PHP_SELF']);

$nr_of_cols = 5;

if ($HTTP_POST_VARS['submit'] || $HTTP_POST_VARS['joke_form'])
{
	//email, email (username), password verification
	$select_entry_SQL = "select * from $database_table_name where username='".$HTTP_POST_VARS['username']."'";
	$select_entry_query = bx_db_query($select_entry_SQL);
	SQL_CHECK(0,"SQL Error at ".__FILE__.":".(__LINE__-1));	
	if (bx_db_num_rows($select_entry_query)==0 and $HTTP_POST_VARS['name']!='' and $HTTP_POST_VARS['username']!='' and $HTTP_POST_VARS['password'] and (eregi("(@)(.*)",$HTTP_POST_VARS['username'],$regs)))
	{
		bx_db_insert($database_table_name, "name, username, password, without_review, phone, fax", "'".$HTTP_POST_VARS['name']."', '".$HTTP_POST_VARS['username']."', '".$HTTP_POST_VARS['password']."', 'no', '".$HTTP_POST_VARS['phone']."', '".$HTTP_POST_VARS['fax']."'");
		SQL_CHECK(0,"SQL Error at ".__FILE__.":".(__LINE__-1));
		echo $message = '<font size="3" color="#FF9900"><b>'.TEXT_REGISTER_SUCCESS.'</b></font>';
		include(DIR_LANGUAGES.$language.'/login_form.php');
		include(DIR_FORMS.'/login_form.php');
		include (DIR_SERVER_ROOT."footer.php");	
		exit;

	}else
		$message = TEXT_REGISTER_UNSUCCESS;
}
else{}

?>
<table border="0" cellpadding="0" cellspacing="0" width="100%" align="center">
	<script language="JavaScript"><!--
        function formCheck(form) {
			if (form.username.value == "") 
            {
             alert("<?php echo TEXT_ALERT_USERNAME; ?>");
             return false;
			}
			if (form.password.value == "") 
            {
             alert("<?php echo TEXT_ALERT_PASSWORD; ?>");
             return false;
			}
			if (form.name.value == "") 
            {
             alert("<?php echo TEXT_ALERT_NAME; ?>");
             return false;
			}
        } 
        // --></script>	
<tr>
	<td>
<?

/***************************************************************************/
//Form is here
/***************************************************************************/
?>
<form method="post" action="<?php 
echo bx_make_url(HTTP_SERVER.basename($HTTP_SERVER_VARS['PHP_SELF']).'?'.$primary_id_name.'='.$HTTP_GET_VARS[$primary_id_name].'&from='.(isset($from1) ? $from1 : '0').'&display_nr='.$HTTP_GET_VARS['display_nr'].($HTTP_GET_VARS['action']=='add' ? '' : '&order_by='.$HTTP_GET_VARS['order_by'].'&order_type='.$HTTP_GET_VARS['order_type'].'&search='.$search), "auth_sess", $bx_session);?>" name="form1" onSubmit="return formCheck(this)" enctype="multipart/form-data">
<input type="hidden" name="joke_form" value="1">
<table align="center" border="0" cellspacing="0" cellpadding="1" width="90%">
<?php
if($message) 
{?>
<tr>
	<td align="center" colspan="2">
		<?php echo bx_msg($message);?><br>
	</td>
</tr>
<?}?>

<tr>
	<td bgcolor="<?php //echo TABLE_BORDERCOLOR;?>">
		<table border="0" cellpadding="4" cellspacing="0" align="center" width="100%">
		<TR>
			<TD colspan="2" width="100%" align="center" class="headertdjob"><font face="<?php echo TEXT_FONT_FACE;?>" size="<?php echo TEXT_FONT_SIZE;?>" color="<?php echo TEXT_FONT_COLOR;?>"><b><?php echo SITE_NAME." - ".TEXT_REGISTER;?></b></font></TD>
		</TR>
		<TR>
			<TD colspan="2" width="100%" align="center">&nbsp;<br><br></TD>
		</TR>
		<tr valign="top">
			<td align="right" width="40%"><font face="<?php echo TEXT_FONT_FACE;?>" size="<?php echo TEXT_FONT_SIZE;?>" color="<?php echo TEXT_FONT_COLOR;?>"><b>&nbsp;&nbsp;<?php echo TEXT_REQUIRED_STAR; ?><?php echo TEXT_REG_USERNAME; ?>:</b>&nbsp;&nbsp;</font></td>
			<td width="60%"><INPUT type="text" name="username" size="30" value="<?php echo $HTTP_POST_VARS['username'];?>" class="input"></td>
		</tr>
		<tr valign="top">
			<td class="lila_bold_text" align="right" width="30%"><b>&nbsp;&nbsp;<?php echo TEXT_REQUIRED_STAR; ?><font face="<?php echo TEXT_FONT_FACE;?>" size="<?php echo TEXT_FONT_SIZE;?>" color="<?php echo TEXT_FONT_COLOR;?>"><?php echo TEXT_REG_PASSWORD; ?>:</b>&nbsp;&nbsp;</font></td>
			<td width="70%"><INPUT type="text" name="password" size="30" value="<?php echo $HTTP_POST_VARS['password'];?>" class="input"></td>
		</tr>
		<tr valign="top">
			<td class="lila_bold_text" align="right" width="30%"><b>&nbsp;&nbsp;<?php echo TEXT_REQUIRED_STAR; ?><font face="<?php echo TEXT_FONT_FACE;?>" size="<?php echo TEXT_FONT_SIZE;?>" color="<?php echo TEXT_FONT_COLOR;?>"><?php echo TEXT_REG_NAME; ?>:</b>&nbsp;&nbsp;</font></td>
			<td width="70%"><INPUT type="text" name="name" size="30" value="<?php echo $HTTP_POST_VARS['name'];?>" class="input"></td>
		</tr>
		<tr valign="top">
			<td class="lila_bold_text" align="right" width="30%"><b>&nbsp;&nbsp;<font face="<?php echo TEXT_FONT_FACE;?>" size="<?php echo TEXT_FONT_SIZE;?>" color="<?php echo TEXT_FONT_COLOR;?>"><?php echo TEXT_REG_PHONE; ?>:</b>&nbsp;&nbsp;</font></td>
			<td width="70%"><INPUT type="text" name="phone" size="30" value="<?php echo $HTTP_POST_VARS['phone'];?>" class="input"></td>
		</tr>
		<tr valign="top">
			<td class="lila_bold_text" align="right" width="30%"><b>&nbsp;&nbsp;<font face="<?php echo TEXT_FONT_FACE;?>" size="<?php echo TEXT_FONT_SIZE;?>" color="<?php echo TEXT_FONT_COLOR;?>"><?php echo TEXT_REG_FAX; ?>:</b>&nbsp;&nbsp;</font></td>
			<td width="70%"><INPUT type="text" name="fax" size="30" value="<?php echo $HTTP_POST_VARS['fax'];?>" class="input"></td>
		</tr>
		<tr>
			<td align="center" colspan="2"><br><br><input type="submit" name="submit" value="<?php echo TEXT_SUBMIT; ?>" class="button"></td>
		</tr>
		</table>
	</td>
</tr>
</table>
</form>
<!--**************************************************************************************************-->
	</td>
</tr>

</table>
<br>
		</td>
	</tr>
	</table>
<?php
include (DIR_SERVER_ROOT."footer.php");
?>