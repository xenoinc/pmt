<?php

/* * **********************************************************
 * Copyright 2013 (C) Xeno Innovations, Inc.
 * ALL RIGHTS RESERVED
 * @Author:       Damian Suess
 * Document:      xenoPMTSetup.php
 * Created Date:  Apr 2, 2013
 * Status:        {unstable/pre-alpha/alpha/beta/stable}
 * Description:
 *  This static class handles the basic business end of Un/Registering
 *  modules with the xenoPMT Core Database
 *
 * ToDo:
 *  [ ] finish "CheckURIConflict() / VerifyURIConflict()"
 *  [ ] finish RegisterModule()
 * Change Log:
 *
 */

/**
 * Description of xenoPMTSetup
 *
 * @author fuct
 */
namespace xenoPMT\Core
{
  require_once "Functions.php";   // xenoPMT Core Functionality
  require_once "misc/Struct.php"; // Structure class
  require_once "misc/ModuleProperties.php";  // Module Info Properties Class

  // Extending is probably NOT needed here (yet)
  class Setup extends \xenoPMT\Core\Functions
  {

    /**
     * Creates the default Structure Class for Module Error Return
     * @return object ModSetup Error Struct Class
     */
    public static function CreateStructModSetupError()
    {
      $objStruct = \xenoPMT\Core\Misc\Struct::Initialize(
          "CoreInvalid",
          "IsInstalled",
          "URN_Conflict",
          "UUID_Conflict",
          "DbConnect_Failed",
          "DbQuery_Failed"
        );
      $objErr= $objStruct->Create(false, false, false, false, false, false);

      return $objErr;
    }

    /**
     *
     * @return object Module Info Struct Class
     */
    public static function CreateStructModInfo()
    {
      // TODO:
      //  ? Should we return just the Stuct and Create values later?
      //  * Really we should use a Class not a pseudo struct class
      /*
      $objStructModInfo = \xenoPMT\Core\Misc\Struct::Initialize(
          "Module_UUID",      "IsCore",         "IsEnabled",
          "Module_Name",      "Module_Version", "Module_Path",
          "Module_Namespace", "Module_Class",   "Module_URN",
          "Description");
      /*
       $objModInfo = $objStructModInfo->Create(
          $this->_uuid,      $this->_core,      "TRUE",
          $this->_title,     $this->_version,   $this->_path,
          $this->_namespace, $this->_classname, $this->_urn,
          $this->_description);
      */
      return false;   // $objStructModInfo;
    }


    // Public static members
    public static function RegisterModule($objModInfo, &$objErr)
    {
      pmtDebug("xenoPMT\Core\Setup.RegisterModule Entering");
      // let's just hope this works here
      global $xpmtConf;   // used for DB values
      $retStatus = true;  // Pass/Fail Registeration

      // Create structure
      $objStruct = \xenoPMT\Core\Misc\Struct::Initialize(
          "CoreInvalid",
          "IsInstalled",
          "URN_Conflict",
          "UUID_Conflict",
          "DbConnect_Failed",
          "DbQuery_Failed"
        );
      $objErr= $objStruct->Create(false, false, false, false, false, false);

      // put structured class properties into this class' private parts
      //self::structToVar($objModInfo);
      $_uuid        = $objModInfo->Module_UUID;
      $_core        = $objModInfo->IsCore;            // undefiend??
      $_enabled     = $objModInfo->IsEnabled;         // "TRUE" / "FALSE"
      $_title       = $objModInfo->Module_Name;
      $_version     = $objModInfo->Module_Version;
      $_path        = $objModInfo->Module_Path;
      $_namespace   = $objModInfo->Module_Namespace;
      $_classname   = $objModInfo->Module_Class;
      $_urn         = $objModInfo->Module_URN;
      $_description = $objModInfo->Description;


      if ($_core) $bCore = "TRUE"; else $bCore = "FALSE";

      //return true;

      //pmtDebug("REG 1: {$xpmtConf['db']['server']}; 2:{$xpmtConf['db']['user']};" .
      //         "3: {$xpmtConf['db']['pass']}; 4: {$xpmtConf['db']['dbname']}");

      //$tmpArr = $pmtDB->Query( $_sql);
      $db = new \mysqli( $xpmtConf["db"]["server"], $xpmtConf["db"]["user"],
                         $xpmtConf["db"]["pass"], $xpmtConf["db"]["dbname"]);

      if(!$db->connect_errno)
      {
        //pmtDebug("Setup.RegMod() privInstall() ModuleName: " . $_path);
        //pmtDebug("Setup.RegMod() privInstall() ModuleNamespace: " . $_namespace);

        // Added 2013-0401
        // Clean up '\' on Windows OS Servers.
        // If (find($_path, "\\" == false && find($_path, "\" == true) {Replace($_path, "\", "\\"); }
        // If (find($_namespace, "\\" == false && find($_namespace, "\" == true) {Replace($_namespace, "\", "\\"); }
        // str_replace($search, $replace, $subject);
        $bkSlash = "\\";
        $bkSlashDouble = "\\\\";
        // Use the '===' just incase "\\" is the first char. pos=0 confuses with FALSE
        //pmtDebug("Setup.RegMod() @@ _path SNGL " . strpos($_path, $bkSlash));
        //pmtDebug("Setup.RegMod() @@ _path DBL " . strpos($_path, $bkSlashDouble));

        /*
         * Note: This will not replace anything on a Windows machine if path
         *  is, "\\server\folder". So don't do it. "c:\path" only works
         */
        if (strpos($_path, $bkSlashDouble) === false &&
            strpos($_path, $bkSlash) > 0)
        {
          //pmtDebug("Setup.RegMod() ## Replaced '\' _path");
          $_path = str_replace($bkSlash, $bkSlashDouble, $_path);
        }

        if (strpos($_namespace, $bkSlashDouble) === false &&
            strpos($_namespace, $bkSlash) > 0)
        {
          //pmtDebug("Setup.RegMod() ## Replaced '\' _NS");
          $_namespace = str_replace($bkSlash, $bkSlashDouble, $_namespace);
        }
        // End PATH & Namespace "\" to "\\" fix

        if ($_core) $bCore = "TRUE"; else $bCore = "FALSE";


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
        '{$_uuid}', {$bCore}, {$_enabled},
        '{$_title}', '{$_version}', '{$_path}',
        '{$_namespace}',
        '{$_classname}',
        '{$_urn}',
        '{$_description}');
sql;
        if($db->query($sql))
          $bRet = true;
        else
        {
          $bRet = false;
          $_verifiedMessages["DbQuery_Failed"] = true;
        }
        $db->close();
      }
      else
      { // connection error
        $_verifiedMessages["DbConnect_Failed"] = true;
        $bRet = false;
      }
      pmtDebug("xenoPMT\Core\Setup.RegisterModule Exiting::{$retStatus}");
      return $retStatus;
    }

    /**
     * Verifies if there is are URN or UUID conflicts with the requesting module
     * @param $objModInfo \xenoPMT\Core\Misc\ModuleProperties
     * @param $objStruct  Error return
     * @return boolean    Overall Pass/Fail of the verification
     */
    public static function CheckConflict($objModInfo, &$objStruct)
    {
      global $xpmtConf;
      pmtDebug("CHK 1: {$xpmtConf['db']['server']}; 2:{$xpmtConf['db']['user']};" .
               "3: {$xpmtConf['db']['pass']}; 4: {$xpmtConf['db']['dbname']}");
      $bRet = false;            // Verify if we can install or not

      /**

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
            // Successful query. Now check the data for pre-existing UUID
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
    */
    }
  }
}
?>
