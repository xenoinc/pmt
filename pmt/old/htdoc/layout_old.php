<?php
/************************************************************
 * Copyright 2010 (C) Xeno Innovations, Inc.
 * ALL RIGHTS RESERVED
 *
 * Author:
 * Damian J. Suess
 * 
 * Description:
 * Generates the general layout of xiPMT
 *
 * Change Log:
 * [2010-1029] - initial creation
 */

/// Used to point to the Core Components
// $LOCAL_proj_path = $pmt_core_path ."/" . $pmt_proj_namespace;
$LOCAL_proj_path = $pmt_core_path . $pmt_proj_namespace;

/*
  print ("LOCAL_Proj_Path: " . $LOCAL_proj_path . "<br>");
  print ("PMT_Core_Path: " . $pmt_core_path . "<br>");
  print ("PMT_Proj_Namespace: " . $pmt_proj_namespace . "<br>");
  print ("PMT_Proj_Image_Mini: " . $pmt_proj_image_mini . "<br>");
*/

/**
 * Generate the Simple top-toolbar, this one is less-dynamic
 */
function Generate_MetaToolbar($site_path)
{
  /*******************************************************
   * Toolbar :: Adjacent mini-bar
   * -------------
   * Logon/logoff | Preferences | Help/Guide | About PMT
   *******************************************************/
  $s6 = str_pad("", 6);  // keep things pretty

  // $toolbar1 = "";    // Actually generate Login / Logoff
  // $toolbar1 += "";   // "logged in as djsuess | Logout"
  
  // test-template
  $toolbar1 = "<ul>" .
              "<li class='first'><a href='" . $site_path . "/login'>Login</a></li>" .
              "<li><a href='" . $site_path . "/prefs'>Preferences</a></li>" .
              "<li><a href='" . $site_path . "/wiki/Help'>Help/Guide</a></li>" .
              "<li class='last'><a href='" . $site_path . "/about'>About PMT</a></li>" .
              "</ul>";
  print $toolbar1;
}


/**
 * Generate the Main Toolbar (dynamic!)
 * Layout depends upon the following:
 *   - User Type / Priv
 *   -
 */
function Generate_MainToolbar($site_path)
{
  /******************************************************************************************
   * Toolbar :: Main Navigation
   * -------------
   * Wiki | ToDo List | Timeline| Roadmap | Browse Source | View Ticket | New Ticket | Search | Admin
   ******************************************************************************************/
  
  $toolbar2 = "<ul>" .
              "<li class='first active'><a href='" . $site_path . "/wiki'>Wiki</a></li>".
              "<li><a href='" . $site_path . "/timeline'>Timeline</a></li>".
              "<li><a href='" . $site_path . "/roadmap'>Roadmap</a></li>".
              "<li><a href='" . $site_path . "/browser'>Browse Source</a></li>".
              "<li><a href='" . $site_path . "/report'>View Tickets</a></li>".
              "<li><a href='" . $site_path . "/newticket'>New Ticket</a></li>".
              "<li><a href='" . $site_path . "/search'>Search</a></li>".
              "<li class='last'><a href='" . $site_path . "/admin' title='Administration'>Admin</a></li>".
              "</ul>";
  print $toolbar2;
}


function Generate_Main()
{
  // load the wiki
  print ("<center><h1>This is where the WIKI goes</h1></center>");
}


?>
<!--
  xi Project Management Tracking System
  Copyright 2010 (C) Xeno Innovations, Inc.
-->

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">


  <head>
    <title>
      <?php print($pmt_proj_title); ?>
    </title>
    
    <!-- debug -->
    <link rel="stylesheet"  href="<?php print($pmt_core_path); ?>/htdoc/default.css" type="text/css" />


    <link rel="search"      href="<?php print($LOCAL_proj_path); ?>/search" />
    <link rel="help"        href="<?php print($LOCAL_proj_path); ?>/wiki/Help" />
    <link rel="alternate"   href="<?php print($LOCAL_proj_path); ?>/wiki/WikiStart?format=txt" type="text/x-trac-wiki" title="Plain Text" />
    <link rel="start"       href="<?php print($LOCAL_proj_path); ?>/wiki" />
    <link rel="stylesheet"  href="<?php print($LOCAL_proj_path); ?>/chrome/common/css/trac.css" type="text/css" />
    <link rel="stylesheet"  href="<?php print($LOCAL_proj_path); ?>/chrome/common/css/wiki.css" type="text/css" />

    <link rel="shortcut icon" href="<?php print($pmt_proj_image_icon); ?>" type="image/gif" />
    <link rel="icon"          href="<?php print($pmt_proj_image_icon); ?>" type="image/gif" />
    <link rel="search"
          type="application/opensearchdescription+xml"
          href="<?php print($LOCAL_proj_path); ?>/search/opensearch"
          title="Search <?php print($pmt_proj_title); ?>" />

  </head>
  <body>
    <?php print(getcwd()); ?>
    <div id="banner">
      <div id="header">
        <a id="logo" href="<?php print($pmt_proj_image_logo_link); ?>" >
          <img src="<?php print($pmt_proj_image_logo);?>"
               alt="<?php print($pmt_proj_image_logo_alt);?>"
               width="<?php print($pmt_proj_image_logo_width);?>"
               height="<?php print($pmt_proj_image_logo_height);?>" />
        </a>
      </div>
      <form id="search" action="<?php print($LOCAL_proj_path); ?>/search" method="get">
        <div>
          <label for="proj-search">Search:</label>
          <input type="text" id="proj-search" name="q" size="18" value="" />
          <input type="submit" value="Search" />
        </div>
      </form>
      <div id="metanav" class="nav">
        <?php Generate_MetaToolbar($LOCAL_proj_path); ?>
      </div>
    </div>
    <div id="mainnav" class="nav">
      <?php Generate_MainToolbar($LOCAL_proj_path); ?>
    </div>
    <div id="main">
      <?php Generate_Main($LOCAL_proj_path); ?>
    </div>

    <!-- footer -->
    <div id="footer" lang="en" eml:lang="en">
      <hr />
      <a id="xtracking" href="http://pmt.xenoinc.org/">
        <img src="<?php print($pmt_proj_image_mini);?>" height="30" width="107" alt="xiPMT" />
      </a>
      <p class="left">
        Powered by <b>xiPMT v0.1</b><br />
        By <a href="http://www.xenoinc.org/">Xeno Innovations</a>.
      </p>
      <p class="right">Brought to you by:<br /><a href="http://www.xenoinc.org/">Xeno Innovations, Inc.</a></p>
   </div>
    
  </body>
</html>