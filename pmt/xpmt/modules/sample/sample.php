<?php

/* * **********************************************************
 * Copyright 2012 (C) Xeno Innovations, Inc.
 * ALL RIGHTS RESERVED
 * Author:       Damian Suess
 * Document:     sample.php - Sample Plugin
 * Created Date: Apr 18, 2012
 *
 * Description:
 *  Knowledge base class. Syntax uses WikiSyntax as its engine
 *
 * Change Log:
 *  2012-0620 + Ground breaking
 */


//require ("/../../iModule.php");
class sample implements iModule
{
  const MODULE  = "sample";    // Module name

  const cCMD    = "cmd";
  const cNEW    = "new";
  const cEDIT   = "edit";
  const cREMOVE = "remove";

  // Module settings that pass pack data to xenoPMT core
  private $_title;        // Title of the screen
  private $_toolbar;      // HTML generated toolbar according to location
  private $_minileft;     // mini toolbar (left)
  private $_miniright;    // mini toolbar (right)
  private $_pagedata;     // Main page data


  // Internal module setup
  private $_MODE;         // ENUM_ProjMode from parser
  private $_WikiPage;     // name of wikipage to display/add/edit/remove
  private $_SWITCH;       // Switch: new, edit, remove, <blank>

  /* ************************************************ */

  //function __construct($uriPath = "")
  function __construct()
  {
    global $uri;

    if (count($uri->seg)>1)
          $this->_PROJ_Name = self::MODULE."/".$uri->seg[1];
    else  $this->_PROJ_Name = self::MODULE;

    // Get the segments and the mode to be used
    $this->ParseData();

    $this->_title     = "Sample Module - [xenoPMT]";
    $this->_toolbar   = ""; //$this->GenerateToolbar();
    $this->_minileft  = $this->GenerateMiniLeft();   // Usually used for breadcrumbs or other
    $this->_miniright = $this->GenerateMiniRight();  // "&nbsp; (test-right)";
    $this->_pagedata  = $this->GeneratePage();
  }

  public function Title() { return $this->_title; }             /* Title of the generated page */
  public function Toolbar() { return $this->_toolbar; }         /* Toolbar - HTML generated toolbar according to location */
  public function MiniBarLeft() { return $this->_minileft; }
  public function MiniBarRight() { return $this->_miniright; }
  public function PageData() { return $this->_pagedata; }


  /* **************************** */
  /* **************************** */

  /**
   * Get the command switch passed in
   * * In future could also use the last Seg[] if it is equal to, "/edit"
   *   however this method may be more safe.
   */
  private function GetCmd()
  {
    // self::cCMD = "cmd"
    if (isset($_GET[self::cCMD]) && $_GET[self::cCMD])
      return $_GET[self::cCMD];
    else
      return "";
  }
  /**
   * Parse URL data to assist in redirecting information to properly
   * generate the GUI
   *
   * @global array $pmtConf
   * @global array $uri
   * @property string _MODE       ListAll, New, Edit, Remove
   * @property string _WikiPage   Wiki page to display (Not used yet)
   * @property string _SWITCH     Switch: new, edit, remove, <blank>
   *
   */
  private function ParseData()
  {
    global $pmtConf;
    global $uri;

    //$proj_url = self::MODULE;         // Default to base URL
    $cmd = $this->GetCmd();             // $proj_switch = "";

    switch (count($uri->seg))
    {
      /// Show KB Article welcome page ("/kb")
      case 1:
        $mode = "";
        break;

      /// Display KB page ("/kb/<kb-id>")
      case 2:
        break;

    }

    $this->_MODE = $mode;
    $this->_SWITCH = $cmd;

  }

  private function GenerateMiniLeft()
  {
    $code = "<ul>".
            "<li>".  $this->AddLink(self::MODULE, "Item1", "?cmd=action1") ."</li>".
            "<li>".  $this->AddLink(self::MODULE, "Item2", "?cmd=action2") ."</li>".
            "<li class='last'>".  $this->AddLink(self::MODULE, "Item3", "?cmd=action3") ."</li>".
            "</ul>";

    return $code;
  }

  private function GenerateMiniRight()
  {
    $code = "<ul>".
            "<li>".  $this->AddLink(self::MODULE, "Item1", "?cmd=action1") ."</li>".
            "<li>".  $this->AddLink(self::MODULE, "Item2", "?cmd=action2") ."</li>".
            "<li class='last'>".  $this->AddLink(self::MODULE, "Create New", "?cmd=new") ."</li>".
            "</ul>";

    return $code;
  }

  private function GeneratePage()
  {
    //global $user;
    //global $uri;
    $html = "";

    if($user->online == false)
      $html = $this->Page_UserOffline();
    else
    {
      switch ($this->_MODE)
      {
        // For now just display the default message
        default:
          $html .= $this->Page_UserOffline();
          break;

      }
    }

    return $html;
  }


  /* User offline message */
  private function Page_UserOffline()
  {
    $html = <<<"EOT"
        <h1>Sample Module</h1>
        <p>
          This system is still under heavy development and is not
          ready for live action use by any means. Soon enough you will
          get to see what the future holds.  As the project develops the
          user and engineering documentation will mature along with it.</p>
        <p>Sit tight and enjoy the ride!</p>
        <p>&nbsp;</p>
        <p>- Xeno Innovations, Inc. -</p>
        <p></p>
EOT;
    return $html;
  }

  /* **[ Assisting members ]******* */
  private function AddLink($module, $text, $extLink = "")
  {
    global $pmtConf;
    return '<a href="'. $pmtConf["general"]["base_url"].$module.$extLink.'">'.$text.'</a>';
  }
}



?>
