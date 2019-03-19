<?php include(DIR_LANGUAGES.$language."/".FILENAME_ADMIN_FORM);?>
<script language="Javascript" src="calendar.js"></script>
<form action="<?php echo HTTP_SERVER_ADMIN.FILENAME_ADMIN_VALIDATION;?>" method="post" enctype="multipart/form-data" name="company">
   <table width="100%" cellspacing="0" cellpadding="2" border="0" class="tedit">
 <tr>
     <td align="center"><b>Company details</b></td>
 </tr>
 <tr>
   <td>
     <TABLE border="0" cellpadding="4" cellspacing="0" width="100%" class="edit">
    <TR>
      <TD>
        <B><?php echo TEXT_COMPANY_FEATURED;?></B>
      </TD>
     <TD>
        <input type="radio" name="featured" class="radio" value="0" <?php if((!$company_result['featured']) || ($company_result['featured']=='0')) {echo "checked";}?>>&nbsp;&nbsp;<?php echo ucfirst(TEXT_NO);?>
        <input type="radio" name="featured" class="radio" value="1" <?php if($company_result['featured']=='1') {echo "checked";}?>>&nbsp;&nbsp;<?php echo ucfirst(TEXT_YES);?>
      </TD>
    </TR>
    <?php if(ALLOW_HTML_MAIL=="yes") {?>
     <TR>
      <TD valign="top" width="25%">
        <B><?php echo TEXT_MAIL_TYPE;?>:</B>
      </TD>
      <TD width="75%">
        <INPUT type="radio" name="cmail_type" class="radio" value="text" <?php if ((!$company_result['cmail_type']) || ($company_result['cmail_type']=='text')) {echo "checked";}?>>&nbsp;<?php echo TEXT_PLAIN_TEXT;?>
        <INPUT type="radio" name="cmail_type" class="radio" value="html" <?php if ($company_result['cmail_type']=='html') {echo "checked";}?>>&nbsp;<?php echo TEXT_HTML_MAIL;?>
      </TD>
    </TR>
    <?php }else{?>
        <input type="hidden" name="cmail_type" value="text">
    <?php }?>
    <TR>
      <TD valign="top" width="25%" align="center">
       <?php
       $image_location = DIR_LOGO. $company_result['logo'];
       if ((!empty($company_result['logo'])) && (file_exists($image_location))) {
                  echo "<img src=\"".HTTP_LOGO.$company_result['logo']."\" border=1 align=absmiddle>";
                  echo "<br>&nbsp;&nbsp;[&nbsp;<a href=\"del_logo.php?compid=".$company_result["compid"]."&logo_name=".$company_result['logo']."\">Delete Logo</a>&nbsp;]";
       }//end if (file_exists($image_location))
       else {
                 echo "<font class=\"error\"><b>".TEXT_LOGO_NOT_AVAILABLE."</b></font>";
       }//end else if (file_exists($image_location))
       ?>
      </TD>
      <TD width="75%">
        <INPUT type="file" name="company_logo">
      </TD>
    </TR>
     <TR>
      <TD valign="top" width="25%">
        <B><?php echo TEXT_COMPANY;?>:</B>
      </TD>
      <TD width="75%">
        <INPUT type="text" name="company" size="30" value="<?php echo $company_result['company'];?>">
      </TD>
    </TR>
    <TR>
      <TD valign="top" width="25%">
        <B><?php echo TEXT_DESCRIPTION;?>:</B>
      </TD>
      <TD width="75%">
        <textarea name="description" rows="6" cols="50"><?php echo $company_result['description'];?></textarea>
      </TD>
    </TR>
    <TR>
      <TD valign="top" width="25%">
        <B><?php echo TEXT_ADDRESS;?>:</B>
      </TD>
      <TD width="75%">
        <textarea name="address" rows="4" cols="40"><?php echo $company_result['address'];?></textarea>
      </TD>
    </TR>
    <TR>
      <TD valign="top" width="25%">
        <B><?php echo TEXT_CITY;?>:</B>
      </TD>
      <TD width="75%">
        <INPUT type="text" name="city" size=30 value="<?php echo $company_result['city'];?>">
      </TD>
    </TR>
    <TR>
      <TD valign="top" width="25%">
        <B><?php echo TEXT_PROVINCE;?>:</B>
      </TD>
      <TD width="75%">
        <INPUT type="text" name="province" size=15 value="<?php echo $company_result['province'];?>">
      </TD>
    </TR>
    <TR>
      <TD valign="top" width="25%">
        <B><?php echo TEXT_POSTAL_CODE;?>:</B>
      </TD>
      <TD width="75%">
        <INPUT type="text" name="postalcode" size=10 value="<?php echo $company_result['postalcode'];?>">
      </TD>
    </TR>
    <TR>
      <TD valign="top" width="25%">
        <B><?php echo TEXT_COUNTRY;?>:</B>
      </TD>
      <TD width="75%">
        <SELECT name="location" size=1>
         <?php
          $country_query=bx_db_query("select * from ".$bx_table_prefix."_locations_".$bx_table_lng."");
          SQL_CHECK(0,"SQL Error at ".__FILE__.":".(__LINE__-1));
          while ($country_result=bx_db_fetch_array($country_query))
          {
          echo '<option value="'.$country_result['locationid'].'"';
          if ($company_result['locationid']==$country_result['locationid']) {echo "selected";}
          echo '>'.$country_result['location'].'</option>';
          }
          ?>
         </SELECT>
      </TD>
    </TR>
    <TR>
      <TD valign="top" width="25%">
        <B><?php echo TEXT_PHONE;?>:</B>
      </TD>
      <TD width="75%">
        <INPUT type="text" name="phone" size="30" value="<?php echo $company_result['phone'];?>">
      </TD>
    </TR>
    <TR>
      <TD valign="top" width="25%">
        <B><?php echo TEXT_FAX;?>:</B>
      </TD>
      <TD width="75%">
        <INPUT type="text" name="fax" size="30" value="<?php echo $company_result['fax'];?>">
      </TD>
    </TR>
    <TR>
      <TD valign="top" width="25%">
        <B><?php echo TEXT_EMAIL;?>:</B>
      </TD>
      <TD width="75%">
        <INPUT type="text" name="email" size="30" value="<?php echo $company_result['email'];?>">
      </TD>
    </TR>
    <TR>
      <TD valign="top" width="25%">
        <B><?php echo TEXT_URL;?>:</B>
      </TD>
      <TD width="75%">
        <INPUT type="text" name="url" size="30" value="<?php echo $company_result['url'];?>">
      </TD>
    </TR>
    <TR><TD colspan="2">&nbsp;</TD></TR>
    <TR><TD colspan="2"><b><?php echo TEXT_HIDE_NOTE;?></b></TD></TR>
    <TR><TD colspan="2">&nbsp;</TD></TR>
    <TR>
      <TD valign="top" width="25%">&nbsp;</TD>
      <TD width="75%"><input type="checkbox" class="radio" name="hide_address" value="yes"<?php if($company_result['hide_address'] == "yes") { echo " checked";}?>> <?php echo TEXT_HIDE_ADDRESS;?></TD>
    </TR>
    <TR>
      <TD valign="top" width="25%">&nbsp;</TD>
      <TD width="75%"><input type="checkbox" class="radio" name="hide_location" value="yes"<?php if($company_result['hide_location'] == "yes") { echo " checked";}?>> <?php echo TEXT_HIDE_LOCATION;?></TD>
    </TR>
    <TR>
      <TD valign="top" width="25%">&nbsp;</TD>
      <TD width="75%"><input type="checkbox" class="radio" name="hide_phone" value="yes"<?php if($company_result['hide_phone'] == "yes") { echo " checked";}?>> <?php echo TEXT_HIDE_PHONE;?></TD>
    </TR>
    <TR>
      <TD valign="top" width="25%">&nbsp;</TD>
      <TD width="75%"><input type="checkbox" class="radio" name="hide_fax" value="yes"<?php if($company_result['hide_fax'] == "yes") { echo " checked";}?>> <?php echo TEXT_HIDE_FAX;?></TD>
    </TR>
    <TR>
      <TD valign="top" width="25%">&nbsp;</TD>
      <TD width="75%"><input type="checkbox" class="radio" name="hide_email" value="yes"<?php if($company_result['hide_email'] == "yes") { echo " checked";}?>> <?php echo TEXT_HIDE_EMAIL;?></TD>
    </TR>
    <TR><TD colspan="2">&nbsp;</TD></TR>
    <TR>
      <TD valign="top" width="25%">
        <B><?php echo TEXT_PASSWORD;?>:</B>
      </TD>
      <TD width="75%">
        <INPUT type="password" name="password" size="30" value="<?php echo $company_result['password'];?>">
      </TD>
    </TR>
    <TR>
      <TD valign="top" width="25%">
        <B><?php echo TEXT_CONFIRM_PASSWORD;?>:</B>
      </TD>
      <TD width="75%">
        <INPUT type="password" name="confpassword" size="30" value="<?php if($error==1) {echo $company_result['confpassword'];} else {echo $company_result['password'];}?>">
      </TD>
    </TR>
    <TR>
      <TD width="25%">
        <B><?php echo TEXT_LAST_LOGIN;?>:</B>
      </TD>
      <TD colspan="4" width="75%">
          <b><?php
           $mytime = strtotime($company_result['lastlogin'])-time();  
           if ($mytime<0) {
               $mytime = - $mytime;
               $sign = "-";
           } 
           else {
               $sign = "+";
           }
           if ($mytime>=86400) {
               echo $sign.(floor($mytime/(3600*24)))."d ";    
           }
           else {
               echo "+0d ";
           }
           if (($mytime%(3600*24))>=3600) {
               echo $sign.floor(($mytime%(3600*24))/3600)."h ";    
               echo $sign.floor(($mytime%(3600))/60)."m "; 
           }
           else {
               echo "+0h ";
               echo $sign.floor(($mytime%(3600))/60)."m ";    
           }
           print "&nbsp;/&nbsp;";
           echo $company_result['lastlogin'];
          ?></b>
        </FONT>
      </TD>
    </TR>
    <TR>
      <TD width="25%">
        <B>IP:</B>
      </TD>
      <TD colspan="4" width="75%">
          <b><?php echo $company_result['lastip'];?></b>
        </FONT>
      </TD>
    </TR>
    <?php
    $compview_query=bx_db_query("select * from ".$bx_table_prefix."_compview where ".$bx_table_prefix."_compview.compid='".$HTTP_GET_VARS['compid']."'");
    SQL_CHECK(0,"SQL Error at ".__FILE__.":".(__LINE__-1));
    $compview_result=bx_db_fetch_array($compview_query);
    ?>
    <TR>
      <TD width="25%">
        <B>Profile Viewed:</B>
      </TD>
      <TD colspan="4" width="75%">
          <b><?php echo $compview_result['viewed'];?> time(s)</b>
        </FONT>
      </TD>
    </TR>
    <TR>
      <TD width="24%">
        <B>Last Time Viewed:</B>
      </TD>
      <TD colspan="4" width="75%">
          <b><?php echo $compview_result['lastdate'];?></b>
        </FONT>
      </TD>
    </TR>
    <TR>
      <TD valign="top" width="25%">
        <B><?php echo TEXT_AVAILABLE_JOBS;?>:</B>
      </TD>
      <TD width="75%">
        <INPUT type="text" name="jobs" size="10" value="<?php echo $company_result['jobs'];?>"> <b>999 = <?php echo TEXT_UNLIMITED;?></b>
      </TD>
    </TR>
    <TR>
      <TD valign="top" width="25%">
        <B><?php echo TEXT_AVAILABLE_FJOBS;?>:</B>
      </TD>
      <TD width="75%">
        <INPUT type="text" name="featuredjobs" size="10" value="<?php echo $company_result['featuredjobs'];?>">
      </TD>
    </TR>
    <TR>
      <TD valign="top" width="25%">
        <B><?php echo TEXT_AVAILABLE_RESUMES;?>:</B>
      </TD>
      <TD width="75%">
        <INPUT type="text" name="contacts" size="10" value="<?php echo $company_result['contacts'];?>"> <b>999 = <?php echo TEXT_UNLIMITED;?></b>
      </TD>
    </TR>
    <TR>
      <TD valign="top" width="25%">
        <B><?php echo TEXT_DISCOUNT;?>:</B>
      </TD>
      <TD width="75%">
        <INPUT type="text" name="discount" size="10" value="<?php echo $company_result['discount'];?>">
      </TD>
    </TR>
    <?php
    $membership_query = bx_db_query("SELECT * from ".$bx_table_prefix."_membership where compid='".$company_result['compid']."'");
    SQL_CHECK(0,"SQL Error at ".__FILE__.":".(__LINE__-1));
    $membership_result = bx_db_fetch_array($membership_query);
    $pricing_query = bx_db_query("SELECT * from ".$bx_table_prefix."_pricing_".$bx_table_lng." where pricing_id > 0");
    SQL_CHECK(0,"SQL Error at ".__FILE__.":".(__LINE__-1));
    ?>
    <TR>
      <TD valign="top" width="25%">
        <B><?php echo TEXT_CURRENT_PLANNING;?>:</B>
      </TD>
      <TD width="75%">
        <select name="pricing_id" size="1">
            <?php
            if (!($membership_result['pricing_id']>=0)) {
                echo "<option selected>".TEXT_NO_PLANNING."</option>";
            }
            while ($pricing_result = bx_db_fetch_array($pricing_query)) {
                if ($pricing_result['pricing_id'] == $membership_result['pricing_id']) {
                    $selected = " selected";
                }
                else {
                    $selected = "";
                }
                echo "<option value=\"".$pricing_result['pricing_id']."\"".$selected.">".$pricing_result['pricing_title']."</option>\n";
            }
            ?>
        </select>
      </TD>
    </TR>
    <TR>
      <TD valign="top" width="25%">
        <B><?php echo TEXT_PLANNING_EXPIRE;?>:</B>
      </TD>
      <TD width="75%">
        <INPUT type="text" name="expire" size="10" value="<?php echo $company_result['expire'];?>">&nbsp;<a href="javascript:show_calendar('company.expire');" onmouseOver="window.status='Calendar'; return true;" onmouseOut="window.status=''; return true;" ><img src="images/calendar.gif" align="absmiddle" border="0" alt=""></a>
      </TD>
    </TR>
    <TR>
      <td align="center" colspan="2" width="100%" style="padding-top: 20px; padding-left: 10px; padding-right: 10px; padding-bottom: 10px;">
      <table align="center" width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
            <td width="40%" nowrap>
                <INPUT type="button" name="btnCancel" onclick="self.history.back()" value="<?php echo TEXT_BUTTON_CANCEL;?>">&nbsp;&nbsp;&nbsp;&nbsp;
                <INPUT type="reset" name="btnReset" value="<?php echo TEXT_BUTTON_RESET;?>">&nbsp;&nbsp;
                <INPUT type="submit" name="btnSave" value="&nbsp;&nbsp;&nbsp;&nbsp;<?php echo TEXT_BUTTON_SAVE;?>&nbsp;&nbsp;&nbsp;&nbsp;">
            </td>
            <TD align="right" width="60%"><INPUT type="submit" name="btnDelete" value="<?php echo TEXT_BUTTON_DELETE;?>" onClick="return confirm('<?php echo eregi_replace("'","\'",TEXT_CONFIRM_COMPANY_DELETE);?>');"></TD>
        </tr>
      </table>
     </td>  
    </TR>
  </TABLE>
 </td></tr>
</table>
<input type="hidden" name="compid" value="<?php echo $HTTP_GET_VARS['compid'];?>">
<input type="hidden" name="action" value="<?php echo $HTTP_GET_VARS['action'];?>"> 
</form>