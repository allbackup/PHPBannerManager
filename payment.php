<?php
include ('application_config_file.php');
include(DIR_LANGUAGES.$language."/".FILENAME_INVOICES);
if ($HTTP_SESSION_VARS['employerid'] || (!$HTTP_SESSION_VARS['employerid'] && $HTTP_POST_VARS['compid'])) {
		  $invoice_sql = "SELECT *,".$bx_table_prefix."_invoices.currency as icurrency from ".$bx_table_prefix."_invoices,$bx_db_table_banner_types,$bx_db_table_banner_users where ".$bx_table_prefix."_invoices.opid='".$HTTP_POST_VARS['opid']."' and i_zone=type_id and compid=user_id";
          $invoice_query=bx_db_query($invoice_sql);
          SQL_CHECK(0,"SQL Error at ".__FILE__.":".(__LINE__-1));
          $invoice_result=bx_db_fetch_array($invoice_query);

          if ($invoice_result['compid']==$HTTP_SESSION_VARS['employerid'] || (!$HTTP_SESSION_VARS['employerid'] && $invoice_result['compid']==$HTTP_POST_VARS['compid'])) {
			   include(DIR_SERVER_ROOT.'cc_payment_settings.php');
			   if($HTTP_POST_VARS['payment_mode'] == 1) { 
					   include(DIR_FUNCTIONS.'cclib/cc_'.CC_PROCESSOR_TYPE.'_process.php'); 					
			   }
               elseif($HTTP_POST_VARS['payment_mode'] == 0) {
                           bx_db_query("UPDATE ".$bx_table_prefix."_invoices set payment_date='".date('Y-m-d')."', paid='Y', updated='N', validated='N', payment_mode='".$HTTP_POST_VARS['payment_mode']."',info='".(($invoice_result['pricing_type']==0)?"Payment description: ":"Upgrade to: ").$invoice_result['pricing_title']."' where ".$bx_table_prefix."_invoices.opid='".$HTTP_POST_VARS['opid']."'");
                           SQL_CHECK(0,"SQL Error at ".__FILE__.":".(__LINE__-1));
                           header('Location: '.bx_make_url(HTTP_SERVER.FILENAME_MYINVOICES, "auth_sess", $bx_session));
                           bx_exit();
	
               }
			   else {

                      include(DIR_SERVER_ROOT."header.php"); 
                      include(DIR_FORMS.FILENAME_PAYMENT_FORM);
                      include(DIR_SERVER_ROOT."footer.php");
			   }
          } //end if ($verify_result['compid']==$HTTP_SESSION_VARS['employerid'])
          else {
               include(DIR_SERVER_ROOT."header.php");
               $error_message=TEXT_UNAUTHORIZED_ACCESS;
               $back_url=bx_make_url(HTTP_SERVER.FILENAME_MEMBERSHIP, "auth_sess", $bx_session);
               include(DIR_FORMS.FILENAME_ERROR_FORM);
               include(DIR_SERVER_ROOT."footer.php");
          }//end else ($verify_result['compid']==$HTTP_SESSION_VARS['employerid'])
}//end if ($HTTP_SESSION_VARS['employerid'])
else {
   
    include(DIR_FORMS. 'header.php');
	include(DIR_FORMS. 'login_form.php');
	include(DIR_FORMS. 'footer.php');
}
?>