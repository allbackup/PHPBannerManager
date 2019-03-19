<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>

<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta name="description" content="">
<meta name="keywords" content="">
<meta name="history" content="">
 
<title>PhpJobsite Calendar</title>
<style type="text/css" title="">
 .cal          {COLOR: #000000; TEXT-DECORATION: none; FONT-FAMILY:  Arial, Helvetica, sans-serif; font-size: 11px; }
 .cal:link    {COLOR: #336699; TEXT-DECORATION: none; FONT-FAMILY:  Arial, Helvetica, sans-serif; font-size: 11px; font-weight: bold}
 .cal:visited {COLOR: #336699; TEXT-DECORATION: none; FONT-FAMILY:  Arial, Helvetica, sans-serif;font-size:  11px; font-weight: bold}
 .cal:hover   {COLOR: #888888; TEXT-DECORATION: underline; FONT-FAMILY:  Arial, Helvetica, sans-serif; font-size: 11px;}
  BODY { margin-top: 0px; margin-bottom: 0px;}
</style>
</head>

<body bgcolor="#C0C0C0">
<?php
function generate_calendar($year, $month, $tday, $forminput){
    $day_heading_length = 2;
    $first_of_month = mktime (0,0,0, $month, 1, $year);
    
    static $day_headings = array("Sunday","Monday","Tuesday","Wednesday","Thursday","Friday","Saturday");
           $month_words = array("01"=>"January","02"=>"February","03"=>"March","04"=>"April","05"=>"May","06"=>"June","07"=>"July","08"=>"August","09"=>"September","10"=>"October","11"=>"November","12"=>"December",);
    $maxdays   = date('t', $first_of_month); #number of days in the month
    $date_info = getdate($first_of_month);   #get info about the first day of the month

    $calendar  = "<form method=\"post\" action=\"calendar.php\"><table bgcolor=\"#000000\" cellpadding=\"0\" cellspacing=\"1\" border=\"0\" align=\"center\" valign=\"middle\"><tr><td><table bgcolor=\"#C0C0C0\" cellpadding=\"1\" cellspacing=\"1\" border=\"0\">\n";
    $calendar .= "<tr><th colspan=\"7\" class=\"cal\"><select class=\"call\" name=\"month\" onChange=\"document.location = 'calendar.php?y=".$year."&m='+this.options[this.selectedIndex].value+'&d=".$tday."'+'&formname=".$forminput."';\">";
    while (list($month_no, $month_name) = each($month_words)) {
        $calendar .= "<option value=\"".$month_no."\"";
        if ($month_no == $month) {
            $calendar .= " selected";
        }
        $calendar .= ">".$month_name."</option>";
    }
    $calendar .= "</select><select name=\"year\" onChange=\"document.location = 'calendar.php?y='+this.options[this.selectedIndex].value+'&m=".$month."&d=".$tday."&formname=".$forminput."';\">";

    for ($i=($year-10);$i<($year+10);$i++ ) {
        $calendar .= "<option value=\"".$i."\"";
        if ($i == $year) {
            $calendar .= " selected";
        }
        $calendar .= ">".$i."</option>";
    }
    $calendar .= "</select></th></tr>\n";    
    $calendar .= "<tr><th colspan=\"7\" class=\"cal\">" .$tday." ". $date_info["month"] . ", " . $date_info["year"] . "</th></tr>\n";

    #print the day headings "Mon", "Tue", etc.
    # if day_heading_length is 4, the full name of the day will be printed
    # otherwise, just the first n characters
    if($day_heading_length > 0 and $day_heading_length <= 4){
        $calendar .= "<tr bgcolor=\"#666699\">";
        foreach($day_headings as $day_heading){
            $calendar .= "<th class=\"smalltext\"><font color=\"#FFFFFF\">" . 
                ($day_heading_length != 4 ? substr($day_heading, 0, $day_heading_length) : $day_heading) .
            "</font></th>";
        }
        $calendar .= "</tr>\n";
    }
    $calendar .= "<tr>";

    $weekday = $date_info["wday"]; #weekday (zero based) of the first day of the month
    $day = 1; #starting day of the month
    #take care of the first "empty" days of the month
    if($weekday > 0){$calendar .= "<td colspan=\"$weekday\">&nbsp;</td>";}

    #print the days of the month
    while ($day <= $maxdays){
        #if a linking function is provided
        if ($day == $tday) {
            $bgcolor = "#D5D5EA";
        }
        else {
            $bgcolor = "#F5F5F5";
        } 
        $calendar .= "<td bgcolor=\"".$bgcolor."\" align=\"center\"><a href=\"javascript: ;\" onClick=\"opener.document.".$forminput.".value='".$year."-".$month."-".($day<=9 ? "0".$day : $day)."'; window.close();\" class=\"cal\">$day</a></td>";
        
        $day++;
        $weekday++;

        if($day > $maxdays and $weekday != 7){#pad the end of the html table
            $calendar .= "<td colspan=\"" . (7 - $weekday) . "\">&nbsp;</td>";
        }elseif($weekday == 7){ #start a new week
            $calendar .= "</tr>\n<tr>";
            $weekday = 0;
        }
    }
    return $calendar . "</tr>\n</table></td></tr></table></form>\n";
}
if ($HTTP_GET_VARS['y'] && $HTTP_GET_VARS['y']!="0000") {
    $tyear = $HTTP_GET_VARS['y'];
}
else {
    $tyear = date('Y');
}
if ($HTTP_GET_VARS['m'] && $HTTP_GET_VARS['m']!="00") {
    $tmonth = $HTTP_GET_VARS['m'];
}
else {
    $tmonth = date('m');
}
if ($HTTP_GET_VARS['d'] && $HTTP_GET_VARS['d']!="00") {
    $tdate = $HTTP_GET_VARS['d'];
}
else {
    $tdate = date('d');
}
$curr_date = date('Y-m-d', mktime(0,0,0,$tmonth, $tdate, $tyear));
echo generate_calendar($tyear, $tmonth, $tdate, $HTTP_GET_VARS['formname']);
?>
</body>
</html>