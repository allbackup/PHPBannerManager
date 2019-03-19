<?php
include('admin_design.php');
include('../application_config_file.php');
include ('admin_auth.php');
@set_time_limit(0);
if ($HTTP_GET_VARS['t'] == "download") {
	$crlf="\n";
    header("Content-disposition: filename=".date('m-d-Y')."-".$HTTP_SERVER_VARS['SERVER_NAME'].".log");
    header("Content-type: application/octetstream");
    header("Pragma: no-cache");
    header("Expires: 0");
	$fp = fopen(DIR_SERVER_ROOT.'logs/parse_time_log','r');    
	
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
	while (!feof($fp)) {
            $buff = fgets($fp, 20000);
    	     echo trim($buff).$crlf;
    }
	fclose($fp);
	bx_exit();
}//end if t==download
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta name="description" content="">
<meta name="keywords" content="">
<meta name="history" content="">
 
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $CHARSET_OPTION;?>">
<title><?php echo SITE_TITLE;?>  :: Log file Information</title>
<style type="text/css" title="log file">
	A:link, A:visited {color:#666699; text-decoration: none; font-weight:bold;}
	A:hover {color: #FF0000; text-decoration: none; font-weight:bold;}
	.stat {color: #000000; background: #ffffff;}
	.time {font-weight: bold;}
	p {margin-top: 0px; margin-bottom: 0px;}
	TD {color: #000000; background: #f5f5f5; font-size: 11px; font-family: Verdana, arial;}
	TH {color: #FF0000; background: #f5f5f5; text-decoration: none; font-weight:bold;}
</style>
</head>
<body>
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="2" bgcolor="#BBBBBB">
<?php
if ($HTTP_GET_VARS['t'] == "empty") {
	$fp = fopen(DIR_SERVER_ROOT.'logs/parse_time_log','w');    
	fclose($fp);
}
if ($HTTP_GET_VARS['detailed']) {
    echo "<tr><th width=\"16%\">ID</th><th width=\"16%\">Time</th><th width=\"16%\">IP - Host</th><th width=\"16%\">Browser</th><th width=\"16%\">Date</th><th width=\"16%\">Url</th></tr></table>";
}
$fp = fopen(DIR_SERVER_ROOT.'logs/parse_time_log','r');
$times = array();
$hosts = array();
$i=1;
while (!feof($fp)) {
	$buff = fgets($fp, 20000);
	//echo $buff;
	if (eregi("\(([0-9.]+).*",$buff, $regs)) {
		//echo "<font color=red>".$regs[1]."</font>";
		$times[] = $regs[1];
		if ($HTTP_GET_VARS['detailed']) {
			echo "<p style=\"white-space: nowrap;\"><u><i>$i</i></u>&nbsp;&nbsp;<b>".$regs[1]."</b>";
			$parse = split(" - ", $buff);
			echo "&nbsp;&nbsp;".$parse[0]." - ".$parse[1]."&nbsp;&nbsp;".$parse[2]."&nbsp;&nbsp;".$parse[3];
			eregi("(.*)\(([0-9.]+).*",$parse[4], $preg);
			echo "&nbsp;&nbsp;<i>".$preg[1]."</i></p>";
			$hosts[] = $parse[1];
			$i++;
		}
	}
	//echo "<br>";
}
fclose($fp);
echo "<table align=\"center\" width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"2\"><tr><td class=\"stat\"><br>Number of records:".sizeof($times)."</td></tr>";
if (sizeof($times)) {
$times=array_unique($times);
sort($times, SORT_NUMERIC);
$min = $times[0];
$max = $times[sizeof($times)-1];
echo "<tr><td class=\"stat\">Min is: <b>".$min."</b></td></tr>";
echo "<tr><td class=\"stat\">Max is: <b>".$max."</b></td></tr>";
echo "<tr><td class=\"stat\">Avg is: <b>".array_sum($times)/sizeof($times)."</b></td></tr>";
echo "<tr><td class=\"stat\">Log File Size is: <b>".number_format(filesize(DIR_SERVER_ROOT.'logs/parse_time_log')/1024,1,'.',',')." Kbytes</b></td></tr>";

$hosts = array_unique($hosts);
if ($HTTP_GET_VARS['detailed']) {
   echo "<tr><td class=\"stat\">Unique hosts: <font color=red><b>".sizeof($hosts)."</b><font></td></tr>";
}
}
echo "<tr><td class=\"stat\"><br><b>[ </b><a href=\"index.php\">Admin Home</a> <b>]</b>&nbsp;&nbsp;<b>[ </b><a href=\"timestat.php?detailed=1\">Detailed log</a> <b>]</b>&nbsp;&nbsp;<b>[</b> <a href=\"timestat.php?t=empty\">Empty logfile</a> <b>]</b><b>&nbsp;&nbsp;[</b> <a href=\"timestat.php?t=download\">Download logfile</a> <b>]</b></td></tr>";
?>
</table>
</body>
</html>
<?php bx_exit();?>