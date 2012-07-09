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
 *  * Password Hash = sha1($user.$pass.time())
 *
 * Cookies:
 *  setcookie('xenopmt_user','',0,'/');
 *  setcookie('xenopmt_hash','',0,'/');
 *  setcookie('xenopmt_remember',0,0,'/');
 *
 * To Do:
 *  [/] Constructor - UNTESTED
 *  [X] Login       - UNTESTED
 *  [X] Logoff      - UNTESTED
 *  [/] NewUser     - UNTESTED
 *  [X] GetInfo     - UNTESTED
 *  [X] GetUsers    - UNTESTED
 *  ------------------
 *  [ ] Finish Group Loader in constructor
 *  [ ] Enable SystemHook
 *
 * Change Log:
 *  2012-0603 * Updated usage of table _USER column `Name` to `Display_Name`
 *  2012-0402 * Changed $userInfo[] key to match the database
 *              because the array's keys get overwritten when loaded by DB.
 *              And when logged off, OG keys return.. so we match the names.
 *  2012-0328 + Updated Login code
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

  /* Array (
   * [0] => 1
   * [User_Id] => 1
   * [1] => admin
   * [User_Name] => admin
   * [2] => Test Administrator
   * [Name] => Test Administrator
   * [3] => 1
   * [Group_Id] => 1
   * [online] => 1 )
   *
   */
  // Usage:
  // $user->userInfo["User_Name"]
  public $userInfo = array(
      "User_Id"   => "0",
      "User_Name" => "Guest",
      "Display_Name" => "Anonymous",
      "Group_Id"  => "0",         // Anon should be setup as Group "2"
      "Online"    => false
  );


  public function __construct()
  {
    /**
     * 1. Check if user cookie is set
     * 2. Set user group
     */
    global $pmtDB;

    if(!isset($_COOKIE["xenopmt_user"])) $_COOKIE["xenopmt_user"] = "";
    if(!isset($_COOKIE["xenopmt_hash"])) $_COOKIE["xenopmt_hash"] = "";

    $tmp =
        "SELECT User_Id, User_Name, Display_Name, Group_Id FROM ".PMT_TBL."USER WHERE " .
        "User_Name='" . $pmtDB->es($_COOKIE['xenopmt_user']) . "' AND " .
        "Session_Hash='" . $pmtDB->es($_COOKIE['xenopmt_hash']) . "' LIMIT 1;";
    $q = $pmtDB->Query($tmp);
    if($pmtDB->NumRows($q))
    {
      // We're logged in still
      $this->userInfo = $pmtDB->FetchArray($q);
      $this->userInfo["online"] = true;
      $this->online = true;
    }

    // TOD: Finish Group setup
    // Get user Group Info (Anon or Logged in)
    //print_r($this->userInfo);

    $grp = $this->userInfo["Group_Id"];
    $tmp =
        "SELECT * FROM ".PMT_TBL."USER_GROUP WHERE " .
        "Group_Id='" . $grp . "' LIMIT 1";
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
    global $pmtDB;

    $q =  "SELECT * FROM ".PMT_TBL."USER ".
          "WHERE User_Name='".$pmtDB->FixString($user)."' AND ".
          "Password='".sha1($pmtDB->FixString($pass))."' LIMIT 1;";
    $login = $pmtDB->Query($q);
    if($pmtDB->NumRows($login))
    {
      $q =  "UPDATE ".PMT_TBL."USER ".
            "SET Session_Hash='".$pmtDB->FixString(sha1($user.$pass.time()))."' ".
            "WHERE User_Name='".$pmtDB->FixString($user)."' LIMIT 1;";
      $pmtDB->Query($q);

      if ($stayOnline)
      {
        // Remember User
        setcookie('xenopmt_user',     $user,                    time()+9999999, '/');
        setcookie('xenopmt_hash',     sha1($user.$pass.time()), time()+9999999, '/');
        setcookie('xenopmt_remember', 1,                        time()+9999999, '/');
      }
      else
      {
        // Session Only
        setcookie('xenopmt_user',     $user, 0, '/');
        setcookie('xenopmt_hash',     sha1($user.$pass.time()), 0, '/');
        setcookie('xenopmt_remember', 0, 0, '/');
      }

      //($hook = SystemHook::Hook("user_login_success")) ? eval($hook) : false;
      return true;
    }
    else
    {
      unset($this->errors);
      $this->errors[] = Locale("error_invalid_login");
      //($hook = SystemHook::Hook("user_login_error")) ? eval($hook) : false;
      return false;
    }
  }

  /**
   * Validate if the login credientials are valid or not
   * @global Database $pmtDB
   * @param string $user
   * @param string $pass
   * @return boolean
   */
  public function Validate($user, $pass)
  {
    global $pmtDB;

    $q =  "SELECT * FROM ".PMT_TBL."USER ".
          "WHERE User_Name='".$pmtDB->FixString($user)."' AND ".
          "Password='".sha1($pmtDB->FixString($pass))."' LIMIT 1;";
    $login = $pmtDB->Query($q);
    if($pmtDB->NumRows($login))
      return true;
    else
      return false;
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

    setcookie('xenopmt_user','',0,'/');
		setcookie('xenopmt_hash','',0,'/');
		setcookie('xenopmt_remember',0,0,'/');
    // ($hook = SystemHook::Hook("user_logout")) ? eval($hook) : false;
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
      "User_Name='".$pmtDB->FixString($nfo['username'])."' LIMIT 1;";
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
    $pmtDB->Query("INSERT INTO ".PMT_TBL."USER ($fields) VALUES($values);");

    return true;
  }

  /**
   * Get User Information
   * @global Database $pmtDB
   * @param int $userId
   */
  //public function GetInfo($userId)
  public function GetInfo($userId)
  {
    global $pmtDB;

    $q =  "SELECT * FROM ".PMT_TBL."USER WHERE ".
          "User_Id='".$pmtDB->Res($userId)."' LIMIT 1;";
    return $pmtDB->QueryFirst($q);

  }

  /**
   * Get list of all users
   * @global Database $pmtDB
   * @return array (User_Id, User_Name)
   */
  public function ListUsers()
  {
    global $pmtDB;
    $arrUsers = array();
    $q= "SELECT User_Id, User_Name, FROM ".PMT_TBL."USER ORDER BY User_Name ASC;";
    $ret = $pmtDB->Query($q);
    while($nfo = $pmtDB->FetchArray($ret))
      $arrUsers[] = $nfo;

    return $arrUsers;
  }

}

?>
