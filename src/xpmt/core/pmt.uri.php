<?php
/************************************************************
 * Copyright 2010-12 (C) Xeno Innovations, Inc.
 * ALL RIGHTS RESERVED
 * Author:       fuct
 * Document:     pmt.uri.php
 * Created Date: Nov 18, 2010, 5:52:27 PM
 *
 * Description:
 *  This code is used to parse the URL entered and
 *  reroute the user's request to the generated page
 *
 * To Do:
 * [ ] Handle requests outlined in /doc/pmt-v1.0-navigation.txt
 *
 * Change Log:
 *  2012-0926 + Added public property Count to count the segments on construction
 *  2012-0309 * Added basic functionality
 ***********************************************************/

/**
 * URI Values
 * ----------
 * Milestone 1
 *  <blank>                   - List all projects
 *  p/<name>               - List project wiki page
 *  p/<name>/milestone     - show milestone
 *  p/<name>/ticket        - Create new ticket
 *  p/<name/ticket/<num>   - Show ticket details
 *  user                      - User listing
 *  admin                     - Admin Controller
 *
 * Milestone 2
 *
 *
 */

// namespace xenoPMT\Core {

class URI
{
  /**
   *  Segments are broken up by the "/"
   *  "p/testProject" >> Array ( [0] => "p", [1] => "testProject" )
   */
  public $seg = array();    // URL Split up inot parts ("p/ProjName")
  public $style = 1;        //
  public $Count = 0;        // Number of items in segment (added 2012-0926)

  private $root;
  private $request;
  private $file = "index.php";

  public function __construct()
  {
    // Get root
    $this->root = str_replace($this->file, "", $_SERVER["SCRIPT_NAME"]);

    // Get request without query string
    $req = explode("?", $_SERVER["REQUEST_URI"]);
    $this->request = $req["0"];

    // explode segments
    $this->seg = explode("/", trim($this->request, "/"));

    // Remove bullshit
    foreach(explode("/", $this->root) as $seg => $val)
    {
      if( !empty($val))
        array_shift($this->seg);
    }

    // remove indedx.php if its there & ignore any error generated (@)
    if(@$this->seg["0"] == "index.php")
      array_shift($this->seg);

    $this->Count = count($this->seg);
  }

  /**
   * Get URI
   * Gets the current URI
   * @return string
   */
  public function GetUri()
  {
    return $this->Anchor($this->seg);
  }

  /**
   * Safely return the value of the segment index.
   * If it doesn't exist the it will return False.
   *
   * @param type $seg Segment index number
   * @return boolean  Return value of segment index else False
   */
  public function Segment($seg)
  {
    if(isset($this->seg[$seg]))
      return $this->seg[$seg];
    else
      return false;
  }

  public function AnchorFile()
  {
    return $this->file;
  }

  /**
   * Create URIs from Array
   * @param array $segments
   * @return string
   */
  public function Anchor($arrSegments = array())
  {
    if (!is_array($arrSegments))
      $arrSegments = func_get_args();

    $pth = ($this->style == 1 ?
            str_replace($this->file, "", $_SERVER["SCRIPT_NAME"])
            : $_SERVER["SCRIPT_NAME"] . "/"
           );
    return $pth . $this->ArrayToURI($arrSegments);
  }

  /**
   * Convert Array to URI segments
   * @example array >> "uri/full/path"
   * @param array $arrSegs
   * @return string
   */
  private function ArrayToURI($arrSegs = array())
  {
    if(count($arrSegs) < 1 || !is_array($arrSegs))
      return;

    foreach($arrSegs as $key => $val)
      $segs[] = $val;
    return implode("/", $segs);

  }

}

?>
