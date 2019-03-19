<?php include(DIR_LANGUAGES.$language."/".FILENAME_LEFT_NAVIGATION);?>

<table width="100%" border="0" bgcolor="<?php echo LEFT_NAV_BG_COLOR;?>" cellspacing="0" cellpadding="0" height="100%">
  <tr>
   <td height="18" align="center" bgcolor="<?php echo TABLE_EMPLOYER;?>"><font face="<?php echo HEADING_FONT_FACE;?>" size="<?php echo HEADING_FONT_SIZE;?>" color="<?php echo HEADING_FONT_COLOR;?>"><b><i><?php echo TEXT_RIGHT_EMPLOYER;?></i></b></font></td>
  </tr>
  <tr><td height="2"><table cellpadding="0" cellspacing="0" border="0" width="100%">
                <tr>
                    <td bgcolor="#000000" height="2"><?php echo bx_image_width(HTTP_IMAGES.$language."/pix-t.gif",1,1,0,"");?></td>
                </tr>
       </table>
  </td></tr>
	<tr><td height="18">&nbsp;</td></tr>
  <tr><td height="18" align="left" valign="top">&nbsp;&nbsp;&nbsp;<a href="<?php echo bx_make_url(HTTP_SERVER.FILENAME_LOGOUT."?employer=true", "auth_sess", $bx_session);?>" class="nav">&#171; <?php echo TEXT_LOGOUT;?></a></td></tr>
	<tr><td height="18"><hr></td></tr>
   <tr><td valign="top" height="18">
      &nbsp;&nbsp;&nbsp;<a href="<?php echo bx_make_url(HTTP_SERVER.FILENAME_USERS, "auth_sess", $bx_session);?>" class="nav">&#171; <?php echo TEXT_MYCOMPANY;?></a><br>
   </td></tr>

   <tr><td valign="top" height="18">
      &nbsp;&nbsp;&nbsp;<a href="<?php echo bx_make_url(HTTP_SERVER.FILENAME_MEMBERSHIP, "auth_sess", $bx_session);?>" class="nav">&#171; <?php echo TEXT_PLANNING;?></a><br>
   </td></tr>
   <tr><td valign="top" height="18">
      &nbsp;&nbsp;&nbsp;<a href="<?php echo bx_make_url(HTTP_SERVER.FILENAME_MYINVOICES, "auth_sess", $bx_session);?>" class="nav">&#171; <?php echo TEXT_INVOICING;?></a><br>
   </td></tr>
   <tr><td valign="top" height="18">
      &nbsp;&nbsp;&nbsp;<a href="<?php echo bx_make_url(HTTP_SERVER.FILENAME_SUPPORT, "auth_sess", $bx_session);?>" class="nav">&#171; <?php echo TEXT_ESUPPORT;?></a><br>
   </td></tr>

   <tr><td height="18" valign="top">
       &nbsp;&nbsp;&nbsp;<a href="<?php echo bx_make_url(HTTP_SERVER.FILENAME_DELACCOUNT, "auth_sess", $bx_session);?>" class="nav">&#171; <?php echo TEXT_DELETE_ACCOUNT;?></a>
   </td></tr>
	<tr><td height="18"><hr></td></tr>
   <tr><td valign="top" height="18">
      &nbsp;&nbsp;&nbsp;<a href="<?php echo bx_make_url(HTTP_SERVER.'banners.php', "auth_sess", $bx_session);?>" class="nav">&#171; <?php echo TEXT_BANNERS;?></a><br>
   </td></tr>
   <tr><td valign="top" height="18">
      &nbsp;&nbsp;&nbsp;<a href="<?php echo bx_make_url(HTTP_SERVER.'client_stats.php', "auth_sess", $bx_session);?>" class="nav">&#171; <?php echo TEXT_STATS;?></a><br>
   </td></tr>
	<tr><td height="18"><hr></td></tr>

   <tr bgcolor="<?php echo LEFT_NAV_BG_COLOR;?>">
         <td valign="top" height="18"><?php echo bx_image_width(HTTP_IMAGES.$language."/pix-t.gif",1,1,0,"alt1");?></td>
   </tr>
   <TR><td>&nbsp;</td></tr>
</table>