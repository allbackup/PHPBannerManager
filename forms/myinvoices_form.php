<?php
include(DIR_LANGUAGES.$language."/".FILENAME_MYINVOICES_FORM);
?>
<table bgcolor="<?php echo TABLE_BGCOLOR;?>" width="100%" border="0" cellspacing="0" cellpadding="2">
   <TR bgcolor="#FFFFFF">
      <TD colspan="2" width="100%" align="center" class="headertdjob"><?php echo TEXT_MY_INVOICES;?></TD>
   </TR>
   <TR><TD colspan="2"><table cellpadding="0" cellspacing="0" border="0" width="100%">
                <tr>
                    <td bgcolor="<?php echo TABLE_HEADING_BGCOLOR;?>" height="1"><?php echo bx_image_width(HTTP_IMAGES.$language."/pix-t.gif",1,1,0,"");?></td>
                </tr>
       </table></TD>
   </TR>
   <TR>
   <TD width="50%">&nbsp;</ul>
   </TD>
   <TD align="right" width="50%"><form name="invlist"><b><?php echo TEXT_LIST_INVOICES;?>: </b><select name="inv" size="1" onChange="document.location.href=document.invlist.inv[document.invlist.inv.selectedIndex].value;"><option value="<?php echo bx_make_url(HTTP_SERVER.FILENAME_MYINVOICES, "auth_sess", $bx_session);?>" selected>-- <?php echo TEXT_PENDING_INVOICES;?> --</option><option value="<?=bx_make_url(HTTP_SERVER.FILENAME_STATISTICS."?action=banner", "auth_sess", $bx_session)?>"<?if ($action=="banner") { echo "selected";}?>>-- <?=TEXT_BANNER_INVOICE_LIST?> --</option></select></form></TD></TR>
</table><br>
<table bgcolor="<?php echo LIST_BORDER_COLOR;?>" width="100%" border="0" cellspacing="0" cellpadding="0"><tr><td>
  <table bgcolor="<?php echo LIST_BORDER_COLOR;?>" width="100%" border="0" cellspacing="1" cellpadding="2">
    <tr>
     <td colspan="2" width="35%" align="center" bgcolor="<?php echo LIST_HEADER_COLOR;?>"><font face="<?php echo TEXT_FONT_FACE;?>" size="<?php echo TEXT_FONT_SIZE;?>" color="<?php echo TEXT_FONT_COLOR;?>"><b><?php echo TEXT_WAITING_PAYMENT;?></b></FONT></td>
     <td colspan="<?php echo ((USE_VAT=="yes")?"3":"2");?>" width="65%" bgcolor="#FFFFFF">&nbsp;</td>
    </tr>
  <?php
  $invoices_SQL = "select * from ".$bx_table_prefix."_invoices where ".$bx_table_prefix."_invoices.compid='".$HTTP_SESSION_VARS['employerid']."' and ".$bx_table_prefix."_invoices.paid='N' and ".$bx_table_prefix."_invoices.updated='N' and ".$bx_table_prefix."_invoices.validated='N'";
  $invoices_query=bx_db_query($invoices_SQL);
  SQL_CHECK(0,"SQL Error at ".__FILE__.":".(__LINE__-1));
  $rows=0;
  if (bx_db_num_rows($invoices_query)>0) {
  ?>
        <tr bgcolor="<?php echo LIST_HEADER_COLOR;?>">
            <td width="25%" align="center"><font face="Verdana" size="<?php echo DISPLAY_TEXT_FONT_SIZE;?>" color="<?php echo DISPLAY_TEXT_FONT_COLOR;?>"><b>&nbsp;<?php echo TEXT_PRICING_TYPE;?>&nbsp;</b></font></td>
            <td width="20%" align="center"><font face="Verdana" size="<?php echo DISPLAY_TEXT_FONT_SIZE;?>" color="<?php echo DISPLAY_TEXT_FONT_COLOR;?>"><b>&nbsp;<?php echo TEXT_UPGRADE_VALUE;?>&nbsp;</b></font></td>

    <?php if(USE_VAT == "yes") {?>
            <td width="20%" align="center"><font face="Verdana" size="<?php echo DISPLAY_TEXT_FONT_SIZE;?>" color="<?php echo DISPLAY_TEXT_FONT_COLOR;?>"><b>&nbsp;<?php echo TEXT_VAT_VALUE;?>&nbsp;</b></font></td>
    <?php }?>
            <td width="20%" align="center"><font face="Verdana" size="<?php echo DISPLAY_TEXT_FONT_SIZE;?>" color="<?php echo DISPLAY_TEXT_FONT_COLOR;?>"><b>&nbsp;<?php echo TEXT_PAYMENT_VALUE;?>&nbsp;</b></font></td>
            <td width="20%" align="center"><font face="Verdana" size="<?php echo DISPLAY_TEXT_FONT_SIZE;?>" color="<?php echo DISPLAY_TEXT_FONT_COLOR;?>"><b>&nbsp;<?php echo TEXT_ACTION;?>&nbsp;</b></font></td>
   </tr>
    <?php
  }
  while ($invoices_result=bx_db_fetch_array($invoices_query))
   {
	$rows++;
   	$select_banner_invoice_SQL = "select * from $bx_db_table_banner_invoices,$bx_db_table_banner_types where i_zone=type_id and i_zone='".$invoices_result['i_zone']."'";
   	$select_banner_invoice_query = bx_db_query($select_banner_invoice_SQL);
   	SQL_CHECK(0,"SQL Error at ".__FILE__.":".(__LINE__-1));
	$select_banner_invoice = bx_db_fetch_array($select_banner_invoice_query);
   ?>
  <tr <?php if(floor($rows/2) == ($rows/2)) {
      echo 'bgcolor="'.DISPLAY_LINE_BGCOLOR_FIRST.'"';
    } else {
      echo 'bgcolor="'.DISPLAY_LINE_BGCOLOR_SECOND.'"';
    }?>>
      <td align="center"><font face="<?=DISPLAY_TEXT_FONT_FACE?>" size="<?=DISPLAY_TEXT_FONT_SIZE?>" color="<?=DISPLAY_TEXT_FONT_COLOR?>"><?=(TEXT_BANNER_PURCHASE.' : '.$select_banner_invoice['typename'.$slng].'<br>'.TEXT_BANNER_PURCHASE_NR.' : '.(($select_banner_invoice['i_purchased_nr']=='-1')?TEXT_FLAT.', '.$select_banner_invoice['i_period'].TEXT_MONTH:$select_banner_invoice['i_purchased_nr']))?></font></td>

      <td align="center"><font face="<?=DISPLAY_TEXT_FONT_FACE?>" size="<?=DISPLAY_TEXT_FONT_SIZE?>" color="<?=DISPLAY_TEXT_FONT_COLOR?>"><?=bx_format_price($invoices_result['totalprice'],$invoices_result['currency'])?></font></td>

    <? if (USE_VAT == "yes") {
    ?>
    <td align="center"><font face="<?php echo DISPLAY_TEXT_FONT_FACE;?>" size="<?php echo DISPLAY_TEXT_FONT_SIZE;?>" color="<?php echo DISPLAY_TEXT_FONT_COLOR;?>"><?php echo bx_format_price((($invoices_result['totalprice'])*$invoices_result['vat']/100),$invoices_result['currency'])." (".$invoices_result['vat']."%)";?></font></td>
    <?php
     }//end if (USE_VAT == "yes"
    ?>
      <td align="center"><font face="<?php echo DISPLAY_TEXT_FONT_FACE;?>" size="<?php echo DISPLAY_TEXT_FONT_SIZE;?>" color="<?php echo DISPLAY_TEXT_FONT_COLOR;?>"><?php echo bx_format_price($invoices_result['totalprice'],$invoices_result['currency']);?></font></td>
      <td align="center"><font face="<?php echo DISPLAY_TEXT_FONT_FACE;?>" size="<?php echo DISPLAY_TEXT_FONT_SIZE;?>" color="<?php echo DISPLAY_TEXT_FONT_COLOR;?>"><a href="<?php echo bx_make_url(HTTP_SERVER.FILENAME_INVOICES."?action=pay&opid=".$invoices_result['opid'], "auth_sess", $bx_session);?>"><?php echo bx_image(HTTP_IMAGES.$language."/pay.gif",0,TEXT_PAY);?></a>&nbsp;<a href="<?php echo bx_make_url(HTTP_SERVER.FILENAME_INVOICES."?action=cancel&opid=".$invoices_result['opid'], "auth_sess", $bx_session);?>"><?php echo bx_image(HTTP_IMAGES.$language."/cancel.gif",0,TEXT_CANCEL);?></a></font></td>
    </tr>
   <?php
   }
   if ($rows==0) {
   ?>
        <tr bgcolor="#FFFFFF">
            <td colspan="6" align="center"><font face="<?php echo TEXT_FONT_FACE;?>" size="1" color="red"><?php echo TEXT_NOTHING_FOUND;?></font></td>
        </tr>
   <?php
   }
   ?>
   </td></tr></table></td></tr></table><br>
   <table bgcolor="<?php echo LIST_BORDER_COLOR;?>" width="100%" border="0" cellspacing="0" cellpadding="0"><tr><td>
   <table bgcolor="<?php echo LIST_BORDER_COLOR;?>" width="100%" border="0" cellspacing="1" cellpadding="2">
    <tr>
     <td colspan="2" width="35%" align="center" bgcolor="<?php echo LIST_HEADER_COLOR;?>"><font face="<?php echo TEXT_FONT_FACE;?>" size="<?php echo TEXT_FONT_SIZE;?>" color="<?php echo TEXT_FONT_COLOR;?>"><b><?php echo TEXT_WAITING_UPDATE;?></b></FONT></td>
     <td colspan="<?php echo ((USE_VAT=="yes")?"3":"2");?>" bgcolor="#FFFFFF" width="65%">&nbsp;</td>
  </tr>
  <?php
  $invoices_SQL = "select * from ".$bx_table_prefix."_invoices where ".$bx_table_prefix."_invoices.compid='".$HTTP_SESSION_VARS['employerid']."' and ".$bx_table_prefix."_invoices.paid='Y' and ".$bx_table_prefix."_invoices.updated='N' and ".$bx_table_prefix."_invoices.validated='Y'";
  $invoices_query=bx_db_query($invoices_SQL);
  SQL_CHECK(0,"SQL Error at ".__FILE__.":".(__LINE__-1));
  $rows=0;
  if (bx_db_num_rows($invoices_query)>0) {
  ?>
        <tr bgcolor="<?php echo LIST_HEADER_COLOR;?>">
            <td width="25%" align="center"><font face="Verdana" size="<?php echo DISPLAY_TEXT_FONT_SIZE;?>" color="<?php echo DISPLAY_TEXT_FONT_COLOR;?>"><b>&nbsp;<?php echo TEXT_PRICING_TYPE;?>&nbsp;</b></font></td>
            <td width="20%" align="center"><font face="Verdana" size="<?php echo DISPLAY_TEXT_FONT_SIZE;?>" color="<?php echo DISPLAY_TEXT_FONT_COLOR;?>"><b>&nbsp;<?php echo TEXT_UPGRADE_VALUE;?>&nbsp;</b></font></td>
         <?php if(USE_VAT == "yes") {?>
            <td width="20%" align="center"><font face="Verdana" size="<?php echo DISPLAY_TEXT_FONT_SIZE;?>" color="<?php echo DISPLAY_TEXT_FONT_COLOR;?>"><b>&nbsp;<?php echo TEXT_VAT_VALUE;?>&nbsp;</b></font></td>
         <?php }?>
            <td width="20%" align="center"><font face="Verdana" size="<?php echo DISPLAY_TEXT_FONT_SIZE;?>" color="<?php echo DISPLAY_TEXT_FONT_COLOR;?>"><b>&nbsp;<?php echo TEXT_PAYMENT_VALUE;?>&nbsp;</b></font></td>
            <td width="20%" align="center"><font face="Verdana" size="<?php echo DISPLAY_TEXT_FONT_SIZE;?>" color="<?php echo DISPLAY_TEXT_FONT_COLOR;?>"><b>&nbsp;<?php echo TEXT_ACTION;?>&nbsp;</b></font></td>
        </tr>
  <?php
  }
  while ($invoices_result=bx_db_fetch_array($invoices_query))
   {

   	$select_banner_invoice_SQL = "select * from $bx_db_table_banner_invoices,$bx_db_table_banner_types where opid='".$invoices_result['opid']."' and i_zone=type_id";
   	$select_banner_invoice_query = bx_db_query($select_banner_invoice_SQL);
   	SQL_CHECK(0,"SQL Error at ".__FILE__.":".(__LINE__-1));
	$select_banner_invoice = bx_db_fetch_array($select_banner_invoice_query);
   $rows++;
   ?>
<tr <?php if(floor($rows/2) == ($rows/2)) {
      echo 'bgcolor="'.DISPLAY_LINE_BGCOLOR_FIRST.'"';
    } else {
      echo 'bgcolor="'.DISPLAY_LINE_BGCOLOR_SECOND.'"';
    }?>>
      <td align="center"><font face="<?=DISPLAY_TEXT_FONT_FACE?>" size="<?=DISPLAY_TEXT_FONT_SIZE?>" color="<?=DISPLAY_TEXT_FONT_COLOR?>"><?=(TEXT_BANNER_PURCHASE.' : '.$select_banner_invoice['typename'.$slng].'<br>'.TEXT_BANNER_PURCHASE_NR.' : '.(($select_banner_invoice['i_purchased_nr']=='-1')?TEXT_FLAT.', '.$select_banner_invoice['i_period'].TEXT_MONTH:$select_banner_invoice['i_purchased_nr']))?></font></td>

      <td align="center"><font face="<?=DISPLAY_TEXT_FONT_FACE?>" size="<?=DISPLAY_TEXT_FONT_SIZE?>" color="<?=DISPLAY_TEXT_FONT_COLOR?>"><?=bx_format_price($invoices_result['totalprice'],$invoices_result['currency'])?></font></td>

    <? if (USE_VAT == "yes") {
    ?>
    <td align="center"><font face="<?php echo DISPLAY_TEXT_FONT_FACE;?>" size="<?php echo DISPLAY_TEXT_FONT_SIZE;?>" color="<?php echo DISPLAY_TEXT_FONT_COLOR;?>"><?php echo bx_format_price(((($invoices_result['totalprice']))*$invoices_result['vat']/100),$invoices_result['currency'])." (".$invoices_result['vat']."%)";?></font></td>
    <?php
     }//end if (USE_VAT == "yes"
    ?>
      <td align="center"><font face="<?php echo DISPLAY_TEXT_FONT_FACE;?>" size="<?php echo DISPLAY_TEXT_FONT_SIZE;?>" color="<?php echo DISPLAY_TEXT_FONT_COLOR;?>"><?php echo bx_format_price($invoices_result['totalprice'],$invoices_result['currency']);?></font></td>
      <td align="center"><font face="<?php echo DISPLAY_TEXT_FONT_FACE;?>" size="<?php echo DISPLAY_TEXT_FONT_SIZE;?>" color="<?php echo DISPLAY_TEXT_FONT_COLOR;?>"><a href="<?php echo bx_make_url(HTTP_SERVER.FILENAME_INVOICES."?action=update&opid=".$invoices_result['opid'], "auth_sess", $bx_session);?>"><?php echo bx_image(HTTP_IMAGES.$language."/update.gif",0,TEXT_UPDATE);?></a></font></td>
    </tr>
   <?php
   }
   if ($rows==0) {
   ?>
        <tr bgcolor="#FFFFFF">
            <td colspan="6" align="center"><font face="<?php echo TEXT_FONT_FACE;?>" size="1" color="#FF0000"><?php echo TEXT_NOTHING_FOUND;?></font></td>
        </tr>
   <?php
   }
   ?>
  </td></tr></table></td></tr></table><br>
   <table bgcolor="<?php echo LIST_BORDER_COLOR;?>" width="100%" border="0" cellspacing="0" cellpadding="0"><tr><td>
  <table bgcolor="<?php echo LIST_BORDER_COLOR;?>" width="100%" border="0" cellspacing="1" cellpadding="2">
  <tr>
     <td colspan="2" width="35%" align="center" bgcolor="<?php echo LIST_HEADER_COLOR;?>"><font face="<?php echo TEXT_FONT_FACE;?>" size="<?php echo TEXT_FONT_SIZE;?>" color="<?php echo TEXT_FONT_COLOR;?>"><b><?php echo TEXT_WAITING_VALIDATION;?></b></FONT></td>
     <td colspan="<?php echo ((USE_VAT=="yes")?"3":"2");?>" bgcolor="#FFFFFF" width="65%">&nbsp;</td>
  </tr>
  <?php
  $invoices_SQL = "select * from ".$bx_table_prefix."_invoices,$bx_db_table_banner_types where compid='".$HTTP_SESSION_VARS['employerid']."' and ".$bx_table_prefix."_invoices.paid='Y' and ".$bx_table_prefix."_invoices.updated='N' and ".$bx_table_prefix."_invoices.validated='N' and i_zone=type_id";
  $invoices_query=bx_db_query($invoices_SQL);
  SQL_CHECK(0,"SQL Error at ".__FILE__.":".(__LINE__-1));
  $rows=0;
  if (bx_db_num_rows($invoices_query)>0) {
  ?>
    <tr bgcolor="<?php echo LIST_HEADER_COLOR;?>">
            <td width="25%" align="center"><font face="Verdana" size="<?php echo DISPLAY_TEXT_FONT_SIZE;?>" color="<?php echo DISPLAY_TEXT_FONT_COLOR;?>"><b>&nbsp;<?php echo TEXT_PRICING_TYPE;?>&nbsp;</b></font></td>
            <td width="20%" align="center"><font face="Verdana" size="<?php echo DISPLAY_TEXT_FONT_SIZE;?>" color="<?php echo DISPLAY_TEXT_FONT_COLOR;?>"><b>&nbsp;<?php echo TEXT_UPGRADE_VALUE;?>&nbsp;</b></font></td>
         <?php if(USE_VAT == "yes") {?>
            <td width="20%" align="center"><font face="Verdana" size="<?php echo DISPLAY_TEXT_FONT_SIZE;?>" color="<?php echo DISPLAY_TEXT_FONT_COLOR;?>"><b>&nbsp;<?php echo TEXT_VAT_VALUE;?>&nbsp;</b></font></td>
         <?php }?>
            <td width="20%" align="center"><font face="Verdana" size="<?php echo DISPLAY_TEXT_FONT_SIZE;?>" color="<?php echo DISPLAY_TEXT_FONT_COLOR;?>"><b>&nbsp;<?php echo TEXT_PAYMENT_VALUE;?>&nbsp;</b></font></td>
            <td width="20%" align="center"><font face="Verdana" size="<?php echo DISPLAY_TEXT_FONT_SIZE;?>" color="<?php echo DISPLAY_TEXT_FONT_COLOR;?>"><b>&nbsp;<?php echo TEXT_ACTION;?>&nbsp;</b></font></td>
   </tr>
  <?php
  }
  while ($invoices_result=bx_db_fetch_array($invoices_query))
   {
   $rows++;
   ?>
   <tr <?php if(floor($rows/2) == ($rows/2)) {
      echo 'bgcolor="'.DISPLAY_LINE_BGCOLOR_FIRST.'"';
    } else {
      echo 'bgcolor="'.DISPLAY_LINE_BGCOLOR_SECOND.'"';
    }?>>
      <td align="center"><font face="<?=DISPLAY_TEXT_FONT_FACE?>" size="<?=DISPLAY_TEXT_FONT_SIZE?>" color="<?=DISPLAY_TEXT_FONT_COLOR?>"><?=(TEXT_BANNER_PURCHASE.' : '.$invoices_result['typename'.$slng].'<br>'.TEXT_BANNER_PURCHASE_NR.' : '.(($invoices_result['i_purchased_nr']=='-1')?TEXT_FLAT.', '.$invoices_result['i_period'].TEXT_MONTH:$invoices_result['i_purchased_nr']))?></font></td>

      <td align="center"><font face="<?=DISPLAY_TEXT_FONT_FACE?>" size="<?=DISPLAY_TEXT_FONT_SIZE?>" color="<?=DISPLAY_TEXT_FONT_COLOR?>"><?=bx_format_price($invoices_result['totalprice'],$invoices_result['currency'])?></font></td>

    <? if (USE_VAT == "yes") {
    ?>
    <td align="center"><font face="<?php echo DISPLAY_TEXT_FONT_FACE;?>" size="<?php echo DISPLAY_TEXT_FONT_SIZE;?>" color="<?php echo DISPLAY_TEXT_FONT_COLOR;?>"><?php echo bx_format_price(((($invoices_result['totalprice']))*$invoices_result['vat']/100),$invoices_result['currency'])." (".$invoices_result['vat']."%)";?></font></td>
    <?php
     }//end if (USE_VAT == "yes"
    ?>
      <td align="center"><font face="<?php echo DISPLAY_TEXT_FONT_FACE;?>" size="<?php echo DISPLAY_TEXT_FONT_SIZE;?>" color="<?php echo DISPLAY_TEXT_FONT_COLOR;?>"><?php echo bx_format_price($invoices_result['totalprice'],$invoices_result['currency']);?></font></td>
      <td align="center"><font face="<?php echo DISPLAY_TEXT_FONT_FACE;?>" size="<?php echo DISPLAY_TEXT_FONT_SIZE;?>" color="<?php echo DISPLAY_TEXT_FONT_COLOR;?>"><a href="<?php echo bx_make_url(HTTP_SERVER.FILENAME_INVOICES."?action=view&opid=".$invoices_result['opid'], "auth_sess", $bx_session);?>"><?php echo bx_image(HTTP_IMAGES.$language."/view.gif",0,TEXT_VIEW);?></a></font></td>
    </tr>
    <?php
   }
 if ($rows==0) {
   ?>
        <tr bgcolor="#FFFFFF">
            <td colspan="6" align="center"><font face="<?php echo TEXT_FONT_FACE;?>" size="1" color="#FF0000"><?php echo TEXT_NOTHING_FOUND;?></font></td>
        </tr>
   <?php
   }
   ?>
</table></td></tr></table>