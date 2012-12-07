<?php

/** * *********************************************************
 * Copyright 2012 (C) Xeno Innovations, Inc.
 * ALL RIGHTS RESERVED
 * @Author:       Damian Suess
 * Document:      admin.setup.php
 * Created Date:  2012-12-04
 * Status:        Unstable  ::  {unstable/pre-alpha/alpha/beta/stable}
 * Description:
 *
 *
 * Change Log:
 *  2012-1206 * Created skeleton
 */

namespace xenoPMT\Module\Admin
{
  require_once "/../../core/xpmt.i.setup.php";
  class Setup implements \xenoPMT\Module\ISetup
  {
    public function __construct()
    {
      debug("Setup Admin");
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
