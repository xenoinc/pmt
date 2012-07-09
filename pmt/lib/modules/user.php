<?php

/* * **********************************************************
 * Copyright 2012 (C) Xeno Innovations, Inc.
 * ALL RIGHTS RESERVED
 * Author:       Damian J. Suess
 * Document:     user.php
 * Created Date: Mar 27, 2012
 *
 * Description:
 *  User module to handle creating, editing and mods to
 *  users and groups.
 *
 *  ** See Engineering document for more info
 *
 * Change Log:
 *  2012-0709 * Renamed 'pmtModule' to new interface name, 'iModule' [DJS]
 *            - Removed require of interface since the core includes it already [DJS]
 *  2012-0402 - Bypassed GenerateToolbar(). Return "" and use default.
 *            * Moved PageData() to $_pagedata();
 *  2012-0328 * fixed "makeLink" to include $pmtConf
 */

//require ("pmtModule.php");
class user implements iModule
{
  const MODULE = "user";
  //private $MODULE = "user";


  private $_title;      // Title of the screen
  private $_toolbar;    // HTML generated toolbar according to location
  private $_minileft;   // mini toolbar (left)
  private $_miniright;  // mini toolbar (right)
  private $_pagedata;   // Main page data

  //function __construct($uriPath = "")
  function __construct()
  {
    $this->_title = "User " . " - " . "[PMT]";    // "Xeno Tracking System"
    $this->_toolbar = ""; //$this->GenerateToolbar();
    $this->_minileft = "";
    $this->_miniright = "";
    $this->_pagedata = $this->GeneratePage();
  }

  public function Title() { return $this->_title; }             /* Title of the generated page */
  public function Toolbar() { return $this->_toolbar; }         /* Toolbar - HTML generated toolbar according to location */
  public function MiniBarLeft() { return $this->_minileft; }
  public function MiniBarRight() { return $this->_miniright; }
  public function PageData() { return $this->_pagedata; }



  /* **************************** */
  /* **************************** */

  private function GeneratePage()
  {
    /**
     * Depending on usr permissions settings, list all projects available to
     * the user logged in.
     */
    /*
     "pmt/user?cmd=create"
    if ($_GET["cmd"] == "create")
      pmtDebug("yes");
    else
      pmtDebug("no");
    */

    global $user;
    global $uri;

    $html = "";

    if (count($uri->seg) > 1)
          $proj_url = self::MODULE."/".$uri->seg[1];
    else  $proj_url = self::MODULE;


    if ($user->online)
    {
      $mode = "";
      switch (count($uri->seg))
      {
        case 1: // List all
          $mode = "";

          // Added the "isset" to suppress error messages of "cmd" not known
          if (isset($_GET["cmd"]) && $_GET["cmd"] == "logoff")
          {
            // Log Off
            $user->Logoff();
            header("Location: " . $uri->Anchor());
          }
      }
      $html =  "<h1<b><i>xeno</i>PMT</b> - User Setup</h1>";
      $html .= "<p>You're ONLINE!</p>";
      $html .= "<p>This system is still under heavy development and is not ";
      $html .= "ready for live action use by any means. Soon enough you will ";
      $html .= "get to see what the future holds.  As the project develops the ";
      $html .= "user and engineering documentation will mature along with it.</p>";
      $html .= "<p>Sit tight and enjoy the ride!</p>";
      $html .= "<p>&nbsp;</p>";
      $html .= "<p>- Xeno Innovations, Inc. -</p>";
    }
    else
    {
      $mode = "";
      switch (count($uri->seg))
      {
        case 1: // List all
          $mode = "";

          // Added the "isset" to suppress error messages of "cmd" not known
          if (isset($_GET["cmd"]) && $_GET["cmd"] == "login")
          {
            // Log In
            $mode = "login";
            $html = $this->GenerateLogin();
          }
          elseif (isset($_GET["cmd"]) && $_GET["cmd"] == "logoff")
          {
            // Log Off (just in case)
            $user->Logoff();
            header("Location: " . $uri->Anchor());
          }


          break;
        case 2: // view user profile
          $mode = "profile";

          /** (_GET["cmd"] ==
           * "remove"   - Remove user account
           * "suspend"  - Suspend user account
           * "edit"     - Edit user profile
           */
          break;
        default:

          $html =  "<h1<b><i>xeno</i>PMT</b> - User Setup</h1>";
          $html .= "<p>This system is still under heavy development and is not ";
          $html .= "ready for live action use by any means. Soon enough you will ";
          $html .= "get to see what the future holds.  As the project develops the ";
          $html .= "user and engineering documentation will mature along with it.</p>";
          $html .= "<p>Sit tight and enjoy the ride!</p>";
          $html .= "<p>&nbsp;</p>";
          $html .= "<p>- Xeno Innovations, Inc. -</p>";
          break;
      }
    }
    return $html;
  }


  private function GenerateLogin()
  {


    global $user;
    global $uri;

    if(isset($_POST["action"]) && $_POST["action"] == "login")
    {
      // Check if we can login
      $u = $_POST["login"];       // user
      $p = $_POST["password"];    // password
      $r = (isset($_POST["remember"]) ? true : false);
      // remember login
      // $ret = $user->Login($u, $p, $r);

      $ret = $user->Validate($u, $p);
      if ($ret)
      {
        $ret = $user->Login($u, $p, $r);
        // goto main page
        header("Location: ". $uri->Anchor());

      }

    }




    $ret = $uri->Anchor("user", "?cmd=login");  //  "/user/?cmd=login"

    $html ='
        <div id="login" class="login_form" align="center" style="padding: 2em;" >
        <table><tr><td>
          <form accept-charset="UTF-8" action="'. $ret .'" method="post">
          <!-- <form accept-charset="UTF-8" action="/session" method="post"> -->
            <input name="action" value="login" type="hidden" />
            <input name="utf8" value="✓" type="hidden" />'.
          /*
            <div style="margin: 0pt; padding: 0pt; display: inline;">
              <input type="hidden"
                name="authenticity_token"
                value="Sk3NI/mz6lwniDxpzFVWwMlNPZx0/Zw3yhtw7WolEK8=">
            </div>
          */
        '
            <h1>Log in</h1>';
    if($user->errors)
    {
      $html .='<div class="message error">';
      foreach($user->errors as $err)
      {
        $html .= $err . "<br />";
      }
      $html .='</div>';
    }

    $html .='
            <div class="formbody">
              <label for="login_field">
                Login or Email<br />
                <input autocapitalize="off" class="text" id="txtLogin" name="login"
                  style="width: 21em;" tabindex="1" type="text">
              </label>
              <br />
              <label for="password">
                Password '. /* AddLink("user", "(forgot password)", "?cmd=forgot") . */ '
                <br />
                <input autocomplete="disabled" class="text" id="txtPass" name="password"
                  style="width: 21em;" tabindex="2" value="" type="password">
              </label>
              <br />
              <br />
              <label for="remember">Remember
                <input type="checkbox" name="remember" value="1" id="remember" />
              </label>
              <br />
              <label class="submit_btn">
                <input name="commit" tabindex="3" value="Log in" type="submit">
              </label>
            </div>
          </form>
        </td></tr>
        </table>
        </div>';
    return $html;
  }

}

?>
