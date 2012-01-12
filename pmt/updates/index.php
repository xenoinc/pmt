<?php
  /************************************************************
   * Copyright 2010 (C) Xeno Innovations, Inc.
   * ALL RIGHTS RESERVED
   * Author:       Damian J. Suess
   * Document:     index.php
   * Created Date: Oct 31, 2010, 11:03:17 PM
   *
   * Description:
   * View the product page only if the user is currently logged
   * into the system and has approprate access.  Otherwise
   * redirect the user back to the main root page.
   * 
   * Temp-Description:
   * Core-Entry point for the Update Engine web interface
   * 
   * When accessing the page directly it will provide options baised upon
   * the person's login credientials to the PMT system.  For now only prompt
   * that this is un unaccessable page.
   * 
   * Competition:
   * - "http://cia.vc/    -  Main Stats Page"
   * 
   * To Do:
   * 
   * 
   * Change Log:
   * [2011-0816] - Modified for alpha to only display "Access Denied"
   * [2010-1030] - Initial Creation
   *
   */

/* [ Full PMT access disabled during alpha testing ]
   
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

*/

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
      <?php
        // print($pmt_proj_title);  // * Removed during Alpha testing [2011-0816]
        
      ?>
    </title>
    <meta http-equiv="content-type" content="text/html;charset=utf-8" />
    
    <!-- debug -->
    <link rel="stylesheet"  href="../htdoc/pmt.css" type="text/css" />


    <!-- <link rel="search"      href="/search" /> -->
    <link rel="start"       href="index.php" />

    <link rel="shortcut icon" href="../img/page-icon.gif" type="image/gif" />
    <link rel="icon"          href="../img/page-icon.gif" type="image/gif" />
    <link rel="search"
          type="application/opensearchdescription+xml"
          href="/pmt/search/opensearch"
          title="Search <?php //print($pmt_proj_title); ?>" />

  </head>
  <body>
<?php
      //print(getcwd());
      //print ("wnd2" . $wnd);
?>
    <div id="banner">
      <div id="header" style="text-align:center">
        <a id="logo" href="index.php" >
          <img src="../htdoc/img/xi-banner.png"
               alt="xenoinc banner"
               width="800"
               height="90" />
        </a>
      </div>
      <div id="metanav" class="nav">
        <?php // Generate_MetaToolbar($cur_wnd); ?>
      </div>
    </div>
    
    <div id="mainnav" class="nav">
      <?php // Generate_MainToolbar($wnd); ?>
    </div>
    
    
    <div id="main">
      <?php // Generate_Main($wnd); ?>
      
      <center>
        Product listing page is currently not available for public
        viewing.  Please redirect yourself back to the main page.
      </center>
      
    </div>
    
    
    <hr />
    <div id="footer" lang="en" eml:lang="en">
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

