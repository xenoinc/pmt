<?php

/* * **********************************************************
 * Copyright 2012 (C) Xeno Innovations, Inc.
 * ALL RIGHTS RESERVED
 * Author:       Damian Suess
 * Document:     kb.php - Knowledge Base SKIN
 * Created Date: July 2, 2012
 *
 * Description:
 *  This is a sample of the KB template baised off of MAIN.PHP
 *
 * Change Log:
 *  2012-0702 + Added to include KB custom style sheet
 */

// page data to display
global $pmtConf;
global $PAGE_PATH;        // "http://pmt/xpmt/themes/default/"
global $PAGE_TITLE;       // Page title
global $PAGE_CSS;         // todo: Add to global var list
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
    <link type="text/css" href="<?php print($PAGE_PATH); ?>kb.css" rel="stylesheet" />
    <?php
      if (isset($PAGE_CSS) && $PAGE_CSS != "")
        print (
          "    <link type\"text/css\" href=\"" . $PAGE_PATH . $PAGE_CSS . "\" rel=\"stylesheet\" />\n"
        );
    ?>

    <script type="text/javascript" src="<?php print($PAGE_PATH); ?>../../libraries/jquery/jquery-1.6.2.min.js"></script>
    <script type="text/javascript" src="<?php print($PAGE_PATH); ?>../../libraries/markitup/jquery.markitup.js"></script>
    <script type="text/javascript" src="<?php print($PAGE_PATH); ?>../../libraries/markitup/sets/default/set.js"></script>
    <link rel="stylesheet" type="text/css" href="<?php print($PAGE_PATH); ?>../../libraries/markitup/skins/markitup/style.css" />
    <link rel="stylesheet" type="text/css" href="<?php print($PAGE_PATH); ?>../../libraries/markitup/sets/default/style.css" />
    <?php  //print($PAGE_PATH . "skin.css");
    /*
      <script type="text/javascript" src="../libraries/jquery/jquery-1.6.2.min.js"></script>             <!-- jQuery -->
      <script type="text/javascript" src="../libraries/markitup/jquery.markitup.js"></script>            <!-- markItUp! -->
      <script type="text/javascript" src="../libraries/markitup/sets/default/set.js"></script>           <!-- markItUp! toolbar settings -->
      <link rel="stylesheet" type="text/css" href="../libraries/markitup/skins/markitup/style.css" />    <!-- markItUp! skin -->
      <link rel="stylesheet" type="text/css" href="../libraries/markitup/sets/default/style.css" />      <!--  markItUp! toolbar skin -->
    */
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