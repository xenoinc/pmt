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
 *    http://pmt/kb/<id>?cmd=list     - List Articles
 *
 *    http://support.microsoft.com/kb/319401
 *
 *    POST:
 *      Vote:#   - Rate article 0-5
 * ToDo:
 * [ ] GenerateMiniRight() - Get user permissions
 * [ ] Create GUI :: Create New Article
 * [ ] Create GUI :: Edit Article
 * [ ] Create GUI :: Remove Article  (Show article title, OK & Cancel button)
 * [ ] Create GUI :: List all articles
 * [ ] Create GUI :: Search Articles
 *
 * Change Log:
 *  2012-0716 * Moved Main and List pages to the KB-MAIN.PHP file
 *            + Added command param, "list" for listing of article. Needs expanded on
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

//require ("/../pmtModule.php");

class kb implements iModule
{

  const MODULE  = "kb";       // Module name
  const cCMD    = "cmd";      // view "1" (1-20), "21" (21-40)
  const cVIEW   = "view";     // view "1" (1-20), "21" (21-40)
  const cLIST   = "list";     // List articles ** Needs Expanded on & sub-switch for range and start-id **
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
  private $_PAGE;         // name of KB page to display/add/edit/remove
  private $_SWITCH;       // Switch: new, edit, remove, list, search, <blank>

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
    //$this->_pagedata = $this->GeneratePage();
    $this->_pagedata = $this->EventHandler();
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
    //global $pmtConf;
    global $uri;

    /**
     * $uri->seg[]  0="kb"  1="1234" (kb id)
     */

    //$proj_url = self::MODULE;         // Default to base URL
    $cmd = $this->GetCmd();
    $mode = ENUM_KBMode::KBMain;        // What is KB suppose to do
    $kbPage = 0;                        // Default no kb page (0)

    switch (count($uri->Count))
    {
      /// Show KB Article welcome page ("/kb")
      /// or List range of pages using parameter passed in (ENUM_KBMode::KBList:)
      case 1:
        switch($cmd)
        {
          case self::cNEW:      $mode = ENUM_KBMode::KBNew;   break;  // Create New
          case self::cLIST:     $mode = ENUM_KBMode::KBList;  break;  // List available pages
          //case self::cSEARCH:     $mode = ENUM_KBMode::KBSearch;  break;  // Search articles
          default:              $mode = ENUM_KBMode::KBMain;  break;  // Display main page
        }
        break;

      /// Display KB page ("/kb/<kb-id>")
      case 2:

        // Should perform (isNumeric)?
        $kbPage = $uri->seg[1];         // Get KB number to edit
        switch($cmd)
        {
          case self::cNEW:      $mode = ENUM_KBMode::KBNew;     break;    // Create New
          case self::cEDIT:     $mode = ENUM_KBMode::KBEdit;    break;    // Edit KB Article
          case self::cREMOVE:   $mode = ENUM_KBMode::KBRemove;  break;    // Remove KB Article
          default:              $mode = ENUM_KBMode::KBView;    break;    // View page
        }
        pmtDebug("0:". $uri->seg[0] . " 1: ". $uri->seg[1]);

        //pmtDebug("KB Page Id: " . $kbPage . PHP_EOL . "Mode: " . $mode);

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
    global $user;
    if ($user->Online != false)
    {
      $code =   "<ul>";
      $code .=  "<li>". $this->AddLink(self::MODULE, "Main", "?")  ."</li>";
      $code .=  "<li class='last'>". $this->AddLink(self::MODULE, "List Articles", "?cmd=".self::cLIST)  ."</li>";
      //$code .=  "<li class='last'>". $this->AddLink(self::MODULE, "KB Search", "?cmd=search")  ."</li>";
      $code .=  "</ul>";

      return $code;
    }
    else
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
    global $user;

    if ($user->Online != false)
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
        $code .= "<li>" .             $this->AddLink(self::MODULE, "Create",  "?cmd=new") . "</li>";
        $code .= "<li>".              $this->AddLink(self::MODULE, "Edit",    "?cmd=edit") . "</li>";
        $code .= "<li class='last'>". $this->AddLink(self::MODULE, "Remove",  "?cmd=remove") . "</li>";
      }
      $code .= "</ul>";
    }
    else
      $code = "";

    return $code;
  }

  private function EventHandler()
  {
    /*
     * ToDo:
     * [ ] + Check if login is required by the module [2012-07-16]
     *      For now, require it (by default)
     *
     */

    global $user;
    //global $uri;
    $html = "";


    // ToDo: Add logic to check if login is required
    if ($user->Online == false)
    {
      $html = $this->Page_UserOffline();
      return $html;
    }

    switch ($this->_MODE)
    {
      case ENUM_KBMode::KBMain:
        // pmtDebug("KB: Main");

        require_once "kb-main.php";
        $k = new xenoPMT\Module\KB\Main;
        $html .= $k->PageLayout(1);

        break;


      case ENUM_KBMode::KBNew:

        require_once "kb-new.php";
        // use xenoPMT\Module\KB\KBNew as kb;
        // $k = new kb;
        // $html = \xenoPMT\Module\KB\KBNew::DataHandler();

        $k = new xenoPMT\Module\KB\Create;
        $k->DataHandler();            // Handle $_POST & $_GET commands
        $html .=  $k->PageLayout();

        // pmtDebug("KB: New");
        break;

      case ENUM_KBMode::KBView:

        require_once "kb-view.php";
        $k = new xenoPMT\Module\KB\View($this->_PAGE);
        $html .=  $k->PageLayout();
        //pmtDebug("KB: View");

        break;

      case ENUM_KBMode::KBEdit:
        pmtDebug("KB: Edit");
        break;


      case ENUM_KBMode::KBRemove:
        pmtDebug("KB: Remove");
        break;


      case ENUM_KBMode::KBList:
        //pmtDebug("KB: List");

        require_once "kb-list.php";
        $k = new xenoPMT\Module\KB\ListItems;
        $k->DataHandler();
        //$html .= $k->ListArticles();
        $html .= $k->PageLayout();

        break;


      // Show main page
      default:

        pmtDebug("KB: Default");
        require_once "kb-main.php";
        $k = new xenoPMT\Module\KB\Main;
        $html .= $k->PageLayout();

        break;
    }

    return $html;
  }

  /* User offline message */

  private function Page_UserOffline()
  {
    // To Do:

    $html = <<<"EOT"
        <h1>Knowledge Base</h1>
        <p>
          Please sign in to use this module</p>
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

    /* To Do:
     * [ ] + sub setting for "List" (startPos, maxResults)
     * [ ] + sub setting for "Search" (search string, category).. though this can be apart of $_POST[]
     */

    // self::cCMD = "cmd"
    if (isset($_GET[self::cCMD]) && $_GET[self::cCMD])
      return $_GET[self::cCMD];
    else
      return "";
  }


}

?>
