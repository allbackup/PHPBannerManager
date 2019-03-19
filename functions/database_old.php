<?
function bx_db_connect() {
	global $db_link;
	$db_link = mysql_connect ( DB_SERVER, DB_SERVER_USERNAME, DB_SERVER_PASSWORD );
	if ($db_link) 
	{
		if (!mysql_select_db(DB_DATABASE))
			return false;
		return $db_link;
	}
	else 
	  return false;
}


function bx_db_query ( $sql )
{
	global $db_link,$bx_temp_query;
	$bx_temp_query=$sql;
	
	return mysql_query ( $sql, $db_link );
}

function bx_db_affected_rows ( )
{
	global $db_link;
	return mysql_affected_rows ( $db_link );
}


function bx_db_num_rows ( $res ) 
{
	return mysql_num_rows ( $res );
}


function bx_db_fetch_array ( $res ) 
{
		return mysql_fetch_array ( $res );
}


function bx_db_free_result ( $res )
{
		return mysql_free_result ( $res );
}



function bx_db_error ()
{
	$ret = mysql_error ();
	
	if ( strlen ( $ret ) )
		return $ret;
	else
		return "Unknown error";
}


function bx_db_fatal_error ( $msg )
{
	echo "<H2>Error</H2>\n";
	echo "<!--begin_error(dbierror)-->\n";
	echo "$msg\n";
	echo "<!--end_error-->\n";
	exit;
}


function bx_db_count_results($SQL)
{
	return mysql_num_rows(mysql_query($SQL));
}


function bx_db_data_seek($db_query, $row_number)
{
	return mysql_data_seek($db_query, $row_number);
}


function bx_db_insert_id() 
{
    global $db_link;
    $result = @mysql_insert_id($db_link);
    return $result;
}


function bx_db_insert($db_table,$db_fields,$db_values, $echo=0)
{
	if($echo)
		echo "insert into ".$db_table." ($db_fields)"." values ($db_values)";
	else
		return bx_db_query("insert into ".$db_table." ($db_fields)"." values ($db_values)");
}
?>