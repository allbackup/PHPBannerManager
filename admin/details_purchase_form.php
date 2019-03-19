<?php include(DIR_LANGUAGES.$language."/".FILENAME_ADMIN_FORM);?>
<script language="Javascript" src="calendar.js"></script>
<form action="<?php echo HTTP_SERVER_ADMIN.FILENAME_ADMIN_VALIDATION;?>" method="post" name="buyers">
<input type="hidden" name="prycing_type" value="<?php echo $actual_result['pricing_id'];?>">
<input type="hidden" name="currency" value="<?php echo $currency;?>">
<input type="hidden" name="compid" value="<?php echo $HTTP_GET_VARS['compid'];?>">
<input type="hidden" name="opid" value="<?php echo $HTTP_GET_VARS['opid'];?>">
<input type="hidden" name="action" value="<?php echo $HTTP_GET_VARS['action'];?>">
<table width="100%" cellspacing="0" cellpadding="2" border="0" class="tedit">

 <tr>
     <td align="center"><b><?php echo TEXT_BANNER_PURCHASE." : ".$actual_invoice_result['typename'.$slng]; ?></b><br>
	 <?php echo TEXT_BANNER_PURCHASE_NR." : ".$actual_invoice_result['i_purchased_nr']; ?></td>
 </tr>

 <tr>
   <td>
     <TABLE border="0" cellpadding="4" cellspacing="0" width="100%" class="edit">
  <tr>
		<td colspan="2"><a href="javascript:myopen('<?php echo HTTP_SERVER_ADMIN."view.php?action=details&opid=".$HTTP_GET_VARS['opid'];?>', 600, 500);">View Invoice details</a></td>	  
  </tr> 
   <tr>
     <td align="right"><?php echo TEXT_DATE_PAYMENT;?>:&nbsp;&nbsp;&nbsp;</td>
     <td><input type="text" name="pdate_added" size="10" value="<?php echo date('Y-m-d');?>">&nbsp;
     <a href="javascript:show_calendar('buyers.pdate_added');" onmouseOver="window.status='Calendar'; return true;" onmouseOut="window.status=''; return true;" ><img src="images/calendar.gif" align="absmiddle" border="0" alt=""></a>
     </td>
   </tr>
   <tr>
     <td align="right" valign="top"><?php echo TEXT_INFO;?>:&nbsp;&nbsp;&nbsp;</td>
     <td><?php echo nl2br($actual_invoice_result['info']);?></td>
   </tr>
   <tr>
     <td align="right" valign="top"><?php echo TEXT_DESCRIPTION;?>:&nbsp;&nbsp;&nbsp;</td>
     <td><textarea name="description" rows="6" cols="50"></textarea></td>
   </tr>
   <?php
   if($cc_transaction == true) {
   include(DIR_FUNCTIONS."cclib/class.rc4crypt.php");
   include(DIR_SERVER_ROOT."cc_payment_settings.php");   
   $decoder=new rc4crypt();   
   if(ENABLE_SSL == "yes" && $HTTP_SERVER_VARS['HTTPS']!='on') {
       echo "<tr><td align=\"center\" colspan=\"2\">".TEXT_EXPLAIN_SENSITIVE_DATA."<br><a href=\"".eregi_replace(HTTP_SERVER,HTTPS_SERVER, HTTP_SERVER_ADMIN."details.php?action=buyers&compid=".$HTTP_GET_VARS['compid']."&opid=".$HTTP_GET_VARS['opid'])."\">Reload in a SSL enabled Page.</a></td></tr>";
   }
   else {
       ?>
       <tr>
           <td colspan="2"><hr></td>
       </tr>
       <tr>
         <td align="right" valign="top"><?php echo TEXT_TRANSACTION_NUMBER;?>:&nbsp;&nbsp;&nbsp;</td>
         <td><?php echo $cc_transaction_result['transid'];?></td>
       </tr>
       <tr>
         <td align="right" valign="top"><?php echo TEXT_INVOICE_NUMBER;?>:&nbsp;&nbsp;&nbsp;</td>
         <td><?php echo $cc_transaction_result['opid'];?></td>
       </tr>
       <tr>
         <td align="right" valign="top"><?php echo TEXT_CC_NAME;?>:&nbsp;&nbsp;&nbsp;</td>
         <td><?php echo $decoder->endecrypt(CRYPT_PHRASE,$cc_transaction_result['cc_name'],"de");?></td>
       </tr>
       <tr>
         <td align="right" valign="top"><?php echo TEXT_CC_TYPE;?>:&nbsp;&nbsp;&nbsp;</td>
         <td><?php echo $decoder->endecrypt(CRYPT_PHRASE,$cc_transaction_result['cc_type'],"de");?></td>
       </tr>
       <tr>
         <td align="right" valign="top"><?php echo TEXT_CC_NUM;?>:&nbsp;&nbsp;&nbsp;</td>
         <td><?php echo $decoder->endecrypt(CRYPT_PHRASE,$cc_transaction_result['cc_num'],"de");?></td>
       </tr>
       <tr>
         <td align="right" valign="top"><?php echo TEXT_CC_VERIFICATION_CODE;?>:&nbsp;&nbsp;&nbsp;</td>
         <td><?php echo $decoder->endecrypt(CRYPT_PHRASE,$cc_transaction_result['cc_cvc'],"de");?></td>
       </tr>
       <tr>
         <td align="right" valign="top"><?php echo TEXT_CC_EXPIRE;?>:&nbsp;&nbsp;&nbsp;</td>
         <td><?php echo $decoder->endecrypt(CRYPT_PHRASE,$cc_transaction_result['cc_exp'],"de");?></td>
       </tr>
       <?php
       if(CC_AVS == "yes") {
           ?>
           </tr>
             <td align="right" valign="top"><?php echo TEXT_CC_STREET;?>:&nbsp;&nbsp;&nbsp;</td>
             <td><?php echo $decoder->endecrypt(CRYPT_PHRASE,$cc_transaction_result['cc_street'],"de");?></td>
           </tr>
           </tr>
             <td align="right" valign="top"><?php echo TEXT_CC_CITY;?>:&nbsp;&nbsp;&nbsp;</td>
             <td><?php echo $decoder->endecrypt(CRYPT_PHRASE,$cc_transaction_result['cc_city'],"de");?></td>
           </tr>
           </tr>
             <td align="right" valign="top"><?php echo TEXT_CC_STATE;?>:&nbsp;&nbsp;&nbsp;</td>
             <td><?php echo $decoder->endecrypt(CRYPT_PHRASE,$cc_transaction_result['cc_state'],"de");?></td>
           </tr>
           </tr>
             <td align="right" valign="top"><?php echo TEXT_CC_ZIP;?>:&nbsp;&nbsp;&nbsp;</td>
             <td><?php echo $decoder->endecrypt(CRYPT_PHRASE,$cc_transaction_result['cc_zip'],"de");?></td>
           </tr>
           </tr>
             <td align="right" valign="top"><?php echo TEXT_CC_COUNTRY;?>:&nbsp;&nbsp;&nbsp;</td>
             <td><?php echo $decoder->endecrypt(CRYPT_PHRASE,$cc_transaction_result['cc_country'],"de");?></td>
           </tr>
           </tr>
             <td align="right" valign="top"><?php echo TEXT_CC_PHONE;?>:&nbsp;&nbsp;&nbsp;</td>
             <td><?php echo $decoder->endecrypt(CRYPT_PHRASE,$cc_transaction_result['cc_phone'],"de");?></td>
           </tr>
           </tr>
             <td align="right" valign="top"><?php echo TEXT_CC_EMAIL;?>:&nbsp;&nbsp;&nbsp;</td>
             <td><?php echo $decoder->endecrypt(CRYPT_PHRASE,$cc_transaction_result['cc_email'],"de");?></td>
           </tr>
           <?php
          }
       ?>
       <tr>
           <td colspan="2"><hr></td>
       </tr>
       <?php   
       }    
   }
   ?>
   <tr>
     <td align="right"><?php echo TEXT_PAYMENT_MODE;?>:&nbsp;&nbsp;&nbsp;</td>
     <td><select name="payment_mode">
       <?php $i=1;
            while (${TEXT_PAYMENT_OPT.$i}) {
             echo '<option value="'.$i.'"';
             if ($i == $actual_invoice_result['payment_mode']) {
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
                <td width="20%">
                    <INPUT type="button" name="btnCancel" onclick="self.history.back()" value="<?php echo TEXT_BUTTON_CANCEL;?>">
                </td>
                <TD align="left" width="80%">&nbsp;&nbsp;<input type="button" name="novalidation" value="Decline Upgrade" onCLick="location.href='<?php echo HTTP_SERVER_ADMIN.FILENAME_ADMIN_NOVALIDATION;?>?action=<?php echo $HTTP_GET_VARS['action'];?>&compid=<?php echo $HTTP_GET_VARS['compid'];?>&opid=<?php echo $HTTP_GET_VARS['opid'];?>'; return false;">&nbsp;&nbsp;<input type="submit" name="validate" value="Validate Upgrade"></TD>
            </tr>
          </table>
         </td>  
    </TR>
</table></td></tr></table>
</form>