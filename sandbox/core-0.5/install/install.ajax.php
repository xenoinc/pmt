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
  //debug("UpdateStep()");

  $ret = $_POST["UpdateStep"];
  //debug("Go To: " . $ret);

  echo json_encode(array("returnValue" => "$ret"));
  // } else {
  // $ret = "99";
  // echo json_encode(array("returnValue" => "$ret"));
}

/**
 * DatabaseTest via Ajax
 *
 * Takes in POST arguments:
 *  db_host - Server Host Name
 *  db_name - Database Name
 *  db_pref - Table Prefix (not needed)
 *  db_user - User Name
 *  db_pass - Password
 */
function ajaxDatabaseTest()
{
  // 0) Setup return vars
  $retMsg   = "blank info";   //
  $retClass = "Fail";         // pre-populate

  // 1) Extract variables (safely pull from POST)
  if (isset($_POST["db_host"])) $dbHost = $_POST['db_host']; else $dbHost = "";
  if (isset($_POST["db_name"])) $dbName = $_POST['db_name']; else $dbName = "";
  if (isset($_POST["db_pref"])) $dbPref = $_POST['db_pref']; else $dbPref = "";
  if (isset($_POST["db_user"])) $dbUser = $_POST['db_user']; else $dbUser = "";
  if (isset($_POST["db_pass"])) $dbPass = $_POST['db_pass']; else $dbPass = "";


  // Stepp 2) Test Database HOST connection (localhost)
  try
  {
    $mysqli = new mysqli($dbHost, $dbUser, $dbPass, $dbName);
    if ($mysqli->connect_errno)
    {
      //$retMsg = "<p>Connection Failed</p><p>[". $mysqli->connect_errno ."]: ". $mysqli->connect_error ."</p>";
      $retMsg = "Connection Failed<br />[".
                $mysqli->connect_errno ."]: ". $mysqli->connect_error;
      $retClass = "Fail";
    }
    else
    {

    }

    // Old code
    /*
    $con = mysql_connect($dbHost, $dbUser, $dbPass);
    if (!$con)
    {
      $retMsg = "MySQL Failed: " . mysql_error();
      $retClass = "Fail";
    } else {

      // 2.1) Test connection to Database (PMT_DATA)
      $retMsg = "Connection Success!";
      $retClass = "Success";      mysql_close($con);
    }
    */
  }
  catch(Exception $e)
  {
    $retMsg = "MySQL Exception: " . $e->getMessage();
    $retClass = "Fail";
  }


  $retArr = array("dbRet_msg"   => $retMsg,
                  "dbRet_class" => $retClass);
  //debug(json_encode($retArr));
  echo json_encode($retArr);


}

?>
