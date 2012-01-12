<?php
/************************************************************
 * Copyright 2010 (C) Xeno Innovations, Inc.
 * ALL RIGHTS RESERVED
 *
 * Author:
 * Damian J. Suess
 *
 * Description:
 * Project entry point!  Simply perform the following:
 *
 *  1) Get project configuration settings
 *  2) Pull up Welcome Page info
 *
 * Change Log:
 * [2010-1029] - Initial Creation
*/

include_once "config.php";
include_once $pmt_core_path . "config.php";
//include_once $pmt_core . "index.php";       // include_once "../index.php";


function Print_Warning($message)
{
  print ("<b>" . $message . "</b>\n<br>");
}

// #########################################

if($pmt_core_path == "")
  Print_Warning("Core not Defined");


// Generate the Welcome Page
include_once $pmt_core_path . "/htdoc/layout.php";

?>