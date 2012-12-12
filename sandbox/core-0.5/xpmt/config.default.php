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
 * Change Log:
 *  2012-1203 + Added procedure, xpmtUseMod($modPath) here from the user's "config.php" so that
 *              the user does not mess with this. And added debugging logic
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

date_default_timezone_set('America/New_York');        // [DJS] Added to fix warning in PHP & PhpConsole


// System admin bypass account (NOT IMPLEMENTED)
$pmtRootUserEnabled = true;
$pmtRootUserName    = "rootadmin";
$pmtRootUserPass    = "testing";


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
    "auth_only" => true,            // Allow access to public or auth-only
    "title"     => "Corporate Management System",
    "base_url"  => "http://pmt/"    // Must include '/' at the end.

    // Settings not yet implemented
    ,"allow_public_registration" => false   // All general public to create user accounts

  )
);

/**
 * Safely REQUIRE modules. If it doesn't exist then it won't crash the system.
 * *** This only exists in "config.default.php" ***
 *
 * @param string $modPath Path to module
 *
 * @example
 *  xpmtUseMod( dirname( __FILE__ ) . "/xpmt/modules/sample/sample.php");
 */
function xpmtUseMod($modPath)
{
  // Must define as a GLOBAL or else $xpmtModule will be lost
  global $xpmtModule;

  if (file_exists($modPath))
  {
    require_once($modPath);
    //if (DebugMode) debug("Mod Added: $modPath");
  }
  else
  {
    //if (DebugMode) debug("Mod Failed: $modPath");
  }
}

?>