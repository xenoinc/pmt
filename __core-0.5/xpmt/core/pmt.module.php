<?php
/** **********************************************************
 * @copyright (c) 2012, Xeno Innovations, Inc.
 * ALL RIGHTS RESERVED
 * @author        Damian Suess
 * Document:      pmt.module.php
 * Created Date:  2012-07-23
 * Version:       0.0.5
 *
 * Description:
 *  Basic model for Modules
 *
 * To Do:
 * [ ] 2012-1029 + Add private/public variables? or let $xpmtPage/Module[] handle this?
 *
 * Change Log:
 *  2012-1029 + Added notes in Toolbar() - Auto generate tbar based upon user/group settings
 *            + Added Footer() - Still needs code to implement it
 *  2012-0723 * Added basic functionality
 ***********************************************************/
// namespace \xenoPMT\Module { class Raw BasicStructure { } }  ;  class myMod extends \xenoPMT\Module\Raw { }
// namespace \xenoPMT { class Module { } }  ;  class myMod extends \xenoPMT\Module { }  <--- this one
require_once ("pmt.i.module.php");

class pmtModule implements \xenoPMT\Module\iModule
{

  /**
   * 2012-1029
   * Add variables here?
   */

  /**
   * Install/Uninstall Module
   * Actions to perform when installing or uninstalling module
   *
   * @param bool $inst_mod Install the module or Uninstall Module
   * @return boolean Success True if no error encountered
   */
  public function Install(bool $inst_mod)
  {
    // Empty, user must define the actions to take
    if($inst_mod) { } else { }

    return true;
  }

  /**
   * Enable or Disable Module
   * Actions to perform when enabling or disabling module
   *
   * @param bool $enable_mod Enable or Disable the module
   * @return boolean Success True if no error encountered
   */
  public function Enabled(bool $enable_mod)
  {
    // Empty, user must define the actions to take
    if($enable_mod) { } else { }

    return true;
  }

  // -----------------------

  /**
   * Title of the generated page
   */
  public function Title()
  {
    return "";
  }

  /**
   * User's setup toolbar
   * [1] Login \ Setup
   * [2] Welcome, <user> \ Preferences \ Logout
   */
  //public function Metabar();

  /**
   * Toolbar - HTML generated toolbar according to location
   */
  public function Toolbar()
  {
    // 2012-1029
    // pre-Generate toolbar based upon user/group settings in DB
    return "";
  }

  /**
   * Mini toolbar left aligned
   * Can be used for module breadcrumbs
   */
  public function MiniBarLeft()
  {
    return "";
  }

  /**
   * Mini toolbar right aligned
   * Can be used for module options and node settings
   * i.e. Wiki: Edit, History, Remove, Rename
   */
  public function MiniBarRight()
  {
    return "";
  }

  /**
   * Generates the page data for the module
   */
  public function PageData()
  {
    return "";
  }

  /**
   * Override page footer here
   */
  public function Footer()
  {
    return "";
  }

}

?>
