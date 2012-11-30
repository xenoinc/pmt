<?xml version="1.0" encoding="UTF-8"?>

<!--  * **********************************************************
  Copyright 2012 (C) Xeno Innovations, Inc.
  ALL RIGHTS RESERVED
  Author:       Damian Suess
  Document:     kb-main.xsl
  Created Date: 2012-11-25

  Description:


  Change Log:

-->

<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform" version="1.0">
  <xsl:output method="html"/>

  <!-- TODO customize transformation http://www.w3.org/TR/xslt -->

  <xsl:template match="/">
    <div id="kbMainBody">

      <div id="idSearch">
        <b>Search:</b><br />
        <input type="text" name="txtSearch" value="enter search text" />
        <input type="submit" name="btnSearch" />
      </div><!-- end:#idSearch -->

      <div id="idTop5Rated">
        <ul>
          <li></li>
        </ul>
      </div><!-- end:#idTop5Rated -->

      <div id="idTop5New">
        <ul>
          <li></li>
        </ul>
      </div><!-- end:#idTop5Rated -->


    </div><!-- end:#kbMainBody -->
  </xsl:template>

</xsl:stylesheet>
