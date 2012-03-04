<?php

/* * **********************************************************
 * Copyright 2012 (C) Xeno Innovations, Inc.
 * ALL RIGHTS RESERVED
 * Author:       suessdam
 * Document:     installer
 * Created Date: Jan 30, 2012
 *
 * Description:
 *
 *
 * Change Log:
 *
 */

/** Is PMT already installed */
function IsInstalled()
{
  $installed = false;
  if(file_exists("../lib/config.php"))
  {
    require_once "../lib/config.php";
    $con = mysql_connect( $pmtConf["db"]["server"],
                          $pmtConf["db"]["user"],
                          $pmtConf["db"]["pass"]);
    mysql_select_db($pmtConf["db"]["dbname"], $link);

    $ret = mysql_query("SHOW TABLES", $con);
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

/**
 * Generate the Header
 * Input:
 *  $step - Current Step
 */
function CreateHeader($title)
{
  echo("<!DOCTYPE html>\n");
	echo("<html>\n");
	echo("  <head>\n");
	echo("    <title>xiPMT ".$title."</title>\n");
	echo("    <link href='pmt.css' media='screen' rel='stylesheet' type='text/css' />\n");
	echo("  </head>\n");
	echo("  <body>\n");
	echo("    <div id='wrapper'>\n");
	echo("      <h1>xiPMT Installation</h1>\n");
	//echo("      <h2>" . aselect(0, explode(" - ", $title)) . "</h2>\n");
	echo("      <h2>" . $title . "</h2>\n");
	echo("      <div id='page'>\n");
  // echo($html);
}

/*
 * Generate the footer
 */
function CreateFooter()
{
  global $pmt_version;
	echo("      </div>\n");
	echo("      <div id='footer'>\n");
	//echo("        xiPMT v" . $pmt_version . " &copy; 20010-2012 Xeno Innovations\n");
	echo("        xiPMT &copy; 2010-2012 Xeno Innovations\n");
	echo("      </div>\n");
	echo("    </div>\n");
	echo("  </body>\n");
	echo("</html>\n");
}

function aselect ($ndx, $arr)
{
  return $arr[$ndx];
}

function htmlMessage($msg1, $msg2="")
{
  if(!empty($msg2)){
    echo("    <div class='message error'>" . $msg1 . " Error: " . $msg2 . "</div>\n");
  }else{
    echo("    <div align='center' class='message error'>" . $msg1 . "</div>\n");
  }
}


/**
 * Get Confiuration Data
 * @param string $cfgItem $config db array item (server, user, pass, dbname, prefix)
 * @param string $default Default output if not set
 * @return Output (default) config setting
 */
function getData($cfgItem, $default)
{
  global $conf;

  if (isset($conf["db"][$cfgItem]))
    return $conf["db"][$cfgItem];
  else
    return $default;
}

/**
 * Print list item
 */
function p($data)
{
  print ("<li>" . $data . "</li>\n");
}


/**
 * Generate Execute SQL files
 * @param string $sqlFile Path to SQL queries to execute
 */
function DbGenerateTables($sqlFile, $dbPrefix)
{
  global $db;

  // Extract SQL & put prefix on tables
  $sqlPmtBrain = file_get_contents($sqlFile);
  $sqlPmtBrain = str_replace("TBLPMT_", $dbPrefix, $sqlPmtBrain);   // Use custom table header
  $query = explode(";", $sqlPmtBrain);

  // Run the queries
  foreach($query as $q)
  {
    // TODO: Readjust the strlen(23). Where is the 20 Padding coming from?
    if(!empty($q) && strlen($q) > 23)
    {
      $q .= ";";
      /*
      if(defined(DebugMode))
        if (DebugMode == true)
        {
          debug("Query [". strlen($q) ."] [" . $q . "]" );
        }
      */

      $db->Query($q);
    }
  }
}

?>
