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
 *  2012-0404 - Removed usage of makeLink & using AddLink from pmt-functions.php
 *  2012-0402 - Bypassed GenerateToolbar(). Return "" and use default.
 *            + Added test front page for links
 *  2012-0328 * fixed "makeLink" to include $pmtConf
 */


// Makeshift Enum
class ENUM_ProjMode   //interface ENUM_ProjMode
{
  const ListAll = 0;          // ""
  const ProjectView = 1;      // "project-view"   - View details (all milestones? or main wiki?)
  const MilestoneView = 2;    // "milestone-view"
  const MilestoneEdit = 3;    // "milestone-edit"
  const Wiki = 4;             // "wiki"           - View wiki page
  const WikiEdit = 5;         // "wiki-edit"      - Edit wiki page
  const WikiRemove = 6;       // "wiki-remove"    - Remove wiki page
}

/**
 * Used along with "$uri->seg[2]"
 */
class ENUM_ProjSegment
{
  const Milestone = "milestone";
  const Wiki = "wiki";
}


//var $today = DaysOfWeek::Sunday;


require ("pmtModule.php");
class project implements pmtModule
{
  const MODULE = "p";
  //private $MODULE = "project";
  //private $MODULE = "p";

  private $_title;        // Title of the screen
  private $_toolbar;      // HTML generated toolbar according to location
  private $_minileft;     // mini toolbar (left)
  private $_miniright;    // mini toolbar (right)
  private $_pagedata;     // Main page data

  private $_projSegment;  // Base project url ("p" or "p/test-proj"

  private $_MODE;         // ENUM_ProjMode from parser
  private $_WikiPage;     // name of wikipage to display/add/edit/remove

  //function __construct($uriPath = "")
  function __construct()
  {
    global $uri;

    if (count($uri->seg)>1)
          $this->_projSegment = self::MODULE."/".$uri->seg[1];
    else  $this->_projSegment = self::MODULE;

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


  private function ParseData()
  {
    /**
     * 1) Get user permission to view Project path or utalize the command passed
     * 2) Generate depending upon [$projMode]
     *    * Project Listing   (pmt/project/)
     *      * Generate: (List Projects, Create Project
     *    * Milestone Listing (pmt/project/milestones)
     */

    // print(ENUM_ProjMode::ListAll);

    global $pmtConf;
    global $uri;
    //$url = $pmtConf["general"]["base_url"] . self::MODULE;

    /** Section 2 - Get Project_Mode **/
    if (count($uri->seg)>1)
          $proj_url = self::MODULE."/".$uri->seg[1];
    else  $proj_url = self::MODULE;


    //$proj_url = self::MODULE;  // Default to base URL
    $proj_mode = "";              // Default to base URL
    $wiki_page = "";              // Default wiki page
    switch (count($uri->seg))
    {
      case 1: // List all
        $proj_mode = ENUM_ProjMode::ListAll;                // "";

        /**
         * Handle
          if($_GET["cmd"] == "new")
         */

        break;

      case 2: // Project View
        $proj_mode =  ENUM_ProjMode::ProjectView;           // "project-view";
        /**
         * Handle
          if($_GET["cmd"] == "new")                         // "New Milestone", "New Wiki Page"
          if($_GET["cmd"] == "edit")                        // "Edit Wiki Page"
          if($_GET["cmd"] == "remove")                      // "Remove Project"
         */
        break;

      case 3: // "Milestone" / "Wiki Page" Viewer

        if ($uri->seg[2] == ENUM_ProjSegment::Milestone)    // "milestone")
        {
          // Show Milestone MAIN Toolbar
          /**
          * Handle
            if($_GET["cmd"] == "edit")                      // "Edit Milestone"
            if($_GET["cmd"] == "remove")                    // "Remove Milestone"
          */
          $proj_mode = ENUM_ProjMode::MilestoneView;        // "milestone-view";

          if($_GET["cmd"] == "edit")
            $proj_mode = ENUM_ProjMode::MilestoneView;      // "milestone-edit";
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
          $proj_mode = ENUM_ProjMode::Wiki;                 // "wiki";
        }
        else
        {
          // Default to base URL
        }
        break;

      case 4: // Wiki Toolbar --- OR --- (Milestone Edit <-- no?)

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
          $proj_mode = ENUM_ProjMode::Wiki;                 // "wiki";
          $wiki_page = $uri->seg[3];          // USED IN::  "/wiki/" ./*page*/ "?cmd=edit") ."</li>".
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

  }


  private function GeneratePage()
  {
    /**
     * Depending on usr permissions settings, list all projects available to
     * the user logged in.
     */
    global $user;

    if($user->online == false)
    {
      $html = $this->Page_Offline();
    }else{
      $html = $this->Page_ListProjects();

    }
    return $html;
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
          // Module       Display
          "dashboard" => "Dashboard",
          "project"   => "Projects",
          //"p"   => "Projects",
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
        //if ($key=="p")$cls = ' class="active"'; else $cls = '';
        if ($key=="project")$cls = ' class="active"'; else $cls = '';

      $ret .= $tab .
              "  <li" . $cls. ">" .
              //AddLink($key, $value) .
              AddLink($key, $value) .
              "</li>" . PHP_EOL;

    }
    $ret .= $tab . "</ul>". PHP_EOL;
    //pmtDebug("disp: " . $ret);
    return $ret;
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


    //$proj_url = self::MODULE;  // Default to base URL
    $proj_mode = "";              // Default to base URL
    $wiki_page = "";              // Default wiki page
    switch (count($uri->seg))
    {
      case 1: // List all
        $proj_mode = "";

        /**
         * Handle
          if($_GET["cmd"] == "new")
         */

        break;

      case 2: // Project View
        $proj_mode = "project-view";
        /**
         * Handle
          if($_GET["cmd"] == "new")     // "New Milestone", "New Wiki Page"
          if($_GET["cmd"] == "edit")    // "Edit Wiki Page"
          if($_GET["cmd"] == "remove")  // "Remove Project"
         */
        break;

      case 3: // "Milestone" / "Wiki Page" Viewer

        if ($uri->seg[2] == "milestone")
        {
          // Show Milestone MAIN Toolbar
          /**
          * Handle
            if($_GET["cmd"] == "edit")    // "Edit Milestone"
            if($_GET["cmd"] == "remove")  // "Remove Milestone"
          */
          $proj_mode = "milestone-view";

          if($_GET["cmd"] == "edit")
            $proj_mode = "milestone-edit";
        }
        elseif ($uri->seg[2] == "wiki")
        {
          // Show MAIN WIKI toolbar
          /**
          * Handle
            if($_GET["cmd"] == "new")     // "New Wiki Page"
            if($_GET["cmd"] == "edit")    // "Edit Wiki Page"
            if($_GET["cmd"] == "remove")  // "Remove Wiki Page"
          */
          $proj_mode = "wiki";
        }
        else
        {
            // Default to base URL
        }
        break;

      case 4: // Wiki Toolbar --- OR --- (Milestone Edit <-- no?)

        // Are we viewing wiki?
        if ($uri->seg[2] == "wiki")
        {
          // Show MAIN WIKI toolbar
          /**
          * Handle
            if($_GET["cmd"] == "new")     // "New Wiki Page"
            if($_GET["cmd"] == "edit")    // "Edit Wiki Page"
            if($_GET["cmd"] == "remove")  // "Remove Wiki Page"
          */
          $proj_mode = "wiki";
          $wiki_page = $uri->seg[3];  // USED IN::  "/wiki/" ./*page*/ "?cmd=edit") ."</li>".
        }
        else
        {
          //$proj_mode = "milestone-edit";
          $proj_mode = "";
        }

        break;

    }

    /** Section 3 - Generate depending on Project_Mode **/

    // $wiki_page = "";  // USED IN::  "/wiki/" ./*page*/ "?cmd=edit") ."</li>".
    switch($proj_mode)
    {
      case "":
        /* Viewing ALL Available Projects */
        /* :: "Create Project" :: */
        $code = "<ul>".
                //"<li class='last'>".  AddLink(self::MODULE, "Create Project", "?cmd=new") ."</li>".
                "<li class='last'>".  AddLink(self::MODULE, "Create Project", "?cmd=new") ."</li>".
                "</ul>";
        break;

      case "project-view":
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

      case "milestone-view":
        /* viewing Single Milestone */
        /* :: "Edit Milestone" | "Remove Milestone" :: */
        $code = "<ul>" .
                "<li class='first'>". AddLink($proj_url,"Edit Milestone","/milestone/?cmd=edit") ."</li>".
                "<li class='last'>".  AddLink($proj_url,"Remove Milestone","/milestone/?cmd=remove") ."</li>".
                "</ul>";
        break;

      case "milestone-edit":
        /* viewing Single Milestone */
        /* :: <blank> :: */
        $code = "";
        break;

      case "wiki":
        /* View wiki page */
        /* :: "New Page" | "Edit Page" (| "Remove Page") <-- if not main :: */

        if(strlen($wiki_page) > 0) $wiki_page.="/";   // add slash if we on a page
        $code = "<ul>" .
                "<li class='first'>". AddLink($proj_url, "New Page", "/wiki/?cmd=new") ."</li>".
                "<li> " .             AddLink($proj_url, "Edit Page",  "/wiki/".$wiki_page."?cmd=edit") ."</li>".
                "<li class='last'>".  AddLink($proj_url, "Remove Page","/wiki/".$wiki_page."?cmd=remove") ."</li>".
                "</ul>";

        break;

    }

    return $code;
  }

  private function GenerateToolbar_default()
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
        if ($key==self::MODULE)$cls = ' class="active"'; else $cls = '';

      $ret .= $tab .
              "  <li" . $cls. ">" .
              AddLink($key, $value) .
              "</li>" . PHP_EOL;

    }
    $ret .= $tab . "</ul>". PHP_EOL;
    //pmtDebug("disp: " . $ret);
    return $ret;
  }


  private function Page_ListProjects()
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
      while($prj = $pmtDB->FetchArray($ret))
      {
        $projects[] = $prj;         // Place project info into array
        $html .= "<hr />" . $prj;   // test output
      }
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

  private function Page_Offline()
  {
      $html =  "<h1>Projects</h1>";
      $html .= "<p>This system is still under heavy development and is not ";
      $html .= "ready for live action use by any means. Soon enough you will ";
      $html .= "get to see what the future holds.  As the project develops the ";
      $html .= "user and engineering documentation will mature along with it.</p>";
      $html .= "<p>Sit tight and enjoy the ride!</p>";
      $html .= "<p>&nbsp;</p>";
      $html .= "<p>- Xeno Innovations, Inc. -</p>";
      $html .= "<p></p>";

      $html .= "<h2>Navagation Test</h2>";
      $html .= "<p>Test out the mini-bar!</p>";
      $html .= "<p>Becareful, this is not completely accurate and you will get ";
      $html .= "unexpected results if you click out of sync or out of order. ";
      $html .= "The <b>MiniBarRight</b> uses a completely different algorithm!";
      $html .= "</p>";

      $html .= "<ul>";
      //$html .= "<li>View Project: " . "<a href='p/xrehab/'>xRehab</a></li>";
      $html .= "<li>View Project: " . AddLink(self::MODULE, "xRehab", "/xrehab") . "</li>";
      $html .= "<li>Milestones";
      $html .= "  <ul>";

      //$html .= "    <li>New: " . "<a href='p/xrehab/milestone/?cmd=new'>test</a></li>";
      //$html .= "    <li>Edit: ". "<a href='p/xrehab/milestone/?cmd=edit'>test</a></li>";
      //$html .= "    <li>Remove: ". "<a href='p/xrehab/milestone/?cmd=remove'>test</a></li>";
      $html .= "  <li>New: " .    AddLink($this->_projSegment, "Test", "/milestone?cmd=new") . "</li>";
      $html .= "  <li>Edit: ".    AddLink($this->_projSegment, "Test", "/milestone?cmd=edit") . "</li>";
      $html .= "  <li>Remove: ".  AddLink($this->_projSegment, "Test", "/milestone?cmd=remove") . "</li>";
      $html .= "  </ul></li>";
      //$html .= "<li>Wiki Page (new, edit, remove): " . "<a href='p/xrehab/wiki'>test</a></li>";
      $html .= "<li>Wiki Page";
      $html .= "  <ul>";
      $html .= "  <li>Page - Main: ".    AddLink($this->_projSegment, "Main", "/wiki") . "</li>";
      $html .= "  <li>Page - Test: ".    AddLink($this->_projSegment, "Main", "/wiki/test") . "</li>";
      $html .= "  <li>New: ".     AddLink($this->_projSegment, "Test", "/wiki?cmd=new") . "</li>";
      $html .= "  <li>Edit: ".    AddLink($this->_projSegment, "Test", "/wiki?cmd=edit") . "</li>";
      $html .= "  <li>Remove: ".  AddLink($this->_projSegment, "Test", "/wiki?cmd=remove") . "</li>";
      $html .= "  </ul></li>";
      $html .= "</ul>";
      return $html;
  }

}

?>
