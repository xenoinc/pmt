<?php
/**
* xenoPMT
* Copyright 2010-2012 (C) Xeno Innovations, Inc.
* ALL RIGHTS RESERVED
* Author:        Damian J. Suess
* Document:      config.php
* Created Date:  Nov 18, 2010, 5:03:43 PM
* Description:
*   This is the Default CORE config file, be careful when editing this
*   file as it will effect ALL of your sub-projects. Here you
*   can set your Root-User, Database, Default Skin, etc.
*
***********************************************************/

date_default_timezone_set('America/New_York');

// Main config var
$xpmtConf = array(
  // Database Connection
  "db" => array(
    "server"  => "localhost", // Database server
    "dbname"  => "PMT_TEST",  // Database name
    "prefix"  => "XI_",       // Table prefix
    "user"    => "betauser",  // Database username
    "pass"    => "betapass",  // Database password
  ),
  // General Site Data
  "general" => array(
    "auth_only" => true,                  // Allow access to public or auth-only
    "title"     => "Corporate Management System",
    "base_url"  => "http://pmt2/",        // Must include '/' at the end.
    "clean_uri" => "1"                    // Clean URI
    // , "allow_public_reg" => false      // This should be in Database under system-cfg
  )
);


/**
 * Safely REQUIRE modules. If it doesn't exist then it won't crash the system.
 * This got moved to, "config.default.php" for safty purposes. We don't want the user to mess with this
 */
// Modules to include. Needed for first time install of module
xpmtUseMod( dirname( __FILE__ ) . "/xpmt/modules/sample/sample.php");
xpmtUseMod( dirname( __FILE__ ) . "/xpmt/modules/uuid/uuid.php");
xpmtUseMod( dirname( __FILE__ ) . "/xpmt/modules/admin/admin.php");
xpmtUseMod( dirname( __FILE__ ) . "/xpmt/modules/dashboard/dashboard.php");
