<?php
$fields = array (
		"opid" => array("%%invoice_id%%" , "Invoice ID", "12"),
		"listprice" => array("%%invoice_listprice%%" , "Invoice List Price", bx_format_price("100",PRICE_CURENCY)),
        "vat" => array("%%invoice_vat%%" , "Invoice VAT Value", bx_format_price("10.11",PRICE_CURENCY)),
		"totalprice" => array("%%invoice_totalprice%%" , "Invoice Total Price", bx_format_price("90",PRICE_CURENCY)),
		"invoice_paymentdate" => array("%%invoice_paymentdate%%" , "Payment Date", bx_format_date(date('Y-m-d'), DATE_FORMAT)),
		"company" => array("%%employer_company%%" , "Employer Company Name", "Test Company"),
        "order_number" => array("%%authorize_order_number%%" , "Authorize Order Number", "12323234"),
        "order_status" => array("%%authorize_order_status%%" , "Authorize Order Status", "This transaction has been approved"),		
        "auth_address" => array("%%cc_address%%" , "Buyer Address", "Specify an address"),								
        "auth_city" => array("%%cc_city%%" , "Buyer City", "City name"),										
        "auth_state" => array("%%cc_state%%" , "Buyer State", "State name"),												
        "auth_zip" => array("%%cc_zip%%" , "Buyer Zip/Postal Code", "USA"),														
        "auth_country" => array("%%cc_country%%" , "Buyer Country", "112233"),																
        "auth_phone" => array("%%cc_phone%%" , "Buyer Phone", "112233"),																		
        "auth_email" => array("%%cc_email%%" , "Buyer Email Address", "mycompany@whatever.com"),
        "ip_address" => array("%%ip_address%%" , "Buyer IP Address", "211.231.12.12")
);
$file_mail_subject = "Invoice Information";
$html_mail = "no";
$mail_type = "1";
$add_html_header = "on";
$add_html_footer = "on";
$add_mail_signature = "on";
?>