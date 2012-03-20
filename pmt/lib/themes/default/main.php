<!--
  Copyright 2012 Xeno Innovations, Inc.
  This is a sample of the main dashboard "default" template
  currently baised upon Trac13
-->
<!DOCTYPE html
    PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
    
<html xmlns="http://www.w3.org/1999/xhtml"
      xmlns:xi="http://www.w3.org/2001/XInclude"
      xmlns:py="http://genshi.edgewall.org/"
      xmlns:i18n="http://genshi.edgewall.org/i18n">
  <head>
    <?php themeHeader(); ?>
  </head>
  <body>
  
    <div id="banner">
      <div id="header">
      </div>
      <div id="headernav">
    </div>
    
    <div id="content" class="<php? print($pageClassType); ?>">
      
    </div>

    <div id="footer">
      
    </div>
    
  </body>
</html>