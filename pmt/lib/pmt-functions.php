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
 *
 * Change Log:
 *
 */


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

function GetSetting($setting)
{
  global $CACHE;
  global $pmtDB;

  // sent back what has been prevoiusly saved
  if (isset($CACHE['setting'][$setting]))
    return $CACHE['setting'][$setting];

  $tmpArr = $pmtDB->Query( "SELECT Setting, Value FROM " . PMT_TBL .
                        " WHERE Setting='" . $pmtDB->Res($setting) . "' LIMIT 1;");
  $ret = $pmtDB->FetchArray($tmpArr);

  // Save into cache now
  $CACHE["setting"][$setting] = $ret['value'];

  return $ret['value'];

}


?>
