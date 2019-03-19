<?php include(DIR_SERVER_ROOT."header.php");?>
<?php include(DIR_LANGUAGES.$language."/".FILENAME_CC_BILLING_FORM);?>
<?php include(DIR_LANGUAGES.$language."/".FILENAME_PAYMENT_FORM);?>
<?php
if($HTTP_POST_VARS['todo'] == "proceed") {
    if(CC_NOTIFY_ADMIN == "yes") {
            $themessage.="          Invoice details (No: ".$invoice_result['opid'].")\n";
            $themessage.="-----------------------Begin Company information(Sender)---------\n";
            $themessage.="User name: ".$invoice_result['name']."\n";
            $themessage.="User email: ".$invoice_result['username']."\n";
            //$themessage.="Address: ".$invoice_result['address']."\n";
            //$themessage.="Company details: ".HTTP_SERVER.FILENAME_VIEW."?company_id=".$invoice_result['compid']."\n";
            $themessage.="-----------------------End Company information---------\n\n";
            $themessage.="-----------------------Begin Payment information---------\n";
            if(MANUAL_STORE == "email") {
                $themessage.="Name of CardHolder: ".$HTTP_SESSION_VARS['sess_auth_ccname']."\n";
                $themessage.="Credit Card Type: ".$HTTP_SESSION_VARS['sess_auth_cctype']."\n";	
                $themessage.="Credit Card Number: ".$HTTP_SESSION_VARS['sess_auth_ccnum']."\n";		
                $themessage.="Credit Card Verification Code: ".(($HTTP_SESSION_VARS['sess_auth_ccvcode'])?$HTTP_SESSION_VARS['sess_auth_ccvcode']:"N/A")."\n";
                $themessage.="Expire Date: ".$HTTP_SESSION_VARS['sess_auth_ccexp']."\n";			
                if(CC_AVS == "yes") {
                    $themessage.="Street Address: ".$HTTP_SESSION_VARS['sess_auth_ccstreet']."\n";			
                    $themessage.="City: ".$HTTP_SESSION_VARS['sess_auth_cccity']."\n";					
                    $themessage.="State/Province: ".$HTTP_SESSION_VARS['sess_auth_ccstate']."\n";							
                    $themessage.="Zip/ Postal Code: ".$HTTP_SESSION_VARS['sess_auth_cczip']."\n";									
                    $themessage.="Country: ".$HTTP_SESSION_VARS['sess_auth_cccountry']."\n";											
                    $themessage.="Phone: ".$HTTP_SESSION_VARS['sess_auth_ccphone']."\n";													
                    $themessage.="Email: ".$HTTP_SESSION_VARS['sess_auth_ccemail']."\n";															
                }
            }
            $themessage.="Comment: \n".stripslashes($HTTP_SESSION_VARS['sess_auth_cccomm'])."\n\n";															
            $themessage.="List Price: ".bx_format_price($invoice_result['totalprice'],$invoice_result['icurrency'])."\n";
            if(USE_VAT == "yes") {
                     $themessage.="VAT: ".bx_format_price((($invoice_result['totalprice'])*$invoice_result['vat']/100),$invoice_result['currency'])." (".$invoice_result['vat']." %)"."\n";
            }
            $themessage.="Total Price: ".bx_format_price($invoice_result['totalprice'],$invoice_result['icurrency'])."\n";
            $themessage.="Payment date: ".bx_format_date(date('Y-m-d'), DATE_FORMAT)."\n";
            $themessage.="-----------------------Begin Payment modalities---------\n";
            $themessage.="Customer selected payment modality: \n";
            $themessage.="          ".${TEXT_PAYMENT_OPT.$HTTP_POST_VARS['payment_mode']}."\n";
            $themessage.="-----------------------End Payment modalities---------\n\n";
			if ($invoice_result['i_purchased_nr']=='-1')
			{
				$themessage.="Payment description: ".TEXT_BANNER_PURCHASE.' '.$invoice_result['typename'.$slng].'  '.TEXT_BANNER_PURCHASE_NR.TEXT_FLAT.', '.$invoice_result['i_period'].TEXT_MONTH."\n";
			}else
			{
				$themessage.="Payment description: ".TEXT_BANNER_PURCHASE.' '.$invoice_result['typename'.$slng].'  '.TEXT_BANNER_PURCHASE_NR.' '.$invoice_result['i_purchased_nr']."\n";						
			}

            $themessage.="Details: ".HTTP_SERVER_ADMIN."admin.php?action=buyers\n";
            $themessage.="-----------------------End Payment information---------\n";
            $themessage.=SITE_SIGNATURE."\n";
			echo $themessage;echo '<br>111111111';exit;
            bx_mail(SITE_NAME,SITE_MAIL,SITE_NAME."<".SITE_MAIL.">",TEXT_INVOICE_NUMBER.": ".$invoice_result['opid'],$themessage,"no");
    } 
    if(MANUAL_STORE == "db"){
         include(DIR_FUNCTIONS."cclib/class.rc4crypt.php");
         $encoder = new rc4crypt();
         $trans_query = bx_db_query("delete from ".$bx_table_prefix."_cctransactions where opid ='".$HTTP_POST_VARS['opid']."'");
         SQL_CHECK(0,"SQL Error at ".__FILE__.":".(__LINE__-1));
         bx_db_insert($bx_table_prefix."_cctransactions","transid,opid,cc_name,cc_type,cc_num,cc_cvc,cc_exp,cc_street,cc_city,cc_state,cc_zip,cc_country,cc_phone,cc_email","'".time()."','".$HTTP_POST_VARS['opid']."','".$encoder->endecrypt(CRYPT_PHRASE,$HTTP_SESSION_VARS['sess_auth_ccname'], "en")."','".$encoder->endecrypt(CRYPT_PHRASE,$HTTP_SESSION_VARS['sess_auth_cctype'],"en")."','".$encoder->endecrypt(CRYPT_PHRASE,$HTTP_SESSION_VARS['sess_auth_ccnum'],"en")."','".$encoder->endecrypt(CRYPT_PHRASE,$HTTP_SESSION_VARS['sess_auth_ccvcode'],"en")."','".$encoder->endecrypt(CRYPT_PHRASE,$HTTP_SESSION_VARS['sess_auth_ccexp'],"en")."','".$encoder->endecrypt(CRYPT_PHRASE,$HTTP_SESSION_VARS['sess_auth_ccstreet'],"en")."','".$encoder->endecrypt(CRYPT_PHRASE,$HTTP_SESSION_VARS['sess_auth_cccity'],"en")."','".$encoder->endecrypt(CRYPT_PHRASE,$HTTP_SESSION_VARS['sess_auth_ccstate'],"en")."','".$encoder->endecrypt(CRYPT_PHRASE,$HTTP_SESSION_VARS['sess_auth_cczip'],"en")."','".$encoder->endecrypt(CRYPT_PHRASE,$HTTP_SESSION_VARS['sess_auth_cccountry'],"en")."','".$encoder->endecrypt(CRYPT_PHRASE,$HTTP_SESSION_VARS['sess_auth_ccphone'],"en")."','".$encoder->endecrypt(CRYPT_PHRASE,$HTTP_SESSION_VARS['sess_auth_ccemail'],"en")."'");
         SQL_CHECK(0,"SQL Error at ".__FILE__.":".(__LINE__-1));
    }
    if(CC_NOTIFY_BUYER) {
        $mailfile = $language."/mail/cc_manualprocess.txt";
        include(DIR_LANGUAGES.$mailfile.".cfg.php");
        if($mail_type=="1" && ALLOW_HTML_MAIL=="yes" && $invoice_result['cmail_type'] == "html") {
                 $mailfile .= ".html";
                 $html_mail = "yes";
        }
        $mail_message = fread(fopen(DIR_LANGUAGES.$mailfile,"r"),filesize(DIR_LANGUAGES.$mailfile));
        $invoice_result['listprice'] = bx_format_price($invoice_result['totalprice'],$invoice_result['icurrency']);
        $invoice_result['vat'] = bx_format_price((($invoice_result['listprice']-($invoice_result['listprice'])*$invoice_result['vat']/100),$invoice_result['currency'])." (".$invoice_result['vat']." %)";
        $invoice_result['totalprice'] = bx_format_price($invoice_result['totalprice'],$invoice_result['icurrency']);
        $invoice_result['invoice_paymentdate'] = bx_format_date(date('Y-m-d'), DATE_FORMAT);
        $invoice_result['auth_name'] = $HTTP_SESSION_VARS['sess_auth_ccname'];
        $invoice_result['auth_type'] = $HTTP_SESSION_VARS['sess_auth_cctype'];	
        $invoice_result['auth_ccnum'] = eregi_replace('([0-9])','x', $HTTP_SESSION_VARS['sess_auth_ccnum']);
        $invoice_result['auth_ccvcode'] = $HTTP_SESSION_VARS['sess_auth_ccvcode'];
        $invoice_result['auth_exp'] = $HTTP_SESSION_VARS['sess_auth_ccexp'];
        $invoice_result['auth_comm'] =$HTTP_SESSION_VARS['sess_auth_cccomm'];    
        if(CC_AVS == "yes") {
            $invoice_result['auth_street'] = $HTTP_SESSION_VARS['sess_auth_ccstreet'];	
            $invoice_result['auth_city'] = $HTTP_SESSION_VARS['sess_auth_cccity'];			
            $invoice_result['auth_state'] = $HTTP_SESSION_VARS['sess_auth_ccstate'];					
            $invoice_result['auth_zip'] = $HTTP_SESSION_VARS['sess_auth_cczip'];							
            $invoice_result['auth_country'] = $HTTP_SESSION_VARS['sess_auth_cc'];							
            $invoice_result['auth_phone'] = $HTTP_SESSION_VARS['sess_auth_ccphone'];											
            $invoice_result['auth_email'] = $HTTP_SESSION_VARS['sess_auth_ccemail'];													
        }
        reset($fields);
        while (list($h, $v) = each($fields)) {
                  $mail_message = eregi_replace($v[0],$invoice_result[$h],$mail_message);
                  $file_mail_subject = eregi_replace($v[0],$invoice_result[$h],$file_mail_subject);
        }
        if($invoice_result['cmail_type'] == "html" && ALLOW_HTML_MAIL=="yes") {
                 if ($add_html_header == "on") {
                     $mail_message = fread(fopen(DIR_LANGUAGES.$language."/html/html_email_message_header.html","r"),filesize(DIR_LANGUAGES.$language."/html/html_email_message_header.html")).$mail_message;
                 } 
                 if ($add_html_footer == "on") {
                     $mail_message .= fread(fopen(DIR_LANGUAGES.$language."/html/html_email_message_footer.html","r"),filesize(DIR_LANGUAGES.$language."/html/html_email_message_footer.html"));
                 } 
        }
        else {
                 if ($add_mail_signature == "on") {
                     $mail_message .= "\n".SITE_SIGNATURE;
                 }
                 $mail_message = bx_unhtmlspecialchars($mail_message);
        }
		echo $themessage;echo '<br>22222222';exit;
        bx_mail(SITE_NAME,SITE_MAIL,$invoice_result['username'], stripslashes($file_mail_subject), stripslashes($mail_message), $html_mail); 
    }    
	bx_db_query("UPDATE ".$bx_table_prefix."_invoices set payment_date='".date('Y-m-d')."', paid='Y', updated='N', validated='N', payment_mode='".$HTTP_POST_VARS['payment_mode']."',info='".$HTTP_SESSION_VARS['sess_auth_cccomm']."', i_start_date='".date('Y-m-d')."' where ".$bx_table_prefix."_invoices.opid='".$HTTP_POST_VARS['opid']."'");
	SQL_CHECK(0,"SQL Error at ".__FILE__.":".(__LINE__-1));

 	bx_session_unregister("sess_auth_ccname");
	bx_session_unregister("sess_auth_cctype");
	bx_session_unregister("sess_auth_ccnum");
    bx_session_unregister("sess_auth_ccvcode");
	bx_session_unregister("sess_auth_ccexp");
	bx_session_unregister("sess_auth_ccstreet");
	bx_session_unregister("sess_auth_cccity");
	bx_session_unregister("sess_auth_ccstate");
	bx_session_unregister("sess_auth_cczip");
    bx_session_unregister("sess_auth_cccountry");
	bx_session_unregister("sess_auth_ccphone");
	bx_session_unregister("sess_auth_ccemail");
	?>
    <table bgcolor="<?php echo TABLE_BGCOLOR;?>" width="100%" border="0" cellspacing="0" cellpadding="0" bordercolor="<?php echo TABLE_BORDERCOLOR;?>" bordercolorlight="<?php echo TABLE_BORDERCOLORLIGHT;?>" bordercolordark="<?php echo TABLE_BORDERCOLORDARK;?>">
	<TR bgcolor="#FFFFFF">
       <TD width="100%" align="center" class="headertdjob"><?php echo TEXT_INFORMATION_SAVED;?></TD>
    </TR>
    <TR><TD><table cellpadding="0" cellspacing="0" border="0" width="100%">
                  <tr>
                     <td bgcolor="<?php echo TABLE_HEADING_BGCOLOR;?>" height="1"><?php echo bx_image_width(HTTP_IMAGES.$language."/pix-t.gif",1,1,0,"");?></td>
                  </tr>
                  </table></TD>
    </TR>
    <TR>
       <TD>&nbsp;</TD>
    </TR>
    <TR>
      <TD align="left" valign="middle" width="100%">
        <font face="<?php echo TEXT_FONT_FACE;?>" size="<?php echo TEXT_FONT_SIZE;?>" color="<?php echo TEXT_FONT_COLOR;?>"><?php echo TEXT_EMAIL_WAS_SENT;?></FONT>
      </TD>
    </TR>
    <TR>
       <TD>&nbsp;</TD>
   </TR>
    <TR>
       <TD align="center" valign="middle" width="100%">
        <form action="<?php echo bx_make_url(HTTP_SERVER.FILENAME_MEMBERSHIP, "auth_sess", $bx_session);?>" method="post">
         <font face="<?php echo TEXT_FONT_FACE;?>" size="<?php echo TEXT_FONT_SIZE;?>" color="<?php echo TEXT_FONT_COLOR;?>"></FONT><input type="submit" value="<?php echo BUTTON_BACK_MEMBERSHIP;?>"></form>
      </TD>
     </TR>
     <TR>
       <TD>&nbsp;</TD>
     </TR>
</TABLE>          
<?php
}
else if ($HTTP_POST_VARS['todo'] == "verify") {
//make validation
$error = 0;
if($HTTP_POST_VARS['auth_ccname'] == "") {
	$error = 1;
	$auth_ccname_error = 1;
}
else {
	$auth_ccname_error = 0;
}
if($HTTP_POST_VARS['auth_cctype'] == "") {
	$error = 1;
	$auth_cctype_error = 1;
}
else {
	$auth_cctype_error = 0;
}
if( (($HTTP_POST_VARS['auth_ccnum'] == "") || (verify($HTTP_POST_VARS['auth_ccnum'],"int")==1)) ) {
	$error = 1;
	$auth_ccnum_error = 1;
}
else {
	$auth_ccnum_error = 0;
}
if(mktime(0,0,0,$HTTP_POST_VARS['auth_expmonth'],date('d'),$HTTP_POST_VARS['auth_expyear']) < mktime(0,0,0,date('m'),date('d'),date('Y'))) {
	$error = 1;
	$auth_exp_error = 1;
}
else {
	$auth_exp_error = 0;
}
if(CC_AVS == "yes") {
    if($HTTP_POST_VARS['auth_ccstreet'] == "") {
        $error = 1;
        $auth_ccstreet_error = 1;
    }
    else {
        $auth_ccstreet_error = 0;
    }
    if($HTTP_POST_VARS['auth_cccity'] == "") {
        $error = 1;
        $auth_cccity_error = 1;
    }
    else {
        $auth_cccity_error = 0;
    }
    if($HTTP_POST_VARS['auth_ccstate'] == "") {
        $error = 1;
        $auth_ccstate_error = 1;
    }
    else {
        $auth_ccstate_error = 0;
    }
    if($HTTP_POST_VARS['auth_cczip'] == "") {
        $error = 1;
        $auth_cczip_error = 1;
    }
    else {
        $auth_cczip_error = 0;
    }
    if($HTTP_POST_VARS['auth_cccountry'] == "") {
        $error = 1;
        $auth_cccountry_error = 1;
    }
    else {
        $auth_cccountry_error = 0;
    }
    if($HTTP_POST_VARS['auth_ccphone'] == "") {
        $error = 1;
        $auth_ccphone_error = 1;
    }
    else {
        $auth_ccphone_error = 0;
    }
    if( ($HTTP_POST_VARS['auth_ccemail'] == "") || ( (!eregi("(@)(.*)",$HTTP_POST_VARS['auth_ccemail'],$regs)) || (!eregi("([.])(.*)",$HTTP_POST_VARS['auth_ccemail'],$regs))) ) {
        $error = 1;
        $auth_ccemail_error = 1;
    }
    else {
        $auth_ccemail_error = 0;
    }
}    
if($error == 1) {
		include(DIR_FORMS.FILENAME_CC_BILLING_FORM);
}
else {
?>
<form method="post" action="<?php echo bx_make_url(((ENABLE_SSL == "yes") ? HTTPS_SERVER : HTTP_SERVER).FILENAME_PROCESSING, "auth_sess", $bx_session);?>">
<input type="hidden" name="opid" value="<?php echo $HTTP_POST_VARS['opid'];?>">
<input type="hidden" name="compid" value="<?php if($HTTP_SESSION_VARS['employerid']) {echo $HTTP_SESSION_VARS['employerid'];} else if ($HTTP_POST_VARS['compid']) {echo $HTTP_POST_VARS['compid'];}?>">
<input type="hidden" name="todo" value="proceed">
<?php
$sess_auth_ccname = $HTTP_POST_VARS['auth_ccname'];
bx_session_register("sess_auth_ccname");
$sess_auth_cctype = $HTTP_POST_VARS['auth_cctype'];
bx_session_register("sess_auth_cctype");
$sess_auth_ccnum = $HTTP_POST_VARS['auth_ccnum'];
bx_session_register("sess_auth_ccnum");
$sess_auth_ccvcode = $HTTP_POST_VARS['auth_ccvcode'];
bx_session_register("sess_auth_ccvcode");
$sess_auth_ccexp = $HTTP_POST_VARS['auth_expmonth']."/".$HTTP_POST_VARS['auth_expyear'];
bx_session_register("sess_auth_ccexp");
$sess_auth_cccomm = $HTTP_POST_VARS['auth_cccomm'];
bx_session_register("sess_auth_cccomm");
if(CC_AVS == "yes") {
	$sess_auth_ccstreet = $HTTP_POST_VARS['auth_ccstreet'];
	bx_session_register("sess_auth_ccstreet");
	$sess_auth_cccity = $HTTP_POST_VARS['auth_cccity'];
	bx_session_register("sess_auth_cccity");
	$sess_auth_ccstate = $HTTP_POST_VARS['auth_ccstate'];
	bx_session_register("sess_auth_ccstate");
   	$sess_auth_cczip = $HTTP_POST_VARS['auth_cczip'];
	bx_session_register("sess_auth_cczip");
	$sess_auth_cccountry = $HTTP_POST_VARS['auth_cccountry'];
	bx_session_register("sess_auth_cccountry");
	$sess_auth_ccphone = $HTTP_POST_VARS['auth_ccphone'];
	bx_session_register("sess_auth_ccphone");
	$sess_auth_ccemail = $HTTP_POST_VARS['auth_ccemail'];
	bx_session_register("sess_auth_ccemail");
}
?>
<input type="hidden" name="payment_mode" value="<?php echo $HTTP_POST_VARS['payment_mode'];?>">
<table bgcolor="<?php echo TABLE_BGCOLOR;?>" width="100%" border="0" cellspacing="1" cellpadding="2">
   <TR bgcolor="#FFFFFF">
            <TD width="100%" align="center" class="headertdjob"><?php echo TEXT_BILLING_INFORMATION;?></TD>
   </TR>
   <TR><TD><table cellpadding="0" cellspacing="0" border="0" width="100%">
                <tr>
                    <td bgcolor="<?php echo TABLE_HEADING_BGCOLOR;?>" height="1"><?php echo bx_image_width(HTTP_IMAGES.$language."/pix-t.gif",1,1,0,"");?></td>
                </tr>
       </table></TD>
    </TR>
	<TR>
			<TD style="padding-left: 20px;" align="center"><font face="<?php echo TEXT_FONT_FACE;?>" size="<?php echo TEXT_FONT_SIZE;?>" color="<?php echo TEXT_FONT_COLOR;?>"><?php echo TEXT_BILLING_APROVE;?></FONT></TD>
	</TR>
	<TR>
			<TD style="padding-left: 20px;"><font face="<?php echo TEXT_FONT_FACE;?>" size="<?php echo TEXT_FONT_SIZE;?>" color="<?php echo TEXT_FONT_COLOR;?>">&nbsp;</FONT></TD>
	</TR>
</TABLE>
<table bgcolor="<?php echo LIST_BORDER_COLOR;?>" width="70%" border="0" cellspacing="0" cellpadding="0" align="center"><tr><td>
  <table bgcolor="<?php echo LIST_BORDER_COLOR;?>" width="100%" border="0" cellspacing="1" cellpadding="3">
   <TR bgcolor="#FFFFFF">
      <TD align="right">
        <font face="<?php echo TEXT_FONT_FACE;?>" size="<?php echo TEXT_FONT_SIZE;?>" color="<?php echo TEXT_FONT_COLOR;?>"><B><?php echo TEXT_CC_NAME;?>:</B></font>
	  </TD>
	  <TD><font face="<?php echo REQUIRED_TEXT_FONT_FACE;?>" size="<?php echo REQUIRED_TEXT_FONT_SIZE;?>" color="<?php echo REQUIRED_TEXT_FONT_COLOR;?>"><B> <?php echo $HTTP_POST_VARS['auth_ccname'];?></B></FONT>
      </TD>
   </TR>
   <TR bgcolor="#FFFFFF">
      <TD align="right">
        <font face="<?php echo TEXT_FONT_FACE;?>" size="<?php echo TEXT_FONT_SIZE;?>" color="<?php echo TEXT_FONT_COLOR;?>"><B><?php echo TEXT_CC_TYPE;?>:</B></font>
	  </TD>
	  <TD><font face="<?php echo REQUIRED_TEXT_FONT_FACE;?>" size="<?php echo REQUIRED_TEXT_FONT_SIZE;?>" color="<?php echo REQUIRED_TEXT_FONT_COLOR;?>"><B> <?php echo $HTTP_POST_VARS['auth_cctype'];?></B></FONT>
      </TD>
   </TR>
   <TR bgcolor="#FFFFFF">
      <TD align="right">
        <font face="<?php echo TEXT_FONT_FACE;?>" size="<?php echo TEXT_FONT_SIZE;?>" color="<?php echo TEXT_FONT_COLOR;?>"><B><?php echo TEXT_CC_NUM;?>:</B></font>
	  </TD>
	  <TD><font face="<?php echo REQUIRED_TEXT_FONT_FACE;?>" size="<?php echo REQUIRED_TEXT_FONT_SIZE;?>" color="<?php echo REQUIRED_TEXT_FONT_COLOR;?>"><B> <?php echo $HTTP_POST_VARS['auth_ccnum'];?></B></FONT>
      </TD>
   </TR>
   <TR bgcolor="#FFFFFF">
      <TD align="right">
        <font face="<?php echo TEXT_FONT_FACE;?>" size="<?php echo TEXT_FONT_SIZE;?>" color="<?php echo TEXT_FONT_COLOR;?>"><B><?php echo TEXT_CC_VERIFICATION_CODE;?>:</B></font>
	  </TD>
	  <TD><font face="<?php echo REQUIRED_TEXT_FONT_FACE;?>" size="<?php echo REQUIRED_TEXT_FONT_SIZE;?>" color="<?php echo REQUIRED_TEXT_FONT_COLOR;?>"><B> <?php echo ($HTTP_POST_VARS['auth_ccvcode'])?$HTTP_POST_VARS['auth_ccvcode']:"N/A";?></B></FONT>
      </TD>
   </TR>
   <TR bgcolor="#FFFFFF">
      <TD align="right">
        <font face="<?php echo TEXT_FONT_FACE;?>" size="<?php echo TEXT_FONT_SIZE;?>" color="<?php echo TEXT_FONT_COLOR;?>"><B><?php echo TEXT_CC_EXPIRE;?>:</B></font>
	  </TD>
	  <TD><font face="<?php echo REQUIRED_TEXT_FONT_FACE;?>" size="<?php echo REQUIRED_TEXT_FONT_SIZE;?>" color="<?php echo REQUIRED_TEXT_FONT_COLOR;?>"><B> <?php echo $HTTP_POST_VARS['auth_expmonth']."/".$HTTP_POST_VARS['auth_expyear'];?></B></FONT>
      </TD>
   </TR>
   <?php if(CC_AVS == "yes") {?>
   <TR bgcolor="#FFFFFF"><TD colspan="2">&nbsp;</TD></TR> 
   <TR bgcolor="#FFFFFF">
      <TD align="right">
        <font face="<?php echo TEXT_FONT_FACE;?>" size="<?php echo TEXT_FONT_SIZE;?>" color="<?php echo TEXT_FONT_COLOR;?>"><B><?php echo TEXT_CC_STREET;?>:</B></font>
	  </TD>
	  <TD><font face="<?php echo REQUIRED_TEXT_FONT_FACE;?>" size="<?php echo REQUIRED_TEXT_FONT_SIZE;?>" color="<?php echo REQUIRED_TEXT_FONT_COLOR;?>"><B> <?php echo nl2br($HTTP_POST_VARS['auth_ccstreet']);?></B></FONT>
      </TD>
   </TR>
   <TR bgcolor="#FFFFFF">
      <TD align="right">
        <font face="<?php echo TEXT_FONT_FACE;?>" size="<?php echo TEXT_FONT_SIZE;?>" color="<?php echo TEXT_FONT_COLOR;?>"><B><?php echo TEXT_CC_CITY;?>:</B></font>
	  </TD>
	  <TD><font face="<?php echo REQUIRED_TEXT_FONT_FACE;?>" size="<?php echo REQUIRED_TEXT_FONT_SIZE;?>" color="<?php echo REQUIRED_TEXT_FONT_COLOR;?>"><B> <?php echo $HTTP_POST_VARS['auth_cccity'];?></B></FONT>
      </TD>
   </TR>
   <TR bgcolor="#FFFFFF">
      <TD align="right">
        <font face="<?php echo TEXT_FONT_FACE;?>" size="<?php echo TEXT_FONT_SIZE;?>" color="<?php echo TEXT_FONT_COLOR;?>"><B><?php echo TEXT_CC_STATE;?>:</B></font>
	  </TD>
	  <TD><font face="<?php echo REQUIRED_TEXT_FONT_FACE;?>" size="<?php echo REQUIRED_TEXT_FONT_SIZE;?>" color="<?php echo REQUIRED_TEXT_FONT_COLOR;?>"><B> <?php echo $HTTP_POST_VARS['auth_ccstate'];?></B></FONT>
      </TD>
   </TR>
   <TR bgcolor="#FFFFFF">
      <TD align="right">
        <font face="<?php echo TEXT_FONT_FACE;?>" size="<?php echo TEXT_FONT_SIZE;?>" color="<?php echo TEXT_FONT_COLOR;?>"><B><?php echo TEXT_CC_ZIP;?>:</B></font>
	  </TD>
	  <TD><font face="<?php echo REQUIRED_TEXT_FONT_FACE;?>" size="<?php echo REQUIRED_TEXT_FONT_SIZE;?>" color="<?php echo REQUIRED_TEXT_FONT_COLOR;?>"><B> <?php echo $HTTP_POST_VARS['auth_cczip'];?></B></FONT>
      </TD>
   </TR>
   <TR bgcolor="#FFFFFF">
      <TD align="right">
        <font face="<?php echo TEXT_FONT_FACE;?>" size="<?php echo TEXT_FONT_SIZE;?>" color="<?php echo TEXT_FONT_COLOR;?>"><B><?php echo TEXT_CC_COUNTRY;?>:</B></font>
	  </TD>
	  <TD><font face="<?php echo REQUIRED_TEXT_FONT_FACE;?>" size="<?php echo REQUIRED_TEXT_FONT_SIZE;?>" color="<?php echo REQUIRED_TEXT_FONT_COLOR;?>"><B> <?php echo $HTTP_POST_VARS['auth_cccountry'];?></B></FONT>
      </TD>
   </TR>
   <TR bgcolor="#FFFFFF">
      <TD align="right">
        <font face="<?php echo TEXT_FONT_FACE;?>" size="<?php echo TEXT_FONT_SIZE;?>" color="<?php echo TEXT_FONT_COLOR;?>"><B><?php echo TEXT_CC_PHONE;?>:</B></font>
	  </TD>
	  <TD><font face="<?php echo REQUIRED_TEXT_FONT_FACE;?>" size="<?php echo REQUIRED_TEXT_FONT_SIZE;?>" color="<?php echo REQUIRED_TEXT_FONT_COLOR;?>"><B> <?php echo $HTTP_POST_VARS['auth_ccphone'];?></B></FONT>
      </TD>
   </TR>
   <TR bgcolor="#FFFFFF">
      <TD align="right">
        <font face="<?php echo TEXT_FONT_FACE;?>" size="<?php echo TEXT_FONT_SIZE;?>" color="<?php echo TEXT_FONT_COLOR;?>"><B><?php echo TEXT_CC_EMAIL;?>:</B></font>
	  </TD>
	  <TD><font face="<?php echo REQUIRED_TEXT_FONT_FACE;?>" size="<?php echo REQUIRED_TEXT_FONT_SIZE;?>" color="<?php echo REQUIRED_TEXT_FONT_COLOR;?>"><B> <?php echo $HTTP_POST_VARS['auth_ccemail'];?></B></FONT>
      </TD>
    </TR>
    <?php }?>
    <TR bgcolor="#FFFFFF"><TD colspan="2">&nbsp;</TD></TR> 
	<TR bgcolor="#FFFFFF">
      <TD align="right" valign="top">
        <font face="<?php echo TEXT_FONT_FACE;?>" size="<?php echo TEXT_FONT_SIZE;?>" color="<?php echo TEXT_FONT_COLOR;?>"><B><?php echo TEXT_CC_COMMENTS;?>:</B></font>
	  </TD>
	  <TD><font face="<?php echo REQUIRED_TEXT_FONT_FACE;?>" size="<?php echo REQUIRED_TEXT_FONT_SIZE;?>" color="<?php echo REQUIRED_TEXT_FONT_COLOR;?>"><B> <?php echo nl2br(stripslashes($HTTP_POST_VARS['auth_cccomm']));?></B></FONT>
      </TD>
   </TR>
   <TR bgcolor="#FFFFFF">
      <TD align="right">
        <font face="<?php echo TEXT_FONT_FACE;?>" size="<?php echo TEXT_FONT_SIZE;?>" color="<?php echo TEXT_FONT_COLOR;?>"><B><?php echo TEXT_PAYMENT_DESCRIPTION;?>:</B></font>
	  </TD>

	  <TD><font face="<?php echo REQUIRED_TEXT_FONT_FACE?>" size="<?php echo REQUIRED_TEXT_FONT_SIZE?>" color="<?php echo REQUIRED_TEXT_FONT_COLOR?>"><B> <?php echo TEXT_BANNER_PURCHASE.' '.$invoice_result['typename'.$slng].'<br>'.TEXT_BANNER_PURCHASE_NR.' '.(($invoice_result['i_purchased_nr']=='-1')?TEXT_FLAT.', '.$invoice_result['i_period'].TEXT_MONTH:$invoice_result['i_purchased_nr'])?></B></FONT>
	  </TD>
    </TR>
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
	  <TD><font face="<?php echo REQUIRED_TEXT_FONT_FACE;?>" size="<?php echo REQUIRED_TEXT_FONT_SIZE;?>" color="<?php echo REQUIRED_TEXT_FONT_COLOR;?>"><B> <?php echo bx_format_price($invoice_result['listprice'],$invoice_result['icurrency']);?></B></FONT>
      </TD>
    </TR>
    <?php if(USE_VAT == "yes") {?>
		<TR bgcolor="#FFEEEE">
		  <TD align="right">
			<font face="<?php echo TEXT_FONT_FACE;?>" size="<?php echo TEXT_FONT_SIZE;?>" color="<?php echo TEXT_FONT_COLOR;?>"><B><?php echo TEXT_PAYMENT_VAT;?>:</B></font>
		  </TD>
		  <TD><font face="<?php echo REQUIRED_TEXT_FONT_FACE;?>" size="<?php echo REQUIRED_TEXT_FONT_SIZE;?>" color="<?php echo REQUIRED_TEXT_FONT_COLOR;?>"><B> <?php echo bx_format_price((($invoice_result['listprice']-($invoice_result['listprice'])*$invoice_result['vat']/100),$invoice_result['currency'])." (".$invoice_result['vat']." %)";?></B></FONT>
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
  </tr>
  </table>
  <br>
  <table width="60%" bgcolor="#FFFFFF" cellspacing="0" cellpadding="0">
	  <TR bgcolor="#FFFFFF">
			<TD align="right"><font face="<?php echo TEXT_FONT_FACE;?>" size="<?php echo TEXT_FONT_SIZE;?>" color="<?php echo TEXT_FONT_COLOR;?>"><input type="submit" name="proceed" value="<?php echo TEXT_PROCEED;?>"></FONT></TD>
     </TR>
  </table>
</FORM>
<?php
  }
}
else {
	include(DIR_FORMS.FILENAME_CC_BILLING_FORM);
}
?>
<?php include(DIR_SERVER_ROOT."footer.php");?>