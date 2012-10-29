<?php

/** * *********************************************************
 * Copyright 2012 (C) Xeno Innovations, Inc.
 * ALL RIGHTS RESERVED
 * @Author:      Damian Suess
 * Document:     ticket v1.0
 * Created Date: Sep 6, 2012
 *
 * Description:
 *  Ticket viewer
 *
 * Change Log:
 *
 */
// Module information

$xpmtModule["info"][] = array
  (
    "author"      => "Damian Suess",
    "version"     => "1.0",
    "title"       => "Inquiry Ticket Module",
    "description" => "Inquiry ticketing system",
    "urn"         => "ticket",                                // UniformResourceName of the module (pmt.com/ticket)
    "classname"   => "ticket",                                // Name of class inside of "path"
    "path"        => dirname(__FILE__) . "/ticket.main.php",  // dirname(__FILE__) . "/sample.main.php"
    "core"        => "false",                                 // core system module (true=core)
    "uuid"        => "1c55ae42-f92a-11e1-90b0-f23c91df28c0"   // Universally Unique Id to tag each module as their own
    //"help_url"    => ""                                     // getHelpURL() - to wiki system (if installed)
  );

// URI to module (also above)
// $xpmtModule["urn"]["ticket"] = "ticket";

?>
