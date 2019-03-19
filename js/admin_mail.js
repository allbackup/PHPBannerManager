function tolowercase() {
        document.addlng.lng.value = document.addlng.lng.value.toLowerCase();
}

function checkifexist(inputtext, tosearch) {
        if (inputtext.value.indexOf(tosearch)>=0) {
                return true;
        }
        else {
                return false;
        }
}

function add_mail_signature(inputtext, tosearch) {
        if (checkifexist(inputtext, tosearch)) {
                        alert('Already exist mail signature in the mail message');
        }
        else {
                        inputtext.value += "\n"+tosearch;
                        alert('Added mail signature at the end of the mail message text!');
        }
}

function remove_mail_signature(inputtext, tosearch) {
        if (checkifexist(inputtext, tosearch)) {
                inputtext.value = inputtext.value.substring(0,inputtext.value.indexOf(tosearch))+inputtext.value.substring(inputtext.value.indexOf(tosearch)+tosearch.length, inputtext.value.length);
                alert('Removed mail signature from the mail text message!');
        }
        else {
                alert('Mail signature not exist in the mail message');
        }
}

function check_form_editmail() {
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