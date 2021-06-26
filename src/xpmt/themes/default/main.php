<?php

/* * **********************************************************
 * Copyright 2012 (C) Xeno Innovations, Inc.
 * ALL RIGHTS RESERVED
 * Author:       Damian Suess
 * Document:     main
 * Created Date: Mar 21, 2012
 *
 * Description:
 *  This is a sample of the main dashboard "default" template
 *  currently based upon Trac13 for its clean looks
 *
 * Change Log:
 *  2012-0424 + Added handling for 'global $errors'
 */

// have a variable access the "project" class
// which will handle all of the display features

// page data to display
global $xpmtPage, $xpmtConf;
// global $PAGE_TITLE, $PAGE_TOOLBAR, $PAGE_METABAR, $PAGE_HTDATA, $PAGE_PATH;

// require ("skin-fctn.php");
?>
<!--
  Copyright 2012 Xeno Innovations, Inc.

-->
<!DOCTYPE html
    PUBLIC "-//W3C//DTD XHTML 1.0 Strlict//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">
  <head>
    <meta content="text/html;charset=utf-8" http-equiv="content-type" />
    <title><?php print($xpmtPage["title"]); ?></title>
    <link type="text/css" href="<?php echo($xpmtPage["path"]); ?>skin.css" rel="stylesheet" />
    <link type="text/css" href="<?php echo($xpmtPage["path"]); ?>proj.css" rel="stylesheet" />
    <?php  echo($xpmtPage["ex_header"]); ?>
  </head>
  <body>

    <div id="header">
      <div id="logo">
        <!-- logo -->
        <a id="logo" href="/" alt="xenoPMT Dashboard">
          <img height="61" width="214" alt="xenoPMT Dashboard"
              src="<?php echo($xpmtPage["path"] . "gfx/logo.png"); ?>" />
        </a>
      </div>
      <div id="metabar" class="metanav">
        <!-- (Login / View Profile), Account Settings, Logout -->
<?php echo($xpmtPage["metabar"]); ?>
      </div>
    </div>
    <div id="mainbar" class="tbar">
      <!-- toolbar -->
      <?php echo($xpmtPage["toolbar"]); ?>
    </div>


    <div id="container">
    <?php
    global $errors;
    if(isset($errors) && count($errors))
    { ?>
      <div class="message error">
      <?php foreach($errors as $error) { ?>
        <?php echo $error?><br />
      <?php } ?>
      </div>
    <?php } ?>

      <div id="minibar_left" class="nav_left">
        <!-- breadcrumbs left --><?php echo($xpmtPage["minileft"]); ?>

      </div>
      <div id="minibar_right" class="nav_right">
        <!-- mini toolbar for module -->
        <?php echo($xpmtPage["miniright"]); ?>

      </div>

      <div id="main">
<?php
  echo($xpmtPage["htdata"]);
  echo("\n");
?>
      </div>
    </div>

    <div id="footer">
      <?php echo($xpmtPage["footer"]); ?>
    </div>

  </body>
</html>