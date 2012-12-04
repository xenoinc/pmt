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
 * 2012-13-03 * Updated, Config Complete message.
 * 2012-11-19 + added proc, "ajaxCreateConfig()" to generate user's "config.php" file.
 *            + added proc, "GetPost($param, $def="")". Refactored to minimize re-using code
 *            * Renamed GetDbParams() to GetPostParams()
 */

require "../xpmt/phpConsole.php";
PhpConsole::start(true, true, dirname(__FILE__));

// ********************
$BETA_TESTING = true;
// ********************

// setup variables
$_txtDbServer = "";  // Database Server Name
$_txtDbName = "";  // Database Name
$_txtDbPrefix = "";  // Database Table Prefix
$_txtDbUser = "";  // Database User Name
$_txtDbPass = "";  // Database Password

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
$_chkModUUID = "";
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
  global $_txtDbServer, $_txtDbName, $_txtDbPrefix, $_txtDbUser, $_txtDbPass;
  global $_txtCfgSiteName, $_txtCfgBaseUrl, $_optCfgCleanUri, $_txtCfgAdminName, $_txtCfgAdminUser, $_txtCfgAdminPass, $_txtCfgAdminEmail;

  // Modules to install
  global $_chkModAdmin;
  global $_chkModDashboard;
  global $_chkModUUID;
  global $_chkModCustomer;
  global $_chkModKB;
  global $_chkModProduct;
  global $_chkModProject;
  global $_chkModTicket;
  global $_chkModBug;
  global $_chkModTask;
  global $_chkModWiki;
  global $_chkModPO;


  $_txtDbServer = GetPost("txtDbServer");     //if (isset($_POST["txtDbServer"])) $_txtDbServer = $_POST['txtDbServer']; else $_txtDbServer = "";
  $_txtDbName   = GetPost("txtDbName");       //if (isset($_POST["txtDbName"]))   $_txtDbName = $_POST['txtDbName'];   else $_txtDbName = "";
  $_txtDbPrefix = GetPost("txtDbPrefix");     //if (isset($_POST["txtDbPrefix"])) $_txtDbPrefix = $_POST['txtDbPrefix']; else $_txtDbPrefix = "";
  $_txtDbUser   = GetPost("txtDbUser");       //if (isset($_POST["txtDbUser"]))   $_txtDbUser = $_POST['txtDbUser'];   else $_txtDbUser = "";
  $_txtDbPass   = GetPost("txtDbPass");       //if (isset($_POST["txtDbPass"]))   $_txtDbPass = $_POST['txtDbPass'];   else $_txtDbPass = "";

  // Site configuration
  $_txtCfgSiteName    = GetPost("txtCfgSiteName");
  $_txtCfgBaseUrl     = GetPost("txtCfgBaseUrl");
  $_optCfgCleanUri    = GetPost("optCfgCleanUri", true);
  $_txtCfgAdminName   = GetPost("txtCfgAdminName");
  $_txtCfgAdminUser   = GetPost("txtCfgAdminUser");
  $_txtCfgAdminPass   = GetPost("txtCfgAdminPass");
  $_txtCfgAdminEmail  = GetPost("txtCfgAdminEmail");

  // Modules to install
  $_chkModAdmin     = GetPost("chkModAdmin", true);
  $_chkModDashboard = GetPost("chkModDashboard", true);
  $_chkModUUID      = GetPost("chkModUUID", false);
  $_chkModProject   = GetPost("chkModProject", false);
  $_chkModTicket    = GetPost("chkModTicket", false);
  $_chkModBug       = GetPost("chkModBug", false);
  $_chkModTask      = GetPost("chkModTask", false);

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
    $ret = $def;

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
  global $_txtDbServer, $_txtDbName, $_txtDbPrefix, $_txtDbUser, $_txtDbPass;
  //GetPostParams();
  require_once("../xpmt/core/pmt.db.php");


  // 2) Connect to db
  $pmtDB = new Database($_txtDbServer, $_txtDbUser, $_txtDbPass);
  // 3) Drop all tables
  $pmtDB->Query("DROP DATABASE ".$_txtDbName.";");
  $pmtDB->Query("CREATE DATABASE ".$_txtDbName.";");
  //$pmtDB->Close();                                // throws an error

  // 4) Report status
  $retArr = array("dbRet_msg"   => "Dropped and created database, '".$_txtDbName."'.",
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
  global $_txtDbServer, $_txtDbName, $_txtDbPrefix, $_txtDbUser, $_txtDbPass;
  //GetPostParams();

  // Stepp 2) Test Database HOST connection (localhost)
  try
  {
    $mysqli = new mysqli($_txtDbServer, $_txtDbUser, $_txtDbPass, $_txtDbName);
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
    $con = mysql_connect($_txtDbServer, $_txtDbUser, $_txtDbPass);
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
  global $_txtDbServer, $_txtDbName, $_txtDbPrefix, $_txtDbUser, $_txtDbPass;
  //GetPostParams();

  // Step 2) Connect to database
  try
  {
    $mysqli = new mysqli($_txtDbServer, $_txtDbUser, $_txtDbPass, $_txtDbName);
    if ($mysqli->connect_errno)
    {
      $retMsg = "Connection Failed<br />[". $mysqli->errno."]: ". $mysqli->connect_error;
      $retClass = "Fail";
    }
    else
    {
      ExecuteSqlFile("sql/pmt-db-core.sql", $_txtDbPrefix, $mysqli, $arrMsgRet);
      $arrMsg = array_merge($arrMsg, $arrMsgRet);

      ExecuteSqlFile("sql/pmt-db-user.sql", $_txtDbPrefix, $mysqli, $arrMsgRet);
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
  //pmtDebug("Entering :: ajaxCreateConfig");

  /** Steps
   * 0 - Error Checking - Check if config.php exists
   * 1 - setup variables
   * 2 - Insert Administrator into Database
   * 3 - Place modules into string list
   * 4 - Write user's "config.php" file
   */

  /** Step 0
   * Check if CONFIG.PHP exists
   */
  /*
  if (file_exists("../config.php"))
  {
    $retArr = array("ret_msg" => "The user config file, 'config.php' already exists in root directory. Please remove it and re-run this step.", "ret_cls" => "Fail");
    echo(json_encode($retArr));
    pmtDebug("Exiting :: ajaxCreateConfig");
    return;
  }
  */
  if (is_writable("../") == false)
  {
    $retArr = array(
        "ret_msg" =>  "The root directory is not is not writable. Please give system write access " .
                      "so we can generate 'config.php' and then change back privs when done.",
        "ret_cls" => "Fail");
    echo(json_encode($retArr));
    pmtDebug("Exiting :: ajaxCreateConfig");
    return;
  }

  // ===================================


  /** Step 1
   * Setup Variables
   */
  global $BETA_TESTING;

  global $_txtDbServer, $_txtDbName, $_txtDbPrefix, $_txtDbUser, $_txtDbPass;
  global $_txtCfgSiteName, $_txtCfgBaseUrl, $_optCfgCleanUri, $_txtCfgAdminName, $_txtCfgAdminUser, $_txtCfgAdminPass, $_txtCfgAdminEmail;

  // Modules to install
  global $_chkModAdmin;
  global $_chkModDashboard;
  global $_chkModUUID;
  global $_chkModCustomer;
  global $_chkModKB;
  global $_chkModProduct;
  global $_chkModProject;
  global $_chkModTicket;
  global $_chkModBug;
  global $_chkModTask;
  global $_chkModWiki;
  global $_chkModPO;

  $xpmtConf = "$"."xpmtConf";
  $modPath = "$"."modPath";

  $retMsg   = "";           // Ajax return message
  $retClass = "Success";    // Ajax return panel class (Success/Fail)
  $lstMods  = "";           // String list of modules to include

  /** Step 2
   * Insert Admin into Database
   */

  try
  {
    $mysqli = new mysqli($_txtDbServer, $_txtDbUser, $_txtDbPass, $_txtDbName);
    if ($mysqli->connect_errno)
    {
      $retMsg .= "Connection Failed<br />[". $mysqli->errno."]: ". $mysqli->connect_error;
      $retClass = "Fail";
    }
    else
    {
      // ==[ 1 - Check if prev-user exist? ]=====================
      $userRows = 0;
      $sqlRet = $mysqli->query("SELECT * FROM  `{$_txtDbPrefix}USER` WHERE User_Name = '{$_txtCfgAdminUser}';");
      if ($sqlRet)
      {
        $userRows = $sqlRet->num_rows;
        pmtDebug("User Query returned {$userRows} rows.\n");
        $sqlRet->close(); /* free result set */
      }

      // ==[ 2 - Insert ]=====================
      if ($userRows == 0)
      {
        $q = "INSERT INTO `{$_txtDbPrefix}USER` (User_Name, Password, Email, Display_Name) VALUES (" .
             "'{$_txtCfgAdminUser}', '{$_txtCfgAdminPass}', '{$_txtCfgAdminEmail}', '{$_txtCfgAdminName}');";
        $mysqli->query($q);
      }

      $mysqli->close();
    }
  }
  catch(Exception $e)
  {
    $retMsg .= "<br />Failed to add Admin User data into database. Error: {$e}";
    $retClass = "Fail";
    pmtDebug("Failed to add Admin User data into database. Error: {$e}");
  }




  /** Step 3
   * Create Module List
   */
  //pmtDebug("Step 3" );    // debug("DirName:" . dirname( __FILE__ ));
  // require_once( dirname( __FILE__ ) . "/extensions/WikiEditor/WikiEditor.php" );
  $req = 'xpmtUseMod( dirname( __FILE__ ) . "/xpmt/modules/';       // disable automatically for now just in case

  // Beta testing only
  if ($BETA_TESTING)    {$lstMods .= $req . 'sample/sample.php");' . PHP_EOL;}
  if ($_chkModUUID)     {$lstMods .= $req . 'uuid/uuid.php");' . PHP_EOL;}


// Modules to install
  //$req = 'require_once( dirname( __FILE__ ) . "/xpmt/modules/';       // disable automatically for now just in case
  if($_chkModAdmin)     {$lstMods .= $req . 'admin/admin.php");' . PHP_EOL;}
  if($_chkModDashboard) {$lstMods .= $req . 'dashboard/dashboard.php");' . PHP_EOL;}

  if($_chkModProject)   {$lstMods .= $req . 'project/project.php");' . PHP_EOL;}
  if($_chkModTicket)    {$lstMods .= $req . 'ticket/ticket.php");' . PHP_EOL;}
  if($_chkModBug)       {$lstMods .= $req . 'bug/bug.php");' . PHP_EOL;}
  if($_chkModTask)      {$lstMods .= $req . 'task/task.php");' . PHP_EOL;}

  if($_chkModCustomer)  {$lstMods .= $req . 'customer/customer.php");' . PHP_EOL;}
  if($_chkModKB)        {$lstMods .= $req . 'kb/kb.php");' . PHP_EOL;}
  if($_chkModProduct)   {$lstMods .= $req . 'product/product.php");' . PHP_EOL;}
  if($_chkModWiki)      {$lstMods .= $req . 'wiki/wiki.php");' . PHP_EOL;}
  if($_chkModPO)        {$lstMods .= $req . 'po/po.php");' . PHP_EOL;}


  /** Step 4
   * Create use's CONFIG.PHP
   */

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

date_default_timezone_set('America/New_York');

// Main config var
{$xpmtConf} = array(
  // Database Connection
  "db" => array(
    "server"  => "{$_txtDbServer}",  // Database server
    "dbname"  => "{$_txtDbName}",  // Database name
    "prefix"  => "{$_txtDbPrefix}",   // Table prefix
    "user"    => "{$_txtDbUser}",  // Database username
    "pass"    => "{$_txtDbPass}",  // Database password
  ),
  // General Site Data
  "general" => array(
    "auth_only" => true, // Allow access to public or auth-only
    "title"     => "{$_txtCfgSiteName}",
    "base_url"  => "{$_txtCfgBaseUrl}",   // Must include '/' at the end.
    "clean_uri" => "{$_optCfgCleanUri}"   // Clean URI
    // , "allow_public_reg" => false      // This should be in Database under system-cfg
  )
);

// Modules to include. Needed for first time install of module
{$lstMods}


/**
 * Safely REQUIRE modules. If it doesn't exist then it won't crash the system.
 * This got moved to, "config.default.php" for safty purposes. We don't want the user to mess with this
 */
//function xpmtUseMod({$modPath})
//{
//  if (file_exists({$modPath})) require_once({$modPath});
//}

?>
CODE;

  // Write config.php
  //pmtDebug("Step 3" );
  try
  {
    // write file data
    $ptr = fopen("../config.php", "w+");
      fwrite($ptr, $buff);
    fclose($ptr);

    $retMsg .= "<h2>Configuration complete!</h2> Generated user's <b>CONFIG.PHP</b> in root directory.";
    //pmtDebug("Created CONFIG.PHP");
  }
  catch (Exception $e)
  {
    $retMsg .= "<br />Error creating <b>CONFIG.PHP</b>. Error: {$e}";
    $retClass = "Fail";
    pmtDebug("Failed to create, CONFIG.PHP");
  }


  // =[ Report back ]==================================

  $retArr = array("ret_msg" => $retMsg,
                  "ret_cls" => $retClass);
  echo(json_encode($retArr));
  //pmtDebug("Exiting :: ajaxCreateConfig");
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
  //global $_txtDbServer, $_txtDbName, $_txtDbPrefix, $_txtDbUser, $_txtDbPass;
  //GetPostParams();


  $installed = false;
  if(file_exists("../config.php"))
  {
    require_once "../config.php";
    $con = mysql_connect( $xpmtConf["db"]["server"],
                          $xpmtConf["db"]["user"],
                          $xpmtConf["db"]["pass"]);

    mysql_select_db($xpmtConf["db"]["dbname"], $con);// $link);

    $ret = mysql_query("SHOW TABLES;", $con);
    while ($arr = mysql_fetch_array($ret))
    {
      // Check if the settings table exists
      if($ret[0] == $xpmtConf["db"]["prefix"] . "settings")
      {
        $installed = true;
        break;
      }
    }
  }
  return $installed;
}

?>
