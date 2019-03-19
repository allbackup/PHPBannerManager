<?php
include ('admin_design.php');
include ('../application_config_file.php');
include ('admin_auth.php');
if ($HTTP_GET_VARS['printit']=="yes") {
    echo "<html><head><title>Invoice details:".$HTTP_GET_VARS['opid']."</title>";
    echo "<SCRIPT Language=\"Javascript\">\n";
    echo "function printit(){  \n";
    echo "var navver = parseInt(navigator.appVersion);\n";
    echo "if (navver > 3) {\n";
    echo "   if (window.print) {\n";
    echo "        parent.pmain.focus();\n";
    echo "        window.print() ;\n";  
    echo "    }\n";
    echo "}\n";
    echo "return false;\n";
    echo "}\n";
    echo "</script>\n";
    echo "</head><body>\n";
}
else {
    include("header.php");
}
if (($HTTP_GET_VARS['action']=="buyers") and ($HTTP_GET_VARS['compid'])) {

	  $actual_invoice_query=bx_db_query("select * from ".$bx_table_prefix."_invoices, $bx_db_table_banner_types where ".$bx_table_prefix."_invoices.compid='".$HTTP_GET_VARS['compid']."' and ".$bx_table_prefix."_invoices.opid='".$HTTP_GET_VARS['opid']."' and i_zone=type_id");
      SQL_CHECK(0,"SQL Error at ".__FILE__.":".(__LINE__-1));
      $actual_invoice_result=bx_db_fetch_array($actual_invoice_query);
      $cc_transaction_query = bx_db_query("select * from ".$bx_table_prefix."_cctransactions where opid = '".$HTTP_GET_VARS['opid']."'");

      if(bx_db_num_rows($cc_transaction_query) > 0) {
          $cc_transaction = true;
          $cc_transaction_result = bx_db_fetch_array($cc_transaction_query);
      }
      else {
          $cc_transaction = false;
      }
      include(DIR_ADMIN."details_purchase_form.php");
}//end elseif (($HTTP_GET_VARS['action']=="buyers") and ($HTTP_GET_VARS['compid'])) {
elseif (($HTTP_GET_VARS['action']=="details") and ($HTTP_GET_VARS['opid']))
 {
  $invoice_query=bx_db_query("select *,".$bx_table_prefix."_invoices.currency as icurrency from ".$bx_table_prefix."_invoices where ".$bx_table_prefix."_invoices.opid='".$HTTP_GET_VARS['opid']."'");
  SQL_CHECK(0,"SQL Error at ".__FILE__.":".(__LINE__-1));
  $invoice_result=bx_db_fetch_array($invoice_query);
  		$banner_invoices_SQL = "select * from $bx_db_table_banner_invoices,$bx_db_table_banner_types where opid='".$invoice_result['opid']."' and i_zone=type_id";
   		$banner_invoice_query = bx_db_query($banner_invoices_SQL);
   		SQL_CHECK(0,"SQL Error at ".__FILE__.":".(__LINE__-1));
		$banner_invoice_result = bx_db_fetch_array($banner_invoice_query);
  include(DIR_ADMIN."details_invoice_form.php");
}//end elseif (($HTTP_GET_VARS['action']=="details") and ($HTTP_GET_VARS['opid']))

else {
}
if ($HTTP_GET_VARS['printit']=="yes") {
    echo "</body></html>";
    bx_exit();
}
else {
    include("footer.php");
}
?>