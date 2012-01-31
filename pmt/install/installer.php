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
  $ins = false;
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
}

/**
 * Generate the Header
 * Input:
 *  $step - Current Step
 */
function CreateHeader($title, $step)
{
  echo("<!DOCTYPE html>\n");
	echo("<html>\n");
	echo("  <head>\n");
	echo("    <title>xiPMT ".$title."</title>\n");
	echo("    <link href='pmt.css' media='screen' rel='stylesheet' type='text/css' />\n");
	echo("  </head>\n");
	echo("  <body>\n");
	echo("    <div id='wrapper'>\n");
	echo("      <h1>xiPMT " . aselect(0, explode(" - ", $title)) . "</h1>\n");
	echo("      <div id='page'>\n");
  
  echo($html);
}

/*
 * Generate the footer
 */
function CreateFooter()
{
	echo("      </div>\n");
	echo("      <div id='footer'>\n");
	echo("        xiPMT &copy; 20010-2012 Xeno Innovations\n");
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

?>
