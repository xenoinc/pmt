<?php

/** * *********************************************************
 * Copyright 2012 (C) Xeno Innovations, Inc.
 * ALL RIGHTS RESERVED
 * @Author:       Damian Suess
 * Document:      xenopmt
 * Created Date:  Dec 4, 2012
 * Status:        pre-alpha {unstable/pre-alpha/alpha/beta/stable}
 * Description:
 *  xenoPMT Core Class
 *
 *  Its still undecided if this should be static or public
 * Change Log:
 *  2013-0130 * GetModuleHeaderFromURN() fixed logic - we weren't using the $urn param before
 */
class xenoPMT
{
  /* Private vars */


  /* Public Vars */

  function __construct() {

  }

  function __destruct() {

  }


  /*
   * Load module via UUID
   */
  public static function LoadModule($uuid)
  {
    /*
     * To Do:
     * [ ] Step 1 - Properly handle "Moodule not found"
     *            - In this case give a raw theme with no background ($htdata="");
     *              and some way to access "Install Module NOW~!" link on the page :)
     *              - Here you can set $module (class) from $xpmtModule[] and provide
     *                access to the installer page
     *
     * [ ] Step 3 - Load module data
     * [ ] Step 4 - Provide temp table generate from (modController) until
     *              the toolbar can be created by AdminPlugin
     *
     */

    // debug ($uuid);

    // include them all just in case
    global $xpmtModule, $xpmtCore, $xpmtPage, $xpmtConf, $pmtDB;

    // $theme       - theme :: System theme name to use (PMT_DATA..XI_CORE_SETTINGS.Setting = "theme")
    // $skin_path   - theme :: Full physical path to theme directory
    // $relpath     - theme :: Relative (shortened) physical path to theme directory
    // $skin_file   - theme :: Base theme file (main.php)
    // $page        - theme :: Full path to MAIN.PHP ($skin_path + $skin_file)
    // $module      - module :: Module classname
    // $obj         - module :: Object loaded from classname ($module)


    // step 1 - Search for registered module via UUID  { $module = GetClassFromUUID($uuid);
    //        + do this via SQL
    // step 2 - Skin Part 1 - Set theme path
    // step 3 - Check if module has custom skin { file_exists($skin_path . $module . ".php")  ** deprecated!!, use CSS rules and STYLE inject
    // step 4 - Initialize module class!   { $obj = new $module(); }
    // step 5 - Setup $xpmtPage[""] properties from $obj->...
    // step 6 - REQUIRE_ONCE ($page)  -  Actually use the theme & display it



    /****************************
     *  Step 1 - Prepare Module *
     *
     * This must set the {$module} variable
     *
     ****************************/
    $_uuid = $pmtDB->FixString($uuid);
    $_sql = "SELECT `Module_Class`, `Module_Path` FROM {$xpmtConf["db"]["prefix"]}CORE_MODULE WHERE Module_UUID='{$_uuid}' LIMIT 1;";

    $tmpArr = $pmtDB->Query( $_sql);
    $ret = $pmtDB->FetchArray($tmpArr);
    if ($ret == false)     // if ($ret === false)   ** use the regular not EXACT just in case **
    {
      // Module not found
      // $htdata = "Module not found";

      pmtDebug("xenoPMT::LoadModule() - Step1 - Module UUID not found");
      $module = "";
      $xpmtPage["htdata"] = "Module not found";

    }
    else
    {
      // Load module from direct pat
      if (file_exists($ret["Module_Path"]))
      {
        require($ret["Module_Path"]);
        $module = $ret["Module_Class"];
      }
      // elseif (... )
      // { require module path from $xpmtModule[][];  /* as a fail safe */ }
      else
      {

        pmtDebug("xenoPMT::LoadModule() - Step1 - Module UUID Found but path is missing");

        // default to base page.. but what if dashboard is missing or errored ?!
        header("Location: " . $xpmtConf["general"]["base_url"] );    // Option B
        exit;
      }
    }



    /**********************
     * Step 2 - Get theme *
     **********************/
    // ToDo - Move this into its own function to be handled by ParseAndLoad not the module??
    /* Step 2.1 */
    $theme = GetSetting("theme");
    if ($theme == "")
      $theme = "default";
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
    pmtDebug("PagePath1: $page");

    /* Step 2.2 */
    // check if module has custom skin. (i.e. main, project, product, etc.)
    if (file_exists($skin_path . $module . ".php"))
    {
      $skin_file = $module . ".php";
      $page = $skin_path . $skin_file;
      pmtDebug("PagePath2: $page");
    }



    /****************************************
     * Step 3 - Setup $xpmtPage[] variables *
     ****************************************/

    // Most of these settings are being set/modified from within the modules on the fly
    // so there is no need to mess with most of them here. Lets safely access the module
    if ($module != null)
    {
      $obj = new module();
      $xpmtPage["icon"]       = "";                           // Path to Icon file
      $xpmtPage["title"]      = "";                           // Page Title
      $xpmtPage["ex_header"]  = "";                           // Extra Header Information
      $xpmtPage["logo"]       = "";                           // Site image path
      $xpmtPage["metabar"]    = "";                           // User (login/usr-pref)/settings/logout/about
      $xpmtPage["toolbar"]    = "";                           // Main toolbar
      $xpmtPage["minileft"]   = $obj->MiniBarLeft();          // Mini-bar Left aligned (bread crumbs)
      $xpmtPage["miniright"]  = $obj->MiniBarRight();         // Mini-bar Right aligned (module node options)
      $xpmtPage["htdata"]     = $obj->PageData();             // Main page html data
      $xpmtPage["path"]       = "";                           // Relative path to theme currently in use
      $xpmtPage["footer"]     = "";                           // Footer
      require($page);
    }
    else
    {
      // Added 2013-0130
      // There was an issue loading the module
      require($page);
    }
  }



  /**
   * Get module header array from UUID input
   *
   * @version v0.0.1
   * @since xenpPMT Core-0.0.5
   * @global array $xpmtModule  List of modules enabled via Config.php
   * @param string $uuid
   * @return array Module Header Data. NULL if not found
   */
  public static function GetModuleHeaderFromUUID($uuid)
  {
    global $xpmtModule;     // Pull back all config file registered modules

    $retModData = null;     // Return array of module data
    $found = false;         // Check for duplicates

    foreach ($xpmtModule["info"] as $mod)
    {
      //$ret = print_r($mod, true);
      //debug($ret);
      if ($mod["uuid"] == $uuid)
      {
        if ($found == false)
        {
          $found = true;
          $retModData = $mod;
        }
        else
        { // Should we fail since there are two??   YES!!
          $retModData = null;
        }
      }
    }

    return $retModData;
  }

  /**
   * Get module header array from URN
   *
   * @example
   *    $xenoPMT::$GetModuleHeaderFromURN("uuid");  // (customer, admin, user, uuid)
   *
   * @version v0.0.5
   * @since xenpPMT Core-0.0.5 (2012-1204)
   * @global  array $xpmtModule  List of modules enabled via Config.php
   * @param   string $urn         Uniform Resource Name
   * @param   boolean $matchFound
   * @return  array Module Header Data. NULL if not found
   */

  public static function GetModuleHeaderFromURN($urn, &$matchFound)
  {
    global $xpmtModule, $xpmtCore;


    $matchFound = false;    // Did we find a module match?
    $modHeader = array();      // Prepare a blank Module header

    foreach( $xpmtModule["info"] as $ndx => $tmpModHeader)
    {
      // fixed 2013-0130  + we weren't using the $urn param
      // if ($xpmtCore["uri"]->Count > 0 && $xpmtCore["uri"]->Segment(0) == $tmpModHeader["urn"])
      if ($urn == $tmpModHeader["urn"])
      {
        $matchFound = true;   // We found a match!
        $modHeader = $tmpModHeader;   // Use this module header!
        break;
      }
    }

    return $modHeader;
  }

  /**
   * Include (user/core) library if not already included
   *
   * @version v0.0.0
   * @since v0.0.7
   * @param string $libraryName
   * @return boolean true=success, false=failure to load
   */
  public static function lib_include($libraryName)
  {
    return false;
  }
  /**
   * List of core/user xpmtLibaries already included
   *
   * @version v0.0.0
   * @since v0.0.7
   * @since xenpPMT Core-0.0.5
   * @var array
   */
  private $libIncluded = array();

  /**
   * Is xpmtLibrary enabled
   * <p>Loops through $this->libIncluded[] to see if it's there</p>
   *
   * @version v0.0.0
   * @since v0.0.7
   * @param type $libName
   * @return boolean true=enabled, fales=not enabled
   */
  public static function lib_enabled($libName) { return false; }

  /**
   * Searches through Core and User folder to see if libary is available
   * to be included.
   *
   * @version v0.0.0
   * @since v0.0.7
   * @param type $libName
   * @return boolean true=found false=not found or error
   */
  public static function lib_available($libName) {return false; }

}

?>
