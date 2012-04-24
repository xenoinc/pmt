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
  static function Page_ProjectNew()
  {

    if (isset($_POST["project"]) && $_POST["project"] == "" )
    {
      //print("Blank Submittion");
    }
    elseif (isset($_POST["project"]) && $_POST["project"] == "newproj" )
    {

      // Check input if it is valid. If not, redisplay $html below.
      print ("Submit attempted");

    }
    // heredoc
    $html = <<<EOT
        <h1>Create Project</h1>
        <!-- <div class="tablethin"> -->
        <div>
          <form action="/p?cmd=new" method="post">
            <input type="hidden" name="project" value="newproj" />
            <table width="800" cellspacing="0" class="tablethin">
              <tr><td class="tblheader first" colspan="2">Project Name</td></tr>
              <tr>
                <td>
                  Name of your project<br />
                  <i>(Only Alpha-Numeric, no spaces, no slashes, no BS!)</i>
                </td>
                <td style="text-align: right;"><input type="text" name="txtProjName" value="" /></td>
              </tr>

              <tr><td class="tblheader" colspan="2">Created Date</td></tr>
              <tr>
                <td>When was the project created <i>(YYYY-MM-DD)</i></td>
                <td style="text-align: right;"><input type="text" name="txtCreatedDTTM" value="YYY-MM-DD" /></td>
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
                  <textarea name="txtDescription" rows="7" cols="0"
                  style="width:99%;"> </textarea>
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