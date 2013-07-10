<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
  <!--xmlns:xs="http://www.w3.org/2001/XMLSchema"
  xmlns:fn="http://www.w3.org/2005/xpath-functions"> -->

  <xsl:output method="html" />
  <xsl:variable name="lt">&lt;</xsl:variable>
  <xsl:variable name="gt">&gt;</xsl:variable>
  <xsl:variable name="lcletters">abcdefghijklmnopqrstuvwxyz</xsl:variable>
  <xsl:variable name="ucletters">ABCDEFGHIJKLMNOPQRSTUVWXYZ</xsl:variable>

  <xsl:template match="/InquiryInfo">
    <xsl:variable name="countit" select="count(applixinquiry)" />

    <style type="text/css">
      HR.break {page-break-after: always}

      TD {
        padding-left: 1px;
        padding-right: 2px;
      }
      TABLE.inquiry {
        border:solid black 1px;
        margin-bottom:5px;
        width:100%;
      }

      DIV.diary-body {
        padding-top: 2px;
        padding-left: 15px;
      }

      DIV.diary { padding-bottom: 5px; }

      DIV.titlepage {
        border:solid black 1px;
        width:98%;
        margin-left: auto;
        margin-right:auto;
        page-break-after: always;
      }

      @media screen {
        DIV.titlepage
        {
          display: none;
        }
      }

      @media print {
        H1.firstHeading { display:none; }
        H3#siteSub { display:none; }
        TABLE.inquiry { width:99%; }
      }
    </style>

    <div class="noprint">
      <xsl:choose>
        <xsl:when test="InquiryCount != 1">
          <xsl:call-template name="navigation-block"/>
        </xsl:when>
      </xsl:choose>
    </div>

    <xsl:choose>
      <xsl:when test="InquiryCount > 1">
        <div class="titlepage">
          <h1 align="center">Applix Inquiry Report</h1>
          <table style="width:100%;">
            <tr>
              <th valign="top" colspan="2">Inquiry ID</th>
              <th valign="top">Site</th>
              <th valign="top">Status</th>
              <th valign="top">Product</th>
              <th valign="top">Age (days)</th>
            </tr>

            <xsl:for-each select="applixinquiry">
              <tr>
                <td valign="top"><xsl:value-of select="position()" /></td>
                <td valign="top"><xsl:value-of select="inquiry_id" /></td>
                <td valign="top"><xsl:value-of select="account/name" />(<xsl:value-of select="account/ext_account_id" />)</td>
                <td valign="top"><xsl:value-of select="status" /></td>
                <td valign="top"><xsl:value-of select="complaint_product" /></td>
                <td valign="top"><xsl:value-of select="age"/></td>
              </tr>
            </xsl:for-each>
          </table>
          <hr />
          <table style="width:100%;">
            <tr >
              <td width="50%">Total Inquiries: <xsl:value-of select="$countit" /></td>
              <td width="50%" align="right">Printed on: <xsl:value-of select="CurrentDate"/></td>
            </tr>
          </table>
        </div>
      </xsl:when>
    </xsl:choose>


    <xsl:for-each select="applixinquiry">
      <div style="float:right;font-size:x-small;">
        <div style="margin: 3px; line-height:10px;">
          Inquiry
          <xsl:value-of select="position()" />
          of
          <xsl:value-of select="$countit" />
        </div>
        <div style="margin: 0px;line-height:10px;">
          printed <xsl:value-of select="/InquiryInfo/CurrentDate" />
        </div>
      </div>
      <h3 style="margin-bottom:5px;">
        Applix Inquiry
        <xsl:value-of select="inquiry_id" />
      </h3>

      <table class="inquiry" cellspacing="0">
        <tr>
          <td bgcolor="#99CCCC" align="right">
            <b>Site:</b>
          </td>
          <td colspan="3">
            <xsl:value-of select="account/name" />
          </td>
          <td bgcolor="#99CCCC" align="right">
            <b>Cust Num:</b>
          </td>
          <td>
            <xsl:value-of select="account/ext_account_id" />
          </td>
        </tr>
        <tr>
          <td bgcolor="#99CCCC" align="right">
            <b>Status:</b>
          </td>
          <td>
            <xsl:value-of select="status" />
            (
            <xsl:value-of select="closed_dt" />
            )
          </td>
          <td bgcolor="#99CCCC" align="right">
            <b>Product:</b>
          </td>
          <td>
            <xsl:value-of select="complaint_product" /> (<xsl:value-of select="complaint_version" />)
          </td>
          <td bgcolor="#99CCCC" align="right">
            <b>Asset Model:</b>
          </td>
          <td>
            <xsl:value-of select="asset/model" />
          </td>
        </tr>
        <tr>
          <td bgcolor="#99CCCC" align="right">
            <b>Transfer To:</b>
          </td>
          <td>
            <xsl:call-template name="rep-href">
              <xsl:with-param name="rep"
                            select="transfer_rep" />
            </xsl:call-template>
            (
            <xsl:value-of select="transfer_dt" />
            )
          </td>
          <td bgcolor="#99CCCC" align="right">
            <b>Priority:</b>
          </td>
          <td>
            <xsl:value-of select="priority" />
          </td>
          <td bgcolor="#99CCCC" align="right">
            <b>Contact:</b>
          </td>
          <td>
            <xsl:value-of select="anonymous_contact" />
          </td>
        </tr>
        <tr>
          <td bgcolor="#99CCCC" align="right">
            <b>Logged:</b>
          </td>
          <td>
            <xsl:value-of select="logged_dt" />
          </td>
          <td bgcolor="#99CCCC" align="right">
            <b>Call Back:</b>
          </td>
          <td>
            <xsl:call-template name="date-format">
              <xsl:with-param name="date-time" select="callback_dt" />
            </xsl:call-template>
          </td>
          <td bgcolor="#99CCCC" align="right">
            <b>VI:</b>
          </td>
          <td>
            <xsl:call-template name="vi-href">
              <xsl:with-param name="vi"
                            select="bugvi/vi_incident_id" />
            </xsl:call-template>
          </td>
        </tr>
        <tr>
          <td bgcolor="#99CCCC" align="right">
            <b>Attachments:</b>
          </td>
          <td colspan="5">
            <xsl:variable name="fileurl">file:///</xsl:variable>
            <xsl:for-each select="attachment">
              <xsl:variable name="filename" select="object_source"/>
              [<a href="{concat($fileurl, $filename)}"><xsl:value-of select="source_name" /></a>]
              <xsl:choose>
                <xsl:when test="position() mod 4 = 0">
                  <p></p>
                </xsl:when>
              </xsl:choose>
            </xsl:for-each>
          </td>
        </tr>
      </table>

      <xsl:for-each select="diary">
        <xsl:sort select="create_dt" />
        <div class="diary">

          <b><xsl:value-of select="topic" /></b>

          by
          <xsl:call-template name="rep-href">
            <xsl:with-param name="rep" select="create_rep" />
          </xsl:call-template>
          on
          <xsl:call-template name="date-format">
            <xsl:with-param name="date-time" select="create_dt" />
          </xsl:call-template>
          <xsl:choose>
            <xsl:when test="topic = 'Transferred'">
              to
              <b>
                <xsl:call-template name="rep-href">
                  <xsl:with-param name="rep" select="transfer_rep" />
                </xsl:call-template>
              </b>
            </xsl:when>
          </xsl:choose>
          <xsl:choose>
            <xsl:when test="
								topic != 'Closed'
						and topic != 'Transferred'
						and topic != 'Added To-Do'
						and topic != 'Reopened'">
              <div class="diary-body">
                <xsl:value-of disable-output-escaping="yes" select="diary_txt" />
              </div>
            </xsl:when>
          </xsl:choose>
        </div>
      </xsl:for-each>
      <xsl:choose>
        <xsl:when test="not(position() = $countit)">
          <hr class="break" />
        </xsl:when>
      </xsl:choose>

    </xsl:for-each>

  </xsl:template>

  <xsl:template name="date-format">
    <xsl:param name="date-time"/>
    <xsl:param name="year" select="substring-before($date-time, '-')"/>
    <xsl:param name="rest" select="substring-after($date-time, '-')"/>
    <xsl:param name="month" select="substring-before($rest, '-')"/>
    <xsl:param name="rest2" select="substring-after($rest, '-')"/>
    <xsl:param name="day" select="substring-before($rest2, ' ')"/>
    <xsl:param name="rest3" select="substring-after($rest2, ' ')"/>
    <xsl:param name="hour" select="substring-before($rest3, ':')"/>
    <xsl:param name="rest4" select="substring-after($rest3, ':')"/>
    <xsl:param name="minute" select="substring-before($rest4, ':')"/>
    <xsl:value-of select="concat($month, '/', $day, '/', $year, ' ', $hour, ':', $minute)" />
  </xsl:template>
  <xsl:template name="vi-href">
    <xsl:param name="vi" />
    <a>
      <xsl:attribute name="href">
        <xsl:text>index.php?title=Special:VIIncident/</xsl:text><xsl:value-of select="$vi"/>
      </xsl:attribute>
      <xsl:value-of select="$vi"/>
    </a>
  </xsl:template>
  <xsl:template name="rep-href">
    <xsl:param name="rep" />
    <a>
      <xsl:attribute name="href">
        <xsl:text>index.php?title=Special:AutomationContact/</xsl:text><xsl:value-of select="$rep"/>
      </xsl:attribute>
      <xsl:value-of select="translate($rep,$ucletters,$lcletters)"/>
    </a>
  </xsl:template>
  <xsl:template name="navigation-block">
    <table style="border:solid black 1px;" class="wikitable">
      <tr>
        <td>Inquiries:</td>
        <xsl:variable name="inputArray" select="InquiryParmAll/InqArray"/>
        <xsl:variable name="concatHref" select="concat('index.php?title=Special:ApplixInquiry/', $inputArray)"/>
        <xsl:variable name="concatALL" select="concat($concatHref, '&amp;inq=')"/>
        <xsl:for-each select="InquiryParmInput">
          <xsl:variable name="inputinquiry" select="InputInq"/>
          <td>
            <a href="{concat($concatALL, $inputinquiry)}"><xsl:value-of select="InputInq"/></a>
          </td>
          <xsl:choose>
            <xsl:when test="position() mod 12 = 0">
              <tr></tr>
              <td></td>
            </xsl:when>
          </xsl:choose>
        </xsl:for-each>
        <td>
          <a href="{concat($concatHref, ',ALL')}">ALL</a>
        </td>
      </tr>
    </table>
  </xsl:template>
</xsl:stylesheet>
