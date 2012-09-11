<?xml version="1.0" encoding="UTF-8"?>

<!--
    Document   : ticket-view.xsl
    Created on : September 11, 2012, 11:17 AM
    Author     : Damian Suess
    Description:
        Ticket information overview
-->

<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform" version="1.0">
  <xsl:output method="html"/>

  <!-- TODO:
    <div class="header"
      Ticket: <ticket_Number />           Status: {_status} / Type: {_type}
      {_subjet}_____________________________________________________________
      Created Dttm:   {dttm}                Mod DTTM:   {dttm}
      Reported by:    {anonymous}           Owned by:   {userName} ({group})
      Priority:       {_severity}
      Pro(ject/uct):  {_pro_id}             Pro Ver:    {_pro_ver_id}
      Component:      {component1}          Comp Ver:   {_component_ver}
      Milestone:  {_milestone}
      Cc:
      Description___________________________________________________________
         syntax recommendation http://www.w3.org/TR/xslt
      ~~~~~~~~~~~~~~~~~~~~~~~
      <div (hideable)> {attachments} </div>
    </div>

    <div class="resolution" (hidable)>
      Resolution Type: {ticket_resolution}  Res Dttm: {dttm}
      Resolution Detals:___________
    </div>

    <div id="diary" class="collapsed">
    </div>

    <div id="newdiary" class="collapsed">
    </div>
  -->
  <xsl:template match="/">

    <div class="overview-main" style="background:#FFD;">

      <table class="details" style="border-top:1px solid #DD9; border-collapse:collapse; table-layout:fixed; width:100%;">
        <tr>
          <td></td>
        </tr>
      </table>

      <div class="complaint">
        <table>
        </table>
      </div>

      <div class="attatchment">
        <table boarder="0">

          <xsl:for-each select="ticket/attachment">
            <tr>
            <td><xsl:value-of select="title" /></td>
            <td><xsl:value-of select="path" /></td>
            </tr>
          </xsl:for-each>
        </table>
      </div>
    </div>

    <div id="diary" class="collapsed">
      <table>
        <xsl:for-each select="ticket/diary">

        </xsl:for-each>
      </table>
    </div>

    <div id="newdiary" class="collapsed">

    </div>

  </xsl:template>
</xsl:stylesheet>
