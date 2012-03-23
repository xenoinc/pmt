<?php

/* * **********************************************************
 * Copyright 2012 (C) Xeno Innovations, Inc.
 * ALL RIGHTS RESERVED
 * Author:       suessdam
 * Document:     project
 * Created Date: Mar 22, 2012
 *
 * Description:
 *
 *
 * Change Log:
 *
 */

require ("pmtModule.php");
class project implements pmtModule {

  function __construct()
  {
    return "hell world";
  }

  public function PageData()
  {
    return "Project module data";
  }

  /**
   * Title of the generated page
   */
  public function Title()
  {
    return "Project";
  }

  /**
   * Toolbar - HTML generated toolbar according to location
   */
  public function Toolbar()
  {
    return "";
  }

}

?>
