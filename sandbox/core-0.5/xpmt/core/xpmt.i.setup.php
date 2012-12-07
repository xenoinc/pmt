<?php

/** * *********************************************************
 * Copyright 2012 (C) Xeno Innovations, Inc.
 * ALL RIGHTS RESERVED
 * @Author:       Damian Suess
 * Document:      xpmt
 * Created Date:  Dec 6, 2012
 * Status:        {unstable/pre-alpha/alpha/beta/stable}
 * Description:
 *  Make sure the module setup classes have the right
 *  components.
 *
 * Change Log:
 *  2012-1206 * ground breaking
 *            * Made the Interface inside of the xenoPMT namespace for safty purposes
 */
namespace xenoPMT\Module
{
  interface ISetup
  {

    /**
     * Verify if there are any pre-installation errors
     *
     * @global array $xpmtCore
     * @global array $xpmtModule
     *
     * @param array &$arrChkPoints  Reference to install checkpoints
     * Each associative array item contains a boolean value
     * <ul>
     *  <li><b>CoreInvalid</b>    - Is module valid with this verion of xenoPMT core (xpmtCore["info"]["version_ex"])</li>
     *  <li><b>IsInstalled</b>    - Is module previously installed?</li>
     *  <li><b>URN_Conflict</b>   - Is URN previously used by other modules?</li>
     *  <li><b>UUID_Conflict</b>  - Is this UUID previously included in $xpmtModule[][]? (and also database v0.0.7)</li>
     * </ul>
     *
     * @return boolean Inspection failed?  (true=fail, false=pass)
     */
    public function PreInstallErrors(&$arrChkPoints);

    /**
     * Install module
     */
    public function Install();

    /**
     * Uninstall module
     */
    public function Uninstall();

  }
}
?>
