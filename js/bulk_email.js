function check_form() {
        var error = 0;
        var error_message = "<?php echo eregi_replace("\"","\\\"",JS_ERROR);?>";

        var subject = document.bulkmail.bulk_subject.value;
        var message = document.bulkmail.bulk_message.value;

        //Validation for SUBJECT
        if (subject == "") {
                error_message = error_message + "<?php echo eregi_replace("\"","\\\"",SUBJECT_ERROR);?>\n";
                error = 1;
        }
        //Validation for MESSAGE
        if (message == "") {
                error_message = error_message + "<?php echo eregi_replace("\"","\\\"",MESSAGE_ERROR);?>\n";
                error = 1;
        }

        if (error == 1) {
                alert(error_message);
                return false;
        } else {
                return true;
        }
}