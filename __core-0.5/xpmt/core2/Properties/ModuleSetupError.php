<?php

/* * **********************************************************
 * Copyright 2013 (C) Xeno Innovations, Inc.
 * ALL RIGHTS RESERVED
 * @Author:       Damian Suess
 * Document:      ModuleSetupError
 * Created Date:  Apr 11, 2013
 * Status:        alpha {unstable/pre-alpha/alpha/beta/stable}
 * Description:
 *  Properties holder for Module Setup Errors
 *
 * ToDo:
 *
 * Change Log:
 *  2013-0415 + Added funciton "ClearErrors()" so that we can quickly clear any errors
 *              on the fly when using the class.
 */
namespace xenoPMT\Core\Properties
{
  /**
   * Properties holder for Module Setup Errors
   */
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
      // Cleanup errors
      $this->ClearErrors();
    }

    /**
     * Clear any existing error flags which may have been previously triggered
     * @since 2013-04-15
     */
    public function ClearErrors()
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
