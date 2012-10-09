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
if(floatval($_phpVerRequired) <= floatval($_phpVer))
  $_phpVerAvail = "Success";
else
  $_phpVerAvail = "Fail";


/**
 * Check MySQL Requirements
 */
if(phpversion("mysql"))
  $_MySQLAvail = "Success";
else
  $_MySQLAvail = "Fail";


/**
 * Check Web Server (Apache Version)
 */
$_webServer



?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title>System Setup - [xenoPMS]</title>

    <link href="setup.css" rel="stylesheet" type="text/css" />

    <script language="JavaScript" src="../common/javascript/moo/prototype.lite.js" type="text/javascript" ></script>
    <script language="JavaScript" src="../common/javascript/moo/moo.fx.js" type="text/javascript" ></script>
    <script language="JavaScript" src="../common/javascript/moo/moo.fx.pack.js" type="text/javascript" ></script>
    <script language="JavaScript" src="../common/javascript/common.js" type="text/javascript"></script>
    <script language="JavaScript" src="install.js" type="text/javascript"></script>
    <script language="JavaScript" type="text/javascript">
      <?php $moduleClass->displpayJS() ?>
    </script>

    <script type="text/javascript">
      function showUser(str)
      {
        if (str=="")
        {
          document.getElementById("txtHint").innerHTML = "";
          return;
        }
      }
    </script>
  </head>
  <body>
    <noscript>
      <div class="panelMain">
        <h1>Javascript Support Disabled</h1>
        <p>xenoPMT installer needs JavaScript to run properly. Please enable and re-run installer.</p>
      </div>
    </noscript>
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

        <!-- Step 1 - Check requirements -->
       <div class="steps" id="step1">
         <!-- PHP, PHP w/ MySQL, Apache2.0, MySQL-->
         <button type="button" class="Buttons btnPrev" disabled="disabled">Back</button>
         <button type="button" class="Buttons btnNext">Next</button>

          <p>step 1</p>
       </div><!-- end:step1 -->


        <!-- Step 2 - User DB Input -->
        <div class="steps" id="step2">
          <!--
           DB: Server, User, Pass, Database, TablePrefix
          ?? Info: Site Name, Admin Name, Admin Pass, Admin Email, Admin Display Name
          -->
          <button type="button" class="ButtonDisabled btnPrev">Back</button>
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