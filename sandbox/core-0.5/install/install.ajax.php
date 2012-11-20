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
 * To Do:
 * 2012-11-19 - Proposal
 * [ ]  Refactor variable names to reflect control names. (_dbHost -> txtDbHost)
 * [ ]  Place procedures in a class and call only the needed members, encapsulating
 *      the class members which don't need to be seen by the rest of the world.
 *      The constructor will perform the GetPostParams crap.
 *
 *     Ex:
 *        $obj = new xpmtAjax();
 *        $obj->ExecuteStep();      // get step from POST and execute member
 *        echo($obj->Output());     // ret_msg and ret_class
 *
 * Change Log:
 * 2012-11-19 + added proc, "ajaxCreateConfig()" to generate user's "config.php" file.
 *            + added proc, "GetPost($param, $def="")". Refactored to minimize re-using code
 *            * Renamed GetDbParams() to GetPostParams()
 */

require "../xpmt/phpConsole.php";
PhpConsole::start(true, true, dirname(__FILE__));

// setup variables
$_dbHost = "";  // Database Server Name
$_dbName = "";  // Database Name
$_dbPrfx = "";  // Database Table Prefix
$_dbUser = "";  // Database User Name
$_dbPass = "";  // Database Password

// Site configuration
$_txtCfgSiteName = "";
$_txtCfgBaseUrl = "";
$_optCfgCleanUri = "";
$_txtCfgAdminName = "";
$_txtCfgAdminUser = "";
$_txtCfgAdminPass = "";
$_txtCfgAdminEmail = "";

// Modules to install
$_chkModAdmin = "";
$_chkModDashboard = "";
$_chkModCustomer = "";
$_chkModKB = "";
$_chkModProduct = "";
$_chkModProject = "";
$_chkModTicket = "";
$_chkModBug = "";
$_chkModTask = "";
$_chkModWiki = "";
$_chkModPO = "";

// Pull parameters by default
GetPostParams();


// Get where we are suppose to go
if(isset($_POST["ClearDB"]) && $_POST["ClearDB"]=="1")  ajaxClearDB();
if(isset($_POST["UpdateStep"]))                         ajaxUpdateStep();
if(isset($_POST["step3"]) && $_POST["step3"]=="1")      ajaxDatabaseTest();
if(isset($_POST["step4"]) && $_POST["step4"]=="1")      ajaxInstallXenoPMT();
if(isset($_POST["step5"]) && $_POST["step5"]=="1")      ajaxCreateConfig();

/* ##[ Misc Functions ]########################################## */
function pmtDebug($buff)
{
  global $BETA_TESTING;
  //if (defined("DebugMode") && DebugMode == true)
  if ($BETA_TESTING)
    debug($buff);
}


/**
 * Get POST parameters
 */
function GetPostParams()
{
  global $_dbHost, $_dbName, $_dbPrfx, $_dbUser, $_dbPass;
  global $_txtCfgSiteName, $_txtCfgBaseUrl, $_optCfgCleanUri, $_txtCfgAdminName, $_txtCfgAdminUser, $_txtCfgAdminPass, $_txtCfgAdminEmail;

  // Modules to install
  global $_chkModAdmin;
  global $_chkModDashboard;
  global $_chkModCustomer;
  global $_chkModKB;
  global $_chkModProduct;
  global $_chkModProject;
  global $_chkModTicket;
  global $_chkModBug;
  global $_chkModTask;
  global $_chkModWiki;
  global $_chkModPO;


  $_dbHost    = GetPost("txtDbServer");     //if (isset($_POST["txtDbServer"])) $_dbHost = $_POST['txtDbServer']; else $_dbHost = "";
  $_dbName    = GetPost("txtDbName");       //if (isset($_POST["txtDbName"]))   $_dbName = $_POST['txtDbName'];   else $_dbName = "";
  $_dbPrfx    = GetPost("txtDbPrefix");     //if (isset($_POST["txtDbPrefix"])) $_dbPrfx = $_POST['txtDbPrefix']; else $_dbPrfx = "";
  $_dbUser    = GetPost("txtDbUser");       //if (isset($_POST["txtDbUser"]))   $_dbUser = $_POST['txtDbUser'];   else $_dbUser = "";
  $_dbPass    = GetPost("txtDbPass");       //if (isset($_POST["txtDbPass"]))   $_dbPass = $_POST['txtDbPass'];   else $_dbPass = "";

  // Site configuration
  $_txtCfgSiteName    = GetPost("txtCfgSiteName");
  $_txtCfgBaseUrl     = GetPost("txtCfgBaseUrl");
  $_optCfgCleanUri    = GetPost("optCfgCleanUri");
  $_txtCfgAdminName   = GetPost("txtCfgAdminName");
  $_txtCfgAdminUser   = GetPost("txtCfgAdminUser");
  $_txtCfgAdminPass   = GetPost("txtCfgAdminPass");
  $_txtCfgAdminEmail  = GetPost("txtCfgAdminEmail");

  // Modules to install
  $_chkModAdmin     = GetPost("chkModAdmin", true);
  $_chkModDashboard = GetPost("chkModDashboard", true);
  $_chkModProject   = GetPost("chkModProject", true);
  $_chkModTicket    = GetPost("chkModTicket", true);
  $_chkModBug       = GetPost("chkModBug", true);
  $_chkModTask      = GetPost("chkModTask", true);

  $_chkModCustomer  = GetPost("chkModCustomer", false);
  $_chkModKB        = GetPost("chkModKB", false);
  $_chkModProduct   = GetPost("chkModProduct", false);
  $_chkModWiki      = GetPost("chkModWiki", false);
  $_chkModPO        = GetPost("chkModPO", false);

}

/**
 * Safely get _POST parameter and inject default if needed
 * @param string $param POST parameter
 * @param any $def      Default value
 * @return any
 */
function GetPost($param, $def="")
{
  if (isset($_POST[$param]))
    $ret = $_POST[$param];
  else
    $ret = "";
  return $ret;
}

/* ##[ Ajax Procedures ]########################################## */

/**
 * Useless function, just use the jQuery shit.
 * This was setup as an Ajax test.
 */
/** 2012-1018 - Removed, this is not needed. jQuery is doing all the work
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
*/

/**
 * Remove database tables and prep for recreation
 */
function ajaxClearDB()
{
  // 1) Extract variables (safely pull from POST)
  global $_dbHost, $_dbName, $_dbPrfx, $_dbUser, $_dbPass;
  //GetPostParams();
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
 * DatabaseTest via Ajax
 *
 * Takes in POST arguments:
 *  txtDbServer - Server Host Name
 *  txtDbName   - Database Name
 *  txtDbPrefix - Table Prefix (not needed)
 *  txtDbUser   - User Name
 *  txtDbPass   - Password
 */
function ajaxDatabaseTest()
{
  // 0) Setup return vars
  $retMsg   = "blank info";   //
  $retClass = "Fail";         // pre-populate

  // 1) Extract variables (safely pull from POST)
  global $_dbHost, $_dbName, $_dbPrfx, $_dbUser, $_dbPass;
  //GetPostParams();

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
  pmtDebug("Entering :: ajaxInstallXenoPMT");

  // Step 1) Setup variables
  $retMsg   = "blank info";   //
  $retClass = "Fail";         // pre-populate
  $arrMsg = array();
  global $_dbHost, $_dbName, $_dbPrfx, $_dbUser, $_dbPass;
  //GetPostParams();

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

  pmtDebug("Exiting :: ajaxInstallXenoPMT");
}

/**
 * Step 5 - Create User Configuration File
 */
function ajaxCreateConfig()
{
  pmtDebug("Entering :: ajaxCreateConfig");

  /** Steps
   * 1 - setup variables
   * 2 - Insert Administrator into Database
   * 3 - Place modules into string list
   * 4 - Write user's "config.php" file
   */

  /* Setup Variables */
  global $_dbHost, $_dbName, $_dbPrfx, $_dbUser, $_dbPass;
  global $_txtCfgSiteName, $_txtCfgBaseUrl, $_optCfgCleanUri, $_txtCfgAdminName, $_txtCfgAdminUser, $_txtCfgAdminPass, $_txtCfgAdminEmail;

  // Modules to install
  global $_chkModAdmin;
  global $_chkModDashboard;
  global $_chkModCustomer;
  global $_chkModKB;
  global $_chkModProduct;
  global $_chkModProject;
  global $_chkModTicket;
  global $_chkModBug;
  global $_chkModTask;
  global $_chkModWiki;
  global $_chkModPO;

  $pmtConf = "$"."pmtConf";
  $retMsg = "";
  $retClass = "Success";

  /* Create Module List */
  // require_once( dirname( __FILE__ ) . "/extensions/WikiEditor/WikiEditor.php" );
  $lstMods = "";
  $lstMods .= "";
  debug("DirName:" . dirname( __FILE__ ));


  /* Step 3 - Create Config File*/

  $buff = <<<CODE
<?php
/**
* xenoPMT
* Copyright 2010-2012 (C) Xeno Innovations, Inc.
* ALL RIGHTS RESERVED
* Author:        Damian J. Suess
* Document:      config.php
* Created Date:  Nov 18, 2010, 5:03:43 PM
* Description:
*   This is the Default CORE config file, becareful when editing this
*   file as it will effect ALL of your sub-projects. Here you
*   can set your Root-User, Database, Default Skin, etc.
*
***********************************************************/

date_default_timezone_set('America/New_York');        // [DJS] Added to fix warning in PHP & PhpConsole

// Main config var
{$pmtConf} = array(
  "db" => array(
    "server"  => "{$_dbHost}",  // Database server
    "user"    => "{$_dbUser}",  // Database username
    "pass"    => "{$_dbPass}",  // Database password
    "dbname"  => "{$_dbName}",  // Database name
    "prefix"  => "{$_dbPrfx}"   // Table prefix
  ),
  "general" => array(
    "auth_only" => true, // Allow access to public or auth-only
    "title"     => "{$_txtCfgSiteName}",
    "base_url"  => "{$_txtCfgBaseUrl}"    // Must include '/' at the end.
  )
);

// Modules to include. Needed for first time install of module
{$lstMods}

?>

CODE;

  // ===================================
  $retArr = array("ret_msg" => $retMsg,
                  "ret_cls" => $retClass);
  echo(json_encode($retArr));
  pmtDebug("Exiting :: ajaxCreateConfig");
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
  //GetPostParams();


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
