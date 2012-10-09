<?php
/**
* xenoPMT
* Copyright 2010-2012 (C) Xeno Innovations, Inc.
* ALL RIGHTS RESERVED
* Author:        Damian J. Suess
* Document:      config.php
* Created Date:  Nov 18, 2010, 5:03:43 PM
* Description:
*   This is the Default CORE config file, becareful when editing this
*   file as it will effect ALL of your sub-projects. Here you
*   can set your Root-User, Database, Default Skin, etc.
*
***********************************************************/

date_default_timezone_set('America/New_York');        // [DJS] Added to fix warning in PHP & PhpConsole

// System admin bypass account (NOT IMPLIMENTED)
$pmtRootUserEnabled = true;
$pmtRootUserName    = "rootadmin";
$pmtRootUserPass    = "testing";


$pmtConf = array(
  "db" => array(
    "server"  => "localhost",   // Database server
    "user"    => "testuser2",   // Database username
    "pass"    => "testpass",    // Database password
    "dbname"  => "PMT_TEST",    // Database name
    "prefix"  => "PMT_"         // Table prefix
  ),
  "general" => array(
    "auth_only" => true,            // Allow access to public or auth-only
    "title"     => "Corporate Management System",
    "base_url"  => "http://pmt2/"    // Must include '/' at the end.
  )
);

?>
