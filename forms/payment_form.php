<?php include(DIR_LANGUAGES.$language."/".FILENAME_PAYMENT_FORM);?>
<?php if($HTTP_POST_VARS['payment_mode']==1)
  {
?>
<FORM action="<?php echo $merchanturl;?>" method="post">
 <INPUT type="hidden" name="x_Login" value="<?php echo $merchantlogin;?>">
 <INPUT type="hidden" name="x_Method" value="CC">
 <INPUT type="hidden" name="x_Amount" value="<?php echo $invoice_result['totalprice'];?>">
 <INPUT type="hidden" name="x_Show_Form" value="PAYMENT_FORM">
 <INPUT TYPE="HIDDEN" NAME="x_Receipt_Link_Method" VALUE="post">
 <INPUT TYPE="HIDDEN" NAME="x_Description" VALUE="<?php if($invoice_result['pricing_type']==0) {echo $invoice_result['pricing_title'];} else {echo TEXT_UPGRADE." ".$invoice_result['pricing_title'];}?>">
 <INPUT TYPE="HIDDEN" NAME="x_Receipt_Link_URL" VALUE="<?php echo bx_make_url(HTTP_SERVER.FILENAME_PROCESSING, "auth_sess", $bx_session);?>">
 <INPUT TYPE="HIDDEN" NAME="x_Receipt_Link_Text" VALUE="<?php echo TEXT_BACK_TO_MY_SITE;?>">
 <INPUT type="hidden" name="x_Invoice_Num" value="<?php echo $invoice_result['opid'];?>">
 <INPUT type="hidden" name="payment_mode" value="<?php echo $HTTP_POST_VARS['payment_mode'];?>">
 <INPUT type="hidden" name="opid" value="<?php echo $invoice_result['opid'];?>">
 <table bgcolor="<?php echo TABLE_BGCOLOR;?>" width="100%" border="0" cellspacing="0" cellpadding="2">
   <TR bgcolor="#FFFFFF">
      <TD width="100%" align="center" class="headertdjob"><?php echo TEXT_INVOICE_PAYMENT;?></TD>
   </TR>
   <TR><TD><table cellpadding="0" cellspacing="0" border="0" width="100%">
                <tr>
                    <td bgcolor="<?php echo TABLE_HEADING_BGCOLOR;?>" height="1"><?php echo bx_image_width(HTTP_IMAGES.$language."/pix-t.gif",1,1,0,"");?></td>
                </tr>
       </table></TD>
   </TR>
   <TR>
     <td><hr></td>
   </TR>
   <TR>
     <TD align="center"><INPUT type="submit" value="<?php echo TEXT_PAY_NOW;?>">
     </TD>
    </TR>
    <TR>
     <td><hr></td>
    </TR>
</TABLE>
</FORM>
<?php
  }//end ($HTTP_POST_VARS['payment_mode']==1)
?>
<?php if($HTTP_POST_VARS['payment_mode']==2)
  {
?>
<FORM action="<?php echo bx_make_url(HTTP_SERVER.FILENAME_PROCESSING, "auth_sess", $bx_session);?>" method="post">
 <INPUT type="hidden" name="payment_mode" value="<?php echo $HTTP_POST_VARS['payment_mode'];?>">
 <INPUT type="hidden" name="opid" value="<?php echo $invoice_result['opid'];?>">
 <table bgcolor="<?php echo TABLE_BGCOLOR;?>" width="100%" border="0" cellspacing="0" cellpadding="2">
    <TR bgcolor="#FFFFFF">
      <TD colspan="2" width="100%" align="center" class="headertdjob"><?php echo TEXT_INVOICE_PAYMENT;?></TD>
   </TR>
   <TR><TD colspan="2"><table cellpadding="0" cellspacing="0" border="0" width="100%">
                <tr>
                    <td bgcolor="<?php echo TABLE_HEADING_BGCOLOR;?>" height="1"><?php echo bx_image_width(HTTP_IMAGES.$language."/pix-t.gif",1,1,0,"");?></td>
                </tr>
       </table></TD>
   </TR>
    <TR>
      <TD colspan="2" align="center">
        <font face="<?php echo TEXT_FONT_FACE;?>" size="<?php echo TEXT_FONT_SIZE;?>" color="<?php echo TEXT_FONT_COLOR;?>"><B><?php echo TEXT_FOLLOWING_INFORMATION;?>:</B></FONT>
      </TD>
 </TR>
</table><br>
<table bgcolor="<?php echo LIST_BORDER_COLOR;?>" width="80%" border="0" cellspacing="0" cellpadding="0" align="center"><tr><td>
  <table bgcolor="<?php echo LIST_BORDER_COLOR;?>" width="100%" border="0" cellspacing="1" cellpadding="3">
          <tr bgcolor="#FFFFFF">
      <TD align="right">
        <font face="<?php echo TEXT_FONT_FACE;?>" size="<?php echo TEXT_FONT_SIZE;?>" color="<?php echo TEXT_FONT_COLOR;?>"><B><?php echo TEXT_PAYMENT_DESCRIPTION;?>:</B></font>
	  </TD>
	  <TD><font face="<?php echo REQUIRED_TEXT_FONT_FACE;?>" size="<?php echo REQUIRED_TEXT_FONT_SIZE;?>" color="<?php echo REQUIRED_TEXT_FONT_COLOR;?>"><B> 
	  <?php 
		  	echo TEXT_BANNER_PURCHASE.' : '.$invoice_result['typename'.$slng].'<br>'.TEXT_BANNER_PURCHASE_NR.' : '.(($invoice_result['i_purchased_nr']=='-1')?TEXT_FLAT.', '.$invoice_result['i_period'].TEXT_MONTH:$invoice_result['i_purchased_nr']);
		?></B></FONT>
      </TD>
    </TR>
    <?php
//end if ($invoice_result['pricing_type']==0)
    ?>
	<TR bgcolor="#FFFFFF"><TD colspan="2">&nbsp;</TD></TR> 
    <TR bgcolor="#FFEEEE">
      <TD align="right">
        <font face="<?php echo TEXT_FONT_FACE;?>" size="<?php echo TEXT_FONT_SIZE;?>" color="<?php echo TEXT_FONT_COLOR;?>"><B><?php echo TEXT_PAYMENT_DATE;?>:</B></font>
	  </TD>
	  <TD><font face="<?php echo REQUIRED_TEXT_FONT_FACE;?>" size="<?php echo REQUIRED_TEXT_FONT_SIZE;?>" color="<?php echo REQUIRED_TEXT_FONT_COLOR;?>"><B> <?php echo bx_format_date(date('Y-m-d'), DATE_FORMAT);?></B></FONT>
      </TD>
    </TR>
    <TR bgcolor="#FFEEEE">
      <TD align="right">
        <font face="<?php echo TEXT_FONT_FACE;?>" size="<?php echo TEXT_FONT_SIZE;?>" color="<?php echo TEXT_FONT_COLOR;?>"><B><?php echo TEXT_PAYMENT_LIST_PRICE;?>:</B></font>
	  </TD>
	  <TD><font face="<?php echo REQUIRED_TEXT_FONT_FACE;?>" size="<?php echo REQUIRED_TEXT_FONT_SIZE;?>" color="<?php echo REQUIRED_TEXT_FONT_COLOR;?>"><B> <?php echo bx_format_price($invoice_result['totalprice'],$invoice_result['icurrency']);?></B></FONT>
      </TD>
    </TR>
    <?php if(USE_VAT == "yes") {?>

    <TR bgcolor="#FFEEEE">
      <TD align="right">
        <font face="<?php echo TEXT_FONT_FACE;?>" size="<?php echo TEXT_FONT_SIZE;?>" color="<?php echo TEXT_FONT_COLOR;?>"><B><?php echo TEXT_PAYMENT_VAT;?>:</B></font>
	  </TD>
	  <TD><font face="<?php echo REQUIRED_TEXT_FONT_FACE;?>" size="<?php echo REQUIRED_TEXT_FONT_SIZE;?>" color="<?php echo REQUIRED_TEXT_FONT_COLOR;?>"><B> <?php echo bx_format_price(($invoice_result['totalprice']*$invoice_result['vat']/100),$invoice_result['currency'])." (".$invoice_result['vat']." %)";?></B></FONT>
      </TD>
    </TR>
    <?php }?>
	<TR  bgcolor="#FF0000">
      <TD align="right">
        <font face="<?php echo TEXT_FONT_FACE;?>" size="<?php echo TEXT_FONT_SIZE;?>" color="<?php echo TEXT_FONT_COLOR;?>"><B><?php echo TEXT_PAYMENT_TOTAL_PRICE;?>:</B></font>
	  </TD>
	  <TD><font face="<?php echo REQUIRED_TEXT_FONT_FACE;?>" size="<?php echo REQUIRED_TEXT_FONT_SIZE;?>" color="#000000"><B> <?php echo bx_format_price($invoice_result['totalprice'],$invoice_result['icurrency']);?></B></FONT>
      </TD>
    </TR>
  </TABLE>
      </TD>
    </TR>
  </TABLE><br>
<table border="0" width="100%" cellpadding="0" cellspacing="0">
<TR>
     <TD colspan="2"><HR></TD>
    </TR>
    <TR>
     <TD colspan="2">&nbsp;</TD>
    </TR>
    <TR>
      <TD valign="top" width="30%">
        <font face="<?php echo TEXT_FONT_FACE;?>" size="<?php echo TEXT_FONT_SIZE;?>" color="<?php echo TEXT_FONT_COLOR;?>"><B><?php echo TEXT_DESCRIPTION;?>:</B></FONT>
        <font face="<?php echo DISPLAY_TEXT_FONT_FACE;?>" size="<?php echo DISPLAY_TEXT_FONT_SIZE;?>" color="<?php echo DISPLAY_TEXT_FONT_COLOR;?>"><br><?php echo TEXT_PAYMENT_DESCRIPTION_HINT;?></font>
      </TD>
      <TD align="center">
          <TEXTAREA name="payment_description" rows="3" cols="40"></TEXTAREA>
      </TD>
    </TR>
    <TR>
         <TD colspan="2">&nbsp;</TD>
    </TR>
    <TR>
         <TD colspan="2"><HR></TD>
    </TR>
    <TR>
     <TD align="center" colspan="2"><BR><INPUT type="submit" value="<?php echo TEXT_PAY_NOW_INVOICE;?>"><br>&nbsp;
     </TD>
    </TR>
</TABLE>
</FORM>
<?php
  }//end ($HTTP_POST_VARS['payment_mode']==2)
?>