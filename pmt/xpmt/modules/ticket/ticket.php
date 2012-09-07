<?php

/** * *********************************************************
 * Copyright 2012 (C) Xeno Innovations, Inc.
 * ALL RIGHTS RESERVED
 * @Author:      Damian Suess
 * Document:     ticket
 * Created Date: Sep 6, 2012
 *
 * Description:
 *  Ticket viwer
 *
 * Change Log:
 *
 */

class ticket implements iModule
{

  private $_title;
  private $_toolbar;
  private $_minileft;
  private $_miniright;
  private $_pagedata;
  // Internal module setup
  private $_MODE;
  private $_PAGE;
  private $_SWITCH;     // Switch: new, edit,

  function __construct()
  {
    $this->_title = "";
    $this->_toolbar = "";

    $this->_minileft = $this->GenerateMiniLeft();
    $this->_miniright = "";
    $this->_pagedata = "";

  }

  public function Title() {         return $this->_title; }       /* Title of the generated page */
  public function Toolbar() {       return $this->_toolbar; }     /* Toolbar - HTML generated toolbar according to location */
  public function MiniBarLeft() {   return $this->_minileft; }
  public function MiniBarRight() {  return $this->_miniright; }
  public function PageData() {      return $this->_pagedata; }

  /* -=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=- */

  private function GenerateMiniLeft()
  {
    global $user;

    if ($user->online != false)
    {
      $code =   "<ul>";
      $code .=  "<li>". $this->AddLink(self::MODULE, "Main", "")  ."</li>";
      $code .=  "<li class='last'>". $this->AddLink(self::MODULE, "List Articles", "?cmd=".self::cLIST)  ."</li>";
      //$code .=  "<li class='last'>". $this->AddLink(self::MODULE, "KB Search", "?cmd=search")  ."</li>";
      $code .=  "</ul>";

      return $code;
    }
    else
      return "";
  }

}

?>
