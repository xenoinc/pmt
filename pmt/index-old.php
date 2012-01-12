<?php
  /************************************************************
   * Copyright 2010 (C) Xeno Innovations, Inc.
   * ALL RIGHTS RESERVED
   * Author:       Damian J. Suess
   * Document:     index
   * Created Date: Oct 31, 2010, 11:03:17 PM
   *
   * Description:
   * Core-Entry point.  If you access this directly you will be asked
   * to do the following (depending on system settings in main MySQL DB)
   *
   *   1) Select a project to view
   *   2) Login to the Admin Panel
   *   3) Login as a Customer
   *   4) Login as an Employee (Development, Management, Support, etc.)
   *
   * Change Log:
   * [2010-1030] - Initial Creation
   *
 */


  /// Login Libary
  include_once "lib/security.php";
  $class = new pmtSecurity();
  define("CLS_SECURITY", $class->isUserOffline());   // So we can access it inside of functions

  require "lib/http.php"; /// Render main page

  //include_once "config.php";

  // In order of presidence
  // if (isset($_GET["wnd"]))

  $wnd = $_GET["wnd"];          // Show window
  $wiki = $_GET["wiki"];        // show wiki page
  $ticket = $_GET["ticket"];    // Display Ticket number



  define('PMT_PATH',str_replace(pathinfo(__FILE__,PATHINFO_BASENAME),'',__FILE__));
    // print("path: " . PMT_PATH);
  require('lib/config.php');  // Core libary


?>

<!--
<span class="section">UTC clock</span>
<div class="section">
  <div class="sectionTop"> </div>
  <div class="row">23:38 on Jul 24, 2011</div>
</div>
-->
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">


  <head>
    <title>
      <?php print($pmt_proj_title); ?>
    </title>

    <!-- debug -->
    <link rel="stylesheet"  href="htdoc/pmt.css" type="text/css" />


    <link rel="search"      href="/search" />
    <link rel="start"       href="index.php" />

    <link rel="shortcut icon" href="img/page-icon.gif" type="image/gif" />
    <link rel="icon"          href="img/page-icon.gif" type="image/gif" />
    <link rel="search"
          type="application/opensearchdescription+xml"
          href="/pmt/search/opensearch"
          title="Search <?php print($pmt_proj_title); ?>" />

  </head>
  <body>
<?php
      //print(getcwd());
      //print ("wnd2" . $wnd);
?>
    <div id="banner">
      <div id="header" style="text-align:center">
        <a id="logo" href="index.php" >
          <img src="htdoc/img/xi-banner.png"
               alt="xenoinc banner"
               width="800"
               height="90" />
        </a>
      </div>
      <div id="metanav" class="nav">
        <?php Generate_MetaToolbar($cur_wnd); ?>
      </div>
    </div>
    
    <div id="mainnav" class="nav">
      <?php Generate_MainToolbar($wnd); ?>
    </div>
    
    
    <div id="main">
      <?php Generate_Main($wnd); ?>
    </div>
    
    
    <hr />
    <div id="footer">  <!--  lang="en" eml:lang="en"> -->
      <p class="left">
        Powered by <b>xiPMT v0.1</b><br />
        By <a href="http://www.xenoinc.org/">Xeno Innovations</a>.
      </p>
      &nbsp;
      <a id="xtracking" href="http://www.xenoinc.org/" target="_blank" >
        <img src="htdoc/img/xiTrac_logo_mini.png" height="30" width="107" alt="xiPMT" />
      </a>
      &nbsp;
      <p class="right">Brought to you by:<br /><a href="http://www.xenoinc.org/">Xeno Innovations, Inc.</a></p>
    </div>
  </body>
</html>

