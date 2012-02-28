<?php

/* * **********************************************************
 * Copyright 2012 (C) Xeno Innovations, Inc.
 * ALL RIGHTS RESERVED
 * Author:       suessdam
 * Document:     index
 * Created Date: Jan 30, 2012
 *
 * Description:
 *  xiPMT system installation
 *
 * To Do:
 *  [ ] Step 2.a - Place into a function so we can resuse it in 2.b
 *  [ ] Step 2.a - Use disappearing suggestive text
 * Change Log:
 *  2012-0225 * 50% there (djs)
 */

require_once "installer.php";
require_once "../lib/common/pmt.db.php";

$defVal = array();
$defVal["server"] = "localhost";
$defVal["user"]   = "testuser";
$defVal["pass"]   = "testpass";
$defVal["dbname"] = "PMT_DATA";
$defVal["prefix"] = "XIPMT_";

/*
 * Auto disappear text
  <input name="myvalue" type="text" onFocus="if(this.value=='enter value')this.value='';" value="enter value">
  <input type="text" name="inputBox" placeholder="enter your text here">
  <input type="text" name="theName" value="DefaultValue" style="color:#BBB;"
         onblur="if(this.value == ''){ this.value = 'DefaultValue'; this.style.color = '#BBB';}"
         onfocus="if(this.value == 'DefaultValue'){ this.value = ''; this.style.color = '#000';}" />
*/

/**
 * Get Confiuration Data
 * @param string $cfgItem $config db array item (server, user, pass, dbname, prefix)
 * @param string $default Default output if not set
 * @return Output (default) config setting
 */
function getData($cfgItem, $default)
{
  global $conf;

  if (isset($conf["db"][$cfgItem]))
    return $conf["db"][$cfgItem];
  else
    return $default;
}

// Get the step in the setup process
$step = (isset($_POST['step']) ? $_POST['step'] : 1);

switch ($step)
{
  /**
   * Welcome screen
   */
  case 1:
    CreateHeader("Welcome to xenoPMT", $step);

    if (IsInstalled())
    {
      htmlMessage("xiPMT is already Installed");
    }
    elseif(file_exists("../lib/config.php"))
    {
      htmlMessage("<code>system/config.php</code> exists, unable to continue.");
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
      if(file_exists("../lib/config.php"))
        require_once "../lib/config.php";

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
      if($fail)
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
      else
      {
        ?>
        <form action="index.php" method="post">
          <input type="hidden" name="step" value="<?php print($step+1); ?>" />
          <input type="hidden" name="db" value="<?php print(json_encode($_POST["db"])); ?>" />
          <div align="center" class="message good">Database connection successful!</div>
          <div id="actions"><input type="submit" value="Next" /></div>
        </form>
        <?php
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
        <input type="hidden" name="step" value="<?php ?>" />
        <input type="hidden" name="db" value="<?php ?>" />

        <h2>Administration Configuration</h2>

        <h3>xiPMT Settings</h3>
        <table class="inputForm">
          <tbody>
            <tr><td class="label">xiPMT Title</td><td><input type="text" name="settings[title]" autocomplete="off" value="" /></td></tr>
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

      ?>
      <form action="index.php" method="post">
        <input type="hidden" name="step"      value="<?php print(step+1); ?>" />
        <input type="hidden" name="db"        value="<?php print($_POST["db"]); ?>" />
        <input type="hidden" name="settings"  value="<?php print(json_encode($_POST["settings"])); ?>" />
        <input type="hidden" name="admin"     value="<?php print(json_encode($_POST["admin"])); ?>" />

        <h2>Administration Configuration</h2>

        <h3>xiPMT Settings</h3>
        <table class="inputForms">
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
      CreateHeader("Step 3.a - Admin Settings (err)");
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

    // InstallPMT();

    $dbase = json_decode($_POST["db"], true);
    $settings = json_decode($_POST["settings"], true);
    $admin = json_decode($_POST["admin"], true);

    $db = new Database($dbase["server"], $dbase["user"], $dbase["pass"], $dbase["dbname"]);

    // Extract SQL & put prefix on tables
    $sqlPmtBrain = file_get_contents("pmt-db.sql");
    $sqlPmtBrain = str_replace("PMT_", $dbase["prefix"], $sqlPmtBrain);
    $query = explode(";", $sqlPmtBrain);

    // Run the queries
    foreach($query as $q)
    {
      if(!empty($q) && strlen($q) > 5)
        $db->Query($q);
    }

    // Create default values
    InsertDefaults($db, $dbase, $settings, $admin);

    // Generate Config file
    $buff = GenerateConfig($dbase);




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

CreateFooter();

/*
function GenStep2Database()
{
?>
      <form action="index.php" method="post">
        <input name="step" type="hidden" value="<?php print($step); ?>" />
        <h2>Input Database Information</h2>
        <table class="inputForm">
          <tr>
            <td class="label">Server:</td>
            <td><input type="text" name="db[server]" autocomplete="off" value="<?php print(getData("server")); ?>" /> </td>
          </tr>
          <tr>
            <td class="label">Username:</td>
            <td><input type="text" name="db[user]" autocomplete="off" value="<?php print(getData("user")); ?>" /> </td>
          </tr>
          <tr>
            <td class="label">Password:</td>
            <td><input type="text" name="db[pass]" autocomplete="off" value="<?php print(getData("pass")); ?>" /> </td>
          </tr>
          <tr>
            <td class="label">Database Name:</td>
            <td><input type="text" name="db[dbname]" autocomplete="off" value="<?php print(getData("dbname")); ?>" /> </td>
          </tr>
          <tr>
            <td class="label">Database Table Prefix:</td>
            <td><input type="text" name="db[prefix]" autocomplete="off" value="<?php print(getData("prefix")); ?>" /> </td>
          </tr>
        </table>
        <div id="actions">
          <input type="submit" value="Next" />
        </div>
      </form>
<?php
}
*/


/**
 * Performed during Step 4 - Installation
 */
function InstallPMT()
{

}

/**
 * Insert the default database information
 */
function InsertDefaults(Database $db, $dbcfg, $settings, $admin)
{
  $pfx = $dbcfg["prefix"];

  /*
  // Plugin :: Textile
  $db->Query( "INSERT INTO `".$pfx."PLUGINS` (`Name`, `Author`, `Website`, `Version`, `Enabled`, `Install_Sql`, `Uninstall_Sql`) VALUES " .
              "('Textile Formatting', 'Jack', 'http://unknown/', '1.0', 1, '', '');");
  $tmp =$db->Res
          ("
            global $textile;
            if(!isset($textile)) {
              require(PMT_PATH.'system/plugins/classTextile.php');
              $textile = new Textile;
            }
            $text = $textile->TextileThis($text);"
          );
	$db->Query( "INSERT INTO `" . $dbconf['prefix'] . "plugin_code` (`plugin_id`, `title`, `hook`, `code`, `execorder`, `enabled`) VALUES ".
              " (" . $db->InsertId() . ", 'formattext', 'function_formattext', '" . $tmp . "', 0, 1);");
  */

  //$db->Query("INSERT INTO ".$pfx."SETTINGS (`value`, `setting`) VALUES ('".$db->Res($settings["title"])."', 'title';" );
  $db->Query("UPDATE ".$pfx."SETTINGS SET VALUE='".$db->Res($settings["title"])."' WHERE setting='title';" );
}

?>
