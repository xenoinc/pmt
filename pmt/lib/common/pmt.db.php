<?php

/* * **********************************************************
 * Copyright 2012 (C) Xeno Innovations, Inc.
 * ALL RIGHTS RESERVED
 * Author:       Damian J. Suess
 * Document:     pmt.db.php
 * Created Date: Jan 30, 2012
 *
 * Description:
 *  Database class
 *
 * Status: Complete
 * Change Log:
 *
 */

class Database {
  private $_conn = null;
  private $last_query = null;
  private $query_count = 0;

  /**
   * Constructor
   * @param string $server
   * @param string $user
   * @param string $pass
   * @param string $db
   */
  public function __construct($server="", $user="", $pass="", $dbName="")
  {
    if (!empty($server))
    {
      $this->Connect($server, $user, $pass);
      $this->Select($dbName);
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
    $this->_conn = mysql_connect($server, $user, $pass)
            or $this->halt();
  }

  /**
   * Close Database Connection
   */
  public function Close()
  {
    mysql_close($this->_conn);
  }

  /**
   * Select the database
   * @param string $db Database name
   */
  public function Select($db)
  {
    mysql_select_db($db, $this->_conn);
  }

  /**
   * Select database
   * @param string $dbName Database name
   */
  public function SelectDb($dbName)
  {
    $this->Select($dbName);
  }

  /**
   * Query the command
   * @param string $query - Query to execute
   * @return string
   */
  public function Query($query)
  {
    $ret = mysql_query( $query, $this->_conn)
            or $this->halt($query);
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
    return mysql_real_escape_string($string,$this->_conn);
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
  	echo '<div class="message error">'.
            $this->errNum() .
            ' error: '.$this->error().' <br>' .
            'SQL: ' .($query !== null ? $query : '(no-sql)').
         '</div>';
/*
    error("Database",
            "#".$this->errNum().": ".
            $this->error()."<br />".
            ($query !== null ? $query : '')
          );
*/
    /*
    error("Database", "ErrNum[" . $this->errNum() . "]: " .
          $this->error() . "<br />" .
          ($query !== null ? $query : ""));
    */
  }

  // MySQL Error number
  private function errNum()
  {
    return mysql_errno($this->_conn);
  }

  // MySQL Error
  private function error()
  {
    return mysql_error($this->_conn);
  }
}

?>
