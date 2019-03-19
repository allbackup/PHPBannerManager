function check_form() {
        var error = 0;
        var error_message = "<?php echo eregi_replace("\"","\\\"",JS_ERROR);?>";
        var login = document.chpasswd.adm_login.value;
        var passwd = document.chpasswd.adm_passwd.value;
        var repasswd = document.chpasswd.adm_repasswd.value;

        //Validation for LOGIN
        if (login == "") {
                error_message = error_message + "<?php echo eregi_replace("\"","\\\"",ADMIN_LOGIN_ERROR);?>\n";
                error = 1;
        }
                //Validation for PASSWORD
        if (passwd == "") {
                error_message = error_message + "<?php echo eregi_replace("\"","\\\"",ADMIN_PASSWORD_ERROR);?>\n";
                error = 1;
        }
                //Validation for LOGIN
        if (repasswd == "") {
                error_message = error_message + "<?php echo eregi_replace("\"","\\\"",ADMIN_REPASSWORD_ERROR);?>\n";
                error = 1;
        }
        if (document.chpasswd.adm_passwd.value != document.chpasswd.adm_repasswd.value) {
                error_message = error_message + "<?php echo eregi_replace("\"","\\\"",ADMIN_PASSWORDMATCH_ERROR);?>\n";
                error = 1;
        }

        if (error == 1) {
                alert(error_message);
                return false;
        } else {
                return true;
        }
}