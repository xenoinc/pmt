<?php

/* * **********************************************************
 * Copyright 2012 (C) Xeno Innovations, Inc.
 * ALL RIGHTS RESERVED
 * Author:       Damian Suess
 * Document:     modcontroller.php
 * Created Date: Mar 22, 2012
 *
 * Description:
 *  Handles as the primary controller for the modules
 *  and loading of skins
 *
 * Components (order of appearance)
 *  $PAGE_TITLE     - Page title
 *  $PAGE_LOGO      - Logo image
 *  $PAGE_METABAR   - Login (profile), preferences, logoff
 *  $PAGE_TOOLBAR   - Main toolbar
 *  $PAGE_HTDATA    - Main page data
 *  $PAGE_PATH      - Relative path to theme being used
 *
 * Change Log:
 *
 */

/**
 * Load Module - Node extended
 * @param string $module Template Node (project, user, customer, ..)
 * @param array $arrParams Module Parameter Array
 */
function LoadModule($module, $arrParams)
{
  /* TODO:
   * [ ] Use use User setting first, then check System Setting
   *
   */

  global $PAGE_TITLE;     // Page title
  global $PAGE_LOGO;      // Site image path  ** not used yet.
  global $PAGE_METABAR;   // User (login/usr-pref)/settings/logout/about
  global $PAGE_TOOLBAR;   // Main toolbar
  global $PAGE_MINILEFT;  // Mini-bar Left aligned (bread crumbs)
  global $PAGE_MINIRIGHT; // Mini-bar Right aligned (module node options)
  global $PAGE_HTDATA;    // Main page html data
  global $PAGE_PATH;      // Relative path to theme currently in use

  global $pmtConf;
  global $uri;
  // if (count($arrParams) == 0)

  /* ** Theme ** */
  $theme = GetSetting("theme");
  if ($theme== "")
    $theme="default";

  if (file_exists(PMT_PATH . "lib/themes/" . $theme))
  { // use custom theme
    $skin_path = PMT_PATH . "lib/themes/" . $theme . "/";
    $relpath = "lib/themes/" . $theme . "/";
  }else{
    // using default
    $skin_path = PMT_PATH . "lib/themes/default/";
    $relpath = "lib/themes/default/";
  }
  // check if module has custom skin. (i.e. main, project, product, etc.)
  $skin_file = "main.php";
  $page = $skin_path . $skin_file;


  /* ** Prepare Module ** */
  if (file_exists(PMT_PATH."lib/modules/".$module.".php"))
    require(PMT_PATH."lib/modules/".$module.".php");
  else
  {
    $module="dashboard";
    //require(PMT_PATH."lib/modules/dashboard.php");              // Option A
    header("Location: " . $pmtConf["general"]["base_url"] );    // Option B
    exit;
  }

  //pmtDebug( "mod: '" .$module .  "' relPath: " . $relpath);
  //pmtDebug( "relPath: " . $relpath);
  //pmtDebug( "page: " . $page);
  //pmtDebug( "skin_path: " . $skin_path);
  //pmtDebug( "skin_file: " . $skin_file);

  $obj = new $module();
  $PAGE_PATH = $pmtConf["general"]["base_url"] . $relpath;
  //$PAGE_PATH = $relpath;      // OLD METHOD
  //$PAGE_PATH = $skin_path;    // NOOO
  $PAGE_TITLE = $obj->Title();
  $PAGE_METABAR = GenerateMetabar($module);   // generated below
  $PAGE_MINILEFT = $obj->MiniBarLeft();
  $PAGE_MINIRIGHT = $obj->MiniBarRight();

  // Main Toolbar
  if($obj->Toolbar() == "")
        $PAGE_TOOLBAR = MakeToolbar($module); // Generate default toolbar
  else  $PAGE_TOOLBAR = $obj->Toolbar();      // Module generates toolbar

  $PAGE_HTDATA = $obj->PageData();

  require($page);

}


function GenerateMetabar($module)
{
  /* TO DO:
   * i)  Get user logged in / off
   * II) Generate accordingly
   * ----------------------
   * Metabar
   * 1) "Welcome, %USER%"  :: "Login"
   * 2) System "Preferences"
   * 3) "About xenoPMG"
   * 4) "Log off"
   */

  /* I) Get online status & user name */
  $user_online = true;
  $user_name = "fuct";



  /* II) Generate page */
  $t = "        ";
  $ret = $t . "<ul>". PHP_EOL;
  // Login / Welcome, %USER%.   <-- Welcome screen take to user stats page
  if ($user_online)
  {
    $ret .= AddLI('Welcome, <a href="/user" alt="User\'s Dashboard">' .$user_name . '</a>');
  }
  else
  {
    $ret .= AddLI("Login");
  }


  $ret .= "";
  $ret .= "";
  $ret .= "";
  $ret .= $t . "</ul>" . PHP_EOL;


  return $ret;
}

function AddLI($buff)
{
  return "<li>" . $buff . "</li>";
}


function MakeToolbar($module)
{
  pmtDebug("Module: " . $module);
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

    if ($tmod[$ndx] == $module)
      $active = true;
    else
      $active = false;

    $ndxCount++;
    if ($ndxCount == 1)
      if($active) $cls = ' class="first active"'; else $cls = ' class="first"';
    elseif($ndxCount == count($arrAvailMods))
      $cls = ' class="last"';
    else
      if ($key=="project") $cls = ' class="active"'; else $cls = '';

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
 * Generate the page's toolbar
 * @param string $module Module name
 */
function MakeToolbar_OLD($module)
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

    if ($tmod[$ndx] == $module)
      $active = true;
    else
      $active = false;


    // Draw toolbar and set the active item
    if ($ndx == 0)
    {
      if($active) $cls = ' class="first active"'; else $cls = ' class="first"';
    }
    elseif ($ndx == count($tbar)-1)
    {
      if($active) $cls = ' class="last active"'; else $cls = ' class="last"';
    }
    else
    {
      if($active) $cls = ' class="active"'; else $cls = '';
    }
    $ret .= $t . "  <li" . $cls. ">" . $tbar[$ndx] . "</li>" . PHP_EOL;

  }

  $ret .= $t . "</ul>". PHP_EOL;
  //pmtDebug("disp: " . $ret);

  return $ret;
}


?>
