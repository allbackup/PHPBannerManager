<?php include(DIR_LANGUAGES.$language."/".FILENAME_ADMIN_FORM);?>
<?php
$error_title = "restoring database";
if ($HTTP_POST_VARS['todo']=="upload") {
    if(ADMIN_SAFE_MODE == "yes") {
        bx_admin_error(TEXT_SAFE_MODE_ALERT);
    }//end if ADMIN_SAFE_MODE == yes
    elseif(ADMIN_BX_DEMO == "yes") {
        $error_title = "restoring database";
        bx_admin_error(TEXT_DEMO_MODE_ALERT);
    }//end if ADMIN_BX_DEMO == yes
    else {
        @set_time_limit(0);
        
                function split_sql($sql)
                {
                    $sql = trim($sql);
                    $sql = ereg_replace("#[^\n]*\n", "", $sql);
                    $buffer = array();
                    $ret = array();
                    $in_string = false;
                
                    for($i=0; $i<strlen($sql)-1; $i++)
                    {
                         if($sql[$i] == ";" && !$in_string)
                        {
                            $ret[] = substr($sql, 0, $i);
                            $sql = substr($sql, $i + 1);
                            $i = 0;
                        }
                
                        if($in_string && ($sql[$i] == $in_string) && $buffer[0] != "\\")
                        {
                             $in_string = false;
                        }
                        elseif(!$in_string && ($sql[$i] == "\"" || $sql[$i] == "'") && (!isset($buffer[0]) || $buffer[0] != "\\"))
                        {
                             $in_string = $sql[$i];
                        }
                        if(isset($buffer[1]))
                        {
                            $buffer[0] = $buffer[1];
                        }
                        $buffer[1] = $sql[$i];
                     }
                
                    if(!empty($sql))
                    {
                        $ret[] = $sql;
                    }
                
                    return($ret);
                }
        if(!empty($HTTP_POST_FILES['restore_file']['tmp_name']) && $HTTP_POST_FILES['restore_file']['tmp_name'] != "none" && ereg("^php[0-9A-Za-z_.-]+$", basename($HTTP_POST_FILES['restore_file']['tmp_name'])))
        {
            $sql_query = fread(fopen($HTTP_POST_FILES['restore_file']['tmp_name'], "r"), filesize($HTTP_POST_FILES['restore_file']['tmp_name']));
        }
        else {
              //echo "<center><b>Can read the uploaded file, or invalid name...Please try again...</b></center>";
	          bx_admin_error("<center><b>Can read the uploaded file, or invalid name...Please try again...</b></center>");
			  bx_exit();
        }
        $pieces=explode("#%%\n",$sql_query);
        if (count($pieces) == 1 && !empty($pieces[0])) {
            $sql_query = eregi_replace(";$","",trim($pieces[0]));
            $result = mysql_db_query (DB_DATABASE, trim($pieces[0])) or die("Unable to execute query: ".$pieces[0]);
            bx_exit();
           }
        
        for ($i=0; $i<count($pieces); $i++)
        {
            $pieces[$i] = eregi_replace(";$","",trim($pieces[$i]));
            if(!empty($pieces[$i]) && $pieces[$i] != "#")
            {
                $result = mysql_db_query (DB_DATABASE, $pieces[$i]) or die("Unable to execute query: ".$pieces[$i]);
            }
        }
        
        $sql_query = stripslashes($sql_query);
        ?>
        <table width="100%" cellspacing="0" cellpadding="2" class="tedit">
        <tr>
            <td align="center"><b>Backup database</b></td>
        </tr>
        <tr>
          <td><TABLE border="0" cellpadding="4" cellspacing="0" width="100%" class="edit">
        <tr>
                <td align="center"><b>Successful database restore...</b></td>
        </tr>
        <tr>
                <td align="center"><a href="<?php echo HTTP_SERVER_ADMIN.FILENAME_INDEX;?>">Home</a></td>
        </tr>
        </table>
        </td></tr>
        </table>
        <?php
     }   
} //end if ($todo == "upload")
else {
?>
<form method="post" enctype="multipart/form-data" action="<?php echo HTTP_SERVER_ADMIN.FILENAME_ADMIN_RESTORE_DB;?>" name="upload">
<input type="hidden" name="todo" value="upload">
<table width="100%" cellspacing="0" cellpadding="2" border="0" class="tedit">
 <tr>
     <td align="center"><b>Restore database</b></td>
 </tr>
 <tr>
   <td><TABLE border="0" cellpadding="4" cellspacing="0" width="100%" class="edit">
<tr>
        <td align="right"><b><?php echo TEXT_UPLOAD_BACKUP_FILE;?>:</b>  <input type="file" name="restore_file"></td>
</tr>
<tr>
        <td align="right"><input type="submit" name="save" value="Upload"></td>
</tr>
</table>

</td></tr></table>
</form>
<?php
} //end else if ($todo=="upload")
?>
