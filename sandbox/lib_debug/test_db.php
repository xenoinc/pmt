<?php

$dbase = array();
$dbase["server"] = "localhost";
$dbase["user"]   = "testuser";
$dbase["pass"]   = "testpass";
$dbase["dbname"] = "PMT_DATA";
$dbase["prefix"] = "XIPMT_";

$cfgPHP = array();
$cfgPHP[] ='<?php';
$cfgPHP[] ='';
$cfgPHP[] ='/* Generated from Installer */';
$cfgPHP[] ='$pmtConf = array(';
$cfgPHP[] ='  "db" => array(';
$cfgPHP[] ='    "server"  => "' . $dbase["server"] .'",   // Database Server';
$cfgPHP[] ='    "user"    => "' . $dbase["user"] . '",    // Database user';
$cfgPHP[] ='    "pass"    => "' . $dbase["pass"] . '",    // Database password';
$cfgPHP[] ='    "dbname"  => "' . $dbase["dbname"] . '",  // Database name';
$cfgPHP[] ='    "prefix"  => "' . $dbase["prefix"] . '"   // Table prefix';
$cfgPHP[] ='  ),';
$cfgPHP[] ='  "general" => array(';
$cfgPHP[] ='    "authorized_only" => false    // Allow access to public or auth-only';
$cfgPHP[] ='  )';
$cfgPHP[] =');';
$cfgPHP[] ='';
$cfgPHP[] ="?>";
$cfgPHP = implode(PHP_EOL, $cfgPHP);

$fh = fopen("config.php", "w+");
  fwrite($fh, $cfgPHP);
fclose($fh);


?>