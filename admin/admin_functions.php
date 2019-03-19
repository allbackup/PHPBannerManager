<?php
/**
 * Stop running the current processed file...but first close the tables, body, html... 
 *
 * Detail description
 * @param      none
 * @global     none
 * @since      1.0
 * @access     private
 * @return     void
 * @update     date time
*/
function exit_footer()
{
    echo "      </td>\n";
echo "     </tr>\n";
echo "  </table>\n";
echo " </td>\n";
echo " <!-- end body_navigation //-->\n";
echo " </tr>\n";
echo " </table>\n";
echo "<table border=\"0\" width=\"".HTML_WIDTH."\" cellspacing=\"0\" cellpadding=\"0\" align=\"center\">\n";
echo " <tr>\n";
echo " <td width=\"100%\">\n";
include (FOOTER);
echo " </td>\n";
echo " </tr>\n";
echo "</table>\n";
echo "</body>\n";
echo "</html>\n";
bx_exit();
} // end func exit_footer()

function bx_admin_error($message) {
	     global $error_title;
         echo "<table width=\"100%\" cellspacing=\"0\" cellpadding=\"2\" class=\"tedit\">";
         echo "<tr>";
         echo "     <td align=\"center\"><b>Error !!!</b></td>";
         echo " </tr>";
         echo " <tr>";
         echo "   <td>";
         echo "<TABLE border=\"0\" cellpadding=\"4\" cellspacing=\"0\" width=\"100%\" class=\"edit\">";
         echo "        <td colspan=\"2\" align=\"center\"><font class=\"error\"><h1>Errors occurred when \"$error_title\"</h1></td>";
         echo "</tr>";
         echo "<tr>";
         echo "        <td colspan=\"2\"><b>$message</b></td>";
         echo "</tr>";
         echo "<tr>";
         echo "        <td colspan=\"2\" align=\"center\"><br><input type=\"button\" name=\"back\" value=\"Back\" onClick=\"history.back();\"></td>";
         echo "</tr>";
         echo "</table>";
         echo "</td></tr></table>";
		 exit_footer();
}//end function bx_admin_error
?>