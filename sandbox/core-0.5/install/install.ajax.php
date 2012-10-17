<?php
/** * *********************************************************
 * Copyright 2012 (C) Xeno Innovations, Inc.
 * ALL RIGHTS RESERVED
 * @Author:      Damian Suess
 * Document:     install.ajax.php
 * Created Date: Oct 9, 2012
 *
 * Description:
 *  Event handlers from Ajax/jQuery calls
 *
 * Change Log:
 *
 */

require "../xpmt/phpConsole.php";
PhpConsole::start(true, true, dirname(__FILE__));

// setup variables
$_dbHost = ""; $_dbName = ""; $_dbPrfx = ""; $_dbUser = ""; $_dbPass = "";

// Pull parameters by default
GetDbParams();


// Get where we are suppose to go
if(isset($_POST["ClearDB"]) && $_POST["ClearDB"]=="1")  ajaxClearDB();
if(isset($_POST["UpdateStep"]))                         ajaxUpdateStep();
if(isset($_POST["step3"]) && $_POST["step3"]=="1")      ajaxDatabaseTest();
if(isset($_POST["step4"]) && $_POST["step4"]=="1")      ajaxInstallXenoPMT();


/* ##[ Misc Functions ]########################################## */
function pmtDebug($buff)
{
  global $BETA_TESTING;
  //if (defined("DebugMode") && DebugMode == true)
  if ($BETA_TESTING)
    debug($buff);
}


function GetDbParams()
{
  global $_dbHost, $_dbName, $_dbPrfx, $_dbUser, $_dbPass;
  if (isset($_POST["db_host"])) $_dbHost = $_POST['db_host']; else $_dbHost = "";
  if (isset($_POST["db_name"])) $_dbName = $_POST['db_name']; else $_dbName = "";
  if (isset($_POST["db_pref"])) $_dbPrfx = $_POST['db_pref']; else $_dbPrfx = "";
  if (isset($_POST["db_user"])) $_dbUser = $_POST['db_user']; else $_dbUser = "";
  if (isset($_POST["db_pass"])) $_dbPass = $_POST['db_pass']; else $_dbPass = "";

}
/* ##[ Ajax Procedures ]########################################## */

/**
 * Remove database tables and prep for recreation
 */
function ajaxClearDB()
{
  // 1) Extract variables (safely pull from POST)
  global $_dbHost, $_dbName, $_dbPrfx, $_dbUser, $_dbPass;
  //GetDbParams();
  require_once("../xpmt/core/pmt.db.php");


  // 2) Connect to db
  $pmtDB = new Database($_dbHost, $_dbUser, $_dbPass);
  // 3) Drop all tables
  $pmtDB->Query("DROP DATABASE ".$_dbName.";");
  $pmtDB->Query("CREATE DATABASE ".$_dbName.";");
  //$pmtDB->Close();                                // throws an error

  // 4) Report status
  $retArr = array("dbRet_msg"   => "Dropped and created database, '".$_dbName."'.",
                  "dbRet_class" => "Success");
  echo json_encode($retArr);
}


/**
 * Useless function, just use the JQuery shit.
 * This was setup as an Ajax test.
 */
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
  global $_dbHost, $_dbName, $_dbPrfx, $_dbUser, $_dbPass;
  //GetDbParams();

  // Stepp 2) Test Database HOST connection (localhost)
  try
  {
    $mysqli = new mysqli($_dbHost, $_dbUser, $_dbPass, $_dbName);
    if ($mysqli->connect_errno)
    {
      //$retMsg = "<p>Connection Failed</p><p>[". $mysqli->connect_errno ."]: ". $mysqli->connect_error ."</p>";
      $retMsg = "Connection Failed<br />[".
                $mysqli->connect_errno ."]: ". $mysqli->connect_error;
      $retClass = "Fail";
    }
    else
    {
      $mysqli->close(); // make sure it closes successfuly

      $retMsg   = "Connection Successful!";
      $retClass = "Success";
    }

    // Old code
    /*
    $con = mysql_connect($_dbHost, $_dbUser, $_dbPass);
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

/**
 * Create database tables
 */
function ajaxInstallXenoPMT()
{
  debug("Entering :: ajaxInstallXenoPMT");

  // Step 1) Setup variables
  $retMsg   = "blank info";   //
  $retClass = "Fail";         // pre-populate
  $arrMsg = array();
  global $_dbHost, $_dbName, $_dbPrfx, $_dbUser, $_dbPass;
  //GetDbParams();

  // Step 2) Connect to database
  try
  {
    $mysqli = new mysqli($_dbHost, $_dbUser, $_dbPass, $_dbName);
    if ($mysqli->connect_errno)
    {
      $retMsg = "Connection Failed<br />[". $mysqli->errno."]: ". $mysqli->connect_error;
      $retClass = "Fail";
    }
    else
    {
      ExecuteSqlFile("sql/pmt-db-core.sql", $_dbPrfx, $mysqli, $arrMsgRet);
      $arrMsg = array_merge($arrMsg, $arrMsgRet);

      ExecuteSqlFile("sql/pmt-db-user.sql", $_dbPrfx, $mysqli, $arrMsgRet);
      $arrMsg = array_merge($arrMsg, $arrMsgRet);

      // Usually returnd messages means FAIL!
      if (count($arrMsg) > 0)
      {
        $retMsg = "<p><b>An error has occurred</b></p> <p>". print_r($arrMsg, true) . "</p>\n";
        $retClass = "Fail";
      }
      else
      {
        $retMsg = "xenoPMT Core installed successfully!";
        $retClass = "Success";
      }

      $mysqli->close();
    }
  }
  catch(Exception $e)
  {
    $retMsg = "MySQL Exception: " . $e->getMessage();
    $retClass = "Fail";
  }

  $retArr = array("dbRet_msg"   => $retMsg,
                  "dbRet_class" => $retClass);
  echo json_encode($retArr);

  debug("Exiting :: ajaxInstallXenoPMT");
}


/* ##################################################################### */
/* ##[ Procedures called by Ajax procs ]################################ */

/**
 * Execute Queries from SQL file and modify table prefix (if requested)
 * @param string $sqlFile   - Path to SQL query file to execute
 * @param string $tblPrefix - Table prefixes to change to
 * @param mysqli $objConn   - mysqli connection object
 * @param array  $arrErrMsg - Array of various error messages returned
 */
function ExecuteSqlFile($sqlFile, $tblPrefix, $objConn, &$arrErrMsg)
{
  debug("Entering :: ExecuteSqlFile");

  pmtDebug("dbFile: '" . $sqlFile . "'  Prefix: '". $tblPrefix ."'");

  $arrErrMsg = array(); // Create new array

  $buff = file_get_contents($sqlFile);                // Buffer the SQL file
  $buff = str_replace("TBLPMT_", $tblPrefix, $buff);  // Replace with custom table header
  $arrQ = explode(";", $buff);                        // Split up SQL Queries into an array

  foreach($arrQ as $q)
  {
    if(!empty($q) && strlen($q) > 23)
    {
      $q .= ";";
      //pmtDebug("Query [".strlen($q)."] [".$q."]");

      if(!$objConn->query($q))
        $arrErrMsg[] = "MySQL Error: [" . $objConn->errno ."] " . $objConn->error;
    }
  }

  debug("Exiting :: ExecuteSqlFile");
}


/**
 * Old method to check if Installed
 * @return boolean true false
 */
function IsInstalled()
{
  //global $_dbHost, $_dbName, $_dbPrfx, $_dbUser, $_dbPass;
  //GetDbParams();


  $installed = false;
  if(file_exists("../config.php"))
  {
    require_once "../config.php";
    $con = mysql_connect( $pmtConf["db"]["server"],
                          $pmtConf["db"]["user"],
                          $pmtConf["db"]["pass"]);

    mysql_select_db($pmtConf["db"]["dbname"], $con);// $link);

    $ret = mysql_query("SHOW TABLES;", $con);
    while ($arr = mysql_fetch_array($ret))
    {
      // Check if the settings table exists
      if($ret[0] == $pmtConf["db"]["prefix"] . "settings")
      {
        $installed = true;
        break;
      }
    }
  }
  return $installed;
}

?>
