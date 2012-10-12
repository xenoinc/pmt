<?php
/** * *********************************************************
 * Copyright 2012 (C) Xeno Innovations, Inc.
 * ALL RIGHTS RESERVED
 * @Author:      Damian Suess
 * Document:     install.ajax.php
 * Created Date: Oct 9, 2012
 *
 * Description:
 *  Event handlers from Ajax/Jquery calls
 *
 * Change Log:
 *
 */

require "../xpmt/phpConsole.php";
PhpConsole::start(true, true, dirname(__FILE__));

if(isset($_POST["updateStep"]))
{
  debug("Button Pressed");
  $ret = $_POST["updateStep"]+1;
  echo json_encode(array("returnValue" => "$ret"));
  // } else {
  // $ret = "99";
  // echo json_encode(array("returnValue" => "$ret"));
}

//if (isset($_POST["btnDbTestConn"]))
if (isset($_POST["DbTestConnection"]))
{
  debug("Testing db conn");

//  if ($_POST["btnDbTestConnection"] == "1")
//  {
    $ret = "Test-Fail";
    echo json_encode(array("dbTestRet" => "$ret"));
    /*
    echo json_encode(
      array(
        "dbTestRet" => "$ret",
        "dbTestRetClass" => "Fail"));
    */
//  }
}

?>
