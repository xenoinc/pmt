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
 *  2012-0328 * fixed "makeLink" to include $pmtConf
 */

require ("pmtModule.php");
class project implements pmtModule
{
  //const MODULE = "project";
  private $MODULE = "project";

  private $_title;      // Title of the screen
  private $_toolbar;    // HTML generated toolbar according to location
  private $_minileft;   // mini toolbar (left)
  private $_miniright;  // mini toolbar (right)

  function __construct($uriPath = "")
  {
    $this->_title = "Project " . " - " . "[PMT]";    // "Xeno Tracking System"
    $this->_toolbar = $this->GenerateToolbar();
    $this->_minileft = "";
    $this->_miniright = $this->GenerateMiniRight();  // "&nbsp; (test-right)";
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


  /**
   * Generates simple link
   * @param string $module Module name to go to
   * @param string $text Link caption
   * @param string $extLink Link URL Extended
   * @return string
   */
  private function makeLink($module, $text, $extLink = "")
  {
    global $pmtConf;
    return '<a href="'. $pmtConf["general"]["base_url"].$module.$extLink.'">'.$text.'</a>';
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
    //$url = $pmtConf["general"]["base_url"] . $this->MODULE;



    /** Section 2 - Get Project_Mode **/
    if (count($uri->seg)>1)
          $proj_url = $this->MODULE."/".$uri->seg[1];
    else  $proj_url = $this->MODULE;


    //$proj_url = $this->MODULE;  // Default to base URL
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
                "<li class='last'>".  $this->makeLink($this->MODULE, "Create Project", "?cmd=new") ."</li>".
                "</ul>";
        break;

      case "project-view":
        /* Viewing Single Project */
        /* :: "New Milestone" | "Edit Project" | "Remove Project" :: */

        // ** Cleanup code for the Selected project
        // $url = $this->MODULE."/".$uri->seg[1];
        $code = "<ul>" .
                "<li class='first'>". $this->makeLink($proj_url, "New Milestone", "/milestone/?cmd=new") ."</li>".
                "<li> " .             $this->makeLink($proj_url, "New Page",  "/wiki/?cmd=new") ."</li>".
                "<li> " .             $this->makeLink($proj_url, "Edit Page",  "/wiki/?cmd=edit") ."</li>".
                "<li class='last'>".  $this->makeLink($proj_url, "Remove Project","/?cmd=remove") ."</li>".
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
                "<li class='first'>". $this->makeLink($proj_url,"Edit Milestone","/milestone/?cmd=edit") ."</li>".
                "<li class='last'>".  $this->makeLink($proj_url,"Remove Milestone","/milestone/?cmd=remove") ."</li>".
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
                "<li class='first'>". $this->makeLink($proj_url, "New Page", "/wiki/?cmd=new") ."</li>".
                "<li> " .             $this->makeLink($proj_url, "Edit Page",  "/wiki/".$wiki_page."?cmd=edit") ."</li>".
                "<li class='last'>".  $this->makeLink($proj_url, "Remove Page","/wiki/".$wiki_page."?cmd=remove") ."</li>".
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
}

?>
