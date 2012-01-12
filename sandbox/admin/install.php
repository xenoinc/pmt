<?php
/************************************************************
* Copyright 2010 (C) Xeno Innovations, Inc.
* ALL RIGHTS RESERVED
* Author:        Damian J. Suess
* Document:      install
* Created Date:  Nov 6, 2010, 5:57:41 PM
* Description:
*   This page is the main installer to create the database,
*   tables, admin, etc.
***********************************************************/

  $install_step = $_GET["step"];
  if ($install_step == "")
    $install_step = 0;  // intro page

  $inst = new web_install($install_step);

  class web_install
  {
    public $title = "PMT Installation  v0.1 [XI, Inc.]";
    public $body = "";      // generated HTML body
    public $error = "";     // return any errors

    function __construct()
    {
      $a = func_get_args();
      $i = func_num_args();
      if (method_exists($this,$f='__construct'.$i))
        call_user_func_array(array($this,$f),$a);
    }

    function __construct1 ($step)
    {
      switch ($step)
      {
        case 0:
          $this->IntroPage();
          break;
        case 1:
          $this->Step1();
          break;
        default:
          $this->StepError();    // show a default error page
          break;
      }
    }

    private function IntroPage(){
      

    }

    private function Step1()
    {
      //asdf 
    }
    private function StepError($step)
    {
      return "Sorry this step does not exist.  Step: " . $step . "";
    }

  }


  // Connectin test
  function test_conn()
  {
    //Connect To Database
    $hostname='xipmtadmin.db.6216100.hostedresource.com';
    $username='xipmtadmin';
    $password='your password';
    $dbname='xipmtadmin';
    $usertable='your_tablename';
    $yourfield = 'your_field';

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
  }


?>
