<?php
/************************************************************
* Copyright 2010 (C) Xeno Innovations, Inc.
* ALL RIGHTS RESERVED
* Author:        fuct
* Document:      wnd_customer
* Created Date:  Nov 4, 2010, 11:36:49 PM
* Description:
*
***********************************************************/


// are we logged in?
include_once "security.php";
$class = new pmtSecurity();
define("CLS_SECURITY", $class->isUserOffline());   // So we can access it inside of functions


if (CLS_SECURITY != 0)
{
  
?>
<div style="margin: 20px;">
  <div>
    <h1>xiPMT Customer Editor</h1>
  </div>

  <ul>
    <li>Create Project Spaces</li>
    <li>Administrate existing Projects 'config.php'</li>
    <li>Setup Users</li>
    <ul>
      <li>Assign Project Managers</li>
      <li>Set Developers</li>
      <li>Allow general public to view or not</li>
      <li>Permit self-contained users (not attached to Customers)</li>
      <li>Assign SVN Repo</li>
      <li>Assign Project physical path on the web</li>
      <li>Setup Project Database and Details</li>
    </ul>
    <li>And much more..</li>
  </ul>

</div>
<?php

}else{

?>
  <div style="margin: 20px;">
    <div>
      <h1>xiPMT Customer Editor</h1>
    </div>
    <p>
      Editor options coming soon.
    </p>

  </div>


<?php


}

?>