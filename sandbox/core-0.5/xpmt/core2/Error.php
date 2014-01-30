<?php

/** * *********************************************************
 * Copyright 2013 (C) Xeno Innovations, Inc.
 * ALL RIGHTS RESERVED
 * @Author:       Damian Suess
 * Document:      Error
 * Created Date:  Jan 29, 2014
 * Status:        {unstable/pre-alpha/alpha/beta/stable}
 * Introduced:    v0.0.5
 * Description:
 *  Return back error messages and descriptions
 *
 * Change Log:
 *
 */

namespace xenoPMT\Core
{
  class Error
  {

    /* Example:
     *  Including code:
     *    require_once PMT_PATH."xpmt/core2/Error.php";
     *    class CLSNAME { ... }
     *
     *  Usage:
     *    $errNum = \xenoPMT\Core\Error::ERROR_TYPE_NONE;
     *    $msg = \xenoPMT\Core\Error::GetMessage($errNum);
     */

    //<editor-fold defaultstate="collapsed" desc="Constant Values">

    // Generic
    const ERROR_TYPE_NONE                   = 0x0;
    const ERROR_TYPE_UNKNOWN                = 0x1;

    // Module errors
    const ERROR_TYPE_MODULE_BAD_UUID          = 0x10;
    const ERROR_TYPE_MODULE_ACCESS_DENIED     = 0x11;
    const ERROR_TYPE_MODULE_DISABLED          = 0x12;
    const ERROR_TYPE_MODULE_INVALID_NAMESPACE = 0x13;

    // Database errors

    // User errors

    // Page errors

    // Theme errors

    //</editor-fold>

    //<editor-fold defaultstate="collapsed" desc="Methods">

    /**
     * Return back the error message description
     *
     * @param type $constValue Error message value
     * @return string Error message
     */
    public function GetMessage($constValue)
    {
      /*
       * ToDo:
       *  [ ] Make use of multilanguage
       */

      $msg = "";

      switch ($constValue)
      {
        case SELF::ERROR_TYPE_NONE:
          $msg = "";
          break;

        case SELF::ERROR_TYPE_MODULE_BAD_UUID:
          $msg = "Module ID not found.";
          break;
        case SELF::ERROR_TYPE_MODULE_ACCESS_DENIED:
          $msg = "User does not have permissions.";
          break;
        case SELF::ERROR_TYPE_MODULE_DISABLED:
          $msg = "Module not currently enabled.";
          break;
        case SELF::ERROR_TYPE_MODULE_INVALID_NAMESPACE:
          $msg = "Module's namespace could not be found.";
          break;

        case SELF::ERROR_TYPE_UNKNOWN:
        default:
          $msg = "An unknown error has occurred";
          break;

      }
      return $msg;
    }

    //</editor-fold>

  }
}
