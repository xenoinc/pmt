<?php

/* * **********************************************************
 * Copyright 2013 (C) Xeno Innovations, Inc.
 * ALL RIGHTS RESERVED
 * @Author:       fuct
 * Document:      ModuleSetupError
 * Created Date:  Apr 11, 2013
 * Status:        {unstable/pre-alpha/alpha/beta/stable}
 * Description:
 *  Properties holder for Module Setup Errors
 * ToDo:
 *
 * Change Log:
 *
 */
namespace xenoPMT\Core\Properties
{
  class ModuleSetupError
  {
    public $CoreInvalid;
    public $IsInstalled;
    public $URN_Conflict;
    public $UUID_Conflict;
    public $DbConnect_Failed;
    public $DbQuery_Failed;

    public function __construct()
    {
      $this->CoreInvalid = false;
      $this->IsInstalled = false;
      $this->URN_Conflict = false;
      $this->UUID_Conflict = false;
      $this->DbConnect_Failed = false;
      $this->DbQuery_Failed = false;
    }
  }
}
?>
