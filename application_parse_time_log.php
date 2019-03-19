<?php
//closing database and writing log_file
bx_db_close(); //use it only when not persistent connection is used
if (STORE_PAGE_PARSE_TIME == 'on' && $HTTP_GET_VARS['bx_count']!="no") {
    $parse_end_time = microtime();
    $time_start = explode(' ', $parse_start_time);
    $time_end = explode(' ', $parse_end_time);
    $parse_time = number_format(($time_end[1] + $time_end[0] - ($time_start[1] + $time_start[0])), 3);
    if ($HTTP_SERVER_VARS["HTTP_X_FORWARDED_FOR"] != ""){
          $IP = $HTTP_SERVER_VARS["HTTP_X_FORWARDED_FOR"];
          $proxy = $HTTP_SERVER_VARS["REMOTE_ADDR"];
          $host = @gethostbyaddr($HTTP_SERVER_VARS["HTTP_X_FORWARDED_FOR"]);
    }
    else {
          $IP = $HTTP_SERVER_VARS['REMOTE_ADDR'];
          $host = @gethostbyaddr($HTTP_SERVER_VARS['REMOTE_ADDR']);
    }
    $write_log = true;    
    if(IP_EXCLUDE_LOG=="yes") {
            $iplist = split(",",trim(IP_EXCLUDE_LIST));
            for ($i=0; $i<sizeof($iplist); $i++) {
                if(trim($iplist[$i]) == $IP) {
                    $write_log = false;
                    break;
                }
            }    
    }
    if($write_log) {
            error_log($IP.' - '.$host.' - '.(getenv(HTTP_USER_AGENT)?getenv(HTTP_USER_AGENT):$HTTP_SERVER_VARS['HTTP_USER_AGENT']).' - ' .strftime(STORE_PARSE_DATE_TIME_FORMAT) . ' - ' .(getenv(REQUEST_URI)?getenv(REQUEST_URI):$HTTP_SERVER_VARS['REQUEST_URI']). ' (' . $parse_time . 'ms)' . "\n", 3, STORE_PAGE_PARSE_TIME_LOG);
    }
}
?>