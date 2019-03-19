<?php
$error_title = "deleting language";
$del = true;
if ($HTTP_POST_VARS['todo'] == "dellng"){
          if(ADMIN_SAFE_MODE == "yes") {
                $error_title = "deleting language!";
                bx_admin_error(TEXT_SAFE_MODE_ALERT);
          }//end if ADMIN_SAFE_MODE == yes
          else {
              $folders = $HTTP_POST_VARS['folders']; 
			  $folder_table_lang = substr($folders,0,2);
              if($folders == DEFAULT_LANGUAGE) {
                   bx_admin_error("The default language (".$folders.") can not be deleted.");
                   $del = false;
              }
              if (!file_exists(DIR_LANGUAGES.$folders)) {
                   bx_admin_error("Language directory doesn't exists ".DIR_LANGUAGES.$folders.".");
                   $del = false;
              }
              if (!file_exists(DIR_LANGUAGES.$folders."/mail")) {
                   bx_admin_error("Language mail directory doesn't exists ".DIR_LANGUAGES.$folders."/mail.");
                   $del = false;
              }
              if (!file_exists(DIR_LANGUAGES.$folders."/html")) {
                   bx_admin_error("Language html directory doesn't exists ".DIR_LANGUAGES.$folders."/html.");
                   $del = false;
              }
              if (!file_exists(DIR_IMAGES.$folders)) {
                   bx_admin_error("Language image directory doesn't exists ".DIR_IMAGES.$folders.".");
                   $del = false;
              }
              if ($del) {
                   $files = getFiles(DIR_LANGUAGES.$folders."/mail");
                   for ($i=0; $i<count($files); $i++) {
                         if (($del) && (!unlink(DIR_LANGUAGES.$folders."/mail/".$files[$i])) ) {
                                   bx_admin_error("Unable to delete language mail file ".DIR_LANGUAGES.$folders."/mail/".$files[$i].".");
                                   $del = false;
                         }
                   }
              }
              if (($del) && (!rmdir(DIR_LANGUAGES.$folders."/mail")) ) {
                   bx_admin_error("Unable to delete directory ".DIR_LANGUAGES.$folders."/mail.");
                   $del = false;
              }
              if ($del) {
                   $files = getFiles(DIR_LANGUAGES.$folders."/html");
                   for ($i=0; $i<count($files); $i++) {
                         if (($del) && (!unlink(DIR_LANGUAGES.$folders."/html/".$files[$i])) ) {
                                   bx_admin_error("Unable to delete language html file ".DIR_LANGUAGES.$folders."/html/".$files[$i].".");
                                   $del = false;
                         }
                   }
              }
              if (($del) && (!rmdir(DIR_LANGUAGES.$folders."/html")) ) {
                   bx_admin_error("Unable to delete directory ".DIR_LANGUAGES.$folders."/html.");
                   $del = false;
              }
              if ($del) {
                   $files = getFiles(DIR_LANGUAGES.$folders);
                   for ($i=0; $i<count($files); $i++) {
                         if (($del) && (!unlink(DIR_LANGUAGES.$folders."/".$files[$i])) ) {
                                   bx_admin_error("Unable to delete language file ".DIR_LANGUAGES.$folders."/".$files[$i].".");
                                   $del = false;
                         }
                   }
              }
              if (($del) && (!rmdir(DIR_LANGUAGES.$folders)) ) {
                   bx_admin_error("Unable to delete directory ".DIR_LANGUAGES.$folders.".");
                   $del = false;
              }
              if ($del) {
                   $files = getFiles(DIR_IMAGES.$folders);
                   for ($i=0; $i<count($files); $i++) {
                         if (($del) && (!unlink(DIR_IMAGES.$folders."/".$files[$i])) ) {
                                   bx_admin_error("Unable to delete language image file ".DIR_IMAGES.$folders."/".$files[$i].".");
                                   $del = false;
                         }
                   }
               }
               if (($del) && (!rmdir(DIR_IMAGES.$folders)) ) {
                   bx_admin_error("Unable to delete image directory ".DIR_IMAGES.$folders.".");
                   $del = false;
               }
               if (($del) && (!unlink(DIR_LANGUAGES.$folders.".php")) ) {
                   bx_admin_error("Unable to delete base language file ".DIR_LANGUAGES.$folders.".php.");
                   $del = false;
               }
               if (file_exists(DIR_FLAG.$folders.".gif")) {
                 $imgsize = getimagesize(DIR_FLAG.$folders.".gif");
                 if (($del) && (!unlink(DIR_FLAG.$folders.".gif")) ) {
                       bx_admin_error("Unable to delete flag image file ".DIR_FLAG.$folders.".gif");
                       $del = false;
                 }
               }
               if (file_exists(DIR_FLAG.$folders.".jpg")) {
                 $imgsize = getimagesize(DIR_FLAG.$folders.".jpg");
                 if (($del) && (!unlink(DIR_FLAG.$folders.".jpg")) ) {
                       bx_admin_error("Unable to delete flag image file ".DIR_FLAG.$folders.".jpg");
                       $del = false;
                 }
               }
               if (file_exists(DIR_FLAG.$folders.".png")) {
                 $imgsize = getimagesize(DIR_FLAG.$folders.".png");
                 if (($del) && (!unlink(DIR_FLAG.$folders.".png")) ) {
                       bx_admin_error("Unable to delete flag image file ".DIR_FLAG.$folders.".png");
                       $del = false;
                 }
               }
               if ($del) {
                    bx_db_query("ALTER TABLE $bx_db_table_banner_types DROP typename_".substr($HTTP_POST_VARS['folders'],0,2));
					SQL_CHECK(0,"SQL Error at ".__FILE__.":".(__LINE__-1));
               }
              if ($del) {
                   ?>
                     <table width="100%" cellspacing="0" cellpadding="2" border="0" class="tedit">
                      <tr>
                          <td align="center"><b><?php echo TEXT_DELETE_LANGUAGE_SUCCESS;?></b></td>
                      </tr>
                      <tr>
                       <td><TABLE border="0" cellpadding="4" cellspacing="0" width="100%" class="edit">
                           <tr>
                               <td colspan="2"><br></td>
                           </tr>
                           <tr>
                               <td><b><?php echo TEXT_LANGUAGE_DELETE_SUCCESS;?></b><br></td>
                           </tr>
                           <tr>
                                 <td colspan="2" align="center"><br><a href="<?php echo HTTP_SERVER_ADMIN.FILENAME_INDEX;?>">Home</a></td>
                           </tr>
                           </table>
                       </td></tr></table>
                   <?php
              }//end if $create
        }      
}
else {
?>
<form method="post" action="<?php echo HTTP_SERVER_ADMIN.FILENAME_ADMIN_DELETE_LANGUAGE;?>" name="dellng" onSubmit="return check_form_dellng();">
<input type="hidden" name="todo" value="dellng">
<table width="100%" cellspacing="0" cellpadding="2" border="0" class="tedit">
<tr>
     <td align="center"><b>Delete language</b></td>
 </tr>
 <tr>
   <td><TABLE border="0" cellpadding="4" cellspacing="0" width="100%" class="edit">
<tr>
    <td colspan="2"><br></td>
</tr>
<tr>
        <td valign="top"><b><?php echo TEXT_SELECT_DELETE_LANGUAGE;?>:</b><br><font class="smalltext"><B><?php echo TEXT_SELECT_DELETE_LANGUAGE_NOTE;?></B></font></td><td valign="top">
<?php
  $dirs = getFolders(DIR_LANGUAGES);
  for ($i=0; $i<count($dirs); $i++) {
       if (file_exists(DIR_LANGUAGES.$dirs[$i].".php")) {
              echo "<input type=\"radio\" name=\"folders\" value=\"".$dirs[$i]."\" class=\"radio\">".$dirs[$i]."<br>";
       }
  }
?>
</td></tr>
<tr>
        <td colspan="2" align="center"><br><input type="submit" name="delete" value="Delete Language"></td>
</tr>
</table>

</td></tr></table>
</form>
<?php
}
?>