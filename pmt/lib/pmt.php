<?php
/************************************************************
 * Copyright 2010 (C) Xeno Innovations, Inc.
 * ALL RIGHTS RESERVED
 * Author:       Damian J. Suess
 * Document:     pmtentry.hpp
 * Created Date: 2012-01-12
 *
 * Description:
 * Core-Entry point.
 *
 * Change Log:
 * [2012-0112] - Initial Creation
 *
 */

/* Step 1 - Make sure system if configured & db installed */

if(!file_exists(PMT_PATH."lib/config.php"))
{
  header("Location: install/");
}

/* Step 2 - Minor init */


/* Step 2 - Include the required classes */

// Require the core PMT files
//require(PMT_PATH."lib/common/pmt.user.php");  // User Class
require(PMT_PATH."lib/common/pmt.db.php");      // Database Class
require(PMT_PATH."lib/common/pmt.user.php");

// Since the system is "configured" include the class now
require(PMT_PATH."lib/config.php");             // Configuration Script


/* Step 1) Perform the following
 * i.   Check if a user is cached in cookie or not
 * ii.  If cashed user verify in database and set PMT_LOGGED_ID
 */


/* Step 2) Parse the URL path
 * Possible Paths:
 *  + /project/
 *  + /users/
 *  + /custoemrs/
 */

global $_product, $_user, $_customer;
global $_project, $_prjwiki, $_prjTicket, $_prjBug, $prjTask, $_prjReport,
                  $_prjRoadmap, $_prjMilestone, $_prjSource, $_prjTimeline;



pmtGetURL();



/// Parse the URL provided by browser so we can tell
/// the system where to go.
function pmtGetURL()
{
  
}

?>
