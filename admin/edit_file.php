<?php
if ($HTTP_POST_VARS['todo']=="preview") {
   include ('admin_design.php');
   include ('../application_config_file.php');
   include ('admin_auth.php');
   if ($HTTP_POST_VARS['type']=="template") {
         $HTTP_POST_VARS['test_html_file'] = bx_unhtmlspecialchars(stripslashes($HTTP_POST_VARS['test_html_file']));
         $lng = $HTTP_POST_VARS['lng'];
         $template_file = $HTTP_POST_VARS['filename'];
         include(DIR_LANGUAGES.$template_file.".cfg.php");
         reset($fields);
         while (list($h, $v) = each($fields)) {
                  $HTTP_POST_VARS['test_html_file'] = eregi_replace($v[0],$v[2],$HTTP_POST_VARS['test_html_file']);
         }
   }
   echo $HTTP_POST_VARS['test_html_file'];
   bx_exit();
}
if ($HTTP_POST_VARS['todo']=="saveasfile") {
    $gzip_file=true;
    include ('admin_design.php');
    include ('../application_config_file.php');
    include ('admin_auth.php');
    $fname= basename($HTTP_POST_VARS['filename']);
    if (eregi("\,php$",$fname)) {
        $fname .= ".html";
    }
    header("Content-disposition: attachment; filename=".$fname."");    
    header("Content-type: application/octetstream");
    header("Pragma: no-cache");
    header("Expires: 0");
    echo fread(fopen(DIR_LANGUAGES.$HTTP_POST_VARS['lng']."/".$HTTP_POST_VARS['filename'],"r"),filesize(DIR_LANGUAGES.$HTTP_POST_VARS['lng']."/".$HTTP_POST_VARS['filename']));
    bx_exit();
}
if ($HTTP_GET_VARS['todo']=="copy") {
    include ('admin_design.php');
    include ('../application_config_file.php');
    include ('admin_auth.php');
    //print DIR_LANGUAGES.$HTTP_GET_VARS['lng'].urldecode($HTTP_GET_VARS['filename'])."<br>";
    //print DIR_LANGUAGES.$HTTP_GET_VARS['new_lang'].urldecode($HTTP_GET_VARS['filename'])."<br>";
    if (file_exists(DIR_LANGUAGES.$HTTP_GET_VARS['lng'].urldecode($HTTP_GET_VARS['filename']))) {
       @unlink(DIR_LANGUAGES.$HTTP_GET_VARS['lng'].urldecode($HTTP_GET_VARS['filename']));
    }
    @copy(DIR_LANGUAGES.$HTTP_GET_VARS['new_lang'].urldecode($HTTP_GET_VARS['filename']),DIR_LANGUAGES.$HTTP_GET_VARS['lng'].urldecode($HTTP_GET_VARS['filename']));
    include("header.php");
    ?>
    <script language="Javascript">
     <!--
        document.write('<form method="post" action="<?php echo HTTP_SERVER_ADMIN."edit_file.php?ref=".time();?>" name="redirect">');
        document.write('<input type="hidden" name="todo" value="editfile">');
        document.write('<input type="hidden" name="editfile" value="<?php echo urldecode($HTTP_GET_VARS['filename']);?>">');
        document.write('<input type="hidden" name="lng" value="<?php echo $HTTP_GET_VARS['lng'];?>">');
        document.write('</form>');
        document.redirect.submit();
      //-->
    </script>
    <?php         
    include("footer.php");
    bx_exit();
}
include('admin_design.php');
include('../application_config_file.php');
include('admin_auth.php');
include(DIR_LANGUAGES.$language."/".FILENAME_ADMIN_FORM);
$jsfile = "admin_mail.js";
include("header.php");
include(DIR_ADMIN.FILENAME_ADMIN_EDIT_FILE_FORM);
include("footer.php");
?>