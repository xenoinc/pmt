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
 *  [ ] Replace PMT_TBL with $xpmtConf["db"]["prefix"] (2014-0129)
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

// namespace xenoPMT\Core {
class Member
{

  // -=-[ Properties ]-=-=-=-=-=-=-=-=-=-=-=-=-=-=-
  //
  // [2012-0908]-(djs)
  // Old method was to use these properties so that they would be easily
  // accessable when typing out the class variable "$user->..". However it
  // may be best to contain propertity values inside of an array and keep the
  // functions out in the open?
  //
  //public $username = "Guest";
  //public $userid = "0";
  //public $fullname = "";
  //public $password = "";
  //public $group = null;


  public $Online = false;       // 2012-1203 + Make property private and use memeber
  public $errors = array();


  // 2012-1203  * make property private and use member, ->Info("User_Id")
  /* Array (
   * [User_Id]    => 1
   * [User_Name]  => admin
   * [Name]       => Test Administrator
   * [Group_Id]   => 1
   * [Online]     => 1 )
   *
   */
  // Usage:
  // $user->UserInfo["User_Name"]
  public $UserInfo = array(
      "User_Id"       => "0",
      "User_Name"     => "Guest",
      "Display_Name"  => "Anonymous",
      "Group_Id"      => "0",             // Anon should be setup as Group "2"
      //"Group_Name"    => null,
      "Online"        => false
  );

  // Added 2012-0908
  // List of all groups this user is apart of
  public $GroupInfo = array(
      "Group_Id"      => 0,
      "Group_Name"    => null
  );

  // -=-[ Members ]-=-=-=-=-=-=-=-=-=-=-=-=-=-=-

  public function __construct()
  {
    /**
     * 1. Check if user cookie is set
     * 2. Set user group
     */
    global $pmtDB;

    if ($pmtDB == null)
    {
      //pmtDebug("pmt.member.php : failed to load pmtDB");
      return;
    }

    if(!isset($_COOKIE["xenopmt_user"])) $_COOKIE["xenopmt_user"] = "";
    if(!isset($_COOKIE["xenopmt_hash"])) $_COOKIE["xenopmt_hash"] = "";


    $t__tbl = PMT_TBL;                                            // TODO: consider using $xpmtConf["db"]["prefix"] (2014-0129)
    $t__usr = $pmtDB->FixString($_COOKIE['xenopmt_user']);
    $t__pas = $pmtDB->FixString($_COOKIE['xenopmt_hash']);
    //pmtDebug("usr: " . $__usr);


    $tmp = <<<QUERY
  SELECT u.User_Id, u.User_Name, u.Display_Name, g.Group_Id
  FROM {$t__tbl}USER u
    JOIN {$t__tbl}USER_GROUP ug
      ON u.User_Id = ug.User_Id
    JOIN {$t__tbl}GROUP g
      ON g.Group_Id = ug.Group_Id
  WHERE
    u.User_Name='{$t__usr}' and Session_Hash='{$t__pas}'
  LIMIT 1;
QUERY;

    /*
    $tmp =
        "SELECT User_Id, User_Name, Display_Name, Group_Id FROM ".PMT_TBL."USER WHERE " .
        //"User_Name='" . $pmtDB->es($_COOKIE['xenopmt_user']) . "' AND " .
        //"Session_Hash='" . $pmtDB->es($_COOKIE['xenopmt_hash']) . "' LIMIT 1;";
        "User_Name='" . $pmtDB->FixString($_COOKIE['xenopmt_user']) . "' AND " .
        "Session_Hash='" . $pmtDB->FixString($_COOKIE['xenopmt_hash']) . "' LIMIT 1;";
    */
    $q = $pmtDB->Query($tmp);
    if ($q == null )
    {
      pmtDebug("query was null");
    }
    else
    {
      $ret33 =$pmtDB->NumRows($q);
      // pmtDebug("ret: " . $ret33);
      if($ret33)
      {
        // We're logged in still
        $this->UserInfo = $pmtDB->FetchArray($q);
        $this->UserInfo["Online"] = true;         // use this for now on
        $this->Online = true;                     // Kept for legacy purposes

        /**
         * Old way [pre 2012-0908]

          $grp = $this->UserInfo["Group_Id"];
          $tmp =
              "SELECT * FROM ".PMT_TBL."USER_GROUP WHERE " .
              "Group_Id='" . $grp . "' LIMIT 1";
          $this->group = $pmtDB->QueryFirst($tmp);
        */

        // Version 1 ]======================================
        /*
        $gid = $this->UserInfo["Group_Id"];
        $tmp =
            "SELECT `Group_Id`, `Group_Name` FROM ".PMT_TBL."GROUP WHERE " .
            "Group_Id=" . $gid . " LIMIT 1";

        try
        {
          $q = $pmtDB->Query($tmp);
          if($pmtDB->NumRows($q))
            $this->GroupInfo = $pmtDB->FetchArray($q);
        } catch (Exception $e) {
          pmtDebug("Exception:' $e'");
        }
        */

        // VERSION 2 (USE THIS) ]======================================
        // First, mod TBL_USER to work in the absence of TBL_USER.Group_Id
        // and to rely on TBL_User_Group.Group_Id

        $dbPrefix = PMT_TBL;                                // TODO: consider using $xpmtConf["db"]["prefix"] (2014-0129)
        $uid = $this->UserInfo["User_Id"];
        $tmp = <<<"EOT"
  SELECT `g`.`Group_Id`, `g`.`Group_Name`
  FROM {$dbPrefix}USER_GROUP ug
    join {$dbPrefix}GROUP g
    on ug.Group_Id = g.Group_Id
  WHERE
    ug.User_Id = {$uid};
EOT;
        try{
          $q = $pmtDB->Query($tmp);
          if($pmtDB->NumRows($q))
            $this->GroupInfo = $pmtDB->FetchArray($q);
        } catch (Exception $e) {
          pmtDebug("Exception:' $e'");
        }
      }
    }

      // -=-=-=-=-=-=-=-=-=-=-=-=

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

    $q =  "SELECT * FROM ".PMT_TBL."USER ".                               // TODO: consider using $xpmtConf["db"]["prefix"] (2014-0129)
          "WHERE User_Name='".$pmtDB->FixString($user)."' AND ".
          "Password='".sha1($pmtDB->FixString($pass))."' LIMIT 1;";
    $login = $pmtDB->Query($q);
    if($pmtDB->NumRows($login))
    {
      $q =  "UPDATE ".PMT_TBL."USER ".                                    // TODO: consider using $xpmtConf["db"]["prefix"] (2014-0129)
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

    $q =  "SELECT * FROM ".PMT_TBL."USER ".                           // TODO: consider using $xpmtConf["db"]["prefix"] (2014-0129)
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
      "SELECT User_Name FROM ".PMT_TBL."USER WHERE " .                    // TODO: consider using $xpmtConf["db"]["prefix"] (2014-0129)
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
    $pmtDB->Query("INSERT INTO ".PMT_TBL."USER ($fields) VALUES($values);");   // TODO: consider using $xpmtConf["db"]["prefix"] (2014-0129)

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
          "User_Id='".$pmtDB->FixString($userId)."' LIMIT 1;";
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
    $q= "SELECT User_Id, User_Name, FROM ".PMT_TBL."USER ORDER BY User_Name ASC;";      // TODO: consider using $xpmtConf["db"]["prefix"] (2014-0129)
    $ret = $pmtDB->Query($q);
    while($nfo = $pmtDB->FetchArray($ret))
      $arrUsers[] = $nfo;

    return $arrUsers;
  }

  /**
   * Return user information
   *
   * @param string $property
   *  + "User_Id"
   *  + "User_Name"
   *  + "Display_Name"
   *  + "Group_Id"
   *  + "Online"
   *
   * @return string
   *  Returns user information from property
   */
  public function Info($property)
  {
    return $this->UserInfo[$property];
  }

  /*
   * currently unused. Make GA by the end of 0.0.5
  public function Online()
  {
    return $this->_online;
  }
   *
   */

}

?>
