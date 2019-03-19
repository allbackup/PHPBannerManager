<?php include(DIR_LANGUAGES.$language."/".FILENAME_ADMIN_FORM);
if($HTTP_POST_VARS['action']) {
    $action=$HTTP_POST_VARS['action'];
}
elseif ($HTTP_GET_VARS['action']){
     $action = $HTTP_GET_VARS['action'];
}
else {
    $action = '';
}
?>

<?php
if ($HTTP_GET_VARS['action']=="buyers")
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
  $company_SQL = "select * from ".$bx_db_table_banner_users.",".$bx_table_prefix."_invoices where ".$bx_db_table_banner_users.".user_id=".$bx_table_prefix."_invoices.compid and  ".$bx_table_prefix."_invoices.validated='N' and ".$bx_table_prefix."_invoices.paid='Y'";// group by ".$bx_db_table_banner_users.".user_id";	
  $company_query=bx_db_query($company_SQL);
  SQL_CHECK(0,"SQL Error at ".__FILE__.":".(__LINE__-1));
  $no_of_res = bx_db_num_rows($company_query);
  ?>
  <table width="100%" cellpadding="1" cellspacing="1" border="0" class="listtable">
  <tr><td colspan="10" bgcolor="#FFFFFF"><?php echo bx_image_width("images/pix-t.gif",1,5,0,"");?></td></tr>
  <TR bgcolor="#FFFFFF"><td colspan="10" align="right" class="searchhead">Search buyers Showing <?php echo " (".$item_from." to ".$item_to." from ".$no_of_res.")";?></td></tr>
  <tr><td colspan="4" bgcolor="#FFFFFF"><?php echo bx_image_width("images/pix-t.gif",1,5,0,"");?></td></tr>
  <tr class="listhead">
            <td width="5%" align="center">&nbsp;<?php echo TEXT_ID;?>&nbsp;</td>
	        <td width="13%" align="center">&nbsp;<?php echo TEXT_COMPANY_NAME;?>&nbsp;</td>
            <td width="13%" align="center">&nbsp;<?php echo TEXT_DATE_ADDED;?>&nbsp;</td>
            <td width="13%" align="center">&nbsp;<?php echo TEXT_ACTION;?>&nbsp;</td>
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

	  <td align="center"><a href="<?php echo HTTP_SERVER_ADMIN;?>users.php?&user_id=<?php echo $company_result['compid'];?>&edit=edit_delete&order_by=user_id"><?php echo $company_result['name'];?></a></td>
      <td align="center"><?php echo $company_result['payment_date'];?></td>
      <td align="center" nowrap>&nbsp;<a href="<?php echo HTTP_SERVER_ADMIN.FILENAME_ADMIN_DETAILS;?>?action=<?php echo $action;?>&compid=<?php echo $company_result['compid'];?>&opid=<?php echo $company_result['opid'];?>" class="action">Details &#187;</a></td>
    </tr>
   <?php
   } //end if
  } //end while $referer_result
  $item_back_from=$item_from;
  $item_from=$item_to;
  $item_to=$item_from+NR_DISPLAY;
  if (!$rows) {
      echo "<tr><td colspan=\"10\" align=\"center\" class=\"errortd\">Nothing found</td></tr>";
  }
  ?>
  </table>
  <br>
  <table width="100%"> 
  <tr>
  <?php
  if ($item_from>NR_DISPLAY)
   {
  ?>
  <td colspan="2" align="left"><a href="<?php echo HTTP_SERVER_ADMIN.FILENAME_ADMIN;?>?action=<?php echo $action;?>&from=<?php echo $item_back_from-NR_DISPLAY;?>"><font face=<?php echo NEXT_FONT_FACE;?> size=<?php echo NEXT_FONT_SIZE;?> color=<?php echo NEXT_FONT_COLOR;?>><b><?php echo PREVIOUS.' '.NR_DISPLAY;?></b></a></font></td>
  <?php
   }
  if ($item_from<bx_db_num_rows($company_query))
  {
   ?>
  <td colspan="8" align="right"><a href="<?php echo HTTP_SERVER_ADMIN.FILENAME_ADMIN;?>?action=<?php echo $action;?>&from=<?php echo $item_from;?>"><font face=<?php echo NEXT_FONT_FACE;?> size=<?php echo NEXT_FONT_SIZE;?> color=<?php echo NEXT_FONT_COLOR;?>><b><?php echo NEXT.' '.NR_DISPLAY;?></b></a></font></td>
  <?php
  }
  ?>
  </tr>
  </table>
<?php
}//end if ($HTTP_GET_VARS['action']=='buyers')
?>