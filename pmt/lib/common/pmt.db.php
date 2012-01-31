<?php

/* * **********************************************************
 * Copyright 2012 (C) Xeno Innovations, Inc.
 * ALL RIGHTS RESERVED
 * Author:       suessdam
 * Document:     pmt.db.php
 * Created Date: Jan 30, 2012
 * 
 * Description:
 *  
 *
 * Change Log:
 * 
 */

class Database {
  private $conn = null;
  private $last_query = null;
  private $query_count = 0;
  
  /**
   * Constructor
   * @param string $server
   * @param string $user
   * @param string $pass
   * @param string $db
   */
  public function __construct($server="", $user="", $pass="", $db="")
  {
    if (!empty($server))
    {
      $this->Connect($server, $user, $pass);
      $this->Select($db);
    }
  }
  
  /**
   * Destructor
   */
  public function __destruct()
  {
    $this->close();
  }
  
  /* **[ Public Members ]********************************** */
  
  /**
   * Open Database Connection
   * @param string $server
   * @param string $user
   * @param string $pass 
   */
  public function Connect($server, $user, $pass)
  {
    $this->conn = mysql_connect($server, $user, $pass)
            or $this->halt();
  }
  
  /**
   * Close Database Connection 
   */
  public function Close()
  {
    mysql_close($this->conn);
  }
  
  /**
   * Select the database
   * @param type $db 
   */
  public function Select($db)
  {
    mysql_select_db($db, $this->conn);
  }
  
  /**
   * Query the command
   * @param string $query - Query to execute
   * @return string
   */
  public function Query($query)
  {
    $ret = mysql_query($query, $this->conn) or $this->halt($query);
    $this->last_query = $query;
    $this->query_count++;
    return $ret;
  }
  
  /**
   * Reutrns array contained in fetched row
   * @param string $ret - SQL Result Array[]
   * @return string
   */
  public function FetchArray($ret)
  {
    $retArr = mysql_fetch_array($ret);
    return $retArr;
  }
  
  /**
   * Escape the string
   * @deprecated
   */
  public function EscapeString()
  {
    return mysql_escape_string($string);
  }
  
  /**
   * Escape string shortcut - quick way to perform escapestring
   * 
   */
  public function es($string)
  {
    return $this->escapestring($string);
  }
  
	
  /**
   * Insert ID
   * Used to get the last inserted row ID.
   */
  public function InsertId()
  {
    return mysql_insert_id();
  }

  /**
   * Num Rows
   * Get number of rows in result.
   */
  public function NumRows($result)
  {
    return mysql_num_rows($result);
  }

  /**
   * Real Escape String
   * Escapes the string, making it safe for use in queries.
   */
  public function Res($string)
  {
    return mysql_real_escape_string($string,$this->conn);
  }

  /**
   * Query First
   * Returns an array of the first row from the query result.
   * @param string $query The query.
   */
  public function QueryFirst($query)
  {
    return $this->FetchArray($this->Query($query));
  }

  
  
  /* **[ Privates Members ]********************************** */

  // Display error message
  private function halt($query=null)
  {
    error("Database", "ErrNum[" . $this->errNum() . "]: " .
          $this->error() . "<br />" .
          ($query !== null ? $query : ""));
  }
  
  // MySQL Error number
  private function errNum()
  {
    return mysql_errno($this->conn);
  }

  // MySQL Error
  private function error()
  {
    return mysql_error($this->conn);
  }
}

?>
