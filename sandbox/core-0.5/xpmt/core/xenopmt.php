<?php

/**
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
 *
 * ToDo:
 *  [ ] Move ParseAndLoad() to this class! (2014-0129)
 *  [ ] Move theme portion of LoadModule() to LoadTheme() (2014-0129)
 *  [ ] Make use of \xenoPMT\Core\Error::ERROR_TYPE_NONE messages (2014-0129)
 *  [ ] Post BugReport to PHP. PHP fails to catch object creation success/failure {see LoadModule()}
 *  [ ] Complete GetToolbarMain($uuid) to pull from CACHE and DB via UserGroup definitions
 *  [ ] Generate GetToolbarMeta() to display login, preferences, about, logoff
 *  [ ] Complete $xpmtPage[..] feature display
 *  [ ] LoadModule() Return back error message in PAGE["HTDATA"] so that we do not create a
 *      redirect loop (ex: dashboard mod). This requires the use of "LoadTheme()" member
 *
 * Change Log:
 *  2014-0129 * Added note to put ParseAndLoad() here! [djs]
 *            + Added the Error class "\xenoPMT\Core\Error" for proper error handling
 *            + Added code folding
 *  2013-0819 - LoadModule() Removed the loading of page HTML. This should be done in the LoadTheme() method [djs]
 *  2013-0401 + LoadModule() :: Added is module 'Enabled' test
 *  2013-0331 + Added "LoadTheme()" to display the theme [djs]
 *  2013-0205 + Added GetToolbarMain($uuid) to generate toolbars [djs]
 *            + Fixed bugs in LoadModule($uuid) [djs]
 *  2013-0130 * GetModuleHeaderFromURN() fixed logic - we weren't using the $urn param before [djs]
 */

require_once PMT_PATH."xpmt/core2/Error.php";
class xenoPMT
{

  //<editor-fold defaultstate="collapsed" desc="constructor">
  /* Private vars */


  /* Public Vars */

  function __construct() { }

  function __destruct() { }

  //</editor-fold>

  //<editor-fold defaultstate="collapsed" desc="Loading and Displaying of Module ">

  /**
   * ParseAndLoad the site's arguments, load modules/themes and display the site
   */
  public static function ParseAndLoad()
  {
    /* 2014-0129 * MOVE code from "./xpmt/pmt.php" to here */
    
  }

  /**
   * Load module via UUID
   * @param string $uuid    Module's Unique Identifier
   */
  public static function LoadModule($uuid)
  {
    /*
     * To Do:
     *  [ ] Consider returning back error message numbers (2014-0129)
     *      $msg = \xenoPMT\Core\Error\GetMessage($errNum)
     *      ERROR_TYPE_MODULE_BAD_UUID        - Module not found
     *      ERROR_TYPE_MODULE_ACCESS_DENIED   - User doesn't have permissions
     *      ERROR_TYPE_MODULE_DISABLED        - Module not currently enabled
     *      ERROR_TYPE_UNKNOWN                - Unknown error
     *      ERROR_TYPE_NONE                   - No errors
     *  [ ] Step 1
     *      - Properly handle "Moodule not found"
     *      - In this case give a raw theme with no background ($htdata="");
     *        and some way to access "Install Module NOW~!" link on the page :)
     *      - Here you can set $module (class) from $xpmtModule[] and provide
     *        access to the installer page
     *  [ ] Step 3  - Load module data
     *  [ ] Step 4  - Provide temp table generate from (modController) until
     *                the toolbar can be created by AdminPlugin
     *  [ ] Protect against infinite loop in Step 1.
     */

    // debug ($uuid);
    // include them all just in case
    global $xpmtModule, $xpmtCore, $xpmtPage, $xpmtConf, $pmtDB;

    // Setup error handling (added 2014-0129)
    $errRet = \xenoPMT\Core\Error::ERROR_TYPE_NONE;

    /*
      // $theme       - theme :: System theme name to use (PMT_DATA..XI_CORE_SETTINGS.Setting = "theme")
      // $skin_path   - theme :: Full physical path to theme directory
      // $relpath     - theme :: Relative (shortened) physical path to theme directory
      // $skin_file   - theme :: Base theme file (main.php)
      // $page        - theme :: Full path to MAIN.PHP ($skin_path + $skin_file)
      // $module      - module :: Module classname
      // $obj         - module :: Object loaded from classname ($module)
     */

    /* Steps
      // step 1 - Search for registered module via UUID  { $module = GetClassFromUUID($uuid);
      //        + do this via SQL
      // step 2 - Skin Part 1 - Set theme path
      // step 3 - Check if module has custom skin { file_exists($skin_path . $module . ".php")  ** deprecated!!, use CSS rules and STYLE inject
      // step 4 - Initialize module class!   { $obj = new $module(); }
      // step 5 - Setup $xpmtPage[""] properties from $obj->...
      // step 6 - REQUIRE_ONCE ($page)  -  Actually use the theme & display it
     */


    /* ************************************ *
     *  Step 1 - Prepare Module             *
     * This must set the {$module} variable *
     * ************************************ */

    // TODO: Consider removing this step since it's already called from ParseAndLoad() (2014-0129)

    $_uuid = $pmtDB->FixString($uuid);
    $_sql = "SELECT `Module_Class`, `Module_Path`, `Module_Namespace`, `Enabled` " .
            "FROM {$xpmtConf["db"]["prefix"]}CORE_MODULE WHERE Module_UUID='{$_uuid}' LIMIT 1;";

    $tmpArr = $pmtDB->Query($_sql);
    $ret = $pmtDB->FetchArray($tmpArr);
    if ($ret == false)     // if ($ret === false)   ** use the regular not EXACT just in case **
    {
      // Module not found
      pmtDebug("xenoPMT::LoadModule() - Step1 - Module UUID not found");
      $module = "";     // Module class Name
      $moduleNS = "";   // blank module with namespace
      $xpmtPage["htdata"] = "Module not found";
    }
    else
    {
      // Load module from direct pat
      if (file_exists($ret["Module_Path"]))
      {
        /*
          // 2013-0205 * Pathced to include Module_Class.PHP
          //  ... "/" . $ret["Module_Class"] . ".php"
          // This is not a good way to define the main file. Idealy we should pull
          // the "mainfile" from this module's header PHP file.
          //
          // pmtDebug("LoadModule() Module_Path: '$modPth'");
          // if (file_exists(PMT_PATH . "custom/mod/" . $ret["Module_Class"] . ".php")) {}
          // if (file_exists(PMT_PATH . "xpmt/modules/" . $ret["Module_Class"] . ".php")) {}
         */

        // 2013-0401 + Added 'Enabled' test
        if ($ret["Enabled"] == FALSE)
        {
          pmtDebug("Module is not enabled. Display prompt to user");

          // TODO: Fix this - Return back error message as to why we can't load (ERROR_TYPE_MODULE_DISABLED)
          // FOR NOW we will allow it

          $modPth = $ret["Module_Path"] . "/" . $ret["Module_Class"] . ".main.php";
          $module = $ret["Module_Class"];
          $moduleNS = $ret["Module_Namespace"] . "\\" . $module;  // Module Namespace + Class
          require($modPth);
        }
        else
        {
          $modPth = $ret["Module_Path"] . "/" . $ret["Module_Class"] . ".main.php";
          $module = $ret["Module_Class"];
          $moduleNS = $ret["Module_Namespace"] . "\\" . $module;  // Module Namespace + Class
          //pmtDebug("xenoPMT::LoadModule() Module_Path: '$modPth'");
          require($modPth);
        }
      }
      // elseif (... )
      // { require module path from $xpmtModule[][];  /* as a fail safe */ }
      else
      {
        // ToDo: Return error message ERROR_TYPE_MODULE_BAD_UUID
        //
        // Return back error message in PAGE["HTDATA"] so that we do not create a redirect loop (ex: dashboard mod)
        pmtDebug("xenoPMT::LoadModule() - Step1 - Module UUID Found but path is missing. " .
          "Check TBL_CORE_MODULE.Module_Path settings.");

        // default to base page.. but what if dashboard is missing or errored ?! (YES, it will do an infinite loop)
        header("Location: " . $xpmtConf["general"]["base_url"]);    // Option B
        exit;
      }
    }



    /* ****************** *
     * Step 2 - Get theme *
     * ****************** */

    /* ### Start MOVE Marker ### */
    // ToDo - Move this into its own function {LoadTheme()} to be handled by ParseAndLoad not the module??
    // Step 2.1
    $theme = GetSetting("theme");
    if ($theme == "")
      $theme = "default";
    if (file_exists(PMT_PATH . "xpmt/themes/" . $theme))
    { // use custom theme
      $skin_path = PMT_PATH . "xpmt/themes/" . $theme . "/";
      $relpath = "xpmt/themes/" . $theme . "/";                         // Reletive path to theme
    }
    else
    {
      // using default
      $skin_path = PMT_PATH . "xpmt/themes/default/";
      $relpath = "xpmt/themes/default/";
    }

    // Set DEFAULT skin to MAIN.PHP - check LATER if module has custom skin
    $skin_file = "main.php";
    $page = $skin_path . $skin_file;
    pmtDebug("xenoPMT::LoadModule() Theme - Page Path: $page");

    // Step 2.2
    // check if module has custom skin. (i.e. main, project, product, etc.)
    if (file_exists($skin_path . $module . ".php"))
    {
      $skin_file = $module . ".php";
      $page = $skin_path . $skin_file;
      pmtDebug("xenoPMT::LoadModule() Theme - *Custom* Page Path: $page");
    }

    /*   ### END MOVE MARKER */
    //pmtDebug("xenoPMT::LoadMod() Theme Loaded");


    /* ******************************************************* *
     * Step 3 - Execute module and setup $xpmtPage[] variables *
     * ******************************************************* */

    // Most of these settings are being set/modified from within the modules on the fly
    // so there is no need to mess with most of them here. Lets safely access the module
    //if ($module != null)  // removed 2013-0331
    pmtDebug("xenoPMT::LoadModule() - moduleNS: $moduleNS");
    if ($moduleNS != null)
    {
      // TODO - 2013-0205
      //  [ ] PATH needs to pull the THEME we're using from the DB
      // TODO: Post BugReport to PHP. PHP fails to catch object creation success/failure
      try
      {
        $obj = new $moduleNS();
        pmtDebug("xenoPMT::LoadModule() [step3] Try Namespace: Using Mod's Namespace");
      }
      catch (Exception $e)
      {
        pmtDebug("xenoPMT::LoadModule() [step3] Try Namespace: fail (no NS specified)");
        // ERROR_TYPE_MODULE_INVALID_NAMESPACE
        $obj = new $module();
      }

      if ($obj != false)
      {
        //$obj = new $module();
        // $obj = new $moduleNS();       // 2013-0331 + Using "namespace"
        $xpmtPage["title"] = "";                           // Page Title
        if ($obj->Title() != "")
          $xpmtPage["title"] = $obj->Title();
        else
          $xpmtPage["title"] = $xpmtConf["general"]["title"];

        $xpmtPage["icon"]       = "";                           // Path to Icon file
        $xpmtPage["ex_header"]  = "";                           // Extra Header Information
        $xpmtPage["logo"]       = "";                           // Site image path
        $xpmtPage["metabar"]    = "";                           // User (login/usr-pref)/settings/logout/about
        $xpmtPage["toolbar"]    = "";                           // Main toolbar

        if ($obj->Toolbar() != "")
          $xpmtPage["toolbar"] = $obj->Toolbar();
        else
          $xpmtPage["toolbar"] = self::GetToolbarMain($uuid);

        $xpmtPage["minileft"]   = $obj->MiniBarLeft();          // Mini-bar Left aligned (bread crumbs)
        $xpmtPage["miniright"]  = $obj->MiniBarRight();         // Mini-bar Right aligned (module node options)
        $xpmtPage["htdata"]     = $obj->PageData();             // Main page html data

        $xpmtPage["path"]       = "";                           // Relative path to theme currently in use
        $xpmtPage["path"]       = $xpmtConf["general"]["base_url"] . $relpath; // Reletive path to theme (just use something for now)

        $xpmtPage["footer"]     = "";                           // Footer

        // Removed 2013-0819
        // We should render the page data through the xenoPMT::LoadTheme() method
        //
        //require($page);
      }
      else
      {
        // Error loading class w/ namespace
        pmtDebug("LoadModule() Error loading Class Object!");
      }
    }
    else
    {
      // Added 2013-0130
      // There was an issue loading the module
      //
      // Removed 2013-0819 - We should render the page data through the xenoPMT::LoadTheme() method
      //
      //require($page);
      //require($page);
    }
    return $errRet; // default = ERROR_TYPE_NONE
  }

  /**
   * Get the selected theme to use and display the page data
   * @since v0.0.5
   * Added: 2013-03-31
   */
  public static function LoadTheme()
  {
    /* TODO
     * 1. Check if the user wants a different Theme (cache)
     * 2. Get the system's selected theme to use (cache)
     * 3. Get HTTP code from loaded module
     */

    global $xpmtPage, $pmtDB;
    global $xpmtPageObj; // obj of Page Properties

    // Setup error handling (added 2014-0129)
    $errRet = \xenoPMT\Core\Error::ERROR_TYPE_NONE;

    /* ************************* *
     * Step 1 - Initialize Theme *
     * ************************* */

    // NOTE: This is all OLD code. It does not check for Custom User Themes in the root folder
    // Step 1.1
    $theme = GetSetting("theme");
    if ($theme == "")
      $theme = "default";

    pmtDebug("xenoPMT::LoadTheme() - GetSetting('theme'): $theme");

    if (file_exists(PMT_PATH . "xpmt/themes/" . $theme))
    { // use custom theme
      $skin_path = PMT_PATH . "xpmt/themes/" . $theme . "/";
      $relpath = "xpmt/themes/" . $theme . "/";
    }
    else
    {
      // using default
      $skin_path = PMT_PATH . "xpmt/themes/default/";
      $relpath = "xpmt/themes/default/";
    }

    // Set DEFAULT skin to MAIN.PHP - check LATER if module has custom skin
    $skin_file = "main.php";
    $page = $skin_path . $skin_file;
    pmtDebug("xenoPMT::LoadTheme() - Page Path: $page");

    // Step 1.2
    // Check if module has custom skin (i.e: main, Project, Product, etc.
    // Module is coming form a UUID lookup
    $module = ""; // just added since it's not defined
    if (file_exists($skin_path . $module . ".php"))
    {
      $skin_file = $module . ".php";
      $page = $skin_path . $skin_file;
      pmtDebug("xenoPMT::LoadTheme() - *Custom* Page Path: $page");
    }

    //pmtDebug("xenoPMT::LoadTheme() - Page: $page");
    require($page);
    return $errRet;
  }

  //</editor-fold>

  //<editor-fold defaultstate="collapsed" desc="Misc functions">
  /**
   * Stage 1) Generate toolbar via static code
   * Stage 2) Generate toolbar items from databsae settings
   *
   * @param string $uuid UUID of the the active module so we can highlight it
   * @version v0.0.5
   * @author Damian Suess
   *
   * @return string HTML Unsorted List
   */
  public static function GetToolbarMain($uuid)
  {
    /* Stage 1 (xenoPMT v0.0.5) */

    // Pull this info from Database
    $tblModules2 = array(
      // Module-UUID                            Display Text
      "df9f29f8-1aed-421d-b01c-860c6b89fb14" => "Dashboard",
      "c6fb97b8-af93-42ce-aac6-de5656c8fdae" => "UUID",
      "04a78f00-220f-11e2-81c1-0800200c9a66" => "Sample",
      "81d641a2-dbcc-4bde-ad09-40c3260f325b" => "Admin"
    );

    $arrAvailMods = array(
      // Module       Display
      // "dashboard" => "Dashboard",
      "df9f29f8-1aed-421d-b01c-860c6b89fb14" => "Dashboard",
      "p" => "Projects",
      "kb" => "Knowledge Base",
      "ticket" => "Tickets", /* "ticket" => array ("Tickets", "+"), */
      "bug" => "Bugs",
      "task" => "Tasks",
      "product" => "Products",
      "customer" => "Customers",
      "user" => "Users",
      "admin" => "Admin"
    );
    $tab = "        ";
    $ret = $tab . "<ul>" . PHP_EOL;
    $ndxCount = 0;

    //print (count($a));
    foreach ($arrAvailMods as $key => $value)
    { //print ("key: $key, Obj: $value <br />");
      $ndxCount++;
      //if ($tmod[$ndx] == $module)
      if ($key == $uuid)  // if ($key == $module)     // 2013-0205 * Changed $module to $uuid
        $active = true;
      else
        $active = false;

      if ($ndxCount == 1)
      {
        if ($active)
          $cls = ' class="first active"';
        else
          $cls = ' class="first"';
      }
      elseif ($ndxCount == count($arrAvailMods))
      {
        $cls = ' class="last"';
      }
      else
      {
        if ($key == $uuid) // if ($key == $module)     // 2013-0205 * Changed $module to $uuid
          $cls = ' class="active"';
        else
          $cls = '';
        //if ($key=="project") $cls = ' class="active"'; else $cls = '';
      }
      $ret .= $tab .
        "  <li" . $cls . ">" .
        AddLink($key, $value) .
        "</li>" . PHP_EOL;
    }
    $ret .= $tab . "</ul>" . PHP_EOL;
    return $ret;


    /* Stage 2  (xenoPMT v0.0.7 */
    /* 1) Get user group (Anon, Dev, Customer, Manager, etc.)
     * 2.a) Does toolbar design exist in CACHE? (xenoPMT v0.0.9)
     * 2.b) Get toolbar group design from database query
     * 3) Return HTML <UL> of toolbar items

      // Step 1 - Get user group
      // $user->$UserInfo["Group_Id"]

      // Step 2.b - Pull this info from Database
      $tblModules2 = array(
      // Module-UUID                            Display Text
      "df9f29f8-1aed-421d-b01c-860c6b89fb14" => "Dashboard",
      "c6fb97b8-af93-42ce-aac6-de5656c8fdae" => "UUID",
      "04a78f00-220f-11e2-81c1-0800200c9a66" => "Sample",
      "81d641a2-dbcc-4bde-ad09-40c3260f325b" => "Admin"
      );
     */
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

    foreach ($xpmtModule["info"] as $ndx => $tmpModHeader)
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

  //</editor-fold>
}
?>