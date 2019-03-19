<?php
include(DIR_LANGUAGES.$language."/".FILENAME_INVOICES_FORM);
if ($HTTP_GET_VARS['printit']=="yes") {
?>
   <table bgcolor="#FFFFFF" width="100%" border="0" cellspacing="0" cellpadding="0">
   <TR><TD colspan="2"><hr></TD></TR>
   <TR bgcolor="#FFFFFF">
      <TD colspan="2" width="100%" align="center" class="headertdjob"><b><?php echo TEXT_INVOICE_DETAILS." ".$invoice_result['opid'].")";?></b></TD>
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
        <table bgcolor="<?php echo TABLE_BGCOLOR;?>" width="100%" border="0" cellspacing="0" cellpadding="2">
           <TR>
              <td align="center" colspan="2"><B><?php echo TEXT_INVOICE_INFORMATION;?>:</B></TD>
          </TR>
          <tr><td colspan="2">&nbsp;</tr>
          <TR>
           <td align="right"><?php echo TEXT_INVOICE_NUMBER;?>:</td><td><?php echo $invoice_result['opid'];?></TD>
          </TR>

		  <?if ($invoice_result['op_type']==3) //banner purchase
		  {?>
          <TR>
           <td align="right" valign="top"><?=TEXT_OPERATION_DESCRIPTION?>:</td><td><B> <?=TEXT_BANNER_PURCHASE.' '.$invoice_result['typename'.$slng].'<br>'.TEXT_BANNER_PURCHASE_NR.' '.(($invoice_result['i_purchased_nr']=='-1')?TEXT_FLAT.', '.$invoice_result['i_period'].TEXT_MONTH:$invoice_result['i_purchased_nr'])?></b></TD>
          </TR>		  	
		  <?}?>
          <TR>
           <td align="right"><?php echo TEXT_LIST_PRICE;?>:</td><td><?php echo bx_format_price($invoice_result['totalprice'],$invoice_result['icurrency']);?></TD>
          </TR>
          <?php if(USE_VAT == "yes") {?>
           <TR>
           <td align="right"><?php echo TEXT_VAT_PROCENT;?>:</td><td><?php echo bx_format_price(((($invoice_result['totalprice']-($invoice_result['totalprice']*$invoice_result['discount'])/100))*$invoice_result['vat']/100),$invoice_result['currency'])." (".$invoice_result['vat']." %)";?></TD>
          </TR> 
          <?php }?>
          <TR>
           <td align="right"><?php echo TEXT_TOTAL_PRICE;?>:</td><td><?php echo bx_format_price($invoice_result['totalprice'],$invoice_result['icurrency']);?></TD>
          </TR>
          <?php
           if ($invoice_result['paid']=="Y" && $invoice_result['validated']=="Y") {
             ?>
              <TR>
               <TD colspan="2"><br><b><?php echo TEXT_INVOICE_PAID;?></b></TD>
              </TR>
              <TR>
                <td colspan="2"><?php echo TEXT_PAYMENT_METHOD;?>: <B><?php echo ${TEXT_PAYMENT_OPT.$invoice_result['payment_mode']};?></b></TD>
              </TR>
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
 </table>
<?php    
}
else {
?>
<table bgcolor="<?php echo TABLE_BGCOLOR;?>" width="100%" border="0" cellspacing="0" cellpadding="2">
   <TR bgcolor="#FFFFFF">
      <TD colspan="2" width="100%" align="center" class="headertdjob"><?php echo TEXT_INVOICE_DETAILS." ".$invoice_result['opid'].")";?></TD>
   </TR>
   <TR><TD colspan="2"><table cellpadding="0" cellspacing="0" border="0" width="100%">
                <tr>
                    <td bgcolor="<?php echo TABLE_HEADING_BGCOLOR;?>" height="1"><?php echo bx_image_width(HTTP_IMAGES.$language."/pix-t.gif",1,1,0,"");?></td>
                </tr>
       </table></TD>
    </TR>
</table>
<table bgcolor="<?php echo TABLE_BGCOLOR;?>" width="100%" border="0" cellspacing="0" cellpadding="4">
    <tr><td colspan="2" align="right"><font face="<?php echo TEXT_FONT_FACE;?>" size="1" color="<?php echo TEXT_FONT_COLOR;?>"><?php echo TEXT_PRINTER_FRIENDLY;?>&nbsp;&nbsp;</font><a href="javascript: ;" onmouseOver="window.status='<?php echo TEXT_PRINTER_FRIENDLY;?>'; return true;" onmouseOut="window.status=''; return true;" onClick="newwind = window.open('<?php echo bx_make_url(HTTP_SERVER."print_version.php", "auth_sess", $bx_session);?>&url='+escape('<?php echo HTTP_SERVER.FILENAME_INVOICES."?action=view&opid=".$HTTP_GET_VARS['opid']."&printit=yes";?>'),'_blank','scrollbars=yes,menubar=no,resizable=yes,location=no,width=500,height=520,screenX=50,screenY=100');"><?php echo bx_image(HTTP_IMAGES.$language."/printit.gif",0,"");?></a></td></tr>
    <TR>
      <TD valign="top">
        <table bgcolor="<?php echo TABLE_BGCOLOR;?>" width="100%" border="0" cellspacing="0" cellpadding="0">
          <TR>
           <td align="center"><font face="<?php echo TEXT_FONT_FACE;?>" size="<?php echo TEXT_FONT_SIZE;?>" color="<?php echo TEXT_FONT_COLOR;?>"><B><?php echo TEXT_COMPANY_INFORMATION;?>:</B></FONT></TD>
          </TR>
          <TR>
           <td align="center"><br><font face="<?php echo TEXT_FONT_FACE;?>" size="<?php echo TEXT_FONT_SIZE;?>" color="<?php echo TEXT_FONT_COLOR;?>"><?php echo nl2br(COMPANY_INFORMATION);?></FONT></TD>
          </TR>
          <TR>
           <td align="center"><br><font face="<?php echo TEXT_FONT_FACE;?>" size="<?php echo TEXT_FONT_SIZE;?>" color="<?php echo TEXT_FONT_COLOR;?>"><B><?php echo TEXT_PAYMENT_INFORMATION;?>:</B></FONT></TD>
          </TR>
          <TR>
           <td align="center"><br><font face="<?php echo TEXT_FONT_FACE;?>" size="<?php echo TEXT_FONT_SIZE;?>" color="<?php echo TEXT_FONT_COLOR;?>"><?php echo nl2br(PAYMENT_INFORMATION);?></FONT></TD>
          </TR>
        </TABLE>
      </TD>
      </TR>
      <TR>
      <TD valign="top" align="center"><br>
        <table bgcolor="<?php echo TABLE_BGCOLOR;?>" width="100%" border="0" cellspacing="0" cellpadding="2">
           <TR>
              <td align="center" colspan="2"><font face="<?php echo TEXT_FONT_FACE;?>" size="<?php echo TEXT_FONT_SIZE;?>" color="<?php echo TEXT_FONT_COLOR;?>"><B><?php echo TEXT_INVOICE_INFORMATION;?>:</B></FONT></TD>
          </TR>
          <tr><td colspan="2">&nbsp;</tr>
          <TR>
           <td align="right"><font face="<?php echo TEXT_FONT_FACE;?>" size="<?php echo TEXT_FONT_SIZE;?>" color="<?php echo TEXT_FONT_COLOR;?>"><?php echo TEXT_INVOICE_NUMBER;?>:</font></td><td><font face="<?php echo TEXT_FONT_FACE;?>" size="<?php echo TEXT_FONT_SIZE;?>" color="<?php echo TEXT_FONT_COLOR;?>"><?php echo $invoice_result['opid'];?></FONT></TD>
          </TR>
		  <?if ($invoice_result['op_type']==3) //banner purchase
		  {?>
          <TR>
           <td align="right" valign="top"><font face="<?=TEXT_FONT_FACE?>" size="<?=TEXT_FONT_SIZE?>" color="<?=TEXT_FONT_COLOR?>"><?=TEXT_OPERATION_DESCRIPTION?>:</FONT></td><td><font face="<?=REQUIRED_TEXT_FONT_FACE?>" size="<?=REQUIRED_TEXT_FONT_SIZE?>" color="<?=REQUIRED_TEXT_FONT_COLOR?>"><B> <?=TEXT_BANNER_PURCHASE.' : '.$invoice_result['typename'.$slng].'<br>'.TEXT_BANNER_PURCHASE_NR.' : '.(($invoice_result['i_purchased_nr']=='-1')?TEXT_FLAT.', '.$invoice_result['i_period'].TEXT_MONTH:$invoice_result['i_purchased_nr'])?></b></font></TD>
          </TR>		  	
		  <?}?>
          <TR>
           <td align="right"><font face="<?php echo TEXT_FONT_FACE;?>" size="<?php echo TEXT_FONT_SIZE;?>" color="<?php echo TEXT_FONT_COLOR;?>"><?php echo TEXT_LIST_PRICE;?>:</font></td><td><font face="<?php echo TEXT_FONT_FACE;?>" size="<?php echo TEXT_FONT_SIZE;?>" color="<?php echo TEXT_FONT_COLOR;?>"><?php echo bx_format_price($invoice_result['totalprice'],$invoice_result['icurrency']);?></FONT></TD>
          </TR>
          <?php if(USE_VAT == "yes") {?>
          <TR>
           <td align="right"><font face="<?php echo TEXT_FONT_FACE;?>" size="<?php echo TEXT_FONT_SIZE;?>" color="<?php echo TEXT_FONT_COLOR;?>"><?php echo TEXT_VAT_PROCENT;?>:</font></td><td><font face="<?php echo TEXT_FONT_FACE;?>" size="<?php echo TEXT_FONT_SIZE;?>" color="<?php echo TEXT_FONT_COLOR;?>"><?php echo bx_format_price(((($invoice_result['totalprice']-($invoice_result['totalprice']*$invoice_result['discount'])/100))*$invoice_result['vat']/100),$invoice_result['currency'])." (".$invoice_result['vat']." %)";?></FONT></TD>
          </TR>
          <?php }?>
          <TR>
           <td align="right"><font face="<?php echo TEXT_FONT_FACE;?>" size="<?php echo TEXT_FONT_SIZE;?>" color="<?php echo TEXT_FONT_COLOR;?>"><?php echo TEXT_TOTAL_PRICE;?>:</font></td><td><font face="<?php echo TEXT_FONT_FACE;?>" size="<?php echo TEXT_FONT_SIZE;?>" color="<?php echo TEXT_FONT_COLOR;?>"><?php echo bx_format_price($invoice_result['totalprice'],$invoice_result['icurrency']);?></FONT></TD>
          </TR>
          <?php
           if ($invoice_result['paid']=="Y" && $invoice_result['validated']=="Y") {
             ?>
              <TR>
               <TD colspan="2"><br><font face="<?php echo REQUIRED_TEXT_FONT_FACE;?>" size="<?php echo REQUIRED_TEXT_FONT_SIZE;?>" color="<?php echo REQUIRED_TEXT_FONT_COLOR;?>"><b><?php echo TEXT_INVOICE_PAID;?></b></TD>
              </TR>
              <TR>
                <td colspan="2"><font face="<?php echo TEXT_FONT_FACE;?>" size="<?php echo TEXT_FONT_SIZE;?>" color="<?php echo TEXT_FONT_COLOR;?>"><?php echo TEXT_PAYMENT_METHOD;?>: </FONT><font face="<?php echo REQUIRED_TEXT_FONT_FACE;?>" size="<?php echo REQUIRED_TEXT_FONT_SIZE;?>" color="<?php echo REQUIRED_TEXT_FONT_COLOR;?>"><B><?php echo ${TEXT_PAYMENT_OPT.$invoice_result['payment_mode']};?></b></FONT></TD>
              </TR>
              <TR>
                <td colspan="2"><font face="<?php echo TEXT_FONT_FACE;?>" size="<?php echo TEXT_FONT_SIZE;?>" color="<?php echo TEXT_FONT_COLOR;?>"><?php echo TEXT_PAYMENT_DATE;?>: </FONT><font face="<?php echo REQUIRED_TEXT_FONT_FACE;?>" size="<?php echo REQUIRED_TEXT_FONT_SIZE;?>" color="<?php echo REQUIRED_TEXT_FONT_COLOR;?>"><B><?php echo bx_format_date($invoice_result['payment_date'], DATE_FORMAT);?></B></FONT></TD>
              </TR>
              <TR>
                <td colspan="2"><font face="<?php echo TEXT_FONT_FACE;?>" size="<?php echo TEXT_FONT_SIZE;?>" color="<?php echo TEXT_FONT_COLOR;?>"><?php echo TEXT_PAYMENT_DESCRIPTION;?>: </FONT><font face="<?php echo REQUIRED_TEXT_FONT_FACE;?>" size="<?php echo REQUIRED_TEXT_FONT_SIZE;?>" color="<?php echo REQUIRED_TEXT_FONT_COLOR;?>"><B><?php echo $invoice_result['description'];?></B></FONT></TD>
              </TR>
             <?php
            }//end if ($invoice_result['paid']=="Y" && $invoice_result['validated']=="Y")
          ?>
        </TABLE>
      </TD>
 </TR>
 <?php
 if ($HTTP_GET_VARS['action']=='cancel')
  {
 ?>
    <TR bgcolor="<?php echo TABLE_HEADING_BGCOLOR;?>">
      <TD colspan="2" align="right" valign="middle" width="100%" style="border: 1px solid #000000">
        <font face="<?php echo HEADING_FONT_FACE;?>" size="<?php echo HEADING_FONT_SIZE;?>" color="<?php echo HEADING_FONT_COLOR;?>"><B><?php echo TEXT_CANCEL_PAYMENT.$invoice_result['opid'].")";?></B></FONT>
      </TD>
   </TR>
   <tr><td colspan="2">&nbsp;</td></tr>
    <TR>
       <td colspan="2" align="left" valign="middle" width="100%"><font face="<?php echo TEXT_FONT_FACE;?>" size="<?php echo TEXT_FONT_SIZE;?>" color="<?php echo TEXT_FONT_COLOR;?>"><B><?php echo TEXT_SURE_CANCEL_PAYMENT;?></B></FONT></TD>
    </TR>
   <tr><td colspan="2">&nbsp;</td></tr>
   <TR>
     <TD colspan="2" align="center" valign="middle" width="100%"><font face="<?php echo DISPLAY_TEXT_FONT_FACE;?>" size="<?php echo DISPLAY_TEXT_FONT_SIZE;?>" color="<?php echo DISPLAY_TEXT_FONT_COLOR;?>"><a href="<?php echo bx_make_url(HTTP_SERVER.FILENAME_INVOICES."?action=del&del=y&opid=".$invoice_result['opid'], "auth_sess", $bx_session);?>"><?php echo bx_image(HTTP_IMAGES.$language."/yes.gif",0,TEXT_YES);?></a>&nbsp;<a href="<?php echo bx_make_url(HTTP_SERVER.FILENAME_INVOICES."?action=del&del=n&opid=".$invoice_result['opid'], "auth_sess", $bx_session);?>"><?php echo bx_image(HTTP_IMAGES.$language."/no.gif",0,TEXT_NO);?></a></font></TD>
    </TR>
  <?php
  }//end if ($HTTP_GET_VARS['action']=='cancel')
  ?>
 <?php
 if ($HTTP_GET_VARS['action']=='pay')
 { 
    $onebutton = false;
    include(DIR_SERVER_ROOT."cc_payment_settings.php");
    $i = 1;
    $max = 2;
    if(CC_PAYMENT == "off" && INVOICE_PAYMENT == "on") {
      $i = 2;
      $onebutton = true;
    }
    else if(INVOICE_PAYMENT == "off" && CC_PAYMENT == "on") {
      $max = 1;
      $onebutton = true;
    }
    else {
        
    }
    //if($invoice_result['listprice']==0) {
    if(CC_PROCESSOR_TYPE=='manual') {

            ?>
            <form action="<?php echo bx_make_url(HTTP_SERVER.FILENAME_PAYMENT, "auth_sess", $bx_session);?>" method="post">
            <TR>
              <TD colspan="2" valign="middle" width="100%"><font face="<?php echo TEXT_FONT_FACE;?>" size="<?php echo TEXT_FONT_SIZE;?>" color="<?php echo TEXT_FONT_COLOR;?>">
             <INPUT type="hidden" name="opid" value="<?php echo $HTTP_GET_VARS['opid'];?>">
             <INPUT type="hidden" name="compid" value="<?php echo $HTTP_SESSION_VARS['employerid'];?>">     
             <INPUT type="hidden" name="payment_mode" value="0">     
             </font></TD>
            </TR>
            <TR>
             <TD colspan="2" align="center" valign="middle" width="100%"><font face="<?php echo TEXT_FONT_FACE;?>" size="<?php echo TEXT_FONT_SIZE;?>" color="<?php echo TEXT_FONT_COLOR;?>">
             <input type="submit" name="upgrade" value="<?php echo TEXT_UPGRADE_NOW;?>"></font>
             </TD>
            </TR>
            </form>
    <?php
    }
    else {
            if(!$onebutton) {
         ?>
            <TR bgcolor="<?php echo TABLE_HEADING_BGCOLOR;?>">
              <TD colspan="2" align="right" valign="middle" width="100%" style="border: 1px solid #000000">
                <font face="<?php echo HEADING_FONT_FACE;?>" size="<?php echo HEADING_FONT_SIZE;?>" color="<?php echo HEADING_FONT_COLOR;?>"><B><?php echo TEXT_PAYMENT_SECTION.$invoice_result['opid'].")";?></B></FONT>
              </TD>
           </TR>
            <TR>
               <td colspan="2" align="left" valign="middle" width="100%"><font face="<?php echo TEXT_FONT_FACE;?>" size="<?php echo TEXT_FONT_SIZE;?>" color="<?php echo TEXT_FONT_COLOR;?>"><B><?php echo TEXT_SELECT_PAYMENT_MODE;?>:</B></FONT></TD>
            </TR>
            <?php
            }
            ?>
            <form action="<?php echo bx_make_url(((ENABLE_SSL == "yes") ? HTTPS_SERVER : HTTP_SERVER).FILENAME_PAYMENT, "auth_sess", $bx_session);?>" method="post">
            <TR>
              <TD colspan="2" valign="middle" width="100%"><font face="<?php echo TEXT_FONT_FACE;?>" size="<?php echo TEXT_FONT_SIZE;?>" color="<?php echo TEXT_FONT_COLOR;?>">
             <INPUT type="hidden" name="opid" value="<?php echo $HTTP_GET_VARS['opid'];?>">
             <INPUT type="hidden" name="compid" value="<?php echo $HTTP_SESSION_VARS['employerid'];?>">     
             <?php
                   while (${TEXT_PAYMENT_OPT.$i} && $i<=$max)
                    {
                         if($onebutton) {
                               echo '<input type="hidden" name="payment_mode" value="'.$i.'"';
                         }
                         else {
                               echo '<br><input type="radio" class="radio" name="payment_mode" value="'.$i.'"';
                         }
                         if ($i==1) {
                            echo " checked";
                         }
                         if(!$onebutton) {
                              echo '>'.${TEXT_PAYMENT_OPT.$i}."";
                         }
                         $i++;
                   }
             ?>
             </font></TD>
            </TR>
            <TR>
             <TD colspan="2" align="center" valign="middle" width="100%"><font face="<?php echo TEXT_FONT_FACE;?>" size="<?php echo TEXT_FONT_SIZE;?>" color="<?php echo TEXT_FONT_COLOR;?>">
             <?php echo bx_image_submit_nowidth(HTTP_IMAGES.$language."/pay.gif",0,TEXT_PAY);?></font>
             </TD>
            </TR>
          </form>
          <?php
          }//end if ($HTTP_GET_VARS['action']=='pay')
  }        
  ?>
</TABLE>
<?php
}      
?>