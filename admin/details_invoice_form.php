<?php include(DIR_LANGUAGES.$language."/".FILENAME_ADMIN_FORM);
if ($HTTP_GET_VARS['printit']=="yes") {
?>
<table bgcolor="#FFFFFF" width="100%" border="0" cellspacing="0" cellpadding="0">
<TR><TD colspan="2"><hr></TD></TR>
<TR bgcolor="#FFFFFF">
  <TD colspan="2" width="100%" align="right"><?php echo TEXT_INVOICE_DETAILS.$invoice_result['opid'].")";?></TD>
</TR>
<TR><TD colspan="2"><hr></TD></TR>
</table>
<table bgcolor="#FFFFFF" width="100%" border="0" cellspacing="0" cellpadding="2">
<TR>
  <TD valign="top">
    <table bgcolor="#FFFFFF" width="100%" border="0" cellspacing="0" cellpadding="0">
      <TR>
       <td align="center"><B><?php echo TEXT_COMPANY_INFORMATION;?>:</B></TD>
      </TR>
      <TR>
       <td align="center"><?php echo nl2br(COMPANY_INFORMATION);?></TD>
      </TR>
      <TR>
       <td align="center"><br><B><?php echo TEXT_PAYMENT_INFORMATION;?>:</B></TD>
      </TR>
      <TR>
       <td align="center"><br><?php echo nl2br(PAYMENT_INFORMATION);?></TD>
      </TR>
    </TABLE>
  </TD>
  </TR>
  <TR>
  <TD valign="top" align="center"><br>
    <table width="100%" border="0" cellspacing="0" cellpadding="2">
       <TR>
          <td align="center" colspan="2"><B><?php echo TEXT_INVOICE_INFORMATION;?>:</B></TD>
      </TR>
      <tr><td colspan="2">&nbsp;</tr>
      <TR>
       <td align="right"><?php echo TEXT_INVOICE_NUMBER;?>:</td><td><?php echo $invoice_result['opid'];?></TD>
      </TR>
      <TR>
       <td align="right"><?php echo TEXT_DATE_ADDED;?>:</td><td><?php echo $invoice_result['date_added'];?></TD>
      </TR>
      <TR>
           <td align="right" valign="top"><?php echo TEXT_OPERATION_DESCRIPTION;?>:</td>
		   <td><B>

           <?=TEXT_BANNER_PURCHASE.' : '.$banner_invoice_result['typename'.$slng].'<br>'.TEXT_BANNER_PURCHASE_NR.' : '.(($banner_invoice_result['i_purchased_nr']=='-1')?TEXT_FLAT.', '.$banner_invoice_result['i_period'].TEXT_MONTH:$banner_invoice_result['i_purchased_nr'])?></b></TD>

		  </TR>

      <TR>
       <td align="right"><?php echo TEXT_LIST_PRICE;?>:</td><td><?php echo bx_format_price($invoice_result['listprice'],$invoice_result['icurrency']);?></TD>
      </TR>
    <?php if(USE_DISCOUNT == "yes") {?>
      <TR>
       <td align="right"><?php echo TEXT_DISCOUNT;?>:</td><td><?php echo bx_format_price((($invoice_result['listprice']*$invoice_result['discount'])/100),$invoice_result['icurrency'])." (".$invoice_result['discount']." %)";?></TD>
      </TR>
     <?php }?>
     <?php if(USE_VAT == "yes") {
         if (USE_DISCOUNT == "yes") {?>
          <TR>
           <td align="right"><?php echo TEXT_DISCOUNTED_PRICE;?>:</td><td><?php echo bx_format_price($invoice_result['listprice']-(($invoice_result['listprice']*$invoice_result['discount'])/100),$invoice_result['icurrency']);?></TD>
          </TR>
         <?php }?>
     <TR>
       <td align="right"><?php echo TEXT_VAT_PROCENT;?>:</td><td><?php echo bx_format_price(((($invoice_result['listprice']-($invoice_result['listprice']*$invoice_result['discount'])/100))*$invoice_result['vat']/100),$invoice_result['currency'])." (".$invoice_result['vat']." %)";?></TD>
     </TR> 
      <?php }?>
      <TR>
       <td align="right"><?php echo TEXT_TOTAL_PRICE;?>:</td><td><?php echo bx_format_price($invoice_result['totalprice'],$invoice_result['icurrency']);?></TD>
      </TR>
      <?php
       if ($invoice_result['paid']=="Y" && $invoice_result['validated']=="Y") {
         ?>
			<TR>
		   <td><B><?php echo 'User description';?>:
           <?=$invoice_result['info']?></b></TD>
		  </TR>
          <TR>
           <TD colspan="2"><br><b><?php echo TEXT_INVOICE_PAID;?></b></TD>
          </TR>
          <TR>
            <td colspan="2"><?php echo TEXT_PAYMENT_METHOD;?>: <B><?php echo ${TEXT_PAYMENT_OPT.$invoice_result['payment_mode']};?></b></TD>
          </TR>
          <?php
            if($invoice_result['payment_mode'] == 1) {
              $trans_query = bx_db_query("SELECT * from ".$bx_table_prefix."_cctransactions where opid = '".$invoice_result['opid']."'");
              SQL_CHECK(0,"SQL Error at ".__FILE__.":".(__LINE__-1));
              if(bx_db_num_rows($trans_query)>0) {
                  $trans_result = bx_db_fetch_array($trans_query);
                      ?>
                          <TR>
                               <td colspan="2"><?php echo TEXT_TRANSACTION_NUMBER;?>: <B><?php echo $trans_result['transid'];?></B></TD>
                          </TR>
                      <?php
                  }
              }
          ?>
          <TR>
            <td colspan="2"><?php echo TEXT_PAYMENT_DATE;?>: <B><?php echo bx_format_date($invoice_result['payment_date'], DATE_FORMAT);?></B></TD>
          </TR>
          <TR>
            <td colspan="2"><?php echo TEXT_PAYMENT_DESCRIPTION;?>: <B><?php echo $invoice_result['description'];?></B></TD>
          </TR>
         <?php
        }//end if ($invoice_result['paid']=="Y" && $invoice_result['validated']=="Y")
      ?>
    </TABLE>
  </TD>
</TR>
<TR><TD colspan="2"><hr></TD></TR>
<TR><TD colspan="2" align="right"><small>&nbsp;<?php echo SITE_NAME;?> - <?php echo date('Y-m-d h:i:s A');?></small></TD></TR>
</table>
<?php    
}
else {
?>
<form action="<?php echo HTTP_SERVER_ADMIN.FILENAME_ADMIN_VALIDATION;?>" method="post">
<table width="100%" cellspacing="0" cellpadding="2" border="0" class="tedit">
<tr>
     <td align="center"><b>Invoice details</b></td>
</tr>
<tr>
<td>
<TABLE border="0" cellpadding="0" cellspacing="0" width="100%" class="edit">
<tr><td colspan="2" align="right" style="padding: 10px;"><font class="smalltext"><b><?php echo TEXT_PRINTER_FRIENDLY;?></b>&nbsp;&nbsp;</font><a href="javascript: ;" onmouseOver="window.status='<?php echo TEXT_PRINTER_FRIENDLY;?>'; return true;" onmouseOut="window.status=''; return true;" onClick="newwind = window.open('<?php echo HTTP_SERVER;?>print_version.php?url='+escape('<?php echo HTTP_SERVER_ADMIN."details.php";?>?action=<?php echo $HTTP_GET_VARS['action'];?>&opid=<?php echo $HTTP_GET_VARS['opid'];?>&printit=yes'),'_blank','scrollbars=yes,menubar=no,resizable=yes,location=no,width=500,height=520,left=0,top=0,screenX=0,screenY=0');"><?php echo bx_image(HTTP_IMAGES.$language."/printit.gif",0,"");?></a></td></tr>
<TR>
  <TD valign="top" colspan="2">
    <TABLE border="0" cellpadding="0" cellspacing="0" width="100%" class="edit">
      <TR>
       <td align="center"><B><?php echo TEXT_COMPANY_INFORMATION;?>:</TD>
      </TR>
      <TR>
       <td align="center"><br><?php echo nl2br(COMPANY_INFORMATION);?></TD>
      </TR>
      <TR>
       <td align="center"><br><B><?php echo TEXT_PAYMENT_INFORMATION;?>:</B></TD>
      </TR>
      <TR>
       <td align="center"><br><?php echo nl2br(PAYMENT_INFORMATION);?></TD>
      </TR>
    </TABLE>
  </TD>
  </TR>
  <TR>
  <TD valign="top" colspan="2">
    <TABLE border="0" cellpadding="0" cellspacing="0" width="100%" class="edit">
       <TR>
       <td align="center"><B><?php echo TEXT_INVOICE_INFORMATION;?>:</B></TD>
      </TR>
      <TR>
       <td align="center"><br><?php echo TEXT_INVOICE_NUMBER.": ".$invoice_result['opid'];?></TD>
      </TR>
       <TR>
       <td align="center"><?php echo TEXT_PAYMENT_DATE.": ".$invoice_result['payment_date'];?></TD>
      </TR>
      <TR>
           <td align="center" valign="top"><?php echo TEXT_OPERATION_DESCRIPTION;?>:<B>

           <?=TEXT_BANNER_PURCHASE.' : '.$invoice_result['typename'.$slng].'<br>'.TEXT_BANNER_PURCHASE_NR.' : '.(($invoice_result['i_purchased_nr']=='-1')?TEXT_FLAT.', '.$invoice_result['i_period'].TEXT_MONTH:$invoice_result['i_purchased_nr'])?></b></TD>

		  </TR>
      <TR>
       <td align="center"><?php echo TEXT_LIST_PRICE.": ".bx_format_price($invoice_result['totalprice'],$invoice_result['icurrency']);?></TD>
      </TR>
     <?php if(USE_VAT == "yes") {?>

      <TR>
          <td align="center"><?php echo TEXT_VAT_PROCENT;?>:<?php echo bx_format_price(((($invoice_result['totalprice']))*$invoice_result['vat']/100),$invoice_result['currency'])." (".$invoice_result['vat']." %)";?></TD>
      </TR> 
      <?php }?>
      <TR>
       <td align="center"><?php echo TEXT_TOTAL_PRICE.": ".bx_format_price($invoice_result['totalprice'],$invoice_result['icurrency']);?></TD>
      </TR>
      <?php
       if ($invoice_result['paid']=="Y" && $invoice_result['validated']=="Y") {
      ?>
	  <TR>
		   <td><B><?php echo 'User description';?>:
           <?=$invoice_result['info']?></b></TD>
		  </TR>
          <TR>
           <TD><br><font class="error"><B><b><?php echo TEXT_INVOICE_PAID;?></b></TD>
          </TR>
          <TR>
            <td><?php echo TEXT_PAYMENT_METHOD;?>: <font class="error"><B><?php echo ${TEXT_PAYMENT_OPT.$invoice_result['payment_mode']};?></b></FONT></TD>
          </TR>
          <?php
       if($invoice_result['payment_mode'] == 1) {
                  $trans_query = bx_db_query("SELECT * from ".$bx_table_prefix."_cctransactions where opid = '".$invoice_result['opid']."'");
                  SQL_CHECK(0,"SQL Error at ".__FILE__.":".(__LINE__-1));
                 if(bx_db_num_rows($trans_query)>0) {
                      $trans_result = bx_db_fetch_array($trans_query);
                      ?>
                          <TR>
                               <td colspan="2"><?php echo TEXT_TRANSACTION_NUMBER;?>: <font class="error"><B><?php echo $trans_result['transid'];?></B></font></TD>
                          </TR>
                      <?php
                  }
         }
        ?>
        <TR>
            <td><?php echo TEXT_PAYMENT_DATE;?>: <font class="error"><B><?php echo $invoice_result['payment_date'];?></B></FONT></TD>
         </TR>
         <TR>
            <td><?php echo TEXT_PAYMENT_DESCRIPTION;?>: <font class="error"><B><?php echo $invoice_result['description'];?></B></FONT></TD>
          </TR>
         <?php
        }//end if ($invoice_result['paid']=="Y")
    else {
      ?>
		<TR>
		   <td><B><?php echo 'User description';?>:
           <?=$invoice_result['info']?></b></TD>
		  </TR>
         <TR>
              <TD><br><font class="error"><B><b><?php echo TEXT_INVOICE_NOTPAID;?></b></TD>
         </TR>
      <?php
      }
      ?>
    </TABLE>
  </TD>
</TR>
<TR>
  <td align="center" colspan="2" width="100%" style="padding-top: 20px; padding-left: 10px; padding-right: 10px; padding-bottom: 10px;">
  <table align="center" width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
        <td width="40%">
            <INPUT type="button" name="btnCancel" onclick="self.history.back()" value="<?php echo TEXT_BUTTON_CANCEL;?>">&nbsp;&nbsp;&nbsp;&nbsp;
        </td>
        <TD align="right" width="60%"><INPUT type="submit" name="btnDelete" value="<?php echo TEXT_BUTTON_DELETE;?>" onClick="return confirm('<?php echo eregi_replace("'","\'",TEXT_CONFIRM_INVOICE_DELETE);?>');"></TD>
    </tr>
  </table>
 </td>  
</TR>
<input type="hidden" name="opid" value="<?php echo $HTTP_GET_VARS['opid'];?>">
<input type="hidden" name="action" value="<?php echo $HTTP_GET_VARS['action'];?>">
</TABLE>
</td></tr></table>
</form>
<?php }?>