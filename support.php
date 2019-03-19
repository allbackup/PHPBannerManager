<?php
include ('application_config_file.php');
include(DIR_LANGUAGES.$language."/".'support.php');
include(DIR_SERVER_ROOT."header.php");
if($HTTP_SESSION_VARS['employerid'])
{
if ($HTTP_POST_VARS['todo']=="support") {
$error = "no";
        if(empty($HTTP_POST_VARS['email']) || (!eregi("(@)(.*)",$HTTP_POST_VARS['email'],$regs)) || (!eregi("([.])(.*)",$HTTP_POST_VARS['email'],$regs))) {
                $email_error="yes";
                $error="yes";
        }
        else {
                $email_error="no";
        }
        if(empty($HTTP_POST_VARS['subject'])) {
                $subject_error="yes";
                $error="yes";
        }
        else {
                $subject_error="no";
        }
        if(empty($HTTP_POST_VARS['sname'])) {
                $name_error="yes";
                $error="yes";
        }
        else {
                $name_error="no";
        }
        if(empty($HTTP_POST_VARS['message'])) {
                $message_error="yes";
                $error="yes";
        }
        else {
                $message_error="no";
        }
        if ($error=="no") {
                $mmessage="Support need by ".$HTTP_POST_VARS['sname'].".\n";
				if (!$HTTP_POST_VARS['support_need']) {
				    $HTTP_POST_VARS['support_need'] = "not logged";
				}
				$mmessage= $HTTP_POST_VARS['sname']." was logged in as: ".$HTTP_POST_VARS['support_need'].".\n";
                $mmessage.="Support subject \"".stripslashes($HTTP_POST_VARS['subject'])."\".\n";
                $mmessage.="Reply email address ".$HTTP_POST_VARS['email'].".\n";
                $mmessage.="Message:\n";
                $mmessage.="\n".stripslashes($HTTP_POST_VARS['message']);
                @mail(SITE_MAIL, "Support: ".$HTTP_POST_VARS['subject'] , $mmessage, "From: ".$HTTP_POST_VARS['sname']."<".$HTTP_POST_VARS['email'].">");
                ?>
            <table border="0" cellpadding="0" cellspacing="0" align="center" width="100%">
            <tr>
                  <td colspan="3">&nbsp;</td>
            </tr>
            <tr>
                  <td colspan="3" align="center"><font face="Arial" color="#00009C" size="2"><b><i><?php echo TEXT_DEAR;?> <?php echo $HTTP_POST_VARS['sname'];?></i></b></font></td>
            </tr>
            <tr>
                  <td colspan="3">&nbsp;</td>
            </tr>
            <tr>
                  <td colspan="3"><font face="Arial" color="#000000" size="2"><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo TEXT_SUPPORT_EMAIL_SENT;?><a href="mailto:<?php echo SITE_MAIL;?>"><font color="red" size="2"><b><?php echo SITE_MAIL;?></b></font></a>.</b></font></td>
            </tr>
            <tr>
                  <td colspan="3"><font face="Arial" color="#000000" size="2">&nbsp;&nbsp;<b><?php echo TEXT_SUBJECT_WAS;?><font color="#000000" size="2">"<?php echo $HTTP_POST_VARS['subject'];?>"</font>.</b></font></td>
            </tr>
            <tr>
                  <td colspan="3"><font face="Arial" color="#000000" size="2">&nbsp;&nbsp;<b><?php echo TEXT_YOUR_EMAIL;?><font color="#000000" size="2">"<?php echo $HTTP_POST_VARS['email'];?>"</font>.</b></font></td>
            </tr>
            <tr>
                  <td colspan="3"><font face="Arial" color="#000000" size="2">&nbsp;&nbsp;<b><?php echo TEXT_SENT_WAS;?></b></font></td>
            </tr>
            <tr>
                  <td width="20%"><font face="Arial" color="#000000" size="2"><b>&nbsp;</b></font></td>
                  <td class="td4textarea" nowrap><font face="Arial" color="#000000" size="1"><b><?php echo bx_textarea($HTTP_POST_VARS['message']);?></b></font></td>
				  <td width="20%">&nbsp;</td>
            </tr>
            <tr>
                  <td colspan="3">&nbsp;</td>
            </tr>
            <tr>
                  <td colspan="3" align="right"><font face="Arial" color="#00009C" size="2"><b><i><?php echo TEXT_THANK_YOU;?></i></b></font></td>
            </tr>
            <tr>
                  <td colspan="3">&nbsp;</td>
            </tr>
            </table>
<?php
}
else {				 
	include(DIR_FORMS.FILENAME_SUPPORT_FORM);
}
}
else {
	include(DIR_FORMS.FILENAME_SUPPORT_FORM);
}
}
else
{
	include(DIR_FORMS."header.php");
	include(DIR_FORMS.FILENAME_LOGIN_FORM);
} //end else
include(DIR_SERVER_ROOT."footer.php");
?>