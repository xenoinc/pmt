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

// namespace xenoPMT\Core {

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
   * Returns array contained in fetched row
   * @param string $ret - SQL Result Array[]
   * @param int $result_type [optional] <p>
   *  The type of array that is to be fetched. It's a constant and can
   *  take the following values: <b>MYSQL_ASSOC</b>, <b>MYSQL_NUM</b>,
   *  and <b>MYSQL_BOTH</b>.</p>
   * @return string
   *
   * Change Log:
   *  2012-0908 * Added parameter, $result_type and defaulted to "MYSQL_ASSOC".
   *              Now it only returns column names as the indices
   */
  public function FetchArray($ret, $result_type=MYSQL_ASSOC)
  {
    $retArr = mysql_fetch_array($ret, $result_type);   // associative keys as indexes

    //$retArr = mysql_fetch_assoc($ret);              // Return column names
    //$retArr = mysql_fetch_array($ret, MYSQL_NUM);   // numeric indexes
    //$retArr = mysql_fetch_row($ret);                // same as using, MYSQL_NUM
    return $retArr;
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

  /**
   * Fix query string of errors
   * @param string $string
   * @return string
   */
  public function FixString($string)
  {
    //return $this->EscapeString($string);
    return $this->Res($string);
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




  /* **[ Privates Members ]********************************** */

  /**
   * Select the database
   * @param string $db Database name
   */
  private function Select($db)
  {
    mysql_select_db($db, $this->_conn);
  }

  /**
   * Real Escape String
   * Escapes the string, making it safe for use in queries.
   */
  private function Res($string)
  {

    // Use this instead.. however it does prompt WARNINGS w/ PHPConsole
    // $string = function_exists("mysql_real_escape_string") ? mysql_real_escape_string($string,$this->_conn) : mysql_escape_string($string);

    return mysql_real_escape_string($string,$this->_conn);
  }


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
