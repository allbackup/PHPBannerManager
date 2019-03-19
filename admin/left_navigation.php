<table width="100%" cellspacing="0" cellpadding="2">
 <tr>
   <td bgcolor="#000000" nowrap>
<table width="100%" border="0" bgcolor="#009191" cellspacing="0" cellpadding="2">
  <tr>
    <td valign="top" nowrap>
      <?php echo bx_image(HTTP_IMAGES.$language."/bullet.gif",0,'');?> <a href="<?php echo HTTP_SERVER.FILENAME_INDEX;?>" class="anav">Home</a></td>
  </tr>
  <tr>
    <td valign="top" nowrap>
      <?php echo bx_image(HTTP_IMAGES.$language."/bullet.gif",0,'');?> <a href="<?php echo HTTP_SERVER_ADMIN.FILENAME_INDEX;?>" class="anav">Admin Home</a></td>
  </tr>
  
  <?php if($HTTP_SESSION_VARS['adm_user']){?>
  <tr>
    <td valign="top" nowrap>
      <?php echo bx_image(HTTP_IMAGES.$language."/bullet.gif",0,'');?> <a href="<?php echo HTTP_SERVER_ADMIN."logout.php";?>" class="anav">Admin Logout</a></td>
  </tr>
  <?php }?>
  <tr>
     <td nowrap>
      <hr>
      <table width="100%" cellspacing="0" cellpadding="2">
       <tr>
        <td bgcolor="#FFFFFF" nowrap>
        <table bgcolor="#C0C0FF" width="100%"><tr><td><font color="white"><b>Buyers Section</b></font></td></tr></table>
        </td></tr></table>
     </td>
  </tr>
  <tr>
     <td>
      <?php echo bx_image(HTTP_IMAGES.$language."/bullet.gif",0,'');?>  <a href="<?php echo HTTP_SERVER_ADMIN.FILENAME_ADMIN_SEARCH;?>" class="anav">Search invoices</a><br>
    </td>
  </tr>
  <tr>
     <td>
      <?php echo bx_image(HTTP_IMAGES.$language."/bullet.gif",0,'');?>  <a href="<?php echo HTTP_SERVER_ADMIN.FILENAME_ADMIN;?>?action=buyers" class="anav">Show buyers</a><br>
    </td>
  </tr>
  <tr>
     <td>
      <?php echo bx_image(HTTP_IMAGES.$language."/bullet.gif",0,'');?>  <a href="<?php echo HTTP_SERVER_ADMIN.FILENAME_ADMIN_BULK_EMAIL;?>?mail=companies" class="anav">Bulk Email</a>
    </td>
  </tr>
  <tr>
     <td nowrap>
      <hr>
      <table width="100%" cellspacing="0" cellpadding="2">
       <tr>
        <td bgcolor="#FFFFFF" nowrap>
        <table bgcolor="#C0C0FF" width="100%"><tr><td nowrap><font face="<?=TEXT_FONT_FACE?>" size="<?=TEXT_FONT_SIZE?>" color="white"><b>Banner Management</b></font></td></tr></table>
        </td></tr></table>
     </td>
  </tr>
    <tr>
     <td>
      <?=bx_image(HTTP_IMAGES.$language."/bullet.gif",0,'');?>  <a href="<?=HTTP_SERVER_ADMIN;?>list_users.php" class="anav">Banners</a>
	  <?php 
	  //echo "<br>".
	  $banSQL = "select * from $bx_db_table_banner_banners where permission!='deny'";
	  $ban_query = bx_db_query($banSQL);
	  SQL_CHECK(0,"SQL Error at ".__FILE__.":".(__LINE__-1));
	  echo '<font size="2" color="#f0f0f0">( '.bx_db_num_rows($ban_query).' )</font>';
	  ?>
	  <br>
    </td>
  </tr>
    <tr>
     <td>
      <?=bx_image(HTTP_IMAGES.$language."/bullet.gif",0,'');?>  <a href="<?=HTTP_SERVER_ADMIN;?>list_users.php?action=val" class="anav">Validate Banners</a>
	  <?php 
	  //echo "<br>".
	  $valSQL = "select * from $bx_db_table_banner_banners where permission='deny'";
	  $val_query = bx_db_query($valSQL);
	  SQL_CHECK(0,"SQL Error at ".__FILE__.":".(__LINE__-1));
	  echo '<font size="2" color="#f0f0f0">( '.bx_db_num_rows($val_query).' )</font>';
	  ?>
	  <br>
    </td>
  </tr>
    <tr>
     <td>
      <?=bx_image(HTTP_IMAGES.$language."/bullet.gif",0,'');?>  <a href="<?=HTTP_SERVER_ADMIN;?>list_users.php?action=rem" class="anav">Removed Banners</a>
	  <?php 
	  //echo "<br>".
	  $delSQL = "select * from $bx_db_table_banner_deleted_banners";
	  $del_query = bx_db_query($delSQL);
	  SQL_CHECK(0,"SQL Error at ".__FILE__.":".(__LINE__-1));
	  echo '<font size="2" color="#f0f0f0">( '.bx_db_num_rows($del_query).' )</font>';
	  ?>
	  <br>
    </td>
  </tr>
    <tr>
     <td>
      <?=bx_image(HTTP_IMAGES.$language."/bullet.gif",0,'');?>  <a href="<?=HTTP_SERVER_ADMIN.FILENAME_ADMIN_BANNERS;?>" class="anav">Planning</a>
	   <?php 
	  //echo "<br>".
	  $planSQL = "select * from $bx_db_table_planning group by p_zone_id";
	  $plan_query = bx_db_query($planSQL);
	  SQL_CHECK(0,"SQL Error at ".__FILE__.":".(__LINE__-1));
	  echo '<font size="2" color="#f0f0f0">( '.(bx_db_num_rows($plan_query)).' )</font>';
	  ?>
	  <br>
    </td>
  </tr>
    <tr>
     <td>
      <?=bx_image(HTTP_IMAGES.$language."/bullet.gif",0,'');?>  <a href="<?=HTTP_SERVER_ADMIN.FILENAME_ADMIN_BANNER_MONTHS;?>" class="anav">Periodes</a>
	  <?php 
	  //echo "<br>".
	  $monthSQL = "select * from $bx_db_table_planning_months";
	  $month_query = bx_db_query($monthSQL);
	  SQL_CHECK(0,"SQL Error at ".__FILE__.":".(__LINE__-1));
	  echo '<font size="2" color="#f0f0f0">( '.bx_db_num_rows($month_query).' )</font>';
	  ?>
	  <br>
    </td>
  </tr>
  </tr>
    <tr>
     <td>
      <?=bx_image(HTTP_IMAGES.$language."/bullet.gif",0,'');?>  <a href="<?=HTTP_SERVER_ADMIN;?>users.php" class="anav">Users</a>
	  <?php 
	  //echo "<br>".
	  $userSQL = "select * from $bx_db_table_banner_users";
	  $user_query = bx_db_query($userSQL);
	  SQL_CHECK(0,"SQL Error at ".__FILE__.":".(__LINE__-1));
	  echo '<font size="2" color="#f0f0f0">( '.(bx_db_num_rows($user_query)).' )</font>';
	  ?>
	  <br>
    </td>
  </tr>
  <tr>
     <td>
      <?=bx_image(HTTP_IMAGES.$language."/bullet.gif",0,'');?>  <a href="<?=HTTP_SERVER_ADMIN;?>zones.php" class="anav">Zones</a>
	  <?php 
	  //echo "<br>".
	  $zoneSQL = "select * from $bx_db_table_banner_types";
	  $zone_query = bx_db_query($zoneSQL);
	  SQL_CHECK(0,"SQL Error at ".__FILE__.":".(__LINE__-1));
	  echo '<font size="2" color="#f0f0f0">( '.bx_db_num_rows($zone_query).' )</font>';
	  ?>
	  <br>
    </td>
  </tr>
    <tr>
     <td>
      <?=bx_image(HTTP_IMAGES.$language."/bullet.gif",0,'');?>  <a href="<?=HTTP_SERVER_ADMIN;?>list_users.php?action=stats" class="anav">Statistics</a><br>
    </td>
  </tr>
  <tr>
     <td>
      <?=bx_image(HTTP_IMAGES.$language."/bullet.gif",0,'');?>  <a href="<?=HTTP_SERVER_ADMIN;?>code.php" class="anav">Generate Banner Code</a><br>
    </td>
  </tr>
  <tr>
     <td nowrap>
      <hr>
      <table width="100%" cellspacing="0" cellpadding="2">
       <tr>
        <td bgcolor="#FFFFFF" nowrap>
        <table bgcolor="#C0C0FF" width="100%"><tr><td nowrap><font color="white"><b>Script Management</b></font></td></tr></table>
        </td></tr></table>
     </td>
  </tr>
  <tr>
     <td>
      <?php echo bx_image(HTTP_IMAGES.$language."/bullet.gif",0,'');?>  <a href="<?php echo HTTP_SERVER_ADMIN.FILENAME_ADMIN_SETTINGS;?>" class="anav">Script Settings</a><br>
    </td>
  </tr>
  <tr>
     <td>
      <?php echo bx_image(HTTP_IMAGES.$language."/bullet.gif",0,'');?>  <a href="<?php echo HTTP_SERVER_ADMIN.FILENAME_ADMIN_LAYOUT;?>" class="anav">Layout manager</a><br>
    </td>
  </tr>
  <tr>
     <td>
      <?php echo bx_image(HTTP_IMAGES.$language."/bullet.gif",0,'');?>  <a href="<?php echo HTTP_SERVER_ADMIN.FILENAME_ADMIN_PAYMENT;?>" class="anav">Payment Settings</a><br>
    </td>
  </tr>
    <tr>
     <td>
      <?php echo bx_image(HTTP_IMAGES.$language."/bullet.gif",0,'');?>  <a href="<?php echo HTTP_SERVER_ADMIN.'admin_cron.php';?>" class="anav">Cron Settings</a><br>
    </td>
  </tr>
  <tr>
     <td>
      <?php echo bx_image(HTTP_IMAGES.$language."/bullet.gif",0,'');?>  <a href="<?php echo HTTP_SERVER_ADMIN.FILENAME_ADMIN_PASSWORD;?>" class="anav">Change admin password</a><br>
    </td>
  </tr>
  <tr>
     <td nowrap>
      <hr>
      <table width="100%" cellspacing="0" cellpadding="2">
       <tr>
        <td bgcolor="#FFFFFF" nowrap>
        <table bgcolor="#C0C0FF" width="100%"><tr><td nowrap><font color="white"><b>Database Management</b></font></td></tr></table>
        </td></tr></table>
     </td>
  </tr>
  <tr>
     <td>
      <?php echo bx_image(HTTP_IMAGES.$language."/bullet.gif",0,'');?>  <a href="<?php echo HTTP_SERVER_ADMIN."backup_db.php";?>" class="anav">Backup database</a><br>
     </td>
  </tr>
  <tr>
     <td>
     <?php echo bx_image(HTTP_IMAGES.$language."/bullet.gif",0,'');?>  <a href="<?php echo HTTP_SERVER_ADMIN."restore_db.php";?>" class="anav">Restore database</a>
    </td>
  </tr>
  <tr>
     <td>
     <?php echo bx_image(HTTP_IMAGES.$language."/bullet.gif",0,'');?>  <a href="<?php echo HTTP_SERVER_ADMIN."mysql_update.php";?>" class="anav">Update database</a>
    </td>
  </tr>
  <tr>
     <td nowrap>
      <hr>
      <table width="100%" cellspacing="0" cellpadding="2">
       <tr>
        <td bgcolor="#FFFFFF" nowrap>
         <table bgcolor="#C0C0FF" width="100%"><tr><td nowrap><font color="white"><b>Multilanguage Support</b></font></td></tr></table>
         </td></tr></table>
     </td>
  </tr>
  <tr>
     <td>
      <?php echo bx_image(HTTP_IMAGES.$language."/bullet.gif",0,'');?>  <a href="<?php echo HTTP_SERVER_ADMIN."add_lng.php";?>" class="anav">Add Language</a><br>
     </td>
  </tr>
  <tr>
     <td>
      <?php echo bx_image(HTTP_IMAGES.$language."/bullet.gif",0,'');?>  <a href="<?php echo HTTP_SERVER_ADMIN."edit_lng.php";?>" class="anav">Edit Language</a><br>
     </td>
  </tr>
  <tr>
     <td>
      <?php echo bx_image(HTTP_IMAGES.$language."/bullet.gif",0,'');?>  <a href="<?php echo HTTP_SERVER_ADMIN."edit_mail.php";?>" class="anav">Edit Email Messages</a><br>
     </td>
  </tr>
  <tr>
     <td>
      <?php echo bx_image(HTTP_IMAGES.$language."/bullet.gif",0,'');?>  <a href="<?php echo HTTP_SERVER_ADMIN.FILENAME_ADMIN_EDIT_FILE;?>" class="anav">Edit HTML Files</a><br>
     </td>
  </tr>
  <tr>
     <td>
     <?php echo bx_image(HTTP_IMAGES.$language."/bullet.gif",0,'');?>  <a href="<?php echo HTTP_SERVER_ADMIN."del_lng.php";?>" class="anav">Delete Language</a>
    </td>
  </tr>
  <tr>
     <td nowrap>
      <hr>
      <table width="100%" cellspacing="0" cellpadding="2">
       <tr>
        <td bgcolor="#FFFFFF" nowrap>
        <table bgcolor="#C0C0FF" width="100%"><tr><td nowrap><font color="white"><b>Others</b></font></td></tr></table>
        </td></tr></table>
     </td>
  </tr>

  <tr>
     <td>
     <?php echo bx_image(HTTP_IMAGES.$language."/bullet.gif",0,'');?>  <a href="<?php echo HTTP_SERVER_ADMIN."scriptdemo_news.php";?>" class="anav">Scriptdemo News</a>
   <hr>
   </td>
  </tr>
</table>
</td></tr></table>