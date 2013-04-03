<?php

/* ***********************************************************
 * Copyright 2012 (C) Xeno Innovations, Inc.
 * ALL RIGHTS RESERVED
 * @Author:       Damian Suess
 * Document:      dashboard
 * Created Date:  Dec 3, 2012
 * Status:        Alpha {unstable/pre-alpha/alpha/beta/stable}
 * Description:
 *  Dashboard module installer / Uninstaller
 *
 * To Do:
 *  [ ] Keep pulling sample info from "sample.setup.php"
 *  [X] Add column in databsae (and ModHeader) for namespace ("xenoPMT\Module\Dashboard")
 *  [ ] Uninstaller is unverified
 *  [X] Sample execution of members inside a namespace
 *    //require_once "kb-main.php";
 *    //$k = new xenoPMT\Module\KB\Main;
 *    //$html .= $k->PageLayout(1);
 *
 * Unit Testing:
 *    require_once("C:/_work/xi/xenoPMT/sandbox/core-0.5/xpmt/modules/dashboard/dashboard.setup.php");
 *
 * Change Log:
 *  2013-0401 * installer: Enabled module by default.
 *            + Added '\' to '\\' converter for _path & _namespace
 *  2013-0131 * Fixed $_verifiedMessages[] in VerifyPreInstall(). it was returning false positives
 *  2012-1219 * Refactored code
 *  2012-1212 + added a bunch of crap.. i'll write it up later (verify, execute of Inst/Uninst).  (djs)
 *            * Changed any PHP core module classes to use "\" namespace convention for safty sake (nsmespace friendly)
 *            + Added, PHPUNIT_VerifyPreUninstall()
 *            + This WAS 100% finished but then i accidentally hit, Delete on this not the UnitTest! it's swiss cheese now.
 *  2012-1206 + Added interface which requires PreInstallErrors()
 *            - Removed 'static' from members due to ISetup interface. Class must now be instantiated.
 *  2012-1203 + started working on setup script
 */

namespace xenoPMT\Module\Dashboard
{
  require_once "/../../core/xpmt.i.setup.php";
  require_once "/../../core/xenoPMTSetup.php";
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

    private $_xenoPMT;

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
     * @param array $headerInfo         Header data from module's own $xpmtModule["info"][]
     * @param boolean $boolAutoEnable   TRUE="Enable on Install"  FALSE="Disabled on Install"
     */
    public function __construct($boolInstall = true, $headerInfo = "", $boolAutoEnable=false)
    {
      global $xpmtConf;
      // debug("Entering Dashboard Setup Constructor");

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

      /* Unit test first */
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
        // Verify our UUID, if it passes, Verify Module Un/install action
        if ($this->_uuid != $headerInfo["uuid"])
        {
          $this->_verified = false;
          $this->_verifiedMessages["UUID_Conflict"] = true;
        }
        else
        {
          // Yes this is dumb that we're requesting our own header to be passed in. But
          // its easier than re-cycling through the $xpmtModule array for now.
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

    /**
     * Property - Safly return verification property status
     * @return boolean
     */
    public function Verified()
    {
      //global $xpmtConf;
      return $this->_verified;
    } // end::Verified()

    /**
     * Property :: Safly return Verification Check-List property array
     * @return array
     */
    public function GetVerifiedMessages()
    {
      //global $xpmtConf;
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
      /* Steps
       * 1. Make sure Verify flag is OK
       * 2. Perform Database Inserts
       *    1. UUID, Core(TF), Enabled(false)
       * 3. Return boolean
       */
      global $xpmtConf;
      $bRet = false;    // Flag to say pass/fail

      if ($this->Verified() == TRUE)
        $bRet = $this->privInstall();            // pmtDebug("Installing Dashboard");

      return $bRet;                       // Use ONLY for Unit Testing
      // return $this->privInstall();     // Perform install process
    } // end::Install()

    /**
     * Uninstall Module
     * If unit testing then override the return statement
     *
     * @assert () == true
     */
    public function Uninstall()
    {
      global $xpmtConf;
      // return $this->privUninstall();   // Perform uninstall process
      return true;                        // Use ONLY for Unit Testing
    } // end::Unisntall()



    // ################################### //
    // ##[ Private Parts ]################ //
    // ################################### //

    /**
     * Verify if we can install or not
     * @return boolean      True=PASSED, False=FAIL
     */
    private function VerifyPreInstall()
    {
      /*
       * To Do
       * 1. [X] Match UUID
       * 2. [ ] Check for conflicting URN
       */

      global $xpmtConf;
      $bRet = false;            // Verify if we can install or not
      /*
      pmtDebug("Server: {$xpmtConf["db"]["server"]}");
      pmtDebug("User: {$xpmtConf["db"]["user"]}");
      pmtDebug("Pass: {$xpmtConf["db"]["pass"]}");
      pmtDebug("dbname: {$xpmtConf["db"]["dbname"]}");
      */

      //{$xpmtConf["db"]["prefix"]}CORE_MODULE
      //select * from xi_core_module where Module_UUID = 'df9f29f8-1aed-421d-b01c-860c6b89fb14';
      $db = new \mysqli($xpmtConf["db"]["server"],  $xpmtConf["db"]["user"],
                        $xpmtConf["db"]["pass"],    $xpmtConf["db"]["dbname"]);
      if(!$db->connect_errno)
      {
        $sql = "select * from {$xpmtConf["db"]["prefix"]}CORE_MODULE where `Module_UUID` = '{$this->_uuid}';";
        //if($db->query($sql)) { }

        $exec = $db->prepare($sql);
        if ($exec)
        {
          $exec->execute();           // Execute the query
          $exec->store_result();      // Store the result so we can get a row count
          $this->_verifiedMessages["DbConnect_Failed"]  = false;
          $this->_verifiedMessages["DbQuery_Failed"]    = false;
          if($exec->num_rows)
          {
            $bRet = false; //1;
            //pmtDebug("dashboard.detup.VerifyPreInstall() UUID Rows: " . $exec->num_rows);
            $this->_verifiedMessages["UUID_Conflict"] = true;
          }
          else
          {
            /*
             * Successful query. Now check the data for pre-existing UUID
             */
            $bRet = true; // 2;
            $this->_verifiedMessages["UUID_Conflict"] = false;
          }
        }
        else
        {
          $bRet = false; // 3;
          $this->_verifiedMessages["DbQuery_Failed"] = true;
        }

        $db->close();
      }
      else
      {
        // There was an error connecting to the database
        $this->_verifiedMessages["DbConnect_Failed"] = true;
        $bRet = false; // 4; //false;
        // debug("FAIL FAIL FAIL");
      }


      // Step 2 - Check if URN is previously used in both DB and ConfigHeaders
      // To Do later

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
        return false;

      global $xpmtConf;
      $bRet = false;

      if ($this->_core) $bCore = "TRUE"; else $bCore = "FALSE";

      //$tmpArr = $pmtDB->Query( $_sql);
      $db = new \mysqli( $xpmtConf["db"]["server"], $xpmtConf["db"]["user"],
                            $xpmtConf["db"]["pass"], $xpmtConf["db"]["dbname"]);

      if(!$db->connect_errno)
      {
        pmtDebug("Dashboard.Setup() privInstall() ModuleName: " . $this->_path);
        pmtDebug("Dashboard.Setup() privInstall() ModuleNamespace: " . $this->_namespace);

        // Added 2013-0401
        // Clean up '\' on Windows OS Servers.
        // If (find($_path, "\\" == false && find($_path, "\" == true) {Replace($_path, "\", "\\"); }
        // If (find($_namespace, "\\" == false && find($_namespace, "\" == true) {Replace($_namespace, "\", "\\"); }
        // str_replace($search, $replace, $subject);
        $bkSlash = "\\";
        $bkSlashDouble = "\\\\";
        // Use the '===' just incase "\\" is the first char. pos=0 confuses with FALSE
        pmtDebug("@@ _path SNGL " . strpos($this->_path, $bkSlash));
        pmtDebug("@@ _path DBL " . strpos($this->_path, $bkSlashDouble));

        if (strpos($this->_path, $bkSlashDouble) === false &&
            strpos($this->_path, $bkSlash) > 0)
        {
          pmtDebug("## Repl '\' _path");
          $this->_path = str_replace($bkSlash, $bkSlashDouble, $this->_path);
        }

        if (strpos($this->_namespace, $bkSlashDouble) === false &&
            strpos($this->_namespace, $bkSlash) > 0)
        {
          pmtDebug("## Repl '\' _NS");
          $this->_namespace = str_replace($bkSlash, $bkSlashDouble, $this->_namespace);
        }
        else {          pmtDebug("## NO REPL!!"); }
        // End update

        // UPDATE `xi_core_module` SET `Module_Path`='C:\\prog\\Apache2\\htdocs\\pmt2\\xpmt\\modules\\dashboard' WHERE  `Module_Id`=1 LIMIT 1;
        // NOTE: This is the only plugin that is ENABLED by default!!
        $sql = <<<"sql"
        INSERT INTO {$xpmtConf["db"]["prefix"]}CORE_MODULE
        ( `Module_UUID`, `Core`, `Enabled`,
          `Module_Name`, `Module_Version`, `Module_Path`,
          `Module_Namespace`,
          `Module_Class`,
          `Module_URN`,
          `Description`
        ) VALUES (
        '{$this->_uuid}', $bCore, TRUE,
        '{$this->_title}', '{$this->_version}', '{$this->_path}',
        '{$this->_namespace}',
        '{$this->_classname}',
        '{$this->_urn}',
        '{$this->_description}');
sql;
        if($db->query($sql))
          $bRet = true;
        else
        {
          $bRet = false;
          $this->_verifiedMessages["DbQuery_Failed"] = true;
        }
        $db->close();
      }
      else
      { // connection error
        $this->_verifiedMessages["DbConnect_Failed"] = true;
        $bRet = false;
      }

      // Return the status pass/fail - true/false
      return $bRet;
    } // end::privInstall()

    /**
     * Actual Uninstaller
     *
     * @global array $xpmtConf
     * @return boolean      True=SUCCESS, False=FAIL
     */
    private function privUninstall()
    {

      if ($this->_verified == false)
        return false;

      global $xpmtConf;

      $db = new \mysqli($xpmtConf["db"]["server"], $xpmtConf["db"]["user"],
                        $xpmtConf["db"]["pass"], $xpmtConf["db"]["dbname"]);

      if(!$db->connect_errno)
      {
        $sql = "delete from {$xpmtConf["db"]["prefix"]}CORE_MODULE where `Module_UUID` = '{$this->_uuid}';";
        if ($db->query($sql))
        {
          // Passed!
          $bRet = true;
        }
        else
        {
          // Failed!
          $this->_verifiedMessages["DbQuery_Failed"] = true;
          $bRet = true;
        }

        $db->close();

      }
      else
      {
        $this->_verifiedMessages["DbConnect_Failed"] = true;
        $bRet = false;
      }

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
      //return true;
      return $this->VerifyPreInstall();
    } // end::PHPUNIT_VerifyPreInstall

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
      return true;
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
      $this->_description = "Welcome screen and main page for all users whether they are logged in or not.";
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
