<?php

/* * *********************************************************
 * Copyright 2012 (C) Xeno Innovations, Inc.
 * ALL RIGHTS RESERVED
 * @Author:       Damian Suess
 * Document:      uuid.setup.php
 * Created Date:  Oct 29, 2012
 * Status:        unstable {unstable/pre-alpha/alpha/beta/stable}
 * Description:
 *  UUID Gemerator Installer
 *
 *  private $_uuid = "c6fb97b8-af93-42ce-aac6-de5656c8fdae";
 *
 * Change Log:
 *  2013-0415 + Added property private, $this->_objModInfo to cleanly pass header info
 *            + Added private property, $this->_objModError to quickly pass setup errors
 *              and to minimize spelling errors when using the older associative array.
 *  2012-1206 * Created skeleton
 */

namespace xenoPMT\Module\UUID
{
  require_once "/../../core/xpmt.i.setup.php";  // Interface for Setup class
  require_once "/../../core2/Setup.php";        // /xenoPMT\Core\Setup Class
  require_once "/../../core2/misc/Struct.php";  // Structure class
  require_once "/../../core2/Properties/ModuleInfo.php";  // Module Info Properties Class
  require_once "/../../core2/Properties/ModuleSetupError.php";  // Module Setup Errors Properties Class

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
    private $_uuid = "c6fb97b8-af93-42ce-aac6-de5656c8fdae";
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
     * @var object \xenoPMT\Core\Properties\ModuleInfo
     * @version v0.0.5.2
     * @since 2013-04-15
     */
    private $_objModInfo;


    /**
     * @var object \xenoPMT\Core\Properties\ModuleSetupError
     * @version v0.0.5.2
     * @since 2013-04-15
     */
    private $_objModError;

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
      // TODO:
      //  ? Replace $headerInfo with $objModInfo class properties. For intellisense we should
      //    still declare $headInfo,  $this->_obj = new \ModInfo(); and $this->_obj = $headInfo
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
      // Added 2013-0415
      $this->_objModError = new \xenoPMT\Core\Properties\ModuleSetupError();

      // Are we in UNIT TESTING mode?
      if (is_array($headerInfo) == false || $headerInfo == "")
      {
        /* Unit Testing Mode */

        // We are unit testing. Generate a fake header
        // and plug in some database information
        $this->PHPUNIT_FakeHeader();

        // Added 2013-0415 (not used in current scope, but declared for future reference)
        $this->_objModInfo = new \xenoPMT\Core\Properties\ModuleInfo(
          $this->_uuid,       $this->_core,       true, // $this->_isEnabled,
          $this->_title,      $this->_version,    $this->_path,
          $this->_namespace,  $this->_classname,  $this->_urn,
          $this->_description);

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

          // Added 2013-0415
          //  + Placing into this properties class makes it cleaner
          //    to pass the entire object
          $this->_objModInfo = new \xenoPMT\Core\Properties\ModuleInfo(
            $this->_uuid,       $this->_core,       true, // $this->_isEnabled,
            $this->_title,      $this->_version,    $this->_path,
            $this->_namespace,  $this->_classname,  $this->_urn,
            $this->_description);

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
      //return true;                        // Use ONLY for Unit Testing
       return $this->privInstall();     // Perform install process
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
     *
     * Changes:
     *  2013-0415 + started using _objModInfo and _objModError.
     *            * Changed $bRet to use Logical (&&) operator versus bitwise (&)
     */
    private function VerifyPreInstall()
    {
      global $xpmtConf;
      //$bRet = false;

      /* Code Hints:
       * 1. Check for prev UUID
       *  RET: IsInstalled, UUID_CONFLICT
       * 2. Check if required tables, values or modules exist
       * 3. Check if URN conflicts
       *  RET: URN_Conflict
       */

      // Step 1 - Check PASS/FAIL for PREV UUID (true=pass)

      // $step1 = true;  // passed
      // Return overall Pass/Fail and what failed in $objErr

      $this->_objModError->ClearErrors();

      $step1 = \xenoPMT\Core\Setup::CheckConflict($this->_objModInfo, $this->_objModError);
      if ($step1 == false)
      {
        // there was an error verifying UUID and/or URN
        $this->_verifiedMessages["URN_Conflict"] = $this->_objModError->URN_Conflict;
        $this->_verifiedMessages["UUID_Conflict"] = $this->_objModError->UUID_Conflict;

        //pmtDebug("PreVerify: URN: '{$this->_objModError->URN_Conflict}'");
        //pmtDebug("PreVerify: UUID: '{$this->_objModError->UUID_Conflict}'");
      }

      // Step 2 - Check for required Tables/Values and dependent modules/libs
      $step2 = true;

      // Step 3 - Check for URN conflict
      // TODO: This should be being done in Step 1 (FINISH IT)


      // Perform final test logic
      $bRet = ($step1 && $step2);      // do we want && (logical) or & (bitwise)

      //pmtDebug("UUID Setup PreVerifyInstall: '{$bRet}'");
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
      /*
       * 2013-03-31 * BUG: During INSERT it is not putting in the '\'. must use "\\"
       */
      if ($this->_verified == false)
      {
        pmtDebug("uuid.setup.Install() Cannot install, verification previously failed.");
        return false;
      }
      else
        pmtDebug("uuid.setup.Install() Installing...");

      global $xpmtConf;

      // test new method to register:
      /*
        '{$this->_uuid}', $bCore, TRUE,
        '{$this->_title}', '{$this->_version}', '{$this->_path}',
        '{$this->_namespace}',
        '{$this->_classname}',
        '{$this->_urn}',
        '{$this->_description}');
      */
      $objStructModInfo = \xenoPMT\Core\Misc\Struct::Initialize(
          "Module_UUID",      "IsCore",         "IsEnabled",
          "Module_Name",      "Module_Version", "Module_Path",
          "Module_Namespace", "Module_Class",   "Module_URN",
          "Description");
      $objModInfo = $objStructModInfo->Create(
          $this->_uuid,      $this->_core,      "TRUE",
          $this->_title,     $this->_version,   $this->_path,
          $this->_namespace, $this->_classname, $this->_urn,
          $this->_description);

      $objErrRet = null;  // Struct containing error ret statuses

      $retStatus = \xenoPMT\Core\Setup::RegisterModule($objModInfo, $objErrRet);
      if ($retStatus == true)
      {
        /* Proceed with further setup since we got Green-Light-Go! */
        pmtDebug("UUID Setup: Registered Successfully!");
        $objErrRet = null;  // cleanup object
      }
      else
      {
        // Report back the errors
        pmtDebug("UUID Setup: Failed to register");
        $this->_verifiedMessages["DbQuery_Failed"]    = $objErrRet->DbQuery_Failed;
        $this->_verifiedMessages["CoreInvalid"]       = $objErrRet->CoreInvalid;
        $this->_verifiedMessages["IsInstalled"]       = $objErrRet->IsInstalled;
        $this->_verifiedMessages["URN_Conflict"]      = $objErrRet->URN_Conflict;
        $this->_verifiedMessages["UUID_Conflict"]     = $objErrRet->UUID_Conflict;
        $this->_verifiedMessages["DbConnect_Failed"]  = $objErrRet->DbConnect_Failed;
        $this->_verifiedMessages["DbQuery_Failed"]    = $objErrRet->DbQuery_Failed;
      }
      // end test

      // Return the status pass/fail - true/false
      return $retStatus;
    } // end::privInstall()

    private function privInstall__OLD()
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

      $this->_author      = "Damian J. Suess";
      $this->_version     = "0.0.5";
      $this->_title       = "UUID Generator";
      $this->_description = "UUID Generator for creating new module IDs";
      $this->_urn         = "uuid";
      $this->_classname   = "uuid";
      $this->_namespace   = "xenoPMT\\Module\\UUID";
      $this->_path        = "dirname(__FILE__)";
      $this->_mainfile    = "uuid.main.php";
      $this->_core        = false;
      $this->_uuid        = "c6fb97b8-af93-42ce-aac6-de5656c8fdae";

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
  } // end::class
} // end::namespace
?>
