<?php
/************************************************************
 * pmTrack (xiPMT, xiPMTrack)
 * Copyright 2010-2012 (C) Xeno Innovations, Inc.
 * ALL RIGHTS RESERVED
 * Author:       Damian J. Suess
 * Document:     index-test.php
 * Created Date: Oct 31, 2010, 11:03:17 PM
 *
 * Description:
 *  TEST Core-Entry point.  If you access this directly you will be asked
 *  to do the following (depending on system settings in main MySQL DB)
 *
 *    1) Select a project to view
 *    2) Login to the Admin Panel
 *    3) Login as a Customer
 *    4) Login as an Employee (Development, Management, Support, etc.)
 *
 * Change Log:
 * 2012-0112 - remvoed front page. we're using a dynamic generator
 * 2010-1030 - Initial Creation
 *
 */

// Development Debug Mode
define("DebugMode", true);

// This is used to test quick code
define("DebugIndex", false);

// Define absolute path
define('PMT_PATH',str_replace(pathinfo(__FILE__,PATHINFO_BASENAME),'',__FILE__));
/*
 * __FILE__                             = C:\prog\Apache2.2\htdocs\pmt\index.php
 * PATHINFO_BASENAME                    = 2
 * pathinfo(__FILE__,PATHINFO_BASENAME) = index.php
 * str_replace(..)                      = C:\prog\Apache2.2\htdocs\pmt\
 *
 */


if (DebugIndex == false)
{

  // print("path: " . PMT_PATH);

  include_once "xpmt/pmt.php";
  PmtParseURL();


  /*
  if (isset($pmtConf["general"]["authorized_only"]) &&
      $pmtConf["general"]["authorized_only"] == true &&
      !$user->loggedin &&
      @$_POST["action"] != "login" &&
       ($uri->seg[0] != "user" &&
        $uri->seg[1] != "register"))
  {
    include(template("user/login"));
    exit;
  }
  */


  /* OLD
  /// Login class
  include_once "xpmt/security.php";
  $class = new pmtSecurity();
  define("CLS_SECURITY", $class->isUserOffline());   // So we can access it inside of functions

  require "xpmt/http.php"; /// Render main page

  // In order of presidence
  // if (isset($_GET["wnd"]))
  $wnd = $_GET["wnd"];          // Show window
  $wiki = $_GET["wiki"];        // show wiki page
  $ticket = $_GET["ticket"];    // Display Ticket number

  define('PMT_PATH',str_replace(pathinfo(__FILE__,PATHINFO_BASENAME),'',__FILE__));
    // print("path: " . PMT_PATH);
  require('xpmt/config.php');  // Core configuration
  */

}
else
{


  // ** debugging
  print("<html><body>");

  print("<pre>");
  print_r($_GET);
  // print ($_SERVER['REQUEST_URI']);
  print("</pre><br><br>");

  /* Samples:
  * ####################################
  * # RewriteRule ^project/(.*)/ticket/(.*) index.php?project=$1&ticket=$2 [L]
  * # RewriteRule .* index.php
  * URL: "http://pmt/project/xRehab/ticket/2948"
      Array
      (
          [project] => xRehab
          [ticket] => 2948
      )
  * URL: "http://pmt/?test=234"
      Array
      (
          [test] => 234
      )
  *
  *
  * ####################################
  * #RewriteRule ^(.*)$ index.php?PAGE=$1 [L,QSA]
  * URL: http://pmt/project/xRehab/ticket/2948
  * Returns:
      Array
      (
          [PAGE] => project/xRehab/ticket/2948
      )
  * #########################################
  * # RewriteRule ^project/(.*)/ticket/(.*) ?project=$1&ticket=$2 [L]
  * URL: http://pmt/project/xRehab/ticket/2948
  * Returns:
      Array
      (
        [project] => xRehab
        [ticket] => 2948
      )
  */

  /*
    //print("<pre>");
    $url = (!empty($_SERVER['HTTPS'])) ? "https://".$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'] : "http://".$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'];
    print ("<pre>" . $url . "</pre>");
    print ("<HR>");

    print("<pre>");
    print_r(parse_url($url));
    print("</pre>");

    print ("<HR>");
    print("<pre>");
    print parse_url($url, PHP_URL_PATH);
    print("</pre>");
  */

  print("</body></html>");

}

?>
