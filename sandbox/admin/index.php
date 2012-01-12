<?php
/************************************************************
* Copyright 2010 (C) Xeno Innovations, Inc.
* ALL RIGHTS RESERVED
* Author:        Damian J. Suess
* Document:      index
* Created Date:  Oct 31, 2010, 11:03:17 PM
* Description:
* Change Log:
***********************************************************/

include_once "security.php";
$class = new pmtSecurity();
define("CLS_SECURITY", $class->isUserOffline());   // So we can access it inside of functions





//$wnd = $HTTP_GET_VARS["wnd"];  // old way
$wnd = $_GET["wnd"];
/**
 * Generate the Simple top-toolbar, this one is less-dynamic
 */


function Generate_MetaToolbar($cur_wnd)
{
  /*******************************************************
   * Toolbar :: Adjacent mini-bar
   * -------------
   * Logon/logoff | Preferences | Help/Guide | About PMT
   *******************************************************/
  $s6 = str_pad("", 6);  // keep things pretty

  // $toolbar1 = "";    // Actually generate Login / Logoff
  // $toolbar1 += "";   // "logged in as djsuess | Logout"


  /*
  // **[ Perform simple auto Login page ]*********************
  // check if password cookie is set
  $TEMP_LOGIN = array("fuct" => "gstk09");
  if (!isset($_COOKIE['verify'])){
    $TEXT_LOGIN = "<li class='first'><a href='?wnd=login' title='cookie not set' >Login</a></li>";
  }else{
    $found = false;   // check if cookie=good
    foreach($TEMP_LOGIN as $key=>$val){
      // print ($key);
      $lp = (true ? $key : "") . "%" . $val;    // yes, cycle through password list.  is this me?
      if ($_COOKIE["verify"] == md5($lp)){
        $found = true;
        if (TIMEOUT_CHECK_ACTIVITY)
          setcookie("verify", md5($lp), $timeout, '/');   // prolong timeout
        break;
      }
    }
    if (!$found)
      $TEXT_LOGIN = "<li class='first'><a href='?wnd=login' title='bad login'>Login</a></li>";
    else{
      $TEXT_LOGIN = "<li class='first'>Welcome, fuct</li>" .
                    "<li><a href=?wnd=login&logout>logout</a></li>";
    }
  }
  // *******************************************************
  */

  
  switch (CLS_SECURITY) //->isUserOffline())
  {
      case 0:
        $TEXT_LOGIN = "<li class='first'>Welcome, fuct</li>" .
                      "<li><a href=?wnd=login&logout>logout</a></li>";
        break;
      case 1:
        $TEXT_LOGIN = "<li class='first'><a href='?wnd=login' title='cookie not set' >Login</a></li>";
        break;
      case 2;
        $TEXT_LOGIN = "<li class='first'><a href='?wnd=login' title='bad login'>Login</a></li>";
        break;
      default:
        $TEXT_LOGIN = "";
        break;
  }
  
  // test-template
  $toolbar1 = "<ul>" .
              $TEXT_LOGIN .
              // "<li><a href='?wnd=mypref'>Preferences</a></li>" .
              // "<li><a href='?wnd=help'>Help/Guide</a></li>" .
              "<li class='last'><a href='?wnd=about'>About PMT</a></li>" .
              "</ul>";
  
  print $toolbar1;
}


/**
 * Generate the Main Toolbar (dynamic!)
 * Layout depends upon the following:
 *   - User Type / Priv
 *   -
 */
function Generate_MainToolbar($cur_wnd)
{

  // set where the "First Active" should go
  switch($cur_wnd)
  {
    case "main":

      break;
    case "view customer":
      break;
  }

  $toolbar2 = "<ul>" .
              "<li class='first active'><a href='?wnd=main'>main</a></li>".
              "<li><a href='?wnd=customer' title='Register/Modify existing Customers'>Customers</a></li>".
              "<li><a href='?wnd=user'>Users</a></li>".
              "<li><a href='?wnd=product'>Available Products</a></li>".
              "<li><a href='?wnd=project'>Project Workspace</a></li>".
              // "<li class='last'><a href='?wnd=admin' title='this does nothing'>Admin</a></li>".
              "</ul>";
  print $toolbar2;
}

function Generate_Main($cur_wnd)
{
  // print ("wnd: " . $cur_wnd);
  if ($cur_wnd == "")
    $cur_wnd = "main";

  $pth = "wnd_" . $cur_wnd . ".php";

  if (file_exists($pth))
    include $pth;
  else
  {
print(
"
<div align='center'>
  <div>
    <p>&nbsp;</p>
    <h1>Extension could not be found</h1>
  </div>
  <div style='width: 800px;'>
    <p>
      An error occurred while attempting to execute: '" . $pth . "'.<br />If you believe this
      feature should exist, then talk to the asshole running the show!
    </p>
    <p>&nbsp;</p>
  </div>
</div>

");
  }
    
}


?>



<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">


  <head>
    <title>
      <?php print($pmt_proj_title); ?>
    </title>

    <!-- debug -->
    <link rel="stylesheet"  href="admin.css" type="text/css" />


    <link rel="search"      href="/search" />
    <link rel="start"       href="index.php" />

    <link rel="shortcut icon" href="img/page-icon.gif" type="image/gif" />
    <link rel="icon"          href="img/page-icon.gif" type="image/gif" />
    <link rel="search"
          type="application/opensearchdescription+xml"
          href="/pmt//search/opensearch"
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
          <img src="\img\xi-banner.png"
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
    <div id="footer">  <!-- lang="en" eml:lang="en">-->
      <hr />
      <a id="xtracking" href="http://pmt.xenoinc.org/">
        <img src="img/xiTrac_logo_mini.png" height="30" width="107" alt="xiPMT" />
      </a>
      <p class="left">
        Powered by <b>xiPMT v0.1</b><br />
        By <a href="http://www.xenoinc.org/">Xeno Innovations</a>.
      </p>
      <p class="right">Brought to you by:<br /><a href="http://www.xenoinc.org/">Xeno Innovations, Inc.</a></p>
   </div>

  </body>
</html>