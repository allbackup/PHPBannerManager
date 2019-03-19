
function isSTR(val){
        var str = val;
        // Return false if characters are not a-z, A-Z, or a space.
        for (var i = 0; i < str.length; i++){
                var ch = str.substring(i, i + 1);
                if (((ch < "a" || "z" < ch) && (ch < "A" || "Z" < ch)) && ch != ' '){
                       return 1;
                }
        }
        return 0;
}
function isEmail(val)
   {
   // Return false if e-mail field does not contain a '@' and '.' .
   if (val.indexOf ('@',0) == -1 ||
       val.indexOf ('.',0) == -1)
      {
      return 1;
      }
   else
      {
      return 0;
      }
   }

function isNum(str)
   {
   // Return false if characters are not '0-9' or '.' .
   for (var i = 0; i < str.length; i++)
      {
      var ch = str.substring(i, i + 1);
      if ((ch < "0" || "9" < ch) && ch != '.' && ch != '-')
         {
         return 1;
         }
      }
   return 0;
  }

function isPhone(str)
   {
   // Return false if characters are not '0-9' or '.' .
   for (var i = 0; i < str.length; i++)
      {
      var ch = str.substring(i, i + 1);
      if ((ch < "0" || "9" < ch) && ch != ' ' && ch != '+' && ch != '-' && ch !=')' && ch !='(')
         {
         return 1;
         }
      }
   return 0;
  }

function check_form() {
  var error = 0;
  var error_message = "<?php echo eregi_replace("\"","\\\"",JS_ERROR);?>";

 var company = document.frm.company.value;
 var password = document.frm.password.value;
 var confpassword = document.frm.confpassword.value;
 var address = document.frm.address.value;
 var phone = document.frm.phone.value;
 var email = document.frm.email.value;
 var postalcode = document.frm.postalcode.value;
 var city = document.frm.city.value;
 var agree = document.frm.agree;

   //Validation for COMPANY
   if (company == "" || company.length < <?php echo ENTRY_COMPANY_MIN_LENGTH;?>) {
    error_message = error_message + "<?php echo eregi_replace("\"","\\\"",COMPANY_ERROR);?>\n";
    error = 1;
    }
   //Validation for PASSWORD
   if (password == "" || password.length < <?php echo ENTRY_PASSWORD_MIN_LENGTH;?>) {
    error_message = error_message + "<?php echo eregi_replace("\"","\\\"",PASSWORD_ERROR);?>\n";
    error = 1;
    }
    //Validation for RETYPE_PASSWORD
   if (confpassword == "" || confpassword.length < <?php echo ENTRY_PASSWORD_MIN_LENGTH;?>) {
    error_message = error_message + "<?php echo eregi_replace("\"","\\\"",CONFPASSWORD_ERROR);?>\n";
    error = 1;
    }
   if (document.frm.password.value!=document.frm.confpassword.value)
     {
    error_message = error_message + "<?php echo eregi_replace("\"","\\\"",CONFPASSWORD_ERROR);?>\n";
    error = 1;
    }
      //Validation for EMAIL
  if (email == "" || email.length < <?php echo ENTRY_EMAIL_MIN_LENGTH;?>) {
    error_message = error_message + "<?php echo eregi_replace("\"","\\\"",EMAIL_ERROR);?>\n";
    error = 1;
    }
    ret=isEmail(document.frm.email.value);
   if (ret==1)
    {
    error_message = error_message + "<?php echo eregi_replace("\"","\\\"",EMAIL_ERROR);?>\n";
    error = 1;
    }
    //Validation for ADDRESS
   if (address == "" || address.length < <?php echo ENTRY_ADDRESS_MIN_LENGTH;?>) {
    error_message = error_message + "<?php echo eregi_replace("\"","\\\"",ADDRESS_ERROR);?>\n";
    error = 1;
    }
    //Validation for   TELEPHONE
   if (phone == "" || phone.length < <?php echo ENTRY_PHONE_MIN_LENGTH;?>) {
    error_message = error_message + "<?php echo eregi_replace("\"","\\\"",PHONE_ERROR);?>\n";
    error = 1;
    }

   ret=isPhone(document.frm.phone.value);
   if (ret==1)
    {
    error_message = error_message + "<?php echo eregi_replace("\"","\\\"",PHONE_ERROR);?>\n";
    error = 1;
    }

    //Validation for POSTALCODE
     if (postalcode == "" || postalcode.length < <?php echo ENTRY_POSTALCODE_MIN_LENGTH;?>) {
    error_message = error_message + "<?php echo eregi_replace("\"","\\\"",POSTALCODE_ERROR);?>\n";
    error = 1;
    }

    //Validation for CITY
    if (city == "" || city.length < <?php echo ENTRY_CITY_MIN_LENGTH;?>) {
    error_message = error_message + "<?php echo eregi_replace("\"","\\\"",CITY_ERROR);?>\n";
    error = 1;
    }

    //Validation for TERMS AND CONDITIONS
    if ( (!agree.checked) && agree.value!="yes" ) {
        error_message = error_message + "<?php echo eregi_replace("\"","\\\"",TERMS_ERROR);?>\n";
        error = 1;
    }

  if (error == 1) {
    alert(error_message);
    return false;
  } else {
    return true;
  }
}
