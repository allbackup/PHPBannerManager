
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