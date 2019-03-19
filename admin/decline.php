<?php
include ('admin_design.php');
include ('../application_config_file.php');
include ('admin_auth.php');
include("header.php");
    if ($HTTP_POST_VARS['action'] == "upgrades") {
        $invoice_query = bx_db_query("SELECT *,".$bx_table_prefix."_invoices.currency as icurrency, ".$bx_table_prefix."_invoices.discount as invoice_discount from ".$bx_table_prefix."_companies, ".$bx_table_prefix."_invoices where ".$bx_table_prefix."_invoices.opid = '".$HTTP_POST_VARS['opid']."' and  ".$bx_table_prefix."_companies.compid = ".$bx_table_prefix."_invoices.compid");
        SQL_CHECK(0,"SQL Error at ".__FILE__.":".(__LINE__-1));
        $invoice_result = bx_db_fetch_array($invoice_query);
        $mailfile = $language."/mail/upgrade_admindeclined.txt";
    }
    elseif($HTTP_POST_VARS['action']=="buyers") {
        $invoice_query = bx_db_query("SELECT *,".$bx_table_prefix."_invoices.currency as icurrency from ".$bx_db_table_banner_users.", ".$bx_table_prefix."_invoices where ".$bx_table_prefix."_invoices.opid = '".$HTTP_POST_VARS['opid']."' and  ".$bx_db_table_banner_users.".user_id = ".$bx_table_prefix."_invoices.compid");
		SQL_CHECK(0,"SQL Error at ".__FILE__.":".(__LINE__-1));
        $invoice_result = bx_db_fetch_array($invoice_query);
        $mailfile = $language."/mail/buyer_admindeclined.txt";
    }

    include(DIR_LANGUAGES.$mailfile.".cfg.php");
    if($mail_type=="1" && ALLOW_HTML_MAIL=="yes" && $invoice_result['cmail_type'] == "html") {
           $mailfile .= ".html";
           $html_mail = "yes";
    }
    $mail_message = fread(fopen(DIR_LANGUAGES.$mailfile,"r"),filesize(DIR_LANGUAGES.$mailfile));

    $invoice_result['listprice'] = bx_format_price($invoice_result['totalprice'],$invoice_result['icurrency']);
    $invoice_result['vat'] = bx_format_price((($invoice_result['totalprice'])*$invoice_result['vat']/100),$invoice_result['currency'])." (".$invoice_result['vat']." %)";
    $invoice_result['totalprice'] = bx_format_price($invoice_result['totalprice'],$invoice_result['icurrency']);
    $invoice_result['invoice_paymentdate'] = bx_format_date(date('Y-m-d'), DATE_FORMAT);
    if ($html_mail == "no") {
         $invoice_result['payment_information'] = eregi_replace("<br>","\n",eregi_replace("&nbsp;"," ",eregi_replace("<ul>|<li>|</ul>|</li>|<p>|</p>","",PAYMENT_INFORMATION)));
         $invoice_result['company_information'] = eregi_replace("<br>","\n",eregi_replace("&nbsp;"," ",eregi_replace("<ul>|<li>|</ul>|</li>|<p>|</p>","",COMPANY_INFORMATION)));
    }
    else {
         $invoice_result['payment_information'] = PAYMENT_INFORMATION;
         $invoice_result['company_information'] = COMPANY_INFORMATION;
    }
    $invoice_result['description'] = $HTTP_POST_VARS['description'];
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
    bx_db_query("DELETE FROM ".$bx_table_prefix."_invoices where opid='".$HTTP_POST_VARS['opid']."'");
    SQL_CHECK(0,"SQL Error at ".__FILE__.":".(__LINE__-1));
    $success_message = "Decline message was sent to ".$invoice_result['username'].". <br>Invoice was deleted.";
    include("success_form.php");
include("footer.php");
?>