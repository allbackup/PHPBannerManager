<?php include(DIR_LANGUAGES.$language."/".FILENAME_ADMIN_FORM);?>
<?php

$action="details";
if($HTTP_POST_VARS['type']) {
    $type=$HTTP_POST_VARS['type'];
}
elseif ($HTTP_GET_VARS['type']){
     $type = $HTTP_GET_VARS['type'];
}
else {
    $type = '';
}

if (($type=="invoice_id") || ($type=="invoice_pricing") || ($type=="invoice_employer") || ($type == "invoice_date"))
{
if ($HTTP_GET_VARS['from'])
  {
  $item_from=$HTTP_GET_VARS['from'];
  //$item_to=$HTTP_GET_VARS['to'];
  $item_to=$item_from+NR_DISPLAY;
  }
  else
  {
  $item_from=0;
  $item_to=NR_DISPLAY;
  }
  $no_of_res = bx_db_num_rows($company_query);
  if($HTTP_POST_VARS['show']=="all") {
      $item_from = 0;
      $item_to = $no_of_res;
  }
?>
  <table width="100%" cellpadding="1" cellspacing="1" border="0" class="listtable">
  <tr><td colspan="8" bgcolor="#FFFFFF"><?php echo bx_image_width("images/pix-t.gif",1,5,0,"");?></td></tr>
  <TR bgcolor="#FFFFFF"><td colspan="8" align="right" class="searchhead">Search invoices Showing <?php echo " (".$item_from." to ".$item_to." from ".$no_of_res.")";?></td></tr>
  <tr><td colspan="8" bgcolor="#FFFFFF"><?php echo bx_image_width("images/pix-t.gif",1,5,0,"");?></td></tr>
  <tr class="listhead">
            <td width="5%" align="center">&nbsp;<?php echo TEXT_ID;?>&nbsp;</td>
            <td width="35%" align="center">&nbsp;<?php echo TEXT_COMPANY_NAME;?>&nbsp;</td>
            <td width="10%" align="center">&nbsp;<?php echo TEXT_PRICING_TYPE;?>&nbsp;</td>
            <td width="15%" align="center">&nbsp;<?php echo TEXT_DATE_ADDED;?>&nbsp;</td>
            <td width="5%" align="center">&nbsp;<?php echo TEXT_UPGRADE_VALUE;?>&nbsp;</td>
          <?php if(USE_DISCOUNT == "yes") {?>
            <td width="10%" align="center">&nbsp;<?php echo TEXT_DISCOUNT_VALUE;?>&nbsp;</td>
         <?php }?>
            <td width="10%" align="center">&nbsp;<?php echo TEXT_PAYMENT_VALUE;?>&nbsp;</td>
            <td width="10%" align="center">&nbsp;<?php echo TEXT_ACTION;?>&nbsp;</td>
  </tr>
  <?php
  $rows=0;
  $item=0;
  while ($company_result=bx_db_fetch_array($company_query))
  {
  $rows++;
  $item++;
   if (($item<=$item_to) and ($item>=$item_from+1))
   {
   ?>
    <tr <?php if(floor($rows/2) == ($rows/2)) {echo 'bgcolor="#ffffff"';} else {echo 'bgcolor="#f4f7fd"';}?>>
      <td align="center"><?php echo $company_result['opid'];?></td>
      <td align="center"><a href="<?php echo HTTP_SERVER_ADMIN.FILENAME_ADMIN_DETAILS;?>?action=details&compid=<?php echo $company_result['compid'];?>"><?php echo $company_result['name'];?></a></td>
      <td align="center">
<?php 
		  /*postvars($company_result, "<b>\$HTTP_POST_VARS[ ]</b>");
		  exit;//*/
	$selectZoneNameSQL = "select typename".$slng." from ".$bx_db_table_banner_invoices." as inv1, ".$bx_db_table_banner_types." as zones where inv1.opid='".$company_result['opid']."'";
	$select_zone_query = bx_db_query($selectZoneNameSQL);
	SQL_CHECK(0,"SQL Error at ".__FILE__.":".(__LINE__-1));
	$zone_res = bx_db_fetch_array($select_zone_query);
	echo $zone_res['typename'.$slng];
?>	
	  </td>
      <td align="center"><?php echo ($company_result['i_start_date']!='0000-00-00' ? $company_result['i_start_date'] : '-' );?></td>
      <td align="center"><?php echo bx_format_price($company_result['listprice'],$company_result['currency']);?></td>
      <?php if(USE_DISCOUNT == "yes") {?>
      <td align="center"><?php echo bx_format_price((($company_result['listprice']*$company_result['idiscount'])/100),$company_result['currency']);?></td>
      <?php }?>
      <td align="center"><?php echo bx_format_price($company_result['totalprice'],$company_result['currency']);?></td>
      <td align="center" nowrap>&nbsp;<a href="<?php echo HTTP_SERVER_ADMIN.FILENAME_ADMIN_DETAILS;?>?action=details&opid=<?php echo $company_result['opid'];?>" class="action">Details &#187;</a></td>
    </tr>
   <?php
   } //end if
  } //end while $referer_result
  $item_back_from=$item_from;
  $item_from=$item_to;
  $item_to=$item_from+NR_DISPLAY;
  if (!$rows) {
      echo "<tr><td colspan=\"8\" align=\"center\" class=\"errortd\">Nothing found</td></tr>";
  }
  ?>
  </table>
  <br>
  <table width="100%">
  <tr>
  <?php
  if ($item_from>NR_DISPLAY && $HTTP_POST_VARS['show']!="all")
   {
  ?>
  <td colspan="2" align="left"><a href="<?php echo HTTP_SERVER_ADMIN.FILENAME_ADMIN_SEARCH;?>?action=<?php echo $action;?>&type=<?php echo $type;?>&invoice_id=<?php echo ($HTTP_GET_VARS['invoice_id']?$HTTP_GET_VARS['invoice_id']:$HTTP_POST_VARS['invoice_id']);?>&compid=<?php echo ($HTTP_GET_VARS['compid']?$HTTP_GET_VARS['compid']:$HTTP_POST_VARS['compid']);?>&pricingid=<?php echo ($HTTP_GET_VARS['pricingid']?$HTTP_GET_VARS['pricingid']:$HTTP_POST_VARS['pricingid']);?>&start=<?php echo ($HTTP_GET_VARS['start']?$HTTP_GET_VARS['start']:$HTTP_POST_VARS['start']);?>&end=<?php echo ($HTTP_GET_VARS['end']?$HTTP_GET_VARS['end']:$HTTP_POST_VARS['end']);?>&from=<?php echo $item_back_from-NR_DISPLAY;?>"><font face=<?php echo NEXT_FONT_FACE;?> size=<?php echo NEXT_FONT_SIZE;?> color=<?php echo NEXT_FONT_COLOR;?>><b><?php echo PREVIOUS.' '.NR_DISPLAY;?></b></a></font></td>
  <?php
   }
  if ($item_from<bx_db_num_rows($company_query) && $HTTP_POST_VARS['show']!="all")
  {
  $remains=$no_of_res-$item_from;
  if ($remains > NR_DISPLAY) {
      $remains = NR_DISPLAY;
  }
  ?>
  <td colspan="7" align="right"><a href="<?php echo HTTP_SERVER_ADMIN.FILENAME_ADMIN_SEARCH;?>?action=<?php echo $action;?>&type=<?php echo $type;?>&invoice_id=<?php echo ($HTTP_GET_VARS['invoice_id']?$HTTP_GET_VARS['invoice_id']:$HTTP_POST_VARS['invoice_id']);?>&compid=<?php echo ($HTTP_GET_VARS['compid']?$HTTP_GET_VARS['compid']:$HTTP_POST_VARS['compid']);?>&pricingid=<?php echo ($HTTP_GET_VARS['pricingid']?$HTTP_GET_VARS['pricingid']:$HTTP_POST_VARS['pricingid']);?>&start=<?php echo ($HTTP_GET_VARS['start']?$HTTP_GET_VARS['start']:$HTTP_POST_VARS['start']);?>&end=<?php echo ($HTTP_GET_VARS['end']?$HTTP_GET_VARS['end']:$HTTP_POST_VARS['end']);?>&from=<?php echo $item_from;?>"><font face=<?php echo NEXT_FONT_FACE;?> size=<?php echo NEXT_FONT_SIZE;?> color=<?php echo NEXT_FONT_COLOR;?>><b><?php echo NEXT.' '.$remains;?></b></a></font></td>
  <?php
  }
  ?>
  </tr>
  </table>
<?php
}//end
?>