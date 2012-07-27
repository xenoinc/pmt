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
global $PAGE_TITLE;
global $PAGE_TOOLBAR;
global $PAGE_METABAR;
global $PAGE_HTDATA;
global $PAGE_PATH;
global $pmtConf;

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
    
    <script type="text/javascript" src="../../libraries/jquery/jquery-1.6.2.min.js"></script>             <!-- jQuery -->
    <script type="text/javascript" src="../../libraries/markitup/jquery.markitup.js"></script>            <!-- markItUp! -->
    <script type="text/javascript" src="../../libraries/markitup/sets/default/set.js"></script>           <!-- markItUp! toolbar settings -->
    <link rel="stylesheet" type="text/css" href="../../libraries/markitup/skins/markitup/style.css" />    <!-- markItUp! skin -->
    <link rel="stylesheet" type="text/css" href="../../libraries/markitup/sets/default/style.css" />      <!--  markItUp! toolbar skin -->
    <?php  //print($PAGE_PATH . "skin.css"); ?>
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

      </div>
      <div id="minibar_right" class="nav_right">
        <!-- mini toolbar for module -->
        <?php print($PAGE_MINIRIGHT); ?>

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