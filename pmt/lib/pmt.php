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

// Get database information
require("config.php");

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
