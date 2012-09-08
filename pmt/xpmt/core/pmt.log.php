<?php
/* * **********************************************************
 * Copyright 2012 (C) Xeno Innovations, Inc.
 * ALL RIGHTS RESERVED
 * Author:       suessdam
 * Document:     pmt
 * Created Date: Feb 27, 2012
 *
 * Description:
 *  Error and Data Logging class
 *
 * Change Log:
 *
 */

// namespace xenoPMT\Core {

class Log {

  /*
    function logMessage($message) {
      $LOGDIR = '/www/mysite/logs/'; //chmod 744 and chown nobody
      $logFile = $LOGDIR . 'mysite.log';
      $hFile = fopen($logFile, 'a+'); //open for appending, create
                                    //the file if it doesn't exist
      if(! is_resource($hFile)) {
        printf("Unable to open %s for writing. Check file permissions.", $logFile);
        return false;
      }

      fwrite($hFile, $message);
      fclose($hFile);

      return true;
    }

    logMessage("Hello, log!\n");
   */

  private $hLogFile;
  private $logLevel;

  //Log Levels.  The higher the number, the less severe the message
  //Gaps are left in the numbering to allow for other levels
  //to be added later
  const DEBUG     = 100;
  const INFO      = 75;
  const NOTICE    = 50;
  const WARNING   = 25;
  const ERROR     = 10;
  const CRITICAL  = 5;

  //Note: private constructor.  Class uses the singleton pattern
   private function __construct() {

    //This is pseudo code that fetches a hash of configuration information
    //Implementation of this is left to the reader, but should hopefully
    //be quite straightforward.
    $cfg = Config::getConfig();

    /* If the config establishes a level, use that level,
       otherwise, default to INFO
    */
    $this->logLevel = isset($cfg['LOGGER_LEVEL']) ?
                             $cfg['LOGGER_LEVEL'] :
                             Logger::INFO;

    //We must specify a log file in the config
    if(! (isset($cfg['LOGGER_FILE']) && strlen($cfg['LOGGER_FILE'])) ) {
      throw new Exception('No log file path was specified ' .
                          'in the system configuration.');
    }

    $logFilePath = $cfg['LOGGER_FILE'];

    //Open a handle to the log file.  Suppress PHP error messages.
    //We'll deal with those ourselves by throwing an exception.
    $this->hLogFile = @fopen($logFilePath, 'a+');

    if(! is_resource($this->hLogFile)) {
      throw new Exception("The specified log file $logFilePath " .
                   'could not be opened or created for ' .
                   'writing.  Check file permissions.');
    }

    //Set encoding type to ISO-8859-1
    stream_encoding($this->hLogFile, 'iso-8859-1');
  }

  public function __destruct() {
    if(is_resource($this->hLogFile)) {
      fclose($this->hLogFile);
    }
  }

  public static function getInstance() {

    static $objLog;

    if(!isset($objLog)) {
      $objLog = new Logger();
    }

    return $objLog;
  }

  public function logMessage($msg, $logLevel = Logger::INFO, $module = null) {

    if($logLevel > $this->logLevel) {
      return;
    }

    /* If you haven't specifed your timezone using the
       date.timezone value in php.ini, be sure to include
       a line like the following.  This can be omitted otherwise.
    */
    date_default_timezone_set('America/New_York');

    $time = strftime('%x %X', time());
    $msg = str_replace("\t", ' ', $msg);
    $msg = str_replace("\n", ' ', $msg);

    $strLogLevel = $this->levelToString($logLevel);

    if(isset($module)) {
      $module = str_replace("\t", ' ', $module);
      $module = str_replace("\n", ' ', $module);
    }

    //logs: date/time loglevel message modulename
    //separated by tabs, new line delimited
    $logLine = "$time\t$strLogLevel\t$msg\t$module\n";
    fwrite($this->hLogFile, $logLine);
  }

  public static function levelToString($logLevel) {
    switch ($logLevel) {
      case Logger::DEBUG:
        return 'Logger::DEBUG';
        break;
      case Logger::INFO:
        return 'Logger::INFO';
        break;
      case Logger::NOTICE:
        return 'Logger::NOTICE';
        break;
      case Logger::WARNING:
        return 'Logger::WARNING';
        break;
      case Logger::ERROR:
        return 'Logger::ERROR';
        break;
      case Logger::CRITICAL:
        return 'Logger::CRITICAL';
        break;
      default:
        return '[unknown]';
    }
  }
}

?>
