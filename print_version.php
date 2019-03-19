<?
include('application_config_file.php');
include(DIR_LANGUAGES.$language."/".'print_version.php');
?><!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>

<head>
<title></title>
</head>
<frameset rows="30,*" border="0">
			<frame name="ptop" frameborder="no" bordercolor="#F5F5F5" scrolling="no" src="print_top.php" marginwidth="0" marginheight="0" noresize>
			<frame name="pmain" frameborder="no" bordercolor="#FFFFFF" scrolling="auto" src="<?php echo $HTTP_GET_VARS['url'];?>" marginwidth="0" marginheight="0" noresize>
</frameset>
<noframes>
  <body>
  <p><?php echo TEXT_NO_FRAME_SUPPORT;?></p>
  </body>
</noframes>
</html>