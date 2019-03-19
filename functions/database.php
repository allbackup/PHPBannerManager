<?php
############################################################
# php-Jobsite(TM)                                          #
# Copyright © 2002 BitmixSoft. All rights reserved.        #
#                                                          #
#  /php-jobsite/                   #
# File: database.php                                       #
# Last update: 2/8/2002                                    #
############################################################
function bx_db_connect() {
 global $db_link;
 if ( strcmp ( DB_SERVER_TYPE, "mysql" ) == 0 ) {
    $db_link = mysql_connect ( DB_SERVER, DB_SERVER_USERNAME, DB_SERVER_PASSWORD );
    if ( $db_link ) {
      if ( ! mysql_select_db ( DB_DATABASE ) )
        return false;
      return $db_link;
    } else {
      return false;
    }
  } else if ( strcmp ( DB_SERVER_TYPE, "oracle" ) == 0 ) {
    if ( strlen ( DB_SERVER ) && strcmp ( DB_SERVER, "localhost" ) )
      $db_link = OCIPLogon ( "".DB_SERVER_USERNAME."@".DB_SERVER."", DB_SERVER_PASSWORD, DB_DATABASE );
    else
      $db_link = OCIPLogon ( DB_SERVER_USERNAME, DB_SERVER_PASSWORD, DB_DATABASE );
    $GLOBALS["oracle_connection"] = $db_link;
    return $db_link;
  } else if ( strcmp ( DB_SERVER_TYPE, "postgresql" ) == 0 ) {
    $db_link = pg_connect ( "host=".DB_SERVER." dbname=".DB_DATABASE." user=".DB_SERVER_USERNAME."" );
    $GLOBALS["postgresql_connection"] = $db_link;
    if ( ! $db_link ) {
        echo "Error connecting to database\n";
        exit;
    }
    return $db_link;
  } else if ( strcmp ( DB_SERVER_TYPE, "odbc" ) == 0 ) {
    $db_link = odbc_pconnect ( DB_DATABASE, DB_SERVER_USERNAME, DB_SERVER_PASSWORD );
    $GLOBALS["odbc_connection"] = $db_link;
    return $db_link;
  } else {
    bx_db_fatal_error ( "dbi_connect(): db_type not defined." );
  }
}

/*function bx_db_query($db_query) {
    global $db_link,$bx_temp_query;
    $bx_temp_query=$db_query;
    $result = mysql_query($db_query, $db_link);


    return $result;
  }
*/
// Execute an SQL query
function bx_db_query ( $sql , $execute=true ) {
    global $db_link,$bx_temp_query, $bx_query_time;
    $bx_temp_query=$sql;
  if ( strcmp ( DB_SERVER_TYPE, "mysql" ) == 0 ) {
      if ($execute) {
          $query_time = microtime();
          $query_start = explode(' ', $query_time);
          $res = @mysql_query ( $sql, $db_link );
          $query_end = explode(' ', microtime());
          $bx_query_time = number_format(($query_end[1] + $query_end[0] - ($query_start[1] + $query_start[0])), 3);
          return $res;
      }
      else {
          echo "<br><font style=\"font-size: 12px; color: #FF0000\">".$sql."</font><br>";
      }
  } else if ( strcmp ( DB_SERVER_TYPE, "oracle" ) == 0 ) {
    $GLOBALS["oracle_statement"] =
      OCIParse ( $GLOBALS["oracle_connection"], $sql );
    return OCIExecute ( $GLOBALS["oracle_statement"],
      OCI_COMMIT_ON_SUCCESS );
  } else if ( strcmp ( DB_SERVER_TYPE, "postgresql" ) == 0 ) {
    $GLOBALS["postgresql_row"] = 0;
    $GLOBALS["postgresql_row"] = 0;
    $res =  pg_exec ( $GLOBALS["postgresql_connection"], $sql );
    if ( ! $res ) {
        bx_db_fatal_error ( "Error executing SQL" );
    }
    $GLOBALS["postgresql_numrows"] = pg_numrows ( $res );
    return $res;
  } else if ( strcmp ( DB_SERVER_TYPE, "odbc" ) == 0 ) {
    $GLOBALS["odbc_row"] = 0;
    return odbc_exec ( $GLOBALS["odbc_connection"], $sql );
  } else {
    bx_db_fatal_error ( "dbi_query(): db_type not defined." );
  }
}
// Close a database connection
// Not necessary for any database that uses pooled connections
// such as MySQL
function bx_db_close () {
   global $db_link;
  if ( strcmp ( DB_SERVER_TYPE, "mysql" ) == 0 ) {
    return @mysql_close ( $db_link );
  } else if ( strcmp ( DB_SERVER_TYPE, "oracle" ) == 0 ) {
    return OCILogOff ( $db_link );
  } else if ( strcmp ( DB_SERVER_TYPE, "postgresql" ) == 0 ) {
    return pg_close ( $GLOBALS["postgresql_connection"] );
  } else if ( strcmp ( DB_SERVER_TYPE, "odbc" ) == 0 ) {
    return odbc_close ( $GLOBALS["odbc_connection"] );
  } else {
    bx_db_fatal_error ( "dbi_close(): db_type not defined." );
  }

}


// Select the database that all queries should use
//function dbi_select_db ( DB_DATABASE ) {
//  if ( strcmp ( DB_SERVER_TYPE, "mysql" ) == 0 ) {
//    return mysql_select_db ( DB_DATABASE );
//  } else if ( strcmp ( DB_SERVER_TYPE, "oracle" ) == 0 ) {
//    // Not supported.  Must sent up a tnsname and user that uses
//    // the correct tablesapce.
//    return true;
//  } else {
//    bx_db_fatal_error ( "dbi_select_db(): db_type not defined." );
//  }
//}

/*
 function bx_db_num_rows($db_query) {

    $result = mysql_num_rows($db_query);

    return $result;
  }
*/

// Determine the number of rows from a result
function bx_db_num_rows ( $res ) {
  if ( strcmp ( DB_SERVER_TYPE, "mysql" ) == 0 ) {
    return @mysql_num_rows ( $res );
  } else {
    bx_db_fatal_error ( "dbi_num_rows(): db_type not defined." );
  }
}

/*
 function bx_db_fetch_array($db_query) {

    $result = mysql_fetch_array($db_query);

    return $result;
  }
*/
// Retrieve a single row from the database and return it
// as an array.
// Note: we don't use the more useful xxx_fetch_array because not all
// databases support this function.
function bx_db_fetch_array ( $res ) {
  if ( strcmp ( DB_SERVER_TYPE, "mysql" ) == 0 ) {
    return @mysql_fetch_array ( $res );
  } else if ( strcmp ( DB_SERVER_TYPE, "oracle" ) == 0 ) {
    if ( OCIFetchInto ( $GLOBALS["oracle_statement"], $row,
      OCI_NUM + OCI_RETURN_NULLS  ) )
      return $row;
    return 0;
  } else if ( strcmp ( DB_SERVER_TYPE, "postgresql" ) == 0 ) {
    if ( $GLOBALS["postgresql_numrows"]  > $GLOBALS["postgresql_row"] ) {
        $r =  pg_fetch_array ( $res, $GLOBALS["postgresql_row"]++ );
        if ( ! $r ) {
            echo "Unable to fetch row\n";
            return '';
        }
    }
    else {
        $r = '';
    }
    return $r;
  } else if ( strcmp ( DB_SERVER_TYPE, "odbc" ) == 0 ) {
    $num_fields = odbc_num_fields ( $res );
    if ( ! odbc_fetch_into ( $res, $GLOBALS["odbc_row"], $ret ) )
      return false;
    return $ret;
  } else {
    bx_db_fatal_error ( "dbi_fetch_row(): db_type not defined." );
  }
}

/*
function bx_db_free_result($db_query) {

    $result = mysql_free_result($db_query);

    return $result;
  }
*/
// Free a result set.
// This isn't really necessary for PHP4 since this is done automatically,
// but it's a good habit for PHP3.
function bx_db_free_result ( $res ) {
  if ( strcmp ( DB_SERVER_TYPE, "mysql" ) == 0 ) {
    return @mysql_free_result ( $res );
  } else if ( strcmp ( DB_SERVER_TYPE, "oracle" ) == 0 ) {
    // Not supported.  Ingore.
    if ( $GLOBALS["oracle_statement"] >= 0 ) {
      OCIFreeStatement ( $GLOBALS["oracle_statement"] );
      $GLOBALS["oracle_statement"] = -1;
    }
  } else if ( strcmp ( DB_SERVER_TYPE, "postgresql" ) == 0 ) {
    return pg_freeresult ( $res );
  } else if ( strcmp ( DB_SERVER_TYPE, "odbc" ) == 0 ) {
    return odbc_free_result ( $res );
  } else {
    bx_db_fatal_error ( "dbi_free_result(): db_type not defined." );
  }
}


// Get the latest db error message.
function bx_db_error () {
  if ( strcmp ( DB_SERVER_TYPE, "mysql" ) == 0 ) {
    $ret = @mysql_error ();
  } else if ( strcmp ( DB_SERVER_TYPE, "oracle" ) == 0 ) {
    $ret = OCIError ( $GLOBALS["oracle_connection"] );
  } else if ( strcmp ( DB_SERVER_TYPE, "postgresql" ) == 0 ) {
    $ret = pg_errormessage ( $GLOBALS["postgresql_connection"] );
  } else if ( strcmp ( DB_SERVER_TYPE, "odbc" ) == 0 ) {
    // no way to get error from ODBC API
    $ret = "Unknown ODBC error";
  } else {
    $ret = "bx_db_error(): db_type not defined.";
  }
  if ( strlen ( $ret ) )
    {
        return $ret;
        }
  else
    {
    return "Unknown error";
        }
}


// display an error message and exit
function bx_db_fatal_error ( $msg ) {
  echo "<H2>Error</H2>\n";
  echo "<!--begin_error(dbierror)-->\n";
  echo "$msg\n";
  echo "<!--end_error-->\n";
  exit;
}

//database conection functions
 function bx_db_data_seek($db_query, $row_number) {

    $result = @mysql_data_seek($db_query, $row_number);

    return $result;
  }

  function bx_db_insert_id() {

    $result = @mysql_insert_id();

    return $result;
  }
 
  function bx_db_insert($db_table,$db_fields,$db_values) {
		return bx_db_query("insert into ".$db_table." ($db_fields)"." values ($db_values)");
  }
  function bx_db_count_results($SQL)
{
	return mysql_num_rows(mysql_query($SQL));
}

?>