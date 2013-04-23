<?php
/* * **********************************************************
 * Copyright 2013 (C) Xeno Innovations, Inc.
 * ALL RIGHTS RESERVED
 * @Author:       Damian Suess
 * Document:      xenoPMTCore.php
 * Created Date:  Apr 3, 2013
 * Status:        {unstable/pre-alpha/alpha/beta/stable}
 * Description:
 *  Contains only basic functions that is to be shared by the other classes
 *  which extend from this (core) one.
 *
 * ToDo:
 *  [ ] Refactor moded code from "pmt-function.php" to work here
 *
 * Change Log:
 *
 */

namespace xenoPMT\Core
{
  class Functions
  {
    /**
     * GetSetting
     * Get system setting and save it into global variable $CACHE["setting"][$setting]
     * so that we don't have to call the database everytime we want the same setting.
     *
     * @global array $CACHE Session cached values
     * @global string $pmtDB Database class
     * @param string $setting Setting name
     * @return string Setting Value
     */
    static function GetSetting($setting)
    {
      /** Setting     Value
       *  theme       <theme-name>
       *  locale      <lang-code>   i.e. "en, en-gb, de, ..."
       *
       */

      global $CACHE;
      global $pmtDB;

      // sent back what has been prevoiusly saved
      if (isset($CACHE['setting'][$setting]))
        return $CACHE['setting'][$setting];

      $tmpArr = $pmtDB->Query("SELECT Setting, Value FROM " . PMT_TBL . "CORE_SETTINGS" .
                              " WHERE Setting='" . $pmtDB->FixString($setting) . "' LIMIT 1;");
      $ret = $pmtDB->FetchArray($tmpArr);

      // Save into cache now
      $CACHE["setting"][$setting] = $ret['value'];
      return $ret['value'];
    }

    /**
     * Get Module Setting
     *  ** in beta **
     * @global array $CACHE
     * @global string $pmtDB
     * @param type $setting
     * @return array
     */
    static function GetModuleSetting($uuid, $setting)
    {
      global $CACHE;
      global $pmtDB;

      // sent back what has been prevoiusly saved
      if (isset($CACHE['mod_setting'][$uuid][$setting]))
        return $CACHE['mod_setting'][$uuid][$setting];

      $tmpArr = $pmtDB->Query("SELECT Setting, Value FROM " . PMT_TBL . "CORE_MODULE_CONFIG" .
                              " WHERE UUID='{$pmtDB->FixString($uuid)}' Setting='" . $pmtDB->FixString($setting) . "' LIMIT 1;");
      $ret = $pmtDB->FetchArray($tmpArr);

      // Save into cache now
      $CACHE["mod_setting"][$uuid][$setting] = $ret['value'];
      return $ret['value'];
    }

    /**
     * GetUserSetting (NOT IMPLEMENTED)
     * Get user's custom system setting
     * @global array $CACHE Session cached values
     * @global string $pmtDB Database class
     * @param string $setting Setting name
     * @return string Setting Value
     */
    static function GetUserSetting($setting)
    {
      /** Setting     Value
       *  theme       <theme-name>
       *  locale      <lang-code>   i.e. "en, en-gb, de, ..."
       *
       */
      /*
      global $CACHE;
      global $pmtDB;

      // sent back what has been prevoiusly saved
      if (isset($CACHE['setting'][$setting]))
        return $CACHE['setting'][$setting];

      $tmpArr = $pmtDB->Query("SELECT Setting, Value FROM " . PMT_TBL . "CORE_SETTINGS" .
                              " WHERE Setting='" . $pmtDB->FixString($setting) . "' LIMIT 1;");
      $ret = $pmtDB->FetchArray($tmpArr);

      // Save into cache now
      $CACHE["setting"][$setting] = $ret['value'];

      return $ret['value'];
      */
      return "";
    }
  }
}
?>
