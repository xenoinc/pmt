<?php

/* * **********************************************************
 * Copyright 2012 (C) Xeno Innovations, Inc.
 * ALL RIGHTS RESERVED
 * Author:       Damian Suess
 * Document:     project-ext
 * Created Date: Apr 11, 2012
 *
 * Description:
 *  Extension of the Project class so we can clearly
 *  generate pages.
 *
 * Use:
 *  // Since we declared everything "static". Call the members using, ::
 *  requre("ext/project.ext.php");
 *  $ret = ProjExt::MemberName();
 *
 * Change Log:
 *  2012-0424 + Moved Page_ProjectNew here
 */

class ProjExt
{
  //private static function IsDate($string)
  #accepts a date, and a date format (not a regular expression)
  //private static function isDate($d, $f='%d-%m-%Y %H:%M')
  private static function IsDate($d, $f='%Y-%m-%d')
  {
    $dateFmtRE = array(
      '/\//' => '\/',
      '/%g|%G|%y|%Y/' => '(19\d\d|20\d\d)',
      '/%m/' => '(0?[1-9]|1[012])',
      '/%d|%e/' => '(0?[1-9]|[12][0-9]|3[01])',
      //'/%H|%I|%l/' => '([0-1]?\d|2[0-3])',
      //'/%M/' => '([0-5]\d)'
      );
    if (empty($d))  return true;

    #convert Unix timestamp to a std format (must not include regular expressions)
    if (preg_match('!\d{5,}!', $d))
      $d = strftime($f, $d);

    return (
      #does %d match the regular expression version of $f? if it does m/d/y are in $x
      preg_match('!^' .preg_replace(array_keys($dateFmtRE), array_values($dateFmtRE),$f) .'$!', $d, $x)
      && (checkdate($x[2], $x[1], $x[3]) || checkdate($x[1], $x[2], $x[3]) || checkdate($x[3], $x[1], $x[2]))
      ?true :false);
  }

  private static function IsDate2($string)
  {

    $t = strtotime($string);
    $m = date('m',$t);
    $d = date('d',$t);
    $y = date('Y',$t);
    return checkdate ($m, $d, $y);
  }

  static function Page_ProjectNew()
  {

    $styProjName="";    $prevProjName="";
    $styCreatedDTTM=""; $prevCreatedDTTM="";
    $styDescription=""; $prevDescription="";



    if (isset($_POST["project"]) && $_POST["project"] == "newproj" )
    {
      $errCnt = 0;
      // Submittion attempt for a New Project


      // Project Name (NoSpaces)
      if(empty($_POST["txtProjName"]))
      {
        $errCnt++;
        $styProjName = "background-color:#FF0000;";
        $prevProjName = $_POST["txtProjName"];
      }
      else
        $prevProjName = $_POST["txtProjName"];

      // Created Date Time
      if( empty($_POST["txtCreatedDTTM"]) )
      {
        $errCnt++;
        $styCreatedDTTM = "background-color:#FF0000;";
        $prevCreatedDTTM = $_POST["txtCreatedDTTM"];
      }
      //elseif (self::IsDate2($_POST["txtCreatedDTTM"], "%Y-%m-%d") == false )
      //elseif (self::IsDate2($_POST["txtCreatedDTTM"]) == false )
      if (!empty($_POST["txtCreatedDTTM"]) &&
          (!ereg("^[0-9]{4}-[0-9]{2}-[0-9]{2}", $_POST["txtCreatedDTTM"]))
          //(!ereg("^[0-9]{4}-[0-9]{2}-[0-9]{2}", $_POST["txtCreatedDTTM"]))
         )
      {
        $errCnt++;
        $styCreatedDTTM = "background-color:#FF0000;";
        $prevCreatedDTTM = $_POST["txtCreatedDTTM"];
      }
      else
        $prevCreatedDTTM = $_POST["txtCreatedDTTM"];

      // Description
      if(empty($_POST["txtDescription"]))
      {
        $errCnt++;
        $styDescription = "background-color:#FF0000;";
        $prevDescription = $_POST["txtDescription"];
      }
      else
        $prevDescription = $_POST["txtDescription"];

    }
    elseif (isset($_POST["project"]) && $_POST["project"] == "" )
    {

      // Invalid post sent
      // Check input if it is valid. If not, redisplay $html below.


    }
    // heredoc
    $html = <<<EOT
        <h1>Create Project</h1>
        <!-- <div class="tablethin"> -->
        <div>
          <form action="/p?cmd=new" method="post">
            <div id="mute_w3c"><input type="hidden" name="project" value="newproj" /></div>
            <table width="800" cellspacing="0" class="tablethin">
              <tr><td class="tblheader first" colspan="2">Project Name</td></tr>
              <tr>
                <td>
                  Name of your project<br />
                  <i>(Only Alpha-Numeric, no spaces, no slashes, no BS!)</i>
                </td>
                <td style="text-align: right;"><input type="text" name="txtProjName" value="{$prevProjName}" style="{$styProjName}" /></td>
              </tr>

              <tr><td class="tblheader" colspan="2">Created Date</td></tr>
              <tr>
                <td>When was the project created <i>(YYYY-MM-DD)</i></td>
                <td style="text-align: right;"><input type="text" name="txtCreatedDTTM" value="{$prevCreatedDTTM}" style="{$styCreatedDTTM}" /></td>
              </tr>


              <!--
              <tr><td class="tblheader" colspan="2">Project Managers</td></tr>
              <tr>
                <td valigh="top">
                  Choose the project managers to control
                  the project properties.
                </td>
                <td width="200">
                  <select name="managers[]" multiple="multiple" style="width:100%;height:50px;">
                    <option value="1">admin</option>
                  </select>
                </td>
              </tr>

              <tr><td class="tblheader" colspan="2">Project Stakeholders</td></tr>
              <tr>
                <td valigh="top">
                  Select the users which have access to view and update the project's
                  tickets/bugs/tasks.
                </td>
                <td width="200">
                  <select name="managers[]"
                      multiple="multiple" style="width:100%;height:50px;">
                    <option value="1">admin</option>
                  </select>
                </td>
              </tr>
              -->


              <tr><td class="tblheader" colspan="2">Description</td></tr>
              <tr>
                <td colspan="2">
                  <textarea name="txtDescription" rows="7" cols="0" style="{$styDescription}"
                  style="width:99%;">{$prevDescription}</textarea>
                </td>
              </tr>

              <tr>
                <td colspan="2">
                  <input type="submit" value="Create Project" />
                </td>
              </tr>
            </table>
          </form>
        </div>
EOT;

    return $html;
  }
}
?>