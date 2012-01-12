<?php
/************************************************************
* Copyright 2010 (C) Xeno Innovations, Inc.
* ALL RIGHTS RESERVED
* Author:        fuct
* Document:      wnd_product
* Created Date:  Nov 4, 2010, 11:37:10 PM
* Description:
*
***********************************************************/
// are we logged in?
include_once "security.php";
$class = new pmtSecurity();
define("CLS_SECURITY", $class->isUserOffline());   // So we can access it inside of functions


if (CLS_SECURITY != 0) {
?>

<div style="margin: 20px;">
  <div>
    <h1>xiPMT Product Editor</h1>
  </div>

  <ul>
    <li>More options...</li>
    <ul>
      <li>...</li>
      <li>...</li>
    </ul>
    <li>And much more..</li>
  </ul>

</div>

<?php  }else{ ?>

  <div style="margin: 20px;">
    <div>
      <h1>xiPMT Product Editor</h1>
    </div>
    <p>
      Editor options coming soon.
    </p>

  </div>

<?php } ?>

