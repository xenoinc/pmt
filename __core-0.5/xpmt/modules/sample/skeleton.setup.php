<?php

/** * *********************************************************
 * Copyright 2012 (C) Xeno Innovations, Inc.
 * ALL RIGHTS RESERVED
 * @Author:       Damian Suess
 * Document:      skeleton
 * Created Date:  Dec 19, 2012
 * Status:        {unstable/pre-alpha/alpha/beta/stable}
 * Description:
 *  This is here to be used as a skeleton for module setup files so that
 *  you can just copy/paste and fill in the rest.
 *
 *  The Setup class is baised off of the ISetup interface found under the
 *  namespace, "\xenoPMT\Module\ISetup"
 *
 *  There are 4 parts to this [Constructor], [Public], [Private] and [Unit Testing]
 *
 * Sample Unit Testing:
 *    Add this right after the "namespace" in the UnitTest file. NetBeans freaks doen't automatically
 *    handle namespaces so we have to manually add this.
 *
 *    require_once("C:/_work/xi/xenoPMT/sandbox/core-0.5/xpmt/modules/sample/skeleton.setup.php");
 *
 * Change Log:
 *  2012-1219 * Created to current xenoPMT Core-v0.0.5 specifications
 */

namespace xenoPMT\Module\MODULE_CLASS_NAME
{
  require_once "/../../core/xpmt.i.setup.php";
  class Setup implements \xenoPMT\Module\ISetup
  {
    /**
     * !!![ REMOVE ME AFTER PASTING List is finished ]!!!
     *
     * TO DO LIST:
     * [ ] Change Namespace from "MODULE_CLASS_NAME" to your own
     * [ ] Change $_uuid to your Unique Identifier
     * [ ] VerifyPreInstall()   - Add Code
     * [ ] VerifyPreUninstall() - Add Code
     * [ ] privInstall()        - Add Code
     * [ ] privUninstall()      - Add Code
     * [ ] If Unit Testing, customize PHPUNIT_FakeHeader() (dont forget Namespace)
     *
     * !!![ EOF ]!!!
     */
    // const   UUID = "00000000-0000-0000-0000-000000000000";  // Which one is better?  Usage: $u = xenoPMT\Module\MOD_NAME\Setup\UUID;
    private $_uuid = "00000000-0000-0000-0000-000000000000";
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



    // ################################### //
    // ##[ Constructor ]################## //
    // ################################### //

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
      global $xpmtConf;
      // debug("Entering UUID Setup Constructor");

      // what is our intended action?
      $this->_installModule = $boolInstall;

      // Clear all verification messages!
      $this->_verified = false;
      $this->_verifiedMessages["CoreInvalid"] = false;
      $this->_verifiedMessages["IsInstalled"] = false;
      $this->_verifiedMessages["URN_Conflict"] = false;
      $this->_verifiedMessages["UUID_Conflict"] = false;
      $this->_verifiedMessages["DbConnect_Failed"] = false;
      $this->_verifiedMessages["DbQuery_Failed"] = false;

      // Are we in UNIT TESTING mode?
      if (is_array($headerInfo) == false || $headerInfo == "")
      {
        /* Unit Testing Mode */

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
        /* Standard Mode */

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
          $this->_version     = $headerInfo["version"];
          $this->_title       = $headerInfo["title"];
          $this->_description = $headerInfo["description"];
          $this->_urn         = $headerInfo["urn"];
          $this->_classname   = $headerInfo["classname"];
          $this->_namespace   = $headerInfo["namespace"];
          $this->_path        = $headerInfo["path"];
          $this->_mainfile    = $headerInfo["mainfile"];
          $this->_core        = $headerInfo["core"];

          if($this->_installModule == true)
            $this->_verified = $this->VerifyPreInstall();
          else
            $this->_verified = $this->VerifyPreUninstall();
        }
      }
    } // end::_construct



    /* ##################################### */
    /* ##[ Public Members ]################# */
    /* ##################################### */

    /* NOTE: Public members are dummy for unit testing purposes */

    /**
     * Safly return verification property status
     * @return boolean
     */
    public function Verified()
    {
      return $this->_verified;
    } // end::Verified()

    /**
     * Property :: Safly return Verification Check-List property array
     * @return array
     */
    public function GetVerifiedMessages()
    {
      return $this->_verifiedMessages;
    } // end::GetVerifiedMessages()

    /**
     * Install Module
     * If unit testing then override the return statement
     *
     * @assert () == true
     * @return boolean  True  = Successful installation
     *                  False = Failed to install module
     */
    public function Install()
    {
      global $xpmtConf;
      return true;                        // Use ONLY for Unit Testing
      // return $this->privInstall();     // Perform install process
    } // end::Install()

    /**
     * Uninstall Module
     * If unit testing then override the return statement
     *
     * @assert () == true
     * @return boolean  True  = Successful uninstallation
     *                  False = Failed to uninstall module
     */
    public function Uninstall()
    {
      global $xpmtConf;
      return true;                        // Use ONLY for Unit Testing
      // return $this->privUninstall();   // Perform uninstall process
    } // end::Uninstall



    // ################################### //
    // ##[ Private Parts ]################ //
    // ################################### //

    /**
     * Verify if we can install or not
     * @return boolean      True=PASSED, False=FAIL
     */
    private function VerifyPreInstall()
    {
      global $xpmtConf;
      $bRet = false;

      /* ... Insert Code ... */

      /* Code Hints:
       * 1. Check for prev UUID
       * 2. Check if required tables, values or modules exist
       */

      return $bRet;
    } // end::VerifyPreInstall()

    /**
     * Check if we can delete this mdoule from the database
     * @return boolean      True=PASSED, False=FAIL
     */
    private function VerifyPreUninstall()
    {
      global $xpmtConf;
      $bRet = false;

      /* ... Insert Code ... */

      /* Code Hints:
       * 1. Check for UUID
       * 2. Check if other modules/tables/value require this or Forgin Key constraints
       */

      return $bRet;
    } // end::VerifyPreUninstall()

     /**
     * Actual Installer
     * @global array $xpmtConf
     * @return boolean      True=SUCCESS, False=FAIL
     */
    private function privInstall()
    {
      if ($this->_verified == false)
        return false;

      global $xpmtConf;
      $bRet = false;

      /* ... Insert Code Here ... */

      return $bRet;
    } // end::privInstall()

    /**
     * Actual Uninstaller
     * @global array $xpmtConf
     * @return boolean      True=SUCCESS, False=FAIL
     */
    private function privUninstall()
     {
      if ($this->_verified == false)
        return false;

      global $xpmtConf;
      $bRet = false;

      /* ... Insert Code Here ... */

      return $bRet;
    } // end::privUninstall()



    /* ##################################### */
    /* ##[ Unit Testing ]################### */
    /* ##################################### */

    /**
     * If you are testing the Installer then uncomment then use the last
     * line, otherwise return "true" to pass or  "false" to hault
     *
     * @assert () == true
     * @return boolean  TRUE = Passed. FALSE = Falied
     */
    public function PHPUNIT_VerifyPreInstall()
    {
      global $xpmtConf;
      return false;
      // return $this->VerifyPreInstall();
    } // end::PHPUNIT_VerifyPreInstall()

    /**
     * If you are testing the Installer then uncomment then use the last
     * line, otherwise return "true" to pass or  "false" to hault
     *
     * @assert () == true
     * @return boolean  TRUE = Passed. FALSE = Falied
     */
    public function PHPUNIT_VerifyPreUninstall()
    {
      global $xpmtConf;
      return false;
      //return $this->VerifyPreUninstall();
    } // end::PHPUNIT_VerifyPreUninstall()

    /**
     * Generate fake header information for unit testing
     *
     * @global array $xpmtConf
     * @return boolean
     */
    private function PHPUNIT_FakeHeader()
    {
      global $xpmtConf;

      $this->_author      = "...";
      $this->_version     = "0.0.5";
      $this->_title       = "...";
      $this->_description = "...";
      $this->_urn         = "...";
      $this->_classname   = "...";
      $this->_namespace   = "xenoPMT\\Module\\MODULE_CLASS_NAME";
      $this->_path        = "dirname(__FILE__)";
      $this->_mainfile    = "XXX.main.php";
      $this->_core        = false;
      $this->_uuid        = "00000000-0000-0000-0000-000000000000";

      // MANUALLY Create connection to test database

      $xpmtConf = array(
        // Database Connection
        "db" => array(
          "server"  => "localhost", // Database server
          "dbname"  => "PMT_TEST",  // Database name
          "prefix"  => "XI_",       // Table prefix
          "user"    => "betauser",  // Database username
          "pass"    => "betapass"  // Database password
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
      return true;
    } // end::PHPUNIT_FakeHeader()

    /**
     * This is a useless debugging function to ensure your function name
     * behind the namespace and the class.
     * @assert () == "xenoPMT\Module\MODULE_CLASS_NAME\Setup::PHPUNIT_WhoAmI"
     * @return system Method name behind namespace.
     */
    public function PHPUNIT_WhoAmI()
    {
      return __METHOD__;
    } // end::PHPUNIT_WhoAmI()
  } // end::class
} // end::namespace

?>
