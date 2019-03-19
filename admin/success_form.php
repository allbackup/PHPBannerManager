<table width="100%" cellspacing="0" cellpadding="2" border="0" class="tedit">
      <tr>
          <td align="center"><b>Admin Message</b></font></td>
      </tr>
      <tr>
         <td>
         <TABLE border="0" cellpadding="4" cellspacing="0" width="100%" class="edit">
            <tr>
                <td align="center"><b><?php
				if ($success_message) {
				    echo $success_message;
				}
				else {
					echo "Successfull update.";    
				}
				?></b></td>
            </tr>
            <tr>
                <td align="left" width="100%" nowrap><a href="javascript: history.go(-1);" onmouseover="window.status='Back'; return true;" onmouseout="window.status=''; return true;" class="settings">&#171;&nbsp;Go Back</a>&nbsp;&nbsp;<a href="<?php echo HTTP_SERVER_ADMIN.FILENAME_INDEX;?>" class="settings">&#171;Admin Home&#187;</a></td>
            </tr>
         </table>
         </td>
      </tr>
</table>