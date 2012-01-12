<?php
  /* Copyright (C) 2011 Xeno Innovations, Inc.
   * Created By:    Damian J. Suess
   * Created Date:  4/18/2011 8:27:35 PM
   * File Name:     xr1-check.php
   * Description:
   *  Performs a multiple functions
   *  1)  Verify that the user's software is registered
   *  2)  Check if there are any system updates
   *
   * Notes:
   *  gz - http://php.net/manual/en/function.gzcompress.php
   *
   * Modifications:
   * 2011-0418  + [djs] Initial creation
   * 
   */
  
  /* System Parameters passed in URL
   * -----------------
   * Function  'f'
   *  + "checku" = Check for updates
   *    - SubKey = "sid" = Site Id
   *  + "checkv" = Verify we can use software
   *    - SubKey
   */

  $_type = $_GET['f'];      // Functionaly to perform
  /*  ## Unused parameters at the moment ##
  $_sub1 = $_GET["sid"];    // Site Customer ID                               - Is this customer verified
  $_sub2 = $_GET["m"];      // Machine ID (MAC laced ID)                      - Verify if this machine is allowed updates
  $_sub3 = $_GET["com"];    // Select specific Component by Name              - Core, ROM, PIE2, QPTI2
  $_sub4 = $_GET["b"];      // Major.Minor Branch version of requesting app   - "0.2" - standard, "1.0b" - special
  */
  switch ($_type)
  {
    // Check for updates
    case "checku":
      $_subB = $_GET["bb"];     // Beta Verification Code     - Is site allowed beta upgrades?

      // Step 1 :: Perform Site Checkup against DB to pull back version info


      // Step 2 :: Check if they are allowed beta or not
      if ($_subB == "h4x0r")
      {
        // Get latest stable version

        $rr = "<?xml version=\"1.0\" encoding=\"utf-8\" ?>" . "\n" .
              "<xrehab>" . "\n" .
              "<VersionHex>0x00020000</VersionHex>"."\n" .
              "<Version32>33621248</Version32>"."\n" .
              "<VersionDisplayString>0.2</VersionDisplayString>" ."\n" .
              "</xrehab>";

        print(gzcompress($rr, 9));

      }else{

        // Get latest beta version
        $rr = "<?xml version=\"1.0\" encoding=\"utf-8\" ?>" . "\n" .
              "<xrehab>" . "\n" .
              "<VersionHex>0x00020000</VersionHex>"."\n" .
              "<Version32>33621248</Version32>"."\n" .
              "<VersionDisplayString>0.2</VersionDisplayString>" ."\n" .
              "</xrehab>";

        print(gzcompress($rr, 9));
      }


      break;


    // Verify user account
    case "checkv":
      
      break;
    
    
  }


?>

