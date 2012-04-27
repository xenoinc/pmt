<?php

/* * **********************************************************
 * Copyright 2012 (C) Xeno Innovations, Inc.
 * ALL RIGHTS RESERVED
 * Author:       Damian Suess
 * Document:     project.ext.view
 * Created Date: Apr 25, 2012
 *
 * Description:
 *  View project details, list all available projects
 *
 * Use:
 *  // Since we declared everything "static". Call the members using, ::
 *  requre("ext/project.ext.view.php");
 *  $ret = ProjExt_View::Proj_Details();
 *
 * Change Log:
 *  2012-0425 + initial
 */


class ProjExt_View
{

  /**
   * This either lists the projects primary details or **main wiki page**
   *
   * Primary Details:
   *  + Number of tickets, bugs, tasks
   *  + Milestones Available
   *  + Versions?
   *
   * @global type $uri
   * @global type $user
   * @return string HTML to be displayed
   */
  public static function Proj_Details()
  {
    global $uri;
    global $user; // used to test if user has permissions to view project

    $html =   "<h1>Project View - (no wiki)</h1>";
    $html .=   "<h2>Alpha Alert!</h2>";
    $html .=  "Attempting to view page: '" . $uri->GetUri() ."'<br />";
    $html .=  "AnchorFile: " . $uri->AnchorFile() . "<br />";
    $html .=  "Project: " . ($uri->seg[1]);

    return $html;
  }


  /**
   * List all available projects if user has permissions
   * @global mixed $pmtDB
   * @return string HTML Data
   */
  public static function Proj_List()
  {
    //global $uri;
    global $pmtDB;

    /** View
     *  -----------------------------------------
     * | {Project-Title}                         |
     * |     Tickets: {0}  Bugs: {0}  Tasks: {0} |
     * | Wiki | Milestones | Reports | Admin     |
     * | --------------------------------------- |
     * | {Description}                           |
     *  -----------------------------------------
     */
    $html = "";
    $projects = array();
    $q = "SELECT `Project_Id`, `Project_Name`, `Project_Description`, `Created_Dttm` FROM ".PMT_TBL."PROJECT ".
         "ORDER BY `Project_Name` ASC;";
    $ret = $pmtDB->Query($q);
    if($pmtDB->NumRows($ret))
    {
      $html =  <<<"EOT"
        <h1>Available Project</h1>
          <div class="projlist">
            <ul class="proj-listing">

EOT;

      $s14 = str_repeat(" ", 14);
      while($prj = $pmtDB->FetchArray($ret))
      {
        $projects[] = $prj;         // Place project info into array
        $html .= $s14.
                "<li>". "\n".
                  // Tickets | Bugs | Tasks
                "  <ul class=\"tbt\">". "\n".
                "    <li class=\"tickets\">".   "<a title='Tickets' href='' >3</a>"  ."</li>". "\n".
                "    <li class=\"bugs\">".      "<a title='Bugs'    href='' >2</a>"  ."</li>". "\n".
                "    <li class=\"tasks\">".     "<a title='Tasks'   href='' >4</a>"  ."</li>". "\n".
                "  </ul>". "\n".

                  // Title
                "  <h3>". "\n".
                "    <span class='proj-listing-icon def-img'></span> &nbsp;" . "\n".
                    AddLink(project::MODULE, $prj["Project_Name"], "/".$prj["Project_Name"]). "\n".
                "  </h3>". "\n".

                "  <p class=\"quicknav\">"."\n".
                "    <a href=''>Wiki</a> &nbsp;"."\n".
                "    <a href=''>Milestones</a> &nbsp;"."\n".
                "    <a href=''>Reports</a> &nbsp;"."\n".
                "    <a href=''>Admin</a> &nbsp;"."\n".
                "  </p>"."\n".

                  // Details
                "  <div class='body'>". "\n".
                //"    <p class='created'>Id: " . $prj["Project_Id"] . "</p>". "\n".
                "    <p class='description'>". "\n".
                "      <b>Id:</b> " . $prj["Project_Id"] . "<br />". "\n".
                "      <b>Description:</b> <br />". "\n".
                       $prj["Project_Description"] . "<br />". "\n".
                "   </p>". "\n".
                    // Created Date
                "    <p class='created'>Created: ". $prj["Created_Dttm"]. "</p>". "\n".
                "  </div>" . "\n".
                "</li>". "\n";   // test output

        /*
         * Array ( [0] => 1
         *         [Project_Id] => 1
         *         [1] => testProject
         *         [Project_Name] => testProject
         *         [2] => This is a test project. There is nothing of any importance here.
         *         [Project_Description] => This is a test project. There is nothing of any importance here. )
         */
      }
      $html .= "</ul></div>\n";
    }
    else
    {
      $html .= "<h1>Projects</h1>";
      $html .= "<p>There are no projects to view</p>";
      $html .= "<p>If permissions allow, you may ".
                AddLink(project::MODULE, "Create", "?cmd=new").
                " a new project</p>";
    }

    return $html;
  }

}

?>
