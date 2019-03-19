<?php
set_time_limit(300);
define ("DIR_SERVER_ROOT","/home/www/local/computer3/html/work/banner-managers/php-bm2.0/ads/");
//define ("DIR_SERVER_ROOT_TARGET","/home/www/local/computer3/html/work/banner-managers/php-bm2.0/ads/");
//define ("DIR_SERVER_ROOT_VARS","/home/www/local/computer3/html/work/banner-managers/php-bm2.0/ads/languages/english/");
define ("DIR_SERVER_ROOT_VARS","/home/www/local/computer3/html/work/banner-managers/php-bm2.0/ads/languages/english/");
$target_folder_list[0]="/home/www/local/computer3/html/work/banner-managers/php-bm2.0/ads/";
$target_folder_list[1]="/home/www/local/computer3/html/work/banner-managers/php-bm2.0/ads/admin/";
$target_folder_list[2]="/home/www/local/computer3/html/work/banner-managers/php-bm2.0/ads/forms/";
$target_folder_list[3]="/home/www/local/computer3/html/work/banner-managers/php-bm2.0/ads/functions/";
$target_folder_list[4]="/home/www/local/computer3/html/work/banner-managers/php-bm2.0/ads/functions/cclib/";
$target_folder_list[5]="/home/www/local/computer3/html/work/banner-managers/php-bm2.0/ads/js/";

include ("functions/general.php");	 
$var_files = getFiles(DIR_SERVER_ROOT_VARS);
for ($n=0; $n<sizeof($var_files); $n++)
{
	if (eregi("\.php",$var_files[$n]))
	{
		echo $n.' '.$var_files[$n].' <br>'; //a file neve
		$fcontents = file (DIR_SERVER_ROOT_VARS.$var_files[$n]);
		while (list ($line_num, $line) = each ($fcontents)) {
		   //echo "<b>Line $line_num:</b>; ", htmlspecialchars ($line), "<br>\n"; //sorok a fileban
		   if (ereg('(^define\(\')(.*)(\',\'.*)$', $line, $regs))
		   {
				$s_var = $regs['2']; //ez a keresett konstans
				//echo $s_var.'<br>';
				///// KERESES
				$found = 0;
				for ($folder_nr=0; $folder_nr<sizeof($target_folder_list) ; $folder_nr++) //search all folders
				{		
					if ($found==1)
					{
						break;
					}
						$target_files = getFiles($target_folder_list[$folder_nr]);
						for ($i=0; $i<sizeof($target_files); $i++) //search all files
						//for ($i=0; $i<1; $i++)
						{
							if ($found==1)
							{
								break;
							}
							if (eregi("\.php",$target_files[$i]) or eregi("\.js",$target_files[$i]))
							{
								//echo $i.' '.$target_files[$i].' <br>'; //a keresett file neve
								$target_contents = file ($target_folder_list[$folder_nr].$target_files[$i]);
								while (list ($line_nr, $target_line) = each ($target_contents)) {
									//echo "<b>Line $line_nr:</b>; ", htmlspecialchars ($target_line), "<br>\n"; //sorok a fileban
									//echo $target_files[$i]."<br>";
									if (ereg('^(.*)'.$s_var.'(.*)$',$target_line))
									//if (ereg('^(.*)TO_DELETE_VAR(.*)$',$target_line))
									{
										$found=1;
										break;
									}
								}
							}
						}
				}
				if ($found==0)
					{
						echo $s_var.' in file: '.$var_files[$n].' is NOT USED<br>';
					}

		   }
		}
	}
}
$var_folders = getFolders(DIR_SERVER_ROOT);
?>