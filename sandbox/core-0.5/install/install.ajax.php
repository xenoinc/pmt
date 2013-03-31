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
 * Test:  http://pmt2/install/install.ajax.php?unitest=1
 *
 * Parameters:
 *  GET   unitest=1  Perform unit testing
 *  POST  ClearDB=1  Clear database and start fresh
 *  POST  UpdateStep
 *  POST  step3
 *  POST  step4
 *  POST  step5
 *  POST  step6
 *
 * To Do:
 * 2012-11-19 - Proposal
 * [X]  Refactor variable names to reflect control names. (_dbHost -> txtDbHost)
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
 *  2013-0329 + Added, 'date_default_timezone_set()' to NY as default
 *  2013-0131 + Added cheap unit testing step so we cal call functions directly
 *  2012-1219 * Updated debug message text
 *            + Added throw new Exception(..) to "ajaxCreateConfig()" when inserting Admin account that already exists. (djs)
 *            + Addex throw new Exception(..) to "ajaxInstallXenoPMT()" if IsInstalled() == true
 *            * Fixed error in procedure, IsInstalled() - it was using $ret[0] and not $arr[0]
 *            * Refactored, ajaxClearDB() to pull from Config.php first, if not then use Debug/Installer's text boxes
 *  2012-1203 * Updated, Config Complete message. (djs)
 *  2012-1119 + added proc, "ajaxCreateConfig()" to generate user's "config.php" file.
 *            + added proc, "GetPost($param, $def="")". Refactored to minimize re-using code
 *            * Renamed GetDbParams() to GetPostParams()
 */

require "../xpmt/phpConsole.php";
PhpConsole::start(true, true, dirname(__FILE__));

date_default_timezone_set('America/New_York');        // [DJS] Added to fix warning in PHP & PhpConsole

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


// Added 2013-0131 - Cheap unit testing
If (isset($_GET["unitest"]) && $_GET["unitest"] =="1")
{
  ajaxInstallModules();
  exit();
}


// Get where we are suppose to go
if(isset($_POST["ClearDB"]) && $_POST["ClearDB"]=="1")  ajaxClearDB();
if(isset($_POST["UpdateStep"]))                         ajaxUpdateStep();
if(isset($_POST["step3"]) && $_POST["step3"]=="1")      ajaxDatabaseTest();
if(isset($_POST["step4"]) && $_POST["step4"]=="1")      ajaxInstallXenoPMT();
if(isset($_POST["step5"]) && $_POST["step5"]=="1")      ajaxCreateConfig();
if(isset($_POST["step6"]) && $_POST["step6"]=="1")      ajaxInstallModules();

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
  //pmtDebug("UpdateStep()");

  $ret = $_POST["UpdateStep"];
  //pmtDebug("Go To: " . $ret);

  echo json_encode(array("returnValue" => "$ret"));
  // } else {
  // $ret = "99";
  // echo json_encode(array("returnValue" => "$ret"));
}
*/

/**
 * Removes Config file and Database tables and prep for recreation
 */
function ajaxClearDB()
{
  // 1) Extract variables (safely pull from POST)
  global $_txtDbServer, $_txtDbName, $_txtDbPrefix, $_txtDbUser, $_txtDbPass;

  $retMsg = "<ul>";     // Used to report info back to UI (user interface)

  // ---[ New ]---
  //
  // Step 0 - Perform initial db info configuration
  if(file_exists("../config.php"))
  {
    require_once("../xpmt/config.default.php");   // Configure default first
    require_once("../config.php");                // Override w/ user's settings
    global $xpmtConf;
    $dbServ = $xpmtConf["db"]["server"];
    $dbName = $xpmtConf["db"]["dbname"];
    $dbUser = $xpmtConf["db"]["user"];
    $dbPass = $xpmtConf["db"]["pass"];
  }
  else
  {
    $retMsg .= "<li>User's <b>config.php</b> not found, trying installer's settings..</li>";
    $dbServ = $_txtDbServer;
    $dbName = $_txtDbName;
    $dbUser = $_txtDbUser;
    $dbPass = $_txtDbPass;
  }

  // Step 2 - Connect to DB and Remove it!
  // NOTE: This step below REQUIRES DB User account to have Drop DB and Create DB privs.
  try
  {
    $mysqli = new mysqli($dbServ, $dbUser, $dbPass, $dbName);
    if ($mysqli->connect_errno)
    {
      // Report DB Connection Error
      $retMsg .= "<li>Database connection failure! [{$mysqli->connect_errno}]: '{$mysqli->connect_error}'</li>";
    }
    else
    {
      if ($mysqli->query("DROP DATABASE " . $dbName .";") === TRUE)
        $retMsg .= "<li>Dropped database.</li>";
      else
        $retMsg .= "<li><b>FAILED</b> to drop database!</li>";

      if ($mysqli->query("CREATE DATABASE " . $dbName .";") === TRUE)
        $retMsg .= "<li>Recreated database.</li>";
      else
        $retMsg .= "<li><b>FAILED</b> to recreated database!</li>";

      $mysqli->close(); // make sure it closes successfuly
    }
  }
  catch(Exception $e)
  {
    $retMsg .= "<li><b>Database Exception:</b> '{$e}'.</li>";
  }

  // ----[ OLD ]-------
  /*
  require_once("../xpmt/core/pmt.db.php");
  // 2) Connect to db
  $pmtDB = new Database($_txtDbServer, $_txtDbUser, $_txtDbPass);
  // 3) Drop all tables
  $pmtDB->Query("DROP DATABASE ".$_txtDbName.";");
  $pmtDB->Query("CREATE DATABASE ".$_txtDbName.";");
  //$pmtDB->Close();                                // throws an error
  */

  if (file_exists("../config.php"))
  {
    unlink("../config.php");
    $retMsg .= "<li>Removed user's 'config.php'.</li>";
  } else {
    $retMsg .= "<li>Cannot remove user's 'config.php', file not found.</li>";
  }

  $retMsg .= "</ul>";

  // 4) Report status
  $retArr = array("dbRet_msg"   => $retMsg,
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
  //pmtDebug(json_encode($retArr));
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
    if (IsInstalled() == true)
      throw new Exception("Database is already installed!  To verify, please manually connect and verify there are no tables.");

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
    $retMsg = "Exception thrown: " . $e->getMessage();
    $retClass = "Fail";
    //$mysqli->close();
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
        pmtDebug("'Previous User' check query returned {$userRows} rows. (<i>0=good</i>)\n");
        $sqlRet->close(); /* free result set */
      }

      // ==[ 2 - Insert ]=====================
      if ($userRows == 0)
      {
        $q = "INSERT INTO `{$_txtDbPrefix}USER` (User_Name, Password, Email, Display_Name) VALUES (" .
             "'{$_txtCfgAdminUser}', '{$_txtCfgAdminPass}', '{$_txtCfgAdminEmail}', '{$_txtCfgAdminName}');";
        $mysqli->query($q);
      }
      else
      {
        $mysqli->close();
        throw new Exception("There is already an admin user in the DB called, '$_txtCfgAdminUser'!");
      }
      $mysqli->close();
    }
  }
  catch(Exception $e)
  {
    $retMsg .= "<br />Failed to add Admin User data into database. Error: {$e}";
    $retClass = "Fail";
    pmtDebug("Failed to add Admin User data into database. Error: {$e}");
    // $mysqli->close();
  }




  /** Step 3
   * Create Module List
   */
  //pmtDebug("Step 3" );    // debug("DirName:" . dirname( __FILE__ ));
  // require_once( dirname( __FILE__ ) . "/extensions/WikiEditor/WikiEditor.php" );
  $req = 'xpmtUseMod( dirname( __FILE__ ) . "/xpmt/modules/';       // disable automatically for now just in case

  // Beta testing only
  //if ($BETA_TESTING)    {$lstMods .= $req . 'sample/sample.php");' . PHP_EOL;}
  if ($_chkModUUID)     {$lstMods .= $req . 'uuid/uuid.php");' . PHP_EOL;}


  // Modules to install
  // Static List from version v0.0.5
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
  $date = date("Y-m-d, H:m:s");
  $buff = <<<CODE
<?php
/** * *********************************************************
* xenoPMT
* Copyright 2011-2013 (C) Xeno Innovations, Inc.
* ALL RIGHTS RESERVED
* Author:        Damian J. Suess
* Document:      config.php
* Created Date:  {$date}
* Description:
*   This is the Default CORE config file, becareful when editing this
*   file as it will effect ALL of your sub-projects. Here you
*   can set your Root-User, Database, Default Skin, etc.
***********************************************************/

// TODO: Set this inside the installer
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

/**
 * Safely REQUIRE modules. If it doesn't exist then it won't crash the system.
 * Procedure can be found in: "config.default.php" for safty purposes.
 */
// Modules to include. Needed for first time install of module
{$lstMods}

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

/**
 * Get data the user's config file and install the listed modules
 *
 * Poor Man's Unit Test:
 *  http://pmt2/install/install.ajax.php?unitest=1
 *
 */
function ajaxInstallModules()
{
  /* Steps:
   * 1. Load the configs
   * 2. Cycle through each module
   * 3. Install each module
   * 4. Report back in ret_msg  (TODO)
   *
   * To Do: This should be ran from the "Next" button in "Step 5"
   */

  //pmtDebug("Install Modules");
  global $xpmtModule;          // Pull back all config file registered modules
  global $xpmtConf;           // Load it so we can pass it into the Module's Setup member

  // Step 1 :: Does config exist?
  if(file_exists("../config.php"))
  {
    // Set the Globals variables before we load them or else it will be overwritten
    require_once("../xpmt/config.default.php");   // Configure default first
    require_once("../config.php");                // Override w/ user's settings

    $retModData = null;                           // Return array of module data
    $found = false;                               // Check for duplicates

    // Step 2 - Cycle through each module
    foreach ($xpmtModule["info"] as $mod)
    {
      $retErr = null;
      $ret = InstallMod($mod, $retErr);

      $errArr = print_r($retErr, true);   // Return error array if any
      if ($ret == false)
      {
        // buffer up the error message's cause of failure for the module
        $errArr = ""; // dummy
      }
    }
  }
  else
  {
    $ret_cls = "Fail";
  }


  $retMsg = "test yay!";
  $ret_cls = "Success";


  $retArr = array("ret_msg" => $retMsg,
                  "ret_cls" => $ret_cls);
  echo(json_encode($retArr));
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
  pmtDebug("Entering :: ExecuteSqlFile");

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

  pmtDebug("Exiting :: ExecuteSqlFile");
}

/**
 * Old method to check if xenoPMT is Installed.
 * <ol>
 *  <li>Does 'config.php' file exists.</li>
 *  <li>Does "CORE_SETTINGS" table exists.</li>
 * </ol>
 *
 * @return boolean true false
 */
function IsInstalled()
{
  pmtDebug("Entering::IsInstalled()");

  $installed = false;
  if(file_exists("../config.php"))
  {
    require_once("../xpmt/config.default.php");   // Configure default first
    require_once("../config.php");                // Override w/ user's settings
    global $xpmtConf;                             // Pull back all config file registered modules
    $con = mysql_connect( $xpmtConf["db"]["server"],
                          $xpmtConf["db"]["user"],
                          $xpmtConf["db"]["pass"]);

    mysql_select_db($xpmtConf["db"]["dbname"], $con);// $link);

    $ret = mysql_query("SHOW TABLES;", $con);
    while ($arr = mysql_fetch_array($ret))
    {
      //pmtDebug("DB:: {$arr[0]}");
      // Check if the settings table exists
      if(strtoupper($arr[0]) == strtoupper($xpmtConf["db"]["prefix"] . "CORE_SETTINGS"))
      {
        $installed = true;
        break;
      }
    }
  }
  pmtDebug("Exiting::IsInstalled() { return=$installed }");
  return $installed;
}

/**
 * Install the module
 * // param string $uuid
 * @param array Module Header
 * @param array $errArr
 */
function InstallMod($modHeader, &$errArr)  //function InstallMod($uuid, &$errArr)
{
  /**
   * ToDo:
   * [ ] Put the REQUIRE_ONCE inside of a try{}catch{} so we
   */

  $errRet = array();      // Error messages returned

  // Step 1) Require physical path of CLASS.setup.php
  $pth = $modHeader["path"] . "/" . $modHeader["classname"] . ".setup.php";

  require_once($pth);

  // Step 2) Load the namespace
  $ns = $modHeader["namespace"] . "\\Setup";
  $modSetup = new $ns(true, $modHeader);        // Fixed 2013-0131 * We were passing wrong params ($boolInstall, $headerInfo[])
  // $modSetup = new $ns($modHeader);           // OLD code (pre-2012-1219)

  // Step 3) Run setup member "bool PreInstallCheck($arrCheck)"
  // $bErr = $modSetup->PreInstallErrors($arrErr); // Old code (pre-2012-1219)
  // if($bErr == true) {}

  if ($modSetup->Verified() == false)
  {
    // Module failed verification, tell us why
    $errArr = $modSetup->GetVerifiedMessages();   // // Get error message array

    // pmtDebug("install.ajax::InstallMod() PreInstallError.. ERR: " . print_r($errArr, true));
    $errRet = $errArr;
  }
  else
  {
    // Module was verified & can be installed
    $bRet = $modSetup->Install();

    //pmtDebug("install.ajax::InstallMod() Install.Return: $bRet");
    if ($bRet == false)
    {
      // false
    }

  }

  // Step 4) Return back results


}
?>
