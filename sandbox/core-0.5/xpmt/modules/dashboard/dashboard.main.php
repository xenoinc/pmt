<?php

/** * *********************************************************
 * Copyright 2012 (C) Xeno Innovations, Inc.
 * ALL RIGHTS RESERVED
 * @Author:       Damian Suess
 * Document:      dashboard.main.php
 * Created Date:  Nov 24, 2012
 * Status:        {unstable/pre-alpha/alpha/beta/stable}
 * Description:
 *  Dashboard module entry point
 *
 * Change Log:
 *
 */

require_once (PMT_PATH . "xpmt/core/pmt.module.php");

class dashboard extends pmtModule
{
  /* Private vars */
  private $_title;      // Title of the page
  private $_toolbar;    // override teh toolbar at least until Core-0.0.5 can do it on its own.
  private $_pagedata;   // page data (Anon / Logged-in)


  /* Public Vars */

  function __construct()
  {
    $this->_title     = "Welcome to xenoPMT";
    $this->_toolbar   = "";
    $this->_pagedata   = $this->GeneratePage(); //
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
  This system is still under heavy development and is not
  ready for live action use by any means. Soon enough you will
  get to see what the future holds.  As the project develops the
  user and engineering documentation will mature along with it.
</p>
<p>Sit tight and enjoy the ride!</p>
<p>&nbsp;</p>
<p>- Xeno Innovations, Inc. -</p>
HTM;

    return $htm;

  }

  // ================================

  /**
   * Checks a series if items to generate Welcome Page data
   *  A. Is user logged in?
   *    i.  Yes :: Display "User Dashboard" (4x2 boxes)
   *    ii. No  :: Display "standard" Welcome page
   * @return string HTML Page Data
   */
  private function GeneratePage()
  {
    // if ($pmtConf["user"]["online"] == false)
    // { ... } else { ... }

    $htdata = "";
    return $htdata;
  }


}

?>
