<?php
$fields[1]["name"]="Site name (will appear in email message as sender name)";
$fields[1]["define"]="SITE_NAME";
$fields[1]["default"]="Php Jobsite 2.02";
$fields[2]["name"]="Site Title ( the site title will appear in the browser as the page title)";
$fields[2]["define"]="SITE_TITLE";
$fields[2]["default"]="Php Jobsite 2.02";
$fields[3]["name"]="Encryption Phrase";
$fields[3]["define"]="CRYPT_PHRASE";
$fields[3]["default"]="This is a long phrase used to encrypt sensitive data here";
$fields[4]["name"]="Admin Email Address ( were email regarding payment, will be sent)";
$fields[4]["define"]="SITE_MAIL";
$fields[4]["default"]="install@bitmixsoft.c0m";
$fields[5]["name"]="number of displayed items in a search/list";
$fields[5]["define"]="NR_DISPLAY";
$fields[5]["default"]="10";
$fields[6]["name"]="session expires after specified number of minutes/then the user have to login again";
$fields[6]["define"]="SESSION_EXPIRES";
$fields[6]["default"]="300";
$fields[7]["comment"][]="Only set the Debug Mode to yes when you want to see the database error messages";
$fields[7]["name"]="(radio yes,no)Debug Mode";
$fields[7]["define"]="DEBUG_MODE";
$fields[7]["default"]="no";
$fields[8]["comment"][]="Writing information to the log file (date, IP address, Host, Browser type, compilation time etc.)";
$fields[8]["comment"][]="For more details on how is this look like please visit [Script Management]->[Log file Statistics]";
$fields[8]["name"]="(radio on,off)write log file";
$fields[8]["define"]="STORE_PAGE_PARSE_TIME";
$fields[8]["default"]="on";
$fields[9]["comment"][]="";
$fields[9]["name"]="(radio yes,no)Use IP Address Filter";
$fields[9]["define"]="USE_IP_FILTER";
$fields[9]["default"]="no";
$fields[10]["comment"][]="Comma (,) separated list with all the IP Address which are not allowed to access your site";
$fields[10]["name"]="Ip Address Filter List";
$fields[10]["define"]="IP_FILTER_LIST";
$fields[10]["default"]="";
$fields[11]["name"]="(radio yes,no)Exclude IP Address from Log Statitistics and do not count banner clicks and views?";
$fields[11]["define"]="IP_EXCLUDE_LOG";
$fields[11]["default"]="no";
$fields[12]["comment"][]="Comma (,) separated list with all the IP Address which should not be loged in statitistic pages.Click <a href=\"javascript: ;\" onclick=\"newwin=window.open('php_stat.php#ipaddress','_blank','width=300,height=150,scrollbars=yes,menubar=no,resizable=yes,location=no');\" class=\"settings\">here</a> to see your IP Address.";
$fields[12]["name"]="Exclude IP Address List";
$fields[12]["define"]="IP_EXCLUDE_LIST";
$fields[12]["default"]="192.168.1.1, 192.168.1.2";
$fields[13]["comment"][]="The default language used by the script, the language have to exist (created in this Admin Area), or use the original english language, which comes with the script";
$fields[13]["name"]="default language";
$fields[13]["define"]="DEFAULT_LANGUAGE";
$fields[13]["default"]="english";
$fields[14]["comment"][]="Multilanguage support option, possible values are on - off";
$fields[14]["comment"][]="If you set it to off the default language will be used as the only language available.";
$fields[14]["name"]="(radio on,off)multilanguage support";
$fields[14]["define"]="MULTILANGUAGE_SUPPORT";
$fields[14]["default"]="on";
$fields[15]["comment"][]="If you set the this option to yes, all information posted either by jobseeker or employer will be searched/replaced for words from the list above";
$fields[15]["name"]="(radio yes,no)Use Dirty words filter?";
$fields[15]["define"]="USE_DIRTY_WORDS";
$fields[15]["default"]="yes";
$fields[16]["comment"][]="Allow employers/jobseekers to select/get html or plain text email messages";
$fields[16]["name"]="(radio yes,no)Allow HTML Email messages?";
$fields[16]["define"]="ALLOW_HTML_MAIL";
$fields[16]["default"]="yes";
$fields[17]["name"]="currency sign for job/resume purchase";
$fields[17]["define"]="PRICE_CURENCY";
$fields[17]["default"]="USD";
$fields[18]["name"]="(radio right,left)currency sign position";
$fields[18]["define"]="CURRENCY_POSITION";
$fields[18]["default"]="right";
$fields[19]["name"]="(radio yes,no)Use VAT  - Value Added Tax?";
$fields[19]["define"]="USE_VAT";
$fields[19]["default"]="no";
$fields[20]["name"]="VAT Percent";
$fields[20]["define"]="VAT_PROCENT";
$fields[20]["default"]="11.11";
$fields[21]["name"]="Meta Keywords";
$fields[21]["define"]="META";
$fields[21]["default"]="<meta name=\"copyright\" content=\"Copyright  2002 -  All rights reserved.\">'.\"\"\"\"\"\"\"\"\"\"\"\"\"\"\"\"\"\"\"\"\"\"\"\"\"\"\"\"\"\"\"\"\"\"\"\"\"\"\"\"\"\"\"\"\"\"\"\"\"\"\"\"\"\"\"\"\"\"\"\"\"\"\"\"\"\"\"\"\"\"\"\"\"\"\"\"\"\"\"\"\"\"\"\"\"\"\"\"\"\n\"\"\"\"\"\"\"\"\"\"\"\"\"\"\"\"\"\"\"\"\"\"\"\"\"\"\"\"\"\"\"\"\"\"\"\"\"\"\"\"\"\"\"\"\"\"\"\"\"\"\"\"\"\"\"\"\"\"\"\"\"\"\"\"\"\"\"\"\"\"\"\"\"\"\"\"\"\"\"\"\"\"\"\"\"\"\"\"\".'<meta name=\"keywords\" content=\"g, g, g, g posting\">'.\"\"\"\"\"\"\"\"\"\"\"\"\"\"\"\"\"\"\"\"\"\"\"\"\"\"\"\"\"\"\"\"\"\"\"\"\"\"\"\"\"\"\"\"\"\"\"\"\"\"\"\"\"\"\"\"\"\"\"\"\"\"\"\"\"\"\"\"\"\"\"\"\"\"\"\"\"\"\"\"\"\"\"\"\"\"\"\"\"\n\"\"\"\"\"\"\"\"\"\"\"\"\"\"\"\"\"\"\"\"\"\"\"\"\"\"\"\"\"\"\"\"\"\"\"\"\"\"\"\"\"\"\"\"\"\"\"\"\"\"\"\"\"\"\"\"\"\"\"\"\"\"\"\"\"\"\"\"\"\"\"\"\"\"\"\"\"\"\"\"\"\"\"\"\"\"\"\"\".'<meta name=\"description\" content=\"g\">";
?>