<?php

// Module information
//$xpmtCore["module"][] = array(...);     // Proposal (2012-1019)

$xpmtModule["info"][] = array
  (
    "author"      => "Damian Suess",
    "version"     => "1.0",
    "title"       => "Sample Module Title",
    "description" => "Sample description for module to show up in loader",
    "urn"         => "sample",                                      // Uniform Resource Name of the module (pmt.com/ticket) ** possibly deprecated
    "classname"   => "sample",                                      // name of the class inside of "$xpmtModule["info"][$ndx]->path"
    "path"        => dirname(__FILE__) . "/sample.main.php",        // Path to module core
    "core"        => "false",                                       // core system module (true=core)
    "uuid"        => "ef64ffb0-19a8-11e2-892e-0800200c9a66"         // Universally Unique identifier
  );

// URI to module (also above)
// not used anymore. See TBL_CORE_MODULE_URI
// $xpmtModule["uri"]["sample"] = "samp";

?>
