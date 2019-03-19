<?php
include ('admin_design.php');
include ('../application_config_file.php');
include ('admin_auth.php');
include("header.php");
$HTTP_POST_VARS['type']='invoice_pricing';
$HTTP_POST_VARS['pricingid']='all';
$HTTP_POST_VARS['search']='Search';
//postvars($HTTP_POST_VARS, "<b>\$HTTP_POST_VARS[ ]</b>");

if($HTTP_POST_VARS['type']) {
    $type=$HTTP_POST_VARS['type'];
}
elseif ($HTTP_GET_VARS['type']){
     $type = $HTTP_GET_VARS['type'];
}
else {
    $type = '';
}
if ($type!="")
      {
        if ($type=="invoice_pricing")
          {
             if ($HTTP_GET_VARS['pricingid']=="all" || $HTTP_POST_VARS['pricingid']=="all")  
             {
             $company_query=bx_db_query("select * from ".$bx_table_prefix."_invoices, $bx_db_table_banner_users as users where users.user_id=".$bx_table_prefix."_invoices.compid order by ".$bx_table_prefix."_invoices.opid desc");
             SQL_CHECK(0,"SQL Error at ".__FILE__.":".(__LINE__-1));
             }//end if ($HTTP_POST_VARS['pricingid']=="000")
             else
             {
             $company_query=bx_db_query("select *,".$bx_table_prefix."_invoices.discount as idiscount from ".$bx_table_prefix."_invoices,".$bx_table_prefix."_pricing_".$bx_table_lng.",".$bx_table_prefix."_companies where ".$bx_table_prefix."_pricing_".$bx_table_lng.".pricing_id=".$bx_table_prefix."_invoices.pricing_type and ".$bx_table_prefix."_pricing_".$bx_table_lng.".pricing_id='".($HTTP_GET_VARS['pricingid']?$HTTP_GET_VARS['pricingid']:$HTTP_POST_VARS['pricingid'])."' and ".$bx_table_prefix."_companies.compid=".$bx_table_prefix."_invoices.compid order by ".$bx_table_prefix."_invoices.opid desc");
             SQL_CHECK(0,"SQL Error at ".__FILE__.":".(__LINE__-1));
             }//end else if ($HTTP_POST_VARS['pricingid']=="000")
        }//end elseif ($HTTP_POST_VARS['type']=="invoice_pricing")

        include(DIR_ADMIN.FILENAME_ADMIN_SEARCH_RESULT_FORM);
    }//end if ($HTTP_POST_VARS['type']!="")
    else
    {
          include(DIR_ADMIN.FILENAME_ADMIN_SEARCH_FORM);
    }//end else if ($HTTP_POST_VARS['type']!="")
include("footer.php");
?>