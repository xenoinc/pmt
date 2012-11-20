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
 *  2012-0425 + Added input checking
 *  2012-0424 + Moved Page_ProjectNew here
 */

class ProjExt_New
{
  // accepts a date, and a date format (not a regular expression)
  private static function IsDate($dttm, $frmat='%Y-%m-%d %H:%M')
  {
    $dateFmtRE = array(
      '/\//' => '\/',
      '/%g|%G|%y|%Y/' => '(19\d\d|20\d\d)',
      '/%m/' => '(0?[1-9]|1[012])',
      '/%d|%e/' => '(0?[1-9]|[12][0-9]|3[01])',
      '/%H|%I|%l/' => '([0-1]?\d|2[0-3])',
      '/%M/' => '([0-5]\d)'
      );
    if (empty($dttm))  return true;

    #convert Unix timestamp to a std format (must not include regular expressions)
    if (preg_match('!\d{5,}!', $dttm))
      $dttm = strftime($frmat, $dttm);

    return (
      #does %d match the regular expression version of $frmat? if it does m/d/y are in $x
      preg_match('!^' .preg_replace(array_keys($dateFmtRE), array_values($dateFmtRE),$frmat) .'$!', $dttm, $x)
      && (checkdate($x[2], $x[3], $x[1]) || checkdate($x[1], $x[2], $x[3]) || checkdate($x[3], $x[1], $x[2]))
        // MM, DD, YYYY
      ?true :false);
  }

  private static function IsDTTM($dttm)
  {
    $frmat="%Y-%m-%d %H:%M:%S";
    $dateFmtRE = array(
      '/\//' => '\/',
      '/%g|%G|%y|%Y/' => '(19\d\d|20\d\d)',
      '/%m/' => '(0?[1-9]|1[012])',
      '/%d|%e/' => '(0?[1-9]|[12][0-9]|3[01])',
      '/%H|%I|%l/' => '([0-1]?\d|2[0-3])',
      '/%M/' => '([0-5]\d)',
      '/%S/' => '([0-5]\d)'
      );
    if (empty($dttm))  return true;

    // Convert Unix timestamp to a std format (must not include regular expressions)
    if (preg_match('!\d{6,}!', $dttm))
      $dttm = strftime($frmat, $dttm);

    return (
      // Does %d match the regular expression version of $frmat? if it does m/d/y are in $x
      preg_match('!^' .preg_replace(array_keys($dateFmtRE), array_values($dateFmtRE),$frmat) .'$!', $dttm, $x)
      && (checkdate($x[2], $x[3], $x[1]) || checkdate($x[1], $x[2], $x[3]) || checkdate($x[3], $x[1], $x[2]))
         //         (MM, DD, YYYY)
      ?true :false);
  }

  static function Page_ProjectNew()
  {
    /** Workflow
     * 1) Get New project details
     * 2) Created NEW Project
     * 3) Take user to "Edit Project" screen so they can edit the finer details
     *    * Project Version(s), Component(s), Component Version(s), Milestone(s), User Priv(s)
     */

    $styProjName=""; $valProjName="";
    $styProjDTTM=""; $valProjDTTM=date("Y-m-d H:i:s");    // Default outpu, "YYYY-MM-DD HH:MM:SS"
    $styProjDesc=""; $valProjDesc="";

    $errCnt = 0;            // Errors generated
    $flagPost = false;      // Has a post been performed

    if (isset($_POST["project"]) && $_POST["project"] == "newproj" )
    {
      // Submittion attempt for a New Project
      $flagPost = true;

      // Project Name (NoSpaces?)
      if  (empty($_POST["txtProjName"]) ||
          (empty($_POST["txtProjName"]) && trim($_POST["txtProjName"])==""))
      {
        $errCnt++;
        $styProjName = "background-color:#FF0000;";
        $valProjName = $_POST["txtProjName"];
      }
      else
      {
        $valProjName = $_POST["txtProjName"];
      }

      // Created Date Time
      if  (empty($_POST["txtCreatedDTTM"]) ||
          (!empty($_POST["txtCreatedDTTM"]) && self::IsDTTM($_POST["txtCreatedDTTM"]) == false))
      {
        $errCnt++;
        $styProjDTTM = "background-color:#FF0000;";
        $valProjDTTM = $_POST["txtCreatedDTTM"];
      }
      /*
      elseif (!empty($_POST["txtCreatedDTTM"]) &&
              (self::IsDTTM($_POST["txtCreatedDTTM"]) == false )
            //(self::IsDate($_POST["txtCreatedDTTM"], "%Y-%m-%d %H:%M") == false )

      //elseif (!empty($_POST["txtCreatedDTTM"]) &&
      //     (self::IsDate2($_POST["txtCreatedDTTM"]) == false )

      //elseif (!empty($_POST["txtCreatedDTTM"]) &&
      //    (!preg_match("/^[0-9]{4}-[0-9]{2}-[0-9]{2}/", $_POST["txtCreatedDTTM"]))
      //elseif(!ereg("^[0-9]{4}-[0-9]{2}-[0-9]{2}", $_POST["txtCreatedDTTM"]))
      )
      {
        $errCnt++;
        $styProjDTTM = "background-color:#FF0000;";
        $valProjDTTM = $_POST["txtCreatedDTTM"];
      }
      */
      else
      {
        $valProjDTTM = $_POST["txtCreatedDTTM"];
      }

      // Description
      if(empty($_POST["txtDescription"]))
      {
        $errCnt++;
        $styProjDesc = "background-color:#FF0000;";
        $valProjDesc = $_POST["txtDescription"];
      }
      else
        $valProjDesc = $_POST["txtDescription"];

    }
    elseif (isset($_POST["project"]) && $_POST["project"] == "" )
    {
      // Invalid post sent
      // Check input if it is valid. If not, redisplay $html below
    }


    // STEP 2 :: Add NEW Project
    if ($flagPost && $errCnt == 0)
    {
      global $pmtDB;
      global $user;

      // $valProjName, $valProjDesc,  $valProjDTTM
      // Project_Id, Project_Name, Project_Description, Created_Dttm, Updated_User_Id
      $q =  "INSERT INTO " . PMT_TBL . "PROJECT " .
            "(Project_Name, Project_Description, Created_Dttm, Updated_User_Id) VALUES " .
            "('" .  trim($pmtDB->FixString($valProjName)) .
            "','".  trim($pmtDB->FixString($valProjDesc)) .
            "','".  trim($pmtDB->FixString($valProjDTTM)) .
            "','".  trim($pmtDB->FixString($user->UserInfo['User_Id'])).
            "');";

      $pmtDB->Query($q);



      // heredoc
      $html = <<<EOT
        <h1>Create Project</h1>
        <div>
          Project Created!
          <!-- Pause 3 seconds & continue to Editing Project's Advanced Details -->
        </div>
EOT;

    }
    else
    {
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
                <td style="text-align: right;"><input type="text" name="txtProjName" value="{$valProjName}" style="{$styProjName}" /></td>
              </tr>

              <tr><td class="tblheader" colspan="2">Created Date</td></tr>
              <tr>
                <td>When was the project created <i>(YYYY-MM-DD)</i></td>
                <td style="text-align: right;"><input type="text" name="txtCreatedDTTM" value="{$valProjDTTM}" style="{$styProjDTTM}" /></td>
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
                  <textarea name="txtDescription" rows="7" cols="80" style="{$styProjDesc}"
                  style="width:99%;">{$valProjDesc}</textarea>
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
    }

    return $html;
  }
}
?>