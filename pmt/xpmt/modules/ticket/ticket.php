<?php

/** * *********************************************************
 * Copyright 2012 (C) Xeno Innovations, Inc.
 * ALL RIGHTS RESERVED
 * @Author:      Damian Suess
 * Document:     ticket
 * Created Date: Sep 6, 2012
 *
 * Description:
 *  Ticket viwer
 *
 * Change Log:
 *
 */

class ticket implements iModule
{

  private $_title;
  private $_toolbar;
  private $_minileft;
  private $_miniright;
  private $_pagedata;
  // Internal module setup
  private $_MODE;
  private $_PAGE;
  private $_SWITCH;     // Switch: new, edit,

  function __construct()
  {
    $this->ParseData();

    $this->_title = "";
    $this->_toolbar = "";

    $this->_minileft = $this->GenerateMiniLeft();
    $this->_miniright = $this->GenerateMiniRight();
    $this->_pagedata = "";

  }

  public function Title() {         return $this->_title; }       /* Title of the generated page */
  public function Toolbar() {       return $this->_toolbar; }     /* Toolbar - HTML generated toolbar according to location */
  public function MiniBarLeft() {   return $this->_minileft; }
  public function MiniBarRight() {  return $this->_miniright; }
  public function PageData() {      return $this->_pagedata; }

  /* -=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=- */


  /**
   * find out where we're going before displaying anything
   */
  private function ParseData()
  {
    global $uri;


    // $cmd = $this->GetCmd();
    //$mode = ENUM_TicketMode
    $tId = null;

    /* To Do:
     * 1) Get user access
     *    i.  Get user group
     *    ii. does group have permission (pmt_s_
     *
     */

    if ($this->UserAllowed())
    {

    }

  }

  private function UserAllowed()
  {
    global $user;
    global $pmtConf;

    /*
     * OFFLINE
     * Member Object (
     *  [Online] =>                     * DEFAULT: NULL
     *  [errors] => Array ( )           * DEFAULT: Empty Array
     *  [userInfo] => Array (
     *    [User_Id] => 0                * DEFAULT: 0
     *    [User_Name] => Guest          * DEFAULT: "Guest"
     *    [Display_Name] => Anonymous   * DEFAULT: "Anonymous"
     *    [Group_Id] => 0               * DEFAULT: 0
     *    [Online] => )                 * DEFAULT: NULL
     * )
     *
     * User logged in (ADMIN)
     * Member Object (
     *  [Online] => 1                   * ONLINE: 1
     *  [errors] => Array ( )
     *  [userInfo] => Array (
     *    [User_Id] => 1            * Integer - (id > 0) - User_Id number
     *    [User_Name] => admin      * String  - User login name
     *    [Display_Name] => admin   * String  - User display name (publically displayed)
     *    [Group_Id] => 1           * Integer - (id > 0) - Group_Id number
     *    [online] => 1 )
     *  )
     *
     */

    print_r($user);

    $ret = false;

    if ($user->UserInfo["User_Id"] < 0 || $user->UserInfo["Online"] == false)
    {

      $dbPrefix = $pmtConf["db"]["prefix"];
      $groupId = $user->UserInfo["Group_Id"];   // User Group Id


      // Look up the user group and see if it has access to perform
      // the requested action
      $q = <<<QUERY
 SELECT * FROM {$dbPrefix}USER_GROUP WHERE `Group_Id` = {$groupId} LIMIT 1;
QUERY;

      //$data = $pmtDB->FetchArray($q);


    }


    return $ret;

  }



  private function GenerateMiniLeft()
  {
    /*
    global $user;
    if ($user->Online != false)
    {
      $code =   "<ul>";
      $code .=  "<li>". $this->AddLink(self::MODULE, "Main", "")  ."</li>";
      $code .=  "<li class='last'>". $this->AddLink(self::MODULE, "List Articles", "?cmd=".self::cLIST)  ."</li>";
      $code .=  "</ul>";
      return $code;
    }
    else
    */
    return "";
  }

  private function GenerateMiniRight()
  {
    /*
    global $user;
    if ($user->Online != false)
    {
      $code =   "<ul>";
      $code .=  "<li>". $this->AddLink(self::MODULE, "Main", "")  ."</li>";
      $code .=  "<li class='last'>". $this->AddLink(self::MODULE, "List Articles", "?cmd=".self::cLIST)  ."</li>";
      $code .=  "</ul>";
      return $code;
    }
    else
    */
    return "";
  }

}

?>
