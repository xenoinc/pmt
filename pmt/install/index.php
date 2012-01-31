<?php

/* * **********************************************************
 * Copyright 2012 (C) Xeno Innovations, Inc.
 * ALL RIGHTS RESERVED
 * Author:       suessdam
 * Document:     index
 * Created Date: Jan 30, 2012
 * 
 * Description:
 *  Setup database script
 *
 * Change Log:
 * 
 */

require_once "installer.php";
require_once "../lib/common/pmt.db.php";

$step = (isset($_POST["step"]) ? $_POST["step"] : 1);


if ($step == 1)
{
  
  CreateHeader("install", $step);
  
  if (IsInstalled())
  {
    htmlMessage("xiPMT is already Installed");  
  }
  elseif(file_exists("../lib/config.php"))
  {
    htmlMessage("<code>system/config.php</code> exists, unable to continue.");
  }
  else
  {
    ?>
    <form action="index.php" method="post">
      <input type="hidden" name="step" value="<?php echo($step+1); ?>" />
      <h2>License Agreement</h2>
      <pre id="license"><?php echo htmlentities(file_get_contents("../license.txt")) ?></pre>
      <div id="action" align="center">
        <input type="submit" value="Accept" />
      </div>
    </form>
    <?php
    
    CreateFooter();
    
  }
}

?>
