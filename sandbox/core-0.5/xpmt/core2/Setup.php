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
  require_once "Functions.php";
  class Setup extends \xenoPMT\Core\Functions
  {
    public static function RegisterModule($modName, $modClass, $modNamespace)
    {
      // let's just hope this works here
      global $xpmtConf;

      pmtDebug("REG 1: {$xpmtConf['db']['server']}; 2:{$xpmtConf['db']['user']};" .
               "3: {$xpmtConf['db']['pass']}; 4: {$xpmtConf['db']['dbname']}");

    }

    /**
     * Verifies if there is an URI conflict witht he requesting module
     */
    public static function CheckURIConflict()
    {
      global $xpmtConf;

      pmtDebug("CHK 1: {$xpmtConf['db']['server']}; 2:{$xpmtConf['db']['user']};" .
               "3: {$xpmtConf['db']['pass']}; 4: {$xpmtConf['db']['dbname']}");

      //{$xpmtConf["db"]["prefix"]}CORE_MODULE
      //select * from xi_core_module where Module_UUID = 'df9f29f8-1aed-421d-b01c-860c6b89fb14';
      /*
      $db = new \mysqli($xpmtConf["db"]["server"],  $xpmtConf["db"]["user"],
                        $xpmtConf["db"]["pass"],    $xpmtConf["db"]["dbname"]);
      if(!$db->connect_errno)
      {
        $sql = "select * from {$xpmtConf["db"]["prefix"]}CORE_MODULE where `Module_UUID` = '{$this->_uuid}';";
      }

      */
    }
  }
}
?>
