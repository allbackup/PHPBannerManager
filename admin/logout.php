<?php
include ('admin_design.php');
include ('../application_config_file.php');
bx_session_unregister("adm_user");
bx_session_unregister("adm_pass");
bx_session_unregister("adm_ip");
?>
<!DOCTYPE HTML PUBLIC "-//IETF//DTD HTML 2.0//EN">
            <HTML><HEAD>
            <TITLE><?php echo SITE_TITLE;?></TITLE>
            </HEAD><BODY>
            <table align="center" width="70%" cellpadding="0" cellspacing="0" style="border: 1px solid #000000; background: #DDDDDD">
            <tr><td>&nbsp;</td></tr>
            <tr><td align="center"><font style="font-size: 18px; font-face: arial;"><b>You</b> have been logged out.</font></td></tr>
            <tr><td align="center"><font style="font-size: 14; font-face: arial;">Last login from: <b><?php echo $HTTP_SERVER_VARS['REMOTE_ADDR'];?></b></font></td></tr>
            <tr><td align="center"><a href="<?php echo HTTP_SERVER_ADMIN.FILENAME_INDEX;?>"><font style="font-size: 12px; font-face: arial;">Click here to log back in.</font></a></td></tr>            
            <tr><td>&nbsp;</td></tr>
            </table>
            <HR>
            </BODY></HTML>
<?php
bx_exit();
?>