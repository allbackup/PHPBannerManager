/* -------------------------------------------------------
*
* PhpConcept Javascript Color Chooser
* Created by Vincent Blavet (vincent@blavet.net) and with some modification made by bitmixsoft.com (Zsolt Mali)
* All right reserved by PhpConcept (http://www.phpconcept.net)
* GNU GPL Licence
*
* For more informations : http://www.phpconcept.net
* -------------------------------------------------------
* Demonstration available at http://www.phpconcept.net
*
* User Guide :
*   1. Insert the script pcjscolorchooser.js in your
*      page, AFTER the BODY tag.
*
*   2. Call the Color Chooser by the JavaScript function
*      PcjsColorChooser(object, property)
*      Where : 'object' is a valid JavaScript object
*              'property' a valid property for the object
*      The selected value will be set like this :
*      object.property = color
*
*   3. Example 1 : Selection as a input/text value :
*      <form name="form2">
*        Color :<input type="text" name="colortext">
*        <input type="button "value="Choose" onClick="PcjsColorChooser(document.forms.form2.colortext,'value')">
*      </form>
*
*   4. Example 2 : Selection as a background table cell :
*      <form name="form2">
*        <table><tr><td>Color : </td>
*        <td width=20 id=testcell>&nbsp;</td>
*        <td>
*          <input type="button" name="Submit2" value="Choisir" onClick="PcjsColorChooser(document.all.testcell,'bgColor')">
*        </td>
*        </tr></table>
*      </form>
* -------------------------------------------------------
*/

// ----- Specific global values
var PcjsDestObject;
var PcjsDestProperty;
NS4 = (document.layers) ? 1 : 0;

// ----- Start color chooser
function PcjsColorChooser2(destobject, destproperty, e) {
        object = 'PcjsPopup';
if (document.getElementById && document.getElementById(object) != null)
        node = document.getElementById(object).style.visibility='visible';
else if (document.layers && document.layers[object] != null) {
        document.layers[object].visibility = 'visible';
                document.layers[object].left = window.pageXOffset - 30;
                document.layers[object].top  = window.pageYOffset - 40;
                }
else if (document.all)
        document.all[object].style.visibility = 'visible';
}
function PcjsColorChooser(destobject, destproperty, destlayer, e)
{
// ----- Store the destination object
PcjsDestObject = destobject;
PcjsDestLayer = destlayer;
if (destproperty == "")
PcjsDestProperty = "value";
else
PcjsDestProperty = destproperty;

// ----- Get the initial value
eval("PcjsInternalSelectColor(PcjsDestObject."+PcjsDestProperty+")");

// ----- Select the ilayer
if (document.layers) {
        var obj = document.layers['PcjsPopup'];
}
else if (document.all) {
                var obj = document.all['PcjsPopup'];
}
else {
        var obj = document.getElementById('PcjsPopup');
}

// ----- Set the popup position
if (document.layers) {
                obj.left = window.pageXOffset-30;
                obj.top  = window.pageYOffset-10;
}
else if (document.all) {
        obj.style.left = e.x-300;
                if (navigator.appName.indexOf('Opera')!=-1) {
                        obj.style.top  = e.y;
                }
                else {
                        obj.style.top  = document.body.scrollTop+e.y;
                }
}
else {
                obj.style.left = e.pageX-300;
                obj.style.top = e.pageY-10;
}
// ----- Close the ilayer
if (document.layers) {
        obj.visibility = "show";
}
else if (document.all) {
        obj.style.visibility = "visible";
}
else {
        obj.style.visibility = "visible";
}
}

// ----- Close function for color chooser without selection
function PcjsInternalClosePopup()
{
// ----- Select the ilayer
if (document.layers) {
        var obj = document.layers['PcjsPopup'];
obj.visibility = "hide";
}
else if (document.all) {
        var obj = document.all['PcjsPopup'];
        obj.style.visibility = "hidden";
}
else {
        var obj = document.getElementById('PcjsPopup');
        obj.style.visibility = "hidden";
}
}

// ----- Close function for color chooser with selection
function PcjsInternalSelectClose()
{
// ----- Select the ilayer
if (document.layers) {
        var obj = document.layers['PcjsPopup'];
}
else if (document.all) {
        var obj = document.all['PcjsPopup'];
}
else {
        var obj = document.getElementById('PcjsPopup');
}

// ----- Get the value and paste it to destination object
PcjsDestObject.value = document.forms.pcjsform.color.value;

// ----- Look for object type
eval("PcjsDestObject."+PcjsDestProperty+" = document.forms.pcjsform.color.value");

// ----- Close the ilayer

if (document.layers) {
        obj.visibility = "hide";
}
else if (document.all) {
obj.style.visibility = "hidden";
}
else {
        obj.style.visibility = "hidden";
}
makecolor(PcjsDestObject, PcjsDestLayer);
}

// ----- Internal color selection
function PcjsInternalSelectColor(color)
{
// ----- Paste the color value
document.forms.pcjsform.color.value = color.toUpperCase();

// ----- Change the color viewer
if (document.layers) {
        document.layers['pcjscell'].bgColor = color.toUpperCase();
        document.layers['id2000'].bgColor = color.toUpperCase();
}
else if (document.all) {
        document.all.pcjscell.style.background = color.toUpperCase();
        document.all.id2000.style.background = color.toUpperCase();
}
else {
        document.getElementById('pcjscell').style.background = color.toUpperCase();
        document.getElementById('id2000').style.background = color.toUpperCase();
}
makeinit();
}

// ----- Popup window creator
function PcjsGeneratePopup()
{
// ----- Generate the div tag
/* document.write("<div id=\"PcjsPopup\" style='position:absolute; left:118px; top:214px; width:312px; height:112px; z-index:1; visibility:hidden; background-color: #FFFFFF; layer-background-color: #FFFFFF; border: 1px none #000000'> ");
document.write("<form name=\"pcjsform\" method=\"post\">");
document.write("<table width=\"100%\" border=\"0\" cellspacing=\"0\" bgcolor=\"#0000CC\">");
document.write("<tr><td height=\"13\" width=\"5\"></td><td height=\"13\"> ");
document.write("<div align=right><font face='Verdana, Arial, Helvetica, sans-serif' color=#FFFFFF size=2><b><a onClick='PcjsInternalClosePopup()'>x</a></b></font></div>");
document.write("</td><td height=13 width=5> </td></tr>");
document.write("<tr><td height=13 width=5><td align=center bgcolor=#FFFFFF><font face='Verdana, Arial, Helvetica, sans-serif' size=2><b><font color=#0000CC size=3>");
document.write("Color Chooser</font><font color=#0000CC> </font></b></font><br></td></tr>");
document.write("<tr> <td height=81 width=5></td><td height=81 bgcolor=#FFFFFF> ");
document.write("<table border=0><tr><td><table border=1 cellspacing=0 align=center bordercolor=#FFFFFF>");


for (i=0;i<6;i++)
{
document.write("<tr>");
if (i==0) v_color_i="00";
if (i==1) v_color_i="33";
if (i==2) v_color_i="66";
if (i==3) v_color_i="99";
if (i==4) v_color_i="CC";
if (i==5) v_color_i="FF";
for (j=0;j<6;j++)
{
if (j==0) v_color_j="00";
if (j==1) v_color_j="33";
if (j==2) v_color_j="66";
if (j==3) {v_color_j="99"; document.write("<tr></tr>");}
if (j==4) v_color_j="CC";
if (j==5) v_color_j="FF";
for (k=0;k<6;k++)
{
        if (k==0) v_color_k="00";
        if (k==1) v_color_k="33";
        if (k==2) v_color_k="66";
        if (k==3) v_color_k="99";
        if (k==4) v_color_k="CC";
        if (k==5) v_color_k="FF";
        document.write("<td bgcolor=#"+v_color_i+v_color_j+v_color_k+" onClick=PcjsInternalSelectColor('#"+v_color_i+v_color_j+v_color_k+"') width=4 height=8><img src=/other/english/pix-t.gif width=4 height=8></td>");
}
if (j==5) {v_color_j="99"; document.write("</tr>");}
}
document.write("</tr>");
}

// ----- Basic color selection
document.write("<tr><td colspan=18></td><td>");
document.write("<tr><td colspan=3></td>");
document.write("<td bgcolor=#000000 onClick=PcjsInternalSelectColor('#000000') width=4 height=8><img src=/other/english/pix-t.gif width=4 height=8></td>");
document.write("<td bgcolor=#333333 onClick=PcjsInternalSelectColor('#333333') width=4 height=8><img src=/other/english/pix-t.gif width=4 height=8></td>");
document.write("<td bgcolor=#666666 onClick=PcjsInternalSelectColor('#666666') width=4 height=8><img src=/other/english/pix-t.gif width=4 height=8></td>");
document.write("<td bgcolor=#999999 onClick=PcjsInternalSelectColor('#999999') width=4 height=8><img src=/other/english/pix-t.gif width=4 height=8></td>");
document.write("<td bgcolor=#CCCCCC onClick=PcjsInternalSelectColor('#CCCCCC') width=4 height=8><img src=/other/english/pix-t.gif width=4 height=8></td>");
document.write("<td bgcolor=#FFFFFF onClick=PcjsInternalSelectColor('#FFFFFF') width=4 height=8><img src=/other/english/pix-t.gif width=4 height=8></td>");
document.write("<td bgcolor=#FF0000 onClick=PcjsInternalSelectColor('#FF0000') width=4 height=8><img src=/other/english/pix-t.gif width=4 height=8></td>");
document.write("<td bgcolor=#00FF00 onClick=PcjsInternalSelectColor('#00FF00') width=4 height=8><img src=/other/english/pix-t.gif width=4 height=8></td>");
document.write("<td bgcolor=#0000FF onClick=PcjsInternalSelectColor('#0000FF') width=4 height=8><img src=/other/english/pix-t.gif width=4 height=8></td>");
document.write("<td bgcolor=#FFFF00 onClick=PcjsInternalSelectColor('#FFFF00') width=4 height=8><img src=/other/english/pix-t.gif width=4 height=8></td>");
document.write("<td bgcolor=#00FFFF onClick=PcjsInternalSelectColor('#00FFFF') width=4 height=8><img src=/other/english/pix-t.gif width=4 height=8></td>");
document.write("<td bgcolor=#FF00FF onClick=PcjsInternalSelectColor('#FF00FF') width=4 height=8><img src=/other/english/pix-t.gif width=4 height=8></td>");
document.write("<td colspan=3></td></tr>");

document.write("<tr><td colspan=18 align=center><table><tr><td width=50%>");
document.write("<font face='Verdana, Arial, Helvetica, sans-serif' size=2 color=#0000CC>Color : </font>");
document.write("<input type=text name=color size=8 maxlength=8 onChange='makeChanges(0);'></td>");
document.write("<td align=center valign=middle> ");
document.write("<table border=0 cellspacing=0 cellpadding=0 align=center width=100%>");
document.write("<tr>");
document.write("<td id=pcjscell width=30 height=35 align=center valign=top>&nbsp;</td>");
document.write("</tr>");
document.write("</table>");
document.write("</td></tr></table></td></tr>");
document.write("<tr><td colspan=18 align=center>");
document.write("<input type=button name=select value=Accept onClick='PcjsInternalSelectClose()'>");
document.write("</td></tr></table></td>");
document.write("<td valign=top bgcolor=#DDDDDD><table bgcolor=1 border=1 cellspacing=0 cellpadding=2 align=center bordercolor=#DDDDDD><tr><td id=id2000 width=200 height=100 align=center valign=middle>&nbsp;</td></tr>");
document.write('<tr><td><TABLE BORDER=0 width=100% CELLPADDING=3 bgcolor=#DDDDDD>');
document.write('<tr><td><b>Red:</b></td><td><input type="text" name="rcolor" size="3" value="" onChange="makeChanges(1);"><INPUT TYPE="button" VALUE="+" ONCLICK="changeColor(1)"><INPUT TYPE="button" VALUE="-" ONCLICK="changeColor(4)"></td></tr>');
document.write('<tr><td><b>Green:</b></td><td><input type="text" name="gcolor" size="3" value="" onChange="makeChanges(2);"><INPUT TYPE="button" VALUE="+" ONCLICK="changeColor(2)"><INPUT TYPE="button" VALUE="-" ONCLICK="changeColor(5)"></td></tr>');

document.write('<tr><td><b>Blue:</b></td><td><input type="text" name="bcolor" size="3" value="" onChange="makeChanges(3);"><INPUT TYPE="button" VALUE="+" ONCLICK="changeColor(3)"><INPUT TYPE="button" VALUE="-" ONCLICK="changeColor(6)"></td></tr>');

document.write('</TABLE></td></tr>');
document.write("</table></td></table>");


document.write("</form></td><td height=71 width=5></td></tr>");
document.write("<tr height=5><td height=5 width=5></td><td height=5></td><td height=5 width=5></td></tr>");
document.write("</table></div>");*/
}

function PcjsGeneratePopup2()
{
// ----- Generate the div tag
var t = "";
t += "<div id=PcjsPopup style='position:absolute; left:118px; top:214px; width:312px; height:112px; z-index:1; visibility:hidden; background-color: #FFFFFF; layer-background-color: #FFFFFF; border: 1px none #000000'> ";
t += "<table width=100% border=0 cellspacing=0 bgcolor=#0000CC>";
t += "<tr><td height=13 width=5></td><td height=13> ";
t += "<div align=right><font face='Verdana, Arial, Helvetica, sans-serif' color=#FFFFFF size=2><b><a onClick='PcjsInternalClosePopup()'>x</a></b></font></div>";
t += "</td><td height=13 width=5> </td></tr>";
t += "<tr><td height=13 width=5><td align=center bgcolor=#FFFFFF><font face='Verdana, Arial, Helvetica, sans-serif' size=2><b><font color=#0000CC size=3>";
t += "Color Chooser</font><font color=#0000CC> </font></b></font><br></td></tr>";
t += "<tr> <td height=81 width=5></td><td height=81 bgcolor=#FFFFFF> ";
t += "<form name=pcjsform method=post>";
t += "<table border=0><tr><td><table border=1 cellspacing=0 align=center bordercolor=#FFFFFF>";


for (i=0;i<6;i++)
{
t += "<tr>\n";
if (i==0) v_color_i="00";
if (i==1) v_color_i="33";
if (i==2) v_color_i="66";
if (i==3) v_color_i="99";
if (i==4) v_color_i="CC";
if (i==5) v_color_i="FF";
for (j=0;j<6;j++)
{
if (j==0) v_color_j="00";
if (j==1) v_color_j="33";
if (j==2) v_color_j="66";
if (j==3) {v_color_j="99"; t += "</tr><tr>\n";}
if (j==4) v_color_j="CC";
if (j==5) v_color_j="FF";
for (k=0;k<6;k++)
{
        if (k==0) v_color_k="00";
        if (k==1) v_color_k="33";
        if (k==2) v_color_k="66";
        if (k==3) v_color_k="99";
        if (k==4) v_color_k="CC";
        if (k==5) v_color_k="FF";
        t += "<td bgcolor=#"+v_color_i+v_color_j+v_color_k+" onClick=PcjsInternalSelectColor('#"+v_color_i+v_color_j+v_color_k+"') width=4 height=8><img src=test.gif width=4 height=8></td>\n";
}
if (j==5) {v_color_j="99"; t += "<tr>\n";}
}
t += "</tr>\n";
}

// ----- Basic color selection
t += "<tr><td colspan=18></td><td>";
t += "<tr><td colspan=3></td>";
t += "<td bgcolor=#000000 onClick=PcjsInternalSelectColor('#000000') width=8 height=8></td>";
t += "<td bgcolor=#333333 onClick=PcjsInternalSelectColor('#333333') width=8 height=8></td>";
t += "<td bgcolor=#666666 onClick=PcjsInternalSelectColor('#666666') width=8 height=8></td>";
t += "<td bgcolor=#999999 onClick=PcjsInternalSelectColor('#999999') width=8 height=8></td>";
t += "<td bgcolor=#CCCCCC onClick=PcjsInternalSelectColor('#CCCCCC') width=8 height=8></td>";
t += "<td bgcolor=#FFFFFF onClick=PcjsInternalSelectColor('#FFFFFF') width=8 height=8></td>";
t += "<td bgcolor=#FF0000 onClick=PcjsInternalSelectColor('#FF0000') width=8 height=8></td>";
t += "<td bgcolor=#00FF00 onClick=PcjsInternalSelectColor('#00FF00') width=8 height=8></td>";
t += "<td bgcolor=#0000FF onClick=PcjsInternalSelectColor('#0000FF') width=8 height=8></td>";
t += "<td bgcolor=#FFFF00 onClick=PcjsInternalSelectColor('#FFFF00') width=8 height=8></td>";
t += "<td bgcolor=#00FFFF onClick=PcjsInternalSelectColor('#00FFFF') width=8 height=8></td>";
t += "<td bgcolor=#FF00FF onClick=PcjsInternalSelectColor('#FF00FF') width=8 height=8></td>";
t += "<td colspan=3></td></tr>";

t += "<tr><td colspan=18 align=center><table><tr><td width=50%>";
t += "<font face='Verdana, Arial, Helvetica, sans-serif' size=2 color=#0000CC>Color : </font>";
t += "<input type=text name=color size=8 maxlength=8 onChange='makeChanges(0);'></td>";
t += "<td align=center valign=middle> ";
t += "<table border=0 cellspacing=0 cellpadding=0 align=center width=100%>";
t += "<tr>";
t += "<td id=pcjscell width=30 height=35 align=center valign=top>&nbsp;</td>";
t += "</tr>";
t += "</table>";
t += "</td></tr></table></td></tr>";
t += "<tr><td colspan=18 align=center>";
t += "<input type=button name=select value=Accept onClick='PcjsInternalSelectClose()'>";
t += "</td></tr></table></td>";
t += "<td valign=top bgcolor=#DDDDDD><table bgcolor=1 border=1 cellspacing=0 cellpadding=2 align=center bordercolor=#DDDDDD><tr><td id=id2000 width=200 height=100 align=center valign=middle>&nbsp;</td></tr>";
t += '<tr><td><TABLE BORDER=0 width=100% CELLPADDING=3 bgcolor=#DDDDDD>';
t += '<tr><td><b>Red:</b></td><td><input type="text" name="rcolor" size="3" value="" onChange="makeChanges(1);"><INPUT TYPE="button" VALUE="+" ONCLICK="changeColor(1)"><INPUT TYPE="button" VALUE="-" ONCLICK="changeColor(4)"></td></tr>';
t += '<tr><td><b>Green:</b></td><td><input type="text" name="gcolor" size="3" value="" onChange="makeChanges(2);"><INPUT TYPE="button" VALUE="+" ONCLICK="changeColor(2)"><INPUT TYPE="button" VALUE="-" ONCLICK="changeColor(5)"></td></tr>';

t += '<tr><td><b>Blue:</b></td><td><input type="text" name="bcolor" size="3" value="" onChange="makeChanges(3);"><INPUT TYPE="button" VALUE="+" ONCLICK="changeColor(3)"><INPUT TYPE="button" VALUE="-" ONCLICK="changeColor(6)"></td></tr>';

t += '</TABLE></td></tr>';
t += "</table></td></table>";


t += "</form></td><td height=71 width=5></td></tr>";
t += "<tr height=5><td height=5 width=5></td><td height=5></td><td height=5 width=5></td></tr>";
t += "</table></div>";
document.write(t);
document.write('<textarea>'+t+'</textarea>');
}

function nwindow()
{
nWindow = this.open('','','toolbar=no,location=no,directories=no,status=no,scrollbars=yes,resizable=yes,copyhistory=no,width=500,height=200');
}

// ----- Call the Color Chooser Popup Window generator function

// +- red green blue

function makeSubs(color) {
var colore = new String(color);
rnum1=hex2num(colore.substring(1,2).toUpperCase());
rnum2=hex2num(colore.substring(2,3).toUpperCase());
gnum1=hex2num(colore.substring(3,4).toUpperCase());
gnum2=hex2num(colore.substring(4,5).toUpperCase());
bnum1=hex2num(colore.substring(5,6).toUpperCase());
bnum2=hex2num(colore.substring(6,7).toUpperCase());
}
function makeNN(colore) {
//alert("bg="+document.getElementById('id2000').style.backgroundColor);
var ccc = colore;
//alert(ccc.length+ccc.indexOf(","));
ccc = ccc.substring(4,ccc.length-1);
var nred = ccc.substring(0,ccc.indexOf(','));
ccc = ccc.substring(ccc.indexOf(',')+1,ccc.length);
var ngreen = ccc.substring(0,ccc.indexOf(','));
ccc = ccc.substring(ccc.indexOf(',')+1,ccc.length);
var nblue = ccc;
//alert(red+"-"+green+"-"+blue);
rnum1 = Math.floor(nred/16);
rnum2 = nred%16;
gnum1 = Math.floor(ngreen/16);
gnum2 = ngreen%16;
bnum1 = Math.floor(nblue/16);
bnum2 = nblue%16;
}
function makeColorNN(colore) {
var ccc = colore;
//alert(ccc.length+ccc.indexOf(","));
ccc = ccc.substring(4,ccc.length-1);
var nred = ccc.substring(0,ccc.indexOf(','));
ccc = ccc.substring(ccc.indexOf(',')+1,ccc.length);
var ngreen = ccc.substring(0,ccc.indexOf(','));
ccc = ccc.substring(ccc.indexOf(',')+1,ccc.length);
var nblue = ccc;
//alert(red+"-"+green+"-"+blue);
rnum1 = Math.floor(nred/16);
rnum2 = nred%16;
gnum1 = Math.floor(ngreen/16);
gnum2 = ngreen%16;
bnum1 = Math.floor(nblue/16);
bnum2 = nblue%16;
return prefix+num2hex(rnum1)+num2hex(rnum2)+num2hex(gnum1)+num2hex(gnum2)+num2hex(bnum1)+num2hex(bnum2);
}

function makeChanges(type) {
if (type==1) {
var tempred = 0;
tempred = document.forms.pcjsform.rcolor.value;
if (tempred>255) {
        alert("Invalid color number!!!");
        return false;
}
rnum1 = Math.floor(tempred/16);
rnum2 = tempred%16;
hexNumber2 = prefix+num2hex(rnum1)+num2hex(rnum2)+num2hex(gnum1)+num2hex(gnum2)+num2hex(bnum1)+num2hex(bnum2);
if (document.layers) {
document.layers.pcjscell.bgColor = hexNumber2;
document.layers.id2000.bgColor = hexNumber2;
}
else if (document.all) {
document.all.id2000.style.background = hexNumber2;
document.all.pcjscell.style.background = hexNumber2;
}
else {
document.getElementById('id2000').style.background = hexNumber2;
document.getElementById('pcjscell').style.background = hexNumber2;
}
document.forms.pcjsform.color.value = hexNumber2;
document.forms.pcjsform.rcolor.value = rnum1*16+rnum2;
document.forms.pcjsform.gcolor.value = gnum1*16+gnum2;
document.forms.pcjsform.bcolor.value = bnum1*16+bnum2;
}

if (type==2) {
var tempgreen = 0;
tempgreen = document.forms.pcjsform.gcolor.value;
if (tempgreen>255) {
        alert("Invalid color number!!!");
        return false;
}
gnum1 = Math.floor(tempgreen/16);
gnum2 = tempgreen%16;
hexNumber2 = prefix+num2hex(rnum1)+num2hex(rnum2)+num2hex(gnum1)+num2hex(gnum2)+num2hex(bnum1)+num2hex(bnum2);

if (document.layers) {
document.layers.pcjscell.bgColor = hexNumber2;
document.layers.id2000.bgColor = hexNumber2;
}
else if (document.all) {
document.all.id2000.style.background = hexNumber2;
document.all.pcjscell.style.background = hexNumber2;
}
else {
document.getElementById('id2000').style.background = hexNumber2;
document.getElementById('pcjscell').style.background = hexNumber2;
}
document.forms.pcjsform.color.value = hexNumber2;
document.forms.pcjsform.rcolor.value = rnum1*16+rnum2;
document.forms.pcjsform.gcolor.value = gnum1*16+gnum2;
document.forms.pcjsform.bcolor.value = bnum1*16+bnum2;
}

if (type==3) {
var tempblue = 0;
tempblue = document.forms.pcjsform.bcolor.value;
if (tempblue>255) {
        alert("Invalid color number!!!");
        return false;
}
bnum1 = Math.floor(tempblue/16);
bnum2 = tempblue%16;
hexNumber2 = prefix+num2hex(rnum1)+num2hex(rnum2)+num2hex(gnum1)+num2hex(gnum2)+num2hex(bnum1)+num2hex(bnum2);
if (document.layers) {
document.layers.pcjscell.bgColor = hexNumber2;
document.layers.id2000.bgColor = hexNumber2;
}
else if (document.all) {
document.all.id2000.style.background = hexNumber2;
document.all.pcjscell.style.background = hexNumber2;
}
else {
document.getElementById('id2000').style.background = hexNumber2;
document.getElementById('pcjscell').style.background = hexNumber2;
}
document.forms.pcjsform.color.value = hexNumber2;
document.forms.pcjsform.rcolor.value = rnum1*16+rnum2;
document.forms.pcjsform.gcolor.value = gnum1*16+gnum2;
document.forms.pcjsform.bcolor.value = bnum1*16+bnum2;
}

if (type == 0) {
color = new String(document.forms.pcjsform.color.value);
rnum1=hex2num(color.substring(1,2).toUpperCase());
rnum2=hex2num(color.substring(2,3).toUpperCase());
gnum1=hex2num(color.substring(3,4).toUpperCase());
gnum2=hex2num(color.substring(4,5).toUpperCase());
bnum1=hex2num(color.substring(5,6).toUpperCase());
bnum2=hex2num(color.substring(6,7).toUpperCase());

document.forms.pcjsform.rcolor.value = rnum1*16+rnum2;
document.forms.pcjsform.gcolor.value = gnum1*16+gnum2;
document.forms.pcjsform.bcolor.value = bnum1*16+bnum2;
if (document.layers) {
document.layers.pcjscell.bgColor = color;
document.layers.id2000.bgColor = color;
}
else if (document.all) {
document.all.id2000.style.background = color;
document.all.pcjscell.style.background = color;
}
else {
document.getElementById('id2000').style.background = color;
document.getElementById('pcjscell').style.background = color;
}

}

}

function makeinit() {
if (document.layers) {
        color = document.layers['id2000'].bgColor;
}
else if (document.all) {
        color = document.all.id2000.style.background;
}
else {
        color = new String (makeColorNN(document.getElementById('id2000').style.backgroundColor));
}
rnum1=hex2num(color.substring(1,2).toUpperCase());
rnum2=hex2num(color.substring(2,3).toUpperCase());
gnum1=hex2num(color.substring(3,4).toUpperCase());
gnum2=hex2num(color.substring(4,5).toUpperCase());
bnum1=hex2num(color.substring(5,6).toUpperCase());
bnum2=hex2num(color.substring(6,7).toUpperCase());
document.forms.pcjsform.rcolor.value = rnum1*16+rnum2;
document.forms.pcjsform.gcolor.value = gnum1*16+gnum2;
document.forms.pcjsform.bcolor.value = bnum1*16+bnum2;
}

/*function changeBackground(hexNumber) {
document.bgColor=hexNumber
}*/

function num2hex(num) {
if (num==15) return "F";
else if (num==14) return "E";
else if (num==13) return "D";
else if (num==12) return "C";
else if (num==11) return "B";
else if (num==10) return "A";
else if (num==9) return "9";
else if (num==8) return "8";
else if (num==7) return "7";
else if (num==6) return "6";
else if (num==5) return "5";
else if (num==4) return "4";
else if (num==3) return "3";
else if (num==2) return "2";
else if (num==1) return "1";
else return "0";
}
function hex2num(num) {
if (num=="F") return 15;
else if (num=="E") return 14;
else if (num=="D") return 13;
else if (num=="C") return 12;
else if (num=="B") return 11;
else if (num=="A") return 10;
else if (num=="9") return 9;
else if (num=="8") return 8;
else if (num=="7") return 7;
else if (num=="6") return 6;
else if (num=="5") return 5;
else if (num=="4") return 4;
else if (num=="3") return 3;
else if (num=="2") return 2;
else if (num=="1") return 1;
else return 0;
}

function changeColor(number) {
if (document.layers) {
        makeSubs(document.layers.id2000.bgColor);
}
else if (document.all) {
        makeSubs(document.all.id2000.style.background);
}
else {
        //makeSubs(document.getElementById('id2000').style.background);
        makeNN(document.getElementById('id2000').style.backgroundColor);
}
//alert(rnum1);
if(number == 1) {
if ((rnum2 < 15) && (rnum1<15)) {
rnum2 += 1;
}
else {
        if ((rnum2 == 15) && (rnum1 <15)) {
                rnum1 += 1;
                rnum2 = 0;
        }
        if ((rnum2 == 15) && (rnum1 == 15)) {
                rnum1 = 0;
                rnum2 = 0;
        }
if ((rnum2 < 15) && (rnum1 == 15)) {
                rnum2 += 1;
        }
}
}

if(number == 2) {
if ((gnum2 < 15) && (gnum1<15)) {
gnum2 += 1;
}
else {
        if ((gnum2 == 15) && (gnum1 <15)) {
                gnum1 += 1;
                gnum2 = 0;
        }
        if ((gnum2 == 15) && (gnum1 == 15)) {
                gnum1 = 0;
                gnum2 = 0;
        }
if ((gnum2 < 15) && (gnum1 == 15)) {
                gnum2 += 1;
        }
}
}

if(number == 3) {
if ((bnum2 < 15) && (bnum1<15)) {
bnum2 += 1;
}
else {
        if ((bnum2 == 15) && (bnum1 <15)) {
                bnum1 += 1;
                bnum2 = 0;
        }
        if ((bnum2 == 15) && (bnum1 == 15)) {
                bnum1 = 0;
                bnum2 = 0;
        }
if ((bnum2 < 15) && (bnum1 == 15)) {
                bnum2 += 1;
        }
}
}

if(number == 4) {
if ((rnum2 > 0) && (rnum1>0)) {
rnum2 -= 1;
}
else {
        if ((rnum2 == 0) && (rnum1 ==0) ) {
                rnum1 = 15;
                rnum2 = 15;
        }
        if ((rnum2 == 0) && (rnum1 >0) ) {
                rnum1 -= 1;
                rnum2 = 15;
        }
        if ((rnum2 > 0) && (rnum1 ==0) ) {
                rnum2 -= 1;
        }

}
}

if(number == 5) {
if ((gnum2 > 0) && (gnum1>0)) {
gnum2 -= 1;
}
else {
        if ((gnum2 == 0) && (gnum1 ==0) ) {
                gnum1 = 15;
                gnum2 = 15;
        }
        if ((gnum2 == 0) && (gnum1 >0) ) {
                gnum1 -= 1;
                gnum2 = 15;
        }
        if ((gnum2 > 0) && (gnum1 ==0) ) {
                gnum2 -= 1;
        }

}
}

if(number == 6) {
if ((bnum2 > 0) && (bnum1>0)) {
bnum2 -= 1;
}
else {
        if ((bnum2 == 0) && (bnum1 ==0) ) {
                bnum1 = 15;
                bnum2 = 15;
        }
        if ((bnum2 == 0) && (bnum1 >0) ) {
                bnum1 -= 1;
                bnum2 = 15;
        }
        if ((bnum2 > 0) && (bnum1 ==0) ) {
                bnum2 -= 1;
        }

}
}

hexNumber2 = prefix+num2hex(rnum1)+num2hex(rnum2)+num2hex(gnum1)+num2hex(gnum2)+num2hex(bnum1)+num2hex(bnum2);
if (document.layers) {
        document.layers.id2000.bgColor = hexNumber2;
        document.layers.pcjscell.bgColor = hexNumber2;
}
else if (document.all) {
        document.all.id2000.style.background = hexNumber2;
        document.all.pcjscell.style.background = hexNumber2;
}
else {
        document.getElementById('id2000').style.background = hexNumber2;
        document.getElementById('pcjscell').style.background = hexNumber2;
}
document.forms.pcjsform.rcolor.value = rnum1*16+rnum2;
document.forms.pcjsform.gcolor.value = gnum1*16+gnum2;
document.forms.pcjsform.bcolor.value = bnum1*16+bnum2;
document.forms.pcjsform.color.value = hexNumber2;
}
function saviee() {
        document.layout.todo.value='save';
        document.layout.target='_self';
}
function prew() {
        document.layout.todo.value='preview';
        document.layout.target='_blank';
        document.layout.submit();
}
function makefields() {
                document.preview.custom_footer.value=document.myhome.footer.value;
                document.preview.custom_header.value=document.myhome.header.value;
                document.preview.custom_fontcolor.value=document.myhome.custom_fontcolor.value;
                document.preview.custom_fontface.value=document.myhome.custom_fontface.value;
                document.preview.custom_table_bgcolor.value=document.myhome.custom_table_bgcolor.value;
                document.preview.custom_maintitlebgcolor.value=document.myhome.custom_maintitlebgcolor.value;
                document.preview.custom_maintitlefontcolor.value=document.myhome.custom_maintitlefontcolor.value;
                document.preview.custom_desc_fontcolor.value=document.myhome.custom_desc_fontcolor.value;
                document.preview.custom_jobs_table_bgcolor.value=document.myhome.custom_jobs_table_bgcolor.value;
                document.preview.custom_table_header_color.value=document.myhome.custom_table_header_color.value;
                document.preview.custom_search_result_color.value=document.myhome.custom_search_result_color.value;
        return true;
}

function makecolor(myinput,tbl_id) {
                if ((isValidColor(myinput.value)==1) || (myinput.value.length<7)) {
                        alert("Invalid color " + myinput.value);
                        return false;
        }
                if (document.layers) {
                        document.layers[tbl_id].bgColor=myinput.value;
                }
                else if (document.all) {
                        document.all[tbl_id].style.background=myinput.value;
                }
                else {
                        document.getElementById(tbl_id).style.background=myinput.value;
                }
                return true;
}
function isValidColor(str)
{
// Return false if characters are not '0-9' or '.' .
for (var i = 0; i < str.length; i++)
{
var ch = str.substring(i, i + 1);
if ((ch < "0" || "9" < ch) && (ch < "a" || "f" < ch) &&  (ch < "A" || "F" < ch) && ch != '#')
        {
        return 1;
        }
}
return 0;
}