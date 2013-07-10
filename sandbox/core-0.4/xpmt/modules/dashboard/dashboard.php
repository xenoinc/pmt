<?php

/* * **********************************************************
 * Copyright 2012 (C) Xeno Innovations, Inc.
 * ALL RIGHTS RESERVED
 * Author:       Damian Suess
 * Document:     dashboard.php
 * Created Date: Mar 22, 2012
 *
 * Description:
 *
 *
 * Change Log:
 *  2012-0402 - Bypassed GenerateToolbar(). Return "" and use default.
 *            * Moved PageData() to $_pagedata();
 *  2012-0328 * fixed "makeLink" to include $pmtConf
 */

// require("pmtModule.php");
// require("/../pmtModule.php");
class dashboard implements iModule
{
  const MODULE = "dashboard";

  private $_title;      // Title of the screen
  private $_toolbar;    // HTML generated toolbar according to location
  private $_minileft;   // mini toolbar (left)
  private $_miniright;  // mini toolbar (right)
  private $_pagedata;   // Main page data

  function __construct()
  {
    /**
     * 1) Get the user access (Logged off, Anon, Admin, etc.)
     *  1.1) get user settings for custom dashboard. (future)
     * 2) Generate page
     * 3) Check for custom title
     */

    // Get title from database setting
    $this->_title = "Dashboard " . " - " . "[PMT]";    // "Xeno Tracking System"
    $this->_toolbar = ""; //$this->GenerateToolbar();
    $this->_minileft = $this->GenerateMiniLeft(); // "&nbsp; (test-left)";
    $this->_miniright = $this->GenerateMiniRight();  // "&nbsp; (test-right)";
    $this->_pagedata = $this->GeneratePage();
  }

  public function Title() { return $this->_title; }             /* Title of the generated page */
  public function Toolbar() { return $this->_toolbar; }         /* Toolbar - HTML generated toolbar according to location */
  public function MiniBarLeft() { return $this->_minileft; }
  public function MiniBarRight() { return $this->_miniright; }
  public function PageData() { return $this->_pagedata; }



  /* ****************** */
  /* ****************** */

  public function GeneratePage()
  {
    $sample =  "<h1>Welcome to <b><i>xeno</i>PMT</b></h1>";
    $sample .= "<p>This system is still under heavy development and is not ";
    $sample .= "ready for live action use by any means. Soon enough you will ";
    $sample .= "get to see what the future holds.  As the project develops the ";
    $sample .= "user and engineering documentation will mature along with it.</p>";
    $sample .= "<p>Sit tight and enjoy the ride!</p>";
    $sample .= "<p>&nbsp;</p>";
    $sample .= "<p>- Xeno Innovations, Inc. -</p>";

    return $sample;
  }


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
          // Module         Display
    //    "dashboard"   => "Dashboard",
    //    "main"        => "Dashboard",
    //    ""            => "Dashboard",
          "main"        => "Dashboard",
          "project"     => "Projects",
          "ticket"      => "Tickets",     /* "ticket" => array ("Tickets", "+"), */
          "bugs"        => "Bugs",
          "tasks"       => "Tasks",
          "product"     => "Products",
          "customer"    => "Customers",
          "user"        => "Users",
          "admin"       => "Admin"
          );

    $tab = "        ";
    $ret = $tab . "<ul>". PHP_EOL;
    $ndxCount = 0;
    //print (count($a));
    foreach($arrAvailMods as $key => $value)
    { //print ("key: $key, Obj: $value <br />");

      $ndxCount++;
      if ($ndxCount == 1)
        $cls = ' class="first active"';
      elseif($ndxCount == count($arrAvailMods))
        $cls = ' class="last"';
      else
        $cls = '';

      $ret .= $tab .
              "  <li" . $cls. ">" .
              $this->makeLink($key, $value) .
              "</li>" . PHP_EOL;

    }
    $ret .= $tab . "</ul>". PHP_EOL;
    //pmtDebug("disp: " . $ret);

    return $ret;
  }

  private function GenerateMiniLeft()
  {
    $code = "<a class='first' href='/'>main</a>";
    return $code;
  }

  private function GenerateMiniRight()
  {
    $code = "<ul>" .
            "<li class='first'> 1st </li>" .
            "<li> 2nd&nbsp; </li>" .
            "<li class='last'> 3rd </li>" .
            "</ul>";
    //$code = "<ul><li class='last'> just one </li></ul>";

    return $code;
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


}

?>
