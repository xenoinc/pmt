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
if (DebugMode == true)
  debug("Debug Mode ON!");

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

define("THEME", $uri->Anchor("lib/themes", GetSetting("theme"))); // Set theme
require(PMT_PATH."lib/lang/" . GetSetting("lang"));   // Setup language


/*
global $_product, $_user, $_customer;
global $_project, $_prjwiki, $_prjTicket, $_prjBug, $prjTask, $_prjReport,
                  $_prjRoadmap, $_prjMilestone, $_prjSource, $_prjTimeline;
*/


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

  

}



?>
