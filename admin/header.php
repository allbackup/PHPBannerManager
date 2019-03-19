<?php 
function is_admin()
{
    return true;
}

$user_id = $HTTP_GET_VARS['user_id'] ? $HTTP_GET_VARS['user_id'] : $HTTP_POST_VARS['user_id'];
?><!-- header //-->
<html>
<head>
<title><?php echo SITE_TITLE;?></title>
<?php echo META;?>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $CHARSET_OPTION;?>">
<script language="Javascript">
<!--
 if (navigator.appName == "Netscape") {
	  if(navigator.userAgent.indexOf("Netscape6") > 0) {
         document.write("<link rel=\"stylesheet\" href=\"job.css\" type=\"text/css\">");
      } else {
	  	 if(navigator.userAgent.indexOf("4.") > 0) {
        	document.write("<link rel=\"stylesheet\" href=\"jobn.css\" type=\"text/css\">");
      	 } else {
		 	document.write("<link rel=\"stylesheet\" href=\"job.css\" type=\"text/css\">");
		 }
	  }
   }
else if (navigator.userAgent.indexOf("MSIE") > 0) {
      document.write("<link rel=\"stylesheet\" href=\"job.css\" type=\"text/css\">");
}
else {
      document.write("<link rel=\"stylesheet\" href=\"job.css\" type=\"text/css\">");
}
function myopen(filename, w_width, w_height) {
         mywindow = window.open(filename,'company','toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=yes,copyhistory=no,width='+w_width+',height='+w_height);
}
//-->
</script>
<noscript>
    <link rel="stylesheet" href="job.css" type="text/css">
</noscript>
<?php

define("BIG_TABLE_WIDTH","600");
define("TABLE_HEAD_BGCOLOR","#9FCBFF");
define("TABLE_BORDERCOLOR","#9FCBFF");
define("INSIDE_TABLE_BG_COLOR","#ffffff");
define("INSIDE_TABLE_BG_COLOR2","#ffffff");
define("TABLE_HEAD_FONT_COLOR","#ffffff");
define("ADMIN_SITE_BGCOLOR","#EFEFEF"); 

if (!empty($jsfile)) {
     echo "\n<script language=\"JavaScript1.1\">";
     echo "\n<!--\n";
     include(DIR_JS.$jsfile);
     echo "\n//-->\n</script>\n";
}
?>
</head>
<body>
<table border="0" width="100%" cellspacing="2" cellpadding="0" align="center">
 <tr>
 <td>
 <?php
include("header.html");
?>
 </td>
 </tr>
</table>
<!-- header_eof //-->
<?php
if (file_exists(DIR_SERVER_ROOT."install.php") || file_exists(DIR_SERVER_ROOT."mysql.sql")){?>
     <table align="center" width="<?php echo MAIN_NAVIGATION_WIDTH;?>" border="0" cellspacing="2" cellpadding="0">
     <tr>
             <td>
             <table border="0" width="100%" cellspacing="0" cellpadding="0" align="center" bgcolor="#EFEFEF" style="border: 1px solid #000000;">
             <tr>
                 <td bgcolor="#BBBBBB"><font color="#FF0000">&nbsp;<b>Important Admin Note!</b></font></td>
             </tr>
             <tr>
                 <td width="100%">&nbsp;<font color="#FF0000">Please delete <b>"install.php"</b> and <b>"mysql.sql"</b> from the root directory!</font></td>
             </tr>
             <tr>
                 <td><font size="2" color="#000000">&nbsp; - <?php echo DIR_SERVER_ROOT."install.php";?> </font></td>
             </tr>
             <tr>
                 <td><font size="2" color="#000000">&nbsp; - <?php echo DIR_SERVER_ROOT."mysql.sql";?> </font></td>
             </tr>
             <tr>
                 <td width="100%">&nbsp;<font color="#FF0000">
                    Those files are only for installing the script.</td>
             </tr>       
             <tr>
                 <td width="100%">&nbsp;<font color="#FF0000">       
                    Please remove them so nobody else can't access them and affect your installation! Thanks.</td>
             </tr>
            </table>
            </td>
     </tr>
     </table>
   <?php
}
?>
<!-- body //-->
<table border="0" width="<?php echo HTML_WIDTH;?>" cellspacing="2" cellpadding="0" align="center">
 <tr>
 <!-- left_navigation //-->
 <td valign="top" width="<?php echo LEFT_NAVIGATION_WIDTH;?>">
  <table border="0" cellspacing="0" cellpadding="0" width="100%">
     <tr>
      <td width="100%" align="center">
      <?php
      include("left_navigation.php");
      ?>
      </td>
     </tr>
  </table>
 </td>
 <!-- end left_navigation //-->
 <!-- body_navigation //-->
 <td valign="top" width="<?php echo MAIN_NAVIGATION_WIDTH;?>">
  <table border="0" cellspacing="0" cellpadding="0" width="100%">
     <tr>
      <td width="100%">