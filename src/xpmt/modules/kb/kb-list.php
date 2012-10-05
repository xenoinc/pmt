<?php
/* * **********************************************************
 * Copyright 2012 (C) Xeno Innovations, Inc.
 * ALL RIGHTS RESERVED
 * Author:       Damian Suess
 * Document:     kb-view.php
 * Created Date: Jun 21, 2012
 *
 * Description:
 *  View Knowledge Base article
 *
 * Change Log:
 *  2012-0903 * Created
 */

namespace xenoPMT\Module\KB
{
  class ListItems
  {

    private $_listStart = 0;      // Start point
    private $_listMax = null;     // Max to show (null = all)

    public function __construct()
    {
      $this->_listStart = 0;      // Start at article 0 (really it's 1)
      $this->_listMax   = null;   // Show all
    }

    /**
     * Does user have permissions to list articles
     */
    // private function HasPermission() { }

    /**
     * Returns article data in array
     * [][0] - id number
     * [][1] - title
     * [][2] - type (general, how-to)
     * [][3] - sample text (first 200 chars)
     * [][4] -
     */
    private function ListArticles()
    {
      global $pmtDB;

      $dbPrefix = PMT_TBL;
      $arrData = array();            // Query data array
      $arrRet = array(array());     // array to return

      // Get max results to pull
      if ($this->_listMax == null)
        $limit = "";
      else
        $limit = "LIMIT " . $this->_listMax;

      // Get data and sort by Article Id
      $q = <<<QUERY
   SELECT `Article_Id`, `Article_Type`, `Title`, `Subject`, `Created_Uid`, `Created_Dttm`, `Modified_Dttm`
   FROM {$dbPrefix}KB_ARTICLE
   ORDER BY `Article_Id` ASC {$limit};
QUERY;


      $ret = $pmtDB->Query($q);
      if($pmtDB->NumRows($ret))
      {
        // Get list of projects
        while($kbData = $pmtDB->FetchArray($ret))
        {
          $arrData[] = $kbData;
          pmtDebug(print_r($kbData));
        }

      }
      else
      {

      }



      return $arrRet;
    }



    // -=-=[ Public Members ]=-=-=-=-=-=-=-=-=-=-=-=-=-=-


    /**
     * Setup class private variables to get ready to display/parse data
     */
    public function DataHandler()
    {
      /* To Do
       * [ ] Get parameters for listing (kb.GetCmd)
       *    [ ] startPos
       *    [ ] maxResults to show (25, 50, 100, 200, 500, all)
       */
      //$this->_listStart = 0;      // Start at article 0 (really it's 1)
      //$this->_listMax   = null;   // Show all
    }

    public function PageLayout()
    {
      $html = "";
      $arrData = $this->ListArticles();



      return $html;
    }

  }
}

?>
