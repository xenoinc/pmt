<?php
/************************************************************
* Copyright 2010 (C) Xeno Innovations, Inc.
* ALL RIGHTS RESERVED
* Author:       Damian J. Suess
* Document:     http.php
* Created Date: Nov 27, 2010, 11:03:17 AM
*
* Description:
* Used to render the INDEX.PHP file and clean up the code.
*
*
* Change Log:
* 2010-1127 - Initial Creation
*/


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


  switch (CLS_SECURITY) //->isUserOffline())
  {
      case 0:
        $TEXT_LOGIN = "<li class='first'>Welcome, fuct</li>" .
                      "<li><a href=?wnd=login&logout>logout</a></li>";
        break;
      // We're logged in
      case 1:
        //$TEXT_LOGIN = "<li class='first'>" .
        //              "<a href='?wnd=login' title='Please login' >Login</a>".
        //              "</li>";

        $TEXT_LOGIN = "<form id='login' action='?wnd=login' method='post'>".
                      "<input type='text'      name='txtUser'  id='user' size='18' value='' 'AUTOCOMPLETE='off' />".
                      "<input type='password'  name='txtPass'  id='pass' size='18' value='' 'AUTOCOMPLETE='off' />".
                      "<input type='submit'    name='cmdLogin' value='Login' />".
                      "</form>";

        break;
      case 2;
        // We're logged in
        $TEXT_LOGIN = "<li class='first'>Login error, perform <a href='?wnd=login&logout' title='bad login'>Logout</a> now!</li>";
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

  $pth = "htdoc/";
  $pth .= "wnd_" . $cur_wnd . ".php";

  if (file_exists($pth))
    include $pth;
  else
  {
    print("
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
  </div>");
  }

}

?>