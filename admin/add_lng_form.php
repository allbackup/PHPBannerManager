<?php
$error_title = "creating the new language";
$create = true;
if($HTTP_POST_VARS['lng']) {
    $lng=$HTTP_POST_VARS['lng'];
}
elseif ($HTTP_GET_VARS['lng']){
     $lng = $HTTP_GET_VARS['lng'];
}
else {
    $lng = '';
}
$lng_table_lang = substr($lng,0,2);
if($HTTP_POST_VARS['folders']) {
    $folders=$HTTP_POST_VARS['folders'];
}
elseif ($HTTP_GET_VARS['folders']){
     $folders = $HTTP_GET_VARS['folders'];
}
else {
    $folders = '';
}
$folder_table_lang = substr($folders,0,2);
if ($HTTP_POST_VARS['todo'] == "addlng"){
      if (empty($lng)) {
           bx_admin_error("Please enter a language name.");
           $create = false;
      }
      $dirs = getFolders(DIR_LANGUAGES);
      for ($i=0; $i<count($dirs); $i++) {
               if (file_exists(DIR_LANGUAGES.$dirs[$i].".php")) {
                      if($lng_table_lang == substr($dirs[$i],0,2)) {
                          bx_admin_error("Invalid language name (the first 2 letters from the language must be unique).");
                          $create = false;
                      }
               }
      }
      if (($create) && (empty($folders))) {
           bx_admin_error("Please select a base language.");
           $create = false;
      }
     if (($create) && (file_exists(DIR_LANGUAGES.$lng))) {
           bx_admin_error("Language directory already exists ".DIR_LANGUAGES.$lng.".");
           $create = false;
     }
     if (($create) && (file_exists(DIR_LANGUAGES.$lng."/mail"))) {
           bx_admin_error("Language mail directory already exists ".DIR_LANGUAGES.$lng."/mail".".");
           $create = false;
     }
     if (($create) && (file_exists(DIR_LANGUAGES.$lng."/html"))) {
           bx_admin_error("Language mail directory already exists ".DIR_LANGUAGES.$lng."/html".".");
           $create = false;
     }
     if (($create) && (file_exists(DIR_IMAGES.$lng))) {
           bx_admin_error("Language image directory already exists ".DIR_LANGUAGES.$lng.".");
           $create = false;
     }
     if (($create) && (!mkdir(DIR_LANGUAGES.$lng, 0777)) ) {
           bx_admin_error("Unable to create directory ".DIR_LANGUAGES.$lng.".");
           $create = false;
     }
     else {
         @chmod(DIR_LANGUAGES.$lng, 0777);
     }
     if (($create) && (!mkdir(DIR_LANGUAGES.$lng."/mail", 0777)) ) {
           bx_admin_error("Unable to create directory ".DIR_LANGUAGES.$lng."/mail.");
           $create = false;
     }
     else {
         @chmod(DIR_LANGUAGES.$lng."/mail", 0777);
     }
     if (($create) && (!mkdir(DIR_LANGUAGES.$lng."/html", 0777)) ) {
           bx_admin_error("Unable to create directory ".DIR_LANGUAGES.$lng."/html.");
           $create = false;
     }
     else {
         @chmod(DIR_LANGUAGES.$lng."/html", 0777);
     }
     if (($create) && (!mkdir(DIR_IMAGES.$lng, 0777)) ) {
           bx_admin_error("Unable to create directory ".DIR_IMAGES.$lng.".");
           $create = false;
     }
     else {
         @chmod(DIR_IMAGES.$lng, 0777);
     }
     if (($create) && (!copy(DIR_LANGUAGES.$folders.".php",DIR_LANGUAGES.$lng.".php")) ) {
           bx_admin_error("Unable to copy/create base language file ".DIR_LANGUAGES.$lng.".php.");
           $create = false;
     }
     if (($create) && (!chmod(DIR_LANGUAGES.$lng.".php", 0777))) {
           bx_admin_error("Unable to change permissions for file ".DIR_LANGUAGES.$lng.".php.");
           $create = false;
     }
     if ($create) {
           $files = getFiles(DIR_LANGUAGES.$folders);
           for ($i=0; $i<count($files); $i++) {
                 if (($create) && (!copy(DIR_LANGUAGES.$folders."/".$files[$i],DIR_LANGUAGES.$lng."/".$files[$i]))) {
                           bx_admin_error("Unable to copy/create base language file ".DIR_LANGUAGES.$lng."/".$files[$i].".");
                           $create = false;
                 }
                 else {
                       @chmod(DIR_LANGUAGES.$lng."/".$files[$i], 0777);
                 }
           }//end for
      }
      if ($create) {
           $files = getFiles(DIR_LANGUAGES.$folders."/mail");
           for ($i=0; $i<count($files); $i++) {
                 if (($create) && (!copy(DIR_LANGUAGES.$folders."/mail/".$files[$i],DIR_LANGUAGES.$lng."/mail/".$files[$i]))) {
                           bx_admin_error("Unable to copy/create base language file ".DIR_LANGUAGES.$lng."/mail/".$files[$i].".");
                           $create = false;
                 }
                 else {
                          @chmod(DIR_LANGUAGES.$lng."/mail/".$files[$i], 0777);
                 }
           }//end for
      }
      if ($create) {
           $files = getFiles(DIR_LANGUAGES.$folders."/html");
           for ($i=0; $i<count($files); $i++) {
                 if (($create) && (!copy(DIR_LANGUAGES.$folders."/html/".$files[$i],DIR_LANGUAGES.$lng."/html/".$files[$i]))) {
                           bx_admin_error("Unable to copy/create base language file ".DIR_LANGUAGES.$lng."/html/".$files[$i].".");
                           $create = false;
                 }
                 else {
                          @chmod(DIR_LANGUAGES.$lng."/html/".$files[$i], 0777);
                 }
           }//end for
      }
      if ($create) {
           $files = getFiles(DIR_IMAGES.$folders);
           for ($i=0; $i<count($files); $i++) {
                 if (($create) && (!copy(DIR_IMAGES.$folders."/".$files[$i],DIR_IMAGES.$lng."/".$files[$i]))) {
                           bx_admin_error("Unable to copy/create language images ".DIR_IMAGES.$lng."/".$files[$i].".");
                           $create = false;
                 }
                 else {
                          @chmod(DIR_IMAGES.$lng."/".$files[$i], 0777);
                 }
           }
      }
      if ($create) {
		$SQL = "ALTER TABLE $bx_db_table_banner_types ADD typename_".substr($HTTP_POST_VARS['lng'],0,2)." VARCHAR(50) NOT NULL after typename_".substr($HTTP_POST_VARS['folders'],0,2);
		bx_db_query($SQL); 
		SQL_CHECK(0,"SQL Error at ".__FILE__.":".(__LINE__-1));
		$SQL = "update $bx_db_table_banner_types set typename_".substr($HTTP_POST_VARS['lng'],0,2)."=typename_".substr($HTTP_POST_VARS['folders'],0,2);
		bx_db_query($SQL);
		SQL_CHECK(0,"SQL Error at ".__FILE__.":".(__LINE__-1));

      }
      if ($create) {
           ?>
             <form method="post" action="<?php echo HTTP_SERVER_ADMIN.FILENAME_ADMIN_EDIT_LANGUAGE;?>" name="addlng">
             <input type="hidden" name="todo" value="editlng">
             <input type="hidden" name="folders" value="<?php echo $lng;?>">
             <table width="100%" cellspacing="0" cellpadding="2" border="0" class="tedit">
              <tr>
                  <td align="center"><b><?php echo TEXT_LANGUAGE_SUCCESS;?></b></td>
              </tr>
              <tr>
               <td>
                   <TABLE border="0" cellpadding="4" cellspacing="0" width="100%" class="edit">
                   <tr>
                       <td colspan="2"><br></td>
                   </tr>
                   <tr>
                       <td><b><?php echo TEXT_LANGUAGE_CREATION_SUCCESS;?></b><br></td>
                   </tr>
                   <tr>
                       <td colspan="2"><br></td>
                   </tr>
                   <tr>
                         <td colspan="2" align="center"><br><input type="submit" name="edit" value="Edit Language"></td>
                   </tr>
                   </table>
               </td></tr></table>
               </form>
           <?php
      }//end if $create
}
else {
?>
<form method="post" action="<?php echo HTTP_SERVER_ADMIN.FILENAME_ADMIN_ADD_LANGUAGE;?>" name="addlng" onSubmit="return check_form();">
<input type="hidden" name="todo" value="addlng">
<table width="100%" cellspacing="0" cellpadding="2" border="0" class="tedit">
<tr>
     <td align="center"><b>Add language</b></td>
 </tr>
 <tr>
   <td><TABLE border="0" cellpadding="4" cellspacing="0" width="100%" class="edit">
<tr>
    <td colspan="2"><br></td>
</tr>
<tr>
        <td align="right"><b><?php echo TEXT_DEFINE_LANGUAGE_NAME;?>:</b><br><font class="smalltext"><?php echo TEXT_DEFINE_LANGUAGE_NAME_NOTE;?></font></td><td valign="top"><input type="text" name="lng" onChange="tolowercase();"></td>
</tr>
<tr>
    <td colspan="2"><br></td>
</tr>
<tr>
        <td valign="top"><b><?php echo TEXT_SELECT_LANGUAGE;?>:</b><br><font class="smalltext"><?php echo TEXT_SELECT_LANGUAGE_NOTE;?></font></td><td valign="top">
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
        <td colspan="2" align="center"><br><input type="submit" name="save" value="Create Language"></td>
</tr>
</table>

</td></tr></table>
</form>
<?php
}
?>