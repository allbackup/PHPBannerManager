<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>Curl Test</title>
</head>
<body>
<?php
if(extension_loaded("curl")) {
    ?>
     <table width="100%" bgcolor="#000000" cellpadding="3" cellspacing="1">
        <tr>
            <td nowrap bgcolor="#EEEEEE">CURL support</td><td bgcolor="#FFFFFF"><font color="#0000FF">Enabled</font></td>
        </tr>
        <tr>
            <td nowrap bgcolor="#EEEEEE">CURL information</td><td nowrap bgcolor="#FFFFFF"><font color="#0000FF"><?php echo curl_version();?></font></td>
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
    <?php
}
else {
    ?>
     <table width="100%" bgcolor="#000000" cellpadding="3" cellspacing="1">
        <tr>
            <td nowrap bgcolor="#EEEEEE">CURL support</td><td bgcolor="#FFFFFF" nowrap>Disabled</td>
        </tr>
        <tr>
            <td nowrap bgcolor="#EEEEEE">Php Version</td><td bgcolor="#FFFFFF" nowrap><?php echo phpversion();?></td>
        </tr>
    </table>    
    <table width="100%" bgcolor="#FFFFFF" cellpadding="0" cellspacing="0">
    <tr>
            <td nowrap bgcolor="#FFFFFF" align="center">&nbsp;</td>
    </tr>
    <tr>
            <td bgcolor="#FFFFFF"><font color="#FF0000"><b>Sorry, you will not be able to use <?php echo urldecode($HTTP_GET_VARS['desc']);?> Payment Gataway.</b></font></td>
    </tr>
    <tr>
            <td nowrap bgcolor="#FFFFFF" align="center">&nbsp;</td>
    </tr>
    <tr>
            <td nowrap bgcolor="#FFFFFF" align="center"><a href="javascript: ;" onClick="window.close();"><font color="#333333">Close</font></a></td>
    </tr>
    </table>
    <?php
}
?>
</body>
</html>