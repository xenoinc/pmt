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

/* Step 1
 * Make sure system is configured & db installed
 */

// Set version (should be in array)
$pmt_version = "0.0.1";
$pmt_version_ex = "000001";
$pmt_db_version = 1;
define("PMT_VER",$pmt_version);

require "phpConsole.php";
PhpConsole::start(true, true, dirname(__FILE__));
if (DebugMode == true)
  debug("Debug Mode ON!");


if(!file_exists(PMT_PATH."lib/config.php"))
{
  header("Location: install/");
}

/* Step 2
 * Minor init
 */


// set breadcrumbs
// strip magic quotes



/* Step 3
 * Include the required classes
 */

// Require the core PMT files
//require(PMT_PATH."lib/common/pmt.user.php");  // User Class
require(PMT_PATH."lib/common/pmt.db.php");      // Database Class
require(PMT_PATH."lib/common/pmt.user.php");
require(PMT_PATH."lib/common/pmt.uri.php");     // URI Parsing class

// Since the system is "configured" include the class now
require(PMT_PATH."lib/config.php");             // Configuration Script


/* Step 4)
 * Initialize the classes
 *
 * 1. Connect to database
 * 2. Check if a user is cached in cookie or not
 * 3. If cashed user verify in database and set PMT_LOGGED_ID
 */

// Add error handling to ensure that $pmtConf[][] is configured
$pmtDb = new Database($pmtConf["db"]["server"],
                      $pmtConf["db"]["user"],
                      $pmtConf["db"]["pass"],
                      $pmtConf["db"]["dbname"]);
define("TBL_PREFIX", $pmtConf["db"]["prefix"]);     // This may be removed

/* Step 5) Parse the URL path
 * Possible Paths:
 *  + /project/
 *  + /users/
 *  + /custoemrs/
 */

/*
global $_product, $_user, $_customer;
global $_project, $_prjwiki, $_prjTicket, $_prjBug, $prjTask, $_prjReport,
                  $_prjRoadmap, $_prjMilestone, $_prjSource, $_prjTimeline;
*/

pmtGetURL();



?>
