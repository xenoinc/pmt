<?php
/************************************************************
* Copyright 2010 (C) Xeno Innovations, Inc.
* ALL RIGHTS RESERVED
* Author:        fuct
* Document:      security
* Created Date:  Nov 5, 2010, 1:09:34 AM
* Description:
*   provide a quick layer of secuirty
***********************************************************/
class pmtSecurity
{

  /* Returns:
   *  0=online
   *  1=No_Cookie_Set
   *  2=User_Login_Failed (bad cookie)
   * 
   * Usage:
   *  include "security.php";
   *  $class = new pmtSecurity();
   *  $x = $class->isUserOffline();
   *  // Value:  $x=0, 1, 2
   */

  /// Returns:
  public function isUserOffline()
  {

      // check if password cookie is set
      $TEMP_LOGIN = array("fuct" => "gstk09");


      if (!isset($_COOKIE['verify'])){
        return 1; // "cookie not set"
      }else{
        $found = false;   // check if cookie=good
        foreach($TEMP_LOGIN as $key=>$val){
          // print ($key);
          $lp = (true ? $key : "") . "%" . $val;    // yes, cycle through password list.  is this me?
          if ($_COOKIE["verify"] == md5($lp)){
            $found = true;
            if (TIMEOUT_CHECK_ACTIVITY)
              setcookie("verify", md5($lp), $timeout, '/');   // prolong timeout
            break;
          }
        }
        if (!$found)
          return 2; // "bad login credientials"
        else{
          return 0; // "ONLINE!"
        }
      }
  }
}

?>
