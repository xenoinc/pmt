<?php
/* * **********************************************************
 * Copyright 2012 (C) Xeno Innovations, Inc.
 * ALL RIGHTS RESERVED
 * Author:       Damian Suess
 * Document:     pmtModule.php
 * Created Date: Mar 22, 2012
 *
 * Description:
 *  Interface to what modules must contain.
 *
 * Change Log:
 *  2012-0619 + Added Install, Uninstall, Init functions. (NOT Implemented Yet)
 *  2012-0322 + Initial creation
 */

/**
 *
 * @author Damian Suess
 */
interface pmtModule
{
  /**
   * Generates the page data for the module
   */
  public function PageData();

  /**
   * Title of the generated page
   */
  public function Title();

  /**
   * User's setup toolbar
   * [1] Login \ Setup
   * [2] Welcome, <user> \ Preferences \ Logout
   */
  //public function Metabar();

  /**
   * Toolbar - HTML generated toolbar according to location
   */
  public function Toolbar();

  /**
   * Mini toolbar left aligned
   * Can be used for module breadcrumbs
   */
  public function MiniBarLeft();

  /**
   * Mini toolbar right aligned
   * Can be used for module options and node settings
   * i.e. Wiki: Edit, History, Remove, Rename
   */
  public function MiniBarRight();

  // public function Install();       // Install module (create components and tables if needed)
  // public function Uninstall();     // Uninstall this module (remove tables and settings if needed)
  // public function Initialize();    // initialize data (basically the construct)


  /*

  private function GenerateToolbar()
  {
    // List of all the available modules
    // ** This should be pulled from DB depending on user/group
    //    permissions & settings!!
    $arrAvailMods = array(
          // Module       Display
          "dashboard" => "Dashboard",
          "project"   => "Projects",
          "ticket"    => "Tickets",
          "bugs"      => "Bugs",
          "tasks"     => "Tasks",
          "product"   => "Products",
          "customer"  => "Customers",
          "user"      => "Users",
          "admin"     => "Admin"
          );

    $tab = "        ";
    $ret = $tab . "<ul>". PHP_EOL;
    $ndxCount = 0;
    //print (count($a));
    foreach($arrAvailMods as $key => $value)
    { //print ("key: $key, Obj: $value <br />");

      $ndxCount++;
      if ($ndxCount == 1)
        $cls = ' class="first"';
      elseif($ndxCount == count($arrAvailMods))
        $cls = ' class="last"';
      else
        if ($key==$this->MODULE)$cls = ' class="active"'; else $cls = '';

      $ret .= $tab .
              "  <li" . $cls. ">" .
              $this->makeLink($key, $value) .
              "</li>" . PHP_EOL;

    }
    $ret .= $tab . "</ul>". PHP_EOL;
    //pmtDebug("disp: " . $ret);
    return $ret;
  }

  */

}

?>
