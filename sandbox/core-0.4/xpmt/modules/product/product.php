<?php

/** * *********************************************************
 * Copyright 2012 (C) Xeno Innovations, Inc.
 * ALL RIGHTS RESERVED
 * @Author:      Damian Suess
 * Document:     product
 * Created Date: Sep 26, 2012
 *
 * Description:
 *
 *
 * Change Log:
 *
 */

// Remove this line when Core-005 is ready
require_once ("product.main.php");



// Used for Module v0.0.5
$xpmtModule["info"][] = array
(
  "author"      => "Damian Suess",
  "version"     => "0.0.1",
  "title"       => "Product Module",
  "description" => "Product overview and management module",
  "urn"         => "prod",                                  // UniformResourceName of the module (pmt.com/ticket)
  "classname"   => "product",                               // Name of class inside of "path"
  "path"        => dirname(__FILE__) . "/product.main.php", // dirname(__FILE__) . "/sample.main.php"
  "core"        => "false",                                 // core system module (true=core)
  "uuid"        => "68bed8da-0989-481c-96df-91123e0276dd"   // Universally Unique Id to tag each module as their own
);

// URI to module (also above)
// not used anymore. See TBL_CORE_MODULE_URI
// $xpmtModule["urn"]["kb"] = "kb";



?>
