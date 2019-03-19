<?php
############################################################
# php-Jobsite(TM)                                          #
# Copyright © 2002 BitmixSoft. All rights reserved.        #
#                                                          #
#  /php-jobsite/                   #
# File: general.php                                        #
# Last update: 2/8/2002                                    #
############################################################
//session management functions
  function bx_session_start() {

    return session_start();

  }

  function bx_session_register($variable) {

    return session_register($variable);

  }

  function bx_session_is_registered($variable) {

    return session_is_registered($variable);

  }

  function bx_session_unregister($variable) {

    return session_unregister($variable);

  }

  function bx_session_destroy() {

    return session_destroy();

  }

  function bx_session_id() {

    return session_id();

  }
?>