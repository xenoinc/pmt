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
    "url"         => "",          // getHelpURL() - to wiki system (if installed)
    "classname"   => "ticket",
    "path"        => dirname(__FILE__) . "/ticket.main.php",          // dirname(__FILE__) . "/sample.main.php"
    "core"        => "false",     // core system module (true=core)
  );

// URI to module (also above)
$xpmtModule["uri"]["ticket"] = "samp";

?>
