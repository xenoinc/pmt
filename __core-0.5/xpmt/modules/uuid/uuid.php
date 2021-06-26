<?php

/** * *********************************************************
 * Copyright 2012 (C) Xeno Innovations, Inc.
 * ALL RIGHTS RESERVED
 * @Author:       Damian Suess
 * Document:      uuid
 * Created Date:  Oct 29, 2012
 * Status:        {unstable/pre-alpha/alpha/beta/stable}
 * Description:
 *
 *
 * Change Log:
 *
 */

// Remove this line when Core-005 is ready
//require_once ("uuid.main.php");



// Used for Module v0.0.5
$xpmtModule["info"][] = array
(
  "author"      => "Damian Suess",
  "version"     => "0.0.1",
  "title"       => "UUID Generator",
  "description" => "Unique Universal Identifier Generator",
  "urn"         => "uuid",                                  // UniformResourceName of the module (pmt.com/ticket)
  "classname"   => "uuid",
  "namespace"   => "xenoPMT\\Module\\UUID",                   // Module's Namespace used by Setup and internal classes
  "path"        => dirname(__FILE__),                       // dirname(__FILE__) . "/sample.main.php"
  "mainfile"    => "uuid.main.php",                         // Startup clsas for module
  "core"        => false,                                   // core system module (true=core)
  "uuid"        => "c6fb97b8-af93-42ce-aac6-de5656c8fdae"   // Universally unique identifier
);

?>
