<?php

/* * **********************************************************
 * Copyright 2012 (C) Xeno Innovations, Inc.
 * ALL RIGHTS RESERVED
 * Author:       Damian Suess
 * Document:     project.ext.wiki
 * Created Date: Apr 25, 2012
 *
 * Description:
 *  Parse the proposed project wiki pages
 *
 * Use:
 *  // Since we declared everything "static". Call the members using, ::
 *  requre("ext/project.ext.wiki.php");
 *  $ret = ProjExt_Wiki::MemberName();
 *
 * Change Log:
 *  2012-0425 + initial
 */


class ProjExt_Wiki
{
  public static function Page_View()
  {
    global $uri;
    
    $html =   "<h1>Project View - Wiki Page</h1>";
    $html .=   "<h2>Alpha Alert!</h2>";
    $html .=  "Attempting to view page: '" . $uri->GetUri() ."'<br />";
    $html .=  "AnchorFile: " . $uri->AnchorFile() . "<br />";
    $html .=  "Project: " . ($uri->seg[1]);

    // Display main page by default
    if (count($uri->seg) < 4)
    {
      // p/PROJECT/wiki
      $html .=  "WikiPage: (MAIN)";
    }
    else
    {
      // Attempt to display the wiki page specified
      $html .=  "WikiPage: " . ($uri->seg[3]);
    }

    return $html;

  }
}

?>
