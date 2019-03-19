<?php
  include ('application_config_file.php');
  bx_session_destroy();
  $HTTP_SESSION_VARS['pbm_userid'] = $HTTP_SESSION_VARS['employerid'] = '';
  $pbm_userid = $employerid = '';
  header('Location: ' .HTTP_SERVER.FILENAME_INDEX);
  bx_exit();
?>