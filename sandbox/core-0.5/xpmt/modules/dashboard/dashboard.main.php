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
 *  2013-0331 + Enabled & fixed namespace (NS). NS needs to be the first item so that required_once() can be used.
 *            + Fixed "pmtModule" class missing. Since it's not in this NS we prepend, "\" for root.
 *  2012-1203 + Added notes to construct. Display, "Module not installed!"
 */

namespace xenoPMT\Module\Dashboard
{
  require_once (PMT_PATH . "xpmt/core/pmt.module.php");
  class dashboard extends \pmtModule
  {
    /* Private vars */
    private $_title;      // Title of the page
    private $_toolbar;    // Override the toolbar at least until Core-0.0.5 can do it on its own.
    private $_minileft;   // Mini Toolbar (left)
    private $_miniright;  // Mini Toolbar (right)
    private $_pagedata;   // page data (Anon / Logged-in)

    /* Public Vars */

    function __construct()
    {
      // ToDo:
      // [ ] 2013-0205 Used to include libWiki
      //    global $xpmtCore;
      //    $xpmtCore->lib_include("wiki");

      $this->_title     = "Welcome to xenoPMT";         // Force change the page title or (blank) for default
      $this->_toolbar   = "";   // Don't override toolbar, let system draw it
      $this->_minileft  = $this->GenerateMiniLeft();    // Breadcrumbs
      $this->_miniright = $this->GenerateMiniRight();   // Dashboard doen't have special features (yet)
      $this->_pagedata  = $this->GeneratePage();        // Pull page from Wiki system

      // If (IsInstalled() == false && UserIsAdmin==true)
      // { give link to install module }
      // --[ OR ]--
      // if (IsInstalled() == false)
      // $this->_pageData = "Module Not Installed!";
    }

    function __destruct()
    {

    }

    public function Title() { return $this->_title; }             /* Title of the generated page */
    public function Toolbar() { return $this->_toolbar; }         /* Toolbar - HTML generated toolbar according to location */
    public function MiniBarLeft() { return $this->_minileft; }
    public function MiniBarRight() { return $this->_miniright; }
    public function PageData() { return $this->_pagedata; }

    // ================================

    private function GenerateMiniLeft()   { return ""; }
    private function GenerateMiniRight()  { return ""; }

    /**
     * Checks a series if items to generate Welcome Page data
     *  A. Is user logged in?
     *    i.  Yes :: Display "User Dashboard" (4x2 boxes)
     *    ii. No  :: Display "standard" Welcome page
     * @return string HTML Page Data
     */
    private function GeneratePage()
    {
      // No need to perform login check.. just generate the damn UUID
      /**
       * TODO [2013-0250]
       * [ ] Pull page from wiki system
       *
       */

      global $xpmtConf, $xpmtCore, $user;

      // Do something different if not logged in?
      //if ($user->Online == true)  // ($xpmtConf["user"]["online"] == false)
      //{  } else {  }

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
  }
}

?>
