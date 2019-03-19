<?php include(DIR_LANGUAGES.$language."/".FILENAME_ADMIN_FORM);?>
<?php
if ($HTTP_POST_VARS['todo']=="save") {
      if(ADMIN_SAFE_MODE == "yes") {
            $error_title = "updateing Site settings!";
            bx_admin_error(TEXT_SAFE_MODE_ALERT);
      }//end if ADMIN_SAFE_MODE == yes
      else {
        $towrite = '';
        for ($i=0;$i<count($HTTP_POST_VARS['inputs']) ;$i++ ) {
            if ($HTTP_POST_VARS[$HTTP_POST_VARS['inputs'][$i]]) {
                $HTTP_POST_VARS[$HTTP_POST_VARS['inputs'][$i]] = stripslashes($HTTP_POST_VARS[$HTTP_POST_VARS['inputs'][$i]]);
                if (eregi(".*'.*", $HTTP_POST_VARS[$HTTP_POST_VARS['inputs'][$i]], $regs)) {
                    $HTTP_POST_VARS[$HTTP_POST_VARS['inputs'][$i]] = eregi_replace("'", "\\'", $HTTP_POST_VARS[$HTTP_POST_VARS['inputs'][$i]]);
                }
                $towrite .= "define('".$HTTP_POST_VARS['inputs'][$i]."','".preg_replace("/(\015\012)|(\015)|(\012)/","'.\"\\n\".'",$HTTP_POST_VARS[$HTTP_POST_VARS['inputs'][$i]])."');\n";
            }
            else {
                if ( is_string($HTTP_POST_VARS[$HTTP_POST_VARS['inputs'][$i]])) {
                    $HTTP_POST_VARS[$HTTP_POST_VARS['inputs'][$i]] = stripslashes($HTTP_POST_VARS[$HTTP_POST_VARS['inputs'][$i]]);
                    if (eregi(".*'.*", $HTTP_POST_VARS[$HTTP_POST_VARS['inputs'][$i]], $regs)) 	{
                        $HTTP_POST_VARS[$HTTP_POST_VARS['inputs'][$i]] = eregi_replace("'", "\\'", $HTTP_POST_VARS[$HTTP_POST_VARS['inputs'][$i]]);
                    }
                $towrite .= "define('".$HTTP_POST_VARS['inputs'][$i]."','".$HTTP_POST_VARS[$HTTP_POST_VARS['inputs'][$i]]."');\n";
                }
                else {
                    $towrite .= "".stripslashes($HTTP_POST_VARS['inputs'][$i])."\n";
                }
            }
        }
        $fp=fopen(DIR_SERVER_ROOT."application_settings.php","w");
        fwrite($fp,"<?php\n");
        fwrite($fp, eregi_replace("\n$","",$towrite));
        fwrite($fp,"\n?>");
        fclose($fp);
        ?>
    <table width="100%" cellspacing="0" cellpadding="2" border="0" class="tedit">
     <tr>
         <td align="center"><b>Script settings</b></td>
     </tr>
     <tr>
       <td><TABLE border="0" cellpadding="4" cellspacing="0" width="100%" class="edit">
                <tr>
                    <td align="center"><b>Successfull update.</b></td>
                </tr>
                <tr>
                    <td align="left" nowrap><a href="<?php echo HTTP_SERVER_ADMIN.FILENAME_INDEX;?>" class="settings">&#171;Admin Home&#187;</a>&nbsp;<a href="<?php echo HTTP_SERVER_ADMIN.FILENAME_ADMIN_SETTINGS;?>?todo=show&t=<?php echo time();?>" class="settings">&#171;Script Settings Home&#187;</a></td>
                </tr>
         </table>
      </td>
     </tr>
    </table>
   <?php
   }
}
else {
$fp=fopen(DIR_SERVER_ROOT."application_settings.php","r");
?>
<form method="post" action="<?php echo HTTP_SERVER_ADMIN.FILENAME_ADMIN_SETTINGS;?>" name="settings">
<input type="hidden" name="todo" value="save">
<table width="100%" cellspacing="0" cellpadding="2" border="0" class="tedit">
<tr>
     <td align="center"><b>Script Settings</b></td>
 </tr>
 <tr>
   <td><TABLE border="0" cellpadding="4" cellspacing="0" width="100%" align="center" class="edit">
<?php
include("phpjob_settings.cfg.php");
$i=0;
while (!feof($fp)) {
   $str=trim(fgets($fp, 20000));
   if (!empty($str) && ($str != "\r\n") && ($str != "\n") && ($str != "\r")) {
        for ( $j=0 ; $j<sizeof($fields[$i]["comment"])  ;$j++  ) {
            echo "<tr><td colspan=\"2\" align=\"center\"><font size=\"2\" face=\"arial\" color=\"#FF0000\"><b>".ucfirst($fields[$i]["comment"][$j])."</b></font></td></tr>";
        }
        $field_name = $fields[$i]["name"];
        if (eregi("^define\(['](.*)['|.?],['|.?| ](.*)\)",htmlspecialchars($str),$regexp)) {
            echo "<tr>";
				if ($field_name) {
					if (eregi("\(radio (.*)\)(.*)",$field_name,$regcomm)) {
                        echo "<td align=\"right\">".$regcomm[2]." </td>";
                        $radio = true;
                    }
                    else {
                        echo "<td align=\"right\" valign=\"top\">".$field_name." = </td>";    
                    }
				}
				else {
                       echo "<td align=\"right\" nowrap valign=\"top\">".$regexp[1]." = </td>";	
				}
                if ($radio) {
                            echo "<td>";
                            $radio_values = split(",",trim($regcomm[1]));
                            for ($j=0;$j<sizeof($radio_values);$j++) {
                                if (eregi_replace("'","",$regexp[2]) == $radio_values[$j]) {
                                    $checked = " checked";
                                }
                                else {
                                    $checked = "";
                                }
                                echo "<a name=\"".$regexp[1]."\"></a><input type=\"radio\" class=\"radio\" name=\"".$regexp[1]."\" value=\"".$radio_values[$j]."\"".$checked."><b>".$radio_values[$j]."</b>&nbsp;";
                            }
                            $radio = false;
                }
                else {
                    if (strlen($regexp[2])<30) {
                            echo "<td valign=\"top\"><a name=\"".$regexp[1]."\"></a><input type=\"text\" name=\"".$regexp[1]."\" value=\"".stripslashes(eregi_replace("'$","",$regexp[2]))."\" size=\"".(strlen($regexp[2])+5)."\">";
                    }
                    else {
                            echo "<td><a name=\"".$regexp[1]."\"></a><textarea name=\"".$regexp[1]."\" cols=\"50\" rows=\"8\">".eregi_replace('\.&quot;\\\\n&quot;\.',"\n",eregi_replace("'","",eregi_replace("\\\\'","&#039",$regexp[2])))."</textarea>";
                    }
                }
				echo "<input type=\"hidden\" name=\"inputs[]\" value=\"".$regexp[1]."\"></td>";
                echo "</tr>";
        }
		else {
			if ($str == "<?php" || $str == "?>") {
			}
			else {
				if (strlen($str) < 30) {
                        echo "<tr><td colspan=\"2\" align=\"center\"><input type=\"text\" name=\"inputs[]\" value=\"".htmlspecialchars($str)."\" size=\"80\"></td></tr>";
				}
				else {
                        echo "<tr><td colspan=\"2\" align=\"center\"><textarea name=\"inputs[]\" rows=\"8\" cols=\"50\">".htmlspecialchars($str)."</textarea></td></tr>";
				}
			}
		}
   }
   $i++;
}
fclose($fp);
?>
<tr>
        <td colspan="3" align="right"><input type="submit" name="save" value="<?php echo TEXT_BUTTON_SAVE;?>"></td>
</tr>
</table>
</td></tr></table>
</form>
<?php
}
?>