<?php
if (($HTTP_POST_VARS['todo']=="bulkmail") && ($HTTP_POST_VARS['mail']=="companies")) {
		include(DIR_ADMIN."employer_bulkmail.cfg.php");
        if(ALLOW_HTML_MAIL=="yes") {
            $mail_message_html_header = fread(fopen(DIR_LANGUAGES.$language."/html/html_email_message_header.html","r"),filesize(DIR_LANGUAGES.$language."/html/html_email_message_header.html"));
            $mail_message_html_footer = fread(fopen(DIR_LANGUAGES.$language."/html/html_email_message_footer.html","r"),filesize(DIR_LANGUAGES.$language."/html/html_email_message_footer.html"));
            $bulk_html_header = $HTTP_POST_VARS['add_html_header'];
            $bulk_html_footer = $HTTP_POST_VARS['add_html_footer'];
            $mail_html_message = $HTTP_POST_VARS['html_mail_message'];
        }
        $add_mail_signature = $HTTP_POST_VARS['add_mail_signature'];
        $mail_text_message = $HTTP_POST_VARS['bulk_message'];
        $mail_subject = $HTTP_POST_VARS['bulk_subject'];
        $html_mail = $HTTP_POST_VARS['bulk_type'];
		//$company_query=bx_db_query("select * from ".$bx_table_prefix."_companies");
		$company_query=bx_db_query("select * from ".$bx_db_table_banner_users);
		SQL_CHECK(0,"SQL Error at ".__FILE__.":".(__LINE__-1));
		while ($company_result=bx_db_fetch_array($company_query)) {       
          if(ADMIN_SAFE_MODE == "yes") {
              echo 'Mail was not sent to '.$company_result['email'].".".TEXT_SAFE_MODE_ALERT."<br>";
          }
          else {
               if($company_result['cmail_type'] == "html" && ALLOW_HTML_MAIL=="yes") {
                   $mail_message = $mail_html_message;
               }
               else {
                   $mail_message = $mail_text_message;
               }
               reset($fields);
               while (list($h, $v) = each($fields)) {
                   $mail_message = eregi_replace($v[0],$company_result[$h],$mail_message);
                   $mail_subject = eregi_replace($v[0],$company_result[$h],$mail_subject);
               }
               if($company_result['cmail_type'] == "html" && ALLOW_HTML_MAIL=="yes") {
                         if ($bulk_html_header == "on") {
                             $mail_message = $mail_message_html_header.$mail_message;
                         } 
                         if ($bulk_html_footer == "on") {
                             $mail_message .= $mail_message_html_footer;
                         } 
                         $html_mail = "yes";                                
               }
               else {
                         if ($add_mail_signature == "on") {
                             $mail_message .= "\n".SITE_SIGNATURE;
                         }
                         $mail_message = bx_unhtmlspecialchars($mail_message);
               } 
               
			  if (eregi('(.*)@(.*)\.(.*)',$company_result['username']))
			  {
			 	bx_mail(SITE_NAME,SITE_MAIL,$company_result['username'],stripslashes($mail_subject),stripslashes($mail_message),$html_mail);
                echo 'Mail was sent to '.$company_result['username']."<br>";
			  } 
          }
      }
}
elseif($HTTP_POST_VARS['todo']=="preview"){
    if($HTTP_POST_VARS['lng']) {
                if($HTTP_POST_VARS['t_mail'] == "companies") {
                    include(DIR_ADMIN."employer_bulkmail.cfg.php");
                }
                $admin_mail_message = stripslashes($HTTP_POST_VARS['test_html_mail_message']);
                $admin_add_html_header = $HTTP_POST_VARS['test_add_html_header'];
                $admin_add_html_footer = $HTTP_POST_VARS['test_add_html_footer'];
                reset($fields);
                while (list($h, $v) = each($fields)) {
                           $admin_mail_message = eregi_replace($v[0],$v[2],$admin_mail_message);
                           $admin_mail_subject = eregi_replace($v[0],$v[2],$admin_mail_subject);
                           $admin_mail_header = eregi_replace($v[0],$v[2],$admin_mail_header);
                           $admin_mail_footer = eregi_replace($v[0],$v[2],$admin_mail_footer);
                }
                if ($admin_add_html_header == "on") {
                    $admin_mail_message = fread(fopen(DIR_LANGUAGES.$HTTP_POST_VARS['lng']."/html/html_email_message_header.html","r"),filesize(DIR_LANGUAGES.$HTTP_POST_VARS['lng']."/html/html_email_message_header.html")).$admin_mail_message;
                }
                if ($admin_add_html_footer == "on") {
                    $admin_mail_message .= fread(fopen(DIR_LANGUAGES.$HTTP_POST_VARS['lng']."/html/html_email_message_footer.html","r"),filesize(DIR_LANGUAGES.$HTTP_POST_VARS['lng']."/html/html_email_message_footer.html"));
                }
                echo $admin_mail_message;
      }
      else {
          bx_admin_error("Please select a language to edit!");
      }
}
else if ($HTTP_POST_VARS['todo']=="test_mail") {
    if($HTTP_POST_VARS['t_mail'] == "companies") {
        include(DIR_ADMIN."employer_bulkmail.cfg.php");
    }
    $admin_mail_message = $HTTP_POST_VARS['test_mail_message'];
    $admin_mail_subject = $HTTP_POST_VARS['test_mail_subject'];
    $admin_html_mail = $HTTP_POST_VARS['test_html_mail'];
    $admin_add_mail_signature = $HTTP_POST_VARS['test_add_mail_signature'];
    reset($fields);
    while (list($h, $v) = each($fields)) {
           $admin_mail_message = eregi_replace($v[0],$v[2],$admin_mail_message);
           $admin_mail_subject = eregi_replace($v[0],$v[2],$admin_mail_subject);
    }
    if ($admin_add_mail_signature == "on") {
        $admin_mail_message .= "\n".SITE_SIGNATURE;
    }
    $admin_mail_message = bx_unhtmlspecialchars($admin_mail_message);
    bx_mail(SITE_NAME,SITE_MAIL,SITE_MAIL,stripslashes($admin_mail_subject),stripslashes($admin_mail_message),$admin_html_mail); 
    ?>
        <table width="100%" cellspacing="0" cellpadding="2" border="0" bgcolor="#EFEFEF">
          <tr>
              <td align="left"><b>Test mail message file</b></td>
          </tr>
          <tr>
             <td><TABLE border="0" cellpadding="4" cellspacing="0" width="100%" class="edit">
                <tr>
                    <td align="left"><b>Mail sent successfully. Please check your mailbox.</b></td>
                </tr>
             </table>
             </td>
          </tr>
          </table>
        <SCRIPT LANGUAGE="JavaScript">
        <!--
        alert('Mail was sent successfull to <?php echo SITE_MAIL;?>.\nPlease check your mailbox in a few moments.\nIf you are satisfied with the result, please then send the bulk mail message file.');
        window.close();
        //-->
        </SCRIPT>
    <?php
}
else if ($HTTP_POST_VARS['todo']=="test_mail_html") {
    if($HTTP_POST_VARS['t_mail'] == "companies") {
        include(DIR_ADMIN."employer_bulkmail.cfg.php");
    }
    $admin_mail_message = $HTTP_POST_VARS['test_html_mail_message'];
    $admin_mail_subject = stripslashes($HTTP_POST_VARS['test_mail_subject']);
    $admin_add_html_header = $HTTP_POST_VARS['test_add_html_header'];
    $admin_add_html_footer = $HTTP_POST_VARS['test_add_html_footer'];
    reset($fields);
    while (list($h, $v) = each($fields)) {
           $admin_mail_message = eregi_replace($v[0],$v[2],$admin_mail_message);
           $admin_mail_subject = eregi_replace($v[0],$v[2],$admin_mail_subject);
    }
    if ($admin_add_html_header == "on") {
            $admin_mail_message = fread(fopen(DIR_LANGUAGES.$HTTP_POST_VARS['lng']."/html/html_email_message_header.html","r"),filesize(DIR_LANGUAGES.$HTTP_POST_VARS['lng']."/html/html_email_message_header.html")).$admin_mail_message;
    }
    if ($admin_add_html_footer == "on") {
        $admin_mail_message .= fread(fopen(DIR_LANGUAGES.$HTTP_POST_VARS['lng']."/html/html_email_message_footer.html","r"),filesize(DIR_LANGUAGES.$HTTP_POST_VARS['lng']."/html/html_email_message_footer.html"));
    }
    bx_mail(SITE_NAME,SITE_MAIL,SITE_MAIL,$admin_mail_subject,stripslashes($admin_mail_message),"yes"); 
    ?>
        <table width="100%" cellspacing="0" cellpadding="2" border="0" bgcolor="#EFEFEF">
          <tr>
              <td align="left"><b>Test mail message file</b></td>
          </tr>
          <tr>
             <td><TABLE border="0" cellpadding="4" cellspacing="0" width="100%" class="edit">
                <tr>
                    <td align="left"><b>Mail sent successfully. Please check your mailbox.</b></td>
                </tr>
             </table>
             </td>
          </tr>
          </table>
        <SCRIPT LANGUAGE="JavaScript">
        <!--
        alert('Mail was sent successfull to <?php echo SITE_MAIL;?>.\nPlease check your mailbox in a few moments.\nIf you are satisfied with the result, please then send the bulk mail message file.');
        window.close();
        //-->
        </SCRIPT>
    <?php
}
else {
if($HTTP_GET_VARS['mail'] == "companies") {
    include(DIR_ADMIN."employer_bulkmail.cfg.php");
}
?>
<form method="post" action="<?php echo HTTP_SERVER_ADMIN.FILENAME_ADMIN_BULK_EMAIL;?>" name="bulkmail" onSubmit="return check_form();">
<input type="hidden" name="todo" value="bulkmail">
<input type="hidden" name="mail" value="<?php echo $HTTP_GET_VARS['mail'];?>">
<table width="100%" cellspacing="0" cellpadding="2" border="0" class="tedit">
 <tr>
     <td align="center"><b>Send bulk email to Users</b></td>
 </tr>
 <tr>
   <td><TABLE border="0" cellpadding="4" cellspacing="0" width="100%" class="edit">
<tr>
       <td colspan="2"><b><?php echo TEXT_MAIL_MESSAGE_NOTE;?></b></td>
</tr>
<tr>
        <td colspan="2" align="center"><b>Bulk Email <?php echo TEXT_SUBJECT;?>:</b></td>
</tr>
<tr>
        <td align="center" colspan="2"><input type="text" name="bulk_subject" size="60"></td>
</tr>
<tr>
        <td colspan="2" align="left"><hr color="#FF0000"></td>
</tr>
<?php if(ALLOW_HTML_MAIL == "yes") {?>
<tr>
        <td colspan="2" align="left"><font class="error"><b>Plain Text Bulk Email Message:</b></font></td>
</tr>
<?php }?>
<tr>
           <td colspan="2" align="center"><b><?php echo TEXT_MAIL_TEXT;?>:</b></td>
       </tr>
<tr>
        <td align="center" colspan="2" valign="top"><textarea name="bulk_message" rows="15" cols="80"></textarea></td>
</tr>
<tr><td colspan="2" align="center" nowrap class="mm"><?php echo TEXT_AVAILABLE_MAIL_FIELDS;?><br>
        
        <select name="fields" class="mm" onChange="document.bulkmail.bulk_message.value += this.options[this.selectedIndex].value; alert('Added '+this.options[this.selectedIndex].text+' to Mail message!'); this.selectedIndex=0;"><option>---- Select/Add field ----</option><?php
        reset($fields);
        while (list($h, $v) = each($fields)) {
            echo "<option value=\"".$v[0]."\">".$v[1]."</option>";
        }
        ?></select>
        <br><?php
        reset($fields);
        while (list($h, $v) = each($fields)) {
            echo "e.g. ".$v[1]." (<b>".$v[0]."</b>) - ".$v[2]."<br>";
        }
        ?>
        </td>
   </tr>
   <?php if(ALLOW_HTML_MAIL == "no") {?>
   <tr>
       <td colspan="2" align="center"><input type="checkbox" name="add_mail_signature" value="on" class="radio"<?php echo ($add_mail_signature=="on")?" checked":"";?>><?php echo TEXT_ADD_MAIL_SIGNATURE;?></td>
   </tr>    
<!--    <tr>
        <td align="right"><?php echo TEXT_EMAIL_TYPE;?>:</font></td><td><input type="radio" class="radio" name="bulk_type" value="no" checked onClick="document.testfile.test_html_mail.value=this.value;">Plain text <input type="radio" class="radio" name="bulk_type" value="yes" onClick="document.testfile.test_html_mail.value=this.value;">HTML</td>
   </tr> -->
   <input type="hidden" name="bulk_type" value="no">
   <tr>
       <td colspan="2" align="center" class="mm"><?php echo TEXT_SITE_MAIL_SIGNATURE;?></td>
   </tr>
   <tr>
        <td  width="35%" align="right"><input type="submit" name="save" value="Send Message(s)"></form>
        </td><td width="65%"><form method="post" target="test_mail" action="<?php echo HTTP_SERVER_ADMIN.FILENAME_ADMIN_BULK_EMAIL;?>" name="testfile" onSubmit="this.test_mail_message.value=document.bulkmail.bulk_message.value; this.test_mail_subject.value=document.bulkmail.bulk_subject.value; if (document.bulkmail.add_mail_signature.checked == true) {this.test_add_mail_signature.value = document.bulkmail.add_mail_signature.value;} else {this.test_add_mail_signature.value='';} df=open('','test_mail','scrollbars=no,menubar=no,resizable=0,location=no,width=350,height=220,left=10,top=10,screenX=10,screenY=10');df.window.focus(); return true;">
<input type="hidden" name="todo" value="test_mail"><input type="hidden" name="test_mail_message" value=""><input type="hidden" name="test_mail_subject" value=""><input type="hidden" name="test_html_mail" value="no"><input type="hidden" name="t_mail" value="<?php echo $HTTP_GET_VARS['mail'];?>"><input type="hidden" name="lng" value="<?php echo $language;?>"><input type="hidden" name="test_add_mail_signature" value=""><input type="submit" name="sendtestmail" value="Send Admin a Test Mail" class=""></form></td>
   </tr>
   <?php }
   if(ALLOW_HTML_MAIL=="yes") {?>
    <tr>
       <td colspan="2" align="center"><input type="checkbox" name="add_mail_signature" value="on"< class="radio"<?php echo ($add_mail_signature=="on")?" checked":"";?>><?php echo TEXT_ADD_MAIL_SIGNATURE;?> (Plain Text Mail)</td>
    </tr>    
    <tr>
       <td colspan="2" align="center" class="mm"><?php echo TEXT_SITE_MAIL_SIGNATURE;?></td>
    </tr>
    <tr>
           <td colspan="2" align="left"><hr color="#FF0000"></td>
       </tr>
       <tr>
           <td colspan="2" align="left"><font class="error"><b>HTML Mail Message:</b></font></td>
       </tr>
      
       <tr>
           <td colspan="2" align="center"><b><?php echo TEXT_MAIL_TEXT;?>:</b></td>
       </tr>
       <tr>
           <td colspan="2" align="center"><textarea name="html_mail_message" rows="15" cols="80" class="mm"></textarea></td>
       </tr>
       <tr><td colspan="2" align="center" nowrap class="mm"><?php echo TEXT_AVAILABLE_MAIL_FIELDS;?><br>
            
            <select name="fields" class="mm" onChange="document.editfile.html_mail_message.value += this.options[this.selectedIndex].value; alert('Added '+this.options[this.selectedIndex].text+' to Mail message!'); this.selectedIndex=0;"><option>---- Select/Add field ----</option><?php
            reset($fields);
            while (list($h, $v) = each($fields)) {
                echo "<option value=\"".$v[0]."\">".$v[1]."</option>";
            }
            ?></select>
            <br><?php
            reset($fields);
            while (list($h, $v) = each($fields)) {
                echo "e.g. ".$v[1]." (<b>".$v[0]."</b>) - ".$v[2]."<br>";
            }
            ?>
            </td>
       </tr>
       <tr>
           <td colspan="2" align="center"><input type="checkbox" name="add_html_header" value="on" class="radio" checked><?php echo TEXT_ADD_HTML_HEADER;?></td>
       </tr>    
       <tr>
           <td colspan="2" align="center"><input type="checkbox" name="add_html_footer" value="on" class="radio" checked><?php echo TEXT_ADD_HTML_FOOTER;?></td>
       </tr>    
       <tr>
           <td colspan="2" align="center" class="mm"><?php echo TEXT_SITE_HTML_SIGNATURE;?></td>
       </tr>
       <tr>
           <td colspan="2" align="center" nowrap><b>For a "Preview" Click on the button on the right:&nbsp;&nbsp;<input type="button" class="button" name="preview" value="Preview HTML Mail Message" onClick="document.preview.test_html_mail_message.value=document.bulkmail.html_mail_message.value; document.preview.t_mail.value=document.bulkmail.mail.value; if (document.bulkmail.add_html_header.checked == true) {document.preview.test_add_html_header.value = document.bulkmail.add_html_header.value;} else {document.preview.test_add_html_header.value='';} if (document.bulkmail.add_html_footer.checked == true) {document.preview.test_add_html_footer.value = document.bulkmail.add_html_footer.value;} else {document.preview.test_add_html_footer.value='';} document.preview.test_mail_subject.value=document.bulkmail.bulk_subject.value; df=open('','preview_html','scrollbars=no,menubar=no,resizable=yes,location=no,width=640,height=480,left=10,top=10,screenX=10,screenY=10');df.window.focus(); document.preview.submit();return true;"></b></td>
   </tr>
   <tr>
           <td colspan="2" align="left"><hr color="#FF0000"></td>
   </tr>
   <tr>
        <td align="right" width="100%" colspan="2"><table width="100%" cellpadding="2" cellspacing="0" border="0"><tr><td width="99%" align="center">
            <input type="submit" name="save" value="Send Message(s)"></form></td>
            <td width="1%"><form method="post" target="preview_html" style="margin-top: 0px; margin-bottom: 0px;" action="<?php echo HTTP_SERVER_ADMIN.FILENAME_ADMIN_BULK_EMAIL;?>" name="preview" onSubmit="this.test_html_mail_message.value=document.bulkmail.html_mail_message.value; this.t_mail.value=document.bulkmail.mail.value; if (document.bulkmail.add_html_header.checked == true) {this.test_add_html_header.value = document.bulkmail.add_html_header.value;} else {this.test_add_html_header.value='';} if (document.bulkmail.add_html_footer.checked == true) {this.test_add_html_footer.value = document.bulkmail.add_html_footer.value;} else {this.test_add_html_footer.value='';} this.test_mail_subject.value=document.bulkmail.bulk_subject.value; df=open('','preview_html','scrollbars=no,menubar=no,resizable=0,location=no,width=640,height=480,left=10,top=10,screenX=10,screenY=10');df.window.focus(); return true;"><input type="hidden" name="lng" value="<?php echo $language;?>">
            <input type="hidden" name="todo" value="preview"><input type="hidden" name="t_mail" value=""><input type="hidden" name="test_html_mail_message" value=""><input type="hidden" name="test_add_html_header" value=""><input type="hidden" name="test_add_html_footer" value=""><input type="hidden" name="test_mail_subject" value=""></form></td>
        </tr>
        <tr>
			<td colspan="2">&nbsp;</td>
   	    </tr>
        <tr>
           <form method="post" style="margin-top: 0px; margin-bottom: 0px;" target="test_mail" action="<?php echo HTTP_SERVER_ADMIN.FILENAME_ADMIN_BULK_EMAIL;?>" name="testfile" onSubmit="this.test_mail_message.value=document.bulkmail.bulk_message.value;this.test_mail_subject.value=document.bulkmail.bulk_subject.value; if (document.bulkmail.add_mail_signature.checked == true) {this.test_add_mail_signature.value = document.bulkmail.add_mail_signature.value;} else {this.test_add_mail_signature.value='';} df=open('','test_mail','scrollbars=no,menubar=no,resizable=no,location=no,width=350,height=220,left=10,top=10,screenX=10,screenY=10');df.window.focus(); return true;"><td colspan="2" align="center" nowrap><b>Send admin a Plaintext Test mail: &nbsp;&nbsp;<input type="hidden" name="lng" value="<?php echo $HTTP_POST_VARS['lng'];?>">
            <input type="hidden" name="todo" value="test_mail"><input type="hidden" name="t_mail" value="<?php echo $HTTP_GET_VARS['mail'];?>"><input type="hidden" name="lng" value="<?php echo $language;?>"><input type="hidden" name="test_mail_message" value=""><input type="hidden" name="test_mail_subject" value=""><input type="hidden" name="test_add_mail_signature" value=""><input type="hidden" name="test_html_mail" value="no"><input type="submit" name="sendtestmail" value="Send Admin (Plaintext) Test Mail" class="button"></b></td></form>
       </tr>
	   <tr>
           <form method="post" target="test_mail_html" style="margin-top: 0px; margin-bottom: 0px;" action="<?php echo HTTP_SERVER_ADMIN.FILENAME_ADMIN_BULK_EMAIL;?>" name="testhtmlfile" onSubmit="this.test_html_mail_message.value=document.bulkmail.html_mail_message.value; if (document.bulkmail.add_html_header.checked == true) {this.test_add_html_header.value = document.bulkmail.add_html_header.value;} else {this.test_add_html_header.value='';} if (document.bulkmail.add_html_footer.checked == true) {this.test_add_html_footer.value = document.bulkmail.add_html_footer.value;} else {this.test_add_html_footer.value='';} this.test_mail_subject.value=document.bulkmail.bulk_subject.value; df=open('','test_mail_html','scrollbars=no,menubar=no,resizable=no,location=no,width=350,height=220,left=10,top=10,screenX=10,screenY=10');df.window.focus(); return true;"><td colspan="2" align="center" nowrap><b>Send admin a HTML Test mail: &nbsp;&nbsp;
            <input type="hidden" name="todo" value="test_mail_html"><input type="hidden" name="t_mail" value="<?php echo $HTTP_GET_VARS['mail'];?>"><input type="hidden" name="lng" value="<?php echo $language;?>"><input type="hidden" name="test_html_mail_message" value=""><input type="hidden" name="test_add_html_header" value=""><input type="hidden" name="test_add_html_footer" value=""><input type="hidden" name="test_mail_subject" value=""><input type="hidden" name="test_html_mail" value="yes"><input type="submit" name="sendtestmail" value="Send Admin (HTML) Test Mail" class="button"></b></td></form>
       </tr>
       <?php }?>
        </table></td>
</tr>
</table>
</td></tr></table>
<?php
}
?>