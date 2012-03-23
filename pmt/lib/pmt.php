<?php
/************************************************************
 * pmTrack (xiPMT, xiPMTrack)
 * Copyright 2010-2012 (C) Xeno Innovations, Inc.
 * ALL RIGHTS RESERVED
 * Author:       Damian J. Suess
 * Document:     pmtentry.hpp
 * Created Date: 2012-01-12
 *
 * Description:
 * Core-Entry point.
 *
 * To Do:
 * [ ] Handle Plugins (Milestone 0.5)
 * [ ] Include langauge pack (Milestone 0.5)
 *
 * Change Log:
 * [2012-0112] - Initial Creation
 *
 */

/* Step 1 - Make sure system is configured & db installed */

// i) Set version (should be in array)
$pmt_version = "0.0.2";
$pmt_version_ex = "000002";
$pmt_db_version = 0.2;
define("PMT_VER",$pmt_version);

// ii) Setup debugging
require "phpConsole.php";
PhpConsole::start(true, true, dirname(__FILE__));
//if (DebugMode == true)
//  debug("Debug Mode ON!");

// iii) no config.php? then goto installer
if(!file_exists(PMT_PATH."lib/config.php"))
  header("Location: install/");
// else { [verify settings are valid] }


/* Step 2 - Minor Init */
// 1) set breadcrumbs
// 2) strip magic quotes
$CACHE = array("setting"=>array());
$BREADCRUMB = array();


/* Step 3 - Include the required classes */

// Require the core PMT files
//require(PMT_PATH."lib/common/pmt.user.php");  // User Class
require(PMT_PATH."lib/common/pmt.db.php");      // Database Class
require(PMT_PATH."lib/common/pmt.user.php");
require(PMT_PATH."lib/common/pmt.uri.php");     // URI Parsing class
require(PMT_PATH."lib/pmt-functions.php");      // Common functions in system
require(PMT_PATH."lib/modcontroller.php");      // module controller

// Since the system is "configured" include the class now
require(PMT_PATH."lib/config.php");             // Configuration Script
//require(PMT_PATH."lib/pmt-functions.php");      // General functions used all over


/* Step 4) - Initialize the classes
 * 1. Connect to database
 * 2. Check if a user is cached in cookie or not
 * 3. If cashed user verify in database and set PMT_LOGGED_ID
 */

// Add error handling to ensure that $pmtConf[][] is configured
$pmtDB = new Database($pmtConf["db"]["server"],
                      $pmtConf["db"]["user"],
                      $pmtConf["db"]["pass"],
                      $pmtConf["db"]["dbname"]);
define("PMT_TBL", $pmtConf["db"]["prefix"]);     // This may be removed

$user = new User;
$uri = new URI;

// undefined GetSetting
define("THEME", $uri->Anchor("lib/themes", GetSetting("theme"))); // Set theme

/* Include language pack (v0.5) */
// require(PMT_PATH."lib/lang/" . GetSetting("lang"));   // Setup language


// Used to generate the body of our skin


$PAGE_TITLE="";     // Page title
$PAGE_LOGO="";      // Site image path  ** not used yet.
$PAGE_METABAR="";   // User (login/usr-pref)/settings/logout/about
$PAGE_TOOLBAR="";   // Main toolbar
$PAGE_MINILEFT="";  // Mini-bar Left aligned (bread crumbs)
$PAGE_MINIRIGHT=""; // Mini-bar Right aligned (module node options)
$PAGE_HTDATA="";    // Main page html data
$PAGE_PATH="";      // Relative path to theme currently in use

function PmtParseURL()
{
  /**
  * Possible Paths:
  * /<root>                      Welcome page
  * /project/<proj_name>         Project page (root=list all)
  * /product/<prod_name>         Product page (root=list all)
  * /user/<user_name>            User page (root=list all)
  * /customer/<customer_number>  Customer page (root=list all)
  * /admin/                      System admin page
  */

  global $uri;
  //$param = array();

  // Cleanup
  if (count($uri->seg) == 0) $uRoot = ""; else $uRoot = $uri->seg[0];

  /* *************** */

  // Debugging
  /*
    print("<small>" );
    print("<p><b>Segments:</b> <br />"); print_r($uri->seg); print("</p>");
    print("<p><b>Full:</b><br />"); print_r($uri); print("</p>");
    print("</small><hr>");
    //pmtDebug("uri.seg[0]: '" . $uRoot . "'");
  */



  switch($uRoot)
  {
    case '':
      pmtDebug("Module: 'dashboard'");

      LoadModule("dashboard", $uri->seg);

      break;

    case 'project':
    case 'p':
      pmtDebug("Module: 'project'");

      LoadModule("project", $uri->seg);

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

    case 'product':
      pmtDebug("Module: 'product'");

      //LoadModule("product", $uri->seg);

      break;

    case 'user':
      //pmtDebug("Show User Page");

      //LoadModule("user", $uri->seg);

      /** Admin Only
       *  [1]         User Overview               http://pmt/user/
       *    [2]       User details & stats        ./user/<user-name>  (min-length=4)
       *    [2]       New User                    ./user/new
       */
      break;

    case 'customer':
      pmtDebug("Module: 'customer'");

      //LoadModule("customer", $uri->seg);

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
      pmtDebug("Module: 'ticket'");

      //LoadModule("ticket", $uri->seg);

      break;


    default:
      pmtDebug("Module: <Unknown> '". $uRoot ."'");

      LoadModule("dashboard", $uri->seg);

      break;
  }

}



?>
