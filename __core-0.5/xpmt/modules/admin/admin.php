<?php

/** * *********************************************************
 * Copyright 2012 (C) Xeno Innovations, Inc.
 * ALL RIGHTS RESERVED
 * @Author:       Damian Suess
 * Document:      admin.
 * Created Date:  Nov 24, 2012
 * Status:        Alpha  ::  {unstable/pre-alpha/alpha/beta/stable}
 * Description:
 *  Administration module header
 *
 * Change Log:
 *  2012-11-24  * Ground Breaking
 *              + UUID: df9f29f8-1aed-421d-b01c-860c6b89fb14
 */

// Remove this line when Core-005 is ready
//require_once ("admin.main.php");

// Used for Module v0.0.5
$xpmtModule["info"][] = array
(
  "author"      => "Damian Suess",
  "version"     => "0.0.5",
  "title"       => "System Administration Panel",
  "description" => "Control panel to your xenoPMT core system.",
  "urn"         => "admin",                                 // UniformResourceName of the module (pmt.com/admin)
  "classname"   => "admin",                                 // Name of class inside of "path"
  "namespace"   => "xenoPMT\Module\Admin",                  // Module's Namespace used by Setup and internal classes
  "path"        => dirname(__FILE__),                       // dirname(__FILE__) . "/sample.main.php"
  "mainfile"    => "admin.setup.php",                       // Startup clsas for module
  "core"        => true,                                    // core system module (true=core)
  "uuid"        => "81d641a2-dbcc-4bde-ad09-40c3260f325b"   // Universally unique identifier
);

?>
