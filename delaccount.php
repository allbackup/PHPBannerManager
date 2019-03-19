<?php 
include ('application_config_file.php');
define('LEFT_NAVIGATION_WIDTH','20%');
define('MAIN_NAVIGATION_WIDTH','80%');
define('TABLE_HEADING_BGCOLOR', TABLE_EMPLOYER);
include(DIR_LANGUAGES.$language."/".FILENAME_DELACCOUNT);
       if ($HTTP_POST_VARS['action']=="delaccount") {
                   if (!$HTTP_SESSION_VARS['pbm_userid']) {
                        include('header.php');
						include(DIR_FORMS. 'login_form.php');
						include('footer.php');
                   }
                   else {
                        if ($HTTP_POST_VARS['yes']) {
							//$employer_query = bx_db_query("select * from ".$bx_table_prefix."_companies where compid='".$HTTP_SESSION_VARS['employerid']."'");
							
							$employer_query = bx_db_query("select * from ".$bx_db_table_banner_users." where user_id='".$HTTP_SESSION_VARS['employerid']."'");
                            $employer_result = bx_db_fetch_array($employer_query);

                            $mailfile = $language."/mail/employer_unregistration.txt";
                            include(DIR_LANGUAGES.$mailfile.".cfg.php");
                            if($mail_type=="1" && $employer_result['cmail_type'] == "html") {
                                $mailfile .= ".html";
                                $html_mail = "yes"; 
                            }
                            $mail_message = fread(fopen(DIR_LANGUAGES.$mailfile,"r"),filesize(DIR_LANGUAGES.$mailfile));
                            reset($fields);
							$employer_result['company'] = $employer_result['name'];
							$employer_result['email'] = $employer_result['username'];
                            while (list($h, $v) = each($fields)) {
                                 $mail_message = eregi_replace($v[0],$employer_result[$h],$mail_message);
                                 $file_mail_subject = eregi_replace($v[0],$employer_result[$h],$file_mail_subject);
                            }
                            if($employer_result['cmail_type'] == "html") {
                                 if ($add_html_header == "on") {
                                     $mail_message = fread(fopen(DIR_LANGUAGES.$language."/html/html_email_message_header.html","r"),filesize(DIR_LANGUAGES.$language."/html/html_email_message_header.html")).$mail_message;
                                 } 
                                 if ($add_html_header == "on") {
                                     $mail_message .= fread(fopen(DIR_LANGUAGES.$language."/html/html_email_message_footer.html","r"),filesize(DIR_LANGUAGES.$language."/html/html_email_message_footer.html"));
                                 } 
                            }
                            else {
                                if ($add_mail_signature == "on") {
                                     $mail_message .= "\n".SITE_SIGNATURE;
                                }
                                $mail_message = bx_unhtmlspecialchars($mail_message);
                            }
								
	
	                        bx_mail(SITE_NAME,SITE_MAIL,$employer_result['username'], stripslashes($file_mail_subject), stripslashes($mail_message), $html_mail); 
                            if($employer_result['logo']) {
                                $image_location = DIR_LOGO.$employer_result['logo'];
                                if (file_exists($image_location)) {
                                      @unlink($image_location);
                                }//end if (file_exists($image_location))
                            }

                            bx_db_query("delete from ".$bx_db_table_banner_users." where user_id=\"".$HTTP_SESSION_VARS['employerid']."\"");
                            SQL_CHECK(0,"SQL Error at ".__FILE__.":".(__LINE__-1));

                            bx_db_query("delete from ".$bx_table_prefix."_invoices where compid=\"".$HTTP_SESSION_VARS['employerid']."\"");
                            SQL_CHECK(0,"SQL Error at ".__FILE__.":".(__LINE__-1));

							//delete banner tables
							bx_db_query("delete from $bx_db_table_banner_users where user_id=\"".$employerid."\"");
                            SQL_CHECK(0,"SQL Error at ".__FILE__.":".(__LINE__-1));
							bx_db_query("delete from $bx_db_table_banner_invoices where compid=\"".$employerid."\"");
                            SQL_CHECK(0,"SQL Error at ".__FILE__.":".(__LINE__-1));
							
							//echo "<br>".
							$selectDeleteSQL = "select * from $bx_db_table_banner_banners where user_id='".$employerid."'";
							$select_delete_query = bx_db_query($selectDeleteSQL);
							SQL_CHECK(0,"SQL Error at ".__FILE__.":".(__LINE__-1));
							//echo bx_db_num_rows($select_delete_query);
							while($del_res = bx_db_fetch_array($select_delete_query))
								if(file_exists(DIR_BANNERS.$del_res['banner_name']) and $del_res['banner_name']!='' and $del_res['format']!='html')
									unlink(DIR_BANNERS.$del_res['banner_name']);


							bx_db_query("delete from $bx_db_table_banner_banners where user_id=\"".$employerid."\"");
                            SQL_CHECK(0,"SQL Error at ".__FILE__.":".(__LINE__-1));
							bx_db_query("delete from $bx_db_table_banner_deleted_banners where user_id=\"".$employerid."\"");
							bx_db_query("delete from $bx_db_table_banner_stats where user_id=\"".$employerid."\"");
                            SQL_CHECK(0,"SQL Error at ".__FILE__.":".(__LINE__-1));
					//delete banner tables

                            bx_session_destroy();
                            $HTTP_SESSION_VARS['employerid'] = '';
                            $HTTP_SESSION_VARS['employerid'] = '';
                            include(DIR_SERVER_ROOT."header.php");
                            $success_message=TEXT_DELETE_ACCOUNT_SUCCESSFULLY;
                            $back_url=bx_make_url(HTTP_SERVER.FILENAME_INDEX."?language=".$language, "auth_sess", $bx_session);
                            include(DIR_FORMS.FILENAME_MESSAGE_FORM);
                            include(DIR_SERVER_ROOT."footer.php");      
                        }
                        else {
                            header("Location: ".bx_make_url(HTTP_SERVER.FILENAME_MEMBERSHIP, "auth_sess", $bx_session));
                            bx_exit();
                        }
                  }
       }
       else {
             include(DIR_SERVER_ROOT."header.php");
             if ($HTTP_SESSION_VARS['employerid'] || $HTTP_SESSION_VARS['userid']) {
                  include(DIR_FORMS.FILENAME_DELACCOUNT_FORM);    
             }
             else {
                  include( 'header.php');
				  include(DIR_FORMS. 'login_form.php');
             }
             include("footer.php");      
       }
?>