<?php

// Module information
$xpmtModule["info"][] = array
  (
    "author"      => "Damian Suess",
    "version"     => "1.0",
    "title"       => "Sample Module Title",
    "description" => "Sample description for module to show up in loader",
    "url"         => "",          // getHelpURL() - to wiki system (if installed)
    "classname"   => "sample",
    "path"        => "",          // dirname(__FILE__) . "/sample.main.php"
    "core"        => "false",     // core system module (true=core)
  );

// URI to module (also above)
$xpmtModule["uri"]["sample"] = "samp";

?>
