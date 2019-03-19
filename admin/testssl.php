<?php
include ('admin_design.php');
include ('../application_config_file.php');
include ('admin_auth.php');
if($HTTP_GET_VARS['todo'] == "testssl") {
   header("Location: ".HTTPS_SERVER.eregi_replace(HTTP_SERVER, "", HTTP_SERVER_ADMIN)."/testssl.php"); 
   bx_exit();
}
else {
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>SSL Test</title>
</head>
<body>
     <table width="100%" bgcolor="#000000" cellpadding="3" cellspacing="1">
        <tr>
            <td nowrap bgcolor="#EEEEEE">Enable SSL</td><td bgcolor="#FFFFFF"><font color="#0000FF"><?php echo ($HTTP_SERVER_VARS['HTTPS'] == "on") ?"yes":"no";?></font></td>
        </tr>
        <tr>
            <td nowrap bgcolor="#EEEEEE">Php Version</td><td bgcolor="#FFFFFF"><font color="#0000FF"><?php echo phpversion();?></font></td>
        </tr>
    </table>
    <table width="100%" bgcolor="#FFFFFF" cellpadding="0" cellspacing="0">
    <tr>
            <td nowrap bgcolor="#FFFFFF" align="center">&nbsp;</td>
    </tr>
    <tr>
            <td nowrap bgcolor="#FFFFFF" align="center"><a href="javascript: ;" onClick="window.close();"><font color="#333333">Close</font></a></td>
    </tr>
    </table>
</body>
</html>
<?php }
bx_exit();
?>