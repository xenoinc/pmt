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
 * Note:
 *  This will only work with newer themes (v0.0.7). Not the older ones
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
    private $_modPath;
    private $_modClass;
    private $_modNamespace;

    /***[ Page module members/properties ]*****/

    /**
     * Physical path to module
     * @var string
     */
    public function ModulePath()
    {
      $numArgs = func_num_args();
      if ($numArgs > 0)
      { // Set
        // echo "Second argument is: " . func_get_arg(1) . "<br />\n";

        $argArr = func_get_args();
        $this->_modPath = $argArr[0];

        return;
        // for ($i = 0; $i < $numArgs; $i++) {
        //  echo "Argument $i is: " . $argArr[$i] . "<br />\n";
        // }
      }
      else
      { // Get
        return $this->_modPath;
      }
    }

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

    /***[ Page Location ]*********************/

    /**
     * Universal Resource Name (kb, ticket, user, admin, ..)
     * @var string
     */
    public $URN;

    /***[ Page theme members/properties ]********************/

    /**
     * Path to Icon file
     * @var string
     */
    public $Icon;

    /**
     * Page Title.
     * This is what will go inside of the <title>...</title> tag
     * @var string
     */
    public $Title;

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
    public $Logo;

    public $Metabar;      // User (login/usr-pref)/settings/logout/about
    public $Toolbar;      // Main toolbar
    public $MiniLeft;     // Mini-bar Left aligned (bread crumbs)
    public $MiniRight;    // Mini-bar Right aligned (module node options)

    /**
     * Body of the page to display
     * (possibly rename to "HTML_Body" or "HTMLBody")
     * @var string
     */
    public $HTDATA;       // Main page html data

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

    /**
     * Input default settings
     */
    public function __construct()
    {
      $this->Footer = '<img src="https://www.ohloh.net/p/pmt/widgets/project_thin_badge.gif" />';
    }

    /**
     * Show the body of our selected theme. This will automatically
     * fill out our page.
     */
    public function DisplayPage()
    {
      require($this->HTData);
    }

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
