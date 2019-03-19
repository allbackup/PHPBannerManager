<?php
include('admin_design.php');
include('../application_config_file.php');
include('admin_auth.php');
$jsfile = "admin.js";
include("header.php");
if ($HTTP_GET_VARS['action']=="details" || $HTTP_POST_VARS['action']=="details") {
		include(DIR_ADMIN."success_form.php");    
}
else {
 		include(DIR_ADMIN.FILENAME_ADMIN_FORM);    
}
include("footer.php");
?>