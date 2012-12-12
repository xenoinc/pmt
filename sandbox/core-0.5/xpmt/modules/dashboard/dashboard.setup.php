<?php

/** * *********************************************************
 * Copyright 2012 (C) Xeno Innovations, Inc.
 * ALL RIGHTS RESERVED
 * @Author:       Damian Suess
 * Document:      dashboard
 * Created Date:  Dec 3, 2012
 * Status:        {unstable/pre-alpha/alpha/beta/stable}
 * Description:
 *
 *
 * To Do:
 *  [ ] Keep pulling sample info from "sample.setup.php"
 *  [ ] Add column in databsae (and ModHeader) for namespace ("xenoPMT\Module\Dashboard")
 *  [X] Sample execution of members inside a namespace
 *    //require_once "kb-main.php";
 *    //$k = new xenoPMT\Module\KB\Main;
 *    //$html .= $k->PageLayout(1);
 *
 *PHPUNIT_VerifyPreUninstall
 *
 * Change Log:
 *  2012-1206 + Added interface which requires PreInstallErrors()
 *            - Removed 'static' from members due to ISetup interface. Class must now be instantiated.
 *  2012-1203 + started working on setup script
 */

namespace xenoPMT\Module\Dashboard
{
  require_once "/../../core/xpmt.i.setup.php";
  class Setup implements \xenoPMT\Module\ISetup
  {
    private $_uuid = "df9f29f8-1aed-421d-b01c-860c6b89fb14";
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



    // ######################################################### //

    /**
     *
     * Unit Testing:
     *  If $headerInfo == "" then we are in PHPUnit testing mode!
     *
     *
     * @global array $xpmtConf
     *
     * @param boolean $boolInstall      TRUE = Install  FALSE = Uninstall
     * @param array $headerInfo
     */
    public function __construct($boolInstall = true, $headerInfo = "")
    {

      global $xpmtConf;

      $this->_verified = false;
      $this->_installModule = $boolInstall;

      if (is_array($headerInfo) == false || $headerInfo == "")
      {
        // We are unit testing. Generate a fake header
        // and plug in some database information

        $this->PHPUNIT_FakeHeader();

        if($this->_installModule == true)
          $this->_verified = $this->PHPUNIT_VerifyPreInstall();
        else
          $this->_verified = $this->PHPUNIT_VerifyPreUninstall();
      }
      else
      {
        if($this->_installModule == true)
          $this->_verified = $this->VerifyPreInstall();
        else
          $this->_verified = $this->VerifyPreUninstall();

      }
    }

    // ################################### //
    // ##[ Private Parts ]################ //
    // ################################### //

    /**
     * @assert () == true
     *
     * @return boolean  TRUE = Passed. FALSE = Falied
     */
    private function VerifyPreInstall()
    {
      // select * from XI_CORE_MODULES where Module_UUID = 'xxxx';
      return false;
    }


    /**
     *
     * @return boolean  TRUE = Passed. FALSE = Falied
     */
    private function VerifyPreUninstall()
    {
      // Delete * from XI_CORE_MODULES where Module_UUID = 'xxxx';
      return false;
    }


    /* ##################################### */
    /* ##[ Public Members ]################# */
    /* ##################################### */


    /**
     * Safly return verification status
     * @return boolean
     */
    public function Verified()
    {
      return $this->_verified;
    }


    /**
     * Safly return Verification Check List array
     * @return array
     */
    public function GetVerifiedMessages()
    {
      return $this->_verifiedMessages;
    }


    /**
     * Install Module
     *
     * @assert () == true
     *
     * @return boolean  True = Successful installation
     *                  False = Failed to install module
     */
    public function Install()
    {

      if ($this->_verified == false)
        return false;

      global $xpmtConf; // $pmtDB;

      if ($this->_core) $bCore = "TRUE"; else $bCore = "FALSE";

      $sql = <<<"sql"
      INSERT INTO {$xpmtConf["db"]["prefix"]}CORE_MODULE
      ( `Module_UUID`, `Core`, `Enabled`,
        `Module_Name`, `Module_Version`, `Module_Path`,
        `Module_Namespace`,
        `Module_Class`,
        `Module_URN`,
        `Description`
      ) VALUES (
      '{$this->_uuid}', $boolCore, FALSE,
      '{$this->_title}', '{$this->_version}', '{$this->_path}',
      '{$this->_namespace}',
      '{$this->_classname}',
      '{$this->_urn}',
      '{}');
sql;

      //$tmpArr = $pmtDB->Query( $_sql);
      $db = new \mysqli( $xpmtConf["db"]["server"], $xpmtConf["db"]["user"],
                            $xpmtConf["db"]["pass"], $xpmtConf["db"]["dbname"]);
      if(!$db->connect_errno)
      {

        if($db->query($sql))
          return true;
        else
          return false;

        $db->close();
        return true;
      }
      else
      { // connection error
        return false;
      }


    }

    /**
     * Uninstall Module
     *
     */
    public function Uninstall()
    {

      if ($this->_verified == false)
        return false;

      global $xpmtConf;

      // We cannot uninstall the dashboard!!
      // Well, not unless if there is a solid replacement

      return false;
    }



    /* ##################################### */
    /* ##[ Unit Testing ]################### */
    /* ##################################### */

    /**
     * @assert () == true
     * @return boolean  TRUE = Passed. FALSE = Falied
     */
    public function PHPUNIT_VerifyPreInstall()
    {
      return $this->VerifyPreInstall();
    }


    /**
     *
     * @assert () == true
     *
     * @return boolean  TRUE = Passed. FALSE = Falied
     */
    public function PHPUNIT_VerifyPreUninstall()
    {
      return $this->VerifyPreUninstall();
    }


    private function PHPUNIT_FakeHeader()
    {

      /*
        "author"      => "Damian Suess",
        "version"     => "0.0.5",
        "title"       => "xenoPMT Dashboard",
        "description" => "Welcome screen and main page for all users whether they are logged in or not.",
        "urn"         => "",                                      // UniformResourceName of the module (pmt.com/admin)
        "classname"   => "dashboard",                             // Name of class inside of "path"
        "namespace"   => "xenoPMT\\Module\\Dashboard",              // Module's Namespace used by Setup and internal classes
        "path"        => dirname(__FILE__),                       // dirname(__FILE__) . "/sample.main.php"
        "mainfile"    => "dashboard.main.php",                    // Startup clsas for module
        "core"        => true,                                    // core system module (true=core)
        "uuid"        => "df9f29f8-1aed-421d-b01c-860c6b89fb14"   // Universally unique identifier
      */

      $this->_author      = "Damian Suess";
      $this->_version     = "0.0.5";
      $this->_title       = "xenoPMT Dashboard";
      $this->_description = "dashboard";
      $this->_urn         = "";
      $this->_classname   = "dashboard";
      $this->_namespace   = "xenoPMT\\Module\\Dashboard";
      $this->_path        = "dirname(__FILE__)";
      $this->_mainfile    = "dashboard.main.php";
      $this->_core        = true;
      $this->_uuid        = "df9f29f8-1aed-421d-b01c-860c6b89fb14";

      // Create database header information
      //require_once("/../../../config.default.php");
      $xpmtConf = array(
        // Database Connection
        "db" => array(
          "server"  => "localhost", // Database server
          "dbname"  => "PMT_TEST",  // Database name
          "prefix"  => "XI_",       // Table prefix
          "user"    => "betauser",  // Database username
          "pass"    => "betapass",  // Database password
        ),
        // General Site Data
        "general" => array(
          "auth_only" => true,                  // Allow access to public or auth-only
          "title"     => "Corporate Management System",
          "base_url"  => "http://pmt2/",        // Must include '/' at the end.
          "clean_uri" => "1"                    // Clean URI
          // , "allow_public_reg" => false      // This should be in Database under system-cfg
        )
      );

    }

  }
}
?>
