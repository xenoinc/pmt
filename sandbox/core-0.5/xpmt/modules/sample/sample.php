<?php

// Module information
//$xpmtCore["module"][] = array(...);     // Proposal (2012-1019).. probably not

$xpmtModule["info"][] = array
(
  "author"      => "Damian Suess",
  "version"     => "0.0.1",
  "title"       => "Sample Module Title",
  "description" => "Sample description for module to show up in loader",
  "urn"         => "sample",                                      // Uniform Resource Name of the module (pmt.com/ticket) ** possibly deprecated
  "classname"   => "sample",                                      // name of the class inside of "$xpmtModule["info"][$ndx]->path"
  "path"        => dirname(__FILE__) . "/sample.main.php",        // Path to module core
  "core"        => "false",                                       // core system module (true=core)
  "uuid"        => "04a78f00-220f-11e2-81c1-0800200c9a66"         // Universally Unique identifier
);

// URI to module (also above)
// not used anymore. See TBL_CORE_MODULE_URI
// $xpmtModule["urn"]["sample"] = "samp";

?>
