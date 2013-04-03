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
  class Setup extends \xenoPMT
  {
    public static function RegisterModule($modName, $modClass, $modNamespace)
    {

    }

    /**
     * Verifies if there is an URI conflict witht he requesting module
     */
    public static function CheckURIConflict()
    {

    }
  }
}
?>
