<?php

/* * **********************************************************
 * Copyright 2012 (C) Xeno Innovations, Inc.
 * ALL RIGHTS RESERVED
 * Author:       Damian J. Suess
 * Document:     pmt.user.php
 * Created Date: Feb 21, 2012
 * 
 * Description:
 *  Class to handle user information  
 *
 * To Do:
 *  [ ] Constructor
 *  [ ] Login
 *  [ ] Logoff
 *  [ ] NewUser
 *  [ ] GetInfo
 *  [ ] GetUsers
 * 
 * Change Log:
 * 
 */

class User {
  public $username = "Guest";
  public $userid = "0";
  public $fullname = "";
  public $password = "";
  
  public $group = null;
  public $online = false;
    
  /**
   * Consider using array
   
  public $userinfo = array(
      "id"        => "",
      "username"  => "Guest",
      "fullname"  => "",
      "groupid"   => ""
  );
  */
  
  public function __construct()
  {
    /**
     * 1. Check if user cookie is set
     * 2. Set user group
     */
    global $db;
    
  }
  
  public function __destruct() {
    // Probably not going to be used
  }
  
  /**
   * Login on the user account
   * @param string $user User Name
   * @param string $pass Password
   * @param boolean $stayOnline 
   * @return boolean Success (true) Failure (false)
   */
  public function Login($user, $pass, $stayOnline)
  {
    
  }
  
  /**
   * Log off the user 
   */
  public function Logoff()
  {
   /**
    * 1. Erase cookies
    * 2. Set class vars back to nothing 
    */ 
  }
  
  /**
   * Create new user
   * @param array $arrUserInfo User Information (username, password, email, fullname, etc.)
   */
  public function NewUser($arrUserInfo)
  {
    $arrErr = array();

  }
  
  public function GetInfo($userId)
  {
    global $db;
    
  }
  
  public function ListUsers()
  {
    
  }
  
}

?>
