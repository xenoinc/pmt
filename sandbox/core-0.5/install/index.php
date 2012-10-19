<?php
/** * *********************************************************
 * Copyright 2012 (C) Xeno Innovations, Inc.
 * ALL RIGHTS RESERVED
 * @Author:       Damian Suess
 * Document:      index.php
 * Created Date:  Oct 4, 2012
 * Status:        {unstable}
 * Description:
 *  Installer (v0.0.5)
 *
 * Change Log:
 *  2012-1015 * A lot of updates occurred
 *            + Added BETA_TESTING boolean switch
 *  2012-1013 + Fixed DB Connection Tester. Using:  mysqli(..) not mysql_connect(..)
 *  2012-1004 + Initial creation
 */
/* Steps
 * 0. Check for Request DB Clear
 *  i. Get config(.user).php data of DB conn & remove
 *
 * 1. Display database information form
 *  i. Get Server, User, Pass, DB_Name and Table Prefix
 *
 * 2. Display Admin Config form
 *  i.  user, pass, email and display name
 *  ii. and Use Clean URI (checkbox)
 *
 * 3. Install xenoPMT
 *  i.    Save config file
 *  ii.   Write generic database
 *  iii.
 *
 */

require "../xpmt/phpConsole.php";
PhpConsole::start(true, true, dirname(__FILE__));

/* ******************** */
$BETA_TESTING = true;
if ($BETA_TESTING)
{
  $_txtDBServer = "localhost";
  $_txtDBName   = "PMT_TEST";
  $_txtDBPrefix = "XI_";
  $_txtDBUser   = "betauser";
  $_txtDBPass   = "betapass";

  $_txtCfgSiteName = "xenoPMT 0.0.5";
  //$_optCfgCleanURI    = true;
  $_txtCfgAdminDisp   = "Administrator";
  $_txtCfgAdminUser   = "admin";
  $_txtCfgAdminPass   = "pass";
  $_txtCfgAdminEmail  = "noreply@localhost";
}
else
{
  $_txtDBServer = "localhost";
  $_txtDBName   = "PMT_DATA";
  $_txtDBPrefix = "XI_";          // $_txtDBPrefix = "XPMT_";
  $_txtDBUser   = "";
  $_txtDBPass   = "";

  $_txtCfgSiteName    = "xenoPMT";
  //$_optCfgCleanURI    = true;
  $_txtCfgAdminDisp   = "Administrator";
  $_txtCfgAdminUser   = "";
  $_txtCfgAdminPass   = "";
  $_txtCfgAdminEmail  = "";
}
/* ******************** */

// Setup requirements

/**
 * Check PHP Version
 */
$_phpVerRequired = "5.3.0";
$_phpVer = phpversion();
if (floatval($_phpVerRequired) <= floatval($_phpVer))
  $_reqPhpVer = "Success";
else
  $_reqPhpVer = "Fail";


/**
 * Check MySQL Requirements
 */
if (phpversion("mysql"))
  $_reqMySQL = "Success";
else
  $_reqMySQL = "Fail";


/**
 * Check Web Server (Apache Version)
 */
$_webSvrTemp = explode(" ", $_SERVER['SERVER_SOFTWARE']);
$_webSvrTemp = explode("/", $_webSvrTemp[0]);
if (strtolower($_webSvrTemp[0]) == "apache")
{
  if(floatval($_webSvrTemp[1]) > 2){
    $_reqWebServer = "Success";   // Apache version supported!
    $_reqWebServerMessage = "$_webSvrTemp[0] v$_webSvrTemp[1]";
  }else{
    $_reqWebServer = "Warning";     // Potentially not supported
    $_reqWebServerMessage = "Apache version not supported";
  }
}
else
{
  $_reqWebServer = "Warning";     // Potentially not supported
  $_reqWebServerMessage = "Non-Apache servers are not supported";
}


function DebugDisplay() {
/*
  //pmtDebug();
  global $_reqMySQL, $_reqPhpVer;

  print("PhpVer(): " . phpversion() . "<br />\n");
  print("WebServer: " . $_SERVER['SERVER_SOFTWARE'] . "<br />\n");
  print("PHP Version: " . $_reqPhpVer . "<br />\n");
  print("PHP MySQL: " . $_reqMySQL . "<br />\n");
*/
}


/**
 * Generate Back and Forward buttons
 * @param integer $bak Previous Panel Id Number
 * @param integer $cur Current Panel Id Number
 * @param integer $fwd Next Panel Id Number
 */
function MakeNav($bak="", $cur="", $fwd="")
{
  if ($bak == "") { $classBak = "ButtonDisabled"; $idBak=""; $disB="disabled "; } else { $classBak = "Buttons"; $idBak="btnBak".$bak; $disB="";}
  if ($fwd == "") { $classFwd = "ButtonDisabled"; $idFwd=""; $disF="disabled "; } else { $classFwd = "Buttons"; $idFwd="btnFwd".$fwd; $disF="";}

  print(PHP_EOL);
  print('          <button type="button" '.$disB.'id="'.$idBak.'" class="'. $classBak .' btnPrev">Back</button>' . PHP_EOL);
  print('          <button type="button" '.$disF.'id="'.$idFwd.'" class="'. $classFwd .' btnNext">Next</button>' . PHP_EOL);

  print('          <script>' . PHP_EOL);
  if ($bak != "") print('            $("#'.$idBak.'").click(function () { $.ChangePanel('.$cur.', '.$bak.'); });' . PHP_EOL);
  if ($fwd != "") print('            $("#'.$idFwd.'").click(function () { $.ChangePanel('.$cur.', '.$fwd.'); });' . PHP_EOL);

  //print("  $('#lstStep').append($('<option></option>').attr('value',key).text(value)); " . PHP_EOL );
  //print('$("#lstStep").val('. $cur .');');

  print(' $("#lstStep option[value='.$cur.']").attr("selected", "selected");');

  print('          </script>' . PHP_EOL);


  /*
    <button type="button" id="btnGoToStep0" class="Buttons btnPrev">Back</button>
    <button type="button" id="btnGoToStep2" class="Buttons btnNext">Next</button>
    <script>
      $("#btnGoToStep0").click(function () { $.ChangePanel(1, 0); });
      $("#btnGoToStep2").click(function () { $.ChangePanel(1, 2); });
    </script>
  */
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title>System Setup - [xenoPMT]</title>

    <link href="setup.css" rel="stylesheet" type="text/css" />
    <script language="JavaScript" src="js/jquery-1.8.2.min.js" type="text/javascript"></script>
    <script language="JavaScript" src="js/jquery-ui-1.9.0.custom.min.js" type="text/javascript"></script>
    <script language="JavaScript" src="installer.js" type="text/javascript"></script>
    <script type="text/javascript" language="JavaScript">

      // $("#lstStep option[value=3]").attr("selected", "selected");

    </script>
  </head>
  <body>
    <noscript>
      <div class="panelMain">
        <h1>Javascript Support Disabled</h1>
        <p>xenoPMT installer needs JavaScript to run properly. Please enable and re-run installer.</p>
      </div>
    </noscript>

<?php
DebugDisplay();
//pmtDebug("hello");
?>

    <h1 id="title">xenoPMT Installer </h1>

    <div>
      <input name="step" id="stepNdx" type="hidden" value="1" />
      <div id="debugStep">
      <!-- data displayed here -->
      </div>
    </div>

    <div class="panelMain">

      <div id="panelLeft">

        <table width="90%">
          <tbody>

            <tr id="tblItem1"><td> 1 -</td><td>Welcome!</td></tr>
            <tr id="tblItem2"><td> 2 -</td><td>Verify Requirements</td></tr>
            <tr id="tblItem3"><td> 3 -</td><td>Database Setup</td></tr>
            <tr id="tblItem4"><td> 4 -</td><td>Configure your System</td></tr>
            <tr id="tblItem5"><td> 5 -</td><td>Install Modules</td></tr>
            <tr id="tblItem6"><td> 6 -</td><td>Finished</td></tr>
            <!--<tr id="tblItem7"><td> 7 -</td><td></td></tr>-->
          </tbody>
        </table>

      </div>

      <div id="panelRight">

        <!-- Step 1 - Welcome Screen -->
        <div class="steps" id="step1">
          <?php MakeNav("", 1, 2); ?>
          <h1> Welcome to xenoPMT! </h1>

          <p>
            Welcome to the xenoPMT installer! Currently this product
            is in beta testing.
          </p>
          <p>
            So what does that mean to you? Well to put
            things simply, there may be changes to the core on short notice.
            Why is that? Well some are for fixing bugs and/or refactoring, but
            also because we found a much better way to perform things in a more
            efficient manner. This can also change the way your modules are
            structured.
          </p>

          <!--
          <div id="TestSpinner">
            Click here to hide spinner!
          </div>
          <img id="imgSpinner" src="pix/spinner.gif" alt="" width="100" height="100" />
          -->
        </div><!-- end:step1 -->


        <!-- Step 2 - Check requirements -->
        <div class="steps" id="step2">
          <?php MakeNav(1,2,3); ?>
          <h1>Verifying Requirements</h1>
          <p>
            We will do our best to provide as much proactive support to
            make your installation more smoothly. Just make sure that your
            system is compliant with our prerequisites, if it's not then
            we cannot help you.
          </p>

          <h2>Server Requirements</h2>
          <table id="tblSystemReq" border="0" cellpadding="0" cellspacing="0" width="100%">
            <thread>
              <tr><td>Requirement</td><td>This System</td></tr>
            </thread>
            <tbody>
              <tr>
                <td>PHP 5.3.0 or higher</td>
                <td><span class="<?php print($_reqPhpVer); ?>"><?php print($_phpVer); ?></span></td>
              </tr>
              <tr>
                <td>PHP MySQL Support</td>
                <td><span class="<?php print($_reqMySQL);?>"><?php print($_reqMySQL); ?></span></td>
              </tr>
              <tr>
                <td>Apache 2.0 +</td>
                <td><span class="<?php print($_reqWebServer);?>"><?php print($_reqWebServerMessage); ?></span></td>
              </tr>
            </tbody>
          </table>


          </p>

        </div> <!-- end:step2 -->


        <!-- Step 3 - Test User DB Input -->
        <div class="steps" id="step3">
          <!--
           DB: Server, User, Pass, Database, TablePrefix
          ?? Info: Site Name, Admin Name, Admin Pass, Admin Email, Admin Display Name
          -->
          <?php MakeNav(2,3,4); ?>
          <h1>Database Setup</h1>

          <p>
            Please have your database configured prior to testing out your connection
            and proceeding below.
          </p>


          <table id="tblSystemReq" border="0" cellpadding="0" cellspacing="0" width="100%">
            <thread>
              <tr>
                <td><b></b></td>
                <td><b>Required Input</b></td>
              </tr>
            </thread>
            <tbody>
              <tr>
                <td>Database Server:</td>
                <td><input type="text" id="txtDbServer" name="dbserver" value="<?php print($_txtDBServer); ?>" /></td>
              </tr>
              <tr>
                <td>Database Name:</td>
                <td><input type="text" id="txtDbName" name="dbname" value="<?php print($_txtDBName); ?>" /></td>
              </tr>
              <tr>
                <td>Table Prefix:</td>
                <td><input type="text" id="txtDbPrefix" name="dbname" value="<?php print($_txtDBPrefix); ?>" /></td>
              </tr>
              <tr>
                <td>User Name:</td>
                <td><input type="text" id="txtDbUser" name="dbuser" value="<?php print($_txtDBUser); ?>" autocomplete="off" /></td>
              </tr>
              <tr>
                <td>Password:</td>
                <td><input type="text" id="txtDbPass" name="dbpass" value="<?php print($_txtDBPass); ?>" autocomplete="off" /></td>
              </tr>
              <tr>
                <td>
                  <button type="button" id="btnDbTestConn" class="Buttons">Test Connection</button>
                </td>
                <td>
                  <button type="button" id="btnInstallDb" class="Buttons">Install xenoPMT Core</button>
                </td>
              </tr>
            </tbody>
          </table>
          <div id="spnDbConnectionStatus" class="" style="padding-top: 10px;">
            <i>(Click, "Test Connection" to continue)</i>
          </div>

        </div> <!-- end:step3 -->


        <!-- Step 4 - Configure the Admin Account -->
        <div class="steps" id="step4">
          <!-- Info: Site Name, Admin Name, Admin Pass, Admin Email, Admin Display Name -->

          <?php MakeNav(3,4,5); ?>
          <h1>Configure your System</h1>

          <table id="tblSystemReq" border="0" cellpadding="0" cellspacing="0" width="100%">
            <thread>
              <tr>
                <td><b></b></td>
                <td><b>Required Input</b></td>
              </tr>
            </thread>
            <tbody>
              <tr>
                <td>System Name:</td>
                <td><input type="text" id="txtCfgSiteName" name="" value="<?php print($_txtCfgSiteName); ?>" /></td>
              </tr>
              <tr>
                <td>Clean URI:</td>
                <td>
                  <input type="radio" id="optCfgCleanURI1" name="" value="1" checked="checked" /><label for="optCfgCleanURI1">Yes</label>
                  <input type="radio" id="optCfgCleanURI2" name="" value="0" /><label for="optCfgCleanURI2">No</label>
                </td>
              </tr>
              <tr>
                <td>Admin Display Name:</td>
                <td><input type="text" id="txtCfgAdminDisp" name="" value="<?php print($_txtCfgAdminDisp); ?>" /></td>
              </tr>
              <tr>
                <td>Admin User Name:</td>
                <td><input type="text" id="txtCfgAdminUser" name="" value="<?php print($_txtCfgAdminUser); ?>" /></td>
              </tr>
              <tr>
                <td>Admin Password:</td>
                <td><input type="text" id="txtCfgAdminPass" name="" value="<?php print($_txtCfgAdminPass); ?>" autocomplete="off" /></td>
              </tr>
              <tr>
                <td>Admin Email:</td>
                <td><input type="text" id="txtCfgAdminEmail" name="" value="<?php print($_txtCfgAdminEmail); ?>" autocomplete="off" /></td>
              </tr>
              <tr>
                <td></td>
                <td>
                  <button type="button" id="btnSysConfig" class="Buttons">Configure System</button>
                </td>
              </tr>
            </tbody>
          </table>

          <div id="divDbInstallMsg" class="" style="padding-top: 10px;">
            <!-- Display error messages here -->
            <i>(Click, "Continue to accept settings")</i>
          </div>

        </div> <!-- end:step4 -->

        <!-- Step 5 - Install Modules -->
        <div class="steps" id="step5">

          <?php MakeNav(4,5,6); ?>
          <h1>Install Modules</h1>

          <table id="tblSystemReq" border="0" cellpadding="0" cellspacing="0" width="100%">
            <thread>
              <tr>
                <td><b>Module Name</b></td>
                <td><b>Select Module to install</b></td>
              </tr>
            </thread>
            <tbody>
              <tr>
                <td>
                  Administrator
                </td>
                <td>X</td>
              </tr>
              <tr>
                <td>
                  <h3>Dashboard</h3>
                  <h6>Version: 0.0.5</h6>
                  <small><i>Default xenoPMT dashboard</i></small>
                </td>
                <td>
                  <i>not available</i>
                </td>
              </tr>
              <tr>
                <td>
                  Customer Manager<br />
                  <small><i>(no dependencies)</i></small>
                </td>
                <td>

                </td>
              </tr>
              <tr>
                <td>
                  Knowledge Base<br />
                  <small><i></i></small>
                </td>
                <td></td>
              </tr>
              <tr>
                <td>
                  Product<br />
                  <small><i> </i></small>
                </td>
                <td></td>
              </tr>
              <tr>
                <td>
                  Project<br />
                  <small><i> </i></small>
                </td>
                <td></td>
              </tr>
              <tr>
                <td>
                  Ticket System<br />
                  <small><i>Issue ticketing system</i></small>
                </td>
                <td></td>
              </tr>
              <tr>
                <td>
                  Bug Manager<br />
                  <small><i> </i></small>
                </td>
                <td></td>
              </tr>
              <tr>
                <td>
                  Task Manager<br />
                  <small><i> </i></small>
                </td>
                <td></td>
              </tr>
              <tr>
                <td>
                  Wiki System<br />
                  <small><i> </i></small>
                </td>
                <td></td>
              </tr>
              <tr>
                <td>
                  Purchase Order Manager<br />
                  <small><i> </i></small>
                </td>
                <td></td>
              </tr>
              <tr>
                <td></td>
                <td><button type="button" id="btnSysConfig" class="Buttons">Install Modules</button></td>
              </tr>
            </tbody>
          </table>

          <div id="divModuleInstallStatus" class="" style="padding-top: 10px;">
            <!-- Display status messages here -->
            <i></i>
          </div>

        </div> <!-- end:step5 -->


        <!-- Step 6 - Install Modules -->
        <div class="steps" id="step6">
          <?php MakeNav(5, 6);  //MakeNav(5, 6, 7); ?>
          <h1>Finished!</h1>

          <p>
            No go forth and give you new system a test drive.
          </p>
          <p>
            Check out our support page to submit any issues or enhancement
            requests you may have. We appreciate your input! If you have
            a code enhancement to make this more robust, your contribution
            may make it into the next release!
          </p>
          <p>
            <i>Xeno Innovations Development Team</i>
          </p>

        </div> <!-- end:step6 -->


        <!-- Step 7 - Finished -->
        <div class="steps" id="step7">
          <?php MakeNav(6, 7); ?>
          <h1>Step 7</h1>

          <p>
            Step 7
          </p>

        </div> <!-- end:step7 -->

      </div> <!-- end:panelRight -->
    </div> <!-- end:panelmain -->
    <?PHP
    if ($BETA_TESTING)
    {?>
    <div align="center">
      <a href="#" id="btnClearDb">Clear Database</a>
    </div>
    <?php }
    ?>
  </body>
</html>