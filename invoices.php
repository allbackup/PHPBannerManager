<?php
include ('application_config_file.php');
	include(DIR_LANGUAGES.$language."/".FILENAME_INVOICES);
if ($HTTP_GET_VARS['printit']=="yes") {
    echo "<html><head><title>".TEXT_PRINT_INV_DETAILS.":".$HTTP_GET_VARS['opid']."</title>";
    echo "<SCRIPT Language=\"Javascript\">\n";
    echo "function printit(){  \n";
    echo "var navver = parseInt(navigator.appVersion);\n";
    echo "if (navver > 3) {\n";
    echo "   if (window.print) {\n";
    echo "        parent.pmain.focus();\n";
    echo "        window.print() ;\n";  
    echo "    }\n";
    echo "}\n";
    echo "}\n";
    echo "</script>\n";
    echo "</head><body>\n";
}
if ($HTTP_SESSION_VARS['employerid'])
      {
       if ($HTTP_GET_VARS['action']=="pay")
       {
		  $invoice_SQL = "SELECT *,".$bx_table_prefix."_invoices.currency as icurrency from ".$bx_table_prefix."_invoices, $bx_db_table_banner_types where ".$bx_table_prefix."_invoices.opid='".$HTTP_GET_VARS['opid']."' and i_zone=type_id";
          $invoice_query=bx_db_query($invoice_SQL);
          SQL_CHECK(0,"SQL Error at ".__FILE__.":".(__LINE__-1));
          $invoice_result=bx_db_fetch_array($invoice_query);

          if ($invoice_result['compid']==$HTTP_SESSION_VARS['employerid']) {
              if ($HTTP_GET_VARS['printit']!="yes") {
                  include(DIR_SERVER_ROOT."header.php");
              }
              include(DIR_FORMS.FILENAME_INVOICES_FORM);
              if ($HTTP_GET_VARS['printit']!="yes") {
                  include(DIR_SERVER_ROOT."footer.php");
              }
          } //end if ($invoice_result['compid']==$HTTP_SESSION_VARS['employerid'])
          else {
               $error_message=TEXT_UNAUTHORIZED_ACCESS;
               $back_url=bx_make_url(HTTP_SERVER.FILENAME_MEMBERSHIP, "auth_sess", $bx_session);
               if ($HTTP_GET_VARS['printit']!="yes") {
                  include(DIR_SERVER_ROOT."header.php");
               }
               include(DIR_FORMS.FILENAME_ERROR_FORM);
               if ($HTTP_GET_VARS['printit']!="yes") {
                  include(DIR_SERVER_ROOT."footer.php");
               }
          }//end else ($verify_result['compid']==$HTTP_SESSION_VARS['employerid'])
       }//end if exist action==pay
       elseif ($HTTP_GET_VARS['action']=="view")
        {
          $invoice_query=bx_db_query("SELECT *,".$bx_table_prefix."_invoices.currency as icurrency from ".$bx_table_prefix."_invoices,$bx_db_table_banner_types where ".$bx_table_prefix."_invoices.opid='".$HTTP_GET_VARS['opid']."' and i_zone=type_id");
          SQL_CHECK(0,"SQL Error at ".__FILE__.":".(__LINE__-1));
          $invoice_result=bx_db_fetch_array($invoice_query);

          if ($invoice_result['compid']==$HTTP_SESSION_VARS['employerid'])
           {
                if ($HTTP_GET_VARS['printit']!="yes") {
                      include(DIR_SERVER_ROOT."header.php");
                }
                include(DIR_FORMS.FILENAME_INVOICES_FORM);
                if ($HTTP_GET_VARS['printit']!="yes") {
                      include(DIR_SERVER_ROOT."footer.php");
                }
           } //end if ($verify_result['compid']==$HTTP_SESSION_VARS['employerid'])
           else
           {
               $error_message=TEXT_UNAUTHORIZED_ACCESS;
               $back_url=bx_make_url(HTTP_SERVER.FILENAME_MEMBERSHIP, "auth_sess", $bx_session);
               if ($HTTP_GET_VARS['printit']!="yes") {
                      include(DIR_SERVER_ROOT."header.php");
               }
               include(DIR_FORMS.FILENAME_ERROR_FORM);
               if ($HTTP_GET_VARS['printit']!="yes") {
                      include(DIR_SERVER_ROOT."footer.php");
               }
           }//end else ($verify_result['compid']==$HTTP_SESSION_VARS['employerid'])
        } //end elseif ($HTTP_GET_VARS['action']=="view")
		elseif ($HTTP_GET_VARS['action']=="del")
        {
		  $invoice_query=bx_db_query("SELECT *,".$bx_table_prefix."_invoices.currency as icurrency from ".$bx_table_prefix."_invoices where ".$bx_table_prefix."_invoices.opid='".$HTTP_GET_VARS['opid']."'");
          SQL_CHECK(0,"SQL Error at ".__FILE__.":".(__LINE__-1));
          $invoice_result=bx_db_fetch_array($invoice_query);
          if ($invoice_result['compid']==$HTTP_SESSION_VARS['employerid'])
           {
				if ($HTTP_GET_VARS['del']=="n") {
					header("Location: ".bx_make_url(HTTP_SERVER.FILENAME_MYINVOICES, "auth_sess", $bx_session));
                    bx_exit();
				}
				elseif ($HTTP_GET_VARS['del']=="y") {
					bx_db_query("DELETE FROM ".$bx_table_prefix."_invoices where ".$bx_table_prefix."_invoices.opid='".$HTTP_GET_VARS['opid']."'");
					SQL_CHECK(0,"SQL Error at ".__FILE__.":".(__LINE__-1));
					if ($invoice_result['op_type']==3)
					{
						bx_db_query("DELETE FROM $bx_db_table_banner_invoices where $bx_db_table_banner_invoices.invoice_id='".$HTTP_GET_VARS['opid']."'");
						SQL_CHECK(0,"SQL Error at ".__FILE__.":".(__LINE__-1));

						$select_buser_SQL = "select * from $bx_db_table_banner_invoices where i_job_user_id='".$employerid."'";
						$select_buser_query = bx_db_query($select_buser_SQL);
						SQL_CHECK(0,"SQL Error at ".__FILE__.":".(__LINE__-1));
						if (bx_db_num_rows($select_buser_query)==1)
						{
							$deleteSQL = "delete from $bx_db_table_banner_users where compid='".$employerid."'";
							$delete_query = bx_db_query($deleteSQL);
							SQL_CHECK(0,"SQL Error at ".__FILE__.":".(__LINE__-1));
						}
					}
					header("Location: ".bx_make_url(HTTP_SERVER.FILENAME_MYINVOICES, "auth_sess", $bx_session));
                    bx_exit();
				}
		   } //end if ($verify_result['compid']==$HTTP_SESSION_VARS['employerid'])
           else
           {
               $error_message=TEXT_UNAUTHORIZED_ACCESS;
               $back_url=bx_make_url(HTTP_SERVER.FILENAME_MEMBERSHIP, "auth_sess", $bx_session);
               if ($HTTP_GET_VARS['printit']!="yes") {
                      include(DIR_SERVER_ROOT."header.php");
               }
               include(DIR_FORMS.FILENAME_ERROR_FORM);
               if ($HTTP_GET_VARS['printit']!="yes") {
                      include(DIR_SERVER_ROOT."footer.php");
               }
           }//end else ($verify_result['compid']==$HTTP_SESSION_VARS['employerid'])
		}
		elseif ($HTTP_GET_VARS['action']=="cancel")
        {
		  $invoice_query=bx_db_query("SELECT *,".$bx_table_prefix."_invoices.currency as icurrency from ".$bx_table_prefix."_invoices where ".$bx_table_prefix."_invoices.opid='".$HTTP_GET_VARS['opid']."'");
          SQL_CHECK(0,"SQL Error at ".__FILE__.":".(__LINE__-1));
          $invoice_result=bx_db_fetch_array($invoice_query);

		$banner_invoices_SQL = "select * from $bx_db_table_banner_invoices,$bx_db_table_banner_types where i_zone=type_id";
   		$banner_invoice_query = bx_db_query($banner_invoices_SQL);
   		SQL_CHECK(0,"SQL Error at ".__FILE__.":".(__LINE__-1));
		$banner_invoice_result = bx_db_fetch_array($banner_invoice_query);

          if ($invoice_result['compid']==$HTTP_SESSION_VARS['employerid'])
          {
                if ($HTTP_GET_VARS['printit']!="yes") {
                  include(DIR_SERVER_ROOT."header.php");
                }
                include(DIR_FORMS.FILENAME_INVOICES_FORM);
                if ($HTTP_GET_VARS['printit']!="yes") {
                  include(DIR_SERVER_ROOT."footer.php");
                }

          } //end if ($verify_result['compid']==$HTTP_SESSION_VARS['employerid'])
          else
          {
               $error_message=TEXT_UNAUTHORIZED_ACCESS;
               $back_url=bx_make_url(HTTP_SERVER.FILENAME_MEMBERSHIP, "auth_sess", $bx_session);
               if ($HTTP_GET_VARS['printit']!="yes") {
                  include(DIR_SERVER_ROOT."header.php");
               }
               include(DIR_FORMS.FILENAME_ERROR_FORM);
               if ($HTTP_GET_VARS['printit']!="yes") {
                  include(DIR_SERVER_ROOT."footer.php");
               }
          }//end else ($verify_result['compid']==$HTTP_SESSION_VARS['employerid'])
		}
        elseif ($HTTP_GET_VARS['action']=="update")
        {
          $invoice_query=bx_db_query("SELECT *,".$bx_table_prefix."_invoices.currency as icurrency from ".$bx_table_prefix."_invoices where ".$bx_table_prefix."_invoices.opid='".$HTTP_GET_VARS['opid']."'");
          SQL_CHECK(0,"SQL Error at ".__FILE__.":".(__LINE__-1));
          $invoice_result=bx_db_fetch_array($invoice_query);
          if ($invoice_result['compid']==$HTTP_SESSION_VARS['employerid'] && $invoice_result['updated'] == "N")
           {
             bx_db_query("UPDATE ".$bx_table_prefix."_invoices set updated='Y', i_start_date='".date('Y-m-d')."' where ".$bx_table_prefix."_invoices.opid='".$invoice_result['opid']."'");
              SQL_CHECK(0,"SQL Error at ".__FILE__.":".(__LINE__-1));
             $success_message=TEXT_UPDATE_SUCCESFULLY;
             $back_url=bx_make_url(HTTP_SERVER.FILENAME_MEMBERSHIP, "auth_sess", $bx_session);
             if ($HTTP_GET_VARS['printit']!="yes") {
                  include(DIR_SERVER_ROOT."header.php");
             }
             include(DIR_FORMS.FILENAME_MESSAGE_FORM);
             if ($HTTP_GET_VARS['printit']!="yes") {
                  include(DIR_SERVER_ROOT."footer.php");
             }
           } //end if ($verify_result['compid']==$HTTP_SESSION_VARS['employerid'])
           else
           {
               $error_message=TEXT_UNAUTHORIZED_ACCESS;
               $back_url=bx_make_url(HTTP_SERVER.FILENAME_MEMBERSHIP, "auth_sess", $bx_session);
               if ($HTTP_GET_VARS['printit']!="yes") {
                      include(DIR_SERVER_ROOT."header.php");
               }
               include(DIR_FORMS.FILENAME_ERROR_FORM);
               if ($HTTP_GET_VARS['printit']!="yes") {
                      include(DIR_SERVER_ROOT."footer.php");
               }
           }//end else ($verify_result['compid']==$HTTP_SESSION_VARS['employerid'])
        } //end elseif ($HTTP_GET_VARS['action']=="update")
      }
     else
      {
          $login='employer';
          if ($HTTP_GET_VARS['printit']!="yes") {
                  include(DIR_SERVER_ROOT."header.php");
          }
          include(DIR_FORMS.FILENAME_LOGIN_FORM);
          if ($HTTP_GET_VARS['printit']!="yes") {
                  include(DIR_SERVER_ROOT."footer.php");
          }
      }
if ($HTTP_GET_VARS['printit']=="yes") {
    echo "</body></html>";
}
?>