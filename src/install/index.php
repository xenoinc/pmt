<?php
//p
//hpinfo();
//exit;


/* * **********************************************************
 * Copyright 2012 (C) Xeno Innovations, Inc.
 * ALL RIGHTS RESERVED
 * Author:       Damian Suess
 * Document:     index.php
 * Created Date: Jan 30, 2012
 *
 * Description:
 *  xiPMT system installation
 *
 * Parameters:
 *  reset - Reset system settings
 *    * full  - Remove CONFIG.PHP
 *            - Drop and recreate database
 *    * db    - Recreate database and use default values
 *
 * To Do:
 *  [ ] Step 2.a - Place into a function so we can resuse it in 2.b
 *  [ ] Step 2.a - Use disappearing suggestive text
 *  [ ] GD.com is using PHP 5.3.6 which is buggy. PMT is tested @ xiLabs against v5.3.10
 *      [ ] Remove usage of so many _POSTs and use ajax
 *
 * Change Log:
 *  2012-0923 + Added default value for System Title
 *  2012-0603 * Updated XIPMT_USER table column `Name` to `Display_Name`
 *  2012-0424 * Repaired Reset links & prompt message
 *  2012-0403 * Fixed warning messages from _GET command. Added "isset(_GET[..])"
 *            * Fixed warning message from !defined(DebugMode)  >> !defined("DebugMode")
 *  2012-0328 + config.php - Updated the cfg file to include "base_url"
 *            + config.php - Added copyright and notes
 *  2012-0225 * 50% there (djs)
 */

if (!defined("DebugMode"))
{
  define("DebugMode", TRUE);
  require "../xpmt/phpConsole.php";
  PhpConsole::start(true, true, dirname(__FILE__));
}

require_once "installer.php";
require_once "../xpmt/core/pmt.db.php";

if (DebugMode == true)
{
  $defVal = array();
  $defVal["server"] = "localhost";
  $defVal["dbname"] = "PMT_DATA";
  $defVal["user"]   = "testuser";
  $defVal["pass"]   = "testpass";
  $defVal["prefix"] = "XI_";
}



if (isset($_GET["reset"]) && $_GET["reset"] == "full")
{
  /*
   * Remove both CONFIG.PHP and Drop Tables
   */

  CreateHeader("PMT System Reset - Full");
  print("<ul>\n");

  if(file_exists("../xpmt/config.php"))
  {
    p("CONFIG.PHP found.");
  }
  if (IsInstalled() == false)
  {
    p("Installed components NOT found. Using DebugMode values");
    $pmtDB = new Database($defVal["server"], $defVal["user"], $defVal["pass"], $defVal["dbname"]);
  }
  else
  {
    p("Using CONFIG.PHP values.");
    $pmtDB = new Database($pmtConf["db"]["server"], $pmtConf["db"]["user"], $pmtConf["db"]["pass"], $pmtConf["db"]["dbname"]);
  }

  if ($pmtDB == null)
    p("Bad DB connection.");
  else
  {
    $pmtDB->Query("DROP DATABASE ". $defVal['dbname'] . ";");
    $pmtDB->Query("CREATE DATABASE ". $defVal['dbname'] . ";");

    p("Removed and recreated database.");
    p("You may now <a href='../'>start over</a>");
  }

  // Delete config.php
  try
  {
    unlink("../xpmt/config.php");
    p("Delete <b>config.php</b> file.");
  }  catch(Exception $e) {

    p("Issue deleting <b>config.php</b> file. Exception: $e");
  }

  print("</ul>\n");
  CreateFooter();
  exit;
}
elseif (isset($_GET["reset"]) && $_GET["reset"] == "db")
{
  /*
   * Drop tables and recreate
   */
  CreateHeader("PMT System Reset - Database Only");
  print("<ul>\n");

  if (file_exists("../xpmt/config.php"))
  {
    require_once("../xpmt/config.php");

    p("Server: " .    $pmtConf["db"]["server"]);
    p("Database: " .  $pmtConf["db"]["dbname"]);
    p("User Name: " . $pmtConf["db"]["user"]);
    p("Password: " .  $pmtConf["db"]["pass"]);
    print("</ul><ul>\n");

    $pmtDB = new Database(
            $pmtConf["db"]["server"],
            $pmtConf["db"]["user"],
            $pmtConf["db"]["pass"],
            $pmtConf["db"]["dbname"]);

    p("<b>DB:</b> Removing database..");        $pmtDB->Query("DROP DATABASE ". $defVal['dbname'] . ";");
    p("<b>DB:</b> Creating database..<br />");  $pmtDB->Query("CREATE DATABASE ". $defVal['dbname'] . ";");

    // Select Database by Name
    $pmtDB->SelectDb($pmtConf["db"]["dbname"]);
    /*
    $pmtDB = null;
    $pmtDB = new Database(
            $pmtConf["db"]["server"],
            $pmtConf["db"]["user"],
            $pmtConf["db"]["pass"],
            $pmtConf["db"]["dbname"]);
    */

    p("<b>Generating tables..</b>");
    p("Executing: pmt-db.sql");           DbGenerateTables("pmt-db.sql",          $pmtConf["db"]["prefix"], $pmtDB);
    p("Executing: pmt-db-user.sql");      DbGenerateTables("pmt-db-user.sql",     $pmtConf["db"]["prefix"], $pmtDB);
    p("Executing: pmt-db-project.sql");   DbGenerateTables("pmt-db-project.sql",  $pmtConf["db"]["prefix"], $pmtDB);
    p("Executing: pmt-db-ticket.sql");    DbGenerateTables("pmt-db-ticket.sql",   $pmtConf["db"]["prefix"], $pmtDB);
    p("Executing: pmt-db-kb.sql");        DbGenerateTables("pmt-db-kb.sql",       $pmtConf["db"]["prefix"], $pmtDB);
    p("Executing: pmt-db-customer.sql");  DbGenerateTables("pmt-db-customer.sql", $pmtConf["db"]["prefix"], $pmtDB);  // 2012-06-02 + Added (testing phase)
    p("Executing: pmt-db-product.sql");   DbGenerateTables("pmt-db-product.sql",  $pmtConf["db"]["prefix"], $pmtDB);  // 2012-06-05 + Added (testing phase)


    p("<b>[DB]</b> - Inserting default admin account '<i>admin:admin</i>'.");

    // Administration Account
    $pmtDB->Query(
            "INSERT INTO ".$pmtConf["db"]["prefix"]."USER ".
            "(`User_Name`, `Password`, `Display_Name`, `Email`, `Group_Id`, `Active`, `Session_Hash`) VALUES (" .
            "'admin', " .                 // User
            "'".sha1('admin')."',".       // Password
            "'xenoPMT Administrator', " . // Display name the world sees
            "'asdf@asdf.com', " .         // Email
            "1, " .                       // Group_Id (ADMIN)
            "true," .                     // Active
            "''" .                        // Session_Hash
            ");");

    // Add TBL_USER_GROUP (User_Id, Group_Id) = (1, 1) = ("Admin", "ADMIN")
    $pmtDB->Query(
            "INSERT INTO ".$pmtConf["db"]["prefix"]."USER_GROUP ".
            "(`User_Id`, `Group_Id`) VALUES (1, 1);");

    p("You may now <a href='../'>start over</a>");

  }
  else
  {
    print("<li><b>config.php</b> not found</li>");
    print("<li>Please run the installer</li>");
    print("<li><a href='index.php'>Run installer</a></li>");
  }

  print("</ul>\n");
  CreateFooter();
  exit;
}
else
{

  /*
   * Just some stupid beta testing
   *
  if (IsInstalled() == true)
    print "It is installed!";
  else
    print "NOT installed!";

  try
  {
    // Config file found. Check for DB
    $pmtDB = new Database(
            $pmtConf["db"]["server"],
            $pmtConf["db"]["user"],
            $pmtConf["db"]["pass"],
            $pmtConf["db"]["dbname"]
            );
    // Removed 2012-0603 - It was causing some error
    // $pmtDB->Query("list tables;");
    $pmtDB->Query("show tables;");
    print ("db works");
   *
  }
  catch (Exception $e)
  {
    print ("Error: " .$e);
  }
  */

}




/*
 * Auto disappear text
  <input name="myvalue" type="text" onFocus="if(this.value=='enter value')this.value='';" value="enter value">
  <input type="text" name="inputBox" placeholder="enter your text here">
  <input type="text" name="theName" value="DefaultValue" style="color:#BBB;"
         onblur="if(this.value == ''){ this.value = 'DefaultValue'; this.style.color = '#BBB';}"
         onfocus="if(this.value == 'DefaultValue'){ this.value = ''; this.style.color = '#000';}" />
*/



// Get the step in the setup process
$step = (isset($_POST['step']) ? $_POST['step'] : 1);

switch ($step)
{
  /**
   * Welcome screen
   */
  case 1:
    CreateHeader("Welcome to xenoPMT");

    if (IsInstalled())
    {
      htmlMessage("xiPMT is already Installed");
    }
    elseif(file_exists("../xpmt/config.php"))
    {
      htmlMessage("<code>xpmt/config.php</code> exists, unable to continue.");
    }
    else
    {
      ?>
      <form action="index.php" method="post">
        <input type="hidden" name="step" value="<?php echo($step+1); ?>" />
        <h2>License Agreement</h2>
        <pre id="license">
          <?php echo htmlentities(file_get_contents("../license.txt")) ?>
        </pre>
        <div id="actions" align="center">
          <input type="submit" value="Accept" />
        </div>
      </form>
      <?php

    }
    break;

  /**
   * Database Setup and Testing
   *  ** Future use AJAX and auto test **
   */
  case 2:
    if (IsInstalled())
      break;


    if(isset($_POST["db"]) == false)
    {
      CreateHeader("Step 2.a");

      // There is no db connetion info. Let get some
      if(file_exists("../xpmt/config.php"))
        require_once "../xpmt/config.php";

      ?>
      <form action="index.php" method="post">
        <input name="step" type="hidden" value="<?php print($step); ?>" />
        <h2>Input Database Information</h2>
        <table class="inputForm">
          <tr>
            <td class="label">Server:</td>
            <td><input type="text" name="db[server]" autocomplete="off" value="<?php print(getData("server", $defVal["server"])); ?>" /> </td>
          </tr>
          <tr>
            <td class="label">Username:</td>
            <td><input type="text" name="db[user]" autocomplete="off" value="<?php print(getData("user", $defVal["user"])); ?>" /> </td>
          </tr>
          <tr>
            <td class="label">Password:</td>
            <td><input type="text" name="db[pass]" autocomplete="off" value="<?php print(getData("pass", $defVal["pass"])); ?>" /> </td>
          </tr>
          <tr>
            <td class="label">Database Name:</td>
            <td><input type="text" name="db[dbname]" autocomplete="off" value="<?php print(getData("dbname", $defVal["dbname"])); ?>" /> </td>
          </tr>
          <tr>
            <td class="label">Database Table Prefix:</td>
            <td><input type="text" name="db[prefix]" autocomplete="off" value="<?php print(getData("prefix", $defVal["prefix"])); ?>" /> </td>
          </tr>
        </table>
        <div id="actions">
          <input type="submit" value="Next" />
        </div>
      </form>
      <?php

    }
    else
    {
      /* Connection Test and display fail/success */
      CreateHeader("Step 2.b");

      // We have DB Connection Information
      $fail = false;
      $conn = mysql_connect($_POST["db"]["server"],
                            $_POST["db"]["user"],
                            $_POST["db"]["pass"]);
      if(!$conn) $fail = true;
      if(!mysql_select_db($_POST["db"]["dbname"], $conn)) $fail = true;

      /* DB connection failure */
      if(!$fail)
      {
        ?>
        <form action="index.php" method="post">
          <input type="hidden" name="step" value="<?php print($step+1); ?>" />
          <input type="hidden" name="db" value='<?php echo json_encode($_POST['db']); ?>' />
          <div align="center" class="message good">Database connection successful!</div>
          <div id="actions"><input type="submit" value="Next" /></div>
        </form>
        <?php
      }
      else
      {
        ?>
        <div align="center" class="message error">
          <p>Cannot connect to database.</p>
          <p>Make sure the connection data was entered correctly and
          that your database has been created.</p>
        </div>
        <form action="index.php" method="post">
          <input type="hidden" name="step" value="<?php print($step); ?>" />
          <div id="actions"><input type="submit" value="Back" /></div>
        </form>
        <?php
        // Include the DB Input screen again so we don't have to click, Back.
        //  GenStep2Database();
      }
    }
    break;
  /**
   * System Administratior Setup
   */
  case 3:
    if (IsInstalled())
      break;

    $arrErr = array();
    $tmp = " cannot be blank.";

    if(isset($_POST["settings"]) && empty($_POST["settings"]["title"])) $arrErr["title"] = "Title$tmp";
    if(isset($_POST["settings"]) && empty($_POST["admin"]["username"])) $arrErr["username"] = "Username$tmp";
    if(isset($_POST["settings"]) && empty($_POST["admin"]["password"])) $arrErr["password"] = "Password$tmp";
    if(isset($_POST["settings"]) && empty($_POST["admin"]["email"]))    $arrErr["email"] = "Email$tmp";

    if(!isset($_POST["settings"]) || count($arrErr))
    {
      CreateHeader("Step 3.a - Admin Settings");
      if (DebugMode)
        print("<div class='message'><pre>" . $_POST["db"] . "</pre></div>");


      if (isset($_POST[""]) && count($arrErr))
      {
        ?>
        <div class="message error">
          <?php print(implode("<br />", $arrErr)); ?>
        </div>
        <?php
      }

      ?>
      <form action="index.php" method="post">
        <input type="hidden" name="step" value="<?php print($step); ?>" />
        <!-- <input type="hidden" name="db" value="<?php //print($_POST["db"]); ?>" /> -->
        <input type="hidden" name="db" value='<?php echo $_POST['db']; ?>' />

        <h2>Administration Configuration</h2>

        <h3>xiPMT Settings</h3>
        <table class="inputForm">
          <tbody>
            <tr><td class="label">xiPMT Title</td><td><input type="text" name="settings[title]" autocomplete="off" value="xenoPMT" /></td></tr>
            <tr>
              <td class="label">Clean URI</td>
              <td>
                <input type="radio" id="rdoCleanUri1" name="settings[seo_url]" value="1" checked="checked" /><label for="rdoCleanUri">Yes</label>
                <input type="radio" id="rdoCleanUri2" name="settings[seo_url]" value="0" /><label for="rdoCleanUri2">No</label>
              </td>
            </tr>
          </tbody>
        </table>

        <h3>Administration Settings</h3>
        <table class="inputForm">
          <tbody>
            <tr><td class="label">Username:</td><td><input type="text" name="admin[username]" /></td></tr>
            <tr><td class="label">Password:</td><td><input type="text" name="admin[password]" /></td></tr>
            <tr><td class="label">Email:</td>   <td><input type="text" name="admin[email]" />   </td></tr>
          </tbody>
        </table>
        <div id="actions">
          <input type="submit" value="Next" />
        </div>
      </form>
      <?php

    }
    elseif(isset($_POST["settings"]) && !count(@$arrErr))
    {
      /**
       * Verify that the information was correct
       *  ** Future, skip this step! **
       */

      CreateHeader("Step 3.b - Verify Settings");
      if (DebugMode)
        print("<div class='message'><pre>" . $_POST["db"] . "</pre></div>");


      ?>
      <form action="index.php" method="post">
        <input type="hidden" name="step"      value="<?php print($step+1); ?>" />
        <input type="hidden" name="db"        value='<?php echo $_POST['db']; ?>' />
        <input type="hidden" name="settings"  value='<?php print(json_encode($_POST['settings'])); ?>' />
        <input type="hidden" name="admin"     value='<?php print(json_encode($_POST['admin'])); ?>' />

        <h2>Administration Configuration</h2>

        <h3>xiPMT Settings</h3>
        <table class="inputForm">
          <tr><td class="label">xiPMT Title</td>  <td><?php print($_POST["settings"]["title"]); ?></td></tr>
          <tr><td class="label">Use Clean URI</td><td><?php print(@$_POST["settings"]["seo_url"] ? "Yes" : "No" ); ?></td></tr>
        </table>

        <h3>Administration Settings</h3>
        <table class="inputForm">
          <tr><td class="label">Username:</td><td><?php print($_POST["admin"]["username"]); ?></td></tr>
          <tr><td class="label">Email:</td>   <td><?php print($_POST["admin"]["email"]); ?></td></tr>
        </table>

        <div id="actions">
          <input type="submit" value="Install" />
        </div>
      </form>

      <?php
    }
    else
    {
      CreateHeader("Step 3.c - Admin Settings (err)");
      ?>
      <form action="index.php" method="post">
        <div>
          something fucked up
        </div>
      </form>
      <?php
    }

    break;

  case 4:

    CreateHeader("Step 4 - Installing Database");

    if (DebugMode)
      print("<div class='message'><pre>" . $_POST["db"] . "</pre></div>");


    ?>
    <div class="message">
      Generating Configuration file

      <ul>
        <li>Starting system...</li>
    <?php

    // InstallPMT();

    $dbase = json_decode($_POST["db"], true);
    $settings = json_decode($_POST["settings"], true);
    $admin = json_decode($_POST["admin"], true);

    //print ("jdec-dbase: " . $dbase . "<br>");
    //print ("jdec-settings: " . $settings . "<br>");
    //print ("jdec-admin: " . $admin . "<br>");

    $pmtDB = new Database(
                $dbase["server"],
                $dbase["user"],
                $dbase["pass"],
                $dbase["dbname"]);

    p("SVR: " .   $dbase['server']);
    p("Usr: " .   $dbase['user']);
    p("Pass: " .  $dbase['pass']);
    p("dbnme: " . $dbase['dbname']);
    p("---------------");
    //p("test_db: " . json_encode($_POST["db"]));
    //p("test_post-db: " . $_POST["db"]["server"]);
    //p("---------------");
    p("<b>[DB]</b> - Generating SQL tables..");
    p("<b>[DB]</b> - pmt-db");            DbGenerateTables("pmt-db.sql",          $dbase["prefix"], $pmtDB);
    p("<b>[DB]</b> - pmt-db-user");       DbGenerateTables("pmt-db-user.sql",     $dbase["prefix"], $pmtDB);
    p("<b>[DB]</b> - pmt-db-project");    DbGenerateTables("pmt-db-project.sql",  $dbase["prefix"], $pmtDB);
    p("<b>[DB]</b> - pmt-db-ticket");     DbGenerateTables("pmt-db-ticket.sql",   $dbase["prefix"], $pmtDB);
    p("<b>[DB]</b> - pmt-db-kb");         DbGenerateTables("pmt-db-kb.sql",       $dbase["prefix"], $pmtDB);
    p("<b>[DB]</b> - pmt-db-customer");   DbGenerateTables("pmt-db-customer.sql", $dbase["prefix"], $pmtDB);  // 2012-06-02 + Added (testing phase)
    p("<b>[DB]</b> - pmt-db-product");    DbGenerateTables("pmt-db-product.sql",  $dbase["prefix"], $pmtDB);  // 2012-06-05 + Added (testing phase)

    /*
    // Extract SQL & put prefix on tables
    $sqlPmtBrain = file_get_contents("pmt-db.sql");
    $sqlPmtBrain = str_replace("PMT_", $dbase["prefix"], $sqlPmtBrain);
    $query = explode(";", $sqlPmtBrain);

    // Run the queries
    foreach($query as $q)
    {
      if(!empty($q) && strlen($q) > 5)
        $pmtDB->Query($q);
    }
    */

    print("        <li><b>[DB]</b> - Inserting general defaults</li>\n");

    // Create default values
    //InsertDefaults($pmtDB, $dbase, $settings, $admin);
    // Simple settings
    $pmtDB->Query("UPDATE ".$dbase["prefix"]."SETTINGS SET Value='".$pmtDB->FixString($settings["title"]) . "' WHERE Setting='title';" );
    $pmtDB->Query("UPDATE ".$dbase["prefix"]."SETTINGS SET Value='".$pmtDB->FixString($settings["seo_url"]) . "' WHERE Setting='seo_url';" );

    print("<li><b>[DB]</b> - Inserting admin defaults</li>\n");

    // Administration Account
    /*
    $pmtDB->Query( "INSERT INTO ".$dbase["prefix"]."USERS ".
                "(Username, Password, Name, Email, Group_Id, Sesshash) VALUES " .
                "('". $pmtDB->FixString($admin['username'])."', '".
                      sha1($admin['password'])."', '".
                      $pmtDB->FixString($admin['username'])."', '".
                      $pmtDB->FixString($admin['email'])."', 1, '');");
    */
    // TODO: Add protection against SQL Injection Attacks (http://snippets.dzone.com/posts/show/1507)
    // TODO: Use the USER Class to perform user insert
    $q ="INSERT INTO ".$dbase["prefix"]."USER ".
        // "(User_Name, Password, Display_Name, Email, Group_Id, Active, Session_Hash) VALUES (" .
        "(User_Name, Password, Display_Name, Email, Active, Session_Hash) VALUES (" .
        "'". $pmtDB->FixString($admin['username'])."', ". // User
        "'". sha1($admin['password'])       ."', ". // Password
        "'". $pmtDB->FixString($admin['username'])."', ". // Display name the world sees
        "'". $pmtDB->FixString($admin['email'])   ."', ". // Email
        //"1, " .                                     // Group_Id (ADMIN)
        "true," .                                   // Active (YES)
        "'');";                                      // Session_Hash

    // print("<li><b>[query]</b> - ".$q."</li>\n");
    $pmtDB->Query($q);

    // Add user to Group ADMIN
    $q ="INSERT INTO ".$dbase["prefix"]."USER_GROUP (User_Id, Group_Id) VALUES (1,1);";
    // print("<li><b>[query]</b> - ".$q."</li>\n");
    $pmtDB->Query($q);

    print("<li><b>[PMT]</b> - Generating 'config.php'</li>\n");
    // Generate Config file
    //$buff = GenerateConfig($dbase);
    $cfgPHP = array();
    $cfgPHP[] ="<?php";
    $cfgPHP[] ='';
    $cfgPHP[] ="/**";
    $cfgPHP[] =" * xenoPMT - Copyright 2010-2012 (C) Xeno Innovations, Inc.";
    $cfgPHP[] =" * ALL RIGHTS RESERVED";
    $cfgPHP[] =" *";
    $cfgPHP[] =" * Description:";
    $cfgPHP[] =" *   This is the Default CORE config file, becareful when";
    $cfgPHP[] =" *   editing this file as it will effect your entire system.";
    $cfgPHP[] =" */";
    $cfgPHP[] ='';
    $cfgPHP[] ='$pmtConf = array(';
    $cfgPHP[] ='  "db" => array(';
    $cfgPHP[] ='    "server"  => "' . $dbase["server"] .'",   // Database Server';
    $cfgPHP[] ='    "user"    => "' . $dbase["user"] . '",    // Database User';
    $cfgPHP[] ='    "pass"    => "' . $dbase["pass"] . '",    // Database Password';
    $cfgPHP[] ='    "dbname"  => "' . $dbase["dbname"] . '",  // Database Name';
    $cfgPHP[] ='    "prefix"  => "' . $dbase["prefix"] . '"   // Table Prefix';
    $cfgPHP[] ='  ),';
    $cfgPHP[] ='  "general" => array(';
    $cfgPHP[] ='    "auth_only" => true,     // Allow access to public or auth-only';
    $cfgPHP[] ='    "title"     => "xenoPMT",';
    $cfgPHP[] ='    "base_url"  => "http://pmt/"    // Must include / at the end.';
    $cfgPHP[] ='  )';
    $cfgPHP[] =');';
    $cfgPHP[] ='';
    $cfgPHP[] ="?>";

    $cfgPHP = implode(PHP_EOL, $cfgPHP);
    print("        <li><b>[PMT]</b> - Writing 'config.php'</li>\n");
    print("      </ul>");
    print("    </div>");

    if(!file_exists("../xpmt/config.php") && is_writable("../xpmt"))
    {
      $fh = fopen("../xpmt/config.php", "w+");
      fwrite($fh, $cfgPHP);
      ?>
      <div align="center" class="message good">Installation Finished</div>
      <div id="actions"><a href="../">xiPHP Main</a></div>
      <?php
      // Use this in the future
      // print("<div id='actions'><a href='../user/" . $dbase['user'] . "'>Admin Control Panel</a></div>");

      fclose($fh);
    }
    else
    {
      ?>
      <div align="center" class="message error">
        The config file ('/xpmt/config.php') was unable to be generated. Please make sure
        that the folder has write permissions to complete this operation, otherwise create
        this file manually from '/xpmt/config.default.php'.
      </div>
      <pre id="config_code">
        <?php print(htmlentities($cfgPHP)); ?>
      </pre>
      <?php
    }


    break;


  /**
   *Something didn't happen right
   */
  default:

    /* Connection Test and display fail/success */
    CreateHeader("Step UNKNOWN!");
    ?>
    <div>
      Should not be here.. error!
    </div>
    <?php
    break;
}

print("<hr><div align='center'><p>Admin Reset Options: <br />" .
      "&nbsp;&nbsp; * <a href='?reset=full'>Full System Reset (cfg &amp; db)</a> [removes config] " .
      "<br />" . //"- or - " .
      "&nbsp;&nbsp; * <a href='?reset=db'>DB Only (drop &amp; create)</a> [retains config]</p></div>");

CreateFooter();

?>
