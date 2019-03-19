<?php
include('admin_design.php');
include('../application_config_file.php');
include('admin_auth.php');
function my_handler($sql_insert)
{
    global $endtag, $asfile;

    if(empty($asfile))
    {
        echo htmlspecialchars("$sql_insert;$endtag");
    }
    else
    {
        echo "$sql_insert;$endtag";
    }
}

function get_table_def($db, $table, $endtag, $crlf)
{
    $schema_create = "";
    $schema_create .= "DROP TABLE IF EXISTS $table;$endtag";

    $schema_create .= "CREATE TABLE $table ($crlf";

    $result = mysql_db_query($db, "SHOW FIELDS FROM $table") or mysql_die();
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
    $result = mysql_db_query($db, "SHOW KEYS FROM $table") or mysql_die();
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
}

// Get the content of $table as a series of INSERT statements.
// After every row, a custom callback function $handler gets called.
// $handler must accept one parameter ($sql_insert);
function get_table_content($db, $table, $handler)
{
    $result = mysql_db_query($db, "SELECT * FROM $table") or mysql_die();
    $i = 0;
    while($row = mysql_fetch_row($result))
    {
        set_time_limit(60); // HaRa
        $table_list = "(";

        for($j=0; $j<mysql_num_fields($result);$j++)
            $table_list .= mysql_field_name($result,$j).", ";

        $table_list = substr($table_list,0,-2);
        $table_list .= ")";

        if(isset($GLOBALS["showcolumns"]))
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
        $handler(trim($schema_insert));
        $i++;
    }
    return (true);
}
if ($HTTP_POST_VARS['todo'] == "backup") {
    if(ADMIN_SAFE_MODE == "yes") {
        $error_title = "backuping database!";
        include("header.php");
        bx_admin_error(TEXT_SAFE_MODE_ALERT);
        include("footer.php");
    }//end if ADMIN_SAFE_MODE == yes
    elseif(ADMIN_BX_DEMO == "yes") {
        include("header.php");
        $error_title = "backuping database!";
        bx_admin_error(TEXT_DEMO_MODE_ALERT);
        include("footer.php");
    }//end if ADMIN_BX_DEMO == yes
    else {
        $db=DB_DATABASE;
        @set_time_limit(0);
        $crlf="\n";
        $endtag="#%%\n";
            header("Content-disposition: filename=".date('m-d-Y')."-".$db.".sql");
            header("Content-type: application/octetstream");
            header("Pragma: no-cache");
            header("Expires: 0");

            // doing some DOS-CRLF magic...
            $client = getenv("HTTP_USER_AGENT")?getenv("HTTP_USER_AGENT"):$HTTP_SERVER_VARS['HTTP_USER_AGENT'];
            if(ereg('[^(]*\((.*)\)[^)]*',$client,$regs))
            {
                $os = $regs[1];
                // this looks better under WinX
                if (eregi("Win",$os))
                    $crlf="\r\n";
                    //$crlf = "<br>";
            }
        $tables = mysql_list_tables($db);

        $num_tables = @mysql_numrows($tables);
        if($num_tables == 0)
        {
            echo "No Tables Found";
        }
        else
        {
            $i = 0;
            print "# Php-Jobsite ".PHP_JOBSITE_VERSION." MySQL-Dump$endtag";
            print "# http://www.bitmixsoft.c0m/php-jobsite/";
            print "# Web access: ".HTTP_SERVER.$endtag;
            print "# Directory path: ".DIR_SERVER_ROOT.$endtag;
            print "#$endtag";
            print "# Host: " . DB_SERVER.$endtag;
            print "# Database: $db$endtag";
            print "#".SITE_NAME." - ".SITE_MAIL."$endtag";
            print "#".SITE_TITLE."$endtag";

            while($i < $num_tables)
            {
                $table = mysql_tablename($tables, $i);

                print $endtag;
                print "# --------------------------------------------------------$endtag";
                print "#$endtag";
                print "#TableStructure '$table'$endtag";
                print "#$endtag";
                print $endtag;

                echo get_table_def($db, $table, $endtag, $crlf).";$endtag$endtag";

                print "#$endtag";
                print "#Dumping data for table '$table'$endtag";
                print "#$endtag";
                print $endtag;

                get_table_content($db, $table, "my_handler");
                $i++;
            }
        }
        bx_exit();
    }//end else ADMIN_SAFE_MODE==yes
}
else {
include("header.php");
include(DIR_ADMIN.FILENAME_ADMIN_BACKUP_DB_FORM);
include("footer.php");
}
?>