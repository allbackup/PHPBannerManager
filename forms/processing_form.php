<?php
include(DIR_LANGUAGES.$language."/".FILENAME_PROCESSING_FORM);
if ($HTTP_POST_VARS['payment_mode']==1)
{
?>
<table bgcolor="<?php echo TABLE_BGCOLOR;?>" width="100%" border="0" cellspacing="0" cellpadding="2">
	<TR bgcolor="#FFFFFF">
      <TD width="100%" align="center" class="headertdjob"><?php echo TEXT_INFORMATION_SAVED;?></TD>
   </TR>
   <TR><TD><table cellpadding="0" cellspacing="0" border="0" width="100%">
                <tr>
                    <td bgcolor="<?php echo TABLE_HEADING_BGCOLOR;?>" height="1"><?php echo bx_image_width(HTTP_IMAGES.$language."/pix-t.gif",1,1,0,"");?></td>
                </tr>
       </table></TD>
   </TR>
   <TR>
       <TD>&nbsp;</TD>
   </TR>
   <TR>
       <TD align="center" valign="middle" width="100%">
        <form action="<?php echo bx_make_url(HTTP_SERVER.FILENAME_MEMBERSHIP, "auth_sess", $bx_session);?>" method="post">
         <font face="<?php echo TEXT_FONT_FACE;?>" size="<?php echo TEXT_FONT_SIZE;?>" color="<?php echo TEXT_FONT_COLOR;?>"></FONT><input type="submit" value="<?php echo BUTTON_BACK_MEMBERSHIP;?>"></form>
      </TD>
    </TR>
	<TR><TD>&nbsp;</TD></TR>
</TABLE>
<?php
}//end if ($HTTP_POST_VARS['payment_mode']==1)
if ($HTTP_POST_VARS['payment_mode']==2)
{
?>
  <table bgcolor="<?php echo TABLE_BGCOLOR;?>" width="100%" border="0" cellspacing="0" cellpadding="2">
	<TR bgcolor="#FFFFFF">
      <TD width="100%" align="center" class="headertdjob"><?php echo TEXT_INFORMATION_SAVED;?></TD>
   </TR>
   <TR><TD><table cellpadding="0" cellspacing="0" border="0" width="100%">
                <tr>
                    <td bgcolor="<?php echo TABLE_HEADING_BGCOLOR;?>" height="1"><?php echo bx_image_width(HTTP_IMAGES.$language."/pix-t.gif",1,1,0,"");?></td>
                </tr>
       </table></TD>
   </TR>
   <TR>
       <TD>&nbsp;</TD>
   </TR>
   <TR>
      <TD align="left" valign="middle" width="100%">
        <font face="<?php echo TEXT_FONT_FACE;?>" size="<?php echo TEXT_FONT_SIZE;?>" color="<?php echo TEXT_FONT_COLOR;?>"><B><I><?php echo TEXT_EMAIL_WAS_SENT;?></I></B></FONT>
      </TD>
    </TR>
    <TR>
       <TD>&nbsp;</TD>
    </TR>
    <TR>
       <TD align="center" valign="middle" width="100%">
        <form action="<?php echo bx_make_url(HTTP_SERVER.FILENAME_MEMBERSHIP, "auth_sess", $bx_session);?>" method="post">
         <font face="<?php echo TEXT_FONT_FACE;?>" size="<?php echo TEXT_FONT_SIZE;?>" color="<?php echo TEXT_FONT_COLOR;?>"></FONT><input type="submit" value="<?php echo BUTTON_BACK_MEMBERSHIP;?>"></form>
      </TD>
    </TR>
</TABLE>
<?php
}//end if ($HTTP_POST_VARS['payment_mode']==2)
?>