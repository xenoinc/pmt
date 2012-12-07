<?php

/** * *********************************************************
 * Copyright 2012 (C) Xeno Innovations, Inc.
 * ALL RIGHTS RESERVED
 * @Author:       Damian Suess
 * Document:      uuid.setup.php
 * Created Date:  Oct 29, 2012
 * Status:        {unstable/pre-alpha/alpha/beta/stable}
 * Description:
 *  Installer members
 *
 * Change Log:
 *  2012-1206 * Created skeleton
 */
namespace xenoPMT\Module\UUID
{
  require_once "/../../core/xpmt.i.setup.php";
  class Setup implements \xenoPMT\Module\ISetup
  {
    public function __construct()
    {
      debug("Setup UUID");
    }

    public function PreInstallErrors(&$arrChkPoints)
    {
      return false;
    }

    public function Install()
    {
      global $xpmtConf;
    }

    public function Uninstall()
    {
      global $xpmtConf;
    }
  }
}
?>
