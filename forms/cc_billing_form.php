<?php include(DIR_LANGUAGES.$language."/".FILENAME_CC_BILLING_FORM);?>
<script language="Javascript">
<!--
function isNum(str)
   {
   // Return false if characters are not '0-9' or '.' .
   for (var i = 0; i < str.length; i++)
      {
      var ch = str.substring(i, i + 1);
      if ((ch < "0" || "9" < ch))
         {
         return 1;
         }
      }
   return 0;
  }
function isEmail(val)
   {
   // Return false if e-mail field does not contain an '@' and '.' .
   if (val.indexOf ('@',0) == -1 ||
       val.indexOf ('.',0) == -1)
      {
      return 1;
      }
   else
      {
      return 0;
      }
   }

function check_cc(formname) {
	var error = 0;
     var error_message = "<?php echo eregi_replace("\"","\\\"",JS_ERROR);?>";

	//Validation for CC Holder
	if(formname.auth_ccname.value == "") {
		error =1;
		error_message += "*<?php echo CC_NAME_ERROR;?>\n";
	}
	//Validation for CC typer
	if(formname.auth_cctype.value == "") {
		error =1;
		error_message += "*<?php echo CC_TYPE_ERROR;?>\n";
	}
	//Validation for CC NUMBER
	if( ((formname.auth_ccnum.value == "") || (isNum(formname.auth_ccnum.value) == 1)) ) {
		error =1;
		error_message += "*<?php echo CC_CARDNUM_ERROR;?>\n";
	}
	<?php if(CC_AVS=="yes") {?>
	//Validation for CC STREET
	if(formname.auth_ccstreet.value == "") {
		error =1;
		error_message += "*<?php echo CC_STREET_ERROR;?>\n";
	}
	//Validation for CC CITY
	if(formname.auth_cccity.value == "") {
		error =1;
		error_message += "*<?php echo CC_CITY_ERROR;?>\n";
	}
	//Validation for CC STATE
	if(formname.auth_ccstate.value == "") {
		error =1;
		error_message += "*<?php echo CC_STATE_ERROR;?>\n";
	}
	//Validation for CC POSTAL CODE
	if(formname.auth_cczip.value == "") {
		error =1;
		error_message += "*<?php echo CC_POSTALCODE_ERROR;?>\n";
	}
	//Validation for CC COUNTRY
	if(formname.auth_cccountry.value == "") {
		error =1;
		error_message += "*<?php echo CC_COUNTRY_ERROR;?>\n";
	}
	//Validation for CC PHONE
	if(formname.auth_ccphone.value == "") {
		error =1;
		error_message += "*<?php echo CC_PHONE_ERROR;?>\n";
	}
	//Validation for CC EMAIL
	if ((formname.auth_ccemail.value == "") || (isEmail(formname.auth_ccemail.value)==1)) {
        error_message += "*<?php echo CC_EMAIL_ERROR;?>\n";
        error = 1;
     }
	<?php }?>
	if(error == 1) {
		alert(error_message);
		return false;
	}
	else {
		return true;
	}
}
//-->
</script>
<table bgcolor="<?php echo TABLE_BGCOLOR;?>" width="100%" border="0" cellspacing="1" cellpadding="2">
   <TR bgcolor="#FFFFFF">
            <TD colspan="5" width="100%" align="center" class="headertdjob"><?php echo TEXT_BILLING_INFORMATION;?></TD>
   </TR>
   <TR><TD colspan="5"><table cellpadding="0" cellspacing="0" border="0" width="100%">
                <tr>
                    <td bgcolor="<?php echo TABLE_HEADING_BGCOLOR;?>" height="1"><?php echo bx_image_width(HTTP_IMAGES.$language."/pix-t.gif",1,1,0,"");?></td>
                </tr>
       </table></TD>
    </TR>
	</TABLE>
    <?php if($HTTP_SESSION_VARS['userid']){?>
	 <form method="post" action="<?php echo bx_make_url(((ENABLE_SSL == "yes") ? HTTPS_SERVER : HTTP_SERVER).FILENAME_JPROCESSING, "auth_sess", $bx_session);?>" onSubmit="return check_cc(this);">
     <?php }
     else {?>
   	 <form method="post" action="<?php echo bx_make_url(((ENABLE_SSL == "yes") ? HTTPS_SERVER : HTTP_SERVER).FILENAME_PROCESSING, "auth_sess", $bx_session);?>" onSubmit="return check_cc(this);">
     <?php }?>
	<input type="hidden" name="todo" value="verify">
	<input type="hidden" name="opid" value="<?php echo $HTTP_POST_VARS['opid'];?>">
    <?php if($HTTP_SESSION_VARS['userid'] || $HTTP_POST_VARS['persid']){?>
    <input type="hidden" name="persid" value="<?php if($HTTP_SESSION_VARS['userid']) {echo $HTTP_SESSION_VARS['userid'];} else if ($HTTP_POST_VARS['persid']) {echo $HTTP_POST_VARS['persid'];}?>">
    <?php }
    else {?>
    <input type="hidden" name="compid" value="<?php if($HTTP_SESSION_VARS['employerid']) {echo $HTTP_SESSION_VARS['employerid'];} else if ($HTTP_POST_VARS['compid']) {echo $HTTP_POST_VARS['compid'];}?>">
    <?php }?>
	<input type="hidden" name="payment_mode" value="<?php echo $HTTP_POST_VARS['payment_mode'];?>">
	<table bgcolor="<?php echo TABLE_BGCOLOR;?>" width="100%" border="0" cellspacing="1" cellpadding="2">
    <TR>
		<TD colspan="5" style="padding-left: 20px;"><font face="<?php echo TEXT_FONT_FACE;?>" size="<?php echo TEXT_FONT_SIZE;?>" color="<?php echo TEXT_FONT_COLOR;?>"><?php echo TEXT_BILLING_NOTE;?></FONT></TD>
	</TR>
	<?php if($auth_ccname_error == 1) {?>
		<TR>
			<TD colspan="5" style="padding-left: 20px;"><font face="<?php echo REQUIRED_TEXT_FONT_FACE;?>" size="<?php echo REQUIRED_TEXT_FONT_SIZE;?>" color="<?php echo REQUIRED_TEXT_FONT_COLOR;?>"><?php echo CC_NAME_ERROR;?></FONT></TD>
		</TR>
	<?php }?>
    <TR>
      <TD valign="top" width="24%">
        <font face="<?php echo TEXT_FONT_FACE;?>" size="<?php echo TEXT_FONT_SIZE;?>" color="<?php echo TEXT_FONT_COLOR;?>"><B><?php echo TEXT_CC_NAME;?>:</B></FONT>
      </TD>
      <TD colspan="4" width="76%">
        <font face="<?php echo TEXT_FONT_FACE;?>" size="<?php echo TEXT_FONT_SIZE;?>" color="<?php echo TEXT_FONT_COLOR;?>"><INPUT type="text" name="auth_ccname" size="30"  value="<?php echo $HTTP_POST_VARS['auth_ccname'];?>">
        </FONT> <?php echo REQUIRED_STAR;?>
      </TD>
    </TR>
	<?php if($auth_cctype_error == 1) {?>
		<TR>
			<TD colspan="5" style="padding-left: 20px;"><font face="<?php echo REQUIRED_TEXT_FONT_FACE;?>" size="<?php echo REQUIRED_TEXT_FONT_SIZE;?>" color="<?php echo REQUIRED_TEXT_FONT_COLOR;?>"><?php echo CC_TYPE_ERROR;?></FONT></TD>
		</TR>
	<?php }?>
    <TR>
      <TD valign="top" width="24%">
        <font face="<?php echo TEXT_FONT_FACE;?>" size="<?php echo TEXT_FONT_SIZE;?>" color="<?php echo TEXT_FONT_COLOR;?>"><B><?php echo TEXT_CC_TYPE;?>:</B></FONT>
      </TD>
      <TD colspan="4" width="76%">
		   <SELECT NAME="auth_cctype">
               <OPTION VALUE=""></OPTION>
               <?php
               $i=1;
                while (${TEXT_CCTYPE_OPT.$i}) {
                     echo '<option value="'.${TEXT_CCTYPE_OPT.$i}.'"';
                     if (${TEXT_CCTYPE_OPT.$i}==$HTTP_POST_VARS['auth_cctype']) {
                            echo " selected";
                     }
                     echo '>'.${TEXT_CCTYPE_OPT.$i}.'</option>';
                     $i++;
                }
                ?>
               </SELECT> <?php echo REQUIRED_STAR;?>
      </TD>
    </TR>
	<?php if($auth_ccnum_error == 1) {?>
		<TR>
			<TD colspan="5" style="padding-left: 20px;"><font face="<?php echo REQUIRED_TEXT_FONT_FACE;?>" size="<?php echo REQUIRED_TEXT_FONT_SIZE;?>" color="<?php echo REQUIRED_TEXT_FONT_COLOR;?>"><?php echo CC_CARDNUM_ERROR;?></FONT></TD>
		</TR>
	<?php }?>
	<TR>
      <TD valign="top" width="24%">
        <font face="<?php echo TEXT_FONT_FACE;?>" size="<?php echo TEXT_FONT_SIZE;?>" color="<?php echo TEXT_FONT_COLOR;?>"><B><?php echo TEXT_CC_NUM;?>:</B></FONT>
      </TD>
      <TD colspan="4" width="76%">
        <font face="<?php echo TEXT_FONT_FACE;?>" size="<?php echo TEXT_FONT_SIZE;?>" color="<?php echo TEXT_FONT_COLOR;?>"><INPUT type="text" name="auth_ccnum" size="20" value="<?php echo $HTTP_POST_VARS['auth_ccnum'];?>"></FONT> <?php echo REQUIRED_STAR;?>
      </TD>
    </TR>
    <TR>
      <TD valign="top" width="34%">
        <font face="<?php echo TEXT_FONT_FACE;?>" size="<?php echo TEXT_FONT_SIZE;?>" color="<?php echo TEXT_FONT_COLOR;?>"><B><?php echo TEXT_CC_VERIFICATION_CODE;?>:</B></FONT>
      </TD>
      <TD colspan="4" width="66%"><table width="100%" border="0" cellspacing="0" cellpadding="0"><tr><td>
        <font face="<?php echo TEXT_FONT_FACE;?>" size="<?php echo TEXT_FONT_SIZE;?>" color="<?php echo TEXT_FONT_COLOR;?>"><INPUT type="text" name="auth_ccvcode" size="3" value="<?php echo $HTTP_POST_VARS['auth_ccvcode'];?>">&nbsp;</FONT>
        </td>
        <td><font face="<?php echo TEXT_FONT_FACE;?>" size="1" color="<?php echo TEXT_FONT_COLOR;?>"><?php echo TEXT_CC_VERIFICATION_NOTE;?></font></td>
        </tr></table>
      </TD>
    </TR>
	<?php if($auth_exp_error == 1) {?>
		<TR>
			<TD colspan="5" style="padding-left: 20px;"><font face="<?php echo REQUIRED_TEXT_FONT_FACE;?>" size="<?php echo REQUIRED_TEXT_FONT_SIZE;?>" color="<?php echo REQUIRED_TEXT_FONT_COLOR;?>"><?php echo CC_EXP_ERROR;?></FONT></TD>
		</TR>
	<?php }?>
    <TR>
      <TD valign="top" width="24%">
        <font face="<?php echo TEXT_FONT_FACE;?>" size="<?php echo TEXT_FONT_SIZE;?>" color="<?php echo TEXT_FONT_COLOR;?>"><B><?php echo TEXT_CC_EXPIRE;?>:</B></FONT>
      </TD>
      <TD colspan="4" width="76%">
        <font face="<?php echo TEXT_FONT_FACE;?>" size="<?php echo TEXT_FONT_SIZE;?>" color="<?php echo TEXT_FONT_COLOR;?>">
		<SELECT NAME="auth_expmonth">
			<option value="01"<?php if($HTTP_POST_VARS['auth_expmonth'] == "01") { echo " selected";}?>>January</option><option value="02"<?php if($HTTP_POST_VARS['auth_expmonth'] == "02") { echo " selected";}?>>February</option><option value="03"<?php if($HTTP_POST_VARS['auth_expmonth'] == "03") { echo " selected";}?>>March</option><option value="04"<?php if($HTTP_POST_VARS['auth_expmonth'] == "04") { echo " selected";}?>>April</option><option value="05"<?php if($HTTP_POST_VARS['auth_expmonth'] == "05") { echo " selected";}?>>May</option><option value="06"<?php if($HTTP_POST_VARS['auth_expmonth'] == "06") { echo " selected";}?>>June</option><option value="07"<?php if($HTTP_POST_VARS['auth_expmonth'] == "07") { echo " selected";}?>>July</option><option value="08"<?php if($HTTP_POST_VARS['auth_expmonth'] == "08") { echo " selected";}?>>August</option><option value="09"<?php if($HTTP_POST_VARS['auth_expmonth'] == "09") { echo " selected";}?>>September</option><option value="10"<?php if($HTTP_POST_VARS['auth_expmonth'] == "10") { echo " selected";}?>>October</option><option value="11"<?php if($HTTP_POST_VARS['auth_expmonth'] == "11") { echo " selected";}?>>November</option><option value="12"<?php if($HTTP_POST_VARS['auth_expmonth'] == "12") { echo " selected";}?>>December</option>
		</SELECT>
		<SELECT NAME="auth_expyear">
		<?php 
			  $today=getdate(); 
			  for ($i=$today['year']; $i < $today['year']+10; $i++) {
					echo '<option value="' . strftime("%Y",mktime(0,0,0,1,1,$i)) . '"';
					if($i == $HTTP_POST_VARS['auth_expyear']) {
						echo " selected";
					}
					echo '>' . strftime("%Y",mktime(0,0,0,1,1,$i)) . '</option>';
			  }
     	?>
		</SELECT>
        </FONT> <?php echo REQUIRED_STAR;?>
      </TD>
    </TR>
	<?php if(CC_AVS == "yes") {?>
	<TR>
		<TD colspan="5" style="padding-left: 20px;"><font face="<?php echo TEXT_DESCRIPTION_FONT_FACE;?>" size="<?php echo TEXT_DESCRIPTION_FONT_SIZE;?>" color="<?php echo TEXT_DESCRIPTION_FONT_COLOR;?>">&nbsp;</FONT></TD>
	</TR>
    <TR>
		<TD colspan="5" style="padding-left: 20px;"><font face="<?php echo TEXT_DESCRIPTION_FONT_FACE;?>" size="<?php echo TEXT_DESCRIPTION_FONT_SIZE;?>" color="<?php echo TEXT_DESCRIPTION_FONT_COLOR;?>"><?php echo TEXT_CARDHOLDER_INFO;?></FONT></TD>
	</TR>
	<?php if($auth_ccstreet_error == 1) {?>
		<TR>
			<TD colspan="5" style="padding-left: 20px;"><font face="<?php echo REQUIRED_TEXT_FONT_FACE;?>" size="<?php echo REQUIRED_TEXT_FONT_SIZE;?>" color="<?php echo REQUIRED_TEXT_FONT_COLOR;?>"><?php echo CC_STREET_ERROR;?></FONT></TD>
		</TR>
	<?php }?>
    <TR>
      <TD valign="top" width="24%">
        <font face="<?php echo TEXT_FONT_FACE;?>" size="<?php echo TEXT_FONT_SIZE;?>" color="<?php echo TEXT_FONT_COLOR;?>"><B><?php echo TEXT_CC_STREET;?>:</B></FONT>
      </TD>
      <TD width="24%">
        <font face="<?php echo TEXT_FONT_FACE;?>" size="<?php echo TEXT_FONT_SIZE;?>" color="<?php echo TEXT_FONT_COLOR;?>"><textarea name="auth_ccstreet" cols="30"  rows="5"><?php echo $HTTP_POST_VARS['auth_ccstreet'];?></textarea></FONT></TD>
	 <TD colspan="3" width="52%" valign="top"><?php echo REQUIRED_STAR;?>
      </TD>
    </TR>
	<?php if($auth_cccity_error == 1) {?>
		<TR>
			<TD colspan="5" style="padding-left: 20px;"><font face="<?php echo REQUIRED_TEXT_FONT_FACE;?>" size="<?php echo REQUIRED_TEXT_FONT_SIZE;?>" color="<?php echo REQUIRED_TEXT_FONT_COLOR;?>"><?php echo CC_CITY_ERROR;?></FONT></TD>
		</TR>
	<?php }?>
	<TR>
      <TD valign="top" width="24%">
        <font face="<?php echo TEXT_FONT_FACE;?>" size="<?php echo TEXT_FONT_SIZE;?>" color="<?php echo TEXT_FONT_COLOR;?>"><B><?php echo TEXT_CC_CITY;?>:</B></FONT>
      </TD>
      <TD colspan="4" width="76%">
        <font face="<?php echo TEXT_FONT_FACE;?>" size="<?php echo TEXT_FONT_SIZE;?>" color="<?php echo TEXT_FONT_COLOR;?>"><INPUT type="text" name="auth_cccity" size="30"  value="<?php echo $HTTP_POST_VARS['auth_cccity'];?>">
        </FONT> <?php echo REQUIRED_STAR;?>
      </TD>
    </TR>
	<?php if($auth_ccstate_error == 1) {?>
		<TR>
			<TD colspan="5" style="padding-left: 20px;"><font face="<?php echo REQUIRED_TEXT_FONT_FACE;?>" size="<?php echo REQUIRED_TEXT_FONT_SIZE;?>" color="<?php echo REQUIRED_TEXT_FONT_COLOR;?>"><?php echo CC_STATE_ERROR;?></FONT></TD>
		</TR>
	<?php }?>
	<TR>
      <TD valign="top" width="24%">
        <font face="<?php echo TEXT_FONT_FACE;?>" size="<?php echo TEXT_FONT_SIZE;?>" color="<?php echo TEXT_FONT_COLOR;?>"><B><?php echo TEXT_CC_STATE;?>:</B></FONT>
      </TD>
      <TD colspan="4" width="76%">
        <font face="<?php echo TEXT_FONT_FACE;?>" size="<?php echo TEXT_FONT_SIZE;?>" color="<?php echo TEXT_FONT_COLOR;?>"><INPUT type="text" name="auth_ccstate" size="30"  value="<?php echo $HTTP_POST_VARS['auth_ccstate'];?>">
        </FONT> <?php echo REQUIRED_STAR;?>
      </TD>
    </TR>
	<?php if($auth_cczip_error == 1) {?>
		<TR>
			<TD colspan="5" style="padding-left: 20px;"><font face="<?php echo REQUIRED_TEXT_FONT_FACE;?>" size="<?php echo REQUIRED_TEXT_FONT_SIZE;?>" color="<?php echo REQUIRED_TEXT_FONT_COLOR;?>"><?php echo CC_POSTALCODE_ERROR;?></FONT></TD>
		</TR>
	<?php }?>
	<TR>
      <TD valign="top" width="24%">
        <font face="<?php echo TEXT_FONT_FACE;?>" size="<?php echo TEXT_FONT_SIZE;?>" color="<?php echo TEXT_FONT_COLOR;?>"><B><?php echo TEXT_CC_ZIP;?>:</B></FONT>
      </TD>
      <TD colspan="4" width="76%">
        <font face="<?php echo TEXT_FONT_FACE;?>" size="<?php echo TEXT_FONT_SIZE;?>" color="<?php echo TEXT_FONT_COLOR;?>"><INPUT type="text" name="auth_cczip" size="30"  value="<?php echo $HTTP_POST_VARS['auth_cczip'];?>">
        </FONT> <?php echo REQUIRED_STAR;?>
      </TD>
    </TR>
	<?php if($auth_cccountry_error == 1) {?>
		<TR>
			<TD colspan="5" style="padding-left: 20px;"><font face="<?php echo REQUIRED_TEXT_FONT_FACE;?>" size="<?php echo REQUIRED_TEXT_FONT_SIZE;?>" color="<?php echo REQUIRED_TEXT_FONT_COLOR;?>"><?php echo CC_COUNTRY_ERROR;?></FONT></TD>
		</TR>
	<?php }?>
	<TR>
      <TD valign="top" width="24%">
        <font face="<?php echo TEXT_FONT_FACE;?>" size="<?php echo TEXT_FONT_SIZE;?>" color="<?php echo TEXT_FONT_COLOR;?>"><B><?php echo TEXT_CC_COUNTRY;?>:</B></FONT>
      </TD>
      <TD colspan="4" width="76%">
        <font face="<?php echo TEXT_FONT_FACE;?>" size="<?php echo TEXT_FONT_SIZE;?>" color="<?php echo TEXT_FONT_COLOR;?>"><INPUT type="text" name="auth_cccountry" size="30"  value="<?php echo $HTTP_POST_VARS['auth_cccountry'];?>">
        </FONT> <?php echo REQUIRED_STAR;?>
      </TD>
    </TR>
	<?php if($auth_ccphone_error == 1) {?>
		<TR>
			<TD colspan="5" style="padding-left: 20px;"><font face="<?php echo REQUIRED_TEXT_FONT_FACE;?>" size="<?php echo REQUIRED_TEXT_FONT_SIZE;?>" color="<?php echo REQUIRED_TEXT_FONT_COLOR;?>"><?php echo CC_PHONE_ERROR;?></FONT></TD>
		</TR>
	<?php }?>
	<TR>
      <TD valign="top" width="24%">
        <font face="<?php echo TEXT_FONT_FACE;?>" size="<?php echo TEXT_FONT_SIZE;?>" color="<?php echo TEXT_FONT_COLOR;?>"><B><?php echo TEXT_CC_PHONE;?>:</B></FONT>
      </TD>
      <TD colspan="4" width="76%">
        <font face="<?php echo TEXT_FONT_FACE;?>" size="<?php echo TEXT_FONT_SIZE;?>" color="<?php echo TEXT_FONT_COLOR;?>"><INPUT type="text" name="auth_ccphone" size="30"  value="<?php echo $HTTP_POST_VARS['auth_ccphone'];?>">
        </FONT> <?php echo REQUIRED_STAR;?>
      </TD>
    </TR>
	<?php if($auth_ccemail_error == 1) {?>
		<TR>
			<TD colspan="5" style="padding-left: 20px;"><font face="<?php echo REQUIRED_TEXT_FONT_FACE;?>" size="<?php echo REQUIRED_TEXT_FONT_SIZE;?>" color="<?php echo REQUIRED_TEXT_FONT_COLOR;?>"><?php echo CC_EMAIL_ERROR;?></FONT></TD>
		</TR>
	<?php }?>
	<TR>
      <TD valign="top" width="24%">
        <font face="<?php echo TEXT_FONT_FACE;?>" size="<?php echo TEXT_FONT_SIZE;?>" color="<?php echo TEXT_FONT_COLOR;?>"><B><?php echo TEXT_CC_EMAIL;?>:</B></FONT>
      </TD>
      <TD colspan="4" width="76%">
        <font face="<?php echo TEXT_FONT_FACE;?>" size="<?php echo TEXT_FONT_SIZE;?>" color="<?php echo TEXT_FONT_COLOR;?>"><INPUT type="text" name="auth_ccemail" size="30"  value="<?php echo $HTTP_POST_VARS['auth_ccemail'];?>">
        </FONT> <?php echo REQUIRED_STAR;?>
      </TD>
    </TR>
	<TR>
		<TD colspan="5" style="padding-left: 20px;"><font face="<?php echo TEXT_DESCRIPTION_FONT_FACE;?>" size="<?php echo TEXT_DESCRIPTION_FONT_SIZE;?>" color="<?php echo TEXT_DESCRIPTION_FONT_COLOR;?>">&nbsp;</FONT></TD>
	</TR>
    <?php }?>
    <TR>
      <TD valign="top" width="24%">
        <font face="<?php echo TEXT_FONT_FACE;?>" size="<?php echo TEXT_FONT_SIZE;?>" color="<?php echo TEXT_FONT_COLOR;?>"><B><?php echo TEXT_CC_AMOUNT;?>:</B></FONT>
      </TD>
      <TD colspan="4" width="76%">
        <font face="<?php echo TEXT_FONT_FACE;?>" size="<?php echo TEXT_FONT_SIZE;?>" color="<?php echo TEXT_FONT_COLOR;?>"><b><?php echo bx_format_price($invoice_result['totalprice'],PRICE_CURENCY);?></b></FONT>
      </TD>
    </TR>
    <TR>
      <TD valign="top" width="24%">
        <font face="<?php echo TEXT_FONT_FACE;?>" size="<?php echo TEXT_FONT_SIZE;?>" color="<?php echo TEXT_FONT_COLOR;?>"><B><?php echo TEXT_CC_COMMENTS;?>:</B></FONT>
      </TD>
      <TD width="24%" colspan="4" valign="top">
        <font face="<?php echo TEXT_FONT_FACE;?>" size="<?php echo TEXT_FONT_SIZE;?>" color="<?php echo TEXT_FONT_COLOR;?>"><textarea name="auth_cccomm" rows="5" cols="30"><?php echo $HTTP_POST_VARS['auth_cccomm'];?></textarea></FONT>
      </TD>
    </TR>
	<TR>
		<TD colspan="5" align="center"><font face="<?php echo TEXT_FONT_FACE;?>" size="<?php echo TEXT_FONT_SIZE;?>" color="<?php echo TEXT_FONT_COLOR;?>"><input type="submit" name="proceed" value="<?php echo TEXT_PROCEED;?>">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="reset" name="preset" value="<?php echo TEXT_BUTTON_RESET;?>"></FONT></TD>
	</TR>
	</table>
</form>