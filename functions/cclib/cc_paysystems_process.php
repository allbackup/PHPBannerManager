<?php include(DIR_SERVER_ROOT."header.php");?>
<?php include(DIR_LANGUAGES.$language."/".FILENAME_CC_BILLING_FORM);?>
<?php include(DIR_LANGUAGES.$language."/".FILENAME_PAYMENT_FORM);?>
<?php include(DIR_LANGUAGES.$language."/".FILENAME_INVOICES_FORM);?>
<?php

/////////*****for local test***********//////////////
//postvars($HTTP_POST_VARS);
//PAYSYSTEMS_COM_ACCOUNT = "181360";
//PAYSYSTEMS_COM_URL = "https://secure.paysystems1.com/cgiv310/payment/onlinesale-tpppro.asp";
//define ("PAYSYSTEMS_COM_URL_TEST",HTTP_SERVER."functions/cclib/paysystems_test.php");
//test account paysystem.com userid:181360 pwd:CKF1014
//URL https://secure.paysystems1.com/cgiv310/payment/onlinesale-tpppro.asp

if($HTTP_GET_VARS['todo'] == "ret") {
    if ($HTTP_POST_VARS['cc_status']=="pass") {
          $transid_query=bx_db_query("select * from ".$bx_table_prefix."_cctransactions where transid='".$HTTP_POST_VARS['orderid']."'");
          SQL_CHECK(0,"SQL Error at ".__FILE__.":".(__LINE__-1));
          if (bx_db_num_rows($transid_query)!=0) {
                    $error_message=TEXT_ALREADY_PAYD_TRANSACTION;
                    $back_url=bx_make_url(HTTP_SERVER.FILENAME_MEMBERSHIP, "auth_sess", $bx_session);
                    include(DIR_FORMS.FILENAME_ERROR_FORM);
          } //end if (bx_db_num_rows($transid_query)!=0)
          else
          {
               bx_db_query("UPDATE ".$bx_table_prefix."_invoices set payment_date='".date('Y-m-d')."', paid='Y',validated='Y', payment_mode='".$HTTP_SESSION_VARS['curr_pmode']."', i_start_date='".date('Y-m-d')."' where ".$bx_table_prefix."_invoices.opid='".$HTTP_POST_VARS['opid']."'");
               SQL_CHECK(0,"SQL Error at ".__FILE__.":".(__LINE__-1));

               bx_db_insert($bx_table_prefix."_cctransactions","transid,opid,cc_name,cc_type,cc_num,cc_exp,cc_street,cc_city,cc_state,cc_zip,cc_country,cc_phone,cc_email","'".$HTTP_POST_VARS['orderid']."','".$HTTP_POST_VARS['opid']."','','','','','','','','','','',''");
               bx_session_unregister('curr_opid');
               bx_session_unregister('curr_pmode');

               if(CC_NOTIFY_BUYER == "yes") {
                   $mailfile = $language."/mail/cc_paysystemsprocess.txt";
                   include(DIR_LANGUAGES.$mailfile.".cfg.php");
                   if($mail_type=="1" && ALLOW_HTML_MAIL=="yes" && $invoice_result['cmail_type'] == "html") {
                           $mailfile .= ".html";
                           $html_mail = "yes";
                   }
                   $mail_message = fread(fopen(DIR_LANGUAGES.$mailfile,"r"),filesize(DIR_LANGUAGES.$mailfile));
                   $invoice_result['listprice'] = bx_format_price($invoice_result['totalprice'],$invoice_result['icurrency']);
                   $invoice_result['inv_vat'] = $invoice_result['vat'];
                   $invoice_result['vat'] = bx_format_price((($invoice_result['totalprice'])*$invoice_result['inv_vat']/100),$invoice_result['currency'])." (".$invoice_result['inv_vat']." %)";
                   $invoice_result['totalprice'] = bx_format_price($invoice_result['totalprice'],$invoice_result['icurrency']);
                   $invoice_result['invoice_paymentdate'] = bx_format_date(date('Y-m-d'), DATE_FORMAT);
                   $invoice_result['auth_address'] = $HTTP_POST_VARS['bill_address'];
                   $invoice_result['auth_city'] = $HTTP_POST_VARS['bill_city'];
                   $invoice_result['auth_state'] = $HTTP_POST_VARS['bill_state'];
                   $invoice_result['auth_zip'] = $HTTP_POST_VARS['bill_zip'];
                   $invoice_result['auth_country'] = $HTTP_POST_VARS['bill_country'];
                   $invoice_result['auth_phone'] = $HTTP_POST_VARS['bill_telephone'];
                   $invoice_result['auth_email'] = $HTTP_POST_VARS['bill_email'];
                   $invoice_result['order_number'] = $HTTP_POST_VARS['orderid'];
                   $invoice_result['order_status'] = $HTTP_POST_VARS['cc_status'];
                   $invoice_result['ip_address'] = $HTTP_SERVER_VARS['REMOTE_ADDR'];
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
                   bx_mail(SITE_NAME,SITE_MAIL,$invoice_result['username'], stripslashes($file_mail_subject), stripslashes($mail_message), $html_mail); 
               }    
               if(CC_NOTIFY_ADMIN == "yes") {
                    $themessage.="          Invoice details (No: ".$invoice_result['opid'].")\n";
                    $themessage.="-----------------------Begin Company information(Sender)---------\n";
                    $themessage.="User name: ".$invoice_result['name']."\n";
                    $themessage.="User email: ".$invoice_result['username']."\n";
                    $themessage.="-----------------------End Company information---------\n\n";
                    $themessage.="-----------------------Begin Payment information---------\n";
                    $themessage.="List Price: ".bx_format_price($invoice_result['totalprice'],$invoice_result['icurrency'])."\n";
                    if(USE_VAT == "yes") {
                            $themessage.="VAT: ".bx_format_price((($invoice_result['totalprice'])*$invoice_result['inv_vat']/100),$invoice_result['currency'])." (".$invoice_result['inv_vat']." %)"."\n";
                    }
                    $themessage.="Total Price: ".bx_format_price($invoice_result['totalprice'],$invoice_result['icurrency'])."\n";
                    $themessage.="Payment date: ".bx_format_date(date('Y-m-d'), DATE_FORMAT)."\n";
                    $themessage.="Paysystems Order Number: ".$HTTP_POST_VARS['orderid']."\n";
                    $themessage.="Paysystems Order Status: ".$HTTP_POST_VARS['cc_status']."\n";
                    $themessage.="-----------------------Begin Payment modalities---------\n";
                    $themessage.="Customer selected payment modality: \n";
                    $themessage.="          ".${TEXT_PAYMENT_OPT.$HTTP_POST_VARS['payment_mode']}."\n";
                    $themessage.="-----------------------End Payment modalities---------\n\n";
					$themessage.="Payment description: ".TEXT_BANNER_PURCHASE.' '.$invoice_result['typename'.$slng].'  '.TEXT_BANNER_PURCHASE_NR.TEXT_FLAT.', '.$invoice_result['i_period'].TEXT_MONTH."\n";

                    $themessage.="Details: ".HTTP_SERVER_ADMIN."admin.php?action=buyers\n";
                    $themessage.="-----------------------End Payment information---------\n";
                    $themessage.=SITE_SIGNATURE."\n";
                    bx_mail(SITE_NAME,SITE_MAIL,SITE_NAME."<".SITE_MAIL.">",TEXT_INVOICE_NUMBER.": ".$invoice_result['opid'],$themessage,"no");
               } 
               $success_message=TEXT_CC_PAYMENT_SUCCESS;
               $back_url=bx_make_url(HTTP_SERVER.FILENAME_MYINVOICES, "auth_sess", $bx_session);
               include(DIR_FORMS.FILENAME_MESSAGE_FORM);
               //include(DIR_FORMS.FILENAME_PROCESSING_FORM);
         }//end else if (bx_db_num_rows($transid_query)!=0)
    }//end if ($HTTP_POST_VARS['x_response_code']=="1")
    else if (($HTTP_POST_VARS['cc_status']!="pass")) {
            $error_message=TEXT_INVALID_CC_PAYMENT;
            $text_reason = $HTTP_POST_VARS['x_response_reason_text'];
            $back_url=bx_make_url(HTTP_SERVER.FILENAME_MYINVOICES, "auth_sess", $bx_session);
            include(DIR_FORMS.FILENAME_ERROR_FORM);
    }
}

elseif ($HTTP_POST_VARS['todo'] == "proceed") {
    $curr_opid = $HTTP_POST_VARS['opid'];
    bx_session_unregister("curr_opid");
    bx_session_register("curr_opid");
    $curr_pmode = $HTTP_POST_VARS['payment_mode'];
    bx_session_unregister("curr_pmode");
    bx_session_register("curr_pmode");
	?>
    <form method="post" action="<?php echo PAYSYSTEMS_COM_URL;?>" name="frmcheckout">
        <input type="hidden" name="formget" value="N">
        <input type="hidden" name="redirect" value="<?php echo bx_make_url(((ENABLE_SSL == "yes") ? HTTPS_SERVER : HTTP_SERVER).FILENAME_PROCESSING."?todo=ret", "auth_sess", $bx_session);?>">
        <input type="hidden" name="companyid" value="<?php echo PAYSYSTEMS_COM_ACCOUNT;?>">
        <input type="hidden" name="total" value="<?php echo $invoice_result['totalprice'];?>">
		<input type="hidden" name="option1" value="<?php echo bx_session_id();?>">
		<input type="hidden" name="option2" value="<?php echo $HTTP_POST_VARS['payment_mode'];?>">
        <table bgcolor="<?php echo TABLE_BGCOLOR;?>" width="100%" border="0" cellspacing="1" cellpadding="2">
           <TR bgcolor="#FFFFFF">
                    <TD width="100%" align="center" class="headertdjob"><?php echo TEXT_PROCESS_PAYMENT;?></TD>
           </TR>
           <TR><TD><table cellpadding="0" cellspacing="0" border="0" width="100%">
                        <tr>
                            <td bgcolor="<?php echo TABLE_HEADING_BGCOLOR;?>" height="1"><?php echo bx_image_width(HTTP_IMAGES.$language."/pix-t.gif",1,1,0,"");?></td>
                        </tr>
               </table></TD>
            </TR>
            <TR>
                    <TD style="padding-left: 20px;" align="center"><font face="<?php echo TEXT_FONT_FACE;?>" size="<?php echo TEXT_FONT_SIZE;?>" color="<?php echo TEXT_FONT_COLOR;?>">
                    <div><span id="pr1"><b><font color="#FF0000"><?php echo TEXT_PROCESSING;?></font></b></span>
<span id="pr2"  STYLE="POSITION: relative;  VISIBILITY: visible; TOP: 0px;  Z-INDEX: 15; LEFT: 0px"><b>..</b>.</span>
</div>
<script language="Javascript">
<!--
var text_status = 0;
var delay=250;
var redirect_delay=3000;
function refresh_text(pobject) {
    if (document.getElementById && document.getElementById(pobject)) {
          if(text_status == 1) {
              document.getElementById(pobject).style.visibility='visible';
              text_status = 0;
          }
          else {
              document.getElementById(pobject).style.visibility='hidden';
              text_status = 1;
          }
    }
    else if (document.layers) {
          if(text_status == 1) {
		    document.layers[pobject].visibility = 'visible';
               text_status = 0;
          }
          else {
              document.layers[pobject].visibility = 'hide';
              text_status = 1;
          }
	}
	else if (document.all && eval(document.all.pobject)) {
          if(text_status == 1) {
		    document.all[pobject].style.visibility = 'visible';
               text_status = 0;
          }
          else {
              document.all[pobject].style.visibility = 'hidden';
              text_status = 1;
          }
    }
    setTimeout("refresh_text(\'pr2\')", delay)
}
function make_redirection() {
    document.frmcheckout.submit();
}
refresh_text('pr2');
setTimeout("make_redirection()", redirect_delay);

//-->
</script>
     </script></FONT></TD>
            </TR>
            <TR>
                    <TD style="padding: 20px;"><font face="<?php echo TEXT_FONT_FACE;?>" size="<?php echo TEXT_FONT_SIZE;?>" color="<?php echo TEXT_FONT_COLOR;?>"><?php echo TEXT_WAIT_NOTIFICATION;?></FONT></TD>
            </TR>
            <TR>
                    <TD style="padding-left: 20px;"><font face="<?php echo TEXT_FONT_FACE;?>" size="<?php echo TEXT_FONT_SIZE;?>" color="<?php echo TEXT_FONT_COLOR;?>">&nbsp;</FONT></TD>
            </TR>
            <TR>
                    <TD style="padding-left: 20px;" align="center"><input type="submit" name="go" value="<?php echo TEXT_GO_THERE;?>"></TD>
            </TR>
        </TABLE>
      </form>  
<?php }
else {
    ?>
    <form method="post" action="<?php echo bx_make_url(((ENABLE_SSL == "yes") ? HTTPS_SERVER : HTTP_SERVER).FILENAME_PROCESSING, "auth_sess", $bx_session);?>">
        <input type="hidden" name="opid" value="<?php echo $HTTP_POST_VARS['opid'];?>">
        <input type="hidden" name="compid" value="<?php if($HTTP_SESSION_VARS['employerid']) {echo $HTTP_SESSION_VARS['employerid'];} else if ($HTTP_POST_VARS['compid']) {echo $HTTP_POST_VARS['compid'];}?>">
        <input type="hidden" name="todo" value="proceed">
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
          <TR bgcolor="#FFFFFF"><TD colspan="2" align="center"><font face="<?php echo TEXT_FONT_FACE;?>" size="<?php echo TEXT_FONT_SIZE;?>" color="<?php echo TEXT_FONT_COLOR;?>"><B><?php echo SITE_TITLE;?></B></TD></TR> 
          <TR bgcolor="#FFFFFF"><TD colspan="2" align="center">&nbsp;</TD></TR> 
          <TR bgcolor="#FFFFFF"><TD colspan="2" align="center"><font face="<?php echo TEXT_FONT_FACE;?>" size="<?php echo TEXT_FONT_SIZE;?>" color="<?php echo TEXT_FONT_COLOR;?>"><B><?php echo TEXT_COMPANY_INFORMATION;?></B></TD></TR> 
           <TR bgcolor="#FFFFFF">
              <TD valign="top" colspan="2" align="center"><font face="<?php echo REQUIRED_TEXT_FONT_FACE;?>" size="<?php echo REQUIRED_TEXT_FONT_SIZE;?>" color="<?php echo REQUIRED_TEXT_FONT_COLOR;?>"><B><?php echo nl2br(COMPANY_INFORMATION);?></B></FONT>
              </TD>
           </TR>
           <TR bgcolor="#FFFFFF"><TD colspan="2" align="center"><font face="<?php echo TEXT_FONT_FACE;?>" size="<?php echo TEXT_FONT_SIZE;?>" color="<?php echo TEXT_FONT_COLOR;?>"><B><?php echo TEXT_EMPLOYER_INFORMATION;?></B></TD></TR> 

          <TR bgcolor="#FFFFFF">
              <TD align="right">
                <font face="<?php echo TEXT_FONT_FACE;?>" size="<?php echo TEXT_FONT_SIZE;?>" color="<?php echo TEXT_FONT_COLOR;?>"><B><?php echo TEXT_COMPANY;?>:</B></font>
              </TD>
              <TD><font face="<?php echo REQUIRED_TEXT_FONT_FACE;?>" size="<?php echo REQUIRED_TEXT_FONT_SIZE;?>" color="<?php echo REQUIRED_TEXT_FONT_COLOR;?>"><B> <?php echo $invoice_result['name'];?></B></FONT>
              </TD>
           </TR>
           <TR bgcolor="#FFFFFF">
              <TD align="right">
                <font face="<?php echo TEXT_FONT_FACE;?>" size="<?php echo TEXT_FONT_SIZE;?>" color="<?php echo TEXT_FONT_COLOR;?>"><B><?php echo TEXT_CC_EMAIL;?>:</B></font>
              </TD>
              <TD><font face="<?php echo REQUIRED_TEXT_FONT_FACE;?>" size="<?php echo REQUIRED_TEXT_FONT_SIZE;?>" color="<?php echo REQUIRED_TEXT_FONT_COLOR;?>"><B> <?php echo $invoice_result['username'];?></B></FONT>
              </TD>
           </TR>
           <TR bgcolor="#FFFFFF"><TD colspan="2">&nbsp;</TD></TR> 
           <TR bgcolor="#FFFFFF">
              <TD align="right" valign="top">
                <font face="<?php echo TEXT_FONT_FACE;?>" size="<?php echo TEXT_FONT_SIZE;?>" color="<?php echo TEXT_FONT_COLOR;?>"><B><?php echo TEXT_IP_ADDRESS;?>:</B></font>
              </TD>
              <TD><font face="<?php echo REQUIRED_TEXT_FONT_FACE;?>" size="<?php echo REQUIRED_TEXT_FONT_SIZE;?>" color="<?php echo REQUIRED_TEXT_FONT_COLOR;?>"><B><?php echo $HTTP_SERVER_VARS['REMOTE_ADDR'];?></B></FONT>
              </TD>
           </TR>
           <TR bgcolor="#FFFFFF">
              <TD align="right">
                <font face="<?php echo TEXT_FONT_FACE?>" size="<?php echo TEXT_FONT_SIZE?>" color="<?php echo TEXT_FONT_COLOR?>"><B><?php echo TEXT_PAYMENT_DESCRIPTION?>:</B></font>
              </TD>

              <TD><font face="<?php echo REQUIRED_TEXT_FONT_FACE?>" size="<?php echo REQUIRED_TEXT_FONT_SIZE?>" color="<?php echo REQUIRED_TEXT_FONT_COLOR?>"><B> <?php echo TEXT_BANNER_PURCHASE.' : '.$invoice_result['typename'.$slng].'<br>'.TEXT_BANNER_PURCHASE_NR.' : '.(($invoice_result['i_purchased_nr']=='-1')?TEXT_FLAT.', '.$invoice_result['i_period'].TEXT_MONTH:$invoice_result['i_purchased_nr'])?></B></FONT>
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
              <TD><font face="<?php echo REQUIRED_TEXT_FONT_FACE;?>" size="<?php echo REQUIRED_TEXT_FONT_SIZE;?>" color="<?php echo REQUIRED_TEXT_FONT_COLOR;?>"><B> <?php echo bx_format_price($invoice_result['totalprice'],$invoice_result['icurrency']);?></B></FONT>
              </TD>
            </TR>
            <?php if(USE_VAT == "yes") {?>

            <TR bgcolor="#FFEEEE">
              <TD align="right">
                <font face="<?php echo TEXT_FONT_FACE;?>" size="<?php echo TEXT_FONT_SIZE;?>" color="<?php echo TEXT_FONT_COLOR;?>"><B><?php echo TEXT_PAYMENT_VAT;?>:</B></font>
              </TD>
              <TD><font face="<?php echo REQUIRED_TEXT_FONT_FACE;?>" size="<?php echo REQUIRED_TEXT_FONT_SIZE;?>" color="<?php echo REQUIRED_TEXT_FONT_COLOR;?>"><B> <?php echo bx_format_price((($invoice_result['totalprice'])*$invoice_result['vat']/100),$invoice_result['currency'])." (".$invoice_result['vat']." %)";?></B></FONT>
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
?>
<?php include(DIR_SERVER_ROOT."footer.php");?>