<?
include('application_config_file.php');
include(DIR_LANGUAGES.$language."/".FILENAME_USERS);
include(DIR_SERVER_ROOT."header.php");
if (!$HTTP_SESSION_VARS['employerid']) 
{
	include(DIR_FORMS.FILENAME_LOGIN_FORM);
	include(DIR_SERVER_ROOT."footer.php");
	exit;
}

$database_table_name = $bx_db_table_banner_users;
$this_file_name = HTTP_SERVER.basename($_SERVER['PHP_SELF']);

$nr_of_cols = 5;

//echo "<br>".
$selectEmailSQL = "select * from $database_table_name where username='".$HTTP_POST_VARS['username']."'";
$select_email_query = bx_db_query($selectEmailSQL);
SQL_CHECK(0,"SQL Error at ".__FILE__.":".(__LINE__-1));
//postvars($HTTP_POST_VARS, "<b>\$HTTP_POST_VARS[ ]</b>");
//echo bx_db_num_rows($select_email_query);
$email_res = bx_db_fetch_array($select_email_query);
//((bx_db_num_rows($select_email_query)==1 && $email_res['username']==$HTTP_POST_VARS['username']) || bx_db_num_rows($select_email_query)==0)

if ($HTTP_POST_VARS['user_form'] && $email_res['user_id']==$HTTP_SESSION_VARS['pbm_userid'])
{
	$updateSQL = "update $database_table_name set name='".$HTTP_POST_VARS['name']."', username='".$HTTP_POST_VARS['username']."', password='".$HTTP_POST_VARS['password']."', phone='".$HTTP_POST_VARS['phone']."', fax='".$HTTP_POST_VARS['fax']."', report='".(isset($HTTP_POST_VARS['report']) ? $HTTP_POST_VARS['report'] : '0' )."' where user_id='".$HTTP_SESSION_VARS['employerid']."'";
	$update_query = bx_db_query($updateSQL);
	SQL_CHECK(0,"SQL Error at ".__FILE__.":".(__LINE__-1));

	$message = TEXT_UPDATE_SUCCESS;
}
elseif ($HTTP_POST_VARS['user_form'] &&  $email_res['user_id']!=$HTTP_SESSION_VARS['pbm_userid'])
{
	$message = TEXT_EMAIL_ALREADY_EXIST;
} //end elseif
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
	$select_entry_SQL = "select * from $database_table_name where user_id='".$HTTP_SESSION_VARS['employerid']."'";
	$select_entry_query = bx_db_query($select_entry_SQL);
	SQL_CHECK(0,"SQL Error at ".__FILE__.":".(__LINE__-1));	
	$entry_result = bx_db_fetch_array($select_entry_query);
/***************************************************************************/
//Form is here
/***************************************************************************/
?>
<form method="post" action="<?php echo bx_make_url(HTTP_SERVER.basename($HTTP_SERVER_VARS['PHP_SELF']).'?'.$primary_id_name.'='.$HTTP_GET_VARS[$primary_id_name].'&from='.(isset($from1) ? $from1 : '0').'&display_nr='.$HTTP_GET_VARS['display_nr'].($HTTP_GET_VARS['action']=='add' ? '' : '&order_by='.$HTTP_GET_VARS['order_by'].'&order_type='.$HTTP_GET_VARS['order_type'].'&search='.$search), "auth_sess", $bx_session);?>" name="form1" onSubmit="return formCheck(this)" enctype="multipart/form-data">
<input type="hidden" name="user_form" value="1">

<table bgcolor="<?php echo TABLE_BGCOLOR;?>" width="100%" border="0" cellspacing="0" cellpadding="2">
<TR bgcolor="#FFFFFF">
		<TD colspan="2" width="100%" align="center" class="headertdjob"><?php echo TEXT_EDIT_INFO;?></TD>
</TR>
<TR><TD colspan="2"><table cellpadding="0" cellspacing="0" border="0" width="100%">
		<tr>
			<td bgcolor="<?php echo TABLE_HEADING_BGCOLOR;?>" height="1"><?php echo bx_image_width(HTTP_IMAGES.$language."/pix-t.gif",1,1,0,"");?></td>
			</tr>
		</table></TD>
</TR>
</table>

<table align="center" border="0" cellspacing="0" cellpadding="1" width="90%">
<tr>
	<td bgcolor="<?php //echo TABLE_BORDERCOLOR;?>">
		<table border="0" cellpadding="4" cellspacing="0" align="center" bgcolor="<?php //echo INSIDE_TABLE_BG_COLOR2;?>" width="100%">

		<TR bgcolor="#FFFFFF">
			<TD colspan="2" width="100%" align="center">&nbsp;<br></TD>
		</TR>
<?php
if($message) 
{?>
<tr>
	<td align="center" colspan="2">
		<?php echo bx_msg($message);?><br>
	</td>
</tr>
<?}?>
		<tr valign="top">
			<td align="right" width="30%"><font face="<?php echo TEXT_FONT_FACE;?>" size="<?php echo TEXT_FONT_SIZE;?>" color="<?php echo TEXT_FONT_COLOR;?>"><b>&nbsp;&nbsp;<?php echo TEXT_REQUIRED_STAR; ?><?php echo TEXT_REG_USERNAME; ?>:</b>&nbsp;&nbsp;</font></td>
			<td width="70%"><INPUT type="text" name="username" size="30" value="<?php echo $entry_result['username'];?>" class="input"></td>
		</tr>
		<tr valign="top">
			<td class="lila_bold_text" align="right" width="30%"><b>&nbsp;&nbsp;<?php echo TEXT_REQUIRED_STAR; ?><font face="<?php echo TEXT_FONT_FACE;?>" size="<?php echo TEXT_FONT_SIZE;?>" color="<?php echo TEXT_FONT_COLOR;?>"><?php echo TEXT_REG_PASSWORD; ?>:</b>&nbsp;&nbsp;</font></td>
			<td width="70%"><INPUT type="text" name="password" size="30" value="<?php echo $entry_result['password'];?>" class="input"></td>
		</tr>
		<tr valign="top">
			<td class="lila_bold_text" align="right" width="30%"><b>&nbsp;&nbsp;<?php echo TEXT_REQUIRED_STAR; ?><font face="<?php echo TEXT_FONT_FACE;?>" size="<?php echo TEXT_FONT_SIZE;?>" color="<?php echo TEXT_FONT_COLOR;?>"><?php echo TEXT_REG_NAME; ?>:</b>&nbsp;&nbsp;</font></td>
			<td width="70%"><INPUT type="text" name="name" size="30" value="<?php echo $entry_result['name'];?>" class="input"></td>
		</tr>
		<tr valign="top">
			<td class="lila_bold_text" align="right" width="30%"><b>&nbsp;&nbsp;<font face="<?php echo TEXT_FONT_FACE;?>" size="<?php echo TEXT_FONT_SIZE;?>" color="<?php echo TEXT_FONT_COLOR;?>"><?php echo TEXT_REG_PHONE; ?>:</b>&nbsp;&nbsp;</font></td>
			<td width="70%"><INPUT type="text" name="phone" size="30" value="<?php echo $entry_result['phone'];?>" class="input"></td>
		</tr>
		<tr valign="top">
			<td class="lila_bold_text" align="right" width="30%"><b>&nbsp;&nbsp;<font face="<?php echo TEXT_FONT_FACE;?>" size="<?php echo TEXT_FONT_SIZE;?>" color="<?php echo TEXT_FONT_COLOR;?>"><?php echo TEXT_REG_FAX; ?>:</b>&nbsp;&nbsp;</font></td>
			<td width="70%"><INPUT type="text" name="fax" size="30" value="<?php echo $entry_result['fax'];?>" class="input"></td>
		</tr>
		<tr valign="top">
			<td class="lila_bold_text" align="right"><b>&nbsp;&nbsp;<font face="<?php echo TEXT_FONT_FACE;?>" size="<?php echo TEXT_FONT_SIZE;?>" color="<?php echo TEXT_FONT_COLOR;?>"><?php echo TEXT_RECEIVE_ADV_EMAIL;?> </b></font>
			</td>
			<td>
				<table border="0" cellspacing="0" cellpadding="0">
				<tr>
					<td>
						<input type="radio" name="report" value="0" class="noborder" <?=$entry_result['report']=='0' ? " checked" : ""?>><?php echo TEXT_BX_NONE;?><br>
						<input type="radio" name="report" value="1" class="noborder" <?=$entry_result['report']=='1' ? " checked" : ""?>><?php echo TEXT_BX_DAILY;?><br>
						<input type="radio" name="report" value="2" class="noborder"<?=$entry_result['report']=='2' ? " checked" : ""?>><?php echo TEXT_BX_WEEKLY;?><br>
						<input type="radio" name="report" value="3" class="noborder"<?=$entry_result['report']=='3' ? " checked" : ""?>><?php echo TEXT_BX_MONTHLY;?><br>
					</td>
				</tr>
				</table>
			</td>
		</tr>
		<tr>
			<td align="center" colspan="2"><br><br><input type="submit" name="submit" value="<?php echo TEXT_UPDATE; ?>" class="button"></td>
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
<?php
include('footer.php'); 
?>