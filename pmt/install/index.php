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

$step = (isset($_POST['step']) ? $_POST['step'] : 1);

switch ($step)
{
  /**
   * Welcome screen
   */
  case 1:
    CreateHeader("Step 1", $step);

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
        <pre id="license">
          <?php echo htmlentities(file_get_contents("../license.txt")) ?>
        </pre>
        <div id="action" align="center">
          <input type="submit" value="Accept" />
        </div>
      </form>
      <?php

    }  
    break;
  
  /**
   * 
   */
  case 2:
    
    CreateHeader("Step 2");
    
    if(isset($_POST["db"]) == false)
    {
      // There is no db connetion info. Let get some
      if(file_exists("../lib/config.php"))
      {
        require_once "../lib/config.php";
      }
        ?>
        <form action="index.php" method="post">
          <input name="step" type="hidden" value="<?php print($step); ?>" />
          <h2>Input Database Information</h2>
          <table class="inputForm">
            <tr>
              <td class="label">Server:</td>
              <td><input</td>
            </tr>
          </table>
        </form>
        <?php
    }
    else
    {
      // We have DB Connection Information
      $fail = false;
      $conn = mysql_connect($_POST["db"]["server"],
                            $_POST["db"]["user"],
                            $_POST["db"]["pass"]);

    }
    break;
  
  
  /**
   *Something didn't happen right 
   */
  default:
    
    
    break;
}

  CreateFooter();

?>
