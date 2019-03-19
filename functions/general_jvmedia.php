<?php
############################################################
# php-Jobsite(TM)                                          #
# Copyright © 2002 BitmixSoft. All rights reserved.        #
#                                                          #
#  /php-jobsite/                   #
# File: general.php                                        #
# Last update: 5/8/2002                                    #
############################################################
//user defined function file
function replace_link($text_with_links,$new_url)
{
	preg_match_all("|href=[\"\']?([^\"' >]+)|i",$text_with_links, $matches);
	for ($i=0; $i < count($matches[0]); $i++) 
		$text_with_links=ereg_replace($matches[1][$i],$new_url."?url=".(strstr($matches[1][$i], "http://") ? "" : "http://").$matches[1][$i],$text_with_links);
	return $text_with_links;
}

function generate_weight($selected)
{
    for ($i=1;$i<11;$i++)
    	$retValue .= '<option value="'.$i.'"'.($i==$selected ? " selected" : "").'>'.$i.'</option>';
	return $retValue;
}

function bx_image_compare($p_photo, $p_compare, $p_width, $p_height, $file="0", $p_size="0")
{	//file mean a file from server not a post file
	if( !file_exists($p_photo['tmp_name']) )
		return false;

	if($p_photo['tmp_name'] == "none" || $p_photo['tmp_name'] == "")
		return false;

	if($file == "0")
		$photo_info = @GetImageSize($p_photo['tmp_name']);
	else
		$photo_info = @GetImageSize($p_photo);
	$photo_width = $photo_info[0];
	$photo_height = $photo_info[1];
	$photo_size_string = $photo_info[3];
	switch($photo_info[2])
	{
		case 1:		$photo_ext = ".gif";	break;
		case 2:		$photo_ext = ".jpg";	break;
		case 3:		$photo_ext = ".png";	break;
		case 4:		$photo_ext = ".swf";	break;
	}
	//echo $photo_width ." . ". $p_width ." . ". $photo_height ." . ". $p_height;

	if ($p_compare == ">")
	{
		if($p_size != '0')
			if ($p_photo['size'] < $p_size)
				return false;

		if ($photo_width > $p_width && $photo_height > $p_height)
			return true;
		else
			return false;
	}
	elseif ($p_compare == "=")
	{
		if($p_size != '0')
			if ($p_photo['size'] > $p_size)
				return false;

		if ($photo_width == $p_width && $photo_height == $p_height)
			return true;
		else
			return false;
	}
	elseif ($p_compare == "<")
	{
		if($p_size != '0')
			if ($p_photo['size'] > $p_size)
				return false;

		if ($photo_width <= $p_width && $photo_height <= $p_height)
			return true;
		else
			return false;
	}
	else{}
}

function resizeToFile ($sourcefile, $dest_x, $dest_y, $targetfile, $jpegqual, $gd_type)
{
$gd_type=2;
/* Get the dimensions of the source picture */
$picsize=getimagesize("$sourcefile");
$source_x = $picsize[0];
$source_y  = $picsize[1];

  $w = $source_x; // width
  $h = $source_y; // height
  $maxsize = $dest_x; // maximum size in one direction
  // prevent division by zero
  if (($h ==0) || ($w==0)) {
     $new_h = $dest_x;
     $new_w = $dest_y;
  }
  if ($h>$w) {
     $new_h = $maxsize; // new height
     $new_w = (int) (($maxsize * $w) / $h); // casting to int !
  } else {
     $new_w = $maxsize; // new width
     $new_h = (int) (($maxsize * $h) / $w); // casting to int !
  }
  
$source_id = imageCreateFromJPEG("$sourcefile");

/* Create a new image object (not neccessarily true colour) */

if ($gd_type=="2") {
    if(function_exists(imagecreatetruecolor)){
           if (!$target_id = imagecreatetruecolor($new_w, $new_h)) {
                 return false;
           }    
    }else{
           if (!$target_id = imagecreate($new_w, $new_h)) {
                return false;
           }
    }
}
else {
       if (!$target_id = imagecreate($new_w, $new_h)) {
            return false;
       }
}
/* Resize the original picture and copy it into the just created image
 object. Because of the lack of space I had to wrap the parameters to
several lines. I recommend putting them in one line in order keep your
 code clean and readable */
if ($gd_type=="2") {
    if (function_exists(imagecopyresampled)){
        if(!imagecopyresampled($target_id,$source_id,0,0,0,0,$new_w, $new_h,$source_x,$source_y)) {
            return false;
        }    
    }
    else{
        if(!imagecopyresized($target_id,$source_id,0,0,0,0,$new_w, $new_h,$source_x,$source_y)) {
            return false;
        }
    }
}
else {
    if(!imagecopyresized($target_id,$source_id,0,0,0,0,$new_w, $new_h,$source_x,$source_y)) {
        return false;
    }
}
/* Create a jpeg with the quality of "$jpegqual" out of the
 image object "$target_pic".
 This will be saved as $targetfile */
imagejpeg ($target_id,"$targetfile",$jpegqual);
return true;
}

function postvars($HTTP_POST_VARS)
{
	echo "<center><table border=\"0\" cellpadding=\"1\" align\"center\"><tr><td bgcolor=\"#CCCCFF\"><table align=\"center\" bgcolor=\"#ffffff\" width=\"100%\">";
	$i=0;
	while (list($h, $v) = each($HTTP_POST_VARS))
	{
		if($i%2 == 0)
		{
			echo "<tr bgcolor=\"#CCefFF\" valign=\"top\"><td><b><font size=\"2\" face=\"helvetica\">".$h."</font></b></td><td><font color=\"#FF9900\"> <b>=></b> </font></td><td><font size=\"2\" face=\"verdana\">";
			if(is_array($v) && sizeof($v) ==1)
				echo $v[0];
			elseif(sizeof($v) == 1)
				echo $v;
			else
			{
				echo "{";
				while (list($hor, $ver) = each($v))
				{
					$cycle1 ++;
					echo "[".$hor."] => "."'".$ver."'";
					if(sizeof($v) != $cycle1)
						echo ", ";
				}
				echo "}";
			}
			echo "</font></td></tr>";
		}
		else
		{
			echo "<tr bgcolor=\"#ffffef\" valign=\"top\"><td><b><font size=\"2\" face=\"helvetica\">".$h."</font></b></td><td><font color=\"#FF9900\"> <b>=></b> </font></td><td><font size=\"2\" face=\"verdana\">";
			if(is_array($v) && sizeof($v) ==1)
				echo $v[0];
			elseif(sizeof($v) == 1)
				echo $v;
			else
			{
				echo "{";
				while (list($hor, $ver) = each($v))
				{
					$cycle2 ++;
					echo "[".$hor."] => "."'".$ver."'";
					if(sizeof($v) != $cycle2)
						echo ", ";
				}
				echo "}";
			}
			echo "</font></td></tr>";
		}
		$i++;
	}
	echo "</table></td></tr></table></center>";
}


 function refresh($url) {
     print "<html><head><meta http-equiv=\"refresh\" content=\"0; URL=$url\"></head></html>\n";
  }
 function bx_mail($sitename,$sitemail,$emailaddress,$subject,$message,$html="no")
 {
  $headers .= "From: $sitename <$sitemail>\n";
  $headers .= "X-Sender: <$sitemail>\n";
  $headers .= "X-Mailer: PHP/" . phpversion()."\n"; // mailer
  $headers .= "X-Priority: 3\n"; // 1 for Urgent message!
  $headers .= "Return-Path: <$sitemail>\n";  // Return path for errors
  if ($html=="yes") {
      $headers .= "Content-Type: text/html; charset=iso-8859-1\n";
  }
  @mail($emailaddress, $subject, $message, $headers);
 }
 //exiting with closing the database
 function bx_exit(){
     bx_db_close();
     exit;
 }
 function bx_format_date($date, $format) {
	if ($format=="YYYY-mm-dd") {
	    return $date;
	}
	else {
		if (preg_match("/YYYY/i",$format)) {
			$format = eregi_replace("YYYY","Y", $format);    
		}
		elseif (preg_match("/YY/i",$format)) {
			$format = eregi_replace("YY","y", $format);    
		}
		if (preg_match("/mm/i",$format)) {
			$format = eregi_replace("mm","m", $format);    
		}
		elseif (preg_match("/m/i",$format)) {
			$format = eregi_replace("m","n", $format);    
		}
		if (preg_match("/dd/i",$format)) {
			$format = eregi_replace("dd","d", $format);    
		}
		elseif (preg_match("/d/i",$format)) {
			$format = eregi_replace("d","j", $format);    
		}
		return date($format, mktime(0,0,0,substr($date,5,2),substr($date,8),substr($date,0,4)));
	}
 }

function bx_format_price($price, $currency, $isprice=1) {
	if($isprice == 1) {
        if(eregi("1(.?)234(.?)(5|6|56)?$",trim(PRICE_FORMAT),$p_regs)) {
            $price = number_format($price,strlen($p_regs[3]),$p_regs[2],$p_regs[1]);    
        }
        else {
            $price = number_format($price,2);    
        }
    }
    if (CURRENCY_POSITION=="left") {
	    return $currency.$price;
	}
	else {
	    return $price." ".$currency;
	}
}

function bx_js_stripslashes($str) {
        $str = stripslashes($str);
        $str = str_replace('"',"&#034;",$str);
        $str = str_replace("'","&#039;",$str);
        return $str;
}

function bx_addslashes($str){
    if (get_magic_quotes_gpc()) {
        return $str;
    }
    else {
        return addslashes($str);
    }
}

function bx_dirty_words($str) {
    if(USE_DIRTY_WORDS == "yes") {
        $patterns = array();
        $replacement = array();
        $wordlist = split(",",trim(DIRTY_WORDS));
        for ($i=0;$i<sizeof($wordlist);$i++) {
                if(trim($wordlist[$i])!='') {
                    $patterns[] = "(".trim($wordlist[$i]).")";
                    if(strlen(DIRTY_WORDS_REPLACEMENT)==1) {
                        $replacement[] = eregi_replace(".", DIRTY_WORDS_REPLACEMENT, trim($wordlist[$i]));
                    }
                    else {
                        $replacement[] = DIRTY_WORDS_REPLACEMENT;
                    }
                }    
        }
        $str = preg_replace($patterns, $replacement, $str);
    }
    return htmlspecialchars(bx_addslashes($str));
}

function bx_textarea($text)
 {
  return stripslashes(nl2br($text));
 }//end function bx_textarea($text)

function verify($variable,$type)
  {
  switch ($type) {
  case "int":
     if (eregi("([^0-9\.])",$variable)==true)
      {
        return 1;
      }
      else
       {
       return 0;
       }
      break;
  case "phone":
     if (eregi("([^0-9 -\+])",$variable)==true)
      {
        return 1;
      }
      else
       {
       return 0;
       }
      break;
   case "string":
              if (eregi("([^a-zA-Z ])",$variable)==true)
               {
                   return 1;
               }
              else
               {
                   return 0;
               }
              break;

    }//end switch
} //end function verify

/**
* String can contain letters language specific special chars ".", "," and "-".
*@param string $bx_srt string to check
*@return boolean result of check
*/
 function bx_chkvalalpha($bx_srt) {
    return (ereg("^[a-zA-ZÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÑÒÓÔÕÖØÙÚÛÜÝÞßàáâãäåæçèéêëìíîïðñòóôõöøùúûüýþÿ\. ,-]+$", $bx_srt));
  }

 function bx_image_width($src, $width, $height, $border, $alt) {
    global $image;

    $image = '<img src="' . $src . '" width="' . $width . '" height="' . $height . '" border="' . $border . '" alt="' . $alt . '">';

    return $image;
 }
 function bx_image($src, $border, $alt) {
    global $image;

    $image = '<img src="' . $src . '" border="' . $border . '" alt="' . $alt . '" align="absmiddle">';

    return $image;
 }

 function bx_image_submit($src, $width, $height, $border, $alt) {
    global $image_submit;

    $image_submit = '<input type="image" src="' . $src . '" width="' . $width . '" height="' . $height . '" border="' . $border . '" alt="' . $alt . '" style="border: 0px solid #FFFFFF">';

    return $image_submit;
  }

  function bx_image_submit_nowidth($src, $border, $alt) {
    global $image_submit;

    $image_submit = '<input type="image" src="' . $src . '" border="' . $border . '" alt="' . $alt . '" style="border: 0px solid #FFFFFF">';

    return $image_submit;
  }


  function bx_table_header($title,$color="red") {
    $fontface="Verdana, Arial";
    $fontcolor="white";
    $fontsize="2";
    return '<table border="0" cellpanding="0" cellspacing="0" width="100%" align="center"><tr><td align="center" bgcolor="'.$color.'"><font face="'.$fontface.'" size="'.$fontsize.'" color="'.$fontcolor.'"><b>'.$title.'</b></font></td></tr></table>';

  }
   //error_reporting(0);
  // SQL_CHECK function
  // parameter: $NO_ROWS
  // if ($NO_ROWS==0) - NO_ROWS case if not error
 // if ($NO_ROWS!=0) - NO_ROWS case is ERROR
 function SQL_CHECK ( $NO_ROWS=1, $errmsg="An error occured" )
  {
   global $bx_temp_query, $bx_query_time, $HTTP_SERVER_VARS, $HTTP_POST_VARS;
   $res = mysql_errno();
   $error = mysql_error();
   $error_flag = 0;
   $subtype = 0;
        if ($res==0) {
             // query successfully executed - so check NO_ROWS case
             if (($NO_ROWS) && (mysql_affected_rows()==0)) {
                      // error: no rows returned
                        $error_flag = 1;
                        $subtype = 1;
              }
        }
        else {
             // an error occured
             $error_flag = 1;
             $post="";
             while (list($header, $value) = each($HTTP_POST_VARS)) {
                     $post.="\nPOST: ".$header." - ".((is_array($value))?implode(",",$value):$value)."";
             }
        }
        if ($error_flag && DEBUG_MODE=="no")  {
            echo "<br><table bgcolor=\"#FF0000\" border=\"0\" width=\"70%\" align=\"center\" cellpadding=\"0\" cellspacing=\"1\">";
            echo "<tr><td bgcolor=\"#EFEFEF\">";
            echo "<font color=\"red\"><b>Sorry....</b><br>An error occured during this request. An detailed error report was sent to <b>admin</b>,<br> so the error will be solved soon.<br><b>Please come back later. Thank you.</b></font></td></tr></table>";
          // sending the email
           @mail
             (
              SITE_MAIL,  SITE_TITLE.": An SQL error occured",
             // Message
             SITE_TITLE.": An SQL error occured:\n".
             "$errmsg\n\n".
             "MySQL error code: $res\n".
             "MySQL error text: $error\n".
             "Query: $bx_temp_query\n".
             "Query took: $bx_query_time ms\n".
             "Date: ".date('Y-m-d H:i:s')."\n".
             "IP: ".$HTTP_SERVER_VARS['REMOTE_ADDR']."\n".
	         "Page: http://".(getenv('HTTP_HOST')?getenv('HTTP_HOST'):$HTTP_SERVER_VARS['HTTP_HOST']).(getenv(REQUEST_URI)?getenv(REQUEST_URI):$HTTP_SERVER_VARS['REQUEST_URI'])."\n".
             "".$post."\n",
             "From: ".SITE_MAIL."\n".
             "Content-type: text/plain\n"
             );        
	     }
         elseif($error_flag && DEBUG_MODE=="yes"){
                       ?>
                        <script language="Javascript">
                        <!--
                                nWindow = open('','_blank','toolbar=no,location=no,directories=no,status=no,scrollbars=yes,resizable=yes,copyhistory=no,width=500,height=200');
                                nWindow.document.writeln('<html><body bgcolor=#C0C0FF>');
                                nWindow.document.write("SQL Query:<br><?php print nl2br(eregi_replace("\n|\015|\012","",eregi_replace("\"","''",$bx_temp_query)));?>");
                                nWindow.document.writeln("<br>Query took: <?php print $bx_query_time;?> ms");
                                nWindow.document.write("<br>Url: <?php print 'http://'.(getenv('HTTP_HOST')?getenv('HTTP_HOST'):$HTTP_SERVER_VARS['HTTP_HOST']).(getenv(REQUEST_URI)?getenv(REQUEST_URI):$HTTP_SERVER_VARS['REQUEST_URI']);?>");
                                nWindow.document.write("<br><font color=red>MySQL error code: <?php print $res;?></font>");
                                nWindow.document.writeln("<br><font color=red>MySQL error text: <?php print $error;?></font>");
                                nWindow.document.write("<br>Error in: <?php print $errmsg;?>");
                                nWindow.document.write("<br><?php print eregi_replace("\n|\015|\012","<br>",eregi_replace("\"","\\''",$post));?>");
                                nWindow.document.writeln('</body></html>');
                           //-->
                          </script>
                          <?php
                          echo "<br><table bgcolor=\"#FF0000\" border=\"0\" width=\"70%\" align=\"center\" cellpadding=\"2\" cellspacing=\"1\">";
                          echo "<tr><td bgcolor=\"#EFEFEF\" style=\"padding: 10px;\">";
                          echo "<font color=\"red\"><b>Sorry....</b><br>An error occured during this request. An detailed error report was sent to <b>admin</b>,<br> so the error will be solved soon.<br><b>Please come back later. Thank you.</b></font></td></tr></table><br>";
         }
         elseif($NO_ROWS==4){
                       ?>
                        <script language="Javascript">
                        <!--
                                nWindow = open('','_blank','toolbar=no,location=no,directories=no,status=no,scrollbars=yes,resizable=yes,copyhistory=no,width=500,height=200');
                                nWindow.document.writeln('<html><body bgcolor=#C0C0FF>');
                                nWindow.document.write("SQL Query:<br><?php print nl2br(eregi_replace("\n|\015|\012","",eregi_replace("\"","''",$bx_temp_query)));?>");
                                nWindow.document.writeln("<br>Query took: <?php print $bx_query_time;?> ms");
                                nWindow.document.write("<br>Url: <?php print 'http://'.(getenv('HTTP_HOST')?getenv('HTTP_HOST'):$HTTP_SERVER_VARS['HTTP_HOST']).(getenv(REQUEST_URI)?getenv(REQUEST_URI):$HTTP_SERVER_VARS['REQUEST_URI']);?>");
                                nWindow.document.write("<br>File: <?php print $errmsg;?>");
                                nWindow.document.write("<br><?php print eregi_replace("\n|\015|\012","<br>",eregi_replace("\"","\\''",$post));?>");
                                nWindow.document.writeln('</body></html>');
                           //-->
                          </script>
                          <?php
         }
         elseif($NO_ROWS==5){
                       echo "QUERY: ".$bx_temp_query;  
         }
}
function random_once($array,$random_id)
{
     $exist=0;
     if (sizeof($array)!=0)
     {
      for ($i=0;$i<sizeof($array);$i++)
      {
       if ($array[$i]==$random_id)
        {
        $exist=1;
        return 1;
        }
      }
    }
     if ($exist==0)
       {
          if (sizeof($array)!=0)
           {
            $array[sizeof($array)]=$random_id;
           }
           else
            {
            $array[0]=$random_id;
            }
    return 0;
    }
   else {
       return 1;
   }
}

function getFolders($dirname=".") {
     $d = dir($dirname);
		while($entry = $d->read()) {
			if ($entry != "." && $entry != "..") {
				if (is_dir($dirname."/".$entry)) {
                    $names[] = $entry;
				} 
			}
		}
		$d->close();

      return $names;
}

function getFiles($dirname=".") {
      $d = dir($dirname);
		while($entry = $d->read()) {
			if ($entry != "." && $entry != "..") {
				if (is_dir($dirname."/".$entry)) {
				} else {
                    $names[] = $entry;
				}
			}
		}
		$d->close();

      return $names;
}

function short_string($text, $length, $symbol = "...")  {
        $length_text = strlen($text);
        $length_symbol = strlen($symbol);
        $sub_str = substr($text, 0, $length - $length_symbol);
        if ($length_text <= $length || $length_text <= $length_symbol || $length <= $length_symbol) {
                return($text);
        }
        else {
                $pos_space = strrpos($sub_str," ");
                $pos['comma'] = strrpos($sub_str,",");
                $pos['point'] = strrpos($sub_str,".");
                $pos['pontveszo'] = strrpos($sub_str,";");
                $pos['rbracket'] = strrpos($sub_str,")");
                $pos['kettospont'] = strrpos($sub_str,":");
                $pos['line'] = strrpos($sub_str,"-");
                $pos['backslash'] = strrpos($sub_str,"/");
                $pos['slash'] = strrpos($sub_str,"\\");
                arsort($pos);
                $max_pos = current($pos);
                if ($max_pos && $max_pos > $pos_space) {
                        return substr($text, 0, $max_pos).$symbol;
                }
                else  {
                        if ($pos_space) {
                                if ((!$max_pos) || (($pos_space - $max_pos) > ($length/4))) {
                                        return substr($text, 0, $pos_space).$symbol;
                                }
                                else {
                                        return substr($text, 0, $max_pos).$symbol;
                                }
                        }
                        else {
                                return $sub_str.$symbol;
                        }
                }
        }
 }
 function bx_make_url($url, $var, $replace) {
    if (eregi($var, $url)) {
           $url = eregi_replace("(\?|&){1,}$","",$url);
           $url.= "&";    
           $url = preg_replace("/".$var."=([^&]*)[&(.*)|$]/",$var."=".$replace."&\\2",$url);
           $url = eregi_replace("(\?|&){1,}$","",$url);
    }
    else {
           $url = eregi_replace("(\?|&){1,}$","",$url);
           if (eregi("\?", $url)) {
                $url .= "&".$var."=".$replace;
           }
           else {
                $url .= "?".$var."=".$replace;
           }
    }
    if($var!="ref") {
         $url=bx_make_url($url,"ref",substr(md5(time()),0,25));   
    }
    return $url;
 }
 function bx_unhtmlspecialchars($str) {
 	return str_replace(array("&gt;", "&lt;", "&quot;", "&amp;"), array(">", "<", "\"", "&"), $str);
 }
?>