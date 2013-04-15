<?php

/* * **********************************************************
 * Copyright 2013 (C) Xeno Innovations, Inc.
 * ALL RIGHTS RESERVED
 * @Author:       Damian Suess
 * Document:      Page
 * Created Date:  Apr 15, 2013
 * Status:        {unstable/pre-alpha/alpha/beta/stable}
 * Description:
 *  This class is intended to easily pass property/member
 *  data between the LoadModule() and LoadTheme() methods
 *  so we can properly display the module * ToDo:
 *
 * Change Log:
 *
 */

namespace xenoPMT\Core\Properties
{
  /**
   * This class is intended to easily pass property/member
   * data between the LoadModule() and LoadTheme() methods
   * so we can properly display the module
   */
  class Page
  {

    /***[ Page module members/properties ]*****/

    /**
     * Physical path to module
     * @var string
     */
    public $ModulePath;

    /**
     * Name of the module's class name to load
     * @var string
     */
    public $ModuleClass;

    /**
     * Module Namespace (if defined)
     * @var string
     */
    public $ModuleNamespace;

    /***[ Page theme members/properties ]********************/

    /**
     * Path to Icon file
     * @var string
     */
    public $Icon;
    public $Title;        // Page Title

    /**
     * Extra Header Information such as custom CSS or JavaScript
     * ** we should define "Public $CSS and $JavaScript" instead!
     * @var string
     */
    public $Ex_Header;

    /**
     * (realitive) Path to site's logo image
     * @var string
     */
    public $Logo;         // Site image path

    public $Metabar;      // User (login/usr-pref)/settings/logout/about
    public $Toolbar;      // Main toolbar
    public $MiniLeft;     // Mini-bar Left aligned (bread crumbs)
    public $MiniRight;    // Mini-bar Right aligned (module node options)
    public $HtData;       // Main page html data

    /**
     * Relative path to theme currently in use
     * TODO: Rename to "ThemePath"
     * @var string
     */
    public $Path;

    /**
     * Footer HTML code
     * @var string
     */
    public $Footer;

    /*
      protected $_unknownMembers = array();

      public function __get($argName)
      {
        if (array_key_exists($argName, $this->_unknownMembers))
          return ($this->_unknownMembers[$argName]);
        else
          return ("No such member");
      }
      public function __set($keyName, $val)
      {
        $this->_unkownMembers[$keyName] = $val;
      }
      public function __isset($argName)
      {
        return (isset($this->_unknownMembers[$argName]));
      }
    */
  }
}
?>
