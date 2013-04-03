<?php

/* * **********************************************************
 * Copyright 2013 (C) Xeno Innovations, Inc.
 * ALL RIGHTS RESERVED
 * @Author:       fuct
 * Document:      xenoPMTLibrary
 * Created Date:  Apr 3, 2013
 * Status:        {unstable/pre-alpha/alpha/beta/stable}
 * Description:
 *  This class is used to access the module's library system.
 *  The library system is a collection of procedures that allows us
 *  to access a particular module's features anywhere else in the system.
 *
 * ToDo:
 *
 * Change Log:
 *
 */
namespace xenoPMT\Core
{
  class Library extends \xenoPMT\Core\Functions
  {

    /** Library Handler */

    /**
     * Include (user/core) library if not already included
     *
     * @version v0.0.0
     * @since v0.0.7
     * @param string $libraryName
     * @return boolean true=success, false=failure to load
     */
    public static function lib_include($libraryName)
    {
      return false;
    }

    /**
     * List of core/user xpmtLibaries already included
     *
     * @version v0.0.0
     * @since v0.0.7
     * @since xenpPMT Core-0.0.5
     * @var array
     */
    private $libIncluded = array();

    /**
     * Is xpmtLibrary enabled
     * <p>Loops through $this->libIncluded[] to see if it's there</p>
     *
     * @version v0.0.0
     * @since v0.0.7
     * @param type $libName
     * @return boolean true=enabled, fales=not enabled
     */
    public static function lib_enabled($libName) { return false; }

    /**
     * Searches through Core and User folder to see if libary is available
     * to be included.
     *
     * @version v0.0.0
     * @since v0.0.7
     * @param type $libName
     * @return boolean true=found false=not found or error
     */
    public static function lib_available($libName) {return false; }

  }
}
?>
