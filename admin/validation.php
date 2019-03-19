<?php
include ('admin_design.php');
include ('../application_config_file.php');
include ('admin_auth.php');
if (!get_magic_quotes_gpc()) {
    while (list($header, $value) = each($HTTP_POST_VARS)) {
         $HTTP_POST_VARS[$header] = addslashes($HTTP_POST_VARS[$header]);
     }
}
include(DIR_LANGUAGES.$language."/".FILENAME_ADMIN_FORM);
if (($HTTP_POST_VARS['action']=="buyers") && ($HTTP_POST_VARS['compid']))
   {
	$update_sql = "update ".$bx_table_prefix."_invoices set payment_mode='".$HTTP_POST_VARS['payment_mode']."',description='".$HTTP_POST_VARS['description']."',payment_date='".$HTTP_POST_VARS['pdate_added']."',paid='Y',validated='Y' where opid='".$HTTP_POST_VARS['opid']."'";
   bx_db_query($update_sql);

   SQL_CHECK(0,"SQL Error at ".__FILE__.":".(__LINE__-1));
   echo $invoice_sql = "SELECT *,".$bx_table_prefix."_invoices.currency as icurrency,".$bx_table_prefix."_invoices.description as description from ".$bx_table_prefix."_invoices,".$bx_db_table_banner_users."  where ".$bx_table_prefix."_invoices.opid='".$HTTP_POST_VARS['opid']."' and user_id=compid";
   $invoice_query=bx_db_query($invoice_sql);
   SQL_CHECK(0,"SQL Error at ".__FILE__.":".(__LINE__-1));
   $invoice_result=bx_db_fetch_array($invoice_query);
   $mailfile = $language."/mail/buyer_adminapproved.txt";
   if($mail_type=="1" && ALLOW_HTML_MAIL=="yes" && $invoice_result['cmail_type'] == "html") {
         $mailfile .= ".html";
         $html_mail = "yes"; 
   }
   include(DIR_LANGUAGES.$mailfile.".cfg.php");
   $mail_message = fread(fopen(DIR_LANGUAGES.$mailfile,"r"),filesize(DIR_LANGUAGES.$mailfile));
   $invoice_result['vat'] = bx_format_price((($invoice_result['totalprice'])*$invoice_result['vat']/100),$invoice_result['currency'])." (".$invoice_result['vat']." %)";
   $invoice_result['totalprice'] = bx_format_price($invoice_result['totalprice'],$invoice_result['icurrency']);
   $invoice_result['listprice'] = bx_format_price($invoice_result['totalprice'],$invoice_result['icurrency']);
   $invoice_result['invoice_paymentdate'] = bx_format_date($invoice_result['payment_date'], DATE_FORMAT);
   if ($html_mail == "no") {
        $invoice_result['payment_information'] = eregi_replace("<br>","\n",eregi_replace("&nbsp;"," ",eregi_replace("<ul>|<li>|</ul>|</li>|<p>|</p>","",PAYMENT_INFORMATION)));
        $invoice_result['company_information'] = eregi_replace("<br>","\n",eregi_replace("&nbsp;"," ",eregi_replace("<ul>|<li>|</ul>|</li>|<p>|</p>","",COMPANY_INFORMATION)));
   }
   else {
        $invoice_result['payment_information'] = PAYMENT_INFORMATION;
        $invoice_result['company_information'] = COMPANY_INFORMATION;
   }
   if($invoice_result['payment_mode'] == 1) {
          $trans_query = bx_db_query("SELECT * from ".$bx_table_prefix."_cctransactions where opid = '".$invoice_result['opid']."'");
          SQL_CHECK(0,"SQL Error at ".__FILE__.":".(__LINE__-1));
          if(bx_db_num_rows($trans_query)>0) {
                 $cc_transaction_result = bx_db_fetch_array($trans_query);
                 include(DIR_FUNCTIONS."cclib/class.rc4crypt.php");
                 include(DIR_SERVER_ROOT."cc_payment_settings.php");   
                 $decoder=new rc4crypt();   
                 $invoice_result['auth_name'] = $decoder->endecrypt(CRYPT_PHRASE,$cc_transaction_result['cc_name'],"de");
                 $invoice_result['auth_type'] =$decoder->endecrypt(CRYPT_PHRASE,$cc_transaction_result['cc_type'],"de");	
                 $invoice_result['auth_ccnum'] = eregi_replace('([0-9])','x', $decoder->endecrypt(CRYPT_PHRASE,$cc_transaction_result['cc_num'],"de"));
                 $invoice_result['auth_ccvcode'] = $decoder->endecrypt(CRYPT_PHRASE,$cc_transaction_result['cc_cvc'],"de");
                 $invoice_result['auth_exp'] = $decoder->endecrypt(CRYPT_PHRASE,$cc_transaction_result['cc_exp'],"de");
                 $invoice_result['auth_comm'] = $invoice_result['info'];
                 if(CC_AVS == "yes") {
                        $invoice_result['auth_street'] = $decoder->endecrypt(CRYPT_PHRASE,$cc_transaction_result['cc_street'],"de");	
                        $invoice_result['auth_city'] = $decoder->endecrypt(CRYPT_PHRASE,$cc_transaction_result['cc_city'],"de");			
                        $invoice_result['auth_state'] = $decoder->endecrypt(CRYPT_PHRASE,$cc_transaction_result['cc_state'],"de");					
                        $invoice_result['auth_zip'] = $decoder->endecrypt(CRYPT_PHRASE,$cc_transaction_result['cc_zip'],"de");							
                        $invoice_result['auth_country'] = $decoder->endecrypt(CRYPT_PHRASE,$cc_transaction_result['cc_country'],"de");							
                        $invoice_result['auth_phone'] = $decoder->endecrypt(CRYPT_PHRASE,$cc_transaction_result['cc_phone'],"de");											
                        $invoice_result['auth_email'] = $decoder->endecrypt(CRYPT_PHRASE,$cc_transaction_result['cc_email'],"de");													
                 }
          }
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
   bx_mail(SITE_NAME,SITE_MAIL,$invoice_result['username'], stripslashes($file_mail_subject), stripslashes($mail_message), $html_mail);
   header("Location: ".HTTP_SERVER_ADMIN.FILENAME_ADMIN."?action=".$HTTP_POST_VARS['action']);
   bx_exit();
   }//end if action=buyers and compid

if (($HTTP_POST_VARS['action']=="details") && ($HTTP_POST_VARS['opid']))
   {
   if ($HTTP_POST_VARS['btnDelete']!="")
   {
    bx_db_query("insert into del".$bx_table_prefix."_invoices select * from ".$bx_table_prefix."_invoices where opid='".$HTTP_POST_VARS['opid']."'");
    SQL_CHECK(0,"SQL Error at ".__FILE__.":".(__LINE__-1));
    bx_db_query("delete from ".$bx_table_prefix."_invoices where opid=\"".$HTTP_POST_VARS['opid']."\"");
    SQL_CHECK(0,"SQL Error at ".__FILE__.":".(__LINE__-1));

    bx_db_query("delete from ".$bx_table_prefix."_cctransactions where opid=\"".$HTTP_POST_VARS['opid']."\"");
    SQL_CHECK(0,"SQL Error at ".__FILE__.":".(__LINE__-1));
    header("Location: ".HTTP_SERVER_ADMIN.FILENAME_ADMIN."?action=".$HTTP_POST_VARS['action']);
    bx_exit();
   }//end if ($HTTP_POST_VARS['btnDelete']!="")
   else
   {
   header("Location: ".HTTP_SERVER_ADMIN.FILENAME_ADMIN."?action=".$HTTP_POST_VARS['action']);
   bx_exit();
   }//end else if ($HTTP_POST_VARS['btnDelete']!="")
}//end if action=details and opid

print $HTTP_POST_VARS['action'];
bx_exit();
?>