<?php

/** * *********************************************************
 * Copyright 2012 (C) Xeno Innovations, Inc.
 * ALL RIGHTS RESERVED
 * @Author:      Damian Suess
 * Document:     Knowledge Base v0.4
 * Created Date: 2012-09-07
 *
 * Description:
 *  Knowledge Base module description file
 *
 * Change Log:
 *
 */

$xpmtModule["info"][] = array
  (
    "author"      => "Damian Suess",
    "version"     => "0.4",
    "title"       => "Knowledge Base Module",
    "description" => "Knowledge Base",
    "urn"         => "kb",                                    // UniformResourceName of the module (pmt.com/ticket)
    "classname"   => "kb",                                    // Name of class inside of "path"
    "path"        => dirname(__FILE__) . "/kb.main.php",      // dirname(__FILE__) . "/sample.main.php"
    "core"        => "false",                                 // core system module (true=core)
    "uuid"        => "81b8f070-f94b-11e1-a21f-0800200c9a66"   // Universally Unique Id to tag each module as their own
    //"help_url"    => ""                                     // getHelpURL() - to wiki system (if installed)
  );

// URI to module (also above)
$xpmtModule["uri"]["kb"] = "kb";

?>
