<?php
/**
* pmTrack (xiPMT, xiPMTrack)
* Copyright 2010-2012 (C) Xeno Innovations, Inc.
* ALL RIGHTS RESERVED
* Author:        Damian J. Suess
* Document:      config.default.php
* Created Date:  Nov 18, 2010, 5:03:43 PM
* Description:
*   This is the Default CORE config file, becareful when editing this
*   file as it will effect ALL of your sub-projects. Here you
*   can set your Root-User, Database, Default Skin, etc.
*
***********************************************************/

// $pmt_defskin = "skin-std";       /* Not used yet */

/**********************************************************
* This is the MASTER root account for all of the projects.
* If you do not want this security hold then DISABLE IT!!
*
* This is useful to use if you forget your Admin password
* set inside of the master database.
*
* Can be used for:
*  + Creating/Editing/Removing projects
**********************************************************/
$pmtRootUserEnabled = true;
$pmtRootUserName    = "rootadmin";
$pmtRootUserPass    = "testing";


$pmtConf = array(
  "db" => array(
    "server"  => "localhost",   // Database server
    "user"    => "root",        // Database username
    "pass"    => "testing123",  // Database password
    "dbname"  => "PMT_DATA",    // Database name
    "prefix"  => "PMT_"         // Table prefix
  ),
  "general" => array(
    "auth_only" => true,            // Allow access to public or auth-only
    "title"     => "Corporate Management System",
    "base_url"  => "http://pmt/"    // Must include '/' at the end.
  )
);

?>