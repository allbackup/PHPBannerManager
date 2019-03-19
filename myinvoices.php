<?php
include ('application_config_file.php');
include(DIR_SERVER_ROOT."header.php");

if ($HTTP_SESSION_VARS['pbm_userid']) {
      include(DIR_FORMS.FILENAME_MYINVOICES_FORM);
}
else
{
    include( 'header.php');
	include(DIR_FORMS. 'login_form.php');
}
include(DIR_SERVER_ROOT."footer.php");
?>