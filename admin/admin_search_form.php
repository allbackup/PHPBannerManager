<?php include(DIR_LANGUAGES.$language."/".FILENAME_ADMIN_FORM);?>
<script language="Javascript" src="calendar.js"></script>
<?php
if ($HTTP_GET_VARS['invoices']=="yes")
{
?>
<form action="<?php echo HTTP_SERVER_ADMIN.FILENAME_ADMIN_SEARCH;?>" method="post">
 <input type="hidden" name="type" value="invoice_id">
<table width="100%" cellspacing="0" cellpadding="2" border="0" class="tedit">
 <tr>
  <td>
  <table width="100%" cellspacing="0" cellpadding="3" border="0" class="edit">
     <tr>
      <TD align="center"><B><?php echo TEXT_SEARCH_OPERATIONID;?>:</B>
      </TD>
     </tr>
     <tr>
      <TD align="center">
        <INPUT TYPE="text" name="invoice_id" size="10" maxlength="20">
      </TD>
      </tr>
     <TR>
       <td align="center"><input type="submit" name="search" value="<?php echo TEXT_SEARCH;?>"></td>
    </tr>
    <TR>
     <td>&nbsp;</td>
    </tr>
 </table>
</td></tr></table>
</form>

<form action="<?php echo HTTP_SERVER_ADMIN.FILENAME_ADMIN_SEARCH;?>" method="post">
 <input type="hidden" name="type" value="invoice_date">
 <input type="hidden" name="end" value="<?php echo date('Y-m-d');?>">
 <table width="100%" cellspacing="0" cellpadding="2" border="0" class="tedit">
 <tr>
  <td>
  <table width="100%" cellspacing="0" cellpadding="3" border="0" class="edit">
          <tr><td width="50%" style="padding-left: 20px;"><table width="100%" cellspacing="0" cellpadding="3" border="0" class="edit">
            <tr>
              <TD width="100%" align="center" colspan="2"><B>All Invoices Generated:</B>
              </TD>
             </tr>
             <tr>
              <TD width="100%" align="left"><input type="radio" class="radio" name="start" value="<?php echo date('Y-m-d');?>" checked>&nbsp;&nbsp;<B>Today</B></TD>
             </tr>
             <tr>
              <TD width="100%" align="left"><input type="radio" class="radio" name="start" value="<?php echo date('Y-m-d',mktime(0,0,0,date('m'),date('d')-7,date('Y')));?>">&nbsp;&nbsp;<B>In the last week</B></TD>
             </tr>
             <tr>
              <TD width="100%" align="left"><input type="radio" class="radio" name="start" value="<?php echo date('Y-m-d',mktime(0,0,0,date('m'),1,date('Y')));?>">&nbsp;&nbsp;<B>In this month</B></TD>
             </tr>
             <tr>
              <TD width="100%" align="left"><input type="radio" class="radio" name="start" value="<?php echo date('Y-m-d',mktime(0,0,0,date('m'),date('d')-30,date('Y')));?>">&nbsp;&nbsp;<B>In the last 30 days</B></TD>
             </tr>
             <tr>
                  <td align="center"><input type="submit" name="search" value="<?php echo TEXT_SEARCH;?>"></td>
             </tr>
         </table>
         </td>
         </form>
         <form action="<?php echo HTTP_SERVER_ADMIN.FILENAME_ADMIN_SEARCH;?>" method="post" name="date_search">
         <input type="hidden" name="type" value="invoice_date">
         <td width="50%"><table width="100%" cellspacing="0" cellpadding="3" border="0" class="edit">
            <tr>
              <TD width="100%" align="center" colspan="2"><B>Invoices Generated between:</B>
              </TD>
             </tr>
             <tr>
                  <td align="center">&nbsp;</td>
             </tr>
             <tr>
              <TD width="100%" align="left"><B>Start Date:</B>&nbsp;&nbsp;&nbsp;<INPUT TYPE="text" name="start" size="10" value="<?php echo date('Y-m-d',mktime(0,0,0,date('m'),date('d')-10,date('Y')));?>">&nbsp;<a href="javascript:show_calendar('date_search.start');" onmouseOver="window.status='Calendar'; return true;" onmouseOut="window.status=''; return true;" ><img src="images/calendar.gif" align="absmiddle" border="0" alt=""></a>
              </TD>
             </tr>
             <tr>
                  <TD  width="100%" align="left" valign="middle"><b>&nbsp;&nbsp;End Date:</b>&nbsp;&nbsp;&nbsp;<INPUT TYPE="text" name="end" size="10" value="<?php echo date('Y-m-d');?>">&nbsp;<a href="javascript:show_calendar('date_search.end');" onmouseOver="window.status='Calendar'; return true;" onmouseOut="window.status=''; return true;" ><img src="images/calendar.gif" align="absmiddle" border="0" alt=""></a>
                  </TD>
             </tr>
             <tr>
                  <td align="center"><input type="submit" name="search" value="<?php echo TEXT_SEARCH;?>"></td>
             </tr>
         </table>
        </td>
    </tr></table>
 </td></tr>
</table>
</form>

<form action="<?php echo HTTP_SERVER_ADMIN.FILENAME_ADMIN_SEARCH;?>" method="post">
 <input type="hidden" name="type" value="invoice_employer">
 <table width="100%" cellspacing="0" cellpadding="2" border="0" class="tedit">
 <tr>
  <td>
  <table width="100%" cellspacing="0" cellpadding="2" border="0" class="edit">
   <tr>
     <TD align="center">
        <B><?php echo TEXT_SEARCH_EMPLOYERS;?>:</B>
      </TD>
     </tr>
     <tr>
     <TD align="center">
           <SELECT name="compid" size="1">
           <option value="000"><?php echo TEXT_ALL_EMPLOYERS;?></option>
        <?php
        /*$employer_query=bx_db_query("SELECT * FROM ".$bx_table_prefix."_companies");
        SQL_CHECK(0,"SQL Error at ".__FILE__.":".(__LINE__-1));
        while ($employer_result=bx_db_fetch_array($employer_query))
        {
        echo '<option value="'.$employer_result['compid'].'">'.$employer_result['company'].'</option>';
        }*/
        ?>
        </SELECT>
      </TD>
      </TR>
      <tr>
          <TD align="center"><INPUT TYPE="checkbox" name="show" value="all" class="radio">&nbsp;&nbsp;<font class="smalltext"><?php echo TEXT_SHOW_ALL;?></font></TD>
     </tr>
     <TR>
     <td align="center"><input type="submit" name="search" value="<?php echo TEXT_SEARCH;?>"></td>
   </tr>
    <tr>
    <td>&nbsp;</td>
   </tr>
 </table>
</td></tr></table>
</form>

<form action="<?php echo HTTP_SERVER_ADMIN.FILENAME_ADMIN_SEARCH;?>" method="post">
 <input type="hidden" name="type" value="invoice_pricing">
 <table width="100%" cellspacing="0" cellpadding="2" border="0" class="tedit">
 <tr>
  <td>
  <table width="100%" cellspacing="0" cellpadding="2" border="0" class="edit">
   <tr>
     <TD align="center">
        <B><?php echo TEXT_SEARCH_PRICING_TYPE;?>:</B>
      </TD>
     </tr>
     <tr>
     <TD align="center">
           <SELECT name="pricingid" size="1">
           <option value="all"><?php echo TEXT_ALL_PRICING_TYPE;?></option>
        <?php
       /* $pricing_query=bx_db_query("SELECT * FROM ".$bx_table_prefix."_pricing_".$bx_table_lng."");
        SQL_CHECK(0,"SQL Error at ".__FILE__.":".(__LINE__-1));
        while ($pricing_result=bx_db_fetch_array($pricing_query))
        {
        echo '<option value="'.$pricing_result['pricing_id'].'">'.$pricing_result['pricing_title'].'</option>';
        }*/
        ?>
        </SELECT>
      </TD>
      </TR>
      <tr>
          <TD align="center"><INPUT TYPE="checkbox" name="show" value="all" class="radio">&nbsp;&nbsp;<font class="smalltext"><?php echo TEXT_SHOW_ALL;?></font></TD>
     </tr>
     <TR>
         <td align="center"><input type="submit" name="search" value="<?php echo TEXT_SEARCH;?>"></td>
     </tr>
    <tr>
    <td>&nbsp;</td>
   </tr>
 </table>
</td></tr></table>
</form>
<?php
}//end if ($HTTP_GET_VARS['jobs']=="invoices")
?>