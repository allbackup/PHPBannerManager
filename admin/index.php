<?php
include ('admin_design.php');
include ('../application_config_file.php');
include ('admin_auth.php');
include("header.php");
if (DEBUG_MODE=="yes") {?>
    <table align="center" width="100%" border="0" cellspacing="2" cellpadding="0">
     <tr>
             <td>
             <table border="0" width="100%" cellspacing="0" cellpadding="0" align="center" bgcolor="#EFEFEF" style="border: 1px solid #000000;">
             <tr>
                 <td bgcolor="#BBBBBB"><font color="#FF0000">&nbsp;<b>Important Admin Note!</b></font></td>
             </tr>
             <tr>
                 <td width="100%"><font color="#FF0000">&nbsp;<b><a href="<?php echo HTTP_SERVER_ADMIN.FILENAME_ADMIN_SETTINGS;?>#DEBUG_MODE" style="color: #FF0000; font-size: 14px;">DEBUG MODE</a></b> is <b>ON</b>!</font></td>
             </tr>
             <tr>
                 <td width="100%"><font color="#FF0000">&nbsp;Please set <a href="<?php echo HTTP_SERVER_ADMIN.FILENAME_ADMIN_SETTINGS;?>#DEBUG_MODE" style="color: #FF0000; font-size: 14px;">DEBUG MODE</a> to <b>no</b> when you finished debuging.</td>
             </tr>       
            </table>
            </td>
     </tr>
     </table>
<?php
}
?>
<table bgcolor="#DBEEEE" border="0" cellspacing="0" cellpadding="0" width="100%" style="border: 1px solid #000000">
     <tr>
      <td width="100%" align="center" valign="top">
	  <font face="Arial" size="5" color="#0073AA"><b><i>Welcome to <?php echo SITE_TITLE;?> Admin Area</i></b></font>
      </td>
	 </tr>
  </table>
<?php include("footer.php");?>