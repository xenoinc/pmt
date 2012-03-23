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
 *
 */

require("pmtModule.php");

class dashboard implements pmtModule
{

  private $_title;      // Title of the screen
  private $_toolbar;    // HTML generated toolbar according to location
  private $_minileft;   // mini toolbar (left)
  private $_miniright;  // mini toolbar (right)

  function __construct()
  {
    /**
     * 1) Get the user access (Logged off, Anon, Admin, etc.)
     *  1.1) get user settings for custom dashboard. (future)
     * 2) Generate page
     * 3) Check for custom title
     */

    // Get title from database setting
    $this->_title = "Dashboard" . "- " . "[PMT]";    // "Xeno Tracking System"
    $this->_toolbar = $this->GenerateToolbar();
    $this->$_minileft = "&nbsp;";
    $this->$_miniright = "&nbsp;";
  }

  public function PageData()
  {
    return "hello world";
  }

  public function Title() { return $this->_title; }

  public function Toolbar() { return $this->_toolbar; }


  public function MiniBarLeft() { return $this->_minileft; }

  public function MiniBarRight() { return $this->_miniright; }



  /* ****************** */
  /* ****************** */


  private function GenerateToolbar()
  {
    /* Steps:
    * 1) Get user profile permissions to see what
    *    items we can draw on the screen.
    * 2) Generate toolbar
    */

    /* Step 1 - Get user permissions */

    /* Step 2 - Generate Toolbar */
    $t = "        ";

    // $tbar = array("main", "projects");
    $tbar = array();
    $tmod = array();

    array_push($tmod, "dashboard"); array_push($tbar, "Dashboard");
    array_push($tmod, "project");   array_push($tbar, "Projects");
    array_push($tmod, "ticket");    array_push($tbar, "Tickets" . "&nbsp;&nbsp;" . "+");
    array_push($tmod, "product");   array_push($tbar, "Products");
    array_push($tmod, "customer");  array_push($tbar, "Customers");
    array_push($tmod, "admin");     array_push($tbar, "Admin");
    //array_push($tmod, "&nbsp;");     array_push($tbar, "&nbsp;"); // add right border

    //$tmod = array("dashboard", "project", "ticket", "product", "customer", "admin");
    //$tbar = array("dashboard", "project", "ticket", "product", "customer", "admin");


    $ret = $t . "<ul>". PHP_EOL;

    for ($ndx = 0; $ndx < count($tmod); $ndx++)
    {
      // pmtDebug($ndx);



      // Draw toolbar and set the active item
      if ($ndx == 0){
        $cls = ' class="first active"';
      }elseif ($ndx == count($tbar)-1){
        $cls = ' class="last"';
      }else{
        $cls = '';
      }
      $ret .= $t . "  <li" . $cls. ">" . $tbar[$ndx] . "</li>" . PHP_EOL;

    }

    $ret .= $t . "</ul>". PHP_EOL;
    //pmtDebug("disp: " . $ret);

    return $ret;
  }

}

?>
