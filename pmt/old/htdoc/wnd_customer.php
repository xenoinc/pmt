<?php
/************************************************************
* Copyright 2011 (C) Xeno Innovations, Inc.
* ALL RIGHTS RESERVED
* Author:       Damian J. Suess
* Document:     wnd_customer.php
*   Created Date: 2011-10-20
*
* Description:
* Customer viewer/editor
*
* Change Log:
* 
*/

include_once "../lib/security.php";
$class = new pmtSecurity();
define("CLS_SECURITY", $class->isUserOffline());   // So we can access it inside of functions


// are we logged in? 0=online, 1=fail,2=fail
// [2011-10-20] Disabled only for testing purposes
// if (CLS_SECURITY != 0)   <--- OG Command. For now we will bypass
if (CLS_SECURITY < 0)
{
  
?>
<div style="margin: 20px;">
  <div>
    <h1>xiPMT Customer Editor</h1>
  </div>
  <p>
    <i>You are currently offline</i>
  </p>
  <ul>
    <li>Create Project Spaces</li>
    <li>Administrate existing Projects 'config.php'</li>
    <li>Setup Users</li>
    <ul>
      <li>Assign Project Managers</li>
      <li>Set Developers</li>
      <li>Allow general public to view or not</li>
      <li>Permit self-contained users (not attached to Customers)</li>
      <li>Assign SVN Repo</li>
      <li>Assign Project physical path on the web</li>
      <li>Setup Project Database and Details</li>
    </ul>
    <li>And much more..</li>
  </ul>

</div>
<?php

}else{

?>

  <link type="text/css" href="htdoc/css/jquery-ui-1.8.15.custom.css" rel="stylesheet" />
  <script type="text/javascript" src="htdoc/js/jquery-1.6.2.min.js"></script>
  <script type="text/javascript" src="htdoc/js/jquery-ui-1.8.15.custom.min.js"></script>
  <script type="text/javascript">
    $(function(){
      // Tabs
      $('#tabs').tabs();
      var $tabs = $('#tabs').tabs(); // first tab selected

      $('#my-text-link1').click(function() { // bind click event to link
        $tabs.tabs('select', 0); // switch to first tab
        //window.location.hash = "top";
        return false;
      });

      $('#my-text-link2').click(function() { // bind click event to link
        $tabs.tabs('select', 1); // switch to second tab
        //window.location.hash = "top";
        return false;
      });

      $('#my-text-link3').click(function() { // bind click event to link
        $tabs.tabs('select', 2); // switch to third tab
        window.location.hash = "anchor3"; //"anchor3";
        return false;
      });

      //reloads the hash after changing tabs, allowing the link to be clicked a second time and still jump to the anchor
      $tabs.tabs({
        select: function(event, ui) {
          window.location.hash = "";
          return true;
        }
      });
    });
  </script>
  <style type="text/css">
    /*demo page css*/
    /*body{ font: 62.5% "Trebuchet MS", sans-serif; margin: 50px;}*/
    .demoHeaders { margin-top: 2em; }
    #dialog_link {padding: .4em 1em .4em 20px;text-decoration: none;position: relative;}
    #dialog_link span.ui-icon {margin: 0 5px 0 0;position: absolute;left: .2em;top: 50%;margin-top: -8px;}
    ul#icons {margin: 0; padding: 0;}
    ul#icons li {margin: 2px; position: relative; padding: 4px 0; cursor: pointer; float: left;  list-style: none;}
    ul#icons span.ui-icon {float: left; margin: 0 4px;}
  </style>

  <div style="margin: 20px;">
    <div>
      <h1>xiPMT Customer Editor</h1>
    </div>
    
    <a name="top" id="top"></a>
    <!-- Tabs -->
    <h2 class="demoHeaders">Tabs</h2><br>
    <a id="my-text-link1" href="#0">Go to first tab</a><br>
    <a id="my-text-link2" href="#1">Go to second tab</a><br>
    <a id="my-text-link3" href="#2">Go to third tab, scroll to anchor</a><br>
    <div id="tabs">
      <ul>
        <li><a href="#tabs-1">Search for Customer</a></li>
        <li><a href="#tabs-2">Edit Customers</a></li>
        <li><a href="#tabs-3">Third</a></li>
      </ul>


      <div id="tabs-1">
        Customer Viewer
      </div>

      <div id="tabs-2">
        Customer Editor.
      </div>

      <div id="tabs-3">
        Do other shit

        Sample anchor inside of a tab.
        <a name="anchor3" id="anchor3">&#160;</a>
      </div>
    </div>
  </div>

  
<?php


}

?>
