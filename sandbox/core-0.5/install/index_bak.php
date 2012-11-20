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
  $_phpVerValid = "Success";
else
  $_phpVerValid = "Fail";


/**
 * Check MySQL Requirements
 */
if (phpversion("mysql"))
  $_MySQLAvail = "Success";
else
  $_MySQLAvail = "Fail";


/**
 * Check Web Server (Apache Version)
 */
$_webSvrTemp = explode(" ", $_SERVER['SERVER_SOFTWARE']);
$_webSvrTemp = explode("/", $_webSvrTemp[0]);
if (strtolower($_webServer[0]) == "apache") {
  $_webServer = "apache";
}

function DebugDisplay() {
  //pmtDebug();
  global $_MySQLAvail;
  global $_phpVerValid;

  print("PhpVer(): " . phpversion() . "<br />\n");
  print("WebServer: " . $_SERVER['SERVER_SOFTWARE'] . "<br />\n");
  print("PHP Version: " . $_phpVerValid . "<br />\n");
  print("PHP MySQL: " . $_MySQLAvail . "<br />\n");
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title>System Setup - [xenoPMS]</title>

    <link href="setup.css" rel="stylesheet" type="text/css" />
    <!--
    <script language="JavaScript" src="js/moo/prototype.lite.js" type="text/javascript" ></script>
    <script language="JavaScript" src="js/moo/moo.fx.js" type="text/javascript" ></script>
    <script language="JavaScript" src="js/moo/moo.fx.pack.js" type="text/javascript" ></script>
    <script language="JavaScript" src="js/common.js" type="text/javascript"></script>*/
    -->
    <script language="JavaScript" src="js/jquery-1.8.2.min.js" type="text/javascript"></script>
    <!-- <script language="JavaScript" src="install.js" type="text/javascript"></script> -->
    <!-- Move this to install.js -->
    <script type="text/javascript" language="JavaScript">

      $(document).ready(function() {

        /* Start visible */
        $('#step0').show();
        $('#imgSpinner').show();

        $("#step1").show();
        $("#step2").hide();
        $("#step3").hide();
        $("#step4").hide();
        $("#step5").hide();


        /** Event Handlers */

        /** Toggle Test
        $('#TestSpinner').click(function() {
          $("#imgSpinner").toggle();
        });
         */

        /* Animate hiding
        $('#TestSpinner').click(function() {
          $('#imgSpinner').hide('slow', function() {
            alert('animation done!');
          });
        });
         */
      });

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
        <select id="lstStep" size="10">
          <!-- Welcome page & cfg suggestions -->
          <option value="1" selected="selected">Verify Requirements</option>
          <option value="2">Database Setup</option>
          <option value="3">Create Database</option>
          <option value="4">Setup System</option>
          <option value="5">Install Modules</option>
          <!-- Secure your install (remove files) -->
        </select>
      </div>

      <div id="panelRight">

        <!-- Step 0 - Welcome Screen -->
        <div class="steps" id="step0">
          <!--
          <button type="button" class="ButtonDisabled btnPrev" disabled="disabled">Back</button>
          <button type="button" class="Buttons btnNext">Next</button>
          -->
          <button type="button" id="" class="ButtonDisabled btnPrev" disabled="disabled">Back</button>
          <button type="button" id="btnGoToStep1" class="Buttons btnNext">Next</button>


          <script>
            $("#btnGoToStep1").click(function () {
              // hides all paragraphs
              $("#step0").hide("normal");
              $("#step1").show("slow");
            });
          </script>

          <p>
            Sample Welcome Screen!
          </p>

          <div id="TestSpinner">
            Click here to hide spinner!
          </div>
          <img id="imgSpinner" src="pix/spinner.gif" alt="" width="100" height="100" />

        </div><!-- end:step1 -->


        <!-- Step 1 - Check requirements -->
        <div class="steps" id="step1">
          <!-- PHP, PHP w/ MySQL, Apache2.0, MySQL-->
          <!-- <button type="button" class="ButtonDisabled btnPrev" disabled="disabled">Back</button>-->
          <button type="button" id="btnGoToStep0" class="Buttons btnPrev">Back</button>
          <button type="button" id="btnGoToStep2" class="Buttons btnNext">Next</button>

          <script>
            // hide all paragraphs (p) when any button (in this div?) is clicked
            $("button").click(function () {
              $("p").hide("slow");
            });
          </script>

          <p>step 1</p>

        </div><!-- end:step1 -->


        <!-- Step 2 - User DB Input -->
        <div class="steps" id="step2">
          <!--
           DB: Server, User, Pass, Database, TablePrefix
          ?? Info: Site Name, Admin Name, Admin Pass, Admin Email, Admin Display Name
          -->
          <button type="button" class="Buttons btnPrev">Back</button>
          <button type="button" class="Buttons btnNext">Next</button>

          <p>step 2</p>
        </div> <!-- end:step2 -->


        <!-- Step 3 - Create Database -->
        <div class="steps" id="step3">
          <button type="button" class="Buttons btnPrev">Back</button>
          <button type="button" class="Buttons btnNext">Next</button>

          <p>step 3</p>
        </div>

        <!-- Step 4 - Name your system -->
        <div class="steps" id="step4">
          <!--
            Info: Site Name, Admin Name, Admin Pass, Admin Email, Admin Display Name
          -->
          <button type="button" class="Buttons btnPrev">Back</button>
          <button type="button" class="Buttons btnNext">Next</button>

          <p>step 4</p>
        </div>


        <!-- Step 5 - Install Modules -->
        <div class="steps" id="step5">
          <button type="button" class="Buttons btnPrev">Back</button>
          <button type="button" class="ButtonDisabled btnNext" disabled="disabled">Next</button>

          <p>step 5</p>
        </div>
      </div>
    </div>
  </body>
</html>