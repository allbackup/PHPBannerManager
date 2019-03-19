<?php
include(DIR_LANGUAGES.$language.'/select_banner_type_form.php');
?>
<table align="center" border="0" cellspacing="5" cellpadding="1" width="100%" bgcolor="#F2F8FF">
<tr>
	<td bgcolor="#9FCBFF">
       <form name="form1" method="post" action="<?php echo bx_make_url('add_banner.php?format=image&user_id='.$user_id.'&zid='.$HTTP_GET_VARS['zid'], "auth_sess", $bx_session);?>" style="margin-width:0px;margin-height:0px">
		<table align="center" cellpadding="8" cellspacing="0" width="100%" bgcolor="#F2F8FF" border="0">
	    <tr>
			<td width="33%">
				&nbsp;
			</td>
			<td class="text"> 
              <input type="radio" name="type" value="0" class="noBorder" onClick="document.form1.action='add_banner.php?format=html&user_id=<?=$user_id?>&zid=<?php echo $HTTP_GET_VARS['zid'];?>';" id="html_label">
              &nbsp;<font face="verdana" size="2" color="#000000"><b><label for="html_label"><?php echo TEXT_INSERT_HTML_BANNER?></label> </b></font>
			</td>
		</tr>
		<tr>
			<td width="33%">
				&nbsp;
			</td>
			<td class="text">
              <input type="radio" name="type" value="1" class="noBorder" checked onClick="document.form1.action='add_banner.php?format=image&user_id=<?=$user_id?>&zid=<?php echo $HTTP_GET_VARS['zid'];?>';" id="image_label">
              <font face="verdana" size="2" color="#000000">&nbsp;<b><label for="image_label"><?php echo TEXT_INSERT_IMAGE_BANNER?></label> </b></font>
            </td>
		</tr>
		<tr>
			<td width="33%">
				&nbsp;
			</td>
			<td class="text">
			  <input type="radio" name="type" value="2" class="noBorder" onClick="document.form1.action='add_banner.php?format=swf&user_id=<?=$user_id?>&zid=<?php echo $HTTP_GET_VARS['zid'];?>';" id="swf_label">
              <font face="verdana" size="2" color="#000000">&nbsp;<b><label for="swf_label"><?php echo TEXT_INSERT_SWF_BANNER?></label></b></font>
          </td>
        </tr>
<!-- remote banner type -->
		<tr>
			<td width="33%">
				&nbsp;
			</td>
			<td class="text">
			  <input type="radio" name="type" value="3" class="noBorder" onClick="document.form1.action='add_banner.php?format=remote&user_id=<?=$user_id?>&zid=<?php echo $HTTP_GET_VARS['zid'];?>';" id="remote_label">
              <font face="verdana" size="2" color="#000000">&nbsp;<b><label for="remote_label"><?php echo TEXT_INSERT_REMOTE_BANNER?></label></b></font>
          </td>
        </tr>
<!-- remote banner type end -->
		<tr>
			<td colspan="2" bgcolor="#9FCBFF" height="1"></td>
		</tr>

		<tr>
          <td colspan="2"> 
            <div align="center"> 
			  <input type="submit" value="<?php echo TEXT_INSERT?>">
            </div>
          </td>
        </tr>
        </form>
      </table>
   </td>
</tr>
</table>
</div>