<?php

/** * *********************************************************
 * Copyright 2012 (C) Xeno Innovations, Inc.
 * ALL RIGHTS RESERVED
 * @Author:      Damian Suess
 * Document:     index.php
 * Created Date: Oct 4, 2012
 *
 * Description:
 *  Installer (v0.0.5)
 *
 * Change Log:
 *  2012-1004 + Initial creation
 */

/* Steps
 * 0. Check for Request DB Clear
 *  i. Get config(.user).php data of DB conn & remove
 *
 * 1. Display database information form
 *  i. Get Server, User, Pass, DB_Name and Table Prefix
 *
 * 2. Display Admin Config form
 *  i.  user, pass, email and display name
 *  ii. and Use Clean URI (checkbox)
 *
 * 3. Install xenoPMT
 *  i.    Save config file
 *  ii.   Write generic database
 *  iii.
 *
 */


?>

<html>
  <head>
    <script type="text/javascript">
      function showUser(str)
      {
        if (str=="")
        {
          document.getElementById("txtHint").innerHTML = "";
          return;
        }
      }
    </script>
  </head>
  <body>

  </body>
</html>