<?php
include('admin_design.php');
include('../application_config_file.php');
include('admin_auth.php');
include(DIR_LANGUAGES.$language."/".FILENAME_ADMIN_FORM);
$jsfile = "admin_lng.js";
include("header.php");
if ($HTTP_POST_VARS['todo']=="delete") {
    if ($HTTP_POST_VARS['folders']==DEFAULT_LANGUAGE) {
        $create=false;
        $error_title = "deleting language database tables!";
        bx_admin_error("You can delete the default language database tables!");
    }
    else {
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
            $create=true;
            bx_db_query("DROP TABLE ".$bx_table_prefix."_jobcategories_".$folder_table_lang);
            SQL_CHECK(0,"SQL Error at ".__FILE__.":".(__LINE__-1));
            bx_db_query("DROP TABLE ".$bx_table_prefix."_locations_".$folder_table_lang);
            SQL_CHECK(0,"SQL Error at ".__FILE__.":".(__LINE__-1));
            bx_db_query("DROP TABLE ".$bx_table_prefix."_jobtypes_".$folder_table_lang);
            SQL_CHECK(0,"SQL Error at ".__FILE__.":".(__LINE__-1));
            bx_db_query("DROP TABLE ".$bx_table_prefix."_help_".$folder_table_lang);
            SQL_CHECK(0,"SQL Error at ".__FILE__.":".(__LINE__-1));
            bx_db_query("DROP TABLE ".$bx_table_prefix."_helpsubcat_".$folder_table_lang);
            SQL_CHECK(0,"SQL Error at ".__FILE__.":".(__LINE__-1));
            bx_db_query("DROP TABLE ".$bx_table_prefix."_helpcat_".$folder_table_lang);
            SQL_CHECK(0,"SQL Error at ".__FILE__.":".(__LINE__-1));
            bx_db_query("DROP TABLE ".$bx_table_prefix."_helptoc_".$folder_table_lang);
            SQL_CHECK(0,"SQL Error at ".__FILE__.":".(__LINE__-1));
            bx_db_query("DROP TABLE ".$bx_table_prefix."_pricing_".$folder_table_lang);
            SQL_CHECK(0,"SQL Error at ".__FILE__.":".(__LINE__-1));
            bx_db_query("DROP TABLE ".$bx_table_prefix."_jpricing_".$folder_table_lang);
            SQL_CHECK(0,"SQL Error at ".__FILE__.":".(__LINE__-1));
            bx_db_query("DELETE FROM ".$bx_table_prefix."_languages where lang_name = '".$folders."'");
            SQL_CHECK(0,"SQL Error at ".__FILE__.":".(__LINE__-1));
    }
    if ($create) {
           ?>
             <table width="100%" cellspacing="0" cellpadding="2" border="0" class="tedit">
              <tr>
                  <td align="center"><b>Language database Created!</b></td>
              </tr>
              <tr>
               <td bgcolor="#000000">
                   <TABLE border="0" cellpadding="4" cellspacing="0" width="100%" class="edit">
                   <tr>
                       <td colspan="2"><br></td>
                   </tr>
                   <tr>
                       <td><b>New Language database deleted!</b><br></td>
                   </tr>
                   <tr>
                       <td colspan="2"><br></td>
                   </tr>
                   </table>
               </td></tr></table>
      <?php
      }//end if $create
}
else {
    ?>
    <form method="post" action="delete_lng_db.php" name="dellng" onSubmit="return check_form_dellng();">
<input type="hidden" name="todo" value="delete">
<table width="100%" cellspacing="0" cellpadding="2" border="0" align="center" class="tedit">
<tr>
     <td align="center"><b>Delete language database tables:</b></td>
 </tr>
 <tr>
   <td>
    <TABLE border="0" cellpadding="4" cellspacing="0" width="100%" class="edit">
    <tr>
        <td colspan="2"><br></td>
    </tr>
    <tr>
            <td valign="top" width="70%"><b>Please select one of the following languages to delete db:</b></td>
            <td valign="top" width="30%">
    <?php
          $lang_query=bx_db_query("SELECT * FROM ".$bx_table_prefix."_languages");
          SQL_CHECK(0,"SQL Error at ".__FILE__.":".(__LINE__-1));
          while ($lang_result=bx_db_fetch_array($lang_query)){
              echo "<input type=\"radio\" name=\"folders\" value=\"".$lang_result['lang_name']."\" class=\"radio\">".$lang_result['lang_name']."<br>";
          }
    ?>
    </td></tr>
    <tr>
            <td colspan="2" align="center"><br><input type="submit" name="save" value="Delete Language Database Tables!"></td>
    </tr>
    </table>
</td></tr></table>
</form>
<?php
}
include("footer.php");
?>