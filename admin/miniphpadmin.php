<?php
if (file_exists('../application_config_file.php')) {
    $config_file = '../application_config_file.php';
}
elseif (file_exists('application_config_file.php')) {
    $config_file = 'application_config_file.php';
}
elseif (file_exists('../config_file.php')) {
    $config_file = '../config_file.php';
}
elseif (file_exists('config_file.php')) {
    $config_file = 'config_file.php';
}
################################
class miniphpmyadmin {
        var $db="";
        var $table="";
        var $query ="" ;
        var $numrows = 0;
        function miniphpmyadmin($database_name) {
                $this->db = $database_name;
        }
        function print_result($dt_result) {
                global $HTTP_GET_VARS,$HTTP_POST_VARS;

                $primary = false;
                if(!empty($HTTP_GET_VARS['table']) && !empty($this->db))
                {
                        $result = mysql_query("SELECT COUNT(*) as total FROM ".$HTTP_GET_VARS['table']) or die();
                        $row = mysql_fetch_array($result);
                        $total = $row["total"];
                }
                if(!isset($HTTP_POST_VARS['pos'])) {
                        $pos = 0;
                }
                else {
                        $pos = $HTTP_POST_VARS['pos'];
                }
                $pos_next = $pos + 25;
                $pos_prev = $pos - 25;
                if(isset($total) && $total>1)
                {
                        $lastShownRec = $pos_next  - 1;
                        $this->show_message($this->query."<br>Showing Records $pos - $lastShownRec  ($total Total)");
                }
                else
                {
                        $this->show_message($this->query);
                }
                ?>
                <?php
                $field = mysql_fetch_field($dt_result);
                $table = $field->table;
                mysql_field_seek($dt_result, 0);
                $this->show_table_navigation($pos_next, $pos_prev, $table);
                ?>
                <table border="0">
                <tr>
                <?php
                while($field = mysql_fetch_field($dt_result)) {
                        if(@mysql_num_rows($dt_result)>1)
                        {
                                echo "<th>";
                                if(!eregi("SHOW VARIABLES|SHOW PROCESSLIST|SHOW STATUS", $this->query))
                                echo "<A HREF=\"miniphpadmin.php?db=$this->db&query=".urlencode($this->query." order by $field->name asc")."&table=$table\">";
                                echo $field->name;
                                if(!eregi("SHOW VARIABLES|SHOW PROCESSLIST|SHOW STATUS", $this->query))
                                echo "</a>";
                                echo "</th>\n";
                        }
                        else
                        {
                                echo "<th>$field->name</th>";
                        }
                        $table = $field->table;
                }
                echo "</tr>\n";
                $foo = 0;
                while(($row = mysql_fetch_row($dt_result)) && $foo<$pos_next)
                {
                        if ($foo<$pos) {
                             $foo++;
                             continue;
                        }
                        $primary_key = "";
                        $uva_nonprimary_condition = "";
                        $bgcolor = "#F0F0F0";
                        $foo % 2  ? 0: $bgcolor = "#DEDEDE";
                        echo "<tr bgcolor=$bgcolor>";
                        for($i=0; $i<mysql_num_fields($dt_result); $i++)
                        {
                                if(!isset($row[$i])) $row[$i] = '';
                                $primary = mysql_fetch_field($dt_result,$i);
                                if($primary->numeric == 1)
                                {
                                        echo "<td align=right>&nbsp;$row[$i]&nbsp;</td>\n";
                                        if(strtoupper(trim($this->query)) == "SHOW PROCESSLIST" || strtoupper(trim($this->query)) == "SHOW DATABASES")
                                                $Id = $row[$i];
                                }
                                else
                                {
                                        if(strtoupper(trim($this->query)) == "SHOW PROCESSLIST" || strtoupper(trim($this->query)) == "SHOW DATABASES") {
                                                $Id = $row[$i];
                                        }
                                        echo "<td>&nbsp;".htmlspecialchars($row[$i])."&nbsp;</td>\n";
                                }
                                if($primary->primary_key > 0)
                                        $primary_key .= " $primary->name = '".addslashes($row[$i])."' AND";
                                        $uva_nonprimary_condition .= " $primary->name = '".addslashes($row[$i])."' AND";
                        }
                        if($primary_key) //use differently and include else
                                      $uva_condition = $primary_key;
                        else
                                      $uva_condition = $uva_nonprimary_condition;
                        $uva_condition = urlencode(ereg_replace("AND$", "", $uva_condition));

                        if (strtoupper(trim($this->query)) == "SHOW DATABASES") {
                                echo "<td colspan=2><a href=\"miniphpadmin.php?todo=showdb&db=".$Id."\">Show</a></td>";
                        }
                        else {
                                echo "<td><a href=\"miniphpadmin.php?todo=change&primary_key=$uva_condition&query=".urlencode($this->query)."&table=$table\">Edit</a></td>";
                                echo "<td><a href=\"miniphpadmin.php?db=".$arg_db."&query=".urlencode("DELETE FROM $table WHERE ").$uva_condition."&$this->query&goto=sql.php3".urlencode("?$this->query&sql_query=$this->query")."\">Delete</a></td>";
                       }
                        if($this->query == "SHOW PROCESSLIST")
                                        echo "<td align=right><a href='sql.php3?db=mysql&sql_query=".urlencode("KILL $Id")."&goto=main.php3'>KILL</a></td>\n";
                        echo "</tr>\n";
                        $foo++;
                  }
                  echo "</table>\n";
                   $this->show_table_navigation($pos_next, $pos_prev, $table);
           }//end print_result();

    function print_db($arg_db) {
        $tables = mysql_list_tables($arg_db);
        $num_tables = @mysql_numrows($tables);
        if($num_tables == 0) {
                echo "No table found in ".$arg_db;
        }
        else {
                $i = 0;
                echo "<table border=0>\n";
                echo "<th>Table</th>";
                echo "<th colspan=6>Action</th>";
                echo "<th>Records</th>";
                while($i < $num_tables)
                {
                        $table = mysql_tablename($tables, $i);
                        $bgcolor = "#F0F0F0";
                        $i % 2  ? 0: $bgcolor = "#DEDEDE";
                        ?>
                        <tr bgcolor="<?php echo $bgcolor;?>">

                        <td class="data"><b><?php echo $table;?></b></td>
                        <td><a href="miniphpadmin.php?db=<?php echo $arg_db;?>&table=<?php echo $table;?>&query=<?php echo urlencode("SELECT * FROM $table");?>">Browse</a></td>
                        <td><a href="miniphpadmin.php?todo=prop&db=<?php echo $arg_db;?>&table=<?php echo $table;?>">Properties</a></td>
                        <td><a href="miniphpadmin.php?db=<?php echo $arg_db;?>&query=<?php echo urlencode("OPTIMIZE TABLE $table");?>">Optimize</a></td>
                        <td><a href="miniphpadmin.php?db=<?php echo $arg_db;?>&query=<?php echo urlencode("ANALYZE TABLE $table");?>">Analyze</a></td>
                        <td><a href="miniphpadmin.php?db=<?php echo $arg_db;?>&query=<?php echo urlencode("DROP TABLE $table");?>" onClick="return confirm('Are you sure you want to Delete <?php echo $table;?> table?');">Drop</a></td>
                        <td><a href="miniphpadmin.php?db=<?php echo $arg_db;?>&query=<?php echo urlencode("DELETE FROM $table");?>" onClick="return confirm('Are you sure you want to Empty <?php echo $table;?> table?');">Empty</a></td>
                        <td align="right">&nbsp;<?php $this->count_records($arg_db,$table);?></td>
                        </tr>
                        <?php
                        $i++;
                }
                echo "</table>\n";
          }
          ?>
          <hr width="60%" align="left">
          <div align="left">
          <ul>
          <form method="post" action="miniphpadmin.php?todo=runsql">
          <input type="hidden" name="db" value="<?php echo $arg_db;?>">
          Run SQL query/queries on database <b><?php echo $arg_db." ";?></b>:<br>
          <textarea name="sql_query" cols="40" rows="10" wrap="VIRTUAL"></textarea><br>
         <input type="submit" name="SQL" value="Go">
         </form>
         <form method="post" action="miniphpadmin.php?todo=runsql" enctype="multipart/form-data">
          <input type="hidden" name="db" value="<?php echo $arg_db;?>">
          <input type="hidden" name="asfile" value="yes">
          Upload file: <input type="file" name="upload_file"> <input type="submit" name="SQL" value="Go"><br>
          Compression: <input type="radio" name="gzipped" value="no" checked>None <input type="radio" name="gzipped" value="yes">Gzipped (<?php echo (@function_exists('gzopen'))?"available":"not available";?>)
          </form>
         <li><form method="post" action="miniphpadmin.php?todo=dump" name="dmp">View dump (schema) of database <b><?php echo $arg_db." ";?></b><br>
         <table>
         <tr>
                <td>
                        <input type="radio" name="what" value="structure" checked>Structure Only
                </td>
                <td>
                        <input type="checkbox" name="drop" value="1">Add "DROP TABLE"
                </td>
                <td colspan="2">
                        <input type="submit" value="Go">
                </td>
         </tr>
         <tr>
                <td>
                        <input type="radio" name="what" value="data">Structure and Data
                </td>
                <td>
                <input type="checkbox" name="asfile" value="sendit" onClick="document.dmp.gzipped[0].checked=!document.dmp.gzipped[0].checked;">Send <input type="radio" name="gzipped" value="no">None <input type="radio" name="gzipped" value="yes">Gzipped (<?php echo (@function_exists('gzencode'))?"available":"not available";?>)
                </td>
         </tr>
         </table>
         <input type="hidden" name="db" value="<?php echo $arg_db;?>">
         </form>
         <li>
         <a href="miniphpadmin.php?db=<?php echo $arg_db;?>&query=<?php echo urlencode("DROP DATABASE $arg_db");?>">Drop Database <b><?php echo $arg_db;?></b></a>
         </ul>
         </div>
        <?php
    }//end print_db
    function table_change() {
        global $HTTP_GET_VARS, $HTTP_POST_VARS;
        $table_def = mysql_query("SHOW FIELDS FROM ".$HTTP_GET_VARS['table']);
        if(isset($HTTP_GET_VARS['primary_key']))
        {
                $HTTP_GET_VARS['primary_key'] = stripslashes($HTTP_GET_VARS['primary_key']);
                $result = mysql_query("SELECT * FROM ".$HTTP_GET_VARS['table']." WHERE ".$HTTP_GET_VARS['primary_key']);
                $row = mysql_fetch_array($result);
        }
        else
        {
                $result = mysql_query("SELECT * FROM ".$HTTP_GET_VARS['table']." LIMIT 1");
        }
        ?>
        <form method="post" action="miniphpadmin.php?todo=replace">
        <input type="hidden" name="db" value="<?php echo $this->db;?>">
        <input type="hidden" name="table" value="<?php echo $HTTP_GET_VARS['table'];?>">
        <input type="hidden" name="query" value="<?php echo $this->get_query();?>">
        <?php
        if(isset($HTTP_GET_VARS['primary_key']))
                echo '<input type="hidden" name="primary_key" value="' . htmlspecialchars($HTTP_GET_VARS['primary_key']) . '">' . "\n";
        ?>
        <table border="0">
        <tr>
        <th>Field</th>
        <th>Type</th>
        <th>Value</th>
        </tr>
        <?php
                for($i=0;$i<mysql_num_rows($table_def);$i++)
                {
                $row_table_def = mysql_fetch_array($table_def);
                $field = $row_table_def["Field"];
                if(($row_table_def['Type']  == "datetime") AND ($row[$field] == ""))
                        $row[$field] = date("Y-m-d H:i:s", time());
                $len = @mysql_field_len($result,$i);

                $bgcolor = "#F0F0F0";
                $i % 2  ? 0: $bgcolor = "#DEDEDE";
                echo "<tr bgcolor=".$bgcolor.">\n";
                echo "<td>$field</td>\n";
                switch (ereg_replace("\\(.*", "", $row_table_def['Type']))
                {
                        case "set":
                        $type = "set";
                        break;
                        case "enum":
                        $type = "enum";
                        break;
                        default:
                        $type = $row_table_def['Type'];
                        break;
                }
                echo "<td>$type</td>\n";
                if(isset($row) && isset($row[$field]))
                {
                        $special_chars = htmlspecialchars($row[$field]);
                        $data = $row[$field];
                }
                else
                {
                        $data = $special_chars = "";
                }

                if(strstr($row_table_def["Type"], "text") || strstr($row_table_def["Type"], "blob"))
                {
                        echo "<td><textarea name=fields[$field] style=\"width: 300px;\" rows=7>$special_chars</textarea></td>\n";
                }
                elseif(strstr($row_table_def["Type"], "enum"))
                {
                        $set = str_replace("enum(", "", $row_table_def["Type"]);
                        $set = ereg_replace("\\)$", "", $set);

                        $set = $this->split_string($set, ",");
                        echo "<td><select name=fields[$field]>\n";
                        echo "<option value=\"\">\n";
                        for($j=0; $j<count($set);$j++)
                        {
                        echo '<option value="'.substr($set[$j], 1, -1).'"';
                        if($data == substr($set[$j], 1, -1) || ($data == "" && substr($set[$j], 1, -1) == $row_table_def["Default"]))
                                echo " selected";
                        echo ">".htmlspecialchars(substr($set[$j], 1, -1))."\n";
                        }
                        echo "</select></td>";
                }
                elseif(strstr($row_table_def["Type"], "set"))
                {
                        $set = str_replace("set(", "", $row_table_def["Type"]);
                        $set = ereg_replace("\)$", "", $set);

                        $set = $this->split_string($set, ",");
                        for($vals = explode(",", $data); list($t, $k) = each($vals);)
                        $vset[$k] = 1;
                        $size = min(4, count($set));
                        echo "<td><input type=\"hidden\" name=\"fields[$field]\" value=\"\$set\$\">";
                        echo "<select name=field_${field}[] size=$size multiple>\n";
                        for($j=0; $j<count($set);$j++)
                        {
                        echo '<option value="'.htmlspecialchars(substr($set[$j], 1, -1)).'"';
                        if($vset[substr($set[$j], 1, -1)])
                                echo " selected";
                        echo ">".htmlspecialchars(substr($set[$j], 1, -1))."\n";
                        }
                        echo "</select></td>";
                }
                else
                {
                        echo "<td><input type=text name=fields[$field] value=\"".$special_chars."\" style=\"width:300px;\" maxlength=$len></td>";
                }
                echo "</tr>\n";
                }
                ?>
                <tr><td colspan="3">&nbsp;</td></tr>
                <tr><td colspan="3" align="right"><input type="submit" value="Save"></td></tr>
                </table>
                </form>
        <?php
    }//end table_change

    function table_replace() {
    global $HTTP_GET_VARS, $HTTP_POST_VARS;
                reset($HTTP_POST_VARS['fields']);
                if(isset($HTTP_POST_VARS['primary_key']))
                {
                        $HTTP_POST_VARS['primary_key'] = stripslashes($HTTP_POST_VARS['primary_key']);
                        $valuelist = '';
                        while(list($key, $val) = each($HTTP_POST_VARS['fields']))
                        {
                                switch (strtolower($val))
                                {
                                        case 'null':
                                                break;
                                        case '$set$':
                                                $f = "field_$key";
                                                $val = "'".($$f?implode(',',$$f):'')."'";
                                                break;
                                        default:
                                                $val = "'$val'";
                                                break;
                                }
                                $valuelist .= "$key = $val, ";
                        }
                        $valuelist = ereg_replace(', $', '', $valuelist);
                        $my_query = "UPDATE ".$HTTP_POST_VARS['table']." SET $valuelist WHERE ".$HTTP_POST_VARS['primary_key'];
                }
                else
                {
                        $fieldlist = '';
                        $valuelist = '';
                        while(list($key, $val) = each($HTTP_POST_VARS['fields']))
                        {
                                $fieldlist .= "$key, ";
                                switch (strtolower($val))
                                {
                                        case 'null':
                                                break;
                                        case '$set$':
                                                $f = "field_$key";
                                                $val = "'".($$f?implode(',',$$f):'')."'";
                                                break;
                                        default:
                                                $val = "'$val'";
                                                break;
                                }
                                $valuelist .= "$val, ";
                        }
                        $fieldlist = ereg_replace(', $', '', $fieldlist);
                        $valuelist = ereg_replace(', $', '', $valuelist);
                        $my_query = "INSERT INTO ".$HTTP_POST_VARS['table']." ($fieldlist) VALUES ($valuelist)";
                }
                $result = mysql_query($my_query);
                if(!$result)
                {
                        echo "<font class=error><b>MYSQL ERROR: ".mysql_error()."</B></font>";
                        echo "<br><font class=normal><b>QUERY: ".$my_query."</B></font>";
                }
               else {
                         print "<html><head><meta http-equiv=\"refresh\" content=\"0; URL=miniphpadmin.php?query=".urlencode($this->query)."\"></head></html>\n";
               }
     }//end table_replace

     function table_property($table){
                $result = mysql_query("SHOW KEYS FROM $table");
                $primary = "";

                while($row = mysql_fetch_array($result))
                if ($row["Key_name"] == "PRIMARY")
                        $primary .= "$row[Column_name], ";

                $result = mysql_query("SHOW FIELDS FROM $table");

                ?>
                <table border=0>
                <TR>
                        <TH>#</TH>
                        <TH>Field</TH>
                        <TH>Type</TH>
                        <TH>Attributes</TH>
                        <TH>Null</TH>
                        <TH>Default</TH>
                        <TH>Extra</TH>
                        <TH COLSPAN=5>Action</TH>
                </TR>
                <?php
                $i=0;

                $aryFields = array();

                while($row= mysql_fetch_array($result))
                {
                $aryFields[] = $row["Field"];
                $bgcolor = "#F0F0F0";
                $i % 2  ? 0: $bgcolor = "#DEDEDE";
                $i++;
                ?>
                        <tr bgcolor="<?php echo $bgcolor;?>">
                        <td><?php echo $i;?></td>
                        <td><?php echo $row["Field"];?>&nbsp;</td>
                        <td>
                <?php
                $Type = stripslashes($row["Type"]);
                $Type = eregi_replace("BINARY", "", $Type);
                $Type = eregi_replace("ZEROFILL", "", $Type);
                $Type = eregi_replace("UNSIGNED", "", $Type);
                echo $Type;
                ?>&nbsp;</td>
                        <td>
                <?php
                $binary   = eregi("BINARY", $row["Type"], $test);
                $unsigned = eregi("UNSIGNED", $row["Type"], $test);
                $zerofill = eregi("ZEROFILL", $row["Type"], $test);
                $strAttribute="";
                if ($binary)
                        $strAttribute="BINARY";
                if ($unsigned)
                        $strAttribute="UNSIGNED";
                if ($zerofill)
                        $strAttribute="UNSIGNED ZEROFILL";
                echo $strAttribute;
                $strAttribute="";
                ?>
                &nbsp;</td>
                <td><?php if ($row["Null"] == "") { echo "No";} else {echo "Yes";}?>&nbsp;</td>
                        <td><?php if(isset($row["Default"])) echo $row["Default"];?>&nbsp;</td>
                        <td><?php echo $row["Extra"];?>&nbsp;</td>
                        <td><a href="miniphpadmin.php?todo=alter&table=<?php echo $table;?>&field=<?php echo $row["Field"];?>">Change</a></td>
                        <td><a href="miniphpadmin.php?query=<?php echo urlencode("ALTER TABLE ".$table." DROP ".$row["Field"]);?>">Drop</a></td>
                        <td><a href="miniphpadmin.php?query=<?php echo urlencode("ALTER TABLE ".$table." DROP PRIMARY KEY, ADD PRIMARY KEY($primary".$row["Field"].")");?>">Primary</a></td>
                        <td><a href="miniphpadmin.php?query=<?php echo urlencode("ALTER TABLE ".$table." ADD INDEX(".$row["Field"].")");?>">Index</a></td>
                        <td><a href="miniphpadmin.php?query=<?php echo urlencode("ALTER TABLE ".$table." ADD UNIQUE(".$row["Field"].")");?>">Unique</a></td>
                        </tr>
                <?php
                }
                ?>
                </table>
                <?php
                $result = mysql_query("SHOW KEYS FROM ".$table) or die(mysql_error());
                if(mysql_num_rows($result)>0)
                {
                ?>
                <br>
                <table border=0>
                <tr>
                <th>Keyname</th>
                <th>Unique</th>
                <th>Field</th>
                <th>Action</th>
                </tr>
                <?php
                for($i=0 ; $i<mysql_num_rows($result); $i++)
                {
                        $row = mysql_fetch_array($result);
                        echo "<tr>";
                        if($row["Key_name"] == "PRIMARY")
                        {
                        $sql_query = urlencode("ALTER TABLE ".$table." DROP PRIMARY KEY");
                        $zero_rows = urlencode($strPrimaryKey." ".$strHasBeenDropped);
                        }
                        else
                        {
                        $sql_query = urlencode("ALTER TABLE ".$table." DROP INDEX ".$row["Key_name"]);
                        $zero_rows = urlencode($strIndex." ".$row["Key_name"]." ".$strHasBeenDropped);
                        }

                        ?>
                        <td><?php echo $row["Key_name"];?></td>
                        <td><?php
                        if($row["Non_unique"]=="0")
                        echo "Yes";
                        else
                        echo "No";
                        ?></td>
                        <td><?php echo $row["Column_name"];?></td>
                        <td><?php echo "<a href=\"miniphpadmin.php?query=$sql_query\">Drop</a>";?></td>
                        <?php
                        echo "</tr>";
                }
                print "</table>\n";
                }
                ?>
                <div align="left">
                <ul>
                <li><a href="miniphpadmin.php?db=<?php echo $this->get_dbname();?>&query=<?php echo urlencode("SELECT * FROM $table");?>">Browse</a>
                <li><a href="miniphpadmin.php?todo=change&db=<?php echo $this->get_dbname();?>&table=<?php echo $table;?>&query=<?php echo urlencode("SELECT * FROM $table");?>">Insert</a>
                <li><form method="post" action="miniphpadmin.php?todo=addfield">
                      <input type="hidden" name="db" value="<?php echo $db;?>">
                      <input type="hidden" name="table" value="<?php echo $table;?>">
                      Add new field:  <input name="num_fields" size=2 maxlength=2 value=1>
                <?php
                echo " ";
                echo " <select name=\"after_field\">\n";
                echo '  <option value="--end--">At the End of the table</option>\n';
                echo '  <option value="--first--">At the Beginning of the table</option>\n';
                while(list ($junk,$fieldname) = each($aryFields)) {
                echo '  <option value="'.$fieldname.'">After '.$fieldname."</option>\n";
                }
                echo " </select>\n";
                ?>
                <input type="submit" value="Go">
                </form>
                <li><form method="post" action="miniphpadmin.php?todo=dump">View dump (schema) of table <b>"<?php echo $table;?>"</b><br>
                <table>
                <tr>
                        <td>
                        <input type="radio" name="what" value="structure" checked>Structure only
                        </td>
                        <td>
                        <input type="checkbox" name="drop" value="1">Add "drop table"
                        </td>
                        <td colspan="3">
                        <input type="submit" value="Go">
                        </td>
                </tr>
                <tr>
                        <td>
                        <input type="radio" name="what" value="data">Structure and data
                        </td>
                        <td>
                        <input type="checkbox" name="asfile" value="sendit">Send <input type="radio" name="gzipped" value="no">None <input type="radio" name="gzipped" value="yes">Gzipped (<?php echo (@function_exists('gzencode'))?"available":"not available";?>)
                        </td>
                </tr>
                </table>

                <input type="hidden" name="db" value="<?php echo $this->get_dbname();?>">
                <input type="hidden" name="tbl" value="<?php echo $table;?>">
                </form>

                </ul>
                </div>
            <?php
     }//end table_property

     function table_alter($table) {
               global $HTTP_GET_VARS, $HTTP_POST_VARS;
               if ($HTTP_GET_VARS['todo']!="addfield") {
                                print "SHOW FIELDS FROM $table LIKE '".$HTTP_GET_VARS['field']."'";
                                $result = mysql_query("SHOW FIELDS FROM $table LIKE '".$HTTP_GET_VARS['field']."'") or die(mysql_error());
                                $HTTP_POST_VARS['num_fields'] = mysql_num_rows($result);
                }
        ?>
                <form method="post" action="miniphpadmin.php?todo=<?php echo $HTTP_GET_VARS['todo'];?>">
                <input type="hidden" name="db" value="<?php echo $this->get_dbname();?>">
                <input type="hidden" name="table" value="<?php echo $table;?>">
                <?php
                if($HTTP_GET_VARS['todo'] == "create")
                {
                ?>
                <input type="hidden" name="reload" value="true">
                <?php
                }
                elseif($HTTP_GET_VARS['todo']  == "addfield")
                {
                echo '<input type="hidden" name="after_field" value="'.$HTTP_POST_VARS['after_field']."\">\n";
                }

                ?>
                <table border=0>
                <tr>
                <th>Field</th>
                <th>Type</th>
                <th>Length/Size</th>
                <th>Attributes</th>
                <th>Null</th>
                <th>Default</th>
                <th>Extra</th>
                </tr>
                <?php
                for($i=0 ; $i<$HTTP_POST_VARS['num_fields']; $i++)
                {
                if(isset($result))
                       $row = mysql_fetch_array($result);
                        $bgcolor = "#F0F0F0";
                $i % 2  ? 0: $bgcolor = "#DEDEDE";
                ?>
                <tr bgcolor="0">
                <td>
                <input type="text" name="field_name[]" size="10" value="<?php if(isset($row) && isset($row["Field"])) echo $row["Field"];?>">
                <input type="hidden" name="field_orig[]" value="<?php if(isset($row) && isset($row["Field"])) echo $row["Field"];?>"></td>
                <td><select name="field_type[]">
                <?php
                $row["Type"] = empty($row["Type"]) ? '' : $row["Type"];
                $Type = stripslashes($row["Type"]);
                $Type = eregi_replace("BINARY", "", $Type);
                $Type = eregi_replace("ZEROFILL", "", $Type);
                $Type = eregi_replace("UNSIGNED", "", $Type);
                $Length = $Type;
                $Type = eregi_replace("\\(.*\\)", "", $Type);
                $Type = chop($Type);
                if(!empty($Type))
                {
                        $Length = eregi_replace("^$Type\(", "", $Length);
                        $Length = eregi_replace("\)$", "", trim($Length));
                }
                $Length = htmlspecialchars(chop($Length));
                if($Length == $Type)
                        $Length = "";
                        ?>
                        <option value="TINYINT"<?php if(strtoupper($Type) == "TINYINT") {echo " selected";}?>>TINYINT</option>
                        <option value="SMALLINT"<?php if(strtoupper($Type) == "SMALLINT") {echo " selected";}?>>SMALLINT</option>
                        <option value="MEDIUMINT"<?php if(strtoupper($Type) == "MEDIUMINT") {echo " selected";}?>>MEDIUMINT</option>
                        <option value="INT"<?php if(strtoupper($Type) == "INT") {echo " selected";}?>>INT</option>
                        <option value="BIGINT"<?php if(strtoupper($Type) == "BIGINT") {echo " selected";}?>>BIGINT</option>
                        <option value="FLOAT"<?php if(strtoupper($Type) == "FLOAT") {echo " selected";}?>>FLOAT</option>
                        <option value="DOUBLE"<?php if(strtoupper($Type) == "DOUBLE") {echo " selected";}?>>DOUBLE</option>
                        <option value="DECIMAL"<?php if(strtoupper($Type) == "DECIMAL") {echo " selected";}?>>DECIMAL</option>
                        <option value="DATE"<?php if(strtoupper($Type) == "DATE") {echo " selected";}?>>DATE</option>
                        <option value="DATETIME"<?php if(strtoupper($Type) == "DATETIME") {echo " selected";}?>>DATETIME</option>
                        <option value="TIMESTAMP"<?php if(strtoupper($Type) == "TIMESTAMP") {echo " selected";}?>>TIMESTAMP</option>
                        <option value="TIME"<?php if(strtoupper($Type) == "TIME") {echo " selected";}?>>TIME</option>
                        <option value="YEAR"<?php if(strtoupper($Type) == "YEAR") {echo " selected";}?>>YEAR</option>
                        <option value="CHAR"<?php if(strtoupper($Type) == "CHAR") {echo " selected";}?>>CHAR</option>
                        <option value="VARCHAR"<?php if(strtoupper($Type) == "VARCHAR") {echo " selected";}?>>VARCHAR</option>
                        <option value="TINYBLOB"<?php if(strtoupper($Type) == "TINYBLOB") {echo " selected";}?>>TINYBLOB</option>
                        <option value="TINYTEXT"<?php if(strtoupper($Type) == "TINYTEXT") {echo " selected";}?>>TINYTEXT</option>
                        <option value="TEXT"<?php if(strtoupper($Type) == "TEXT") {echo " selected";}?>>TEXT</option>
                        <option value="BLOB"<?php if(strtoupper($Type) == "BLOB") {echo " selected";}?>>BLOB</option>
                        <option value="MEDIUMBLOB"<?php if(strtoupper($Type) == "MEDIUMBLOB") {echo " selected";}?>>MEDIUMBLOB</option>
                        <option value="MEDIUMTEXT"<?php if(strtoupper($Type) == "MEDIUMTEXT") {echo " selected";}?>>MEDIUMTEXT</option>
                        <option value="LONGBLOB"<?php if(strtoupper($Type) == "LONGBLOB") {echo " selected";}?>>LONGBLOB</option>
                        <option value="LONGTEXT"<?php if(strtoupper($Type) == "LONGTEXT") {echo " selected";}?>>LONGTEXT</option>
                        <option value="ENUM"<?php if(strtoupper($Type) == "ENUM") {echo " selected";}?>>ENUM</option>
                        <option value="SET"<?php if(strtoupper($Type) == "SET") {echo " selected";}?>>SET</option>
                </select>
                </td>
                <td><input type="text" name="field_length[]" size="8" value="<?php echo $Length;?>"></td>
                <td><select name="field_attribute[]">
                <?php
                $binary   = eregi("BINARY", $row["Type"], $test_attribute1);
                $unsigned = eregi("UNSIGNED", $row["Type"], $test_attribute2);
                $zerofill = eregi("ZEROFILL", $row["Type"], $test_attribute3);
                $strAttribute = "";
                if($binary)
                        $strAttribute="BINARY";
                if($unsigned)
                        $strAttribute="UNSIGNED";
                if($zerofill)
                        $strAttribute="UNSIGNED ZEROFILL";
                for($j=0;$j<count($cfgAttributeTypes);$j++)
                {
                        echo "<option value=\"$cfgAttributeTypes[$j]\"";
                        if(strtoupper($strAttribute) == strtoupper($cfgAttributeTypes[$j]))
                        echo " selected";
                        echo ">$cfgAttributeTypes[$j]</option>\n";
                }
                ?>
                </select></td>
                <td><select name="field_null[]">
                <?php
                if(!isset($row) || !isset($row["Null"]) || $row["Null"] == "")
                {
                        ?>
                        <option value=" not null">not null</option>
                        <option value="">null</option>
                        <?php
                }
                else
                {
                        ?>
                        <option value="">null</option>
                        <option value="not null">not null</option>
                        <?php
                }
                ?>
                </select></td>
                <td><input type="text" name="field_default[]" size="8" value="<?php if(isset($row) && isset($row["Default"])) echo $row["Default"];?>"></td>
                <td><select name="field_extra[]">
                <?php
                if(!isset($row) || !isset($row["Extra"]) || $row["Extra"] == "")
                {
                        ?>
                        <option value=""></option>
                        <option value="AUTO_INCREMENT">auto_increment</option>
                        <?php
                }
                else
                {
                        ?>
                        <option value="AUTO_INCREMENT">auto_increment</option>
                        <option value=""></option>
                        <?php
                }
                ?>
                </select></td>
                </tr>
                <?php
                }
                ?>
                </table>
                <p>
                <input type="submit" name="submit" value="Save">
                </p>
                </form>
       <?php
     }//end function table_alter

     function alter($table) {
     global $HTTP_GET_VARS, $HTTP_POST_VARS;
                if(isset($HTTP_POST_VARS['submit']))
                {
                    if ($HTTP_GET_VARS['todo'] =="addfield") {
                                $query = '';
                                for($i=0; $i<count($HTTP_POST_VARS['field_name']); $i++)
                                {
                                        $query .= $HTTP_POST_VARS['field_name'][$i]." ".$HTTP_POST_VARS['field_type'][$i]." ";
                                        if($HTTP_POST_VARS['field_length'][$i] != "")
                                        $query .= "(".stripslashes($HTTP_POST_VARS['field_length'][$i]).") ";
                                        if($HTTP_POST_VARS['field_attribute'][$i] != "")
                                        $query .= $HTTP_POST_VARS['field_attribute'][$i]." " ;
                                        if($field_default[$i] != "")
                                        $query .= "DEFAULT '".stripslashes($HTTP_POST_VARS['field_default'][$i])."' ";
                                        $query .= $HTTP_POST_VARS['field_null'][$i]." ".$HTTP_POST_VARS['field_extra'][$i];
                                        if($HTTP_POST_VARS['after_field'] != "--end--")
                                        if ($i == 0)
                                                if ($HTTP_POST_VARS['after_field'] == "--first--")
                                                $query .= " FIRST ";
                                                else
                                                $query .= " AFTER ".stripslashes($HTTP_POST_VARS['after_field'])." ";
                                        else
                                                $query .= " AFTER ".stripslashes($HTTP_POST_VARS['field_name'][$i-1])." ";
                                        $query .= ", ADD ";
                                }
                                $query = stripslashes(ereg_replace(", ADD $", "", $query));
                                $result = mysql_query("ALTER TABLE $table ADD $query");
                                $this->table_property($table);
                   }
                    else {
                        if(!isset($HTTP_GET_VARS['query']))
                                $query = "";
                                $query .= " `".$HTTP_POST_VARS['field_orig'][0]."` `".$HTTP_POST_VARS['field_name'][0]."` ".$HTTP_POST_VARS['field_type'][0]." ";
                        if($HTTP_POST_VARS['field_length'][0] != "")
                                $query .= "(".$HTTP_POST_VARS['field_length'][0].") ";
                        if($HTTP_POST_VARS['field_attribute'][0] != "")
                                $query .= $HTTP_POST_VARS['field_attribute'][0]." ";
                        if($HTTP_POST_VARS['field_default'][0] != "")
                                $query .= "DEFAULT '".$HTTP_POST_VARS['field_default'][0]."' ";

                        $query .= $HTTP_POST_VARS['field_null'][0]." ".$HTTP_POST_VARS['field_extra'][0];
                        $query = stripslashes($query);
                        $sql_query = "ALTER TABLE $table CHANGE $query";
                        $result = mysql_query("ALTER TABLE $table CHANGE $query") or die(mysql_error());
                        $this->table_property($table);
                   }
                }
                else
                {
                       $this->table_alter($table);
                }

     }

     function split_sql(&$ret, $sql) {
            $sql          = trim($sql);
            $sql_len      = strlen($sql);
            $char         = '';
            $string_start = '';
            $in_string    = FALSE;
            $time0        = time();
            for ($i = 0; $i < $sql_len; ++$i) {
                $char = $sql[$i];
                // We are in a string, check for not escaped end of strings except for
                // backquotes that can't be escaped
                if ($in_string) {
                    for (;;) {
                        $i = strpos($sql, $string_start, $i);
                        // No end of string found -> add the current substring to the
                        // returned array
                        if (!$i) {
                            $ret[] = $sql;
                            return TRUE;
                        }
                        // Backquotes or no backslashes before quotes: it's indeed the
                        // end of the string -> exit the loop
                        else if ($string_start == '`' || $sql[$i-1] != '\\') {
                            $string_start      = '';
                            $in_string         = FALSE;
                            break;
                        }
                        // one or more Backslashes before the presumed end of string...
                        else {
                            // ... first checks for escaped backslashes
                            $j                     = 2;
                            $escaped_backslash     = FALSE;
                            while ($i-$j > 0 && $sql[$i-$j] == '\\') {
                                $escaped_backslash = !$escaped_backslash;
                                $j++;
                            }
                            // ... if escaped backslashes: it's really the end of the
                            // string -> exit the loop
                            if ($escaped_backslash) {
                                $string_start  = '';
                                $in_string     = FALSE;
                                break;
                            }
                            // ... else loop
                            else {
                                $i++;
                            }
                        } // end if...elseif...else
                    } // end for
                } // end if (in string)
                // We are not in a string, first check for delimiter...
                else if ($char == ';') {
                    // if delimiter found, add the parsed part to the returned array
                    $ret[]      = substr($sql, 0, $i);
                    $sql        = ltrim(substr($sql, min($i + 1, $sql_len)));
                    $sql_len    = strlen($sql);
                    if ($sql_len) {
                        $i      = -1;
                    } else {
                        // The submited statement(s) end(s) here
                        return TRUE;
                    }
                } // end else if (is delimiter)

                // ... then check for start of a string,...
                else if (($char == '"') || ($char == '\'') || ($char == '`')) {
                    $in_string    = TRUE;
                    $string_start = $char;
                } // end else if (is start of string)

                // ... for start of a comment (and remove this comment if found)...
                else if ($char == '#'
                         || ($char == ' ' && $i > 1 && $sql[$i-2] . $sql[$i-1] == '--')) {
                    // starting position of the comment depends on the comment type
                    $start_of_comment = (($sql[$i] == '#') ? $i : $i-2);
                    // if no "\n" exits in the remaining string, checks for "\r"
                    // (Mac eol style)
                    $end_of_comment   = (strpos(' ' . $sql, "\012", $i+2))
                                      ? strpos(' ' . $sql, "\012", $i+2)
                                      : strpos(' ' . $sql, "\015", $i+2);
                    if (!$end_of_comment) {
                        // no eol found after '#', add the parsed part to the returned
                        // array if required and exit
                        if ($start_of_comment > 0) {
                            $ret[]    = trim(substr($sql, 0, $start_of_comment));
                        }
                        return TRUE;
                    } else {
                        $sql          = substr($sql, 0, $start_of_comment)
                                      . ltrim(substr($sql, $end_of_comment));
                        $sql_len      = strlen($sql);
                        $i--;
                    } // end if...else
                } // end else if (is comment)
              
            } // end for

            // add any rest to the returned array
            if (!empty($sql) && ereg('[^[:space:]]+', $sql)) {
                $ret[] = $sql;
            }
            return $ret;
        }

        function split_string($sql, $delimiter)
        {
            $sql = trim($sql);
            $buffer = array();
            $ret = array();
            $in_string = false;
        
            for($i=0; $i<strlen($sql); $i++)
            {
                if($sql[$i] == $delimiter && !$in_string)
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
                    $buffer[0] = $buffer[1];
                $buffer[1] = $sql[$i];
             }
        
            if (!empty($sql))
            {
                $ret[] = $sql;
            }
        
            return($ret);
        }

        function run_sql() {
                global $HTTP_POST_VARS, $HTTP_GET_VARS,$HTTP_POST_FILES;
                @set_time_limit(0);
                if($HTTP_POST_VARS['asfile']) {
                    if($HTTP_POST_VARS['gzipped']=="yes" && @function_exists('gzopen')) {
                    	$file = gzopen($HTTP_POST_FILES['upload_file']['tmp_name'], 'rb');
                        $sql_query = '';
                        while (!gzeof($file)) {
                            $sql_query .= gzgetc($file);
                        }
                        gzclose($file);
                    }
                    else {
                        $sql_query = fread(fopen($HTTP_POST_FILES['upload_file']['tmp_name'], "rb"), filesize($HTTP_POST_FILES['upload_file']['tmp_name']));	
                    }    
                    $mypieces  = $this->split_sql($pieces,$sql_query);	
                }
                else {
                    $mypieces  = $this->split_sql($pieces,stripslashes($HTTP_POST_VARS['sql_query']));	
                }
                
                if (count($pieces) == 1 && !empty($pieces[0]))
                {
                        $sql_query = trim($pieces[0]);
                        $query_res = @mysql_query($sql_query);
                        if (!$query_res) {
                                        echo "<font class=error><b>MYSQL ERROR: ".mysql_error()."</B></font>";
                                        echo "<br><font class=normal><b>QUERY: ".$sql_query."</B></font>";
                        }//end if (!$query_res) {
                        else {
                                        $queryrows = @mysql_num_rows($query_res);
                                        if ($queryrows>0) {
                                                $this->set_query($sql_query);
                                                $this->set_numrows($queryrows);
                                                $this->print_result($query_res);
                                        } //end if ($queryrows>0) {
                                        else {
                                                $this->show_message("<b>EMPTY RESULT</b><br>".urldecode($sql_query));
                                                $this->print_db($HTTP_GET_VARS['db']);
                                        }//end else if ($queryrows>0) {
                        }//end else if (!$query_res) {
                }
                else {
                        for ($i=0; $i<count($pieces); $i++)
                        {
                                $pieces[$i] = trim($pieces[$i]);
                                if(!empty($pieces[$i]) && $pieces[$i] != "#")
                                {
                                        $result = @mysql_query ($pieces[$i]);
                                        if (mysql_errno()) {
                                                echo "<font class=\"error\"><b>MYSQL ERROR: ".mysql_error()."</B></font>";
                                                echo "<br><font class=\"normal\"><b>QUERY: ".$pieces[$i]."</B></font><br><br>";
                                        }
                                }
                        }
                        $this->show_message("<b>Executed ".count($pieces)." Instructions</b>");
                        $this->print_db($this->get_dbname());
                }
     }

     function get_table_def($db, $table, $crlf)
                {
                        global $HTTP_POST_VARS, $HTTP_GET_VARS;
                        $schema_create = "";
                        if(!empty($HTTP_POST_VARS['drop']))
                                $schema_create .= "DROP TABLE IF EXISTS $table;$crlf";

                        $schema_create .= "CREATE TABLE $table ($crlf";

                        $result = mysql_query("SHOW FIELDS FROM $table") or die();
                        while($row = mysql_fetch_array($result))
                        {
                                $schema_create .= "   $row[Field] $row[Type]";

                                if(isset($row["Default"]) && (!empty($row["Default"]) || $row["Default"] == "0"))
                                $schema_create .= " DEFAULT '$row[Default]'";
                                if($row["Null"] != "YES")
                                $schema_create .= " NOT NULL";
                                if($row["Extra"] != "")
                                $schema_create .= " $row[Extra]";
                                $schema_create .= ",$crlf";
                        }
                        $schema_create = ereg_replace(",".$crlf."$", "", $schema_create);
                        $result = mysql_query("SHOW KEYS FROM $table") or die();
                        while($row = mysql_fetch_array($result))
                        {
                                $kname=$row['Key_name'];
                                if(($kname != "PRIMARY") && ($row['Non_unique'] == 0))
                                $kname="UNIQUE|$kname";
                                if(!isset($index[$kname]))
                                $index[$kname] = array();
                                $index[$kname][] = $row['Column_name'];
                        }

                        while(list($x, $columns) = @each($index))
                        {
                                $schema_create .= ",$crlf";
                                if($x == "PRIMARY")
                                $schema_create .= "   PRIMARY KEY (" . implode($columns, ", ") . ")";
                                elseif (substr($x,0,6) == "UNIQUE")
                                $schema_create .= "   UNIQUE ".substr($x,7)." (" . implode($columns, ", ") . ")";
                                else
                                $schema_create .= "   KEY $x (" . implode($columns, ", ") . ")";
                        }

                        $schema_create .= "$crlf)";
                        return (stripslashes($schema_create));
              }//end get_table_def

              function get_table_content($table, $crlf)
                        {
                        global $HTTP_POST_VARS, $HTTP_GET_VARS;
                        $result = mysql_query("SELECT * FROM $table") or die();
                        $i = 0;
                        while($row = mysql_fetch_row($result))
                        {
                                @set_time_limit(0);
                                $table_list = "(";

                                for($j=0; $j<mysql_num_fields($result);$j++)
                                $table_list .= mysql_field_name($result,$j).", ";

                                $table_list = substr($table_list,0,-2);
                                $table_list .= ")";

                                if(isset($HTTP_POST_VARS["showcolumns"]))
                                        $schema_insert = "INSERT INTO $table $table_list VALUES (";
                                else
                                        $schema_insert = "INSERT INTO $table VALUES (";

                                for($j=0; $j<mysql_num_fields($result);$j++)
                                {
                                if(!isset($row[$j]))
                                        $schema_insert .= " NULL,";
                                elseif($row[$j] != "")
                                        $schema_insert .= " '".addslashes($row[$j])."',";
                                else
                                        $schema_insert .= " '',";
                                }
                                $schema_insert = ereg_replace(",$", "", $schema_insert);
                                $schema_insert .= ")";
                                 if(empty($HTTP_POST_VARS['asfile']))
                                 {
                                        echo htmlspecialchars(trim($schema_insert).";$crlf");
                                 }
                                 else
                                 {
                                        echo trim($schema_insert).";$crlf";
                                 }
                                $i++;
                        }
                        return (true);
            }//end get_table_def()

     function dump_sql() {
                global $HTTP_POST_VARS, $HTTP_GET_VARS, $HTTP_SESSION_VARS;
                @set_time_limit(0);
                $crlf="\n";
                if(empty($HTTP_POST_VARS['asfile']))
                {
                        $this->print_header();
                        echo "<div align=left><pre>\n";
                }
                else
                {       
                        if($HTTP_POST_VARS['gzipped']=="yes" && @function_exists('gzencode')) {
                            $d_filename = $this->get_dbname().".sql.gz";
                        }
                        else {
                        	$d_filename = $this->get_dbname().".sql";
                        }
                        header("Content-disposition: attachment; filename=".$d_filename);
                        header("Content-type: application/octetstream");
                        header("Pragma: no-cache");
                        header("Expires: 0");

                        $client = getenv("HTTP_USER_AGENT");
                        if(ereg('[^(]*\((.*)\)[^)]*',$client,$regs))
                        {
                                $os = $regs[1];
                                // this looks better under WinX
                                if (eregi("Win",$os))
                                $crlf="\r\n";
                        }
                }

                $tables = mysql_list_tables($this->get_dbname());

                $num_tables = @mysql_numrows($tables);
                if($num_tables == 0)
                {
                        echo "No table found.";
                }
                else
                {
                        $i = 0;
                        if($HTTP_POST_VARS['gzipped']=="yes" && @function_exists('gzencode')) {
                        	ob_start();
                        }
                        print "#MySQL-Dump$crlf";
                        print "# $crlf";
                        print "# Host: ".$HTTP_SESSION_VARS['mysql_host'];
                        print " Database: ".$this->get_dbname()."$crlf";

                        while($i < $num_tables)
                        {
                                $table = mysql_tablename($tables, $i);

                                if ($HTTP_POST_VARS['tbl']){
                                     if ($HTTP_POST_VARS['tbl'] == $table) {
                                                print $crlf;
                                                print "# --------------------------------------------------------$crlf";
                                                print "#$crlf";
                                                print "# Table structure for table '$table'$crlf";
                                                print "#$crlf";
                                                print $crlf;
                                                echo $this->get_table_def($this->get_dbname(), $table, $crlf).";$crlf$crlf";
                                                if($HTTP_POST_VARS['what'] == "data")
                                                {
                                                        print "#$crlf";
                                                        print "# Dumping data for table '$table'$crlf";
                                                        print "#$crlf";
                                                        print $crlf;
                                                        $this->get_table_content($table,$crlf);
                                                }

                                        }
                                }
                                else {
                                        print $crlf;
                                        print "# --------------------------------------------------------$crlf";
                                        print "#$crlf";
                                        print "# Table structure for table '$table'$crlf";
                                        print "#$crlf";
                                        print $crlf;

                                        echo $this->get_table_def($this->get_dbname(), $table, $crlf).";$crlf$crlf";
                                        if($HTTP_POST_VARS['what'] == "data")
                                        {
                                                        print "#$crlf";
                                                        print "# Dumping data for table '$table'$crlf";
                                                        print "#$crlf";
                                                        print $crlf;
                                                        $this->get_table_content($table,$crlf);
                                        }
                                }
                                $i++;
                        }
                }

                if(empty($HTTP_POST_VARS['asfile']))
                {
                        echo "</div></pre>\n";
                        $this->print_footer();
                }
                elseif($HTTP_POST_VARS['gzipped']=="yes" && @function_exists('gzencode')) {
                    $content=ob_get_contents();
                	ob_end_clean();
                    $content = gzencode($content);
                    echo $content;
                } 

     }//end function dump;

    function count_records($arg_db, $arg_tbl) {
        $result = mysql_query("select count(*) as num from $arg_tbl");
        $num = mysql_result($result,0,"num");
        echo $num;
    }

    function show_table_navigation($arg_next, $arg_prev,$table) {
         ?>
         <table>
         <tr>
                <?php if($arg_prev>=0) {?>
                <td><form action="miniphpadmin.php?table=<?php echo $table;?>" method="post">
                        <input type="hidden" name="query" value="<?php echo $this->query;?>">
                        <input type="hidden" name="pos" value="<?php echo $arg_prev;?>">
                        <input type="submit" name="go" value="Previous 25">
                        </form>
                </td>
                 <?php
                 }
                 if ($arg_next < $this->numrows) {
                 ?>
                <td><form action="miniphpadmin.php?table=<?php echo $table;?>" method="post">
                        <input type="hidden" name="query" value="<?php echo $this->query;?>">
                        <input type="hidden" name="pos" value="<?php echo $arg_next;?>">
                        <input type="submit" name="go" value="Next 25">
                        </form>
                </td>
                <td><form action="miniphpadmin.php?table=<?php echo $table;?>" method="post">
                        <input type="hidden" name="query" value="<?php echo $this->query;?>">
                        <input type="hidden" name="pos" value="<?php echo ($this->numrows-25);?>">
                        <input type="submit" name="go" value="End >>">
                        </form>
                </td>
                <?php
                }
                ?>
         </tr>
         </table>
         </form>
         <?php

    }//end show_table_navigation()

    function show_message($message) {
                echo "<table class=\"mess\" width=\"60%\"><tr><td align=\"left\" nowrap>";
                echo nl2br($message);
                echo "</td></tr></table>";
    }//end show_message();

   function set_query($ret_query) {
               $this->query =  $ret_query;
   }//end set_query();

   function get_query() {
               return $this->query;
   }//end get_query();

   function set_numrows($arg_num) {
               $this->numrows =  $arg_num;
   }//end set_query();

   function get_numrows() {
               return $this->numrows;
   }//end get_query();

   function get_dbname() {
               return $this->db;
   }//end get_dbname();

   function set_dbname($arg_db) {
               $this->db = $arg_db;
   }//end set_dbname();

   function print_header() {
                ?>
                <!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0//EN">
                <html>
                <head>
                <title>Mini Php My ADmin</title>
                <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
                <meta name="GENERATOR" content="Quanta Plus">
                </head>
                <style type="text/css" title="mini">
                        .error {font-family: arial; color: #FF0000; font-size: 12px;}
                        .mess {background: #F5F5F5; font-family: arial; color: #000000; font-size: 12px; border: 1px solid #DDDDDD}
                        .normal {font-family: arial; color: #000000; font-size: 12px;}
                        .small {font-family: helvetica; font-size: 11px;}
                        A:LINK, A:VISITED {color: #0E066D; font-family: Arial, Helvetica; font-size: 11px;}
                        A:HOVER {color: #404040; font-size: 11px;}
                </style>
                <body>
                </body>
                </html>
                <?php
   }//end print_header()

   function print_footer() {
       global $HTTP_GET_VARS, $HTTP_POST_VARS;
               ?>
               <hr width="60%" align="left">
               <table>
               <?php if($HTTP_GET_VARS['todo']=="settings") {?>
               	<tr><td align="left"><a href="index.php">Admin Home</a>&nbsp;|&nbsp;<a href="miniphpadmin.php">MiniphpAdmin Home</a>&nbsp;|&nbsp;<a href="miniphpadmin.php?todo=settings&type=script">Script Settings</a>&nbsp;|&nbsp;<a href="miniphpadmin.php?todo=settings&type=payment">Payment Settings</a>&nbsp;|&nbsp;<a href="miniphpadmin.php?todo=settings&type=design">Design Settings</a>&nbsp;|&nbsp;<a href="miniphpadmin.php?todo=settings&type=<?php echo $HTTP_GET_VARS['type'];?>&action=add">Add Setting</a>&nbsp;|&nbsp;<a href="miniphpadmin.php?todo=settings&type=<?php echo $HTTP_GET_VARS['type'];?>&action=delete">Delete Setting</a></td></tr>
                <?php }else{?>
                <tr><td align="left"><a href="index.php">Admin Home</a>&nbsp;|&nbsp;<a href="miniphpadmin.php?todo=newconn">New Connection</a>&nbsp;|&nbsp;<a href="miniphpadmin.php?todo=showalldb">Show all Databases</a>&nbsp;|&nbsp;<a href="miniphpadmin.php?todo=showdb&db=<?php echo ($this->get_dbname())?$this->get_dbname():DB_DATABASE;?>">Show "<?php echo ($this->get_dbname())?$this->get_dbname():DB_DATABASE;?>" tables </a>&nbsp;|&nbsp;<a href="miniphpadmin.php?todo=settings">Settings</a></td></tr>
                <?php }?>
                </table>
               <?php
   }

}//end class  miniphpmyadmin()
################################

################################
class settings{
    var $set_file="";
    var $set_include="";
    function settings($file_name,$include_name) {
                $this->set_file = $file_name;
                $this->set_include = $include_name;
    }
    function readin($type) {
    	include($this->set_include);
        //print_r($fields);
        $fp=fopen($this->set_file,"r");
        ?>
        <form action="miniphpadmin.php?todo=settings&type=<?php echo $type;?>" method="post">
        <input type="hidden" name="action" value="save">
        <TABLE border="0" cellpadding="4" cellspacing="0" width="100%" align="center" bgcolor="#EFEFEF" style="border: 1px solid #000000;">
        <?php
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
                            else if (eregi("\(select (.*)\)(.*)",$field_name,$regcomm)) {
                                echo "<td align=\"right\" valign=\"top\">".$regcomm[2]." </td>\n";
                                $select = true;
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
                                        echo "<input type=\"radio\" class=\"radio\" name=\"".$regexp[1]."\" value=\"".$radio_values[$j]."\"".$checked."><b>".$radio_values[$j]."</b>&nbsp;";
                                    }
                                    $radio = false;
                        }
                        else {
                            if (strlen($regexp[2])<30) {
                                    echo "<td valign=\"top\"><input type=\"text\" name=\"".$regexp[1]."\" value=\"".stripslashes(eregi_replace("'$","",$regexp[2]))."\" size=\"".(strlen($regexp[2])+5)."\">";
                            }
                            else {
                                    echo "<td><textarea name=\"".$regexp[1]."\" cols=\"50\" rows=\"8\">".eregi_replace('\.&quot;\\\\n&quot;\.',"\n",eregi_replace("'","",eregi_replace("\\\\'","&#039",$regexp[2])))."</textarea>";
                            }
                        }
                        echo "<input type=\"hidden\" name=\"inputs[]\" value=\"".$regexp[1]."\"></td>";
                        echo "</tr>";
                }
                else {
                    if ($str == "<?php" || $str == "?".">") {
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
                <td colspan="3" align="center"><input type="submit" name="save" value="Save"></td>
        </tr>
        </table>
        </form>
    <?php
    }//end function readin()
    function save() {
            global $HTTP_GET_VARS, $HTTP_POST_VARS;
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
            $fp=fopen($this->set_file,"w");
            fwrite($fp,"<?php\n");
            fwrite($fp, eregi_replace("\n$","",$towrite));
            fwrite($fp,"\n?".">");
            fclose($fp);
    } // end func
    function add($type) {
    	include($this->set_include);
        //print_r($fields);
        ?>
        <form action="miniphpadmin.php?todo=settings&type=<?php echo $type;?>" method="post">
        <input type="hidden" name="action" value="addsave">
        <TABLE border="0" cellpadding="4" cellspacing="0" width="100%" align="center">
        <tr>
        <td>After:</td>
        <td><select name="after">
        <option value=""></option>
        <?php
        for($i=1;$i<=sizeof($fields);$i++) {
            $field_name = $fields[$i]["name"];
            if (eregi("\(radio (.*)\)(.*)",$field_name,$regcomm)) {
                $field_name = $regcomm[2];
            }
            else if (eregi("\(select (.*)\)(.*)",$field_name,$regcomm)) {
                $field_name = $regcomm[2];
            }
            else {
            }
        	echo "<option value=\"".$i."\">".$field_name."</option>";
        }
        ?>
        </select></td></tr>
        <tr>
            <td>Name:</td>
            <td><input type="text" name="new_name" value="" size="50"></td>
        </tr>
        <tr>
            <td valign="top">Comment:</td>
            <td><textarea name="new_comm" cols="60" rows="4"></textarea></td>
        </tr>
        <tr>
            <td>Define:</td>
            <td><input type="text" name="new_define" value="" size="30"></td>
        </tr> 
        <tr>
            <td>Value:</td>
            <td><input type="text" name="new_val" value="" size="30"></td>
        </tr>
        <tr>
                <td colspan="2" align="center"><input type="submit" name="save" value="Add"></td>
        </tr>
        </table>
        </form>
        <?php
    } // end func
    function addsave($type) {
        global $HTTP_GET_VARS, $HTTP_POST_VARS;
        include($this->set_include);
        $fp=fopen($this->set_file,"r");
        $towrite='';
        $new_fields = array();
        $i=0;
        if(!$HTTP_POST_VARS['after']) {
        	$HTTP_POST_VARS['after']=0;
        }
        while (!feof($fp)) {
           $str=trim(fgets($fp, 20000));
           if (!empty($str) && ($str != "\r\n") && ($str != "\n") && ($str != "\r")) {
                if($i == ($HTTP_POST_VARS['after']+1)) {
                    $new_fields[]['name'] = $HTTP_POST_VARS['new_name'];
                    $new_fields[sizeof($new_fields)-1]['define'] = $HTTP_POST_VARS['new_define'];
                    $new_fields[sizeof($new_fields)-1]['default'] = $HTTP_POST_VARS['new_val'];
                    $comments = split("\n", trim($HTTP_POST_VARS['new_comm']));
                    for($c=0; $c<sizeof($comments); $c++){
                        $new_fields[sizeof($new_fields)-1]['comment'][] = trim($comments[$c]);
                    }
                    $towrite .= "define('".$HTTP_POST_VARS['new_define']."','".preg_replace("/(\015\012)|(\015)|(\012)/","'.\"\\n\".'",$HTTP_POST_VARS['new_val'])."');\n";
                }
                $new_fields[] = $fields[$i];
                $towrite.=$str."\n";
           }
           $i++;
        }
        fclose($fp);
        $fp=fopen($this->set_file,"w");
        fwrite($fp, eregi_replace("\n$","",$towrite));
        fclose($fp);
        $towrite='';
        $j=1;
        for($i=1;$i<sizeof($new_fields)-1;$i++) {
            for($r=0;$r<sizeof($new_fields[$i]['comment']);$r++) {
               $towrite.= "\$fields[".$j."][\"comment\"][]=\"".eregi_replace('"','\\"',$new_fields[$i]['comment'][$r])."\";\n";
            }
        	$towrite.= "\$fields[".$j."][\"name\"]=\"".$new_fields[$i]['name']."\";\n";
        	$towrite.= "\$fields[".$j."][\"define\"]=\"".$new_fields[$i]['define']."\";\n";
        	$towrite.= "\$fields[".$j."][\"default\"]=\"".eregi_replace('"','\\"',preg_replace("/(\015\012)|(\015)|(\012)/","\"\\n\"",$new_fields[$i]['default']))."\";\n";
        	$j++;
        }
        $fp=fopen($this->set_include,"w");
        fwrite($fp,"<?php\n");
        fwrite($fp, eregi_replace("\n$","",$towrite));
        fwrite($fp,"\n?".">");
        fclose($fp);
        ?>
        </table>
        </form>
        <?php
    } // end func
    function delete($type) {
    	include($this->set_include);
        //print_r($fields);
        ?>
        <form action="miniphpadmin.php?todo=settings&type=<?php echo $type;?>" method="post">
        <input type="hidden" name="action" value="deletesave">
        <TABLE border="0" cellpadding="4" cellspacing="0" width="100%" align="center">
        <tr>
        <td>Select:</td>
        <td><select name="setid">
        <option value=""></option>
        <?php
        for($i=1;$i<=sizeof($fields);$i++) {
            $field_name = $fields[$i]["name"];
            if (eregi("\(radio (.*)\)(.*)",$field_name,$regcomm)) {
                $field_name = $regcomm[2];
            }
            else if (eregi("\(select (.*)\)(.*)",$field_name,$regcomm)) {
                $field_name = $regcomm[2];
            }
            else {
            }
        	echo "<option value=\"".$i."\">".$field_name."</option>";
        }
        ?>
        </select></td></tr>
        <tr>
                <td colspan="2" align="center"><input type="submit" name="delete" value="Delete" onClick="return confirm('Are you sure you want to delete this setting?');"></td>
        </tr>
        </table>
        </form>
        <?php
    } // end func
    function deletesave($type) {
        global $HTTP_GET_VARS, $HTTP_POST_VARS;
        include($this->set_include);
        $fp=fopen($this->set_file,"r");
        $towrite='';
        $new_fields = array();
        $i=0;
        while (!feof($fp)) {
           $str=trim(fgets($fp, 20000));
           if (!empty($str) && ($str != "\r\n") && ($str != "\n") && ($str != "\r")) {
                if($i == ($HTTP_POST_VARS['setid'])) {
                }
                else {
                    $new_fields[] = $fields[$i];
                    $towrite.=$str."\n";	
                }
           }
           $i++;
        }
        fclose($fp);
        $fp=fopen($this->set_file,"w");
        fwrite($fp, eregi_replace("\n$","",$towrite));
        fclose($fp);
        $towrite='';
        $j=1;
        for($i=1;$i<sizeof($new_fields)-1;$i++) {
            for($r=0;$r<sizeof($new_fields[$i]['comment']);$r++) {
               $towrite.= "\$fields[".$j."][\"comment\"][]=\"".eregi_replace('"','\\"',$new_fields[$i]['comment'][$r])."\";\n";
            }
        	$towrite.= "\$fields[".$j."][\"name\"]=\"".$new_fields[$i]['name']."\";\n";
        	$towrite.= "\$fields[".$j."][\"define\"]=\"".$new_fields[$i]['define']."\";\n";
        	$towrite.= "\$fields[".$j."][\"default\"]=\"".eregi_replace('"','\\"',preg_replace("/(\015\012)|(\015)|(\012)/","\"\\n\"",$new_fields[$i]['default']))."\";\n";
        	$j++;
        }
        $fp=fopen($this->set_include,"w");
        fwrite($fp,"<?php\n");
        fwrite($fp, eregi_replace("\n$","",$towrite));
        fwrite($fp,"\n?".">");
        fclose($fp);
        ?>
        </table>
        </form>
        <?php
    } // end func
}//end class  settings()
if ($HTTP_GET_VARS['todo']!="dump") {
    ob_start("ob_gzhandler");
}
session_start();
if ( ((md5($HTTP_POST_VARS['username']."mini php admin authorization") != "9a14a0a7d1642d1bfd476f4ba6cf7ac7") && (md5($HTTP_SESSION_VARS['username']."mini php admin authorization") != "9a14a0a7d1642d1bfd476f4ba6cf7ac7")) || ((md5($HTTP_POST_VARS['userpass']."mini php admin authorization") != "9a1b60e8b2ee9917dcacffca3cd20b2c") && (md5($HTTP_SESSION_VARS['userpass']."mini php admin authorization") != "9a1b60e8b2ee9917dcacffca3cd20b2c")) ) {
	?>
		<!DOCTYPE HTML PUBLIC "-//IETF//DTD HTML 2.0//EN">
		<HTML><HEAD>
		<TITLE>401 Authorization Required</TITLE>
		</HEAD><BODY>
                          <center>
		<H1>Authorization Required</H1>
                          <br><br>
                          <form method="post" action="miniphpadmin.php?todo=login">
                          <table>
                                <tr><td align="right"><B>Username :</B></td><td><input type="text" name="username" size="20" value=""></td></tr>
                                <tr><td align="right"><B>Password :</B></td><td><input type="password" name="userpass" size="20" value=""></td></tr>
                                <tr><td colspan="2" align="center"><input type="submit"  name="Go" value="Enter"></td></tr>
                          </table>
                          </form>
                          </center>
		<HR>
		</BODY></HTML>
		<?php
        exit;
}
else {
        if ($HTTP_GET_VARS['todo'] == "login") {
                $username = $HTTP_POST_VARS['username'];
                session_register("username");
                $userpass = $HTTP_POST_VARS['userpass'];
                session_register("userpass");
        }
        if ($HTTP_GET_VARS['todo'] == "conn") {
                session_unregister('mysql_host');
                $mysql_host = $HTTP_POST_VARS['mysqlhost'];
                session_register('mysql_host');
                session_unregister('mysql_user');
                $mysql_user = $HTTP_POST_VARS['mysqluser'];
                session_register('mysql_user');
                session_unregister('mysql_pass');
                $mysql_pass = $HTTP_POST_VARS['mysqlpasswd'];
                session_register('mysql_pass');
                session_unregister('mysql_db');
                $mysql_db = $HTTP_POST_VARS['mysqldb'];
                session_register('mysql_db');
        }
        if ($HTTP_SESSION_VARS['mysql_host']) {
                        define("DB_SERVER",$HTTP_SESSION_VARS['mysql_host']);
        }
        else if ($HTTP_POST_VARS['mysqlhost']) {
                        define("DB_SERVER",$HTTP_POST_VARS['mysqlhost']);
        }
        if ($HTTP_SESSION_VARS['mysql_user']) {
                        define("DB_SERVER_USERNAME",$HTTP_SESSION_VARS['mysql_user']);
        }
        else if ($HTTP_POST_VARS['mysqluser']) {
                        define("DB_SERVER_USERNAME",$HTTP_POST_VARS['mysqluser']);
        }
        if ($HTTP_SESSION_VARS['mysql_pass']) {
                        define("DB_SERVER_PASSWORD",$HTTP_SESSION_VARS['mysql_pass']);
        }
        else if ($HTTP_POST_VARS['mysqlpasswd']) {
                        define("DB_SERVER_PASSWORD",$HTTP_POST_VARS['mysqlpasswd']);
        }
        if ($HTTP_GET_VARS['db']) {
                        define("DB_DATABASE",$HTTP_GET_VARS['db']);
                        session_unregister('mysql_db');
                        $mysql_db = $HTTP_GET_VARS['db'];
                        session_register('mysql_db');
        }
        if ($HTTP_SESSION_VARS['mysql_db']) {
                        define("DB_DATABASE",$HTTP_SESSION_VARS['mysql_db']);
                        $HTTP_GET_VARS['db'] = $HTTP_SESSION_VARS['mysql_db'];
        }
        if ($HTTP_POST_VARS['mysqldb']) {
                        define("DB_DATABASE",$HTTP_POST_VARS['mysqldb']);
                        $HTTP_GET_VARS['db'] = $HTTP_POST_VARS['mysqldb'];
        }
        if ($HTTP_GET_VARS['todo'] != "newconn" && $HTTP_GET_VARS['todo'] != "conn") {
                if ($HTTP_SESSION_VARS['mysql_host'] || $HTTP_SESSION_VARS['mysql_user'] || $HTTP_SESSION_VARS['mysql_pass']) {
                        $link = mysql_connect(defined('DB_SERVER') ? DB_SERVER : "" , defined('DB_SERVER_USERNAME') ? DB_SERVER_USERNAME : "" ,  defined('DB_SERVER_PASSWORD') ? DB_SERVER_PASSWORD : "");
                        if (mysql_errno()) {
                                echo "<font class=\"error\">".mysql_error()."</fonr>";
                        }
                        if ($HTTP_GET_VARS['db']) {
                                if (!mysql_select_db($HTTP_GET_VARS['db'])) {
                                        echo "<font class=\"error\">".mysql_error()."</fonr>";
                                }
                        }
                }
                else {
                        include($config_file);
                }
        }
        else {
        $link = mysql_connect(defined('DB_SERVER') ? DB_SERVER : "" , defined('DB_SERVER_USERNAME') ? DB_SERVER_USERNAME : "" ,  defined('DB_SERVER_PASSWORD') ? DB_SERVER_PASSWORD : "");
        if ($link && $HTTP_GET_VARS['todo']!='newconn') {
                        if ($HTTP_GET_VARS['db']) {
                                mysql_select_db($HTTP_GET_VARS['db']);
                        }
        }
        else {
                        ?>
                        <table align="center">
                        <form method="post" action="miniphpadmin.php?todo=conn">
                        <tr><td align="center" colspan="2"><b>MYSQL DATABASE CONNECTION</b></td></tr>
                        <tr><td align="center" colspan="2">&nbsp;</td></tr>
                        <tr><td align="center">Mysql Host: </td><td><input type="text" name="mysqlhost" value="localhost"></td></tr>
                        <tr><td align="center">Mysql User: </td><td><input type="text" name="mysqluser" value="<?php echo $HTTP_SESSION_VARS['mysql_user'];?>"></td></tr>
                        <tr><td align="center">Mysql Password: </td><td><input type="text" name="mysqlpasswd" value="<?php echo $HTTP_SESSION_VARS['mysql_pass'];?>"></td></tr>
                        <tr><td align="center">Mysql Database: </td><td><input type="text" name="mysqldb" value="<?php echo $HTTP_SESSION_VARS['mysql_db'];?>"></td></tr>
                        <tr><td align="center" colspan="2"><input type="submit" name="mygo" value="Go"></td></tr>
                        </form>
                        </table>
                <?php
                }
        }

        $myphp = new miniphpmyadmin($HTTP_GET_VARS['db']);
        if ($HTTP_GET_VARS['todo'] != "dump") {
                $myphp->print_header();
        }
        if ($HTTP_GET_VARS['todo']=="showalldb") {
                $query = "SHOW DATABASES";
        }
        elseif ($HTTP_GET_VARS['todo']=="showdb") {
                $myphp->print_db($HTTP_GET_VARS['db']);
        }
        elseif ($HTTP_GET_VARS['todo']=="dump") {
                $myphp->dump_sql($HTTP_GET_VARS['db']);
        }
        elseif ($HTTP_GET_VARS['todo']=="prop") {
                $myphp->table_property($HTTP_GET_VARS['table']);
        }
        elseif ($HTTP_GET_VARS['todo']=="alter" || $HTTP_GET_VARS['todo']=="addfield") {
                if ($HTTP_POST_VARS['table']) {
                        $myphp->alter($HTTP_POST_VARS['table']);
                }
                else {
                        $myphp->alter($HTTP_GET_VARS['table']);
                }
        }

        if ($HTTP_GET_VARS['query']) {
        $query = urldecode(stripslashes($HTTP_GET_VARS['query']));
        }
        elseif ($HTTP_POST_VARS['query']) {
        $query = stripslashes($HTTP_POST_VARS['query']);
        }
        elseif ($HTTP_GET_VARS['todo']=="runsql") {
                $myphp->run_sql();
        }
        if ($query) {
                $myphp->set_query($query);
                if ($HTTP_GET_VARS['todo']=="change") {
                        $myphp->table_change();
                }
                elseif ($HTTP_GET_VARS['todo']=="replace") {
                        $myphp->table_replace();
                }
        else {
                        $query_res = @mysql_query( $query);
                        if (!$query_res) {
                                        echo "<font class=error><b>MYSQL ERROR: ".mysql_error()."</B></font>";
                                        echo "<br><font class=normal><b>QUERY: ".$query."</B></font>";
                        }//end if (!$query_res) {
                        else {
                                        $queryrows = @mysql_num_rows($query_res);
                                        if ($queryrows>0) {
                                                $myphp->set_numrows($queryrows);
                                                $myphp->print_result($query_res);
                                        } //end if ($queryrows>0) {
                                        else {
                                                $myphp->show_message("<b>EMPTY RESULT</b><br>".urldecode($query));
                                                $myphp->print_db($HTTP_GET_VARS['db']);
                                        }//end else if ($queryrows>0) {
                        }//end else if (!$query_res) {
                }
        }//end if ($query)
        if($HTTP_GET_VARS['todo']=="settings") {
        	if($HTTP_GET_VARS['type']=="script") {
                $wfile = "application_settings.php";
                $ifile = "phpjob_settings.cfg.php";
            }
            elseif($HTTP_GET_VARS['type']=="payment") {
            	$wfile = "cc_payment_settings.php";
                $ifile = "phpjob_payment.cfg.php";
            }
            elseif($HTTP_GET_VARS['type']=="design") {
            	$wfile = "design_configuration.php";
                $ifile = "phpjob_design.cfg.php";
            }
            if(DIR_SERVER_ROOT) {
                $myfile = DIR_SERVER_ROOT.$wfile;
                $myinclude = DIR_ADMIN.$ifile;	    
            }
            elseif($HTTP_GET_VARS['dir'] && $HTTP_GET_VARS['dirinc']){
                $myfile = $HTTP_GET_VARS['dir'].$wfile;
                $myinclude = $HTTP_GET_VARS['dirinc'].$ifile;	    
            }
            elseif(@file_exists(eregi_replace("(/|\\\)([^\/]|[^\])*$","\\1".$wfile,dirname(__FILE__)))){
               $myfile = eregi_replace("(/|\\\)([^\/]|[^\])*$","\\1".$wfile,dirname(__FILE__));
               $myinclude = dirname(__FILE__)."/".$ifile;
               
            }
            elseif(@file_exists(dirname(__FILE__)."/".$wfile)){
                $myfile = dirname(__FILE__)."/".$wfile;
                $myinclude = dirname(__FILE__)."/admin/".$ifile;
            }
            if($HTTP_POST_VARS['action']=="save") {
                $myset = new settings($myfile, $myinclude);	
                $myset->save();
            }
            elseif($HTTP_GET_VARS['action']=="add") {
                $myset = new settings($myfile, $myinclude);	
                $myset->add($HTTP_GET_VARS['type']);
            }
            elseif($HTTP_POST_VARS['action']=="addsave") {
                $myset = new settings($myfile, $myinclude);	
                $myset->addsave($HTTP_GET_VARS['type']);
            }
            elseif($HTTP_GET_VARS['action']=="delete") {
                $myset = new settings($myfile, $myinclude);	
                $myset->delete($HTTP_GET_VARS['type']);
            }
            elseif($HTTP_POST_VARS['action']=="deletesave") {
                $myset = new settings($myfile, $myinclude);	
                $myset->deletesave($HTTP_GET_VARS['type']);
            }
            elseif($HTTP_GET_VARS['type']) {
                echo "<b>Permissions:</b> <br>".$myfile.": ".substr(base_convert(fileperms($myfile), 10, 8),3)."<br>";
                echo "".$myinclude.": ".substr(base_convert(fileperms($myinclude), 10, 8),3)."<br>";
                $myset = new settings($myfile, $myinclude);	
                $myset->readin($HTTP_GET_VARS['type']);	    
            }
            else {
            	
            }
        }
        if ($HTTP_GET_VARS['todo'] != "dump") {
                $myphp->print_footer();
        }
}
?>