<?php

/* * **********************************************************
 * Copyright 2012 (C) Xeno Innovations, Inc.
 * ALL RIGHTS RESERVED
 * Author:       Damian Suess
 * Document:     dashboard.php
 * Created Date: Mar 22, 2012
 *
 * Description:
 *
 *
 * Change Log:
 *
 */

class dashboard {

  public $Title;

  function __construct()
  {
    /**
     * 1) Get the user access (Logged off, Anon, Admin, etc.)
     *  1.1) get user settings for custom dashboard. (future)
     * 2) Generate page
     * 3) Check for custom title
     */

    // Get title from database setting
    $Title = "Dashboard" . "- " . "[PMT]";    // "Xeno Tracking System"

  }

  public function PageData()
  {
    return "hello world";
  }
}

?>
