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
 *  2012-0920 * Modified id locations (moved Container to WHOLE page, body to main body)
 *            * Moved #container to sourround the entire page
 *            + Added #pagebody
 *            + Added entry for $PAGE_CSS to be used in the future
 *  2012-0424 + Added handling for 'global $errors'
 */

// have a variable access the "project" class
// which will handle all of the display features

// page data to display
global $pmtConf;
global $PAGE_PATH;        // "http://pmt/xpmt/themes/default/"
global $PAGE_TITLE;       // Page title
global $PAGE_CSS;         // todo: Add to global var list
//global $PAGE_CUSTHEADER;// Custom Header for module
global $PAGE_METABAR;     // Login & Search bar (right aligned)
global $PAGE_TOOLBAR;     // Main toolbar
global $PAGE_MINILEFT;    // Mini toolbar (Left aligned)
global $PAGE_MINIRIGHT;   // Mini toolbar (Right aligned)
global $PAGE_HTDATA;      // Page content

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
    <title><?php print($PAGE_TITLE); ?></title>
    <link type="text/css" href="<?php print($PAGE_PATH); ?>skin.css" rel="stylesheet" />
    <link type="text/css" href="<?php print($PAGE_PATH); ?>proj.css" rel="stylesheet" />
    <?php
      if (isset($PAGE_CSS) && $PAGE_CSS != "")
        print (
          "    <link type\"text/css\" href=\"" . $PAGE_PATH . $PAGE_CSS . "\" rel=\"stylesheet\" />\n"
        );
      // if (isset($PAGE_CUSTHEADER)) print($PAGE_CUSTHEADER);
    ?>
  </head>
  <body>

    <div id="container">
      <div id="header">
        <div id="logo">
          <!-- logo -->
          <a id="logoimg" href="/">
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


      <div id="pagebody">
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
          <!-- breadcrumbs left --><?php print($PAGE_MINILEFT); ?>

        </div><!-- end:minibar_left -->
        <div id="minibar_right" class="nav_right">
          <!-- mini toolbar for module -->
          <?php print($PAGE_MINIRIGHT); ?>

        </div> <!-- end:#minibar_right -->

        <div id="main">
  <?php
    print($PAGE_HTDATA);
    print("\n");
  ?>
        </div> <!-- end:#main -->
      </div> <!-- end:#body -->

      <div id="footer">

      </div> <!-- end:footer -->
    </div> <!-- end:container -->
  </body>
</html>