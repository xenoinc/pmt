<?php

/* * **********************************************************
 * Copyright 2013 (C) Xeno Innovations, Inc.
 * ALL RIGHTS RESERVED
 * @Author:       Damian Suess
 * Document:      ModuleProperties.php
 * Created Date:  Apr 11, 2013
 * Status:        {unstable/pre-alpha/alpha/beta/stable}
 * Description:
 *  Blank properties class of module info
 *
 * ToDo:
 *  [ ] Change this to a series of Get/Set so we can have default values
 *
 * Change Log:
 *
 */

namespace xenoPMT\Core\Properties
{
  class ModuleInfo
  {
    /**
     *
     * @var string Universal Uniquie Identifier of Module
     */
    public $UUID;               // String

    /**
     * Is Module apart of the xenoPMT "Core" system
     * @var boolean
     *    True = Is a Core module and cannot be uninstalled
     */
    public $IsCore = false;     // Boolean

    /**
     * Is the module automatically enabled when installed?
     * @var boolean
     *    True  = Automatically Enabled
     *    False = (default) Disable module on install
     */
    public $IsEnabled = false;  // Boolean

    public $Name;               // Title of module
    public $Version;            //
    public $Path;               // Physical path
    public $Namespace;          //
    public $Classname;          //
    public $URN;                //
    public $Description;        // Description of module

    public function __construct
        (
        $uuid, $isCore, $isEnabled,
        $title, $version, $path,
        $namespace, $classname, $urn,
        $description)
    {
      $this->UUID = $uuid;
      $this->IsCore = $isCore;
      $this->IsEnabled = $isEnabled;
      $this->Name = $title;
      $this->Version = $version;
      $this->Path = $path;
      $this->Namespace = $namespace;
      $this->Classname = $classname;
      $this->URN = $urn;
      $this->Description = $description;
      // return $this;
    }
  }
}
?>
