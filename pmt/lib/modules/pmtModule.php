<?php
/* * **********************************************************
 * Copyright 2012 (C) Xeno Innovations, Inc.
 * ALL RIGHTS RESERVED
 * Author:       Damian Suess
 * Document:     pmtModule.php
 * Created Date: Mar 22, 2012
 *
 * Description:
 *  Interface to what modules must contain.
 *
 * Change Log:
 *
 */

/**
 *
 * @author Damian Suess
 */
interface pmtModule
{
  /**
   * Generates the page data for the module
   */
  public function PageData();

  /**
   * Title of the generated page
   */
  public function Title();

  /**
   * User's setup toolbar
   * [1] Login \ Setup
   * [2] Welcome, <user> \ Preferences \ Logout
   */
  //public function Metabar();

  /**
   * Toolbar - HTML generated toolbar according to location
   */
  public function Toolbar();

  public function MiniBarLeft();
  public function MiniBarRight();

}

?>
