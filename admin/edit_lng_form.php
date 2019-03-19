<?php
if($HTTP_POST_VARS['todo']) {
    $todo=$HTTP_POST_VARS['todo'];
}
elseif ($HTTP_GET_VARS['todo']){
     $todo = $HTTP_GET_VARS['todo'];
}
else {
    $todo = '';
}
if($HTTP_POST_VARS['lng']) {
    $lng=$HTTP_POST_VARS['lng'];
}
elseif ($HTTP_GET_VARS['lng']){
     $lng = $HTTP_GET_VARS['lng'];
}
else {
    $lng = '';
}
$lng_table_lang = substr($lng,0,2);
if($HTTP_POST_VARS['folders']) {
    $folders=$HTTP_POST_VARS['folders'];
}
elseif ($HTTP_GET_VARS['folders']){
     $folders = $HTTP_GET_VARS['folders'];
}
else {
    $folders = '';
}
$folder_table_lang = substr($folders,0,2);
$error_title = "editing language";

function header_nav($l_todo, $l_lng){
echo "<table width=\"100%\" cellpadding=\"1\" cellspacing=\"1\" class=\"edit\"><tr>";
 if ($l_todo == "editlng") {
      echo "<td align=\"center\"><b>Edit files</b></td>";
 }
 else {
      echo "<td align=\"center\" bgcolor=\"#909090\"><b><A href=\"".HTTP_SERVER_ADMIN.FILENAME_ADMIN_EDIT_LANGUAGE."?todo=editlng&folders=".urlencode($l_lng)."\" style=\"color:#000000; text-decoration:none; font-family: Verdana; font-weight: bold; font-size: 11px;\" onmouseover=\"window.status='Edit files'; return true;\" onmouseout=\"window.status=''; return true;\">Edit files</a></b></td>";
 }
 
 if ($l_todo == "editoptions") {
     echo "<td align=\"center\">Edit language options</b></td>";
 }
 else {
     echo "<td align=\"center\" bgcolor=\"#909090\"><b><A href=\"".HTTP_SERVER_ADMIN.FILENAME_ADMIN_EDIT_LANGUAGE."?todo=editoptions&folders=".urlencode($l_lng)."\" style=\"color:#000000; text-decoration:none; font-family: Verdana; font-weight: bold; font-size: 11px;\" onmouseover=\"window.status='Edit language options'; return true;\" onmouseout=\"window.status=''; return true;\">Edit language options</a></b></td>";
 }
 echo "</tr></table>";
}

//function to write the language file
function write_language_file($type) {
global $lng, $todo, $HTTP_POST_VARS;  
include(DIR_LANGUAGES.$lng.".php");
$newlangfile = array();
$langfile=file(DIR_LANGUAGES.$lng.".php");
    if ($type == "cctype") {
        if ($todo =="savecctype") {
            if ($HTTP_POST_VARS['cctypeid'] == "0") {
                $i=1;
                while (${TEXT_CCTYPE_OPT.$i})
                {
                     $i++;
                }
                for ($j=0;$j<sizeof($langfile);$j++) {
                    if (eregi("TEXT_CCTYPE_OPT".($i-1),$langfile[$j],$regs)) {
                        $newlangfile[] = $langfile[$j];
                        $newlangfile[] = "\$TEXT_CCTYPE_OPT".$i."='".$HTTP_POST_VARS['cctype']."';\n";
                    }
                    else {
                        $newlangfile[] = $langfile[$j];
                    }
                }
            }//end if degreeid == "0"
            else {
                for ($j=0;$j<sizeof($langfile);$j++) {
                    if (eregi("TEXT_CCTYPE_OPT".$HTTP_POST_VARS['cctypeid'],$langfile[$j],$regs)) {
                        $newlangfile[] = "\$TEXT_CCTYPE_OPT".$HTTP_POST_VARS['cctypeid']."='".$HTTP_POST_VARS['cctype']."';\n";
                    }
                    else {
                        $newlangfile[] = $langfile[$j];
                    }
                }//end for
            }//end else if degreeid == "0"
        }
        else if ($todo =="delcctype") {
            for ($j=0;$j<sizeof($langfile);$j++) {
                    if (eregi("TEXT_CCTYPE_OPT".$HTTP_POST_VARS['cctypeid'],$langfile[$j],$regs)) {
                    }
                    else {
                        if (eregi("TEXT_CCTYPE_OPT(.*)=(.*)",$langfile[$j],$regsw)) {
                            if ($regsw[1]>$HTTP_POST_VARS['cctypeid']) {
                                $newlangfile[] = "\$TEXT_CCTYPE_OPT".($regsw[1]-1)."=".$regsw[2];
                            }
                            else {
                                $newlangfile[] = $langfile[$j];       
                            }
                        }
                        else {
                            $newlangfile[] = $langfile[$j];   
                        }
                    }
            }//end for;
        } 
    }
   
    if ($type == "payment") {
        if ($todo =="savepayment") {
            if ($HTTP_POST_VARS['paymentid'] == "0") {
            }//end if paymentid == "0"
            else {
                for ($j=0;$j<sizeof($langfile);$j++) {
                    if (eregi("TEXT_PAYMENT_OPT".$HTTP_POST_VARS['paymentid'],$langfile[$j],$regs)) {
                        $newlangfile[] = "\$TEXT_PAYMENT_OPT".$HTTP_POST_VARS['paymentid']."='".$HTTP_POST_VARS['payment']."';\n";
                    }
                    else {
                        $newlangfile[] = $langfile[$j];
                    }
                }//end for
            }//end else if paymentid == "0"
        }
    }
    if ($type == "charset") {
        if ($todo =="savecharset") {
                for ($j=0;$j<sizeof($langfile);$j++) {
                    if (eregi("CHARSET_OPTION",$langfile[$j],$regs)) {
                        $newlangfile[] = "\$CHARSET_OPTION='".$HTTP_POST_VARS['charset']."';\n";
                    }
                    else {
                        $newlangfile[] = $langfile[$j];
                    }
                }//end for
         }       
    }
    if ($type == "dformat") {
        if ($todo =="savedformat") {
                for ($j=0;$j<sizeof($langfile);$j++) {
                    if (eregi("DATE_FORMAT",$langfile[$j],$regs)) {
                        $newlangfile[] = "\$DATE_FORMAT='".$HTTP_POST_VARS['dformat']."';\n";
                    }
                    else {
                        $newlangfile[] = $langfile[$j];
                    }
                }//end for
         }       
    }
    if ($type == "pformat") {
        if ($todo =="savedpormat") {
                for ($j=0;$j<sizeof($langfile);$j++) {
                    if (eregi("PRICE_FORMAT",$langfile[$j],$regs)) {
                        $newlangfile[] = "\$PRICE_FORMAT='".$HTTP_POST_VARS['pformat']."';\n";
                    }
                    else {
                        $newlangfile[] = $langfile[$j];
                    }
                }//end for
         }       
    }
    $fp = fopen(DIR_LANGUAGES.$lng.".php", "w");
    for ($j=0;$j<sizeof($newlangfile);$j++) {
          fwrite($fp, $newlangfile[$j]);
    }
    fclose($fp);
}
//end function write_language_file
if ($todo == "upload") {
     if(ADMIN_SAFE_MODE == "yes" && $lng==DEFAULT_LANGUAGE) {
         $error_title = "editing language";
         bx_admin_error(TEXT_SAFE_MODE_ALERT);
     }
     else {
             if(!empty($HTTP_POST_FILES['flag_file']['tmp_name']) && $HTTP_POST_FILES['flag_file']['tmp_name'] != "none" && ereg("^php[0-9A-Za-z_.-]+$", basename($HTTP_POST_FILES['flag_file']['tmp_name'])) && (in_array($HTTP_POST_FILES['flag_file']['type'],array ("image/gif","image/pjpeg","image/jpeg","image/x-png"))))
             {
                $flag_size=getimagesize($HTTP_POST_FILES['flag_file']['tmp_name']);
                switch ($flag_size[2]) {
                          case 1:
                               $flag_extension=".gif";
                               break;
                          case 2:
                               $flag_extension=".jpg";
                               break;
                          case 3:
                               $flag_extension=".png";
                               break;
                        default:
                               $flag_extension="";
        
                }//end switch ($logo_size[2])
                 $flag_location = DIR_FLAG.$lng.$flag_extension;
                 if (file_exists($flag_location)) {
                        @unlink($flag_location);
                 }//end if (file_exists($flag_location))
                 if (file_exists(DIR_FLAG.$lng.".gif")) {
                        @unlink(DIR_FLAG.$lng.".gif");
                 }//end if (file_exists(DIR_FLAG.$lng.".gif"))
                 if (file_exists(DIR_FLAG.$lng.".jpg")) {
                        @unlink(DIR_FLAG.$lng.".jpg");
                 }//end if (file_exists(DIR_FLAG.$lng.".jpg"))
                 if (file_exists(DIR_FLAG.$lng.".png")) {
                        @unlink(DIR_FLAG.$lng.".png");
                 }//end if (file_exists(DIR_FLAG.$lng.".png"))
                 if (move_uploaded_file($HTTP_POST_FILES['flag_file']['tmp_name'], $flag_location)) {
                     @chmod($flag_location, 0777);
                 ?>
                 <script language="Javascript">
                    <!--
                    document.write('<form method="post" action="<?php echo HTTP_SERVER_ADMIN.FILENAME_ADMIN_EDIT_LANGUAGE."?ref=".time();?>" name="redirect">');
                    document.write('<input type="hidden" name="todo" value="editlng">');
                    document.write('<input type="hidden" name="folders" value="<?php echo $lng;?>">');
                    document.write('</form>');
                    document.redirect.submit();
                    //-->
                 </script>
                 <?php
                 }
                 else {
                     bx_admin_error("Language flag picture upload fail.");
                 }
             }
             else {
                    bx_admin_error("Language flag picture upload fail.");
             }
       }
}
elseif ($todo == "uploadimg") {
     if(ADMIN_SAFE_MODE == "yes" && $lng==DEFAULT_LANGUAGE) {
         $error_title = "editing language";
         bx_admin_error(TEXT_SAFE_MODE_ALERT);
     }
     else {
             if(!empty($HTTP_POST_FILES['replace_file']['tmp_name']) && $HTTP_POST_FILES['replace_file']['tmp_name'] != "none" && ereg("^php[0-9A-Za-z_.-]+$", basename($HTTP_POST_FILES['replace_file']['tmp_name'])) && (in_array($HTTP_POST_FILES['replace_file']['type'],array ("image/gif","image/pjpeg","image/jpeg","image/x-png"))))
             {
                $replace_size=getimagesize($HTTP_POST_FILES['replace_file']['tmp_name']);
                $replace_location = DIR_IMAGES.$HTTP_POST_VARS['replacefile'];
                if (file_exists($replace_location)) {
                        @unlink($replace_location);
                }//end if (file_exists($flag_location))
                if (move_uploaded_file($HTTP_POST_FILES['replace_file']['tmp_name'], $replace_location)) {
                @chmod($replace_location, 0777);
                ?>
                 <script language="Javascript"><!--
                    document.write('<form method="post" action="<?php echo HTTP_SERVER_ADMIN.FILENAME_ADMIN_EDIT_LANGUAGE."?ref=".time();?>" name="redirect">');
                    document.write('<input type="hidden" name="todo" value="editimg">');
                    document.write('<input type="hidden" name="folders" value="<?php echo $lng;?>">');
                    document.write('</form>');
                    document.redirect.submit();
                    //-->
                  </script>
                 <?php
                 }
                 else {
                     bx_admin_error("Language image file upload fail.");
                 }
             }
             else {
                    bx_admin_error("Language image file upload fail.");
             }
     }
}
elseif ($todo == "savecctype") {
     if(ADMIN_SAFE_MODE == "yes" && $lng==DEFAULT_LANGUAGE) {
         $error_title = "editing language";
         bx_admin_error(TEXT_SAFE_MODE_ALERT);
     }
     else {
             $ready = false;
             if (empty($lng)) {
                    bx_admin_error("Please select a language to edit.");
             }
             else {
                   if ($HTTP_POST_VARS['cctypeid'] == "0") { //when we are adding a jobtype
                        if (empty($HTTP_POST_VARS['cctype'])) {
                             bx_admin_error("Invalid CC Type Option! Please enter a CC Type Option to add.");
                        }
                        else {
                             $dirs = getFolders(DIR_LANGUAGES);  
                             for ($i=0; $i<count($dirs); $i++) {
                                        if (file_exists(DIR_LANGUAGES.$dirs[$i].".php")) {
                                                 $lng = $dirs[$i];
                                                 write_language_file("cctype");
                                         }
                             }
                             $ready = true;
                        }
                   } //end if ($postlangid == "0")
                   else {  //else we are updating a jobtype
                        if (empty($HTTP_POST_VARS['cctype'])) {
                             bx_admin_error("Invalid CC Type Option! Please enter a CC Type Option to add.");
                        }
                        else {
                             write_language_file("cctype");
                             $ready = true;
                        }
                   } //end else if ($postlangid == "0")
                   if ($ready) {
                ?>
                 <script language="Javascript">
                 <!--
                    document.write('<form method="post" action="<?php echo HTTP_SERVER_ADMIN.FILENAME_ADMIN_EDIT_LANGUAGE."?ref=".time();?>" name="redirect">');
                    document.write('<input type="hidden" name="todo" value="editoptions">');
                    document.write('<input type="hidden" name="folders" value="<?php echo $lng;?>">');
                    document.write('</form>');
                    document.redirect.submit();
                  //-->
                  </script>
                 <?php
                 }
            }
     }        
}
elseif ($todo == "delcctype") {
     if(ADMIN_SAFE_MODE == "yes" && $lng==DEFAULT_LANGUAGE) {
         $error_title = "editing language";
         bx_admin_error(TEXT_SAFE_MODE_ALERT);
     }
     else {
         $ready = false;
         if (empty($lng)) {
                bx_admin_error("Please select a language to edit.");
         }
         else {
                $dirs = getFolders(DIR_LANGUAGES);  
                for ($i=0; $i<count($dirs); $i++) {
                           if (file_exists(DIR_LANGUAGES.$dirs[$i].".php")) {
                                     $lng = $dirs[$i];
                                     write_language_file("cctype");
                           }
                }
                $ready = true;
         } //end else if ($cctypeid == "0")
         if ($ready) {
            ?>
             <script language="Javascript">
                <!--
                document.write('<form method="post" action="<?php echo HTTP_SERVER_ADMIN.FILENAME_ADMIN_EDIT_LANGUAGE."?ref=".time();?>" name="redirect">');
                document.write('<input type="hidden" name="todo" value="editoptions">');
                document.write('<input type="hidden" name="folders" value="<?php echo $lng;?>">');
                document.write('</form>');
                document.redirect.submit();
                //-->
             </script>
             <?php
          }
     }     
}
elseif ($todo == "savepayment") {
     if(ADMIN_SAFE_MODE == "yes" && $lng==DEFAULT_LANGUAGE) {
         $error_title = "editing language";
         bx_admin_error(TEXT_SAFE_MODE_ALERT);
     }
     else {
         $ready = false;
         if (empty($lng)) {
                bx_admin_error("Please select a language to edit.");
         }
         else {
               if ($HTTP_POST_VARS['paymentid'] == "0") { //when we are adding a jobtype
               } //end if ($postlangid == "0")
               else {  //else we are updating a jobtype
                    if (empty($HTTP_POST_VARS['payment'])) {
                         bx_admin_error("Invalid Payment Option! Please enter a Payment Option to add.");
                    }
                    else {
                         write_language_file("payment");
                         $ready = true;
                    }
               } //end else if ($postlangid == "0")
               if ($ready) {
            ?>
             <script language="Javascript">
             <!--
                document.write('<form method="post" action="<?php echo HTTP_SERVER_ADMIN.FILENAME_ADMIN_EDIT_LANGUAGE."?ref=".time();?>" name="redirect">');
                document.write('<input type="hidden" name="todo" value="editoptions">');
                document.write('<input type="hidden" name="folders" value="<?php echo $lng;?>">');
                document.write('</form>');
                document.redirect.submit();
              //-->
              </script>
             <?php
             }
         }
     }    
}
elseif ($todo == "savecharset") {
     if(ADMIN_SAFE_MODE == "yes" && $lng==DEFAULT_LANGUAGE) {
         $error_title = "editing language";
         bx_admin_error(TEXT_SAFE_MODE_ALERT);
     }
     else {
         $ready = false;
         if (empty($HTTP_POST_VARS['lng'])) {
                bx_admin_error("Please select a language to edit.");
         }
         else {
                   if (empty($HTTP_POST_VARS['charset'])) {
                         bx_admin_error("Invalid Language Encoding Option! Please enter a Language Encoding to update.");
                   }
                   else {
                         write_language_file("charset");
                        $ready = true;
                   }
             if ($ready) {
            ?>
             <script language="Javascript">
             <!--
                document.write('<form method="post" action="<?php echo HTTP_SERVER_ADMIN.FILENAME_ADMIN_EDIT_LANGUAGE."?ref=".time();?>" name="redirect">');
                document.write('<input type="hidden" name="todo" value="editoptions">');
                document.write('<input type="hidden" name="folders" value="<?php echo $HTTP_POST_VARS['lng'];?>">');
                document.write('</form>');
                document.redirect.submit();
              //-->
              </script>
             <?php
             }
         }
     }    
}
elseif ($todo == "savedformat") {
     if(ADMIN_SAFE_MODE == "yes" && $lng==DEFAULT_LANGUAGE) {
         $error_title = "editing language";
         bx_admin_error(TEXT_SAFE_MODE_ALERT);
     }
     else {
         $ready = false;
         if (empty($HTTP_POST_VARS['lng'])) {
                bx_admin_error("Please select a language to edit.");
         }
         else {
                   if (empty($HTTP_POST_VARS['dformat'])) {
                        bx_admin_error("Invalid Language Date Display Format! Please enter a Language Date Display Format.");
                   }
                   else {
                        write_language_file("dformat");
                        $ready = true;
                   }
             if ($ready) {
            ?>
             <script language="Javascript">
             <!--
                document.write('<form method="post" action="<?php echo HTTP_SERVER_ADMIN.FILENAME_ADMIN_EDIT_LANGUAGE."?ref=".time();?>" name="redirect">');
                document.write('<input type="hidden" name="todo" value="editoptions">');
                document.write('<input type="hidden" name="folders" value="<?php echo $HTTP_POST_VARS['lng'];?>">');
                document.write('</form>');
                document.redirect.submit();
              //-->
              </script>
             <?php
             }
         }
     }    
}
elseif ($todo == "savepformat") {
     if(ADMIN_SAFE_MODE == "yes" && $lng==DEFAULT_LANGUAGE) {
         $error_title = "editing language";
         bx_admin_error(TEXT_SAFE_MODE_ALERT);
     }
     else {
         $ready = false;
         if (empty($HTTP_POST_VARS['lng'])) {
                bx_admin_error("Please select a language to edit.");
         }
         else {
                   if (empty($HTTP_POST_VARS['pformat'])) {
                         bx_admin_error("Invalid Language Price Display Format! Please enter a Language Price Display Format.");
                   }
                   else {
                         write_language_file("pformat");
                        $ready = true;
                   }
             if ($ready) {
            ?>
             <script language="Javascript">
             <!--
                document.write('<form method="post" action="<?php echo HTTP_SERVER_ADMIN.FILENAME_ADMIN_EDIT_LANGUAGE."?ref=".time();?>" name="redirect">');
                document.write('<input type="hidden" name="todo" value="editoptions">');
                document.write('<input type="hidden" name="folders" value="<?php echo $HTTP_POST_VARS['lng'];?>">');
                document.write('</form>');
                document.redirect.submit();
              //-->
              </script>
             <?php
             }
         }
     }    
}
else if ($todo == "savefile") {
     if(ADMIN_SAFE_MODE == "yes" && $lng==DEFAULT_LANGUAGE) {
         $error_title = "editing language";
         bx_admin_error(TEXT_SAFE_MODE_ALERT);
     }
     else {
            $towrite = '';
            for ($i=0;$i<count($HTTP_POST_VARS['inputs']) ;$i++ ) {
                if ($HTTP_POST_VARS[$HTTP_POST_VARS['inputs'][$i]]) {
                    $HTTP_POST_VARS[$HTTP_POST_VARS['inputs'][$i]] = stripslashes($HTTP_POST_VARS[$HTTP_POST_VARS['inputs'][$i]]);
                    if (eregi(".*'.*", $HTTP_POST_VARS[$HTTP_POST_VARS['inputs'][$i]], $regs)) {
                        if(ADMIN_SAFE_MODE == "yes") {
                            $HTTP_POST_VARS[$HTTP_POST_VARS['inputs'][$i]] = eregi_replace("exec|system|\(|\)|\$|print|echo","",$HTTP_POST_VARS[$HTTP_POST_VARS['inputs'][$i]]);
                        }    
                        $HTTP_POST_VARS[$HTTP_POST_VARS['inputs'][$i]] = eregi_replace("'", "\\'", $HTTP_POST_VARS[$HTTP_POST_VARS['inputs'][$i]]);
                        $HTTP_POST_VARS[$HTTP_POST_VARS['inputs'][$i]] = eregi_replace("\\\\'\.$", "\\\\'.", $HTTP_POST_VARS[$HTTP_POST_VARS['inputs'][$i]]);
                        $HTTP_POST_VARS[$HTTP_POST_VARS['inputs'][$i]] = eregi_replace("\\\\'[\.]", "'.", $HTTP_POST_VARS[$HTTP_POST_VARS['inputs'][$i]]);
                        $HTTP_POST_VARS[$HTTP_POST_VARS['inputs'][$i]] = eregi_replace("^\.\\\\'", "%[wqt]%", $HTTP_POST_VARS[$HTTP_POST_VARS['inputs'][$i]]);
                        $HTTP_POST_VARS[$HTTP_POST_VARS['inputs'][$i]] = eregi_replace("\.\\\\'", ".'", $HTTP_POST_VARS[$HTTP_POST_VARS['inputs'][$i]]);
                        $HTTP_POST_VARS[$HTTP_POST_VARS['inputs'][$i]] = eregi_replace("%\[wqt\]%", ".\'", $HTTP_POST_VARS[$HTTP_POST_VARS['inputs'][$i]]);
                    }
                    if($HTTP_POST_VARS['type']=="main") {
                        $towrite .= "define('".$HTTP_POST_VARS['inputs'][$i]."','".preg_replace("/(\015\012)|(\015)|(\012)/","'.\"\\n\".'",$HTTP_POST_VARS[$HTTP_POST_VARS['inputs'][$i]])."');\n";
                    }
                    else {
                        $HTTP_POST_VARS[$HTTP_POST_VARS['inputs'][$i]] = eregi_replace("\015\012|\015|\012", ' ', trim($HTTP_POST_VARS[$HTTP_POST_VARS['inputs'][$i]]));
                        $towrite .= "define('".$HTTP_POST_VARS['inputs'][$i]."','".$HTTP_POST_VARS[$HTTP_POST_VARS['inputs'][$i]]."');\n";
                    }
                }
                else {
                    if ( is_string($HTTP_POST_VARS[$HTTP_POST_VARS['inputs'][$i]])) {
                        $HTTP_POST_VARS[$HTTP_POST_VARS['inputs'][$i]] = stripslashes($HTTP_POST_VARS[$HTTP_POST_VARS['inputs'][$i]]);
                        if (eregi(".*'.*", $HTTP_POST_VARS[$HTTP_POST_VARS['inputs'][$i]], $regs)) 	{
                            $HTTP_POST_VARS[$HTTP_POST_VARS['inputs'][$i]] = eregi_replace("'", "\\'", $HTTP_POST_VARS[$HTTP_POST_VARS['inputs'][$i]]);
                            }
                                                $HTTP_POST_VARS[$HTTP_POST_VARS['inputs'][$i]] = eregi_replace("\015\012|\015|\012", ' ', trim($HTTP_POST_VARS[$HTTP_POST_VARS['inputs'][$i]]));
                                                $towrite .= "define('".$HTTP_POST_VARS['inputs'][$i]."','".$HTTP_POST_VARS[$HTTP_POST_VARS['inputs'][$i]]."');\n";
                    }
                    else {
                        $towrite .= "".stripslashes(trim($HTTP_POST_VARS['inputs'][$i]))."\n";
                    }
                }
            }
            $fp=fopen(DIR_LANGUAGES.$HTTP_POST_VARS['filename'],"w");
            fwrite($fp,"<?php\n");
            fwrite($fp, eregi_replace("\n$","",$towrite));
            fwrite($fp,"\n?>");
            fclose($fp);
            @chmod(DIR_LANGUAGES.$HTTP_POST_VARS['filename'], 0777);
            ?>
            <script language="Javascript">
            <!--
                document.write('<form method="post" action="<?php echo HTTP_SERVER_ADMIN.FILENAME_ADMIN_EDIT_LANGUAGE."?ref=".time();?>" name="redirect">');
                document.write('<input type="hidden" name="todo" value="editlng">');
                document.write('<input type="hidden" name="folders" value="<?php echo $lng;?>">');
                document.write('</form>');
                document.redirect.submit();
            //-->
            </script>
    <?php
     }
}
else if ($todo == "editfile") {
$fp=fopen(DIR_LANGUAGES.$HTTP_POST_VARS['editfile'],"r");
?>
<form method="post" action="<?php echo HTTP_SERVER_ADMIN.FILENAME_ADMIN_EDIT_LANGUAGE;?>" name="editfile">
<input type="hidden" name="todo" value="savefile">
<input type="hidden" name="filename" value="<?php echo $HTTP_POST_VARS['editfile'];?>">
<input type="hidden" name="lng" value="<?php echo $lng;?>">
<?php if($HTTP_POST_VARS['type']=="main") {
	echo "<input type=\"hidden\" name=\"type\" value=\"main\">";
}?>
<table width="100%" cellspacing="0" cellpadding="2" border="0" class="tedit">
<tr>
     <td align="center"><b>Edit language file: <?php echo $HTTP_POST_VARS['editfile'];?></b></td></tr>
<tr>
   <td><TABLE border="0" cellpadding="4" cellspacing="0" width="100%" class="edit">
   <tr>
       <td><b>Base language text:</b></td><td><b>New language text:</b></td>
   </tr>
<?php
while (!feof($fp)) {
   $str=trim(fgets($fp, 20000));
   if (!empty($str) && ($str != "\r\n") && ($str != "\n") && ($str != "\r")) {
        if (eregi("define\(['](.*)['|.?],['|.?| ](.*)\)",htmlspecialchars($str),$regexp)) {
            echo "<tr>";
            $regexp[2] = eregi_replace("'$", "", $regexp[2]);
			if (strlen(eregi_replace("'","",$regexp[2])) < 30) {
                echo "<td width=\"50%\" valign=\"top\"><span class=\"editlng\">".eregi_replace("\\\\'","'",$regexp[2])."</span></td><td><input type=\"text\" name=\"".$regexp[1]."\" size=\"40\" value=\"".eregi_replace("\\\\'","'",$regexp[2])."\"></td>";
            }
            else {
                $regexp[2] = eregi_replace("'\.&quot;\\\\n&quot;\.'","\n",$regexp[2]);
                $regexp[2] = eregi_replace('\.&quot;\\\\n&quot;\.',"\n",eregi_replace("\\\\'","&#039",eregi_replace("'\.","&#039;.",eregi_replace("\.'",".&#039;",$regexp[2]))));
                echo "<td width=\"50%\" valign=\"top\"><span class=\"editlng\">".$regexp[2]."</span></td><td><textarea name=\"".$regexp[1]."\"  rows=\"8\" cols=\"50\" wrap=virtual>".eregi_replace("'","",$regexp[2])."</textarea></td>";
            }
            echo "</tr>";
			echo "<input type=\"hidden\" name=\"inputs[]\" value=\"".$regexp[1]."\">";
        }
		else if ($str == "<?php" || $str == "?>") {
		}
        else {
             echo "<input type=\"hidden\" name=\"inputs[]\" value=\"".htmlspecialchars($str)."\">";
        }
   }
}
fclose($fp);
?>
<tr>
        <td colspan="3" align="right"><input type="submit" name="save" value="Save"></td>
</tr>
</table>
</td></tr></table>
</form>
<?php
}
elseif($todo=="mvup"){
    if(ADMIN_SAFE_MODE == "yes" && $lng==DEFAULT_LANGUAGE) {
         $error_title = "editing language";
         bx_admin_error(TEXT_SAFE_MODE_ALERT);
     }
     else {
         $ready = false;
         if (empty($lng)) {
                bx_admin_error("Please select a language to edit.");
         }
         else {
                if($HTTP_GET_VARS['type']=="categ") {
                       if($HTTP_GET_VARS['jobcategoryid'] && $HTTP_GET_VARS['upid']) {
                           $up_query="";
                           $mysql_query = array();
                           $categ_query=bx_db_query("SELECT * FROM ".$bx_table_prefix."_jobcategories_".$lng_table_lang);
                           SQL_CHECK(0,"SQL Error at ".__FILE__.":".(__LINE__-1));
                           $mysql_query[]="DELETE FROM ".$bx_table_prefix."_jobcategories_".$lng_table_lang;
                           while($categ_result=bx_db_fetch_array($categ_query)){
                               if($categ_result[0]==$HTTP_GET_VARS['upid']) {
                                     $up_query = "INSERT INTO ".$bx_table_prefix."_jobcategories_".$lng_table_lang." VALUES (".$categ_result[0].",'".addslashes($categ_result[1])."')";  
                               }
                               elseif($categ_result[0]==$HTTP_GET_VARS['jobcategoryid']) {
                                     $mysql_query[] = "INSERT INTO ".$bx_table_prefix."_jobcategories_".$lng_table_lang." VALUES (".$categ_result[0].",'".addslashes($categ_result[1])."')";  
                                     $mysql_query[] = $up_query;  
                               }
                               else {
                                     $mysql_query[] = "INSERT INTO ".$bx_table_prefix."_jobcategories_".$lng_table_lang." VALUES (".$categ_result[0].",'".addslashes($categ_result[1])."')";  
                               }
                           }//end while
                           for ($i=0;$i<sizeof($mysql_query);$i++){
                               bx_db_query($mysql_query[$i]);
                               SQL_CHECK(0,"SQL Error at ".__FILE__.":".(__LINE__-1));
                           }    
                           $mysql_query = array();
                           $ready = true;
                       }
                       else {
                           $ready = false;
                       }
                       if ($ready) {
                            ?>
                             <script language="Javascript">
                             <!--
                                document.write('<form method="post" action="<?php echo HTTP_SERVER_ADMIN.FILENAME_ADMIN_EDIT_LANGUAGE;?>" name="redirect">');
                                document.write('<input type="hidden" name="todo" value="editcateg">');
                                document.write('<input type="hidden" name="folders" value="<?php echo $lng;?>">');
                                document.write('</form>');
                                document.redirect.submit();
                              //-->
                              </script>
                             <?php
                      }//end if($ready)
                      else {
                          bx_admin_error("Invalid Jobcategory ID");
                      }
                }//end if($type=="categ")   
                elseif($HTTP_GET_VARS['type']=="location") {
                       if($HTTP_GET_VARS['locationid'] && $HTTP_GET_VARS['upid']) {
                           $up_query="";
                           $mysql_query = array();
                           $location_query=bx_db_query("SELECT * FROM ".$bx_table_prefix."_locations_".$lng_table_lang);
                           SQL_CHECK(0,"SQL Error at ".__FILE__.":".(__LINE__-1));
                           $mysql_query[]="DELETE FROM ".$bx_table_prefix."_locations_".$lng_table_lang;
                           while($location_result=bx_db_fetch_array($location_query)){
                               if($location_result[0]==$HTTP_GET_VARS['upid']) {
                                     $up_query = "INSERT INTO ".$bx_table_prefix."_locations_".$lng_table_lang." VALUES (".$location_result[0].",'".addslashes($location_result[1])."')";  
                               }
                               elseif($location_result[0]==$HTTP_GET_VARS['locationid']) {
                                     $mysql_query[] = "INSERT INTO ".$bx_table_prefix."_locations_".$lng_table_lang." VALUES (".$location_result[0].",'".addslashes($location_result[1])."')";  
                                     $mysql_query[] = $up_query;  
                               }
                               else {
                                     $mysql_query[] = "INSERT INTO ".$bx_table_prefix."_locations_".$lng_table_lang." VALUES (".$location_result[0].",'".addslashes($location_result[1])."')";  
                               }
                           }//end while
                           for ($i=0;$i<sizeof($mysql_query);$i++){
                               bx_db_query($mysql_query[$i]);
                               SQL_CHECK(0,"SQL Error at ".__FILE__.":".(__LINE__-1));
                           }    
                           $mysql_query = array();
                           $ready = true;
                       }
                       else {
                           $ready = false;
                       }
                       if ($ready) {
                            ?>
                             <script language="Javascript">
                             <!--
                                document.write('<form method="post" action="<?php echo HTTP_SERVER_ADMIN.FILENAME_ADMIN_EDIT_LANGUAGE;?>" name="redirect">');
                                document.write('<input type="hidden" name="todo" value="editlocation">');
                                document.write('<input type="hidden" name="folders" value="<?php echo $lng;?>">');
                                document.write('</form>');
                                document.redirect.submit();
                              //-->
                              </script>
                             <?php
                      }//end if($ready)
                      else {
                          bx_admin_error("Invalid JobLocation ID");
                      }
                }//end elseif($type=="location")   
                elseif($HTTP_GET_VARS['type']=="jobtype") {
                       if($HTTP_GET_VARS['jobtypeid'] && $HTTP_GET_VARS['upid']) {
                           $up_query="";
                           $mysql_query = array();
                           $type_query=bx_db_query("SELECT * FROM ".$bx_table_prefix."_jobtypes_".$lng_table_lang);
                           SQL_CHECK(0,"SQL Error at ".__FILE__.":".(__LINE__-1));
                           $mysql_query[]="DELETE FROM ".$bx_table_prefix."_jobtypes_".$lng_table_lang;
                           while($type_result=bx_db_fetch_array($type_query)){
                               if($type_result[0]==$HTTP_GET_VARS['upid']) {
                                     $up_query = "INSERT INTO ".$bx_table_prefix."_jobtypes_".$lng_table_lang." VALUES (".$type_result[0].",'".addslashes($type_result[1])."')";  
                               }
                               elseif($type_result[0]==$HTTP_GET_VARS['jobtypeid']) {
                                     $mysql_query[] = "INSERT INTO ".$bx_table_prefix."_jobtypes_".$lng_table_lang." VALUES (".$type_result[0].",'".addslashes($type_result[1])."')";  
                                     $mysql_query[] = $up_query;  
                               }
                               else {
                                     $mysql_query[] = "INSERT INTO ".$bx_table_prefix."_jobtypes_".$lng_table_lang." VALUES (".$type_result[0].",'".addslashes($type_result[1])."')";  
                               }
                           }//end while
                           for ($i=0;$i<sizeof($mysql_query);$i++){
                               bx_db_query($mysql_query[$i]);
                               SQL_CHECK(0,"SQL Error at ".__FILE__.":".(__LINE__-1));
                           }    
                           $mysql_query = array();
                           $ready = true;
                       }
                       else {
                           $ready = false;
                       }
                       if ($ready) {
                            ?>
                             <script language="Javascript">
                             <!--
                                document.write('<form method="post" action="<?php echo HTTP_SERVER_ADMIN.FILENAME_ADMIN_EDIT_LANGUAGE;?>" name="redirect">');
                                document.write('<input type="hidden" name="todo" value="edittypes">');
                                document.write('<input type="hidden" name="folders" value="<?php echo $lng;?>">');
                                document.write('</form>');
                                document.redirect.submit();
                              //-->
                              </script>
                             <?php
                      }//end if($ready)
                      else {
                          bx_admin_error("Invalid JobType ID");
                      }
                }//end if($type=="jobtype")   
                else {
                    ?>
                     <script language="Javascript">
                     <!--
                        document.write('<form method="post" action="<?php echo HTTP_SERVER_ADMIN.FILENAME_ADMIN_EDIT_LANGUAGE;?>" name="redirect">');
                        document.write('<input type="hidden" name="todo" value="">');
                        document.write('<input type="hidden" name="folders" value="<?php echo $lng;?>">');
                        document.write('</form>');
                        document.redirect.submit();
                      //-->
                      </script>
                     <?php
                }//end else
         }//end else if (empty($lng)) {
     }//end if(ADMIN_SAFE_MODE == "yes" && $lng==DEFAULT_LANGUAGE) {        
}//end elseif $todo=="mvup"
elseif($todo=="mvdwn"){
    if(ADMIN_SAFE_MODE == "yes" && $lng==DEFAULT_LANGUAGE) {
         $error_title = "editing language";
         bx_admin_error(TEXT_SAFE_MODE_ALERT);
     }
     else {
         $ready = false;
         if (empty($lng)) {
                bx_admin_error("Please select a language to edit.");
         }
         else {
                if($HTTP_GET_VARS['type']=="categ") {
                       if($HTTP_GET_VARS['jobcategoryid'] && $HTTP_GET_VARS['downid']) {
                           $down_query="";
                           $mysql_query = array();
                           $categ_query=bx_db_query("SELECT * FROM ".$bx_table_prefix."_jobcategories_".$lng_table_lang);
                           SQL_CHECK(0,"SQL Error at ".__FILE__.":".(__LINE__-1));
                           $mysql_query[]="DELETE FROM ".$bx_table_prefix."_jobcategories_".$lng_table_lang;
                           while($categ_result=bx_db_fetch_array($categ_query)){
                               if($categ_result[0]==$HTTP_GET_VARS['jobcategoryid']) {
                                     $down_query = "INSERT INTO ".$bx_table_prefix."_jobcategories_".$lng_table_lang." VALUES (".$categ_result[0].",'".addslashes($categ_result[1])."')";  
                               }
                               elseif($categ_result[0]==$HTTP_GET_VARS['downid']) {
                                     $mysql_query[] = "INSERT INTO ".$bx_table_prefix."_jobcategories_".$lng_table_lang." VALUES (".$categ_result[0].",'".addslashes($categ_result[1])."')";  
                                     $mysql_query[] = $down_query;  
                               }
                               else {
                                     $mysql_query[] = "INSERT INTO ".$bx_table_prefix."_jobcategories_".$lng_table_lang." VALUES (".$categ_result[0].",'".addslashes($categ_result[1])."')";  
                               }
                           }//end while
                           for ($i=0;$i<sizeof($mysql_query);$i++){
                               bx_db_query($mysql_query[$i]);
                               SQL_CHECK(0,"SQL Error at ".__FILE__.":".(__LINE__-1));
                           }    
                           $mysql_query = array();
                           $ready = true;
                       }
                       else {
                           $ready = false;
                       }
                       if ($ready) {
                            ?>
                             <script language="Javascript">
                             <!--
                                document.write('<form method="post" action="<?php echo HTTP_SERVER_ADMIN.FILENAME_ADMIN_EDIT_LANGUAGE;?>" name="redirect">');
                                document.write('<input type="hidden" name="todo" value="editcateg">');
                                document.write('<input type="hidden" name="folders" value="<?php echo $lng;?>">');
                                document.write('</form>');
                                document.redirect.submit();
                              //-->
                              </script>
                             <?php
                      }//end if($ready)
                      else {
                          bx_admin_error("Invalid Jobcategory ID");
                      }
                }//end if($type=="categ")   
                elseif($HTTP_GET_VARS['type']=="location") {
                       if($HTTP_GET_VARS['locationid'] && $HTTP_GET_VARS['downid']) {
                           $down_query="";
                           $mysql_query = array();
                           $location_query=bx_db_query("SELECT * FROM ".$bx_table_prefix."_locations_".$lng_table_lang);
                           SQL_CHECK(0,"SQL Error at ".__FILE__.":".(__LINE__-1));
                           $mysql_query[]="DELETE FROM ".$bx_table_prefix."_locations_".$lng_table_lang;
                           while($location_result=bx_db_fetch_array($location_query)){
                               if($location_result[0]==$HTTP_GET_VARS['locationid']) {
                                     $down_query = "INSERT INTO ".$bx_table_prefix."_locations_".$lng_table_lang." VALUES (".$location_result[0].",'".addslashes($location_result[1])."')";  
                               }
                               elseif($location_result[0]==$HTTP_GET_VARS['downid']) {
                                     $mysql_query[] = "INSERT INTO ".$bx_table_prefix."_locations_".$lng_table_lang." VALUES (".$location_result[0].",'".addslashes($location_result[1])."')";  
                                     $mysql_query[] = $down_query;  
                               }
                               else {
                                     $mysql_query[] = "INSERT INTO ".$bx_table_prefix."_locations_".$lng_table_lang." VALUES (".$location_result[0].",'".addslashes($location_result[1])."')";  
                               }
                           }//end while
                           for ($i=0;$i<sizeof($mysql_query);$i++){
                               bx_db_query($mysql_query[$i]);
                               SQL_CHECK(0,"SQL Error at ".__FILE__.":".(__LINE__-1));
                           }    
                           $mysql_query = array();
                           $ready = true;
                       }
                       else {
                           $ready = false;
                       }
                       if ($ready) {
                            ?>
                             <script language="Javascript">
                             <!--
                                document.write('<form method="post" action="<?php echo HTTP_SERVER_ADMIN.FILENAME_ADMIN_EDIT_LANGUAGE;?>" name="redirect">');
                                document.write('<input type="hidden" name="todo" value="editlocation">');
                                document.write('<input type="hidden" name="folders" value="<?php echo $lng;?>">');
                                document.write('</form>');
                                document.redirect.submit();
                              //-->
                              </script>
                             <?php
                      }//end if($ready)
                      else {
                          bx_admin_error("Invalid Location ID");
                      }
                }//end if($type=="location")  
                elseif($HTTP_GET_VARS['type']=="jobtype") {
                       if($HTTP_GET_VARS['jobtypeid'] && $HTTP_GET_VARS['downid']) {
                           $down_query="";
                           $mysql_query = array();
                           $type_query=bx_db_query("SELECT * FROM ".$bx_table_prefix."_jobtypes_".$lng_table_lang);
                           SQL_CHECK(0,"SQL Error at ".__FILE__.":".(__LINE__-1));
                           $mysql_query[]="DELETE FROM ".$bx_table_prefix."_jobtypes_".$lng_table_lang;
                           while($type_result=bx_db_fetch_array($type_query)){
                               if($type_result[0]==$HTTP_GET_VARS['jobtypeid']) {
                                     $down_query = "INSERT INTO ".$bx_table_prefix."_jobtypes_".$lng_table_lang." VALUES (".$type_result[0].",'".addslashes($type_result[1])."')";  
                               }
                               elseif($type_result[0]==$HTTP_GET_VARS['downid']) {
                                     $mysql_query[] = "INSERT INTO ".$bx_table_prefix."_jobtypes_".$lng_table_lang." VALUES (".$type_result[0].",'".addslashes($type_result[1])."')";  
                                     $mysql_query[] = $down_query;  
                               }
                               else {
                                     $mysql_query[] = "INSERT INTO ".$bx_table_prefix."_jobtypes_".$lng_table_lang." VALUES (".$type_result[0].",'".addslashes($type_result[1])."')";  
                               }
                           }//end while
                           for ($i=0;$i<sizeof($mysql_query);$i++){
                               bx_db_query($mysql_query[$i]);
                               SQL_CHECK(0,"SQL Error at ".__FILE__.":".(__LINE__-1));
                           }    
                           $mysql_query = array();
                           $ready = true;
                       }
                       else {
                           $ready = false;
                       }
                       if ($ready) {
                            ?>
                             <script language="Javascript">
                             <!--
                                document.write('<form method="post" action="<?php echo HTTP_SERVER_ADMIN.FILENAME_ADMIN_EDIT_LANGUAGE;?>" name="redirect">');
                                document.write('<input type="hidden" name="todo" value="edittypes">');
                                document.write('<input type="hidden" name="folders" value="<?php echo $lng;?>">');
                                document.write('</form>');
                                document.redirect.submit();
                              //-->
                              </script>
                             <?php
                      }//end if($ready)
                      else {
                          bx_admin_error("Invalid Jobtype ID");
                      }
                }//end if($type=="jobtype")                  
                else {
                    ?>
                     <script language="Javascript">
                     <!--
                        document.write('<form method="post" action="<?php echo HTTP_SERVER_ADMIN.FILENAME_ADMIN_EDIT_LANGUAGE;?>" name="redirect">');
                        document.write('<input type="hidden" name="todo" value="">');
                        document.write('<input type="hidden" name="folders" value="<?php echo $lng;?>">');
                        document.write('</form>');
                        document.redirect.submit();
                      //-->
                      </script>
                     <?php
                }//end else
         }//end else if (empty($lng)) {
     }//end if(ADMIN_SAFE_MODE == "yes" && $lng==DEFAULT_LANGUAGE) {        
}//end elseif $todo=="mvdwn"
else if ($todo == "editlng") {
     if (empty($folders)) {
         bx_admin_error("You must select a language to edit.");
     }//end if (empty($folders))
     else {
            ?>
            <table width="100%" cellspacing="0" cellpadding="2" border="0" class="tedit">
            <tr>
                 <td align="center"><b>Edit <?php echo urldecode($folders);?> language files</b></td>
            </tr>
            <tr>
               <td>
               <TABLE border="0" cellpadding="4" cellspacing="0" width="100%" class="edit">
               <tr>
                   <td colspan="4"><?php header_nav($todo, $folders);?></td>
               </tr>
               <tr>
                   <td colspan="2"><br></td>
               </tr>
               <tr>
                   <td valign="top" colspan="2"><b><?php echo TEXT_EDIT_LANGUAGE_FILE_NOTE;?></b></td>
               </tr>
               <tr>
                   <td valign="top" colspan="2"><b><?php echo TEXT_SELECT_EDIT_LANGUAGE_FILE;?>:</b></td>
               </tr>
               <tr>
                    <td colspan="2">
                        <table align="center" width="100%" border="0" cellspacing="0" cellpadding="2" class="edit">
                        <tr>
                            <td><b>#</b></td>
                            <td><b>File Name</b></td>
                            <td><b>Edit File</b></td>
                            <td><b>Last Modified</b></td>
                        </tr>
                        <tr>
                            <td colspan="4"><hr size="1"></td>
                        </tr>
                 <?php
                 $n=0;
                 if (file_exists(DIR_LANGUAGES.$folders.".php")) {
                       $fmodtime = filemtime (DIR_LANGUAGES.$folders.".php");
                       $lastmodtime = date("d.m.Y - H:i:s", $fmodtime);
                       $n++;
                       ?>
                       <tr>
                           <td><u><b><?php echo $n;?>.</b></u></td>
                           <td>
                           <form method="post" action="<?php echo HTTP_SERVER_ADMIN.FILENAME_ADMIN_EDIT_LANGUAGE;?>" style="margin-top: 0px; margin-bottom: 0px;">
                           <input type="hidden" name="todo" value="editfile">
                           <input type="hidden" name="editfile" value="<?php echo $folders;?>.php">
                           <input type="hidden" name="lng" value="<?php echo $folders;?>">
                           <input type="hidden" name="type" value="main">
                           <b><?php echo $folders;?></b></td>
                           <td><input type="submit" name="edit" value="Edit File"></td>
                           <td><?php echo $lastmodtime;?></td>
                       </tr>
                       </form>
                       <?php
                 }
               ?>
               <?php
                     $dirs = getFiles(DIR_LANGUAGES.$folders);
                     sort($dirs);
                     for ($i=0; $i<count($dirs); $i++) {
                       if ($dirs[$i]!="index.html" && $dirs[$i]!="index.htm") {
                           $n++;
                           $fmodtime = filemtime (DIR_LANGUAGES.$folders."/".$dirs[$i]);
                           $lastmodtime = date("d.m.Y - H:i:s", $fmodtime);
                           ?>
                           <tr>
                           <td><u><b><?php echo $n;?>.</b></u></td>
                           <td><form method="post" action="<?php echo HTTP_SERVER_ADMIN.FILENAME_ADMIN_EDIT_LANGUAGE;?>" style="margin-top: 0px; margin-bottom: 0px;">
                           <input type="hidden" name="todo" value="editfile">
                           <input type="hidden" name="editfile" value="<?php echo $folders."/".$dirs[$i];?>">
                           <input type="hidden" name="lng" value="<?php echo $folders;?>"><b><?php echo eregi_replace(".php$","",$dirs[$i]);?></b></td>
                           
                           <td><input type="submit" name="edit" value="Edit File"></td>
                           <td><?php echo $lastmodtime;?></td>
                           </form>
                           </tr>
                           <?php
                       }
                     }
                 ?>
                 <tr>
                    <td colspan="4"><hr size="1"></td>
                </tr>
               </table>
               </td>
               </tr>
               <tr>
                   <td valign="top">&nbsp;</td>
               </tr>
               <tr>
                   <td valign="top"><b><?php echo TEXT_UPLOAD_LANGUAGE_FLAG;?></b><br><font face="Verdana" size="1" color="#000000"><?php echo TEXT_UPLOAD_LANGUAGE_FLAG_NOTE;?></font></td>
               </tr>
               <?php
               if ( (file_exists(DIR_FLAG.$folders.".gif")) || (file_exists(DIR_FLAG.$folders.".jpg")) || (file_exists(DIR_FLAG.$folders.".png"))) {
               ?>
               <tr>
                   <td valign="top"><font face="Verdana" size="1" color="#000000"><?php echo TEXT_FLAG_INFORMATION;?>:</font></td>
               </tr>
               <?php
               if (file_exists(DIR_FLAG.$folders.".gif")) {
                   $imgsize = getimagesize(DIR_FLAG.$folders.".gif");
                   $imgname = $folders.".gif";
                   $imgmodtime = filemtime (DIR_FLAG.$folders.".gif");
               }
               if (file_exists(DIR_FLAG.$folders.".jpg")) {
                   $imgsize = getimagesize(DIR_FLAG.$folders.".jpg");
                   $imgname = $folders.".jpg";
                   $imgmodtime = filemtime (DIR_FLAG.$folders.".jpg");
               }
               if (file_exists(DIR_FLAG.$folders.".png")) {
                   $imgsize = getimagesize(DIR_FLAG.$folders.".png");
                   $imgname = $folders.".png";
                   $imgmodtime = filemtime (DIR_FLAG.$folders.".jpg");
               }
               $lastmodtime = date("d.m.Y - H:i:s", $imgmodtime);
               ?>
               <tr>
                   <td valign="top"><font class="smalltext"><?php echo TEXT_FLAG_FILE_NAME;?>: <?php echo $imgname;?></font></td>
               </tr>
               <tr>
                   <td valign="top"><font class="smalltext"><?php echo TEXT_FLAG_FILE_SIZE;?>: <?php echo $imgsize[0]."x".$imgsize[1];?></font></td>
               </tr>
               <tr>
                   <td valign="top"><font class="smalltext"><?php echo TEXT_FLAG_FILE_PREVIEW;?>: <?php echo bx_image(HTTP_FLAG.$imgname,0,'');?></font></td>
               </tr>
               <tr>
                   <td valign="top"><font class="smalltext"><?php echo TEXT_FLAG_FILE_LAST_MODIFIED;?>: <?php echo $lastmodtime;?></font></td>
               </tr>
               <?php
               }
               ?>
               <form method="post" enctype="multipart/form-data" action="<?php echo HTTP_SERVER_ADMIN.FILENAME_ADMIN_EDIT_LANGUAGE;?>" name="upload" style="margin-top: 0px; margin-bottom: 0px;">
               <input type="hidden" name="todo" value="upload">
               <input type="hidden" name="lng" value="<?php echo $folders;?>">
               <?php
               if (fileperms(DIR_FLAG) != 16895) {
               ?>
               <tr>
                   <td valign="top"><font face="Verdana" size="1" color="#FF0000">You must set the directory <i><?php echo DIR_FLAG;?></i> to all writeable (chmod 777).</font></td>
               </tr>
               <?php
               }
               ?>
               <tr>
                       <td align="right"><b><?php echo TEXT_UPLOAD_FLAG_FILE;?>:</b>&nbsp;<input type="file" name="flag_file"></td>
               </tr>
               <tr>
                       <td align="center"><input type="submit" name="save" value="Upload"></td>
               </tr>
			   <tr>
                       <td align="center">&nbsp;</td>
               </tr>
               </table>
         </td></tr></table>
         <?php
     }//end else if (empty($folders))
}//end if ($todo == "editlng")
else if ($todo == "editimg") {
     if (empty($folders)) {
         bx_admin_error("You must select a language to edit.");
     }//end if (empty($folders))
     else {
            ?>
            <table width="100%" cellspacing="0" cellpadding="2" border="0" class="tedit">
            <tr>
                 <td align="center"><b>Edit <?php echo $folders;?> language files</b></td>
            </tr>
            <tr>
               <td>
               <TABLE border="0" cellpadding="4" cellspacing="0" width="100%" class="edit">
               <tr>
                   <td colspan="4"><?php header_nav($todo, $folders);?></td>
               </tr>
               <tr>
                   <td colspan="2"><br></td>
               </tr>
               <tr>
                   <td valign="top" colspan="2"><b><?php echo TEXT_EDIT_LANGUAGE_IMAGE_NOTE;?></b></td>
               </tr>
               <tr>
                   <td valign="top" colspan="2"><b><?php echo TEXT_SELECT_EDIT_LANGUAGE_IMAGE;?>:</b></td>
               </tr>
               <?php
                     $dirs = getFiles(DIR_IMAGES.$folders);
                     sort($dirs);
                     for ($i=0; $i<count($dirs); $i++) {
                               if ($dirs[$i]!="index.html" && $dirs[$i]!="index.htm") {
                                   echo "<form method=\"post\" enctype=\"multipart/form-data\" action=\"".HTTP_SERVER_ADMIN.FILENAME_ADMIN_EDIT_LANGUAGE."\" style=\"margin-top: 0px; margin-bottom: 0px;\"><input type=\"hidden\" name=\"todo\" value=\"uploadimg\"><input type=\"hidden\" name=\"replacefile\" value=\"".$folders."/".$dirs[$i]."\"><input type=\"hidden\" name=\"lng\" value=\"".$folders."\">";
                                   //echo "<tr><td align=\"right\"><b>".$dirs[$i]."</b></td><td><input type=\"submit\" name=\"edit\" value=\"Edit\"></td></tr></form>";
                                   $imgsize = getimagesize(DIR_IMAGES.$folders."/".$dirs[$i]);
                                   $imgmodtime = filemtime(DIR_IMAGES.$folders."/".$dirs[$i]);
                                   $lastmodtime = date("d.m.Y - H:i:s", $imgmodtime);
                                   echo "<tr>";
                                        echo "<td width=\"50%\">".bx_image(HTTP_IMAGES.$folders."/".$dirs[$i],0,'')."</td>";
                                        echo "<td><font face=\"Verdana\" size=\"1\" color=\"#000000\">Name: <b>".$dirs[$i]."</b></font>";
                                        echo "<br><font face=\"Verdana\" size=\"1\" color=\"#000000\">Size: ".$imgsize[0]."x".$imgsize[1]."</font>";
                                        echo "<br><font face=\"Verdana\" size=\"1\" color=\"#000000\">Modified: ".$lastmodtime."</font>";
                                        echo "</td>";
    
                                   echo "</tr>";
                                   echo "<tr><td colspan=\"2\"><input type=\"file\" name=\"replace_file\">  <input type=\"submit\" name=\"replace\" value=\"Upload/Replace\"></td></tr></form>";
                               }     
                     }
                ?>
			   <tr>
                   <td valign="top" colspan="2">&nbsp;</td>
               </tr>
               </table>
         </td></tr></table>
         <?php
     }//end else if (empty($folders))
}//end if ($todo == "editimg")
else if ($todo == "editoptions") {
     if (empty($folders)) {
         bx_admin_error("You must select a language to edit.");
     }//end if (empty($folders))
     else {
            ?>
            <table width="100%" cellspacing="0" cellpadding="2" border="0" class="tedit">
            <tr>
                 <td align="center"><b>Edit <?php echo $folders;?> language files</b></td>
            </tr>
            <tr>
               <td>
               <TABLE border="0" cellpadding="4" cellspacing="0" width="100%" class="edit">
               <tr>
                   <td colspan="4"><?php header_nav($todo, $folders);?></td>
               </tr>
               <tr>
                   <td colspan="3"><br></td>
               </tr>
                <tr>
                   <td valign="top" colspan="3"><b><?php echo TEXT_SELECT_EDIT_CCTYPE_OPTIONS;?>:</b></td>
               </tr>
               <?php
               $i=1;
               while (${TEXT_CCTYPE_OPT.$i}) {
                    echo "<form method=\"post\" action=\"".HTTP_SERVER_ADMIN.FILENAME_ADMIN_EDIT_LANGUAGE."\"><input type=\"hidden\" name=\"todo\" value=\"savecctype\"><input type=\"hidden\" name=\"lng\" value=\"".$folders."\"><tr><td align=\"right\" width=\"70%\"><input type=\"hidden\" name=\"cctypeid\" value=\"".$i."\"><input type=\"text\" name=\"cctype\" value=\"".${TEXT_CCTYPE_OPT.$i}."\" size=\"30\"></td><td align=\"right\"><input type=\"submit\" name=\"edit\" value=\"Update\"></td></form>";
                    echo "<form method=\"post\" action=\"".HTTP_SERVER_ADMIN.FILENAME_ADMIN_EDIT_LANGUAGE."\"><input type=\"hidden\" name=\"todo\" value=\"delcctype\"><input type=\"hidden\" name=\"lng\" value=\"".$folders."\"><td align=\"left\"><input type=\"hidden\" name=\"cctypeid\" value=\"".$i."\"><input type=\"submit\" name=\"edit\" value=\"Delete\" onClick=\"return confirm('".TEXT_CONFIRM_CCTYPE_DELETE."');\"></td></tr></form>";
                    $i++;
               }
               echo "<form method=\"post\" action=\"".HTTP_SERVER_ADMIN.FILENAME_ADMIN_EDIT_LANGUAGE."\"><input type=\"hidden\" name=\"todo\" value=\"savecctype\"><input type=\"hidden\" name=\"lng\" value=\"".$folders."\"><tr><td align=\"right\" width=\"70%\"><input type=\"hidden\" name=\"cctypeid\" value=\"0\"><input type=\"text\" name=\"cctype\" value=\"\" size=\"30\"></td><td align=\"center\" colspan=\"2\"><input type=\"submit\" name=\"edit\" value=\"  Add  \"></td></tr></form>";
               ?>
                 <tr>
                   <td valign="top" colspan="3"><b><?php echo TEXT_SELECT_EDIT_PAYMENT_OPTIONS;?>:</b></td>
               </tr>
               <?php
               $i=1;  
               while (${TEXT_PAYMENT_OPT.$i}) {
                    echo "<form method=\"post\" action=\"".HTTP_SERVER_ADMIN.FILENAME_ADMIN_EDIT_LANGUAGE."\" style=\"margin-top: 0px; margin-bottom: 0px;\"><input type=\"hidden\" name=\"todo\" value=\"savepayment\"><input type=\"hidden\" name=\"lng\" value=\"".$folders."\"><tr><td align=\"right\" width=\"70%\"><input type=\"hidden\" name=\"paymentid\" value=\"".$i."\"><input type=\"text\" name=\"payment\" value=\"".${TEXT_PAYMENT_OPT.$i}."\" size=\"".(strlen(${TEXT_PAYMENT_OPT.$i})+5)."\"></td><td align=\"center\" colspan=\"2\"><input type=\"submit\" name=\"edit\" value=\"Update\"></td></form>";
                    $i++;
               }
               ?>
               <tr>
                   <td valign="top" colspan="3"><b><?php echo TEXT_SELECT_EDIT_CHARSET_OPTIONS;?>:</b></td>
               </tr>
               <tr>
                   <td valign="top" colspan="3" class="smalltext"><?php echo TEXT_SELECT_EDIT_CHARSET_NOTE;?></td>
               </tr>
               <?php
                    echo "<form method=\"post\" action=\"".HTTP_SERVER_ADMIN.FILENAME_ADMIN_EDIT_LANGUAGE."\" style=\"margin-top: 0px; margin-bottom: 0px;\"><input type=\"hidden\" name=\"todo\" value=\"savecharset\"><input type=\"hidden\" name=\"lng\" value=\"".$folders."\"><tr><td align=\"right\" width=\"70%\"><input type=\"text\" name=\"charset\" value=\"".$CHARSET_OPTION."\" size=\"".(strlen($CHARSET_OPTION)+5)."\"></td><td align=\"center\" colspan=\"2\"><input type=\"submit\" name=\"edit\" value=\"Update\"></td></form>";
               ?>
               <tr>
                   <td valign="top" colspan="3"><b><?php echo TEXT_SELECT_EDIT_DATE_FORMAT;?>:</b></td>
               </tr>
               <tr>
                   <td valign="top" colspan="3" class="smalltext"><?php echo TEXT_SELECT_EDIT_DATE_NOTE;?></td>
               </tr>
               <?php
                    echo "<form method=\"post\" action=\"".HTTP_SERVER_ADMIN.FILENAME_ADMIN_EDIT_LANGUAGE."\" style=\"margin-top: 0px; margin-bottom: 0px;\"><input type=\"hidden\" name=\"todo\" value=\"savedformat\"><input type=\"hidden\" name=\"lng\" value=\"".$folders."\"><tr><td align=\"right\" width=\"70%\"><input type=\"text\" name=\"dformat\" value=\"".$DATE_FORMAT."\" size=\"".(strlen($DATE_FORMAT)+5)."\"></td><td align=\"center\" colspan=\"2\"><input type=\"submit\" name=\"edit\" value=\"Update\"></td></form>";
               ?>
               <tr>
                   <td valign="top" colspan="3"><b><?php echo TEXT_SELECT_EDIT_PRICE_FORMAT;?>:</b></td>
               </tr>
               <tr>
                   <td valign="top" colspan="3" class="smalltext"><?php echo TEXT_SELECT_EDIT_PRICE_NOTE;?>:</td>
               </tr>
               <?php
                    echo "<form method=\"post\" action=\"".HTTP_SERVER_ADMIN.FILENAME_ADMIN_EDIT_LANGUAGE."\" style=\"margin-top: 0px; margin-bottom: 0px;\"><input type=\"hidden\" name=\"todo\" value=\"savepformat\"><input type=\"hidden\" name=\"lng\" value=\"".$folders."\"><tr><td align=\"right\" width=\"70%\"><input type=\"text\" name=\"pformat\" value=\"".$PRICE_FORMAT."\" size=\"".(strlen($PRICE_FORMAT)+5)."\"></td><td align=\"center\" colspan=\"2\"><input type=\"submit\" name=\"edit\" value=\"Update\"></td></form>";
               ?>
			   <tr>
                   <td valign="top" colspan="3">&nbsp;</td>
               </tr>
               </table>
         </td></tr></table>
         <?php
     }//end else if (empty($folders))
}//end if ($todo == "editoptions")
else if ($todo == "editcateg") {
     if (empty($folders)) {
         bx_admin_error("You must select a language to edit.");
     }//end if (empty($folders))
     else {
            ?>
            <table width="100%" cellspacing="0" cellpadding="2" border="0" class="tedit">
            <tr>
                 <td align="center"><b>Edit Job Categories for <?php echo $folders;?> language</b></td>
            </tr>
            <tr>
               <td>
               <TABLE border="0" cellpadding="4" cellspacing="0" width="100%" class="edit">
               <tr>
                   <td colspan="4"><?php header_nav($todo, $folders);?></td>
               </tr>
               <tr>
                   <td colspan="4"><br></td>
               </tr>
               <tr>
                   <td valign="top" colspan="4"><b><?php echo TEXT_EDIT_LANGUAGE_jobcategories_NOTE;?></b></td>
               </tr>
               <tr>
                   <td valign="top" colspan="4"><b><?php echo TEXT_SELECT_EDIT_LANGUAGE_JOBCATEG;?>:</b></td>
               </tr>
               <?php
                     $prev = "";
                     $row=0;
                     $categ_query=bx_db_query("SELECT * FROM ".$bx_table_prefix."_jobcategories_".$folder_table_lang);
                     SQL_CHECK(0,"SQL Error at ".__FILE__.":".(__LINE__-1));
                     while ($categ_result=bx_db_fetch_array($categ_query)) {
                             $row++;
                             if($prev != "") {
                                   print  "<a href=\"".HTTP_SERVER_ADMIN.FILENAME_ADMIN_EDIT_LANGUAGE."?todo=mvdwn&type=categ&lng=".$folders."&jobcategoryid=".$prev."&downid=".$categ_result['jobcategoryid']."\" onmouseOver=\"window.status='Move Down'; return true;\" onmouseOut=\"window.status=''; return true;\"><img src=\"images/down.gif\" border=\"0\"></a></td><td align=\"right\"><input type=\"submit\" name=\"edit\" value=\"Update\"></td></form>";
                                   echo "<form method=\"post\" action=\"".HTTP_SERVER_ADMIN.FILENAME_ADMIN_EDIT_LANGUAGE."\" style=\"margin-top: 0px; margin-bottom: 0px;\"><input type=\"hidden\" name=\"todo\" value=\"delcateg\"><input type=\"hidden\" name=\"lng\" value=\"".$folders."\"><td align=\"left\"><input type=\"hidden\" name=\"jobcategoryid\" value=\"".$prev."\"><input type=\"submit\" name=\"edit\" value=\"Delete\" onClick=\"return confirm('".TEXT_CONFIRM_JOBCATEGORY_DELETE."');\"></td></tr></form>";
                            } 
                            echo "<form method=\"post\" action=\"".HTTP_SERVER_ADMIN.FILENAME_ADMIN_EDIT_LANGUAGE."\" style=\"margin-top: 0px; margin-bottom: 0px;\"><input type=\"hidden\" name=\"todo\" value=\"savecateg\"><input type=\"hidden\" name=\"lng\" value=\"".$folders."\"><tr><td align=\"right\" width=\"70%\"><B>".$row.".</B>&nbsp;&nbsp;<input type=\"hidden\" name=\"jobcategoryid\" value=\"".$categ_result['jobcategoryid']."\"><input type=\"text\" name=\"jobcategory\" value=\"".bx_js_stripslashes($categ_result['jobcategory'])."\" size=\"30\"></td>";
                            if($prev != "") {
                                 echo "<td align=\"center\" width=\"10%\" nowrap><a href=\"".HTTP_SERVER_ADMIN.FILENAME_ADMIN_EDIT_LANGUAGE."?todo=mvup&type=categ&lng=".$folders."&jobcategoryid=".$categ_result['jobcategoryid']."&upid=".$prev."\" onmouseOver=\"window.status='Move Up'; return true;\" onmouseOut=\"window.status=''; return true;\"><img src=\"images/up.gif\" border=\"0\"></a>&nbsp;";
                            }
                            else {
                                  print "<td align=\"center\" width=\"10%\" nowrap>&nbsp;";
                            }
                            $prev = $categ_result['jobcategoryid'];          
                     }//end while
                     echo "</td><td align=\"right\"><input type=\"submit\" name=\"edit\" value=\"Update\"></td></form>";
                     echo "<form method=\"post\" action=\"".HTTP_SERVER_ADMIN.FILENAME_ADMIN_EDIT_LANGUAGE."\" style=\"margin-top: 0px; margin-bottom: 0px;\"><input type=\"hidden\" name=\"todo\" value=\"delcateg\"><input type=\"hidden\" name=\"lng\" value=\"".$folders."\"><td align=\"left\"><input type=\"hidden\" name=\"jobcategoryid\" value=\"".$prev."\"><input type=\"submit\" name=\"edit\" value=\"Delete\" onClick=\"return confirm('".TEXT_CONFIRM_JOBCATEGORY_DELETE."');\"></td></tr></form>";
                     echo "<form method=\"post\" action=\"".HTTP_SERVER_ADMIN.FILENAME_ADMIN_EDIT_LANGUAGE."\" style=\"margin-top: 0px; margin-bottom: 0px;\"><input type=\"hidden\" name=\"todo\" value=\"savecateg\"><input type=\"hidden\" name=\"lng\" value=\"".$folders."\"><tr><td align=\"right\" width=\"70%\"><input type=\"hidden\" name=\"jobcategoryid\" value=\"0\"><br><input type=\"text\" name=\"jobcategory\" value=\"\" size=\"30\"></td><td>Position:<br><select name=\"pos\">";
                     for($i=0;$i<$row; $i++) {
                         echo "<option value=\"".$i."\">".($i+1)."</option>";
                     }
                     echo "<option value=\"".$i."\" selected>".($i+1)."</option></select>&nbsp;</td><td align=\"center\" colspan=\"2\"><br><input type=\"submit\" name=\"edit\" value=\"  Add  \"></td></tr></form>";
                ?>
                <tr>
                   <td valign="top" colspan="4">&nbsp;</td>
               </tr>
               <tr>
                   <form method="post" action="<?php echo HTTP_SERVER_ADMIN.FILENAME_ADMIN_EDIT_LANGUAGE;?>"><input type="hidden" name="todo" value="ordercateg"><input type="hidden" name="lng" value="<?php echo $folders;?>"><td valign="top" colspan="4">Click here to order Jobcategories Alphabetically:&nbsp;&nbsp;&nbsp;<input type="submit" name="edit" value="  Order  "></td></form>
               </tr>
               <tr>
                   <td valign="top" colspan="4">&nbsp;</td>
               </tr>
               <tr>
                   <form method="post" action="<?php echo HTTP_SERVER_ADMIN.FILENAME_ADMIN_EDIT_LANGUAGE;?>"><input type="hidden" name="todo" value="calcjobcount"><input type="hidden" name="lng" value="<?php echo $folders;?>"><td valign="top" colspan="4">Click here to Calculate Job Count in Jobcategories:&nbsp;&nbsp;&nbsp;<input type="submit" name="edit" value="  Calculate  "></td></form>
               </tr>
               <tr>
                   <td valign="top" colspan="4">&nbsp;</td>
               </tr>
               <tr>
                   <td valign="top" colspan="4">&nbsp;</td>
               </tr>
               <tr>
                   <td valign="top" colspan="4"><b>Quick Job Categories Edit:</b></td>
               </tr>
               <tr>
                   <td valign="top" colspan="4"><b>Every Job Category should be positioned on a single line!</b></td>
               </tr>
               <tr>
                   <td valign="top" colspan="4"><b>Like in the example below:</b><b><br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;JobCategory1<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Jobcategory2<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;JobCategory3<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Etc..</b></td>
               </tr>
               <tr>
                   <td colspan="3"><font color="#FF0000"><b>Very Important:</b> Every order change or delete or addition will be reflected also in the other languages available. If you want to translate the jobcategories without changing the order please check the "Translate Mode" below.</td>
               </tr>
               <form method="post" action="<?php echo HTTP_SERVER_ADMIN.FILENAME_ADMIN_EDIT_LANGUAGE;?>" name="savebulk">
               <input type="hidden" name="todo" value="savebulkcateg">
               <input type="hidden" name="lng" value="<?php echo $folders;?>">
                <tr>
                   <td width="100%" align="center" colspan="4"><table width="100%" cellpadding="0" cellspacing="2" border="0" align="center"><tr>
                   <td valign="top" width="30%">&nbsp;&nbsp;<b>Jobcategory List:</b></td>
                   <td valign="top" width="70%" align="left" colspan="2"><textarea name="jobcategoryids" rows="20" cols="50"><?php
                     $categ_query=bx_db_query("SELECT * FROM ".$bx_table_prefix."_jobcategories_".$folder_table_lang);
                     SQL_CHECK(0,"SQL Error at ".__FILE__.":".(__LINE__-1));
                     $row=0;
                     while ($categ_result=bx_db_fetch_array($categ_query)) {
                             if($row!=0) {
                                 echo "\n";
                             }
                             echo $categ_result['jobcategory'];
                             $row++;
                     }
                ?></textarea></td></tr>
                <tr>
                   <td>&nbsp;</td>
                   <td><input type="checkbox" name="translate" value="yes" class="radio"<?php echo ($folders==DEFAULT_LANGUAGE)?"":" checked";?>>&nbsp;<b>Translating Mode</b></td>
               </tr>
               </table></td>
               </tr>
               <tr>
                   <td valign="top" colspan="3" align="center">&nbsp;<input type="submit" name="go" value="Update" onClick="if(!document.savebulk.translate.checked){ return confirm('IMPORTANT!!!\nYou are NOT in Translate Mode\n All the changes you have made will be reflected in all the languages available!\nAre you sure you want this?');}"></td>
               </tr>
               </form>
               <tr>
                   <td valign="top" colspan="4">&nbsp;</td>
               </tr>
               </table>
         </td></tr></table>
         <?php
     }//end else if (empty($folders))
}//end if ($todo == "editcateg")
else if ($todo == "editlocation") {
     if (empty($folders)) {
         bx_admin_error("You must select a language to edit.");
     }//end if (empty($folders))
     else {
            ?>
            <table width="100%" cellspacing="0" cellpadding="2" border="0" class="tedit">
            <tr>
                 <td align="center"><b>Edit job location for <?php echo $folders;?> language</b></td>
            </tr>
            <tr>
               <td>
               <TABLE border="0" cellpadding="4" cellspacing="0" width="100%" class="edit">
               <tr>
                   <td colspan="4"><?php header_nav($todo, $folders);?></td>
               </tr>
               <tr>
                   <td colspan="4"><br></td>
               </tr>
               <tr>
                   <td valign="top" colspan="4"><b><?php echo TEXT_EDIT_LANGUAGE_LOCATION_NOTE;?></b></td>
               </tr>
               <tr>
                   <td valign="top" colspan="4"><b><?php echo TEXT_SELECT_EDIT_LANGUAGE_LOCATION;?>:</b></td>
               </tr>
               <?php
                     $prev = "";
                     $row = 0;
                     $location_query=bx_db_query("SELECT * FROM ".$bx_table_prefix."_locations_".$folder_table_lang);
                     SQL_CHECK(0,"SQL Error at ".__FILE__.":".(__LINE__-1));
                     while ($location_result=bx_db_fetch_array($location_query)) {
                            $row++;
                            if($prev != "") {
                                   print  "<a href=\"".HTTP_SERVER_ADMIN.FILENAME_ADMIN_EDIT_LANGUAGE."?todo=mvdwn&type=location&lng=".$folders."&locationid=".$prev."&downid=".$location_result['locationid']."\" onmouseOver=\"window.status='Move Down'; return true;\" onmouseOut=\"window.status=''; return true;\"><img src=\"images/down.gif\" border=\"0\"></a></td><td align=\"right\"><input type=\"submit\" name=\"edit\" value=\"Update\"></td></form>";
                                   echo "<form method=\"post\" action=\"".HTTP_SERVER_ADMIN.FILENAME_ADMIN_EDIT_LANGUAGE."\" style=\"margin-top: 0px; margin-bottom: 0px;\"><input type=\"hidden\" name=\"todo\" value=\"dellocation\"><input type=\"hidden\" name=\"lng\" value=\"".$folders."\"><td align=\"left\"><input type=\"hidden\" name=\"locationid\" value=\"".$prev."\"><input type=\"submit\" name=\"edit\" value=\"Delete\" onClick=\"return confirm('".TEXT_CONFIRM_JOBLOCATION_DELETE."');\"></td></tr></form>";
                            }
                            echo "<form method=\"post\" action=\"".HTTP_SERVER_ADMIN.FILENAME_ADMIN_EDIT_LANGUAGE."\" style=\"margin-top: 0px; margin-bottom: 0px;\"><input type=\"hidden\" name=\"todo\" value=\"savelocation\"><input type=\"hidden\" name=\"lng\" value=\"".$folders."\"><tr><td align=\"right\" width=\"70%\"><B>".$row.".</B>&nbsp;&nbsp;<input type=\"hidden\" name=\"locationid\" value=\"".$location_result['locationid']."\"><input type=\"text\" name=\"location\" value=\"".bx_js_stripslashes($location_result['location'])."\" size=\"30\"></td>";
                            if($prev != "") {
                                 echo "<td align=\"center\" width=\"10%\" nowrap><a href=\"".HTTP_SERVER_ADMIN.FILENAME_ADMIN_EDIT_LANGUAGE."?todo=mvup&type=location&lng=".$folders."&locationid=".$location_result['locationid']."&upid=".$prev."\" onmouseOver=\"window.status='Move Up'; return true;\" onmouseOut=\"window.status=''; return true;\"><img src=\"images/up.gif\" border=\"0\"></a>&nbsp;";
                            }
                            else {
                                  print "<td align=\"center\" width=\"10%\" nowrap>&nbsp;";
                            }
                            $prev = $location_result['locationid'];
                     }
                     echo "</td><td align=\"right\"><input type=\"submit\" name=\"edit\" value=\"Update\"></td></form>";
                     echo "<form method=\"post\" action=\"".HTTP_SERVER_ADMIN.FILENAME_ADMIN_EDIT_LANGUAGE."\" style=\"margin-top: 0px; margin-bottom: 0px;\"><input type=\"hidden\" name=\"todo\" value=\"dellocation\"><input type=\"hidden\" name=\"lng\" value=\"".$folders."\"><td align=\"left\"><input type=\"hidden\" name=\"locationid\" value=\"".$prev."\"><input type=\"submit\" name=\"edit\" value=\"Delete\" onClick=\"return confirm('".TEXT_CONFIRM_JOBLOCATION_DELETE."');\"></td></tr></form>";
                     echo "<form method=\"post\" action=\"".HTTP_SERVER_ADMIN.FILENAME_ADMIN_EDIT_LANGUAGE."\" style=\"margin-top: 0px; margin-bottom: 0px;\"><input type=\"hidden\" name=\"todo\" value=\"savelocation\"><input type=\"hidden\" name=\"lng\" value=\"".$folders."\"><tr><td align=\"right\" width=\"70%\"><br><input type=\"hidden\" name=\"locationid\" value=\"0\"><input type=\"text\" name=\"location\" value=\"\" size=\"30\"></td><td>Position:<br><select name=\"pos\">";
                     for($i=0;$i<$row; $i++) {
                         echo "<option value=\"".$i."\">".($i+1)."</option>";
                     }
                     echo "<option value=\"".$i."\" selected>".($i+1)."</option></select>&nbsp;</td><td align=\"center\" colspan=\"2\"><br><input type=\"submit\" name=\"edit\" value=\"  Add  \"></td></tr></form>";
                ?>
                <tr>
                   <td valign="top" colspan="4">&nbsp;</td>
                </tr>
                <tr>
                   <form method="post" action="<?php echo HTTP_SERVER_ADMIN.FILENAME_ADMIN_EDIT_LANGUAGE;?>"><input type="hidden" name="todo" value="orderloc"><input type="hidden" name="lng" value="<?php echo $folders;?>"><td valign="top" colspan="4">Click here to order JobLocations Alphabetically:&nbsp;&nbsp;&nbsp;<input type="submit" name="edit" value="  Order  "></td></form>
                </tr>
                <tr>
                   <td valign="top" colspan="4">&nbsp;</td>
                </tr>
                <tr>
                   <td valign="top" colspan="4">&nbsp;</td>
               </tr>
               <tr>
                   <td valign="top" colspan="4"><b>Quick Job Locations Edit:</b></td>
               </tr>
               <tr>
                   <td valign="top" colspan="4"><b>Every Job Location should be positioned on a single line!</b></td>
               </tr>
               <tr>
                   <td valign="top" colspan="4"><b>Like in the example below:</b><b><br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Location1<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Location2<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Location3<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Etc..</b></td>
               </tr>
               <tr>
                   <td colspan="3"><font color="#FF0000"><b>Very Important:</b> Every order change or delete or addition will be reflected also in the other languages available. If you want to translate the job locations without changing the order please check the "Translate Mode" below.</td>
               </tr>
               <form method="post" action="<?php echo HTTP_SERVER_ADMIN.FILENAME_ADMIN_EDIT_LANGUAGE;?>" name="savebulk">
               <input type="hidden" name="todo" value="savebulklocation">
               <input type="hidden" name="lng" value="<?php echo $folders;?>">
               <tr>
                   <td width="100%" align="center" colspan="4"><table width="100%" cellpadding="0" cellspacing="2" border="0" align="center"><tr>
                   <td valign="top" width="30%">&nbsp;&nbsp;<b>Location List:</b></td>
                   <td valign="top" width="70%" align="left" colspan="2"><textarea name="locationids" rows="20" cols="50"><?php
                     $location_query=bx_db_query("SELECT * FROM ".$bx_table_prefix."_locations_".$folder_table_lang);
                     SQL_CHECK(0,"SQL Error at ".__FILE__.":".(__LINE__-1));
                     $row=0;
                     while ($location_result=bx_db_fetch_array($location_query)) {
                             if($row!=0) {
                                 echo "\n";
                             }
                             echo $location_result['location'];
                             $row++;
                     }
               ?></textarea></td></tr>
               <tr>
                   <td>&nbsp;</td>
                   <td><input type="checkbox" name="translate" value="yes" class="radio"<?php echo ($folders==DEFAULT_LANGUAGE)?"":" checked";?>>&nbsp;<b>Translating Mode</b></td>
               </tr>
               </table></td>
               </tr>
               <tr>
                   <td valign="top" colspan="3" align="center">&nbsp;<input type="submit" name="go" value="Update" onClick="if(!document.savebulk.translate.checked){ return confirm('IMPORTANT!!!\nYou are NOT in Translate Mode\n All the changes you have made will be reflected in all the languages available!\nAre you sure you want this?');}"></td>
               </tr>
               </form>
               <tr>
                   <td valign="top" colspan="4">&nbsp;</td>
               </tr>
                </table>
         </td></tr></table>
         <?php
     }//end else if (empty($folders))
}//end if ($todo == "editlocation")
else if ($todo == "edittypes") {
     if (empty($folders)) {
         bx_admin_error("You must select a language to edit.");
     }//end if (empty($folders))
     else {
            ?>
            <table width="100%" cellspacing="0" cellpadding="2" border="0" class="tedit">
            <tr>
                 <td align="center"><b>Edit <?php echo $folders;?> language files</b></td>
            </tr>
            <tr>
               <td>
               <TABLE border="0" cellpadding="4" cellspacing="0" width="100%" class="edit">
               <tr>
                   <td colspan="4"><?php header_nav($todo, $folders);?></td>
               </tr>
               <tr>
                   <td colspan="4"><br></td>
               </tr>
               <tr>
                   <td valign="top" colspan="4"><b><?php echo TEXT_EDIT_LANGUAGE_TYPE_NOTE;?></b></td>
               </tr>
               <tr>
                   <td valign="top" colspan="4"><b><?php echo TEXT_SELECT_EDIT_LANGUAGE_TYPE;?>:</b></td>
               </tr>
               <?php
                     $prev = "";
                     $row = 0;
                     $type_query=bx_db_query("SELECT * FROM ".$bx_table_prefix."_jobtypes_".$folder_table_lang);
                     SQL_CHECK(0,"SQL Error at ".__FILE__.":".(__LINE__-1));
                     while ($type_result=bx_db_fetch_array($type_query)) {
                            $row++; 
                            if($prev != "") {
                                   print  "<a href=\"".HTTP_SERVER_ADMIN.FILENAME_ADMIN_EDIT_LANGUAGE."?todo=mvdwn&type=jobtype&lng=".$folders."&jobtypeid=".$prev."&downid=".$type_result['jobtypeid']."\" onmouseOver=\"window.status='Move Down'; return true;\" onmouseOut=\"window.status=''; return true;\"><img src=\"images/down.gif\" border=\"0\"></a></td><td align=\"right\"><input type=\"submit\" name=\"edit\" value=\"Update\"></td></form>";
                                   echo "<form method=\"post\" action=\"".HTTP_SERVER_ADMIN.FILENAME_ADMIN_EDIT_LANGUAGE."\" style=\"margin-top: 0px; margin-bottom: 0px;\"><input type=\"hidden\" name=\"todo\" value=\"deltypes\"><input type=\"hidden\" name=\"lng\" value=\"".$folders."\"><td align=\"left\"><input type=\"hidden\" name=\"jobtypeid\" value=\"".$prev."\"><input type=\"submit\" name=\"edit\" value=\"Delete\" onClick=\"return confirm('".TEXT_CONFIRM_JOBTYPE_DELETE."');\"></td></tr></form>";
                            } 
                            echo "<form method=\"post\" action=\"".HTTP_SERVER_ADMIN.FILENAME_ADMIN_EDIT_LANGUAGE."\" style=\"margin-top: 0px; margin-bottom: 0px;\"><input type=\"hidden\" name=\"todo\" value=\"savetypes\"><input type=\"hidden\" name=\"lng\" value=\"".$folders."\"><tr><td align=\"right\" width=\"70%\"><B>".$row.".</B>&nbsp;&nbsp;<input type=\"hidden\" name=\"jobtypeid\" value=\"".$type_result['jobtypeid']."\"><input type=\"text\" size=\"30\" name=\"jobtype\" value=\"".bx_js_stripslashes($type_result['jobtype'])."\"></td>";
                            if($prev != "") {
                                 echo "<td align=\"center\" width=\"10%\" nowrap><a href=\"".HTTP_SERVER_ADMIN.FILENAME_ADMIN_EDIT_LANGUAGE."?todo=mvup&type=jobtype&lng=".$folders."&jobtypeid=".$type_result['jobtypeid']."&upid=".$prev."\" onmouseOver=\"window.status='Move Up'; return true;\" onmouseOut=\"window.status=''; return true;\"><img src=\"images/up.gif\" border=\"0\"></a>&nbsp;";
                            }
                            else {
                                  print "<td align=\"center\" width=\"10%\" nowrap>&nbsp;";
                            }
                            $prev = $type_result['jobtypeid'];
                     }//end while
                     echo "</td><td align=\"right\"><input type=\"submit\" name=\"edit\" value=\"Update\"></td></form>";
                     echo "<form method=\"post\" action=\"".HTTP_SERVER_ADMIN.FILENAME_ADMIN_EDIT_LANGUAGE."\" style=\"margin-top: 0px; margin-bottom: 0px;\"><input type=\"hidden\" name=\"todo\" value=\"deltypes\"><input type=\"hidden\" name=\"lng\" value=\"".$folders."\"><td align=\"left\"><input type=\"hidden\" name=\"jobtypeid\" value=\"".$prev."\"><input type=\"submit\" name=\"edit\" value=\"Delete\" onClick=\"return confirm('".TEXT_CONFIRM_JOBTYPE_DELETE."');\"></td></tr></form>";
                     echo "<form method=\"post\" action=\"".HTTP_SERVER_ADMIN.FILENAME_ADMIN_EDIT_LANGUAGE."\" style=\"margin-top: 0px; margin-bottom: 0px;\"><input type=\"hidden\" name=\"todo\" value=\"savetypes\"><input type=\"hidden\" name=\"lng\" value=\"".$folders."\"><tr><td align=\"right\" width=\"70%\"><br><input type=\"hidden\" name=\"jobtypeid\" value=\"0\"><input type=\"text\" size=\"30\" name=\"jobtype\" value=\"\"></td><td>Position:<br><select name=\"pos\">";
                     for($i=0;$i<$row; $i++) {
                         echo "<option value=\"".$i."\">".($i+1)."</option>";
                     }
                     echo "<option value=\"".$i."\" selected>".($i+1)."</option></select>&nbsp;</td><td align=\"center\" colspan=\"2\"><input type=\"submit\" name=\"edit\" value=\"  Add  \"></td></tr></form>";
                ?>
                <tr>
                   <td valign="top" colspan="4">&nbsp;</td>
               </tr>
               </table>
         </td></tr></table>
         <?php
     }//end else if (empty($folders))
}//end if ($todo == "edittypes")
else {
?>
<form method="post" action="<?php echo HTTP_SERVER_ADMIN.FILENAME_ADMIN_EDIT_LANGUAGE;?>" name="editlng" onSubmit="return check_form_editlng();">
<input type="hidden" name="todo" value="editlng">
<table width="100%" cellspacing="0" cellpadding="2" border="0" class="tedit">
<tr>
     <td align="center"><b>Edit language</b></td>
 </tr>
 <tr>
   <td>
<TABLE border="0" cellpadding="4" cellspacing="0" width="100%" class="edit">
<tr>
    <td colspan="2"><br></td>
</tr>
<tr>
        <td valign="top" width="70%"><b><?php echo TEXT_SELECT_EDIT_LANGUAGE;?>:</b></td><td valign="top">
<?php
  $dirs = getFolders(DIR_LANGUAGES);
  if(count($dirs) == 1) {
          refresh(HTTP_SERVER_ADMIN.FILENAME_ADMIN_EDIT_LANGUAGE."?todo=editlng&folders=".$dirs[0]);
  }
  for ($i=0; $i<count($dirs); $i++) {
       if (file_exists(DIR_LANGUAGES.$dirs[$i].".php")) {
              echo "<input type=\"radio\" name=\"folders\" value=\"".$dirs[$i]."\" class=\"radio\">".$dirs[$i]."<br>";
       }
  }
?>
</td></tr>
<tr>
        <td colspan="2" align="center"><br><input type="submit" name="edit" value="Edit Language"></td>
</tr>
</table>

</td></tr></table>
</form>
<?php
}
?>