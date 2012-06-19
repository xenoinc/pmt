<?php

/* * **********************************************************
 * Copyright 2012 (C) Xeno Innovations, Inc.
 * ALL RIGHTS RESERVED
 * Author:       Damian Suess
 * Document:     kb.php - Knowledge Base
 * Created Date: Apr 18, 2012
 *
 * Description:
 *  Knowledge base class. Syntax uses WikiSyntax as its engine
 *
 * URL Examples:
 *    http://pmt/kb/<id>
 *    http://pmt/kb/?cmd=new
 *    http://pmt/kb/<id>?cmd=edit
 *    http://pmt/kb/<id>?cmd=remove
 *
 *    POST:
 *      Vote:#   - Rate article 0-5
 *
 * Change Log:
 *  2012-0618 + Ground breaking
 *
 */

require ("pmtModule.php");
class kb implements pmtModule
{
  //function __construct($uriPath = "")
  function __construct()
  {
    global $uri;

    if (count($uri->seg)>1)
          $this->_PROJ_Name = self::MODULE."/".$uri->seg[1];
    else  $this->_PROJ_Name = self::MODULE;

    // Get the segments and the mode to be used
    $this->ParseData();

    $this->_title = "Knowledge Base " . " - " . "[xenoPMT]";    // "Xeno Tracking System"
    $this->_toolbar = ""; //$this->GenerateToolbar();
    $this->_minileft = "";
    $this->_miniright = $this->GenerateMiniRight();  // "&nbsp; (test-right)";
    $this->_pagedata = $this->GeneratePage();
  }

  public function Title() { return $this->_title; }             /* Title of the generated page */
  public function Toolbar() { return $this->_toolbar; }         /* Toolbar - HTML generated toolbar according to location */
  public function MiniBarLeft() { return $this->_minileft; }
  public function MiniBarRight() { return $this->_miniright; }
  public function PageData() { return $this->_pagedata; }
}

?>
