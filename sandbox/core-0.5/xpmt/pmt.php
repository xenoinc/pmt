<?php
/************************************************************
 * pmTrack (xiPMT, xiPMTrack)
 * Copyright 2010-2012 (C) Xeno Innovations, Inc.
 * ALL RIGHTS RESERVED
 * Author:       Damian J. Suess
 * Document:     core.php (OLD: pmt.php)
 * Created Date: 2012-01-12
 *
 * Description:
 * Core-Entry point.
 *
 * To Do:
 * [ ] Handle Plugins (Milestone 0.5)
 * [ ] Change "$PAGE_TITLE" to use associatve array $xpmtPage[] or $xpmtCore["page"][]
 * [ ] Include langauge pack (Milestone 0.5)
 * [ ] Call "config.default.php" before calling "config.php" to ensure
 *      that our system handles the default settings.
 *
 * Change Log:
 *  2012-1204 + Addex static core class, xenoPMT
 *  2012-1203 * Changed global variables to associative arrays like $xpmtCore[][];
 *            + $xpmtCore["uri"] = $uri; Testing to see if it's worth it to consolidate into xpmtCore ass array.
 *  2012-0716 + Added "config.default.php" to be accessed before user custom file is accessed
 *  2012-0709 + Added 'iModule.php' interface to {required} list. (old pmtModule interface) [DJS]
 *  2012-0112 - Initial Creation [DJS]
 */

/* Step 1 - Make sure system is configured & db installed */

// i) Set version (should be in array)

// Added 2012-1019 - to replace old crap
$xpmtCore["info"]["version"]     = "0.0.5";       // Core Version      (maj.min.rev)
$xpmtCore["info"]["version_ex"]  = 000005;        // Integer friendly  (00 00 05)
$xpmtCore["info"]["version_ex2"] = "000005";      // String  friendly  (00 00 05)
$xpmtCore["info"]["db_version"]  = 0.2;           // Minimum Database version accepted
define("PMT_VER", $xpmtCore["info"]["version"]);


// iii) no config.php? then goto installer
///  ** Moved to index.php (2012-0805 - djs)


/* Step 2 - Minor Init */
// 1) set breadcrumbs
// 2) strip magic quotes
// Added 2012-1019 - keep one home for many variables
$xpmtCore["page"]["cache"]      = array("setting"=>array());
$xpmtCore["page"]["breadcrumb"] = array();


/* Step 3 - Include the required classes */

// Since the system is "configured" include the class now
//require(PMT_PATH."xpmt/config.default.php");      // Default configuration script
//require(PMT_PATH."xpmt/config.php");              // Configuration Script

// Require the core PMT files
//require_once(PMT_PATH."xpmt/common/pmt.user.php");     // User Class
require_once(PMT_PATH."xpmt/core/xenopmt.php");        // Static class to core functions
require_once(PMT_PATH."xpmt/core/pmt.db.php");         // Database Class
require_once(PMT_PATH."xpmt/core/pmt.member.php");     // Member (User) class
require_once(PMT_PATH."xpmt/core/pmt.uri.php");        // URI Parsing class
require_once(PMT_PATH."xpmt/core/pmt.i.module.php");   // module interface
require_once(PMT_PATH."xpmt/pmt-functions.php");       // Common functions in system
require_once(PMT_PATH."xpmt/modcontroller.php");       // module controller

/* Step 4) - Initialize the classes
 * 1. Connect to database
 * 2. Check if a user is cached in cookie or not
 * 3. If cashed user verify in database and set PMT_LOGGED_ID
 */

// Add error handling to ensure that $xpmtConf[][] is configured
// Change $pmtDB to $xpmtCore["db"]
$pmtDB = new Database($xpmtConf["db"]["server"],
                      $xpmtConf["db"]["user"],
                      $xpmtConf["db"]["pass"],
                      $xpmtConf["db"]["dbname"]);
define("PMT_TBL", $xpmtConf["db"]["prefix"]);     // This may be removed

$xenoPMT = new xenoPMT;
$user = new Member;   // $user = new User;
$uri = new URI;
$xpmtCore["uri"] = $uri;      // 2012-1203  + Testing to see if it's worth it to consolidate into xpmtCore ass array.
$xpmtCore["user"] = $user;    // 2012-1203  + Eliminate class variables & place into associative array!



/**********************************************
 *  Step 5 - Get theme to use
 * 0) Pull theme from system settings
 * 1) Future: Pull theme from user's settings
 ******************************************* */

// undefined GetSetting
//define("THEME", $uri->Anchor("xpmt/themes", GetSetting("theme"))); // Set theme
define("THEME", $xpmtCore["uri"]->Anchor("xpmt/themes", GetSetting("theme"))); // Set theme



/****************************
 * Step 6 - Language Pack
 ************************* */

/* Include language pack (v0.5) */
// require(PMT_PATH."xpmt/lang/" . GetSetting("lang"));   // Setup language



/*********************************
 * Step 7 - Initialize Page data
 ****************************** */

// Used to generate the body of our skin
$PAGE_TITLE="";     // Page title
$PAGE_LOGO="";      // Site image path  ** not used yet.
$PAGE_METABAR="";   // User (login/usr-pref)/settings/logout/about
$PAGE_TOOLBAR="";   // Main toolbar
$PAGE_MINILEFT="";  // Mini-bar Left aligned (bread crumbs)
$PAGE_MINIRIGHT=""; // Mini-bar Right aligned (module node options)
$PAGE_HTDATA="";    // Main page html data
$PAGE_PATH="";      // Relative path to theme currently in use


// Proposal 2012-1019 - keep one home for many variables
//
//  $xpmtCore["page"]["title"]="";
//  or
//  $xpmtPage["title"]="";      <<<< use this

// Used to generate the body of our skin
$xpmtPage["icon"]="";         // Path to Icon file
$xpmtPage["title"]="";        // Page Title
$xpmtPage["ex_header"]="";    // Extra Header Information
$xpmtPage["logo"]="";         // Site image path
$xpmtPage["metabar"]="";      // User (login/usr-pref)/settings/logout/about
$xpmtPage["toolbar"]="";      // Main toolbar
$xpmtPage["minileft"]="";     // Mini-bar Left aligned (bread crumbs)
$xpmtPage["miniright"]="";    // Mini-bar Right aligned (module node options)
$xpmtPage["htdata"]="";       // Main page html data
$xpmtPage["path"]="";         // Relative path to theme currently in use
$xpmtPage["footer"]="";       // Footer

/* ################################################################################ */


/**
 * Parse URI and Load Module
 *
 * @since v0.0.5
 *
 * @global URI $uri
 * @global type $xpmtModule
 * @global array $xpmtCore
 */
function ParseAndLoad()
{
  global $xpmtCore, $xpmtModule,$xpmtPage, $PAGE_HTDATA;    // , $xpmtConf;
  global $xenoPMT;


  // Step 1 - Cleanup segment data ]-------------
  if (count($xpmtCore["uri"]->Count) == 0)      // if (count($uri->seg) == 0)
    $uRoot = "";
  else
    $uRoot = $xpmtCore["uri"]->Segment(0);

  //$uRoot = $xpmtCore["uri"]->seg[0];
  //pmtDebug("uri.seg[0]: '" . $uRoot . "'");

  /* *************** */

  // Debugging ]-------------
  /*
  echo("<small>" );
  //echo("<p><b>2-Segments:</b> <br />"); print_r($xpmtCore["uri"]->seg);   echo("</p>");
  //echo("<p><b>2-Full:</b><br />");      print_r($xpmtCore["uri"]);        echo("</p>");
  echo("<p><b>Modules:</b><br /> '");   print_r($xpmtModule); echo("'</p>");
  echo("</small><hr>");
  */


  // Step 2 - Load the module ]-------------
  // ** In 0.0.7, load modules from DB and not just config file
  /*
  $matchFound = false;    // Did we find a module match?
  $modHeader = array();      // Prepare a blank Module header
  foreach( $xpmtModule["info"] as $ndx => $tmpModHeader)
  {
    //echo($tmpModHeader["urn"]);
    if ($xpmtCore["uri"]->Count > 0 && $xpmtCore["uri"]->Segment(0) == $tmpModHeader["urn"])
    {
      $matchFound = true;   // We found a match!
      $modHeader = $tmpModHeader;   // Use this module header!
      break;
    }
  }
  */

  $modHeader = $xenoPMT::GetModuleHeaderFromURN($xpmtCore["uri"]->Segment(0), $matchFound);

  if ($matchFound)
  { // Load the module
    $xenoPMT::LoadModule($modHeader["uuid"]);
  }
  else
  { // Unknown Module URN / Module not loaded
    $html = "Unknown Module!";

    $xpmtPage["htdata"]=$html;
    $PAGE_HTDATA=$html;
    echo($html );

  }
}



/**
 * @deprecated since version Core-0.0.5 - 2012-1203 [djs]
 *
 * Backup from old Core-0.0.4
 * Retained for legacy and documentation values
 *
 *
 * @global URI $uri
 * @global type $xpmtModule
 * @global array $xpmtCore
 */
function OLD__PmtParseURL()
{
  /**
   * Possible Paths:
   * /<root>                      Welcome page
   * /kb/<kb-id>                  Knowledge Base
   * /project/<proj_name>         Project page (root=list all)
   * /product/<prod_name>         Product page (root=list all)
   * /user/<user_name>            User page (root=list all)
   * /customer/<customer_number>  Customer page (root=list all)
   * /admin/                      System admin page
   */

  global $uri, $xpmtModule,$xpmtCore; // , $xpmtConf, $xpmtCore;
  //$param = array();

  // Cleanup ]-------------
  if (count($xpmtCore["uri"]->Count) == 0)      // if (count($uri->seg) == 0)
    $uRoot = "";
  else
    $uRoot = $xpmtCore["uri"]->Segment(0);  //$uRoot = $xpmtCore["uri"]->seg[0];

  /* *************** */

  // Debugging ]-------------

  echo("<small>" );
  echo("<p><b>Segments:</b> <br />");   print_r($uri->seg);   echo("</p>");
  echo("<p><b>Full:</b><br />");        print_r($uri);        echo("</p>");
  echo("<p><b>2-Segments:</b> <br />"); print_r($xpmtCore["uri"]->seg);   echo("</p>");
  echo("<p><b>2-Full:</b><br />");      print_r($xpmtCore["uri"]);        echo("</p>");
  echo("<p><b>Modules:</b><br /> '");   print_r($xpmtModule); echo("'</p>");
  echo("</small><hr>");
  //pmtDebug("uri.seg[0]: '" . $uRoot . "'");




  // Load the module ]-------------
  /*
   * Filter out the modules with ones that are known
   * In the future, don't use this.. just rely on LoadModule()
   * to handle the logic
   */
  switch($uRoot)
  {
    case '':
      //pmtDebug("Module: 'dashboard'");
      LoadModule("dashboard", $xpmtCore["uri"]->SegmentArray()); //LoadModule("dashboard", $uri->seg);
      break;

    case 'kb':
      LoadModule("kb", $uri->seg);
      break;

    case 'project':
    case 'prj':
    case 'p':
      //pmtDebug("Module: 'project'");

      //LoadModule("project", $uri->seg);
      LoadModule("p", $uri->seg);

      /*  [1]         Project Stats / Selection   http://pmt/project/) or (http://pmt/p/)
       *    [2]       Project view                ./p/<prj>/
       *      [3]     Wiki Browser                ./p/../wiki/
       *        [4a]  Wiki page viewer            ./p/../wiki/about
       *        [4b]  Edit Wiki page              ./p/../wiki/about?edit
       *      [3]     Ticket New                  ./p/../ticket
       *        [4]   Ticket View                 ./p/../ticket/<id>
       *          [5] Ticket Edit                 ./p/../ticket/<id>/edit
       *      [3]     New Bug                     ./p/../bug
       *        [4]   Bug View                    ./p/../bug/<id>
       *          [5] Bug Edit                    ./p/../bug/<id>/edit
       *      [3]     New Task                    ./p/../task
       *        [4]   Task View                   ./p/../Task/<id>
       *          [5] Task Edit                   ./p/../Task/<id>/edit
       */
      break;

    case 'prod':
    case 'product':
      //pmtDebug("Module: 'product'");
      LoadModule("product", $uri->seg);
      break;

    case 'user':
      //pmtDebug("Show User Page");
      LoadModule("user", $uri->seg);
      /** Admin Only
       *  [1]         User Overview               http://pmt/user/
       *    [2]       User details & stats        ./user/<user-name>  (min-length=4)
       *    [2]       New User                    ./user/new
       */
      break;

    case 'customer':
      //pmtDebug("Module: 'customer'");
      LoadModule("customer", $uri->seg);
      /*  [1]         Customer Overview           http://pmt/customer/
       *    [2]       View Customer Details       ./c/<custmr-id>
       *      [3]     (redirect to [2]            ./c/../contact/
       *        [4]   Contact Viewer              ./c/../contact/<id>
       *          [5] Contact Edit                ./c/../contact/<id>/edit
       *      [3a]    Edit Customer Details       ./c/../edit
       *      [3c]    Tasks                       ./c/../task/
       *        [4]   Task Viewer                 ./c/../task/<id>
       */
      break;

    case 'ticket':
      // create general ticket
      //pmtDebug("Module: 'ticket'");
      LoadModule("ticket", $uri->seg);
      break;

    default:

      //pmtDebug("Module: <Unknown> '". $uRoot ."'");
      /**
       * A) If unknown - Use Dashboard
       * B) if unknown - FORCE Dashboard  ** (Doen't work yet)
       * C) Allows for custom modules
       */

      // Option A
      LoadModule("dashboard", $uri->seg);

      // Option B
      //header("Location: " . $xpmtConf["general"]["base_url"] );  exit;

      // Option C - (TEST) Allow virtually anything
      //LoadModule($uRoot, $uri->seg);
      break;
  }
}

?>
