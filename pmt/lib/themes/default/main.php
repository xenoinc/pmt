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
 *
 */

// have a variable access the "project" class
// which will handle all of the display features

// page data to display
global $PAGE_TITLE;
global $PAGE_TOOLBAR;
global $PAGE_METABAR;
global $PAGE_HTDATA;
global $PAGE_PATH;


// require ("skin-fctn.php");
?>
<!--
  Copyright 2012 Xeno Innovations, Inc.

-->
<!DOCTYPE html
    PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">
  <head>
    <meta content="text/html;charset=utf-8" http-equiv="content-type" />
    <title><?php $PAGE_TITLE; ?></title>
    <link type="text/css" href="<?php print($PAGE_PATH); ?>skin.css" rel="stylesheet" />

  </head>
  <body>

    <div id="header">
      <div id="logo">
        <!-- logo -->
        <a id="logo" href="/" alt="xenoPMT Dashboard">
          <img height="61" width="214" alt="xenoPMT Dashboard"
              src="<?php print($PAGE_PATH . 'gfx/logo.png'); ?>" />
        </a>
      </div>
      <div id="metabar" class="metanav">
        <!-- (Login / View Profile), Account Settings, Logout -->
        <?php print($PAGE_METABAR); ?>
      </div>
    </div>
    <div id="mainbar" class="tbar">
      <!-- toolbar -->
      <?php print($PAGE_TOOLBAR); ?>
    </div>
    <div id="container">
      <div id="minibar_left">
        <!-- breadcrumbs left -->
        &nbsp; a
      </div>
      <div id="minibar_right">
        <!-- mini toolbar for module -->
        &nbsp; b
      </div>

      <div id="main">
<?php
  print($PAGE_HTDATA);
  print("\n");
?>
      </div>
    </div>

    <div id="footer">

    </div>

  </body>
</html>