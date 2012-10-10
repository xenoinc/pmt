<?php
/** * *********************************************************
 * Copyright 2012 (C) Xeno Innovations, Inc.
 * ALL RIGHTS RESERVED
 * @Author:      Damian Suess
 * Document:     index.php
 * Created Date: Oct 4, 2012
 *
 * Description:
 *  Installer (v0.0.5)
 *
 * Change Log:
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
  //pmtDebug();
  global $_reqMySQL;
  global $_phpVerValid;

  print("PhpVer(): " . phpversion() . "<br />\n");
  print("WebServer: " . $_SERVER['SERVER_SOFTWARE'] . "<br />\n");
  print("PHP Version: " . $_reqPhpVer . "<br />\n");
  print("PHP MySQL: " . $_reqMySQL . "<br />\n");
}

/**
 * Generate Back and Forward buttons
 * @param integer $bak Previous Panel Id Number
 * @param integer $cur Current Panel Id Number
 * @param integer $fwd Next Panel Id Number
 */
function MakeNav($bak="", $cur="", $fwd="")
{
  if ($bak == "") { $classBak = "ButtonDisabled"; $idBak=""; } else { $classBak = "Buttons"; $idBak="btnBak".$bak; }
  if ($fwd == "") { $classFwd = "ButtonDisabled"; $idFwd=""; } else { $classFwd = "Buttons"; $idFwd="btnFwd".$fwd; }
  print(PHP_EOL);
  print('          <button type="button" id="'.$idBak.'" class="'. $classBak .' btnPrev">Back</button>' . PHP_EOL);
  print('          <button type="button" id="'.$idFwd.'" class="'. $classFwd .' btnNext">Next</button>' . PHP_EOL);

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
    <div class="panelMain">

      <div id="panelLeft">
        <select name="lstStep" id="lstStep" size="10">
          <!-- Welcome page & cfg suggestions -->
          <option value="1" selected="selected">Verify Requirements</option>
          <option value="2">Database Setup</option>
          <option value="3">Create Database</option>
          <option value="4">Setup System</option>
          <option value="5">Install Modules</option>
          <!-- Secure your install (remove files) -->
        </select>

        <table width="90%">
          <tr><td>1</td><td>description</td></tr>
          <tr><td>2</td><td>description</td></tr>
          <tr><td>3</td><td>description</td></tr>
          <tr><td>4</td><td>description</td></tr>
          <tr><td>5</td><td>description</td></tr>
        </table>

      </div>

      <div id="panelRight">

        <!-- Step 0 - Welcome Screen -->
        <div class="steps" id="step0">
          <?php MakeNav("", 0, 1); ?>
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
        </div><!-- end:step0 -->


        <!-- Step 1 - Check requirements -->
        <div class="steps" id="step1">
          <?php MakeNav("0",1,2); ?>
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

        </div> <!-- end:step1 -->


        <!-- Step 2 - User DB Input -->
        <div class="steps" id="step2">
          <!--
           DB: Server, User, Pass, Database, TablePrefix
          ?? Info: Site Name, Admin Name, Admin Pass, Admin Email, Admin Display Name
          -->
          <?php MakeNav(1,2,3); ?>
          <h1>Database Setup</h1>

          <p>
            step 2
          </p>

        </div> <!-- end:step2 -->


        <!-- Step 3 - Create Database -->
        <div class="steps" id="step3">
          <?php MakeNav(2,3,4); ?>
          <h1>Create Database</h1>

          <p>
            step 3
          </p>

        </div> <!-- end:step3 -->

        <!-- Step 4 - Name your system -->
        <div class="steps" id="step4">
          <!-- Info: Site Name, Admin Name, Admin Pass, Admin Email, Admin Display Name -->
          <?php MakeNav(3,4,5); ?>
          <h1>Configure your System</h1>

          <p>
            step 4
          </p>

        </div> <!-- end:step4 -->


        <!-- Step 5 - Install Modules -->
        <div class="steps" id="step5">
          <?php MakeNav(4, 5, 6); ?>
          <h1>Install Modules</h1>

          <p>
            step 5
          </p>

        </div> <!-- end:step5 -->


        <!-- Step 6 - Install Modules -->
        <div class="steps" id="step6">
          <?php MakeNav(5, 6); ?>
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

      </div> <!-- end:panelRight -->
    </div> <!-- end:panelmain -->
  </body>
</html>