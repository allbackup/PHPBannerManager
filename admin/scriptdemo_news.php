<?php
include ('admin_design.php');
include ('../application_config_file.php');
include ('admin_auth.php');
include("header.php");
?>

         <table width="100%" cellspacing="0" cellpadding="2" border="0" class="tedit">
         <tr>
             <td align="center"><b>Scriptdemo.com News</b></td>
         </tr>
         <tr>
           <td>
             <TABLE border="0" cellpadding="4" cellspacing="0" width="100%" class="edit">
                    <tr>
                        <td align="center"><br>
							<?php
                            if (!@include("http://www.scriptdem0.com/scriptdemo_news_all.php"))
                            {
                                echo "<table align=center border=0 cellspacing=0 cellpadding=0 bgcolor=#efefef><tr><td align=center><font size='".TEXT_FONT_SIZE."'><br>Unable to open remote file on Scriptdemo.com<br><br></font></td></tr></table>";}
                            ?>
                        </td>
                    </tr>
                    <tr>
                        <td align="center"><a href="<?php echo HTTP_SERVER_ADMIN.FILENAME_INDEX;?>">Home</a></td>
                    </tr>
             </table>
          </td>
         </tr>
        </table>
<?php
include("footer.php");
?>