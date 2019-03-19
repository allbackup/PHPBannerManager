<?php
$fields = array (
                "opid" => array("%%invoice_id%%" , "Invoice ID", "12"),
                "listprice" => array("%%invoice_listprice%%" , "Invoice List Price", bx_format_price("100",PRICE_CURENCY)),
                "vat" => array("%%invoice_vat%%" , "Invoice VAT Value", bx_format_price("10.11",PRICE_CURENCY)),
                "totalprice" => array("%%invoice_totalprice%%" , "Invoice Total Price", bx_format_price("90",PRICE_CURENCY)),
                "invoice_paymentdate" => array("%%invoice_paymentdate%%" , "Payment Date", bx_format_date(date('Y-m-d'), DATE_FORMAT)),
                "description" => array("%%invoice_description%%" , "Payment Description", "Payment description entered by admin."),
                "company_information" => array("%%company_information%%" , "Company Information", eregi_replace("<ul>|<li>|</ul>|</li>|<p>|</p>","",COMPANY_INFORMATION)),
                "payment_information" => array("%%payment_information%%" , "Payment Information", eregi_replace("<ul>|<li>|</ul>|</li>|<p>|</p>","",PAYMENT_INFORMATION)),
                "name" => array("%%employer_company%%" , "Employer Company Name", "Test Company"),
                "username" => array("%%employer_email%%" , "Employer Email Address", "mycompany@whatever.com"),
                "auth_name" => array("%%cc_name%%" , "Buyer CardHolder Name", "Buyer Name"),
                "auth_type" => array("%%cc_type%%" , "Credit Card Type", "Visa"),		
                "auth_ccnum" => array("%%cc_num%%" , "Credit Card Number", "xxxx xxxx xxxx xxxx"),				
                "auth_ccvcode" => array("%%cc_vcode%%" , "Card Verification Number (Visa and Mastercard Only)", "123"),				
                "auth_exp" => array("%%cc_exp%%" , "Expiration Date", "05/2000"),						
                "auth_comm" => array("%%cc_comment%%" , "Buyer Comment", "Some Free Text Here"),						        
                "auth_strreet" => array("%%cc_street%%" , "Buyer Street", "Specify an address"),								
                "auth_city" => array("%%cc_city%%" , "Buyer City", "City name"),										
                "auth_state" => array("%%cc_state%%" , "Buyer State", "State name"),												
                "auth_zip" => array("%%cc_zip%%" , "Buyer Zip/Postal Code", "USA"),														
                "auth_country" => array("%%cc_country%%" , "Buyer Country", "112233"),																
                "auth_phone" => array("%%cc_phone%%" , "Buyer Phone", "112233"),																		
                "auth_email" => array("%%cc_email%%" , "Buyer Email Address", "mycompany@whatever.com")		
);
$file_mail_subject = "Banner purchase approved";
$html_mail = "no";
$mail_type = "1";
$add_html_header = "on";
$add_html_footer = "on";
$add_mail_signature = "on";
?>