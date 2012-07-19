<?php

/* * **********************************************************
 * Copyright 2012 (C) Xeno Innovations, Inc.
 * ALL RIGHTS RESERVED
 * Author:       suessdam
 * Document:     installer2
 * Created Date: Mar 4, 2012
 *
 * Description:
 *  This file contains extended installer functions
 *  which may or may not be used in this version.
 *
 * Change Log:
 *
 */


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


/**
 * Performed during Step 4 - Installation
 */
function InstallPMT()
{

}


/**
 * Insert the default database information
 * @param Database $pmtDB Database class passed in
 * @param string $dbcfg Database Config array
 * @param string $settings Settings array
 * @param string $admin Administrator name
 */
function InsertDefaults(Database $pmtDB, $dbcfg, $settings, $admin)
{
  $pfx = $dbcfg["prefix"];

  /*
  // Plugin :: Textile
  $pmtDB->Query( "INSERT INTO `".$pfx."PLUGINS` (`Name`, `Author`, `Website`, `Version`, `Enabled`, `Install_Sql`, `Uninstall_Sql`) VALUES " .
              "('Textile Formatting', 'Jack', 'http://unknown/', '1.0', 1, '', '');");
  $tmp =$pmtDB->FixString
          ("
            global $textile;
            if(!isset($textile)) {
              require(PMT_PATH.'system/plugins/classTextile.php');
              $textile = new Textile;
            }
            $text = $textile->TextileThis($text);"
          );
	$pmtDB->Query( "INSERT INTO `" . $dbconf['prefix'] . "plugin_code` (`plugin_id`, `title`, `hook`, `code`, `execorder`, `enabled`) VALUES ".
              " (" . $pmtDB->InsertId() . ", 'formattext', 'function_formattext', '" . $tmp . "', 0, 1);");
  */

  //$pmtDB->Query("INSERT INTO ".$pfx."SETTINGS (`value`, `setting`) VALUES ('".$pmtDB->FixString($settings["title"])."', 'title';" );
  $pmtDB->Query("UPDATE ".$pfx."SETTINGS SET VALUE='".$pmtDB->FixString($settings["title"])."' WHERE setting='title';" );

}



?>
