<?php
$pass_query = @bx_db_query("select * from phpjob_ladmin");
if(bx_db_num_rows($pass_query)>0) {
    $pass_result = bx_db_fetch_array($pass_query);
    if($HTTP_GET_VARS['adm_user']) {
        $HTTP_POST_VARS['adm_user'] = $HTTP_GET_VARS['adm_user'];
    }
    if($HTTP_GET_VARS['adm_pass']) {
        $HTTP_POST_VARS['adm_pass'] = $HTTP_GET_VARS['adm_pass'];
    }
    if ( ((md5($HTTP_POST_VARS['adm_user']."Php-Jobsite admin authorization") != $pass_result['admin']) && (md5($HTTP_SESSION_VARS['adm_user']."Php-Jobsite admin authorization") != $pass_result['admin'])) || ((md5($HTTP_POST_VARS['adm_pass']."Php-Jobsite admin authorization") != $pass_result['passw']) && (md5($HTTP_SESSION_VARS['adm_pass']."Php-Jobsite admin authorization") != $pass_result['passw'])) ) {
        ?>
            <!DOCTYPE HTML PUBLIC "-//IETF//DTD HTML 2.0//EN">
            <HTML><HEAD>
            <TITLE>401 Authorization Required</TITLE>
            </HEAD><BODY>
                              <center>
            <H1>Authorization Required</H1>
                              <br><br>
                              <form method="post" action="index.php">
                              <input type="hidden" name="todo" value="login">
                              <table width="60%" align="center" border="0">
                                    <?php if($HTTP_POST_VARS['adm_user'] || $HTTP_POST_VARS['adm_pass']){?>
                                    <tr><td align="center" colspan="2" nowrap><b><font color="#FF0000">Invalid Username or password. Please try again!</font></b></td></tr>
                                    <?php }?>
                                    <tr><td align="right" width="40%"><B>Username :</B></td><td align="left" width="60%"><input type="text" name="adm_user" size="20" value="<?php echo $HTTP_POST_VARS['adm_user'];?>"></td></tr>
                                    <tr><td align="right" width="40%"><B>Password :</B></td><td align="left" width="60%"><input type="password" name="adm_pass" size="20" value=""></td></tr>
                                    <tr><td colspan="2" align="center"><input type="submit"  name="Go" value="Enter"></td></tr>
                              </table>
                              </form>
                              </center>
            <HR>
            </BODY></HTML>
            <?php
            bx_exit();
    }
    if($HTTP_POST_VARS['todo'] == "login") {
                $adm_user = $HTTP_POST_VARS['adm_user'];
                session_register("adm_user");
                $adm_pass = $HTTP_POST_VARS['adm_pass'];
                session_register("adm_pass");
                $adm_ip = $pass_result['ipaddress'];
                session_register("adm_ip");
                bx_db_query("UPDATE phpjob_ladmin set ipaddress='".$HTTP_SERVER_VARS['REMOTE_ADDR']."'");
    }
    else {
        
    }
}
?>