<?php

/* * **********************************************************
 * Copyright 2012 (C) Xeno Innovations, Inc.
 * ALL RIGHTS RESERVED
 * Author:       Damian J. Suess
 * Document:     user.php
 * Created Date: Mar 27, 2012
 *
 * Description:
 *  User module to handle creating, editing and mods to
 *  users and groups.
 *
 *  ** See Engineering document for more info
 *
 * Change Log:
 *  2012-0328 * fixed "makeLink" to include $pmtConf
 */

require ("pmtModule.php");
class user implements pmtModule
{
  //const MODULE = "user";
  private $MODULE = "user";


  private $_title;      // Title of the screen
  private $_toolbar;    // HTML generated toolbar according to location
  private $_minileft;   // mini toolbar (left)
  private $_miniright;  // mini toolbar (right)

  //function __construct($uriPath = "")
  function __construct()
  {
    $this->_title = "User " . " - " . "[PMT]";    // "Xeno Tracking System"
    $this->_toolbar = $this->GenerateToolbar();
    $this->_minileft = "";
    $this->_miniright = "";
  }

  public function Title() { return $this->_title; }             /* Title of the generated page */
  public function Toolbar() { return $this->_toolbar; }         /* Toolbar - HTML generated toolbar according to location */
  public function MiniBarLeft() { return $this->_minileft; }
  public function MiniBarRight() { return $this->_miniright; }

  public function PageData()
  {
    /**
     * Depending on usr permissions settings, list all projects available to
     * the user logged in.
     */
    /*
     "pmt/user?cmd=create"
    if ($_GET["cmd"] == "create")
      pmtDebug("yes");
    else
      pmtDebug("no");
    */

    $sample =  "<h1<b><i>xeno</i>PMT</b> - User Setup</h1>";
    $sample .= "<p>This system is still under heavy development and is not ";
    $sample .= "ready for live action use by any means. Soon enough you will ";
    $sample .= "get to see what the future holds.  As the project develops the ";
    $sample .= "user and engineering documentation will mature along with it.</p>";
    $sample .= "<p>Sit tight and enjoy the ride!</p>";
    $sample .= "<p>&nbsp;</p>";
    $sample .= "<p>- Xeno Innovations, Inc. -</p>";

    return $sample;
  }



  /* **************************** */
  /* **************************** */


  private function GenerateToolbar()
  {
    /* Steps:
    * 1) Get user profile permissions to see what
    *    items we can draw on the screen.
    * 2) Generate toolbar
    */

    /* Step 1 - Get user permissions */


    /* Step 2 - Generate Toolbar */

    // List of all the available modules
    // ** This should be pulled from DB depending on user/group
    //    permissions & settings!!
    $arrAvailMods = array(
          // Module       Display
          "dashboard" => "Dashboard",
          "project"   => "Projects",
          "ticket"    => "Tickets",     /* "ticket" => array ("Tickets", "+"), */
          "bugs"      => "Bugs",
          "tasks"     => "Tasks",
          "product"   => "Products",
          "customer"  => "Customers",
          "user"      => "Users",
          "admin"     => "Admin"
          );

    $tab = "        ";
    $ret = $tab . "<ul>". PHP_EOL;
    $ndxCount = 0;
    //print (count($a));
    foreach($arrAvailMods as $key => $value)
    { //print ("key: $key, Obj: $value <br />");

      $ndxCount++;
      if ($ndxCount == 1)
        $cls = ' class="first"';
      elseif($ndxCount == count($arrAvailMods))
        $cls = ' class="last"';
      else
        if ($key==$this->MODULE)$cls = ' class="active"'; else $cls = '';

      $ret .= $tab .
              "  <li" . $cls. ">" .
              $this->makeLink($key, $value) .
              "</li>" . PHP_EOL;

    }
    $ret .= $tab . "</ul>". PHP_EOL;
    //pmtDebug("disp: " . $ret);
    return $ret;
  }


  /**
   * Make link
   * @param type $module
   * @param type $text
   * @return type
   */
  private function makeLink($module, $text)
  {
    global $pmtConf;
    return '<a href="'. $pmtConf["general"]["base_url"] . $module.'">'.$text.'</a>';
  }




  /*
  private function GenerateToolbar_default()
  {
    // Steps:
    // 1) Get user profile permissions to see what
    //    items we can draw on the screen.
    // 2) Generate toolbar
    //

    // Step 1 - Get user permissions


    // Step 2 - Generate Toolbar

    // List of all the available modules
    // ** This should be pulled from DB depending on user/group
    //    permissions & settings!!
    $arrAvailMods = array(
          // Module       Display
          "dashboard" => "Dashboard",
          "project"   => "Projects",
          "ticket"    => "Tickets",
          "bugs"      => "Bugs",
          "tasks"     => "Tasks",
          "product"   => "Products",
          "customer"  => "Customers",
          "user"      => "Users",
          "admin"     => "Admin"
          );

    $tab = "        ";
    $ret = $tab . "<ul>". PHP_EOL;
    $ndxCount = 0;
    //print (count($a));
    foreach($arrAvailMods as $key => $value)
    { //print ("key: $key, Obj: $value <br />");

      $ndxCount++;
      if ($ndxCount == 1)
        $cls = ' class="first"';
      elseif($ndxCount == count($arrAvailMods))
        $cls = ' class="last"';
      else
        if ($key=="project")$cls = ' class="active"'; else $cls = '';

      $ret .= $tab .
              "  <li" . $cls. ">" .
              $this->makeLink($key, $value) .
              "</li>" . PHP_EOL;

    }
    $ret .= $tab . "</ul>". PHP_EOL;
    //pmtDebug("disp: " . $ret);
    return $ret;
  }
  */


}

?>
