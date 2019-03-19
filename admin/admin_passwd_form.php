<?php
$error_title = "changing admin password";
$updatepwd = true;
if ($HTTP_POST_VARS['todo'] == "chpasswd"){
       //<<begin fields validations
       if (empty($HTTP_POST_VARS['adm_login'])) {
                  bx_admin_error("New Login is required.");
                  $updatepwd = false;
       }
       if (empty($HTTP_POST_VARS['adm_passwd'])) {
                  bx_admin_error("New Password is required.");
                  $updatepwd = false;
       }
       if (empty($HTTP_POST_VARS['adm_repasswd'])) {
                  bx_admin_error("Retype Password is required.");
                  $updatepwd = false;
       }
       if ($HTTP_POST_VARS['adm_passwd'] != $HTTP_POST_VARS['adm_repasswd']) {
                  bx_admin_error("New Password and Retyped Password does't match.");
                  $updatepwd = false;
       }
       //>>end fields validation
       if(ADMIN_SAFE_MODE != "yes") {
           if($HTTP_POST_VARS['protect'] == '1') {
                   bx_db_query("DROP TABLE IF EXISTS phpjob_ladmin");
                   SQL_CHECK(0,"SQL Error at ".__FILE__.":".(__LINE__-1));
                   bx_session_unregister("adm_user");
                   bx_session_unregister("adm_pass");
                   bx_session_unregister("adm_ip");
                   if (file_exists(DIR_ADMIN.".htaccess")) {
                       if (!unlink(DIR_ADMIN.".htaccess")) {
                              bx_admin_error("Can't delete ".DIR_ADMIN.".htaccess .");
                              $updatepwd = false;
                       }
                   }
                   $fp=fopen(DIR_ADMIN.".htaccess","w");
                   fwrite($fp, "AuthType Basic\n");
                   fwrite($fp, "AuthUserFile ".DIR_ADMIN.".htpasswd"."\n");
                   fwrite($fp, "AuthName \"".SITE_NAME." Admin\"\n");
                   fwrite($fp, "require valid-user");
                   fclose($fp);
                   if (file_exists(DIR_ADMIN.".htpasswd")) {
                       if (!unlink(DIR_ADMIN.".htpasswd")) {
                              bx_admin_error("Can't delete ".DIR_ADMIN.".htpasswd .");
                              $updatepwd = false;
                       }
                   }
                   $fp=fopen(DIR_ADMIN.".htpasswd","w");
                   fwrite($fp, $HTTP_POST_VARS['adm_login'].":".crypt($HTTP_POST_VARS['adm_passwd']));
                   fclose($fp);
            }
            elseif($HTTP_POST_VARS['protect'] == '2') {
                bx_db_query("DROP TABLE IF EXISTS phpjob_ladmin");
                SQL_CHECK(0,"SQL Error at ".__FILE__.":".(__LINE__-1));
                bx_db_query("CREATE TABLE phpjob_ladmin (admin varchar(50) NOT NULL default '', passw varchar(50) NOT NULL default '', ipaddress varchar(32) NOT NULL default '')"); 
                SQL_CHECK(0,"SQL Error at ".__FILE__.":".(__LINE__-1));
                bx_db_query("INSERT INTO phpjob_ladmin VALUES ('".md5($HTTP_POST_VARS['adm_login']."Php-Jobsite admin authorization")."', '".md5($HTTP_POST_VARS['adm_passwd']."Php-Jobsite admin authorization")."','".$HTTP_SERVER_VARS['REMOTE_ADDR']."')");
                SQL_CHECK(0,"SQL Error at ".__FILE__.":".(__LINE__-1));
            }
            else {
                $updatepwd = false;
                bx_admin_error("Invalid Login type!");
            }
       }    
       else {
           $updatepwd = false;
           bx_admin_error(TEXT_SAFE_MODE_ALERT);
       }
       if ($updatepwd) {
           ?>
             <table width="100%" cellspacing="0" cellpadding="2" border="0" class="tedit">
              <tr>
                  <td align="center"><b><?php echo TEXT_ADMIN_PASSWORD_SUCCESS;?></b></td>
              </tr>
              <tr>
               <td>
                   <TABLE border="0" cellpadding="4" cellspacing="0" width="100%" class="edit">
                   <tr>
                       <td colspan="2"><br></td>
                   </tr>
                   <tr>
                       <td align="center"><b><?php echo TEXT_ADMIN_PASSWORD_PROTECTION_SUCCESS;?></b><br></td>
                   </tr>
                   <tr>
                         <td colspan="2" align="center"><br><a href="<?php echo HTTP_SERVER_ADMIN.FILENAME_INDEX;?>">Home</a></td>
                   </tr>
                   </table>
               </td></tr></table>
           <?php
      }//end if $updatepwd
}//end if ($todo == "chpasswd")
else {
?>
<form method="post" action="<?php echo HTTP_SERVER_ADMIN.FILENAME_ADMIN_PASSWORD;?>" name="chpasswd" onSubmit="return check_form();">
<input type="hidden" name="todo" value="chpasswd">
<table width="100%" cellspacing="0" cellpadding="2" border="0" class="tedit">
<tr>
     <td align="center"><b>Change Admin password</b></td>
 </tr>
 <tr>
   <td><TABLE border="0" cellpadding="4" cellspacing="0" width="100%" class="edit">
<tr>
    <td colspan="2"><br></td>
</tr>
<tr>
        <td colspan="2"><font class="error"><b><?php echo TEXT_ADMIN_PASSWORD_NOTE;?></b></font><br><font class="smalltext"><?php echo TEXT_ADMIN_PASSWORD_SYSTEM_NOTE;?></font></td>
</tr>
<TR>
    <td align="right" valign="top"><b><?php echo TEXT_ADMIN_LOGIN_TYPE;?>:</b></td><td nowrap><input type="radio" name="protect" value="1" class="radio"<?php if(eregi("linux",PHP_OS) || eregi("unix",PHP_OS) || eregi("bsd",PHP_OS)){ echo " checked";};?>> <b>Apache htaccess (Linux/Unix OS)</b> <br><input type="radio" name="protect" value="2" class="radio"<?php if(eregi("win",PHP_OS)){ echo " checked";};?>> <b>Session login (Windows OS)</b></td>
</TR>
<tr>
        <td align="right"><b><?php echo TEXT_ADMIN_PASSWORD_LOGIN;?>:</b></td><td valign="top"><input type="text" name="adm_login"></td>
</tr>
<tr>
        <td align="right"><b><?php echo TEXT_ADMIN_PASSWORD_PASSWORD;?>:</b></td><td valign="top"><input type="text" name="adm_passwd"></td>
</tr>
<tr>
        <td align="right"><b><?php echo TEXT_ADMIN_PASSWORD_REPASSWORD;?>:</b></td><td valign="top"><input type="text" name="adm_repasswd"></td>
</tr>
<tr>
        <td colspan="2" align="center"><br><input type="submit" name="update" value="Update"></td>
</tr>
</table>

</td></tr></table>
</form>
<?php
}//end else if ($todo == "chpasswd")
?>