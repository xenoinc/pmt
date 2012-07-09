<?php
/************************************************************
 * pmTrack (xiPMT, xiPMTrack)
 * Copyright 2010-2012 (C) Xeno Innovations, Inc.
 * ALL RIGHTS RESERVED
 * Author:       Damian J. Suess
 * Document:     index.php
 * Created Date: Oct 31, 2010, 11:03:17 PM
 *
 * Description:
 *  Core-Entry point.  If you access this directly you will be asked
 *  to do the following (depending on system settings in main MySQL DB)
 *
 *    1) Select a project to view
 *    2) Login to the Admin Panel
 *    3) Login as a Customer
 *    4) Login as an Employee (Development, Management, Support, etc.)
 *
 * To Do:
 * [ ] Move "DebugMode" as a variable in "config.default.php" as { $pmtDebugMode = true; }
 *
 * Change Log:
 * 2012-0709 - removed unused code & created "index-test.php"
 * 2012-0112 - remvoed front page. we're using a dynamic generator
 * 2010-1030 - Initial Creation
 *
 */

// Development Debug Mode
define("DebugMode", true);

// Define absolute path
define('PMT_PATH',str_replace(pathinfo(__FILE__,PATHINFO_BASENAME),'',__FILE__));

// print("path: " . PMT_PATH);
include_once "lib/pmt.php";
PmtParseURL();

?>
