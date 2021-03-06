<?php

/* * **********************************************************
 * Copyright 2012 (C) Xeno Innovations, Inc.
 * ALL RIGHTS RESERVED
 * Author:       Damian J. Suess
 * Document:     project.php
 * Created Date: Mar 22, 2012
 *
 * Description:
 *  Project module to handle all project pages
 *
 *  ** See Engineering document for more info
 *
 * Change Log:
 *  2012-0709 * Renamed 'pmtModule' to new interface name, 'iModule' [DJS]
 *            - Removed require of interface since the core includes it already [DJS]
 *  2012-0424 - Moved member 'Page_ProjectNew' to ProjExt::Page_ProjectNew
 *  2012-0406 + Adding Heredoc & Nowdoc versus a shitload of PRINT statements
 *  2012-0404 - Removed usage of makeLink & using AddLink from pmt-functions.php
 *  2012-0402 - Bypassed GenerateToolbar(). Return "" and use default.
 *            + Added test front page for links
 *  2012-0328 * fixed "makeLink" to include $pmtConf
 */


// Makeshift Enum
class ENUM_ProjMode   //interface ENUM_ProjMode
{
  const ListAll         = 0;    // ""

  const ProjectView     = 10;   // "project-view"   - View project (all milestones? or main wiki?)
  const ProjectNew      = 11;   // "project-new"    - Create new project
  const ProjectEdit     = 12;   // "project-edit"   - Edit project options (wiki, scm, milestones, components, versions)
  const ProjectRemove   = 13;   // "project-remove" - Remove the project (if dependancies are remove first [milestones])

  const MilestoneView   = 20;   // "milestone-view"
  const MilestoneAdd    = 21;   // "milestone-add"
  const MilestoneEdit   = 22;   // "milestone-edit"
  const MilestoneRemove = 23;   // "milestone-remove"

  const ComponentView   = 30;   // "component-view"
  const ComponentAdd    = 31;   // "component-add"
  const ComponentEdit   = 32;   // "component-edit"
  const ComponentRemove = 33;   // "component-remove"

  const WikiView        = 40;   // "wiki"           - View wiki page
  const WikiNew         = 41;   // "wiki-new"       - New wiki page
  const WikiEdit        = 42;   // "wiki-edit"      - Edit wiki page
  const WikiRemove      = 43;   // "wiki-remove"    - Remove wiki page
}

/**
 * Used along with "$uri->seg[2]"
 */
class ENUM_ProjSegment
{
  const Milestone = "milestone";
  const Component = "component";
  const Wiki = "wiki";
  const Repo = "repo";
}


//var $today = DaysOfWeek::Sunday;


//require ("pmtModule.php");
class project implements iModule
{
  const MODULE = "p";
  //private $MODULE = "project";
  //private $MODULE = "p";
  const cCMD    = "cmd";
  const cNEW    = "new";
  const cEDIT   = "edit";
  const cREMOVE = "remove";


  private $_title;        // Title of the screen
  private $_toolbar;      // HTML generated toolbar according to location
  private $_minileft;     // mini toolbar (left)
  private $_miniright;    // mini toolbar (right)
  private $_pagedata;     // Main page data

  private $_PROJ_Name;  // Base project url ("p" or "p/test-proj"

  private $_MODE;         // ENUM_ProjMode from parser
  private $_WikiPage;     // name of wikipage to display/add/edit/remove
  private $_SWITCH;       // Switch: new, edit, remove, <blank>

  //function __construct($uriPath = "")
  function __construct()
  {
    global $uri;

    if (count($uri->seg)>1)
          $this->_PROJ_Name = self::MODULE."/".$uri->seg[1];
    else  $this->_PROJ_Name = self::MODULE;

    // Get the segments and the mode to be used
    $this->ParseData();

    $this->_title = "Project " . " - " . "[PMT]";    // "Xeno Tracking System"
    $this->_toolbar = ""; //$this->GenerateToolbar();
    $this->_minileft = "";
    $this->_miniright = $this->GenerateMiniRight();  // "&nbsp; (test-right)";
    $this->_pagedata = $this->GeneratePage();
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
   * Safely get data from POST command
   */
  private function GetPost($postCmd)
  {
    return "";
  }

  /**
   * Parse URL data to assist in redirecting information to properly
   * generate the GUI
   *
   * @global array $pmtConf
   * @global array $uri
   * @property string _MODE       ListAll, ProjectView, MilestoneView/Edit, Wiki
   * @property string _WikiPage   Wiki page to display (Not used yet)
   * @property string _SWITCH     Switch: new, edit, remove, <blank>
   *
   */
  private function ParseData()
  {
    /**
     * 1) Get user permission to view Project path or utalize the command passed
     * 2) Generate depending upon [$projMode]
     *    * Project Listing   (pmt/project/)
     *      * Generate: (List Projects, Create Project
     *    * Milestone Listing (pmt/project/milestones)
     *
     * Note:
     *  $uri->seg[]  0=p  1=projectName  2=Action (wiki, milestone, etc)
     */

    // print(ENUM_ProjMode::ListAll);

    global $pmtConf;
    global $uri;
    //$url = $pmtConf["general"]["base_url"] . self::MODULE;

    /** Section 2 - Get Project_Mode **/
    if (count($uri->seg)>1)
          $proj_url = self::MODULE."/".$uri->seg[1];
    else  $proj_url = self::MODULE;


    //$proj_url = self::MODULE;         // Default to base URL
    $proj_mode = "";                    // Default to base URL
    $wiki_page = "";                    // Default wiki page
    $proj_switch = $this->GetCmd();     // $proj_switch = "";


    switch (count($uri->seg))
    {
      case 1: // List all
        $proj_mode = ENUM_ProjMode::ListAll;                // "";

        /**
         * Handle
          if($_GET["cmd"] == "new")
         */
        // if (isset($_GET["cmd"] && $_GET["cmd"])
        //   $proj_switch = $_GET["cmd"];
        //$proj_switch = $this->GetCmd();

        if ($proj_switch == self::cNEW)         $proj_mode = ENUM_ProjMode::ProjectNew;

        break;

      case 2: // Project View  ("/p/<proj-name>/")  (seg[1]=<proj-name>)
        /**
         * Handle
          if($_GET["cmd"] == "new")                         // "New Milestone", "New Wiki Page"
          if($_GET["cmd"] == "edit")                        // "Edit Wiki Page"
          if($_GET["cmd"] == "remove")                      // "Remove Project"
         */
        if     ($proj_switch == self::cEDIT)    $proj_mode = ENUM_ProjMode::ProjectEdit;
        elseif ($proj_switch == self::cREMOVE)  $proj_mode = ENUM_ProjMode::ProjectRemove;
        else                                    $proj_mode = ENUM_ProjMode::ProjectView;          // "project-view";

        //$proj_switch = $this->GetCmd();

        break;

      case 3: // "Milestone" / "Wiki Page" Viewer
        /**
         * Segment ($uri->seg[])
         * [1] = <proj-name>
         * [2] = (milestone, wiki)
         */

        if ($uri->seg[2] == ENUM_ProjSegment::Milestone)    // "milestone")
        {
          // Show Milestone MAIN Toolbar
          /**
          * Handle
            if($_GET["cmd"] == "edit")                      // "Edit Milestone"
            if($_GET["cmd"] == "remove")                    // "Remove Milestone"
          */
          //$proj_switch = $this->GetCmd();

          if     ($proj_switch == self::cEDIT)    $proj_mode = ENUM_ProjMode::MilestoneEdit;      // "milestone-edit";
          elseif ($proj_switch == self::cREMOVE)  $proj_mode = ENUM_ProjMode::MilestoneRemove;    // "milestone-edit";
          else                                    $proj_mode = ENUM_ProjMode::MilestoneView;      // "milestone-view";

        }
        elseif ($uri->seg[2] == ENUM_ProjSegment::Wiki)     // "wiki")
        {
          // Show MAIN WIKI toolbar
          /**
          * Handle
            if($_GET["cmd"] == "new")                       // "New Wiki Page"
            if($_GET["cmd"] == "edit")                      // "Edit Wiki Page"
            if($_GET["cmd"] == "remove")                    // "Remove Wiki Page"
          */
          //$proj_switch = $this->GetCmd();
          if     ($proj_switch == self::cNEW)       $proj_mode = ENUM_ProjMode::WikiNew;
          elseif ($proj_switch == self::cEDIT)      $proj_mode = ENUM_ProjMode::WikiEdit;
          elseif ($proj_switch == self::cREMOVE)    $proj_mode = ENUM_ProjMode::WikiRemove;
          else                                      $proj_mode = ENUM_ProjMode::WikiView;         // "wiki";
        }
        else
        {
          // Default to base URL
          $proj_mode =  ENUM_ProjMode::ProjectView;
        }
        break;

      case 4: // Wiki Toolbar --- OR --- (Milestone Edit <-- no?)
        /**
         * Segment ($uri->seg[])
         * [1] = <proj-name>
         * [2] = (milestone, wiki)
         *    wiki: [3] = page
         */
        // Are we viewing wiki?
        if ($uri->seg[2] == ENUM_ProjSegment::Wiki)         // "wiki")
        {
          // Show MAIN WIKI toolbar
          /**
          * Handle
            if($_GET["cmd"] == "new")                       // "New Wiki Page"
            if($_GET["cmd"] == "edit")                      // "Edit Wiki Page"
            if($_GET["cmd"] == "remove")                    // "Remove Wiki Page"
          */
          //$proj_switch = $this->GetCmd();
          $proj_mode = ENUM_ProjMode::WikiView;                 // "wiki";
          $wiki_page = $uri->seg[3];          // USED IN::  "/wiki/" ./*page*/ "?cmd=edit") ."</li>".

          if ($proj_switch == "edit")
            $proj_mode = ENUM_ProjMode::WikiEdit;

        }
        else
        {
          //$proj_mode = "milestone-edit";
          $proj_mode = "";
        }
        break;
    }

    $this->_MODE = $proj_mode;        // ListAll, ProjectView, MilestoneView/Edit, Wiki
    $this->_WikiPage = $wiki_page;    // Not used yet
    $this->_SWITCH = $proj_switch;    // Switch: new, edit, remove, <blank>

  }


  /**
   * Redirect information from ParseData() to generate
   * the correct page
   *
   * @global Member $user
   * @return string
   */
  private function GeneratePage()
  {
    /**
     * Depending on usr permissions settings, list all projects available to
     * the user logged in.
     */
    global $user;
    global $uri;
    $html = "";

    if($user->Online == false)
      $html = $this->Page_UserOffline();
    else
    {
      switch ($this->_MODE)
      {

        case ENUM_ProjMode::ListAll:
          require ("ext/project.ext.view.php");    // Maybe move outside the class
          $html = ProjExt_View::Proj_List();
          // $html = $this->Page_ProjectList();
          break;

        case ENUM_ProjMode::ProjectNew:

          require ("ext/project.ext.new.php");    // Maybe move outside the class
          $html = ProjExt_New::Page_ProjectNew();
          break;

        case ENUM_ProjMode::ProjectEdit:

          break;

        // For now, do the same thing
        case ENUM_ProjMode::ProjectView:
          require ("ext/project.ext.view.php");    // Maybe move outside the class
          $html = ProjExt_View::Proj_Details();
          break;

        case ENUM_ProjMode::WikiView:

          require ("ext/project.ext.wiki.php");
          $html = ProjExt_Wiki::Page_View();
          break;

        default:
          $html = <<<EOT
        <div align='left' class='message error'>
          Location Unknown: <code>{$uri->GetUri()}</code>
          <blockquote><code>{$uri->GetUri()}</code></blockquote>
        </div>
EOT;
          $html .= $this->Page_UserOffline();
          break;
      }


    }
    return $html;
  }


  private function GenerateMiniRight()
  {
    /**
     * 1) Get user permission to view Project path or utalize the command passed
     * 2) Generate depending upon [$projMode]
     *    * Project Listing   (pmt/project/)
     *      * Generate: (List Projects, Create Project
     *    * Milestone Listing (pmt/project/milestones)
     */
    global $pmtConf;
    global $uri;
    //$url = $pmtConf["general"]["base_url"] . self::MODULE;



    /** Section 2 - Get Project_Mode **/
    if (count($uri->seg)>1)
          $proj_url = self::MODULE."/".$uri->seg[1];
    else  $proj_url = self::MODULE;


    //$proj_url = self::MODULE;     // Default to base URL
    $proj_mode = $this->_MODE;      // Default to base URL
    $wiki_page = $this->_WikiPage;  // Default wiki page
    $proj_switch = $this->_SWITCH;  // "cmd" switch was thrown


    /** Section 3 - Generate depending on Project_Mode **/

    // $wiki_page = "";  // USED IN::  "/wiki/" ./*page*/ "?cmd=edit") ."</li>".
    // switch($proj_mode)
    switch($this->_MODE)
    {
      case "":
        /* Viewing ALL Available Projects */
        /* :: "Create Project" :: */
        $code = "<ul>".
                //"<li class='last'>".  AddLink(self::MODULE, "Create Project", "?cmd=new") ."</li>".
                "<li class='last'>".  AddLink(self::MODULE, "Create Project", "?cmd=new") ."</li>".
                "</ul>";
        break;

      case ENUM_ProjMode::ProjectView: // "project-view":

        /* Viewing Single Project */
        /* :: "New Milestone" | "Edit Project" | "Remove Project" :: */

        // ** Cleanup code for the Selected project
        // $url = self::MODULE."/".$uri->seg[1];
        $code = "<ul>" .
                "<li class='first'>". AddLink($proj_url, "New Milestone", "/milestone/?cmd=new") ."</li>".
                "<li> " .             AddLink($proj_url, "New Page",  "/wiki/?cmd=new") ."</li>".
                "<li> " .             AddLink($proj_url, "Edit Page",  "/wiki/?cmd=edit") ."</li>".
                "<li class='last'>".  AddLink($proj_url, "Remove Project","/?cmd=remove") ."</li>".
                "</ul>";
        break;

      //case "milestone-all":
      //  /* Viewing All Milestones*/
      //  /* :: "New Milestone" :: */
      //  break;

      case ENUM_ProjMode::MilestoneView:  // "milestone-view":
        /* viewing Single Milestone */
        /* :: "Edit Milestone" | "Remove Milestone" :: */
        $code = "<ul>" .
                "<li class='first'>". AddLink($proj_url,"Edit Milestone","/milestone/?cmd=edit") ."</li>".
                "<li class='last'>".  AddLink($proj_url,"Remove Milestone","/milestone/?cmd=remove") ."</li>".
                "</ul>";
        break;

      case ENUM_ProjMode::MilestoneEdit:   // "milestone-edit":
        /* Editing Milestone */
        /* :: <blank> :: */
        $code = "";
        break;

      case ENUM_ProjMode::MilestoneRemove:
        /* Removing Milestone */
        /* :: <blank> :: */
        $code = "";
        break;


      case ENUM_ProjMode::WikiView:   // "wiki":
        /* View wiki page */
        /* :: "New Page" | "Edit Page" (| "Remove Page") <-- if not main :: */

        if(strlen($wiki_page) > 0)
          $wiki_page.="/";   // add slash if we on a page
        $code = "<ul>" .
                "<li class='first'>". AddLink($proj_url, "New Page", "/wiki/?cmd=new") ."</li>".
                "<li> " .             AddLink($proj_url, "Edit Page",  "/wiki/".$wiki_page."?cmd=edit") ."</li>".
                "<li class='last'>".  AddLink($proj_url, "Remove Page","/wiki/".$wiki_page."?cmd=remove") ."</li>".
                "</ul>";
        break;

      case ENUM_ProjMode::WikiEdit:
        $code = "";
        break;
      case ENUM_ProjMode::WikiRemove:
        $code = "";
        break;

      default:
        $code = "";
        break;
    }

    return $code;
  }

  /* ######################################### */
  /* ######################################### */

  /**
   * List all available projects if user has permissions
   * @global mixed $pmtDB
   * @return string HTML Data
   */
  private function Page_ProjectList()
  {
    global $pmtDB;

    /** View
     *  --------------------------------------
     * | {Project-Title}                      |
     * | Tickets: {0}  Bugs: {0}  Tasks: {0}  |
     * | Milestones | Wiki
     * | {Description}                        |
     *  --------------------------------------
     */
    $html = "";
    $projects = array();
    $q = "SELECT `Project_Id`, `Project_Name`, `Project_Description` FROM ".PMT_TBL."PROJECT ".
         "ORDER BY `Project_Name` ASC;";
    $ret = $pmtDB->Query($q);
    if($pmtDB->NumRows($ret))
    {
      $html = "<h1>List of Project</h1>\n";
      $html .= "<div><ul>\n";
      while($prj = $pmtDB->FetchArray($ret))
      {
        $projects[] = $prj;         // Place project info into array
        $html .= "<li>" .
                  $prj["Project_Name"] . "<ul>".
                    "<li><b>ID Number:</b> " . $prj["Project_Id"] . "</li>" .
                    "<li><b>Description:</b> " .$prj["Project_Description"] . "</li></ul>" .
                  "</li>\n";   // test output

        /*
         * Array ( [0] => 1
         *         [Project_Id] => 1
         *         [1] => testProject
         *         [Project_Name] => testProject
         *         [2] => This is a test project. There is nothing of any importance here.
         *         [Project_Description] => This is a test project. There is nothing of any importance here. )
         */
      }
      $html .= "</ul></div>\n";
    }
    else
    {
      $html .= "<h1>Projects</h1>";
      $html .= "<p>There are no projects to view</p>";
      $html .= "<p>If permissions allow, you may ".
                AddLink(self::MODULE, "Create", "?cmd=new").
                " a new project</p>";
    }
    return $html;
  }




  private function Page_UserOffline()
  {

    $html = <<<"EOT"
        <h1>Projects</h1>
        <p>
          This system is still under heavy development and is not
          ready for live action use by any means. Soon enough you will
          get to see what the future holds.  As the project develops the
          user and engineering documentation will mature along with it.</p>
        <p>Sit tight and enjoy the ride!</p>
        <p>&nbsp;</p>
        <p>- Xeno Innovations, Inc. -</p>
        <p></p>

        <h2>Navagation Test</h2>
        <p>Test out the mini-bar!</p>
        <p>
          Becareful, this is not completely accurate and you will get
          unexpected results if you click out of sync or out of order.
          The <b>MiniBarRight</b> uses a completely different algorithm!
        </p>
EOT;

      $html .="  <ul>";
        //<li>View Project: " . "<a href='p/xrehab/'>xRehab</a></li>";
      $html .="   <li>View Project: " . AddLink(self::MODULE, "xRehab", "/xrehab") . "</li>";
      $html .="  <li>Milestones";
      $html .="    <ul>";
      //    <li>New: " . "<a href='p/xrehab/milestone/?cmd=new'>test</a></li>";
      //    <li>Edit: ". "<a href='p/xrehab/milestone/?cmd=edit'>test</a></li>";
      //    <li>Remove: ". "<a href='p/xrehab/milestone/?cmd=remove'>test</a></li>";
      $html .="  <li>New: " .    AddLink($this->_PROJ_Name, "Test", "/milestone?cmd=new") . "</li>";
      $html .="  <li>Edit: ".    AddLink($this->_PROJ_Name, "Test", "/milestone?cmd=edit") . "</li>";
      $html .="  <li>Remove: ".  AddLink($this->_PROJ_Name, "Test", "/milestone?cmd=remove") . "</li>";
      $html .="  </ul></li>";
      //<li>Wiki Page (new, edit, remove): " . "<a href='p/xrehab/wiki'>test</a></li>";
      $html .="<li>Wiki Page";
      $html .="  <ul>";
      $html .="  <li>Page - Main: ".    AddLink($this->_PROJ_Name, "Main", "/wiki") . "</li>";
      $html .="  <li>Page - Test: ".    AddLink($this->_PROJ_Name, "Main", "/wiki/test") . "</li>";
      $html .="  <li>New: ".     AddLink($this->_PROJ_Name, "Test", "/wiki?cmd=new") . "</li>";
      $html .="  <li>Edit: ".    AddLink($this->_PROJ_Name, "Test", "/wiki?cmd=edit") . "</li>";
      $html .="  <li>Remove: ".  AddLink($this->_PROJ_Name, "Test", "/wiki?cmd=remove") . "</li>";
      $html .="  </ul></li>";
      $html .="</ul>";

      return $html;
  }

}

?>
