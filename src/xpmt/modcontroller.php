
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
 *  2012-0703 * Cleaned up tab spacing for easy HTML code view.
 *  2012-0619 + Added alternative module load method. This is used until the whole LoadModule() procedure
 *              can be rewritten to handle dynamic loading.
 *              New Method: Module subfolder is same as mod name
 *  2012-0402 + Added ModRedirect($mod) to easily convert from long>short and short>long module names.
 *            * Fixed MakeToolbar()
 */

/**
 * Redirect module to a shorter URL ("project" >> "p")
 * @param string $module
 */
function ModRedirect($module)
{
  $rename = $module;
  switch($module)
  {
    case "project":
      $rename = "p";
      break;

    case "p":
      $rename = "project";
      break;
  }
  return $rename;
}

/**
 * Load Module - Node extended
 * @param string $module Template Node (project, user, customer, ..)
 * @param array $arrParams Module Parameter Array
 */
function LoadModule($module, $arrParams)
{
  /** [Change Log]
   *  2012-0402 + Added convert of $module="p" >> "project".
   *              a better method will be needed!
   *            + Using ModRedirect() for the "project" >> "p" convert.
   */

  /* TODO:
   * [ ] Use use User setting first, then check System Setting
   * [ ] Change this function to check the DB for where the module is installed to (PMT_MODULE)
   *     this will be used for the more dynamic approach of En/Disabling installed modules
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

  if ($module == "p")
    $module = "project";

  /* ** Theme ** */
  $theme = GetSetting("theme");
  if ($theme== "")
    $theme="default";

  if (file_exists(PMT_PATH . "xpmt/themes/" . $theme))
  { // use custom theme
    $skin_path = PMT_PATH . "xpmt/themes/" . $theme . "/";
    $relpath = "xpmt/themes/" . $theme . "/";
  }else{
    // using default
    $skin_path = PMT_PATH . "xpmt/themes/default/";
    $relpath = "xpmt/themes/default/";
  }

  // Set DEFAULT skin to MAIN.PHP - check LATER if module has custom skin
  $skin_file = "main.php";
  $page = $skin_path . $skin_file;



  /* ** Prepare Module ** */

  if (file_exists(PMT_PATH."xpmt/modules/".$module.".php"))
    require(PMT_PATH."xpmt/modules/".$module.".php");

  // Alternative method (Module subfolder is same as mod name)
  elseif (file_exists(PMT_PATH."xpmt/modules/".$module."/".$module.".php"))
    require(PMT_PATH."xpmt/modules/".$module."/".$module.".php");

  else
  {
    $module="dashboard";
    //require(PMT_PATH."xpmt/modules/dashboard.php");              // Option A
    header("Location: " . $pmtConf["general"]["base_url"] );    // Option B
    exit;
  }

  /// Skin Part 2 ============
  // check if module has custom skin. (i.e. main, project, product, etc.)
  if (file_exists($skin_path . $module . ".php"))
  {
    $skin_file = $module . ".php";
    $page = $skin_path . $skin_file;
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
  $PAGE_METABAR = GenerateMetabar(ModRedirect($module));   // generated below
  $PAGE_MINILEFT = $obj->MiniBarLeft();
  $PAGE_MINIRIGHT = $obj->MiniBarRight();

  // Main Toolbar
  if($obj->Toolbar() == "")
        $PAGE_TOOLBAR = MakeToolbar(ModRedirect($module)); // Generate default toolbar
        //$PAGE_TOOLBAR = MakeToolbar($module); // Generate default toolbar
  else  $PAGE_TOOLBAR = $obj->Toolbar();      // Module generates toolbar

  $PAGE_HTDATA = $obj->PageData();

  require($page);

}

function AddLI($buff, $class="")
{
  if($class=="")
        $c = "";
  else  $c = ' class="'.$class.'"';
  return "<li$c>" . $buff . "</li>";
}

  /**
   * Generates simple link based upon PMT's installed location
   * @param string $module Module name to go to
   * @param string $text Link caption
   * @param string $extLink Link URL Extended
   * @return string
   */
  function AddLink($module, $text, $extLink = "")
  {
    global $pmtConf;
    return '<a href="'. $pmtConf["general"]["base_url"].$module.$extLink.'">'.$text.'</a>';
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
   * 3) "About xenoPMT"
   * 4) "Log off"
   */

  global $user;

  /* I) Get online status & user name */
  //$user_online = true;
  //$user_name = "fuct";

  //if ($user->UserInfo["Online"]  == true)


  /* II) Generate page */
  $t = "        ";
  //$ret = $t . "<ul>" . PHP_EOL;
  $ret = $t . "<ul>" . PHP_EOL;
  if ($user->Online)
  {
    $ret .= $t . "  ";
    // ONLINE
    // Login / Welcome, %USER%.   <-- Welcome screen take to user stats page
    //$ret .= AddLI('Welcome, <a href="/user" alt="User\'s Dashboard">' .$user->UserInfo["username"] . '</a>', "first");
    $ret .= AddLI('Welcome, <a href="/user" alt="User\'s Dashboard">' .$user->UserInfo["Display_Name"] . '</a>', "first");
    // $ret .= AddLI('Welcome, <a href="/user" alt="User\'s Dashboard">' .$user->UserInfo["User_Name"] . '</a>', "first");
    $ret .= AddLI("Preferences");         //
    $ret .= AddLI("About xenoPMT");       // "pmt/wiki/about"
    $ret .= AddLI(AddLink("user", "Logoff", "?cmd=logoff" ), "last");
  }
  else
  {
    // OFFLINE
    $ret .= $t . "  ";
    $ret .= AddLI(AddLink("user", "Login", "?cmd=login" )  );
    $ret .= AddLI("About xenoPMT", "last");

    // * Disabled for now
    //
    //$ret .= AddLI("Login", "first");
    //$ret .= AddLI("Preferences");
    //$ret .= AddLI("About xenoPMT", "last");

  }
    $ret .= PHP_EOL . $t . "</ul>" . PHP_EOL;

  /*
  if(
      isset($conf['general']['authorized_only'])
      && $conf['general']['authorized_only'] == true
      && !$user->loggedin && @$_POST['action'] != 'login'
      && ($uri->seg[0] != 'user' && $uri->seg[1] != 'register'))
  {
      include(template('user/login'));
      exit;
  }
  */




  return $ret;
}


function MakeToolbar($module)
{
  // pmtDebug("Module: " . $module);
  /* Steps:
  * 1) Get user profile permissions to see what items we can draw on the screen.
  * 2) Generate toolbar
  */

  /* Step 1 - Get user permissions */
  // This should possibly be handled by the module itself considering
  // that the modules will be dynamic plug-ins in future versions.

  // (currently not in use)

  

  /* Step 2 - Generate Toolbar */
  // List of all the available modules
  // ** This should be pulled from DB depending on user/group
  //    permissions & settings!!
  $arrAvailMods = array(
        // Module       Display
        "dashboard" => "Dashboard",
        //"project"   => "Projects",
        "p"         => "Projects",
        "kb"        => "Knowledge Base",
        "ticket"    => "Tickets",     /* "ticket" => array ("Tickets", "+"), */
        "bug"       => "Bugs",
        "task"      => "Tasks",
        "product"   => "Products",
        "customer"  => "Customers",
        "user"      => "Users",
        "admin"     => "Admin"
        );

  if (count($arrAvailMods) == 0)
  { // Looks like a system lockdown just occurred!
    $ret = "";
  }
  else
  {

    $tab = "        ";
    $ret = $tab . "<ul>". PHP_EOL;
    $ndxCount = 0;
    //print (count($a));
    foreach($arrAvailMods as $key => $value)
    { //print ("key: $key, Obj: $value <br />");

      $ndxCount++;
      //if ($tmod[$ndx] == $module)
      if ($key == $module) $active = true; else $active = false;

      if ($ndxCount == 1)
        if($active) $cls = ' class="first active"'; else $cls = ' class="first"';
      elseif($ndxCount == count($arrAvailMods))
        $cls = ' class="last"';
      else
        if ($key==$module) $cls = ' class="active"'; else $cls = '';
        //if ($key=="project") $cls = ' class="active"'; else $cls = '';

      $ret .= $tab .
              "  <li" . $cls. ">" .
              AddLink($key, $value) .
              "</li>" . PHP_EOL;
    }
    $ret .= $tab . "</ul>". PHP_EOL;
    //pmtDebug("disp: " . $ret);
  }

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
