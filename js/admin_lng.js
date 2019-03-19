function tolowercase() {
        document.addlng.lng.value = document.addlng.lng.value.toLowerCase();
}

function check_form() {
        var error = 0;
        var error_message = "<?php echo eregi_replace("\"","\\\"",JS_ERROR);?>";

        var lng = document.addlng.lng.value;

        //Validation for LANGUAGE_NAME
        if (lng == "") {
                error_message = error_message + "<?php echo eregi_replace("\"","\\\"",LANGUAGE_NAME_ERROR);?>\n";
                error = 1;
        }

        //Validation for BASE_LANGUAGE
        var exist = false;
        if (document.addlng.folders.length>0) {
                for (i=0;i<document.addlng.folders.length ;i++ ) {
                                if (document.addlng.folders[i].checked == true) {
                                        exist = true;
                                }
                }
        }
        else {
                 if (document.addlng.folders.checked == true) {
                                exist = true;
                 }
        }
        if (!exist) {
                error_message = error_message + "<?php echo eregi_replace("\"","\\\"",BASE_LANGUAGE_ERROR);?>\n";
                error = 1;
        }

        if (error == 1) {
                alert(error_message);
                return false;
        } else {
                return true;
        }
}

function check_form_dellng() {
        var error = 0;
        var error_message = "<?php echo eregi_replace("\"","\\\"",JS_ERROR);?>";

        //Validation for BASE_LANGUAGE
        var exist = false;
        if (document.dellng.folders.length) {
                for (i=0;i<document.dellng.folders.length ;i++ ) {
                                if (document.dellng.folders[i].checked == true) {
                                        exist = true;
                                }
                }
        }
        else {
                if (document.dellng.folders.checked == true) {
                                exist = true;
                }
        }
        if (!exist) {
                error_message = error_message + "<?php echo eregi_replace("\"","\\\"",DELETE_LANGUAGE_ERROR);?>\n";
                error = 1;
        }

        if (error == 1) {
                alert(error_message);
                return false;
        } else {
                return confirm("<?php echo eregi_replace("\"","\\\"",TEXT_CONFIRM_LANGUAGE_DELETE);?>");
        }
}

function check_form_editlng() {
        var error = 0;
        var error_message = "<?php echo eregi_replace("\"","\\\"",JS_ERROR);?>";

        //Validation for BASE_LANGUAGE
        var exist = false;
        if (document.editlng.folders.length) {
                for (i=0;i<document.editlng.folders.length ;i++ ) {
                                if (document.editlng.folders[i].checked == true) {
                                        exist = true;
                                }
                }
        }
        else {
                if (document.editlng.folders.checked == true) {
                                exist = true;
                }
        }
        if (!exist) {
                error_message = error_message + "<?php echo eregi_replace("\"","\\\"",EDIT_LANGUAGE_ERROR);?>\n";
                error = 1;
        }

        if (error == 1) {
                alert(error_message);
                return false;
        } else {
                return true;
        }
}