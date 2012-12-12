<?php

/** * *********************************************************
 * Copyright 2012 (C) Xeno Innovations, Inc.
 * ALL RIGHTS RESERVED
 * @Author:       Damian Suess
 * Document:      dashboard
 * Created Date:  Nov 24, 2012
 * Status:        {unstable/pre-alpha/alpha/beta/stable}
 * Description:
 *
 *
 * Change Log:
 *  2012-11-24  * Ground Breaking
 *              + UUID: df9f29f8-1aed-421d-b01c-860c6b89fb14
 */

// Remove this line when Core-005 is ready
//require_once ("dashboard.main.php");

// Used for Module v0.0.5
$xpmtModule["info"][] = array
(
  "author"      => "Damian Suess",
  "version"     => "0.0.5",
  "title"       => "xenoPMT Dashboard",
  "description" => "Welcome screen and main page for all users whether they are logged in or not.",
  "urn"         => "",                                      // UniformResourceName of the module (pmt.com/admin)
  "classname"   => "dashboard",                             // Name of class inside of "path"
  "namespace"   => "xenoPMT\\Module\\Dashboard",              // Module's Namespace used by Setup and internal classes
  "path"        => dirname(__FILE__),                       // dirname(__FILE__) . "/sample.main.php"
  "mainfile"    => "dashboard.main.php",                    // Startup clsas for module
  "core"        => true,                                    // core system module (true=core)
  "uuid"        => "df9f29f8-1aed-421d-b01c-860c6b89fb14"   // Universally unique identifier
);

?>