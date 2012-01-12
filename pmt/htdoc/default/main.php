<?php
/************************************************************
 * Copyright 2011 (C) Xeno Innovations, Inc. ALL RIGHTS RESERVED
 * Project:      xiPMT
 * Author:       Damian J. Suess
 * Document:     main.php
 * Created Date: 2011-07-25
 * Description:
 *  Main skin controller - default main body for PMT
 *
 * Change Log:
 * 
 */


?>

<!--
<span class="section">UTC clock</span>
<div class="section">
  <div class="sectionTop"> </div>
  <div class="row">23:38 on Jul 24, 2011</div>
</div>
-->
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">


  <head>
    <title>
      <?php print($pmt_proj_title); ?>
    </title>

    <!-- debug -->
    <link rel="stylesheet"  href="htdoc/pmt.css" type="text/css" />


    <link rel="search"      href="/search" />
    <link rel="start"       href="index.php" />

    <link rel="shortcut icon" href="img/page-icon.gif" type="image/gif" />
    <link rel="icon"          href="img/page-icon.gif" type="image/gif" />
    <link rel="search"
          type="application/opensearchdescription+xml"
          href="/pmt/search/opensearch"
          title="Search <?php print($pmt_proj_title); ?>" />

  </head>
  <body>
<?php
      //print(getcwd());
      //print ("wnd2" . $wnd);
?>
    <div id="banner">
      <div id="header" style="text-align:center">
        <a id="logo" href="index.php" >
          <img src="htdoc/img/xi-banner.png"
               alt="xenoinc banner"
               width="800"
               height="90" />
        </a>
      </div>
      <div id="metanav" class="nav">
        <?php Generate_MetaToolbar($cur_wnd); ?>
      </div>
    </div>
    
    <div id="mainnav" class="nav">
      <?php Generate_MainToolbar($wnd); ?>
    </div>
    
    
    <div id="main">
      <?php Generate_Main($wnd); ?>
    </div>
    
    
    <hr />
    <div id="footer" lang="en" eml:lang="en">
      <p class="left">
        Powered by <b>xiPMT v0.1</b><br />
        By <a href="http://www.xenoinc.org/">Xeno Innovations</a>.
      </p>
      &nbsp;
      <a id="xtracking" href="http://www.xenoinc.org/" target="_blank" >
        <img src="htdoc/img/xiTrac_logo_mini.png" height="30" width="107" alt="xiPMT" />
      </a>
      &nbsp;
      <p class="right">Brought to you by:<br /><a href="http://www.xenoinc.org/">Xeno Innovations, Inc.</a></p>
    </div>
  </body>
</html>

