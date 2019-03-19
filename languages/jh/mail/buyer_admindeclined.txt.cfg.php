<?php
$fields = array (
                "opid" => array("%%invoice_id%%" , "Invoice ID", "12"),
                "listprice" => array("%%invoice_listprice%%" , "Invoice List Price", bx_format_price("100",PRICE_CURENCY)),
                "vat" => array("%%invoice_vat%%" , "Invoice VAT Value", bx_format_price("10.11",PRICE_CURENCY)),
                "totalprice" => array("%%invoice_totalprice%%" , "Invoice Total Price", bx_format_price("90",PRICE_CURENCY)),
                "invoice_paymentdate" => array("%%invoice_paymentdate%%" , "Decline Date", bx_format_date(date('Y-m-d'), DATE_FORMAT)),
                "description" => array("%%decline_message%%" , "Decline Description", "Decline description entered by the admin."),
                "company_information" => array("%%company_information%%" , "Company Information", eregi_replace("<ul>|<li>|</ul>|</li>|<p>|</p>","",COMPANY_INFORMATION)),
                "payment_information" => array("%%payment_information%%" , "Payment Information", eregi_replace("<ul>|<li>|</ul>|</li>|<p>|</p>","",PAYMENT_INFORMATION)),
                "name" => array("%%name%%" , "Company Name", "Test Company"),
                "email" => array("%%employer_email%%" , "Employer Email Address", "mycompany@whatever.com")
);
$file_mail_subject = "Banner purchase declined";
$html_mail = "no";
$mail_type = "1";
$add_html_header = "on";
$add_html_footer = "on";
$add_mail_signature = "on";
?>