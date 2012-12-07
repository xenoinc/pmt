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
    private $_namespace;
    private $_classname;
    private $_path;
    private $_core;

    /**
     * Module has be verified and there are not duplicates in database
     * ? Handled in Construct() or PreInstallErrors()?
     * @var boolean
     */
    private $_verified = false;


    // ######################################################### //

    public function __construct($headerInfo)
    {
      //debug("Setup Dashboard");
      /*
      global $xpmtCore;
      $mod = $xenoPMT::$GetModuleHeaderFromUUID();
      //$mod = $this->GetModuleHeaderFromUUID($this->_uuid);
      if ($mod != NULL)
      {
        $this->_verified = true;
      }
      else
      {
        $this->_verified = false;
      }
      */
    }


    public function PreInstallErrors(&$arrChkPoints)
    {
      return false;
    }


    /**
     * Install Module
     * ** was "static"
     *
     * @return boolean  True = Successful installation
     *                  False = Failed to install module
     */
    public function Install()
    //public function Install()
    {
      global $xpmtConf; // $pmtDB;

      if ($this->_core) $bCore = "TRUE"; else $bCore = "FALSE";

      $sql = <<<"sql"
      INSERT INTO {$xpmtConf["db"]["prefix"]}CORE_MODULE
      ( `Module_UUID`, `Core`, `Enabled`,
        `Module_Name`, `Module_Version`, `Module_Path`,
        `Module_Namespace`, `Module_Class`, `Module_URN`, `Description`
      ) VALUES (
      '{$this->_uuid}', $boolCore, FALSE,
      '{$this->_title}', '{$this->_version}', '{$this->_path}',
      '{$this->_namespace}', {$this->_classname}', '{$this->_urn}', '{}');
sql;

      //$tmpArr = $pmtDB->Query( $_sql);
      $db = new mysqli( $xpmtConf["db"]["server"], $xpmtConf["db"]["user"],
                            $xpmtConf["db"]["pass"], $xpmtConf["db"]["dbname"]);
      if(!$db->connect_errno)
      {
        $mysqli->close();
        return true;
      }
      else
      { // connection error
        return false;
      }


    }

    /**
     * Uninstall Module
     * ** was "static"
     */
    public function Uninstall()
    //public function Uninstall()
    {
      global $xpmtConf;

      // We cannot uninstall the dashboard!!
      // Well, not unless if there is a solid replacement

      return false;
    }


    // ################################### //
    // ##[ Private Parts ]################ //
    // ################################### //



  }
}
?>
