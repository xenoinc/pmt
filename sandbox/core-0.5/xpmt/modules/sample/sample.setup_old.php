<?php

/** * *********************************************************
 * Copyright 2012 (C) Xeno Innovations, Inc.
 * ALL RIGHTS RESERVED
 * @Author:       Damian Suess
 * Document:      sample
 * Created Date:  Oct 19, 2012
 * Status:        test
 * Description:
 *
 *
 * Change Log:
 *  2012-1219 * Updated members to reflect new layout
 *  2012-1206 + Added namespace and interface
 *  2012-1019 * Created skeleton
 */

namespace xenoPMT\Module\Sample
{
  require_once "/../../core/xpmt.i.setup.php";
  class Setup implements \xenoPMT\Module\ISetup
  {

    private $_uuid = "ef64ffb0-19a8-11e2-892e-0800200c9a66";         // Universally Unique identifier
    private $_author;
    private $_version;
    private $_title;
    private $_description;
    private $_urn;
    private $_classname;
    private $_namespace;
    private $_path;
    private $_mainfile;
    private $_core;

    /**
     * Module has be verified and there are not duplicates in database
     * ? Handled in Construct() or PreInstallErrors()?
     * @var boolean
     */
    private $_verified = false;

    /**
     *
     * Each associative array item contains a boolean value
     * <ul>
     *  <li><b>CoreInvalid</b>      - Is module valid with this verion of xenoPMT core (xpmtCore["info"]["version_ex"])</li>
     *  <li><b>IsInstalled</b>      - Is module previously installed?</li>
     *  <li><b>URN_Conflict</b>     - Is URN previously used by other modules?</li>
     *  <li><b>UUID_Conflict</b>    - Is this UUID previously included in $xpmtModule[][]? (and also database v0.0.7)</li>
     *  <li><b>DbConnect_Failed</b> - Database connection failed (this "shouldn't" happen)
     *  <li><b>DbQuery_Failed</b>   - Database query failed (this "shouldn't" happen)
     * </ul>
     *
     * @var array
     */
    private $_verifiedMessages = array();

    /**
     *
     * @var boolean
     */
    private $_installModule = false;


    /**
     * Unit Testing:
     *  If $headerInfo == "" then we are in PHPUnit testing mode!
     *
     * @global array $xpmtConf
     *
     * @param boolean $boolInstall      TRUE = Install  FALSE = Uninstall
     * @param array $headerInfo
     */
    public function __construct($boolInstall = true, $headerInfo = "")
    {
      debug("Setup Sample");

      $this->_verified = false;
      $this->_verifiedMessages["CoreInvalid"] = false;
      $this->_verifiedMessages["IsInstalled"] = false;
      $this->_verifiedMessages["URN_Conflict"] = false;
      $this->_verifiedMessages["UUID_Conflict"] = false;
      $this->_verifiedMessages["DbConnect_Failed"] = false;
      $this->_verifiedMessages["DbQuery_Failed"] = false;

      if (is_array($headerInfo) == false || $headerInfo == "")
      {
        // Perform unit testing
      }
      else
      {
        // Verify our UUID, if it passes, Verify Module Un/install action
        if ($this->_uuid != $headerInfo["uuid"])
        {
          $this->_verified = false;
          $this->_verifiedMessages["UUID_Conflict"] = true;
        }
        else
        {
          // So far so good, now let's verify!
          $this->_author      = $headerInfo["author"];
          $this->_version     = "0.0.5";
          $this->_title       = "Sample Module!";
          $this->_description = "This is a sample module.";
          $this->_urn         = "sample";                   // use, "" if it's a dashboard replacement
          $this->_classname   = "sample";                   // Name of our module class
          $this->_namespace   = "xenoPMT\\Module\\Sample";  // Namespace of module
          $this->_path        = "dirname(__FILE__)";
          $this->_mainfile    = "sample.main.php";          // Main file to execute
          $this->_core        = false;                      // (Boolean) Is this a core module or not.
                                                            // Note: Core mods can only be removed by admins

          if($this->_installModule == true)
            $this->_verified = $this->VerifyPreInstall();
          else
            $this->_verified = $this->VerifyPreUninstall();
        }
      }
    }
    // ################################### //
    // ##[ Private Parts ]################ //
    // ################################### //
    /**
     * Verify if we can install or not
     *
     * @return boolean  TRUE = Passed. FALSE = Falied
     */
    private function VerifyPreInstall()
    {
      return false;
    }
    /**
     * Check if we can delete this mdoule from the database
     *
     * @return boolean  TRUE = Passed. FALSE = Falied
     */
    private function VerifyPreUninstall()
    {
      global $xpmtConf;
      $bRet = false;

      $db = new \mysqli($xpmtConf["db"]["server"],  $xpmtConf["db"]["user"],
                        $xpmtConf["db"]["pass"],    $xpmtConf["db"]["dbname"]);
      if(!$db->connect_errno)
      {
        $sql = "select * from {$xpmtConf["db"]["prefix"]}CORE_MODULE where `Module_UUID` = '{$this->_uuid}'";
        $db->real_query($sql);
        if ($db->field_count > 0)
        {
          // Found matching item to delete! YAY!
          $this->_verifiedMessages["UUID_Conflict"] = false;
          $bRet = true;
        }
        else
        {
          // Didn't find the module installed
          $this->_verifiedMessages["UUID_Conflict"] = true;
          $bRet = false;
        }
        $db->close();
      }
      else
      {
        // Database loading error
        $this->_verifiedMessages["DbConnect_Failed"] = true;
        $bRet = false;
      }

      return $bRet;
    }
    /* ##################################### */
    /* ##[ Public Members ]################# */
    /* ##################################### */


    /* ######################################## */

    /**
     * Get Module Data
     * Created: 2012-10-19
     * Move this to /xmpt/core/pmt.core.php (replacement for "pmt-functions", OOP approach)
     *
     * @return array    $xpmtModule Data. Return NULL if empty
     */
    private function GetModuleData()
    {

      $retModData = null;     // Return array of module data
      $found = false;         // Check for duplicates
      global $xpmtModule;     // Pull back all config file registered modules

      foreach ($xpmtModule["info"] as $mod)
      {
        //$ret = print_r($mod, true);
        //debug($ret);
        if ($mod["uuid"] == $this->_uuid)
        {
          if ($found == false)
          {
            $found = true;
            $retModData = $mod;
          }
          else
          {
            // Should we fail since there are two??   YES!!
            // this could fuck-up with a first-come first-serve
            $retModData = null;
          }
        }
      }

      return $retModData;
    }


    /**
     * PreInstallErrors
     * Verifies that the module is ok to install
     * @param array $arrChkPoints Array of check points
     * @return boolean
     *    True = Failed inspection
     *    False = Passed inspection
     */
    public function PreInstallErrors(&$arrChkPoints)
    {
      global $xpmtCore, $xpmtModule;

      $bCoreInvalid = false;
      $bIsInstalled = false;
      $bURNConflict = false;
      $bUUIDConflict = false;
      $bInspectionFailed = false;     // return with a PASS for now

      /**[ Step 1) Is Core Version compatiable? ]************   */

      if ($xpmtCore["info"]["version_ex"] >= $this->CoreVersion)
      //if ($xpmtCore["info"]["version_ex"] >= $this->CoreVersion)
        $bCoreInvalid = false;
      else
      {
        $bCoreInvalid = true;
        $bInspectionFailed = true;
      }


      /**[ Step 2) Is Previously Installed? ]************   */


      /**[ Step 3) Is there a URN conflict? ]************   */
      {
        //$result = $db->query("SELECT COUNT(*) FROM `table`");
        //$row = $result->fetch_row();
        //echo '#: ', $row[0];
        // -----------
        //$result = $mysqli->query("SELECT COUNT(*) AS cityCount FROM myCity")
        //$row = $result->fetch_assoc();
        //echo $row['cityCount']." rows in table myCity.";
        //$result->close();
      }

      /**[ Step 3) Is there a UUID conflict? ]************   */
      // This checks all config file registered modules for duplicate UUIDs
      {
        $__uuidFound = false;
        $__uuidFoundAgain = false;
        foreach ($xpmtModule["info"] as $__mod)
        {
          if ($__mod["uuid"] == $this->_uuid)
            if ($__uuidFound == false)
              $__uuidFound = true;
            else
              $__uuidFoundAgain = true;
        }
        if ($__uuidFoundAgain == false)
          $bUUIDConflict = false;
        else
        {
          $bUUIDConflict = true;
          $bInspectionFailed = true;
        }
      }


      /**[ return report ]****** */
      $arrChkPoints = array(
          "CoreInvalid"     => $bCoreInvalid,   // Valid / Invalid Core
          "IsInstalled"     => $bIsInstalled,   // Search for UUID
          "URN_Conflict"    => $bURNConflict,   // Previously used URN
          "UUID_Conflict"   => $bUUIDConflict   // Previously used Module UUID
          );

      return $bInspectionFailed;
    }

    /* ############################################ */


    public function Install()
    {
      global $xpmtConf;

      // 1) Verify if prev-installed / compatable
        // i.   Pull db info for prev install
        // ii.  Check Core Version against this

      // 2) Execute the code in (ext/sample.install.sql)

      //$query = "INSERT INTO myCity VALUES (NULL, 'Stuttgart', 'DEU', 'Stuttgart', 617000)";
      //$mysqli->query($query);
      //printf ("New Record has id %d.\n", $mysqli->insert_id);

      $pfx = $xpmtConf["db"]["prefix"];

      // Future, get our Module Info from xpmtModule["info"][$ndx] via scanning it
      // global $xpmtModule, $xpmtCore;    // $xpmtCore["module"][];


      if ($this->_core) $boolCore = "TRUE"; else $boolCore = "FALSE";


      $q1 = <<<"sql"
      INSERT INTO ${pfx}CORE_MODULE
      ( `Module_UUID`, `Core`, `Enabled`,
        `Module_Name`, `Module_Version`, `Module_Path`,
        `Module_Class`, `Module_URN`, `Description`
      ) VALUES (
        '{$this->_uuid}', $boolCore, FALSE,
        '{$this->_title}', '{$this->_version}', '{$this->_path}',
        '{$this->_classname}', '{$this->_urn}', '{}');
sql;

      //$mysqli = new mysqli("", "", "");
      //$mysqli->query($q1);
      //$ndx = $mysqli->insert_id;

      /*
       * Gui Options:
       *  [ ] Insert test data
       *      * Group: "Active" - "dev1-active"
       *      * Group: "Backlog" - "dev1-backlog"
       */

    }

    public function Uninstall()
    {
      // 1) Verify that nothing else is linking back to tickets
      // 2) Execute the uninstall script (ext/sample.uninstall.sql)

    }


    private function unused()
    {
      /*
      -- Module registry
      CREATE TABLE IF NOT EXISTS `TBLPMT_CORE_MODULE`
      (
        `Module_Id`       INT UNSIGNED NOT NULL AUTO_INCREMENT,
        `Module_UUID`     VARCHAR(36) NOT NULL,                           -- Unique Identifer of registered module. No two should be the same
        `Core`            BOOLEAN NOT NULL DEFAULT FALSE,                 -- Is this a core module? DEFAULT=FALSE
        `Enabled`         BOOLEAN NOT NULL DEFAULT FALSE,                 -- Disable all new modules by default
        `Module_Name`     VARCHAR(64) collate utf8_unicode_ci not null,   -- name of module "kb"
        `Module_Version`  VARCHAR(16),                                    -- Version number/Name (0.2 nighthawk)
        `Module_Path`     VARCHAR(255) collate utf8_unicode_ci not null,  -- main install path ("/module/kb/kb.php" or "kb.php")
        `Module_Class`    VARCHAR(255) collate utf8_unicode_ci not null,  -- class name to be called
        `Module_URN`      VARCHAR(16) NOT NULL,                           -- BASE Uniform Resource Name (kb, p, customer, ..)
        `Description`     VARCHAR(255) collate utf8_unicode_ci not null,
        primary key (`Module_Id`)
      ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

      -- Module Configuration Settings
      create table if not exists `TBLPMT_CORE_MODULE_CONFIG`
      (
        `Module_UUID`   VARCHAR(36),
        `Setting` varchar(255) collate utf8_unicode_ci not null,
        `Value`   longtext collate utf8_unicode_ci not null,
        primary key (`Module_UUID`)
      ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

      -- During the install of Modules data is insert into this.
      -- This is to used for User/Group permissions and is setup by the Administrators
      -- Used with XIPMT_GROUP_PRIV and/or XIPMT_USER_MODULE_PRIV table (if one exists)
      CREATE TABLE IF NOT EXISTS `TBLPMT_S_CORE_MODULE_PRIV`
      (
        `Module_UUID`       VARCHAR(36) NOT NULL,
        `Priv_Name`         VARCHAR(32),
        `Priv_Description`  VARCHAR(64),
        `DataType`          VARCHAR(8),     -- 'string', 'integer'
        `DefaultValue`      VARCHAR(64),    -- Default setting suggested to user
        primary key (`Module_UUID`)
      );

      -- Not currently used
      -- For modules with multiple URNs  (p, proj, project)
      CREATE TABLE IF NOT EXISTS `TBLPMT_CORE_MODULE_URN`
      (
        `Module_UUID`   VARCHAR(36) NOT NULL,                             -- This is better than using Module_Id
        `Module_URN`    VARCHAR(16)                                       -- don't let people get crazy with lengths
      ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


      */


        /*
         * Find Module by UUID Test code
         *
          $xpmtModule["info"][] = array
          (
            "author"      => "Damian Suess",
            "version"     => "1.0",
            "title"       => "Sample Module Title",
            "description" => "Sample description for module to show up in loader",
            "urn"         => "sample",                                      // Uniform Resource Name of the module (pmt.com/ticket) ** possibly deprecated
            "classname"   => "sample",                                      // name of the class inside of "$xpmtModule["info"][$ndx]->path"
            "path"        => "/sample.main.php",        // Path to module core
            "core"        => "false",                                       // core system module (true=core)
            "uuid"        => "ef64ffb0-19a8-11e2-892e-0800200c9a66"         // Universally Unique identifier
          );

          $arrData = null;
          $_uuid   = "ef64ffb0-19a8-11e2-892e-0800200c9a66";

          foreach ($xpmtModule["info"] as $mod)
          {
            $ret = print_r($mod, true);
            debug($ret);
            if ($mod["uuid"] == $_uuid)
            {
              debug ("hello");
            }
          }
         *
         */
    }
  }
}

?>
