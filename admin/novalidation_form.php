<?php include(DIR_LANGUAGES.$language."/".FILENAME_ADMIN_FORM);?>
<?php
 if (($HTTP_GET_VARS['action']=="upgrades" || $HTTP_GET_VARS['action']=="buyers") && ($HTTP_GET_VARS['opid']))
 {
  $company_query=bx_db_query("select * from ".$bx_db_table_banner_users.", ".$bx_table_prefix."_invoices where ".$bx_table_prefix."_invoices.opid = '".$HTTP_GET_VARS['opid']."' and ".$bx_db_table_banner_users.".user_id = ".$bx_table_prefix."_invoices.compid");
  SQL_CHECK(0,"SQL Error at ".__FILE__.":".(__LINE__-1));
  $company_result=bx_db_fetch_array($company_query);
  ?>
  <form action="<?php echo HTTP_SERVER_ADMIN.FILENAME_ADMIN_DECLINE;?>" method="post">
  <input type="hidden" name="opid" value="<?php echo $company_result['opid'];?>">
  <input type="hidden" name="action" value="<?php echo $HTTP_GET_VARS['action'];?>">
  <input type="hidden" name="compid" value="<?php echo $company_result['compid'];?>">
  <table width="100%" cellspacing="0" cellpadding="2" border="0" class="tedit">
  <tr>
     <td align="center"><b>Decline invoice</b></td>
 </tr>
 <tr>
   <td>
     <TABLE border="0" cellpadding="4" cellspacing="0" width="100%" class="edit">
    <TR><TD colspan="2"><font class="smalltext"><b><?php echo TEXT_INFORMATION;?>: <a href="javascript:myopen('<?php echo HTTP_SERVER_ADMIN;?>view.php?action=details&compid=<?php echo $HTTP_GET_VARS['compid'];?>', 500, 500);"><?php echo $company_result['company'];?></a></b></font></td></tr>
   <tr>
     <td align="right" valign="top"><?php echo TEXT_DECLINE_DESCRIPTION;?>:&nbsp;&nbsp;&nbsp;</td>
     <td><textarea name="description" rows="5" cols="35" size="30"></textarea></td>
   </tr>
   <tr>
     <td align="right"><?php echo TEXT_PAYMENT_MODE;?>:&nbsp;&nbsp;&nbsp;</td>
     <td><select name="payment_mode">
       <?php $i=1;
		while (${TEXT_PAYMENT_OPT.$i}) {
         echo '<option value="'.$i.'"';
		 if ($i == $company_result['payment_mode']) {
		     echo " selected";
		 }
		 echo '>'.${TEXT_PAYMENT_OPT.$i}.'</option>';
         $i++;
         }
       ?>
       </select>
     </td>
   </tr>
   <tr>
      <td colspan="2"	 align="center"><?php echo bx_image_submit_nowidth(HTTP_IMAGES.$language."/decline.gif",0,"Validate");?> </td>
   </tr>
   </table>
 </td></tr></table>
</form>
 <?php
 }//end if action 
 elseif ($HTTP_GET_VARS['action']=="jobseekers" && ($HTTP_GET_VARS['opid']))
 {
  $person_query=bx_db_query("select * from ".$bx_table_prefix."_persons, ".$bx_table_prefix."_jinvoices where ".$bx_table_prefix."_jinvoices.opid = '".$HTTP_GET_VARS['opid']."' and ".$bx_table_prefix."_persons.persid = ".$bx_table_prefix."_jinvoices.persid");
  SQL_CHECK(0,"SQL Error at ".__FILE__.":".(__LINE__-1));
  $person_result=bx_db_fetch_array($person_query);
  ?>
  <form action="<?php echo HTTP_SERVER_ADMIN.FILENAME_ADMIN_DECLINE;?>" method="post">
  <input type="hidden" name="opid" value="<?php echo $person_result['opid'];?>">
  <input type="hidden" name="action" value="<?php echo $HTTP_GET_VARS['action'];?>">
  <input type="hidden" name="compid" value="<?php echo $person_result['persid'];?>">
  <table width="100%" cellspacing="0" cellpadding="2" border="0" class="tedit">
  <tr>
     <td align="center"><b>Decline invoice</b></td>
 </tr>
 <tr>
   <td>
     <TABLE border="0" cellpadding="4" cellspacing="0" width="100%" class="edit">
    <TR><TD colspan="2"><font class="smalltext"><b><?php echo TEXT_INFORMATION;?>: <a href="javascript:myopen('<?php echo HTTP_SERVER_ADMIN;?>view.php?action=details&persid=<?php echo $HTTP_GET_VARS['persid'];?>', 500, 500);"><?php echo $person_result['name'];?></a></b></font></td></tr>
   <tr>
     <td align="right" valign="top"><?php echo TEXT_DECLINE_DESCRIPTION;?>:&nbsp;&nbsp;&nbsp;</td>
     <td><textarea name="description" rows="5" cols="35" size="30"></textarea></td>
   </tr>
   <tr>
     <td align="right"><?php echo TEXT_PAYMENT_MODE;?>:&nbsp;&nbsp;&nbsp;</td>
     <td><select name="payment_mode">
       <?php $i=1;
		while (${TEXT_PAYMENT_OPT.$i}) {
         echo '<option value="'.$i.'"';
		 if ($i == $person_result['payment_mode']) {
		     echo " selected";
		 }
		 echo '>'.${TEXT_PAYMENT_OPT.$i}.'</option>';
         $i++;
         }
       ?>
       </select>
     </td>
   </tr>
    <TR>
    <td align="center" colspan="2" width="100%" style="padding-top: 20px; padding-left: 10px; padding-right: 10px; padding-bottom: 10px;">
      <table align="center" width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
            <TD align="center" width="75%"><INPUT type="button" name="btnCancel" onclick="self.history.back()" value="<?php echo TEXT_BUTTON_CANCEL;?>">&nbsp;&nbsp;&nbsp;&nbsp;<input type="submit" name="decline" value="Decline"></TD>
        </tr>
      </table>
     </td>  
   </TR>
   </table>
 </td></tr></table>
</form>
 <?php
 }//end if action 
 ?>