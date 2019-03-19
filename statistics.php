<?php
include ('application_config_file.php');
include(DIR_SERVER_ROOT."header.php");
if ($HTTP_SESSION_VARS['employerid']){
    include(DIR_FORMS.FILENAME_STATISTICS_FORM);
}
else {
      include(DIR_FORMS.FILENAME_LOGIN_FORM);
}//end else employerid
include(DIR_SERVER_ROOT."footer.php");
?>