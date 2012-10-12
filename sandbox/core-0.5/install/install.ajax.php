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


if(isset($_POST["UpdateStep"]))                         ajaxUpdateStep();
if(isset($_POST["step3"]) && $_POST["step3"]=="1")      ajaxDatabaseTest();


/* ############################################ */

function ajaxUpdateStep()
{
  // debug("UpdateStep()");
  $ret = $_POST["UpdateStep"]+1;
  echo json_encode(array("returnValue" => "$ret"));
  // } else {
  // $ret = "99";
  // echo json_encode(array("returnValue" => "$ret"));
}

function ajaxDatabaseTest()
{
  debug("Testing db conn");
  debug("Host: " . $_POST['db_host']);

  $ret_msg   = "null";
  $ret_class = "Fail";

  try
  {
    $con = mysql_connect("localhost", "user", "pass");
    if (!$con)
    {
      $ret_msg = "MySQL Failed: " . mysql_error();
      $ret_class = "Fail";
    }
    else
    {
      $ret_msg = "Test Success!";
      $ret_class = "Success";      mysql_close($con);
    }
  }
  catch(Exception $e)
  {
    $ret_msg = "MySQL Exception: " . $e->getMessage();
    $ret_class = "Fail";
  }

  echo json_encode(array("dbTestRet" => "$ret_msg"));
  /*
  echo json_encode(
    array(
      "dbTestRet"       => "$ret_msg",
      "dbTestRetClass"  => "$ret_class"));
  }
  */


}

?>
