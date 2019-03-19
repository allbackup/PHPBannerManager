<?php
if($HTTP_POST_VARS['folders']) {
    $folders=$HTTP_POST_VARS['folders'];
}
elseif ($HTTP_GET_VARS['folders']){
     $folders = $HTTP_GET_VARS['folders'];
}
else {
    $folders = '';
}
function write_email_config($filename, $cf)
{
    $fp = fopen($filename, "r");
    while (!feof($fp)) {
        $buffer = fgets($fp, 20000);
        for ($i=0;$i<sizeof($cf['h']);$i++) {
                if (eregi("\\\$".$cf['h'][$i]."(.*)",$buffer,$regs)) {
                    $buffer = eregi_replace("\\\$".$cf['h'][$i]."(.*)","\$".$cf['h'][$i]." = \"".$cf['v'][$i]."\";\n",$buffer);
                }
        }
        $to_write .= $buffer;
    }
    fclose($fp);
    $fp2 = fopen($filename, "w+");
    fwrite($fp2, $to_write);
    fclose($fp2);
} // end func
if ($HTTP_POST_VARS['todo'] == "savefile") {
    if(ADMIN_SAFE_MODE == "yes" && $HTTP_POST_VARS['lng']==DEFAULT_LANGUAGE) {
         $error_title = "editing language mail message";
         bx_admin_error(TEXT_SAFE_MODE_ALERT);
    }
    else {
        $fp=fopen(DIR_LANGUAGES.$HTTP_POST_VARS['filename'], "w+");
        fwrite($fp, stripslashes($HTTP_POST_VARS['mail_message']));
        fclose($fp);
        if($HTTP_POST_VARS['mail_type']=="1") {
            $fp=fopen(DIR_LANGUAGES.$HTTP_POST_VARS['filename'].".html", "w+");
            fwrite($fp, stripslashes($HTTP_POST_VARS['html_mail_message']));
            fclose($fp);
        }
        $cf['h'][] = "file_mail_subject";
        $cf['v'][] = $HTTP_POST_VARS['mail_subject'];
        $cf['h'][] = "html_mail";
        $cf['v'][] = $HTTP_POST_VARS['html_mail'];
        if($HTTP_POST_VARS['mail_type']=="1") {
            $cf['h'][] = "add_html_header";
            $cf['v'][] = $HTTP_POST_VARS['add_html_header'];
            $cf['h'][] = "add_html_footer";
            $cf['v'][] = $HTTP_POST_VARS['add_html_footer'];
        }
        $cf['h'][] = "add_mail_signature";
        $cf['v'][] = $HTTP_POST_VARS['add_mail_signature'];
        write_email_config(DIR_LANGUAGES.$HTTP_POST_VARS['filename'].".cfg.php" , $cf);
        ?>
         <table width="100%" cellspacing="0" cellpadding="2" border="0" class="tedit">
          <tr>
              <td align="center"><b>Edit Email Message Files</b></td>
          </tr>
          <tr>
             <td><TABLE border="0" cellpadding="4" cellspacing="0" width="100%" class="edit">
                <tr>
                    <td align="center"><b>Successfull update.</b></td>
                </tr>
                <tr>
                    <td align="left" nowrap><a href="<?php echo HTTP_SERVER_ADMIN.FILENAME_INDEX;?>" class="settings">&#171;Admin Home&#187;</a>&nbsp;&nbsp;<a href="<?php echo HTTP_SERVER_ADMIN.FILENAME_ADMIN_EDIT_MAIL."?todo=editmail&folders=".$HTTP_POST_VARS['lng'];?>" class="settings">&#171;Edit Mail Messages Home&#187;</a></td>
                </tr>
             </table>
             </td>
          </tr>
          </table>
<?php
    }           
}
else if ($HTTP_GET_VARS['todo'] == "preview") {
    ?>
    <script language="Javascript">
    <!--
          if(parent.opener.document.editfile.add_html_header.checked) {
              document.write("<?php echo eregi_replace('"',"&#034;",nl2br(fread(fopen(DIR_LANGUAGES.$HTTP_GET_VARS['lng']."/html/html_email_message_header.html","r"),filesize(DIR_LANGUAGES.$HTTP_GET_VARS['lng']."/html/html_email_message_footer.html"))));?>");
          }
          document.write(parent.opener.document.editfile.html_mail_message.value);
          if(parent.opener.document.editfile.add_html_footer.checked) {
              document.write("<br><?php echo eregi_replace('"',"&#034;",nl2br(fread(fopen(DIR_LANGUAGES.$HTTP_GET_VARS['lng']."/html/html_email_message_footer.html","r"),filesize(DIR_LANGUAGES.$HTTP_GET_VARS['lng']."/html/html_email_message_footer.html"))));?>");
          }
    //-->
    </script>
    <?php
}
else if ($HTTP_POST_VARS['todo'] == "preview") {
      if($HTTP_POST_VARS['lng']) {
                include(DIR_LANGUAGES.$HTTP_POST_VARS['filename'].".cfg.php");
                $admin_mail_message = stripslashes($HTTP_POST_VARS['test_html_mail_message']);
                $admin_add_html_header = $HTTP_POST_VARS['test_add_html_header'];
                $admin_add_html_footer = $HTTP_POST_VARS['test_add_html_footer'];
                if ($repeat_code) {
                    $ee = eregi("(.*)<BEGIN REPEAT>(.*)<END REPEAT>(.*)",$admin_mail_message, $regs);
                    $admin_mail_header = $regs[1];
                    $admin_mail_message = $regs[2];
                    $admin_mail_footer = $regs[3];
                }
                reset($fields);
                while (list($h, $v) = each($fields)) {
                           $admin_mail_message = eregi_replace($v[0],$v[2],$admin_mail_message);
                           $admin_mail_subject = eregi_replace($v[0],$v[2],$admin_mail_subject);
                           $admin_mail_header = eregi_replace($v[0],$v[2],$admin_mail_header);
                           $admin_mail_footer = eregi_replace($v[0],$v[2],$admin_mail_footer);
                }
                if ($repeat_code) {
                    $admin_mail_message .= "<br>".$admin_mail_message;
                    $admin_mail_message = $admin_mail_header.$admin_mail_message.$admin_mail_footer;
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
else if ($HTTP_POST_VARS['todo'] == "test_mail") {
include(DIR_LANGUAGES.$HTTP_POST_VARS['filename'].".cfg.php");
$admin_mail_message = $HTTP_POST_VARS['test_mail_message'];
$admin_mail_subject = stripslashes($HTTP_POST_VARS['test_mail_subject']);
$admin_html_mail = $HTTP_POST_VARS['test_html_mail'];
$admin_add_mail_signature = $HTTP_POST_VARS['test_add_mail_signature'];
if ($repeat_code) {
    $ee = eregi("(.*)<BEGIN REPEAT>(.*)<END REPEAT>(.*)",$admin_mail_message, $regs);
    $admin_mail_header = $regs[1];
    $admin_mail_message = $regs[2];
    $admin_mail_footer = $regs[3];
}
    reset($fields);
    while (list($h, $v) = each($fields)) {
           $admin_mail_message = eregi_replace($v[0],$v[2],$admin_mail_message);
           $admin_mail_subject = eregi_replace($v[0],$v[2],$admin_mail_subject);
           $admin_mail_header = eregi_replace($v[0],$v[2],$admin_mail_header);
           $admin_mail_footer = eregi_replace($v[0],$v[2],$admin_mail_footer);
    }
if ($repeat_code) {
    $admin_mail_message .= "\n".$admin_mail_message;
    $admin_mail_message = $admin_mail_header.$admin_mail_message.$admin_mail_footer;
}
if ($admin_add_mail_signature == "on") {
    $admin_mail_message .= "\n".SITE_SIGNATURE;
}
$admin_mail_message = bx_unhtmlspecialchars($admin_mail_message);
bx_mail(SITE_NAME,SITE_MAIL,SITE_MAIL,$admin_mail_subject,stripslashes($admin_mail_message),$admin_html_mail); 
?>
    <table width="100%" cellspacing="0" cellpadding="2" border="0" class="tedit">
      <tr>
          <td align="center"><b>Test mail message file</b></td>
      </tr>
      <tr>
         <td><TABLE border="0" cellpadding="4" cellspacing="0" width="100%" class="edit">
            <tr>
                <td align="center"><b>Mail sent successfully. Please check your mailbox.</b></td>
            </tr>
         </table>
         </td>
      </tr>
      </table>
    <SCRIPT LANGUAGE="JavaScript">
    <!--
    alert('Mail was sent successfull to <?php echo SITE_MAIL;?>.\nPlease check your mailbox in a few moments.\nIf you are satisfied with the result, please then save the mail message file.');
    window.close();
    //-->
    </SCRIPT>
<?php
}
else if ($HTTP_POST_VARS['todo'] == "test_mail_html") {
include(DIR_LANGUAGES.$HTTP_POST_VARS['filename'].".cfg.php");
if($HTTP_POST_VARS['mail_type']=="1") {
        $admin_mail_subject = stripslashes($HTTP_POST_VARS['test_mail_subject']);
        $admin_mail_message = $HTTP_POST_VARS['test_html_mail_message'];
        $admin_add_html_header = $HTTP_POST_VARS['test_add_html_header'];
        $admin_add_html_footer = $HTTP_POST_VARS['test_add_html_footer'];
        if ($repeat_code) {
            $ee = eregi("(.*)<BEGIN REPEAT>(.*)<END REPEAT>(.*)",$admin_mail_message, $regs);
            $admin_mail_header = $regs[1];
            $admin_mail_message = $regs[2];
            $admin_mail_footer = $regs[3];
        }
        reset($fields);
        while (list($h, $v) = each($fields)) {
                   $admin_mail_message = eregi_replace($v[0],$v[2],$admin_mail_message);
                   $admin_mail_subject = eregi_replace($v[0],$v[2],$admin_mail_subject);
                   $admin_mail_header = eregi_replace($v[0],$v[2],$admin_mail_header);
                   $admin_mail_footer = eregi_replace($v[0],$v[2],$admin_mail_footer);
        }
        if ($repeat_code) {
            $admin_mail_message .= "<br>".$admin_mail_message;
            $admin_mail_message = $admin_mail_header.$admin_mail_message.$admin_mail_footer;
        }
        if ($admin_add_html_header == "on") {
            $admin_mail_message = fread(fopen(DIR_LANGUAGES.$HTTP_POST_VARS['lng']."/html/html_email_message_header.html","r"),filesize(DIR_LANGUAGES.$HTTP_POST_VARS['lng']."/html/html_email_message_header.html")).$admin_mail_message;
        }
        if ($admin_add_html_footer == "on") {
            $admin_mail_message .= fread(fopen(DIR_LANGUAGES.$HTTP_POST_VARS['lng']."/html/html_email_message_footer.html","r"),filesize(DIR_LANGUAGES.$HTTP_POST_VARS['lng']."/html/html_email_message_footer.html"));
        }
        bx_mail(SITE_NAME,SITE_MAIL,SITE_MAIL,$admin_mail_subject,stripslashes($admin_mail_message),"yes"); 
}
?>
    <table width="100%" cellspacing="0" cellpadding="2" border="0" class="tedit">
      <tr>
          <td align="center"><b>Test mail message file</b></td>
      </tr>
      <tr>
         <td><TABLE border="0" cellpadding="4" cellspacing="0" width="100%" class="edit">
            <tr>
                <td align="center"><b>Mail sent successfully. Please check your mailbox.</b></td>
            </tr>
         </table>
         </td>
      </tr>
      </table>
    <SCRIPT LANGUAGE="JavaScript">
    <!--
    alert('Mail was sent successfull to <?php echo SITE_MAIL;?>.\nPlease check your mailbox in a few moments.\nIf you are satisfied with the result, please then save the mail message file.');
    window.close();
    //-->
    </SCRIPT>
<?php
}
else if ($HTTP_POST_VARS['todo'] == "editfile") {
include(DIR_LANGUAGES.$HTTP_POST_VARS['editfile'].".cfg.php");
?>
<form method="post" action="<?php echo HTTP_SERVER_ADMIN.FILENAME_ADMIN_EDIT_MAIL;?>" name="editfile" style="margin-top: 0px; margin-bottom: 0px;">
<input type="hidden" name="todo" value="savefile">
<input type="hidden" name="lng" value="<?php echo $HTTP_POST_VARS['lng'];?>">
<?php if($mail_type=="1"){?>
    <input type="hidden" name="html_mail" value="<?php echo $html_mail;?>">
<?php }?>
<input type="hidden" name="filename" value="<?php echo $HTTP_POST_VARS['editfile'];?>">
<input type="hidden" name="mail_type" value="<?php echo $mail_type;?>">
<table width="100%" cellspacing="0" cellpadding="2" border="0" class="tedit">
<tr>
     <td align="center"><b>Edit language email message file: <?php echo $HTTP_POST_VARS['editfile'];?></b></td>
</tr>
<tr>
   <td><TABLE border="0" cellpadding="4" cellspacing="0" width="100%" class="edit">
   <tr>
       <td colspan="2"><b><?php echo TEXT_MAIL_MESSAGE_NOTE;?></b></td>
   </tr>
   <?php
   if ($message_note) {
   ?>
   <tr>
       <td colspan="2"><font class="error"><b><?php echo $message_note;?></b></font></td>
   </tr>
   <?php  
   }
   ?>
    <tr>
           <td colspan="2" align="center"><b><?php echo TEXT_MAIL_SUBJECT;?>:</b></td>
   </tr>
   <tr>
       <td colspan="2" align="center"><input type="text" name="mail_subject" size="60" value="<?php echo bx_js_stripslashes($file_mail_subject);?>"></td>
   </tr>
   <?php if($mail_type=="1" && ALLOW_HTML_MAIL=="yes"){?>
       <tr>
           <td colspan="2" align="left"><hr color="#FF0000"></td>
       </tr>
       <tr>
           <td colspan="2" align="left"><font class="error"><b>Plain Text Mail Message:</b></font></td>
       </tr>
   <?php }?>
   <tr>
       <td colspan="2" align="center"><b><?php echo TEXT_MAIL_TEXT;?>:</b></td>
   </tr>
   <tr>
       <td colspan="2" align="center"><textarea name="mail_message" rows="15" cols="80" class="mm"><?php echo fread(fopen(DIR_LANGUAGES.$HTTP_POST_VARS['editfile'],"r"),filesize(DIR_LANGUAGES.$HTTP_POST_VARS['editfile']));?></textarea></td>
   </tr>
   <tr><td colspan="2" align="center" nowrap class="mm"><?php echo TEXT_AVAILABLE_MAIL_FIELDS;?><br>
        
        <select name="fields" class="mm" onChange="document.editfile.mail_message.value += this.options[this.selectedIndex].value; alert('Added '+this.options[this.selectedIndex].text+' to Mail message!'); this.selectedIndex=0;"><option>---- Select/Add field ----</option><?php
        reset($fields);
        $i=1;
        while (list($h, $v) = each($fields)) {
            echo "<option value=\"".$v[0]."\">".$i." - ".$v[1]."</option>";
            $i++;
        }
        ?></select>
        </td>
   </tr>
   <?php
    reset($fields);
    $i=1;
    while (list($h, $v) = each($fields)) {
        echo "<tr><td colspan=\"2\" class=\"mm\">&nbsp;&nbsp;&nbsp;<b>".$i.". - ".$v[0]."</b> - ".$v[1]."  - e.g. ".htmlspecialchars($v[2])."</td></tr>";
        $i++;
   }
   ?>
   <?php if($mail_type=="0" || ALLOW_HTML_MAIL=="no"){?>
   <tr>
       <td colspan="2" align="center"><input type="checkbox" name="add_mail_signature" value="on" class="radio"<?php echo ($add_mail_signature=="on")?" checked":"";?>><?php echo TEXT_ADD_MAIL_SIGNATURE;?></td>
   </tr>    
   <tr>
        <td align="right"><?php echo TEXT_EMAIL_TYPE;?>:</font></td><td><input type="radio" class="radio" name="html_mail" value="no"<?php echo ($html_mail=="no")?" checked":"";?> onClick="document.testfile.test_html_mail.value=this.value;">Plain text <input type="radio" class="radio" name="html_mail" value="yes"<?php echo ($html_mail=="yes")?" checked":"";?> onClick="document.testfile.test_html_mail.value=this.value;">HTML</td>
   </tr>
   <tr>
       <td colspan="2" align="center" class="mm"><?php echo TEXT_SITE_MAIL_SIGNATURE;?></font></td>
   </tr>
   <tr>
        <td  width="35%" align="right"><input type="submit" name="save" value="Save"></form>
        </td><td width="65%"><form method="post" target="test_mail" action="<?php echo HTTP_SERVER_ADMIN.FILENAME_ADMIN_EDIT_MAIL;?>" name="testfile" onSubmit="this.test_mail_message.value=document.editfile.mail_message.value; this.test_mail_subject.value=document.editfile.mail_subject.value; if (document.editfile.add_mail_signature.checked == true) {this.test_add_mail_signature.value = document.editfile.add_mail_signature.value;} else {this.test_add_mail_signature.value='';} df=open('','test_mail','scrollbars=no,menubar=no,resizable=0,location=no,width=350,height=220,left=10,top=10,screenX=10,screenY=10');df.window.focus(); return true;"><input type="hidden" name="filename" value="<?php echo $HTTP_POST_VARS['editfile'];?>">
        <input type="hidden" name="todo" value="test_mail"><input type="hidden" name="test_mail_message" value=""><input type="hidden" name="test_mail_subject" value=""><input type="hidden" name="test_html_mail" value="<?php echo $html_mail;?>"><input type="hidden" name="mail_type" value="<?php echo $mail_type;?>"><input type="hidden" name="test_add_mail_signature" value=""><input type="submit" name="sendtestmail" value="Send Admin a Test Mail" class=""></form></td>
   </tr>
   <?php }?>
   <?php if($mail_type=="1" && ALLOW_HTML_MAIL=="yes"){?>
       <tr>
           <td colspan="2" align="center"><input type="checkbox" name="add_mail_signature" value="on" class="radio"<?php echo ($add_mail_signature=="on")?" checked":"";?>><?php echo TEXT_ADD_MAIL_SIGNATURE;?></td>
       </tr>    
       <tr>
           <td colspan="2" align="center" class="mm"><?php echo TEXT_SITE_MAIL_SIGNATURE;?></font></td>
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
           <td colspan="2" align="center"><textarea name="html_mail_message" rows="15" cols="80" class="mm"><?php echo htmlspecialchars(fread(fopen(DIR_LANGUAGES.$HTTP_POST_VARS['editfile'].".html","r"),filesize(DIR_LANGUAGES.$HTTP_POST_VARS['editfile'].".html")));?></textarea></td>
       </tr>
       <tr><td colspan="2" align="center" nowrap class="mm"><?php echo TEXT_AVAILABLE_MAIL_FIELDS;?><br>
            
            <select name="fields" class="mm" onChange="document.editfile.html_mail_message.value += this.options[this.selectedIndex].value; alert('Added '+this.options[this.selectedIndex].text+' to Mail message!'); this.selectedIndex=0;"><option>---- Select/Add field ----</option><?php
            reset($fields);
            $i=1;
            while (list($h, $v) = each($fields)) {
                echo "<option value=\"".$v[0]."\">".$i." - ".$v[1]."</option>";
                $i++;
            }
            ?></select>
            </td>
       </tr>
       <?php
        reset($fields);
        $i=1;
        while (list($h, $v) = each($fields)) {
            echo "<tr><td colspan=\"2\" class=\"mm\">&nbsp;&nbsp;&nbsp;<b>".$i.". - ".$v[0]."</b> - ".$v[1]."  - e.g. ".htmlspecialchars($v[2])."</td></tr>";
            $i++;
       }
       ?>
       <tr>
           <td colspan="2" align="center"><input type="checkbox" name="add_html_header" value="on" class="radio"<?php echo ($add_html_header=="on")?" checked":"";?>><?php echo TEXT_ADD_HTML_HEADER;?></td>
       </tr>    
       <tr>
           <td colspan="2" align="center"><input type="checkbox" name="add_html_footer" value="on" class="radio"<?php echo ($add_html_footer=="on")?" checked":"";?>><?php echo TEXT_ADD_HTML_FOOTER;?></td>
       </tr>    
       <tr>
           <td colspan="2" align="center" class="mm"><?php echo TEXT_SITE_HTML_SIGNATURE;?></font></td>
       </tr>
       <tr>
           <td colspan="2" align="center" nowrap><b>For a "Preview" Click on the button on the right:&nbsp;&nbsp;<input type="button" class="button" name="preview" value="Preview HTML Mail Message" onClick="document.preview.test_html_mail_message.value=document.editfile.html_mail_message.value; if (document.editfile.add_html_header.checked == true) {document.preview.test_add_html_header.value = document.editfile.add_html_header.value;} else {document.preview.test_add_html_header.value='';} if (document.editfile.add_html_footer.checked == true) {document.preview.test_add_html_footer.value = document.editfile.add_html_footer.value;} else {document.preview.test_add_html_footer.value='';} document.preview.test_mail_subject.value=document.editfile.mail_subject.value; df=open('','preview_html','scrollbars=yes,menubar=no,resizable=yes,location=no,width=640,height=480,left=10,top=10,screenX=10,screenY=10');df.window.focus(); document.preview.submit();return true;"></b></td>
       </tr>
       <tr>
           <td colspan="2" align="left"><hr color="#FF0000"></td>
       </tr>
       <tr>
            <td  width="100%" align="center"><input type="submit" name="save" value="&nbsp;&nbsp;&nbsp;&nbsp;Save Mail Message&nbsp;&nbsp;&nbsp;&nbsp;"></form>
            </td><td width="1%"><form method="post" target="preview_html" style="margin-top: 0px; margin-bottom: 0px;" action="<?php echo HTTP_SERVER_ADMIN.FILENAME_ADMIN_EDIT_MAIL;?>" name="preview" onSubmit="this.test_html_mail_message.value=document.editfile.html_mail_message.value; if (document.editfile.add_html_header.checked == true) {this.test_add_html_header.value = document.editfile.add_html_header.value;} else {this.test_add_html_header.value='';} if (document.editfile.add_html_footer.checked == true) {this.test_add_html_footer.value = document.editfile.add_html_footer.value;} else {this.test_add_html_footer.value='';} this.test_mail_subject.value=document.editfile.mail_subject.value; df=open('','preview_html','scrollbars=yes,menubar=no,resizable=0,location=no,width=640,height=480,left=10,top=10,screenX=10,screenY=10');df.window.focus(); return true;"><input type="hidden" name="filename" value="<?php echo $HTTP_POST_VARS['editfile'];?>"><input type="hidden" name="lng" value="<?php echo $HTTP_POST_VARS['lng'];?>">
            <input type="hidden" name="todo" value="preview"><input type="hidden" name="test_html_mail_message" value=""><input type="hidden" name="test_add_html_header" value=""><input type="hidden" name="test_add_html_footer" value=""><input type="hidden" name="test_mail_subject" value=""><input type="hidden" name="test_html_mail" value="<?php echo $html_mail;?>"><input type="hidden" name="mail_type" value="<?php echo $mail_type;?>"></form></td>
       </tr>
	   <tr>
			<td colspan="2">&nbsp;</td>
	   </tr>
	   <tr>
           <form method="post" style="margin-top: 0px; margin-bottom: 0px;" target="test_mail" action="<?php echo HTTP_SERVER_ADMIN.FILENAME_ADMIN_EDIT_MAIL;?>" name="testfile" onSubmit="this.test_mail_message.value=document.editfile.mail_message.value;this.test_mail_subject.value=document.editfile.mail_subject.value; if (document.editfile.add_mail_signature.checked == true) {this.test_add_mail_signature.value = document.editfile.add_mail_signature.value;} else {this.test_add_mail_signature.value='';} df=open('','test_mail','scrollbars=no,menubar=no,resizable=no,location=no,width=350,height=220,left=10,top=10,screenX=10,screenY=10');df.window.focus(); return true;"><td colspan="2" align="center" nowrap><b>Send admin a Plaintext Test mail: &nbsp;&nbsp;<input type="hidden" name="filename" value="<?php echo $HTTP_POST_VARS['editfile'];?>"><input type="hidden" name="lng" value="<?php echo $HTTP_POST_VARS['lng'];?>">
            <input type="hidden" name="todo" value="test_mail"><input type="hidden" name="test_mail_message" value=""><input type="hidden" name="test_mail_subject" value=""><input type="hidden" name="test_html_mail" value="<?php echo $html_mail;?>"><input type="hidden" name="test_add_mail_signature" value=""><input type="hidden" name="mail_type" value="<?php echo $mail_type;?>"><input type="submit" name="sendtestmail" value="Send Admin (Plaintext) Test Mail" class="button"></b></td></form>
       </tr>
	   <tr>
           <form method="post" target="test_mail_html" style="margin-top: 0px; margin-bottom: 0px;" action="<?php echo HTTP_SERVER_ADMIN.FILENAME_ADMIN_EDIT_MAIL;?>" name="testhtmlfile" onSubmit="this.test_html_mail_message.value=document.editfile.html_mail_message.value; if (document.editfile.add_html_header.checked == true) {this.test_add_html_header.value = document.editfile.add_html_header.value;} else {this.test_add_html_header.value='';} if (document.editfile.add_html_footer.checked == true) {this.test_add_html_footer.value = document.editfile.add_html_footer.value;} else {this.test_add_html_footer.value='';} this.test_mail_subject.value=document.editfile.mail_subject.value; df=open('','test_mail_html','scrollbars=no,menubar=no,resizable=no,location=no,width=350,height=220,left=10,top=10,screenX=10,screenY=10');df.window.focus(); return true;"><td colspan="2" align="center" nowrap><b>Send admin a HTML Test mail: &nbsp;&nbsp;<input type="hidden" name="filename" value="<?php echo $HTTP_POST_VARS['editfile'];?>"><input type="hidden" name="lng" value="<?php echo $HTTP_POST_VARS['lng'];?>">
            <input type="hidden" name="todo" value="test_mail_html"><input type="hidden" name="test_html_mail_message" value=""><input type="hidden" name="test_add_html_header" value=""><input type="hidden" name="test_add_html_footer" value=""><input type="hidden" name="test_mail_subject" value=""><input type="hidden" name="test_html_mail" value="<?php echo $html_mail;?>"><input type="hidden" name="mail_type" value="<?php echo $mail_type;?>"><input type="submit" name="sendtestmail" value="Send Admin (HTML) Test Mail" class="button"></b></td></form>
       </tr>
   <?php }?>
</table>
</td></tr></table>
<?php
}
else if ($HTTP_POST_VARS['todo'] == "editmail" || $HTTP_GET_VARS['todo'] == "editmail") {
     if (empty($folders)) {
         bx_admin_error("You must select a language to edit.");
     }//end if (empty($folders))
     else {
            ?>
            <table width="100%" cellspacing="0" cellpadding="2" border="0" class="tedit">
            <tr>
                 <td align="center"><b>Edit <?php echo $folders;?> language email message files</b></td>
            </tr>
            <tr>
               <td><TABLE border="0" cellpadding="4" cellspacing="0" width="100%" class="edit">
               <tr>
                   <td colspan="2"><br></td>
               </tr>
               <tr>
                   <td valign="top" colspan="2"><b><?php echo TEXT_EDIT_LANGUAGE_MAIL_NOTE;?></b></td>
               </tr>
               <tr>
                   <td valign="top" colspan="2"><b><?php echo TEXT_SELECT_EDIT_LANGUAGE_MAIL_FILE;?>:</b></td>
               </tr>
               <tr><td colspan="2">
                <table align="center" width="100%" border="0" cellspacing="0" cellpadding="2" class="edit">
                        <tr>
                            <td><b>#</b></td>
                            <td><b>File Name</b></td>
                            <td><b>Edit File</b></td>
                            <td><b>Last Modified</b></td>
                        </tr>
                        <tr>
                            <td colspan="4"><hr size="1"></td>
                        </tr>
               <?php
                     $n=1;
                     $dirs = getFiles(DIR_LANGUAGES.$folders."/mail");
                     sort($dirs);
                     for ($i=0; $i<count($dirs); $i++) {
                               if (eregi("\.cfg\.php|\.txt\.html",$dirs[$i],$rrr) || $dirs[$i]=="index.html" || $dirs[$i]=="index.htm") {    
                               }
                               else {
                                   $fmodtime = filemtime (DIR_LANGUAGES.$folders."/mail/".$dirs[$i]);
                                   $lastmodtime = date("d.m.Y - H:i:s", $fmodtime);
                                     ?>
                                     <tr>
                                         <td><b><u><?php echo $n;?></u>.</b></td>
                                         <form method="post" action="<?php echo HTTP_SERVER_ADMIN.FILENAME_ADMIN_EDIT_MAIL;?>">
                                         <input type="hidden" name="todo" value="editfile">
                                         <input type="hidden" name="editfile" value="<?php echo $folders."/mail/".$dirs[$i];?>">
                                         <input type="hidden" name="lng" value="<?php echo $folders;?>">
                                         <td><b><?php echo eregi_replace(".txt$","",$dirs[$i]);?></b></td>
                                         <td><input type="submit" name="edit" value="Edit File"></td>
                                         <td><?php echo $lastmodtime;?></td>
                                     </tr>
                                     </form>
                                     <?php
                                     $n++;
                               }
                               
                    }
                 ?>
                 <tr>
                    <td colspan="4"><hr size="1"></td>
                </tr>
               </table>
               </td>
               </tr>
               <tr>
                   <td valign="top" colspan="2">&nbsp;</td>
               </tr>
               </table>
         </td></tr></table>
         <?php
     }//end else if (empty($folders))
}//end if ($todo == "editmail")
else {
?>
<form method="post" action="<?php echo HTTP_SERVER_ADMIN.FILENAME_ADMIN_EDIT_MAIL;?>" name="editlng" onSubmit="return check_form_editmail();">
<input type="hidden" name="todo" value="editmail">
<table width="100%" cellspacing="0" cellpadding="2" border="0" class="tedit">
<tr>
     <td align="center"><b>Edit email messages</b></td>
</tr>
<tr>
<td><TABLE border="0" cellpadding="4" cellspacing="0" width="100%" class="edit">
<tr>
    <td colspan="2"><br></td>
</tr>
<tr>
        <td valign="top" width="80%"><b><?php echo TEXT_SELECT_EDIT_LANGUAGE_MAIL;?>:</b></td><td valign="top">
<?php
  $dirs = getFolders(DIR_LANGUAGES);
  if(count($dirs) == 1) {
          refresh(HTTP_SERVER_ADMIN.FILENAME_ADMIN_EDIT_MAIL."?todo=editmail&folders=".$dirs[0]);
  }
  for ($i=0; $i<count($dirs); $i++) {
       if (file_exists(DIR_LANGUAGES.$dirs[$i].".php")) {
              echo "<input type=\"radio\" name=\"folders\" value=\"".$dirs[$i]."\" class=\"radio\">".$dirs[$i]."<br>";
       }
  }
?>
</td></tr>
<tr>
        <td colspan="2" align="center"><br><input type="submit" name="edit" value="<?php echo TEXT_EDIT_LANGUAGE_EMAIL;?>"></td>
</tr>
</table>

</td></tr></table>
</form>
<?php
}
?>