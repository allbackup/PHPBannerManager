<?php
include(DIR_LANGUAGES.$language."/".FILENAME_MESSAGE_FORM);
?>
<form action="<?php echo $back_url;?>" method="post">
<table bgcolor="<?php echo TABLE_BGCOLOR;?>" align="center" width="100%" border="0" cellspacing="0" cellpadding="2">
    <TR bgcolor="#FFFFFF">
      <TD width="100%" align="center" class="headertdjob"><?php echo TEXT_MESSAGE_SUCCESS;?></TD>
    </TR>
    <TR><TD><table cellpadding="0" cellspacing="0" border="0" width="100%">
                <tr>
                    <td bgcolor="<?php echo TABLE_HEADING_BGCOLOR;?>" height="1"><?php echo bx_image_width(HTTP_IMAGES.$language."/pix-t.gif",1,1,0,"");?></td>
                </tr>
       </table></TD>
    </TR>
    <TR>
      <TD valign="top" align="center" valign="middle" width="100%">
       <font face="<?php echo TEXT_FONT_FACE;?>" size="<?php echo TEXT_FONT_SIZE;?>" color="<?php echo TEXT_FONT_COLOR;?>"><b><?php echo $success_message;?></b></font>
      </TD>
    </TR>
    
    <TR>
      <TD valign="top" align="center" valign="middle" width="100%">
       <br><font face="<?php echo TEXT_FONT_FACE;?>" size="<?php echo TEXT_FONT_SIZE;?>" color="<?php echo TEXT_FONT_COLOR;?>"><b><?php echo TEXT_GO_BACK_SUCCESS;?></b></font>
      </TD>
    </TR>
    <?php if(!$close_button) {?>
    <TR>
      <TD width="100%" align="center"><br><INPUT type="submit" name="back_to__page" value="<?php echo TEXT_BACK_TO_UPDATED_PAGE;?>"></TD>
    </TR>
    <?php }else{?>
    <TR>
      <TD width="100%" align="center"><br><INPUT type="submit" name="back_to__page" value="<?php echo TEXT_CLOSE_WINDOW;?>" onClick="parent.window.close();"></TD>
    </TR>
    <?php }?>
</table>
</form>