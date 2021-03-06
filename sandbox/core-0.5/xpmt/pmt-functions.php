<?php

/* * **********************************************************
 * Copyright 2012 (C) Xeno Innovations, Inc.
 * ALL RIGHTS RESERVED
 * Author:       suessdam
 * Document:     pmt-functions
 * Created Date: Mar 9, 2012
 *
 * Description:
 *  General system functions
 *
 * TO DO:
 *  [ ] Locale - Add functionaly for arrays
 *  [ ] Locale - Add SystemHook "function_locale"
 *  [ ] GetUserSetting - Get user & setting
 * 
 * Change Log:
 *  2012-0712 * Changed db call from the now private $pmtDB->Res(..) to the proper FixString method.
 */


/**
 * GetSetting
 * Get system setting
 * @global array $CACHE Session cached values
 * @global string $pmtDB Database class
 * @param string $setting Setting name
 * @return string Setting Value
 */
function GetSetting($setting)
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

  $tmpArr = $pmtDB->Query("SELECT Setting, Value FROM " . PMT_TBL . "SETTINGS" .
                          " WHERE Setting='" . $pmtDB->FixString($setting) . "' LIMIT 1;");
  $ret = $pmtDB->FetchArray($tmpArr);

  // Save into cache now
  $CACHE["setting"][$setting] = $ret['value'];

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
function GetUserSetting($setting)
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

  $tmpArr = $pmtDB->Query("SELECT Setting, Value FROM " . PMT_TBL . "SETTINGS" .
                          " WHERE Setting='" . $pmtDB->FixString($setting) . "' LIMIT 1;");
  $ret = $pmtDB->FetchArray($tmpArr);

  // Save into cache now
  $CACHE["setting"][$setting] = $ret['value'];

  return $ret['value'];
  */
  return "";
}


/**
 * Locale Lookup
 * Looks up the string set for the systems set language
 * @global string $lang System language type
 * @param string $param Setting Name/Key
 * @param mixed $args
 * @return string Language string lookup
 */
function Locale($param, $args=array())
{
  global $lang;

  if(!isset($lang[$param]))
    return "[" . $param . "]";

  $param = $lang[$param];

  // TODO: check if $args is an array to use the function arguments

  return $param;
}

function pmtDebug($buff)
{
  if (defined("DebugMode") && DebugMode == true)
    debug($buff);
}


?>
