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
     *  --------------------------------------
     * | {Project-Title}                      |
     * | Tickets: {0}  Bugs: {0}  Tasks: {0}  |
     * | Milestones | Wiki
     * | {Description}                        |
     *  --------------------------------------
     */
    $html = "";
    $projects = array();
    $q = "SELECT `Project_Id`, `Project_Name`, `Project_Description` FROM ".PMT_TBL."PROJECT ".
         "ORDER BY `Project_Name` ASC;";
    $ret = $pmtDB->Query($q);
    if($pmtDB->NumRows($ret))
    {
      $html = "<h1>List of Project</h1>\n";
      $html .= "<div><ul>\n";
      while($prj = $pmtDB->FetchArray($ret))
      {
        $projects[] = $prj;         // Place project info into array
        $html .= "<li>" .
                  $prj["Project_Name"] . "<ul>".
                    "<li><b>ID Number:</b> " . $prj["Project_Id"] . "</li>" .
                    "<li><b>Description:</b> " .$prj["Project_Description"] . "</li></ul>" .
                  "</li>\n";   // test output

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
                //AddLink(self::MODULE, "Create", "?cmd=new").
                //AddLink(parent::MODULE, "Create", "?cmd=new").
                AddLink("p", "Create", "?cmd=new").
                " a new project</p>";
    }

    return $html;
  }

}

?>
