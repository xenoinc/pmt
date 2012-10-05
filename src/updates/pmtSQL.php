<?php
/************************************************************
 * Copyright 2011 (C) Xeno Innovations, Inc.
 * ALL RIGHTS RESERVED
 * Document:     clsPMTSql.php
 * Created Date: 2011-08-25
 *
 * Description:
 * General class for logging into the PMT (Project Management and Tracking)
 * database for customer and application validation
 * 
 * Change Log:
 * [2011-0825] - 
 *
 */

class pmtSQL
{
  const _dbName = "xipmtadmin";           // MySQL Database Name
  const _ioPMTable = "db-main.sql";       // Database table definitions (use actual path '../xpmt/db-main.sql')
  
  private $_dbServer;                     // DB Server
  private $_dbUser;                       // DB User Name
  private $_dbPass;                       // DB Password
  
  public $db_link;
  //public $table_file;   // so it's visible outside the class
  
  public function __construct($localtest = false)
  {
    if ($localtest == true)
    {
      $this->_dbServer = "localhost:3307";
      $this->_dbUser = "fuct";
      $this->_dbPass = "gstk09";
      //$this->table_file = self::_ioPMTable;
    }
    else
    {
      $this->_dbServer = "xipmtadmin.db.6216100.hostedresource.com";
      $this->_dbUser = "xipmtadmin";
      $this->_dbPass = "PMT4dm1n";
      //$this->table_file = self::_ioPMTable;
    }
  }
  
  public function __destruct() { }

  
  /**
   * Description: Creates the connection to PMT Product and Customer database
   */
  public function pmtConnectDB(){
    //$conn = mysql_connect(self::_dbServer, self::_dbUser, self::_dbPass);       // calling constant vars
    $this->db_link = mysql_connect($this->_dbServer, $this->_dbUser, $this->_dbPass);      // calling privat vars
    if (!$this->db_link)
      return 0; // die("can't connect to pmt db");
    else
    {
      // select our default database, "xipmtadmin"
      mysql_select_db(self::_dbName) or print("MySQL Error: " . mysql_error() );
      return $this->db_link;
    }
    
    /*
      mysql_select_db("test") or die(mysql_error());
      $ret = mysql_query("SELECT * FROM table") or die(mysql_error());
      // EX1
      //$row = mysql_fetch_array($ret);
      //echo("name: " . $row['name'] . "  row2: " . $row['row2']);
      // EX2
      //while($row = mysql_fetch_array($result)){
      //echo $row['name']. " - ". $row['age'];
      //echo "<br />";
    */
    
    /*
        mysql_connect($hostname,$username, $password) OR DIE ('Unable to connect to database! Please try again later.');
        mysql_select_db($dbname);
        $query = 'SELECT * FROM ' . $usertable;
        $result = mysql_query($query);
        if($result) {
          while($row = mysql_fetch_array($result)){
            $name = $row[$yourfield];
            echo 'Name: ' . $name;
          }
        }
     */
    
  }
  
  // Execute query with line parsing, ignore any returned columns
  public function QueryParser($query)
  {
    $chars = preg_split('/;/', $query); //, -1, PREG_SPLIT_OFFSET_CAPTURE);
    print_r($chars);
    
    //split ( string $pattern , string $string [, int $limit = -1 ] )
    //mysql_query
    
    return true;
  }
  
  // Do DB tables exist?
  public function pmtDoTablesExist()
  {
    /*
      $check = mysql_query ("SELECT * FROM `xi_product` LIMIT 0,1");
      if($check)
        return true;
      else
        return false;
    */
    
    // Simple search for table "xi_customer", "xi_product", etc...
    
    if( mysql_num_rows( mysql_query("SHOW TABLES LIKE 'xi_%'")))
      return true;
    else
      return false;
  }
  
  // Make sure that DB tables exist.  if not, create them.
  public function pmtCreateTables()
  {
    
    if (file_exists(self::_ioPMTable))
    {
      $buffer = file_get_contents(self::_ioPMTable);
      /*
      $result = mysql_query($buffer);
      if($result){
        print("---created tables---"."<br>\n");
      }else{
        print("<b>error:</b> " . mysql_error() . "<br>\n");
        print("<hr><br>" . $buffer . "<br><hr>");
      }
      */
      
      
      // Split each query by the semicolon and execute
      $parseQ = preg_split('/;/', $buffer);
      foreach($parseQ as $query)
      {
        $result = mysql_query($query);
        if($result){
          print("Query Executed Successful.."."<br>\n");
        }else{
          print("<b>error:</b> " . mysql_error() . "<br>\n");
          print("<hr><br>" . $query . "<br><hr>");
        } 
      }
      
      return true;
      
    }else{
      
      // File is missing
      return false;
      
    }
  }
  
}

/*
 * Param: $forceRecreate
 *  - True  :: Drop all tables and recreate them
 *  - False :: Dont create tables if they already exist
 */
function CreateTables($forceRecreate = false)
{
  print ("<h1>Testing DB Connection...</h1>"."<br>\n");
  
  $cls = new pmtSQL();
  $conn = $cls->pmtConnectDB();
  if ($conn != 0){
    
    print("Connection Success!"."<br>\n");
    
    if ($forceRecreate)
      // drop all tables and recreate them
      $exist = false;
    else{
      $exist = $cls->pmtDoTablesExist();
      print("<b>Do tables exist? </b> " . $exist . "<br>\n");
    }
    
    
    // Tables don't exist, create them
    if ($exist == false)
    {
      // Drop all tables and recreate them
      if ($cls->pmtCreateTables() == true)
        print("create worked<br>\n");
      else
        print ("Missing DB instructions<br>\n");
              
    }else
    {
      print("Tables already exist.. leave it alone.". "<br>\n");
    }
    
    mysql_close($conn);
    
  }else{
    
    print("Could not connect to PMT database!"."<br>\n");
  }  
}


?>

<html>
  <body>
    
    <p>Connection test</p>
    <p>
      <?php
      
      // create tables  
      CreateTables(true);
      
      ?>
    </p>
  </body>
</html>
