<?php
/** **********************************************************
 * Copyright 2012 (C) Xeno Innovations, Inc.
 * ALL RIGHTS RESERVED
 * @author        Damian J. Suess
 * Document:      sample.main.php
 * Created Date:  2012-08-05
 *
 * Description:
 *  Basic model for Modules
 *
 * To Do:
 *  [ ]
 *
 * Change Log:
 *  2012-0805 * Initial Creation
 ***********************************************************/

require_once (PMT_PATH . "xpmt/core/pmt.module.php");

// namespace xenoPMT\Module\Sample;

class sample extends pmtModule    // implements iModule
{
  function __construct()
  {

  }

  function Title() {
    return "";
  }
  function PageData() {
    return "";
  }
  function Toolbar() {
    return "";
  }
  function MiniBarLeft() {
    return "";
  }
  function MiniBarRight() {
    return "";
  }

}

?>
