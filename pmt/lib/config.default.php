<?php
/**
 * Copyright 2010 (C) Xeno Innovations, Inc.
 * ALL RIGHTS RESERVED
 * Author:        Damian J. Suess
 * Document:      config
 * Created Date:  Nov 18, 2010, 5:03:43 PM
 * Description:
 *   This is the Default CORE config file, becareful when editing this
 *   file as it will effect ALL of your sub-projects. Here you
 *   can set your Root-User, Database, Default Skin, etc.
 *
 * Change Log:
 * [2012-0130] - moved DB to 
 * [2010-1118] - Move config file to /lib/ dir & not root
 *
  ***********************************************************/

require("version.php");   // Get PMT version

// require(PMT_PATH."lib/subversion.php");
// $pmt_defskin = "skin-std";       /* Not used yet */
// $pmt_project = "";

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


// New Method
$pmtConf = array(
	"db" => array(
		"server"  => "localhost",   // Database server
		"user"    => "root",        // Database username
		"pass"    => "testing123",  // Database password
		"dbname"  => "pmt",         // Database mame
		"prefix"  => "pmt_"         // Table prefix
	),
	"general" => array(
		"authorized_only" => false // Access for authorized users only
	)
);



?>





