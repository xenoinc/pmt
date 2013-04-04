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

  // Extending is probably NOT needed here (yet)
  class Setup extends \xenoPMT\Core\Functions
  {
    public static function RegisterModule($modName, $modClass, $modNamespace)
    {
      // let's just hope this works here
      global $xpmtConf;
      $objStruct = Struct::factory(
          "CoreInvalid",
          "IsInstalled",
          "URN_Conflict",
          "UUID_Conflict",
          "DbConnect_Failed",
          "DbQuery_Failed"
        );
      $regStatus = $ojbStruct->create(false, false, false, false, false, false);
      $regStatus->CoreInvalid = true;
      pmtDebug("REG 1: {$xpmtConf['db']['server']}; 2:{$xpmtConf['db']['user']};" .
               "3: {$xpmtConf['db']['pass']}; 4: {$xpmtConf['db']['dbname']}"
               );
      
               // o
      return false;
    }

    /**
     * Verifies if there is an URI conflict witht he requesting module
     */
    public static function CheckURIConflict()
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
