<?php

/** * *********************************************************
 * Copyright 2012 (C) Xeno Innovations, Inc.
 * ALL RIGHTS RESERVED
 * @Author:       Damian Suess
 * Document:      admin.main.php
 * Created Date:  Nov 24, 2012
 * Status:        {unstable/pre-alpha/alpha/beta/stable}
 * Description:
 *  Administration module entry point
 *
 * Change Log:
 *
 */
require_once (PMT_PATH . "xpmt/core/pmt.module.php");

class admin extends pmtModule
{

  /* Private vars */
  private $_title;      // Title of the page
  private $_toolbar;    // override teh toolbar at least until Core-0.0.5 can do it on its own.
  private $_pagedata;   // page data (Anon / Logged-in)


  /* Public Vars */

  function __construct()
  {
    $this->_title     = "xenoPMT System Administration";
    $this->_toolbar   = "";
    $this->_pagedata   = $this->GeneratePage();   // Figure out what to display
  }

  function __destruct()
  {

  }

  public function PageData()
  {
    // No need to perform login check.. just generate the damn UUID

    $htm = <<<HTM
<h1>Welcome to <b><i>xeno</i>PMT</b></h1>
<p>
  Under Construction.
</p>
<p>&nbsp;</p>
<p>- Xeno Innovations, Inc. -</p>
HTM;

    return $htm;

  }

  // ================================

  /**
   * Checks a series if items to generate Welcome Page data
   *
   * @return string HTML Page Data
   */
  private function GeneratePage()
  {
    // if ($pmtConf["user"]["online"] == false)
    // { ... } else { ... }

    /** [Point to ]
     * Module           :: http://pmt/admin/module/
     *  Module Config   :: http://pmt/admin/module/<modname>
     * User/Group       :: http://pmt/admin/user/
     * System Config    :: http://pmt/admin/system
     */


    $htdata = "";
    return $htdata;
  }


}

?>

