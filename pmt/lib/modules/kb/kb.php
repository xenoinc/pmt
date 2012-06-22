<?php

/* * **********************************************************
 * Copyright 2012 (C) Xeno Innovations, Inc.
 * ALL RIGHTS RESERVED
 * Author:       Damian Suess
 * Document:     kb.php - Knowledge Base
 * Created Date: Apr 18, 2012
 *
 * Description:
 *  Knowledge base class. Syntax uses WikiSyntax as its engine
 *
 * URL Examples:
 *    http://pmt/kb/<id>              - View
 *    http://pmt/kb/?cmd=new          - New
 *    http://pmt/kb/<id>?cmd=edit     - Edit
 *    http://pmt/kb/<id>?cmd=remove   - Remove
 *
 *    POST:
 *      Vote:#   - Rate article 0-5
 * ToDo:
 * [ ] GenerateMiniRight() - Get user permissions
 * [ ] Create GUI :: Create New Article
 * [ ] Create GUI :: View Article
 * [ ] Create GUI :: Edit Article
 * [ ] Create GUI :: Remove Article  (Show article title, OK & Cancel button)
 * [ ] Create GUI :: List all articles
 * [ ] Create GUI :: Search Articles
 *
 * Change Log:
 *  2012-0618 + Ground breaking
 *
 */

// Makeshift Enum
class ENUM_KBMode
{
  const KBMain = 0;         // View welcome page. Search box, Create New, Top 10
  const KBView = 10;        // "kb-view"   - View article
  const KBNew = 11;         // "kb-new"    - Create new article
  const KBEdit = 12;        // "kb-edit"   - Edit article
  const KBRemove = 13;      // "kb-remove" - Remove article (must have permissions)
  const KBList = 14;        // List of articles - In the future, break down to TOP 20 or 0-200
}

require ("/../pmtModule.php");

class kb implements pmtModule {

  const MODULE = "kb";      // Module name
  const cCMD = "cmd";       // view "1" (1-20), "21" (21-40)
  const cVIEW = "view";     // view "1" (1-20), "21" (21-40)
  const cNEW = "new";
  const cEDIT = "edit";
  const cREMOVE = "remove";

  // Module settings that pass pack data to xenoPMT core
  private $_title;        // Title of the screen
  private $_toolbar;      // HTML generated toolbar according to location
  private $_minileft;     // mini toolbar (left)
  private $_miniright;    // mini toolbar (right)
  private $_pagedata;     // Main page data
  // Internal module setup
  private $_MODE;         // ENUM_ProjMode from parser
  private $_PAGE;         // name of KB page to display/add/edit/remove
  private $_SWITCH;       // Switch: new, edit, remove, <blank>

  /*   * *********************************************** */

  //function __construct($uriPath = "")
  function __construct()
  {
    global $uri;

    if (count($uri->seg) > 1)
      $this->_PROJ_Name = self::MODULE . "/" . $uri->seg[1];
    else
      $this->_PROJ_Name = self::MODULE;

    // Set default name - This will change when viewing articles (KB Article 'title' - [xenoPMT])
    $this->_title = "Knowledge Base - [xenoPMT]";    // "Xeno Tracking System"

    // Get the segments and the mode to be used
    $this->ParseData();
    $this->_toolbar = "";                             // Custom toolbar not used )($this->GenerateToolbar();)
    $this->_minileft = $this->GenerateMiniLeft();     // Breadcrumbs  (Not used in KB space)
    $this->_miniright = $this->GenerateMiniRight();   // "&nbsp; (test-right)";
    $this->_pagedata = $this->GeneratePage();
  }

  public function Title() {         return $this->_title; }       /* Title of the generated page */
  public function Toolbar() {       return $this->_toolbar; }     /* Toolbar - HTML generated toolbar according to location */
  public function MiniBarLeft() {   return $this->_minileft; }
  public function MiniBarRight() {  return $this->_miniright; }
  public function PageData() {      return $this->_pagedata; }

  /*   * *************************** */
  /*   * *************************** */

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

    /**
     * $uri->seg[]  0="kb"  1="1234" (kb id)
     */

    //$proj_url = self::MODULE;         // Default to base URL
    $cmd = $this->GetCmd();
    $mode = ENUM_KBMode::KBMain;        // What is KB suppose to do
    $kbPage = 0;                        // Default no kb page (0)

    switch (count($uri->seg))
    {
      /// Show KB Article welcome page ("/kb")
      case 1:
        $mode = ENUM_KBMode::KBMain;    // Display main page
        pmtDebug("KB: Display Main Page");
        break;

      /// Display KB page ("/kb/<kb-id>")
      case 2:

        $kbPage = $uri->seg[1];         // Get KB number to edit

        if ($cmd == self::cNEW)         $mode = ENUM_KBMode::KBNew;     // Create New
        elseif ($cmd == self::cEDIT)    $mode = ENUM_KBMode::KBEdit;    // Edit KB Article
        elseif ($cmd == self::cREMOVE)  $mode = ENUM_KBMode::KBRemove;  // Remove KB Article
        else                            $mode = ENUM_KBMode::KBView;    // View page

        pmtDebug("KB Page Id: " . $kbPage . PHP_EOL . "Mode: " . $mode);

        break;

      default:
        $mode = ENUM_KBMode::KBMain;
        break;
    }

    $this->_MODE = $mode;
    $this->_SWITCH = $cmd;
    $this->_PAGE = $kbPage;
  }

  /**
   * Generate bread crumbs (not used for KB)
   */
  private function GenerateMiniLeft()
  {
    return "";
  }

  /**
   * Generate Action Toolbar
   */
  private function GenerateMiniRight()
  {
    /**
     * To Do:
     *  [ ] Check user permission if they can create [new], [edit], [rmv] articles
     */
    if ($user->online != false)
    {
      $code = "<ul>";
      if ($this->_PAGE == "0")
      {
        $code .= "<li class='last'>Article:</li>";
        $code .= "<li class='last'>" . $this->AddLink(self::MODULE, "Create", "?cmd=new") . "</li>";
      }
      else
      {
        $code .= "<li class='last'>Article:</li>";
        $code .= "<li>" .             $this->AddLink(self::MODULE, "Create", "?cmd=new") . "</li>";
        $code .= "<li>".              $this->AddLink(self::MODULE, "Edit", "?cmd=edit") . "</li>";
        $code .= "<li class='last'>". $this->AddLink(self::MODULE, "Remove", "?cmd=remove") . "</li>";
      }
      $code .= "</ul>";
    }
    else
      $code = "";

    return $code;
  }

  private function GeneratePage()
  {
    global $user;
    global $uri;
    $html = "";


    if ($user->online == false)
      $html = $this->Page_UserOffline();
    else {
      switch ($this->_MODE) {
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
        <h1>Knowledge Base</h1>
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

  /*   * *[ Assisting members ]******* */

  private function AddLink($module, $text, $extLink = "")
  {
    global $pmtConf;
    return '<a href="' . $pmtConf["general"]["base_url"] . $module . $extLink . '">' . $text . '</a>';
  }

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


}

?>
