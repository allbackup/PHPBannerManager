<?php
include(DIR_LANGUAGES.$language."/".FILENAME_STATISTICS_FORM);?>
<table bgcolor="<?php echo TABLE_BGCOLOR;?>" width="100%" border="0" cellspacing="0" cellpadding="2">
<TR bgcolor="#FFFFFF">
      <TD width="100%" align="center" class="headertdjob"><?php echo TEXT_STATISTICS;?></TD>
   </TR>
<TR><TD><table cellpadding="0" cellspacing="0" border="0" width="100%">
                <tr>
                    <td bgcolor="<?php echo TABLE_HEADING_BGCOLOR;?>" height="1"><?php echo bx_image_width(HTTP_IMAGES.$language."/pix-t.gif",1,1,0,"");?></td>
                </tr>
       </table></TD>
</TR>
   <TR><TD align="right"><form name="invlist"><b><?php echo TEXT_LIST_INVOICES;?>: </b><select name="inv" size="1" onChange="document.location.href=document.invlist.inv[document.invlist.inv.selectedIndex].value;"><option value="<?php echo bx_make_url(HTTP_SERVER.FILENAME_MYINVOICES, "auth_sess", $bx_session);?>">-- <?php echo TEXT_CURRENTLY_PENDING;?> --</option><option value="<?=bx_make_url(HTTP_SERVER.FILENAME_STATISTICS."?action=banner", "auth_sess", $bx_session)?>"<?if ($action=="banner") { echo "selected";}?>>-- <?=TEXT_BANNER_INVOICE_LIST?> --</option></select></form></TD></TR>
</table><br><?php

if ($HTTP_GET_VARS['action']=="banner")
 {
  ?>
  <table bgcolor="<?=LIST_BORDER_COLOR?>" width="100%" border="0" cellspacing="0" cellpadding="0"><tr><td>
  <table bgcolor="<?=LIST_BORDER_COLOR?>" width="100%" border="0" cellspacing="1" cellpadding="2">
   <tr>
        <td colspan="2" width="35%" align="center" bgcolor="<?=LIST_HEADER_COLOR?>"><font face="<?=TEXT_FONT_FACE?>" size="<?=TEXT_FONT_SIZE?>" color="<?=TEXT_FONT_COLOR?>"><b><?=TEXT_BANNER_INVOICES?></b></FONT></td>
        <td colspan="3" bgcolor="#FFFFFF">&nbsp;</td>
   </tr>
   <tr bgcolor="#DDFFFF">
            <td width="20%" align="center"><font face="Verdana" size="<?=DISPLAY_TEXT_FONT_SIZE?>" color="<?=DISPLAY_TEXT_FONT_COLOR?>"> <b>&nbsp;<?=TEXT_BANNER_ZONE?>&nbsp;</b></font></td>
            <td width="25%" align="center"><font face="Verdana" size="<?=DISPLAY_TEXT_FONT_SIZE?>" color="<?=DISPLAY_TEXT_FONT_COLOR?>"> <b>&nbsp;<?=TEXT_BANNER_PURCHASE?>&nbsp;</b></font></td>
            <td width="20%" align="center"><font face="Verdana" size="<?=DISPLAY_TEXT_FONT_SIZE?>" color="<?=DISPLAY_TEXT_FONT_COLOR?>"> <b>&nbsp;<?=TEXT_BANNER_PERIOD?>(<?=TEXT_MONTH?>)&nbsp;</b></font></td>
            <td width="20%" align="center"><font face="Verdana" size="<?=DISPLAY_TEXT_FONT_SIZE?>" color="<?=DISPLAY_TEXT_FONT_COLOR?>"> <b>&nbsp;<?=TEXT_BANNER_VALUE?>&nbsp;</b></font></td>
            <td width="15%" align="center"><font face="Verdana" size="<?=DISPLAY_TEXT_FONT_SIZE?>" color="<?=DISPLAY_TEXT_FONT_COLOR?>"> <b>&nbsp;<?=TEXT_VIEW?>&nbsp;</b></font></td>
  </tr>
  <?
  $banner_query_SQL = "select * from ".$bx_table_prefix."_invoices,$bx_db_table_banner_types where compid='".$employerid."' and paid='Y' and validated='Y' and i_zone=type_id ORDER by type_id";
  $banner_query=bx_db_query($banner_query_SQL);
  SQL_CHECK(0,"SQL Error at ".__FILE__.":".(__LINE__-1));
  while ($banner_result=bx_db_fetch_array($banner_query))
   {
   $rows++;
   ?>
    <tr <?if (floor($rows/2) == ($rows/2)) {
      echo 'bgcolor="'.DISPLAY_LINE_BGCOLOR_FIRST.'"';
    } else {
      echo 'bgcolor="'.DISPLAY_LINE_BGCOLOR_SECOND.'"';
    }?>>
      <td align="center"><font face="<?=DISPLAY_TEXT_FONT_FACE?>" size="<?=DISPLAY_TEXT_FONT_SIZE?>" color="<?=DISPLAY_TEXT_FONT_COLOR?>"> <?=$banner_result['typename'.$slng]?></font></td>
      <td align="center"><font face="<?=DISPLAY_TEXT_FONT_FACE?>" size="<?=DISPLAY_TEXT_FONT_SIZE?>" color="<?=DISPLAY_TEXT_FONT_COLOR?>"> <?=($banner_result['i_purchased_nr']=='-1')?TEXT_FLAT:$banner_result['i_purchased_nr']?></font></td>
      <td align="center"><font face="<?=DISPLAY_TEXT_FONT_FACE?>" size="<?=DISPLAY_TEXT_FONT_SIZE?>" color="<?=DISPLAY_TEXT_FONT_COLOR?>"> <?=$banner_result['i_period']?></font></td>
      <td align="center"><font face="<?=DISPLAY_TEXT_FONT_FACE?>" size="<?=DISPLAY_TEXT_FONT_SIZE?>" color="<?=DISPLAY_TEXT_FONT_COLOR?>"> <?=bx_format_price($banner_result['totalprice'],$banner_result['currency'])?></font></td>
      <td align="center"><font face="<?=DISPLAY_TEXT_FONT_FACE?>" size="<?=DISPLAY_TEXT_FONT_SIZE?>" color="<?=DISPLAY_TEXT_FONT_COLOR?>"><a href="<?=bx_make_url(HTTP_SERVER.FILENAME_INVOICES."?action=view&opid=".$banner_result['opid'], "auth_sess", $bx_session)?>"><?=bx_image(HTTP_IMAGES.$language."/view.gif",0,TEXT_VIEW)?></a></font></td>
    </tr>
   <?
   }
   ?>
   </table></td></tr></table>
   <?
 }

?>
<BR>
<table width="100%" cellpadding="0" cellspacing="0">
    <tr>
        <td align="right"><a href="javascript:self.history.back();" onmouseover="window.status='<?php echo TEXT_BACK;?>'; return true;" onmouseout="window.status = ''; return true;">&#171;&nbsp;<?php echo TEXT_BACK;?></a>
        </td>
    </tr>
</table>