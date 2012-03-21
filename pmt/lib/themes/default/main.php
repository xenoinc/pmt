<!--
  Copyright 2012 Xeno Innovations, Inc.
  This is a sample of the main dashboard "default" template
  currently based upon Trac13 for its clean looks
-->
<!DOCTYPE html
    PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">
  <head>
    <?php themeHeader(); ?>
  </head>
  <body>

    <div id="banner">
      <div id="header">
        <!-- logo -->
      </div>
      <div id="headernav">
        <!-- toolbar -->
        <!-- (Login / View Profile), Account Settings, Logout -->
      </div>
    </div>

    <div id="content" class="<php? print($pageClassType); ?>">

    </div>

    <div id="footer">

    </div>

  </body>
</html>