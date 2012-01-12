<?php
/************************************************************
* Copyright 2010 (C) Xeno Innovations, Inc.
* ALL RIGHTS RESERVED
* Author:        Damain J. Suess
* Document:      wnd_login
* Created Date:  Nov 4, 2010, 11:44:26 PM
* Description:
*   Login to the admin panel
***********************************************************/


$TEMP_LOGIN = array("fuct" => "gstk09");                // me
define("USE_USERNAME", true);                           // me again
define("REDIR_LOGOUT", "http://localhost/admin/");      // return to
define("REDIR_LOGON", "http://localhost/admin/");       // return to
define("TIMEOUT_MINUTES", 720);                         // 12 hrs
define("TIMEOUT_CHECK_ACTIVITY", false);
// timeout in seconds
$timeout = (TIMEOUT_MINUTES == 0 ? 0 : time() + TIMEOUT_MINUTES * 60);

// logout?
if(isset($_GET['logout'])) {
  setcookie("verify", '', $timeout, '/'); // clear password;
  header('Location: ' . REDIR_LOGOUT);
  exit();
}


function Display_Logged_In_Page()
{
  header('Refresh: 3; url=' . REDIR_LOGON);
?>

  <div style="margin: 20px;">
  <div align="center">
    <div>
      <h1>You are logged in to the xiPMT Administration page</h1>
      <i>Only Xeno Innovations, Inc. Employees Allowed</i>
      <p>redirecting in 3 seconds</p>
    </div>

  </div>
</div>
<?PHP
}



if(!function_exists('show_login')) {


function show_login($err_msg)
{
  // print("user: " . USE_USERNAME . "  pass: " );
?>
<div style="margin: 20px;">
  <div align="center">
    <div>
      <h1>Login to the xiPMT Administration page</h1>
      <i>Only Xeno Innovations, Inc. Employees Allowed</i>
    </div>
    <div style="color:red;">
      <?php print($err_msg); ?>
    </div>

    <form id="login" action="?wnd=login" method="post">
      <input type="text"      name="txtUser"  id="user" size="18" value="" <?php print('AUTOCOMPLETE="off"');?> />
      <input type="password"  name="txtPass"  id="pass" size="18" value="" <?php print('AUTOCOMPLETE="off"');?> />
      <input type="submit"    name="cmdLogin" value="Login" />
    </form>
  </div>
</div>

<?php
}
}

  // Keep track of failues & block IP for 30 minutes
  // insert into XI_ADMIN_SESSION table (ip, user_id, dttm, fail_attempt);

  if (isset($_POST["txtPass"])){

    $login = isset($_POST['txtUser']) ? $_POST['txtUser'] : '';
    $pass = $_POST['txtPass'];

    // print("user: " . $login . "  pass: " . $pass );

    if (!USE_USERNAME && !in_array($pass, $TEMP_LOGIN) ||
       ( USE_USERNAME && (!array_key_exists($login, $TEMP_LOGIN) ||
                          $TEMP_LOGIN[$login] != $pass)))
    {
      show_login("<i>Incorrect Password!</i>");

    }else{

      setcookie("verify", md5($login."%".$pass), $timeout, "/");
      unset($_POST["txtUser"]);
      unset($_POST["txtPass"]);
      unset($_POST["cmdLogin"]);

      Display_Logged_In_Page();

    }

  }else{

    // check if password cookie is set
    if (!isset($_COOKIE['verify'])){
      show_login("");
    }else{

      $found = false;   // check if cookie=good

      foreach($TEMP_LOGIN as $key=>$val){

        $lp = (USE_USERNAME ? $key : "") . "%" . $val;
        if ($_COOKIE["verify"] == md5($lp)){
          $found = true;
          if (TIMEOUT_CHECK_ACTIVITY)
            setcookie("verify", md5($lp), $timeout, '/');   // prolong timeout
          break;
        }
      }
      if (!$found)
        show_login ("");
      else
        Display_Logged_In_Page();
    }
  }
  
?>