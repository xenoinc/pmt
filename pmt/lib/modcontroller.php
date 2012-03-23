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

  global $PAGE_TITLE;
  global $PAGE_TOOLBAR;
  global $PAGE_METABAR;
  global $PAGE_HTDATA;
  global $PAGE_PATH;

  // if (count($arrParams) == 0)

  $theme = GetSetting("theme");
  if ($theme== "") $theme="default";

  if (file_exists(PMT_PATH . "lib/themes/" . $theme))
  { // use custom theme
    $skinpath = PMT_PATH . "lib/themes/" . $theme . "/";
    $relpath = "lib/themes/" . $theme . "/";
  }else{
    // using default
    $skinpath = PMT_PATH . "lib/themes/default/";
    $relpath = "lib/themes/default/";
  }

  // check if module has custom skin.
  // main, project, product, etc..
  $skinfile = "main.php";
  $page = $skinpath . $skinfile;


  // Generate module
  require(PMT_PATH."lib/modules/".$module.".php");

  $obj = new $module();
  $PAGE_TITLE = $obj->Title;
  $PAGE_METABAR = "";
  $PAGE_TOOLBAR = MakeToolbar($module);
  $PAGE_HTDATA = $obj->PageData();
  $PAGE_PATH = $relpath;

  require($page);

}

/**
 * Generate the page's toolbar
 * @param string $module Module name
 */
function MakeToolbar($module)
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
