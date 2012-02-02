<?php
/************************************************************
 * Copyright 2010 (C) Xeno Innovations, Inc.
 * ALL RIGHTS RESERVED
 *
 * Author:
 * Damian J. Suess
 *
 * Description:
 * This file is used to authenticate users in the system.
 * 
 * Usages:
 * login.php
 *
 * Change Log:
 * [2010-30] - Initial Creation
 */


/**********************
 * WebAuth
 * ---------
 * This is used to authenticate users into the system and
 * log them out.
 *
 */

class WebAuth
{

  public $varTest = "test";
  public function testprint()
  {
    print $this->var;
  }


  public function Authenticate()
  {

  }

  public function isCookieEnabled()
  {
    // Check the project database if we are using cookies
    return true;
  }

}


?>
