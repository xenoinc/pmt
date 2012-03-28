<?php

/* * **********************************************************
 * pmTrack (xiPMT, xiPMTrack)
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
 *  [/] Constructor
 *  [ ] Login
 *  [X] Logoff
 *  [ ] NewUser
 *  [ ] GetInfo
 *  [ ] GetUsers
 *  ------------------
 *  [ ] Finish Group Loader in constructor
 *
 * Change Log:
 *  2012-0309 + Added code to constructor
 */

class Member {
//class User {
  public $username = "Guest";
  public $userid = "0";
  public $fullname = "";
  public $password = "";

  public $group = null;
  public $online = false;
  public $errors = array();

  public $userInfo = array(
      "id"        => "0",
      "username"  => "Guest",
      "fullname"  => "Anonymous",
      "groupid"   => "0"          // Anon should be setup as Group "2"
  );


  public function __construct()
  {
    /**
     * 1. Check if user cookie is set
     * 2. Set user group
     */
    global $pmtDB;

    if(!isset($_COOKIE["xipmt_user"])) $_COOKIE["xipmt_user"] = "";
    if(!isset($_COOKIE["xipmt_hash"])) $_COOKIE["xipmt_hash"] = "";

    $tmp =
        "SELECT User_Id, User_Name, Name, Group_Id FROM ".PMT_TBL."USER WHERE " .
        "User_Name='" . $pmtDB->es($_COOKIE['xipmt_user']) . "' AND " .
        "Session_Hash='" . $pmtDB->es($_COOKIE['xipmt_hash']) . "' LIMIT 1;";
    $q = $pmtDB->Query($tmp);
    if($pmtDB->NumRows($q))
    {
      // We're logged in still
      $this->userInfo = $pmtDB->FetchArray(query);
      $this->online = true;
    }

    // TOD: Finish Group setup
    // Get user Group Info (Anon or Logged in)
    $tmp =
        "SELECT * FROM ".PMT_TBL."USER_GROUP WHERE " .
        "Group_Id='" . $this->userInfo['groupid'] . "' LIMIT 1";
    $this->group = $pmtDB->QueryFirst($tmp);

    // TODO: Setup user SystemHook
    //($hook = SystemHook::Hook("User_Construction")) ? eval($hook) : false;

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

    setcookie('xipmt_user','',0,'/');
		setcookie('xipmt_hash','',0,'/');
		setcookie('xipmt_remember',0,0,'/');
    // ($hook = SystemHook::Hook('user_logout')) ? eval($hook) : false;
  }

  /**
   * Create new user
   * @param array $nfo User Information (username, password, email, fullname, etc.)
   */
  public function NewUser($nfo)
  {
    $arrErr = array();
    global $pmtDB;

    // Test for errors
    $q =
      "SELECT User_Name FROM ".PMT_TBL."USER WHERE " .
      "User_Name='".$pmtDB->EscapeString($nfo['username'])."' LIMIT 1;";
    if($pmtDB->NumRows($pmtDB->Query($q)))
      $arrErr["Username"] = Locale("error_username_taken");

    if(empty($nfo["password"]))                 $arrErr["password"]   = "password empty";
    if($nfo["password"] != $nfo["password2"])   $arrErr["password2"]  = "password doesn't match";
    if(empty($nfo["email"]))                    $arrErr["email"]      = "email empty";
    if(empty($nfo["name"]))                     $arrErr["name"]       = "name empty";

    // There were errors
    if(count($arrErr) > 0)
    {
      $this->errors = $arrErr;
      return false;
    }

    // No Errors, create account
    $colmns = array();
    $values = array();
    foreach($nfo as $i => $x)
    {
      $colmns[] = $i;
      $values[] = "'" . $x . "'";
    }
    $colmns = implode(",", $colmns);
    $values = implode(",", $values);

    // TODO: REMOVE THIS TEST USER!!
    //$colm = "User_Name, Password, Email, Name";
    //$vals = "'admin', 'admin', 'test@email.com', 'Test User'";
    $pmtDB->query("INSERT INTO ".PMT_TBL."USER ($fields) VALUES($values)");

    return true;
  }

  public function GetInfo($userId)
  {
    global $pmtDB;

  }

  public function ListUsers()
  {

  }

}

?>
