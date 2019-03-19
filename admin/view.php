<?php
include ('admin_design.php');
include ('../application_config_file.php');
include ('admin_auth.php');
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
if (($HTTP_GET_VARS['action']=="details") and ($HTTP_GET_VARS['jobid'])){
  $job_query=bx_db_query("select * from ".$bx_table_prefix."_jobs where ".$bx_table_prefix."_jobs.jobid='".$HTTP_GET_VARS['jobid']."'");
  SQL_CHECK(0,"SQL Error at ".__FILE__.":".(__LINE__-1));
  $job_result=bx_db_fetch_array($job_query);
  include(DIR_ADMIN."details_job_form.php");
}//end if (($HTTP_GET_VARS['action']=="details") and ($HTTP_GET_VARS['jobid'])){
elseif (($HTTP_GET_VARS['action']=="details") and ($HTTP_GET_VARS['compid'])) {
  $company_query=bx_db_query("select * from ".$bx_table_prefix."_companies, ".$bx_table_prefix."_companycredits where ".$bx_table_prefix."_companies.compid='".$HTTP_GET_VARS['compid']."' and ".$bx_table_prefix."_companycredits.compid = ".$bx_table_prefix."_companies.compid");
  SQL_CHECK(0,"SQL Error at ".__FILE__.":".(__LINE__-1));
  $company_result=bx_db_fetch_array($company_query);
  include(DIR_ADMIN."details_company_form.php");
}
elseif (($HTTP_GET_VARS['action']=="upgrades") and ($HTTP_GET_VARS['compid'])) {
      $actual_query=bx_db_query("select * from ".$bx_table_prefix."_membership,".$bx_table_prefix."_pricing_".$bx_table_lng." where ".$bx_table_prefix."_membership.compid='".$HTTP_GET_VARS['compid']."' and ".$bx_table_prefix."_pricing_".$bx_table_lng.".pricing_id=".$bx_table_prefix."_membership.pricing_id");
      SQL_CHECK(0,"SQL Error at ".__FILE__.":".(__LINE__-1));
      $actual_result=bx_db_fetch_array($actual_query);
      $desired_query=bx_db_query("select * from ".$bx_table_prefix."_pricing_".$bx_table_lng.",".$bx_table_prefix."_invoices where ".$bx_table_prefix."_invoices.compid='".$HTTP_GET_VARS['compid']."' and ".$bx_table_prefix."_invoices.opid = '".$HTTP_GET_VARS['opid']."' and ".$bx_table_prefix."_invoices.op_type='1' and ".$bx_table_prefix."_pricing_".$bx_table_lng.".pricing_id=".$bx_table_prefix."_invoices.pricing_type");
      SQL_CHECK(0,"SQL Error at ".__FILE__.":".(__LINE__-1));
      $desired_result=bx_db_fetch_array($desired_query);
      $cc_transaction_query = bx_db_query("select * from ".$bx_table_prefix."_cctransactions where opid = '".$HTTP_GET_VARS['opid']."'");
      if(bx_db_num_rows($cc_transaction_query) > 0) {
          $cc_transaction = true;
          $cc_transaction_result = bx_db_fetch_array($cc_transaction_query);
      }
      else {
          $cc_transaction = false;
      }
      include(DIR_ADMIN."details_upgrade_form.php");
}//end elseif (($HTTP_GET_VARS['action']=="upgrades") and ($HTTP_GET_VARS['compid'])) {
elseif (($HTTP_GET_VARS['action']=="buyers") and ($HTTP_GET_VARS['compid'])) {
      $actual_query=bx_db_query("select * from ".$bx_table_prefix."_membership,".$bx_table_prefix."_pricing_".$bx_table_lng." where ".$bx_table_prefix."_membership.compid='".$HTTP_GET_VARS['compid']."' and ".$bx_table_prefix."_pricing_".$bx_table_lng.".pricing_id=".$bx_table_prefix."_membership.pricing_id");
      SQL_CHECK(0,"SQL Error at ".__FILE__.":".(__LINE__-1));
      $actual_result=bx_db_fetch_array($actual_query);
      $actual_invoice_query=bx_db_query("select * from ".$bx_table_prefix."_invoices where ".$bx_table_prefix."_invoices.compid='".$HTTP_GET_VARS['compid']."' and ".$bx_table_prefix."_invoices.opid='".$HTTP_GET_VARS['opid']."'");
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
  $invoice_query=bx_db_query("select *,".$bx_table_prefix."_invoices.currency as icurrency from ".$bx_table_prefix."_invoices,$bx_db_table_banner_types where ".$bx_table_prefix."_invoices.opid='".$HTTP_GET_VARS['opid']."' and i_zone=type_id");
  SQL_CHECK(0,"SQL Error at ".__FILE__.":".(__LINE__-1));
  $invoice_result=bx_db_fetch_array($invoice_query);
  		/*$banner_invoices_SQL = "select * from $bx_db_table_banner_invoices,$bx_db_table_banner_types where invoice_id='".$invoice_result['opid']."' and i_zone=type_id";
   		$banner_invoice_query = bx_db_query($banner_invoices_SQL);
   		SQL_CHECK(0,"SQL Error at ".__FILE__.":".(__LINE__-1));
		$banner_invoice_result = bx_db_fetch_array($banner_invoice_query);*/
  include(DIR_ADMIN."details_invoice_form.php");
}//end elseif (($HTTP_GET_VARS['action']=="details") and ($HTTP_GET_VARS['opid']))
elseif (($HTTP_GET_VARS['action']=="details") and ($HTTP_GET_VARS['persid'])) {
  $personal_query=bx_db_query("select * from ".$bx_table_prefix."_persons, ".$bx_table_prefix."_personcredits where ".$bx_table_prefix."_persons.persid='".$HTTP_GET_VARS['persid']."' and ".$bx_table_prefix."_personcredits.persid = ".$bx_table_prefix."_persons.persid");
  SQL_CHECK(0,"SQL Error at ".__FILE__.":".(__LINE__-1));
  $personal_result=bx_db_fetch_array($personal_query);
  include(DIR_ADMIN."details_person_form.php");
}//end elseif (($HTTP_GET_VARS['action']=="details") and ($HTTP_GET_VARS['persid'])) { 
elseif (($HTTP_GET_VARS['action']=="details") and ($HTTP_GET_VARS['resumeid'])) {
    $resume_query=bx_db_query("select * from ".$bx_table_prefix."_resumes where resumeid='".$HTTP_GET_VARS['resumeid']."'");
    SQL_CHECK(0,"SQL Error at ".__FILE__.":".(__LINE__-1));
    $resume_result=bx_db_fetch_array($resume_query);
    include(DIR_ADMIN."details_resume_form.php");
}
elseif (($HTTP_GET_VARS['action']=="details") and ($HTTP_GET_VARS['jopid'])) {
    $invoice_query=bx_db_query("select *,".$bx_table_prefix."_jinvoices.currency as icurrency from ".$bx_table_prefix."_jinvoices,".$bx_table_prefix."_jpricing_".$bx_table_lng." where ".$bx_table_prefix."_jinvoices.opid='".$HTTP_GET_VARS['jopid']."' and ".$bx_table_prefix."_jpricing_".$bx_table_lng.".pricing_id=".$bx_table_prefix."_jinvoices.pricing_type");
    SQL_CHECK(0,"SQL Error at ".__FILE__.":".(__LINE__-1));
    $invoice_result=bx_db_fetch_array($invoice_query);
    include(DIR_ADMIN."details_jinvoice_form.php");
}//end if (($HTTP_GET_VARS['action']=="details") and ($HTTP_GET_VARS['jopid']))
elseif (($HTTP_GET_VARS['action']=="jobseekers") and ($HTTP_GET_VARS['persid'])) {
    $actual_query=bx_db_query("select * from ".$bx_table_prefix."_jmembership,".$bx_table_prefix."_jpricing_".$bx_table_lng." where ".$bx_table_prefix."_jmembership.persid='".$HTTP_GET_VARS['persid']."' and ".$bx_table_prefix."_jpricing_".$bx_table_lng.".pricing_id=".$bx_table_prefix."_jmembership.pricing_id");
    SQL_CHECK(0,"SQL Error at ".__FILE__.":".(__LINE__-1));
    $actual_result=bx_db_fetch_array($actual_query);
    $desired_query=bx_db_query("select * from ".$bx_table_prefix."_jpricing_".$bx_table_lng.",".$bx_table_prefix."_jinvoices where ".$bx_table_prefix."_jinvoices.persid='".$HTTP_GET_VARS['persid']."' and ".$bx_table_prefix."_jinvoices.opid = '".$HTTP_GET_VARS['opid']."' and ".$bx_table_prefix."_jinvoices.op_type='1' and ".$bx_table_prefix."_jpricing_".$bx_table_lng.".pricing_id=".$bx_table_prefix."_jinvoices.pricing_type");
    SQL_CHECK(0,"SQL Error at ".__FILE__.":".(__LINE__-1));
    $desired_result=bx_db_fetch_array($desired_query);
    $cc_transaction_query = bx_db_query("select * from ".$bx_table_prefix."_jcctransactions where opid = '".$HTTP_GET_VARS['opid']."'");
    if(bx_db_num_rows($cc_transaction_query) > 0) {
          $cc_transaction = true;
          $cc_transaction_result = bx_db_fetch_array($cc_transaction_query);
    }
    else {
          $cc_transaction = false;
    }
    include(DIR_ADMIN."details_jupgrade_form.php");
}//end elseif (($HTTP_GET_VARS['action']=="jobseekers") and ($HTTP_GET_VARS['persid'])) {
elseif (($HTTP_GET_VARS['action']=="details") and ($HTTP_GET_VARS['applyid'])) {
  $apply_query=bx_db_query("select * from ".$bx_table_prefix."_jobapply,".$bx_table_prefix."_companies,".$bx_table_prefix."_jobs, ".$bx_table_prefix."_persons,".$bx_table_prefix."_resumes where ".$bx_table_prefix."_jobapply.applyid='".$HTTP_GET_VARS['applyid']."' and ".$bx_table_prefix."_companies.compid = ".$bx_table_prefix."_jobapply.compid and ".$bx_table_prefix."_persons.persid=".$bx_table_prefix."_jobapply.persid and ".$bx_table_prefix."_jobs.jobid=".$bx_table_prefix."_jobapply.jobid and ".$bx_table_prefix."_resumes.resumeid=".$bx_table_prefix."_jobapply.resumeid");
  SQL_CHECK(0,"SQL Error at ".__FILE__.":".(__LINE__-1));
  $apply_result=bx_db_fetch_array($apply_query);
  include(DIR_ADMIN."details_jobapply_form.php");
}//end elseif (($HTTP_GET_VARS['action']=="details") and ($HTTP_GET_VARS['applyid'])) {
else {
}
echo "</body></html>";
bx_exit();
?>