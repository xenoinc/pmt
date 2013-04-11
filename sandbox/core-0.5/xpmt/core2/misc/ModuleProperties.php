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

namespace xenoPMT\Core\Misc
{
  class ModuleProperties
  {
    public $UUID;               // String
    public $IsCore = false;     // Boolean
    public $IsEnabled = false;  // Boolean
    public $Name;               // Title of module
    public $Version;            //
    public $Path;               // Physical path
    public $Namespace;          //
    public $Classname;          //
    public $URN;                //
    public $Description;        // Description of module
  }
}
?>
