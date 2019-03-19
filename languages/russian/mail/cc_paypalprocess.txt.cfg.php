<?php
$fields = array (
		"opid" => array("%%invoice_id%%" , "Invoice ID", "12"),
		"listprice" => array("%%invoice_listprice%%" , "Invoice List Price", bx_format_price("100",PRICE_CURENCY)),
		"invoice_discount" => array("%%invoice_discount%%" , "Invoice Discount", bx_format_price("10",PRICE_CURENCY)),
        "vat" => array("%%invoice_vat%%" , "Invoice VAT Value", bx_format_price("10.11",PRICE_CURENCY)),
		"totalprice" => array("%%invoice_totalprice%%" , "Invoice Total Price", bx_format_price("90",PRICE_CURENCY)),
		"invoice_paymentdate" => array("%%invoice_paymentdate%%" , "Payment Date", bx_format_date(date('Y-m-d'), DATE_FORMAT)),
		"company" => array("%%employer_company%%" , "Employer Company Name", "Test Company"),
        "order_number" => array("%%paypal_invoice_number%%" , "Paypal Invoice Number", "12323234"),
        "order_status" => array("%%paypal_order_status%%" , "Paypal Order Status", "Completed"),		
        "order_method" => array("%%paypal_order_method%%" , "Paypal Order Method", "echeck, instant"),		
        "ip_address" => array("%%ip_address%%" , "Buyer IP Address", "211.231.12.12")
);
$file_mail_subject = "Invoice Information";
$html_mail = "no";
$mail_type = "1";
$add_html_header = "on";
$add_html_footer = "on";
$add_mail_signature = "on";
?>