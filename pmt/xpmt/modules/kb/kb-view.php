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
 *  Inputs:   KB Article Number
 *  Outputs:  Formatted WikiText
 * Change Log:
 *
 */

namespace xenoPMT\Module\KB
{
  class View
  {
    /**
     * Knowledge Base ID Number
     * @var integer KB Id Number
     */
    private $_kbId = null;

    /**
     * KB Article HTML data
     * @var string HTML Data
     */
    private $_htData = "";


    const ARTICLE_ERR_INVALID_ID = 1;     // Invalid input of ID (alpha-numeric input provided)
    const ARTICLE_ERR_NO_PAGE    = 2;     // Article Id doesn't exist

    // --------------------------------------------------------------

    public function __construct($kbId)
    {

      if (is_numeric($kbId) == false)
      {
        $this->_htData = $this->PageError(self::ARTICLE_ERR_INVALID_ID, $kbId);
      }
      else
      {
        $this->_htData = $this->LookupArticle($kbId);
      }
    }

    /**
     * Return page data
     */
    public function PageLayout()
    {
      return $this->_htData;
    }

    // ##################################################

    /**
     * Lookup article and return the HTML data of the page
     * whether it is an error message or full article
     *
     * @param integer $kbId
     */
    private function LookupArticle($kbId)
    {
      global $pmtConf;  // Database Prefix
      global $pmtDB;    // DB Handler
      global $user;     // User Id (for breadcrumb Audit) and Permissions

      $dbPrefix = $pmtConf["db"]["prefix"];

      $q = <<<QUERY
 SELECT * FROM {$dbPrefix}KB_ARTICLE WHERE `Article_Id` = {$kbId} LIMIT 1;
QUERY;


      $arr = $pmtDB->Query($q);
      $ret = $pmtDB->FetchArray($arr);

      //debug(count($ret));
      //debug(print_r($ret));

      if (count($ret) < 2)
      {
        $html = $this->PageError(self::ARTICLE_ERR_NO_PAGE, $kbId);
      }
      else
      {

        $kbTitle        = $ret["Title"];
        $kbType         = $ret["Article_Type"];            // How-To General, etc
        $kbSubject      = $ret["Subject"];
        $kbData         = $ret["Article_Data"];

        $kbCreatedUid   = $ret["Created_Uid"];
        $kbCreatedDttm  = $ret["Created_Dttm"];
        $kbModifiedDttm = $ret["Modified_Dttm"];


        /* Display box banner if Visibility = "Hidden" */
        $html = "Article is marked as invisible to the world";    // Visible = false
        $html = "Article is marked as internal only";             // Login_Required = true



        $html = ""; // clear it for now

        /* main body */
        $html .= <<<HTML
          <a name="kb-top"></a>
          <div id="kb-view">
            <h1>{$kbTitle}</h1>
            <small><b>Article Id:</b> {$kbId} - <b>Type:</b> {$kbType}</small>
            <h3>Subject: {$kbSubject}</h3>

            <h2 class="subTitle">Article</h2>
            <div>
              {$kbData}
            </div>
            <div>
              <p><a href="#kb-top">Back to the top</a></p>
            </div>

            <h2 class="subTitle">Details</h2>
            <ul>
              <li>Created By: {$kbCreatedUid}</li>
              <li>Created Date: {$kbCreatedDttm}</li>
              <li>Modified Date: {$kbModifiedDttm}</li>
            </ul>
            <h2>Attachemnts</h2>
            <ul>
              <!-- <li>title: xxxx</li> -->
              <li><i>(disabled)</i></li>
            </ul>

            <!-- <h2>Related Product</h2> -->
            <!-- <h2>Related Project</h2> -->

            <!-- <h2>Related Articles</h2>  <ul><li>KB:### - Title</li></ul> -->
            <!-- <h2>Feedback</h2> -->
          </div>
HTML;
      }

      return $html;

    }


    /**
     * Generate page error message
     * @param integer $errNum
     * @return string HTML error message
     */
    private function PageError($errNum, $id)
    {
      switch ($errNum)
      {
        // Invalid Id number
        case self::ARTICLE_ERR_INVALID_ID:
          $html = "<h1>Invalid KB Article number - ID Must be numeric!</h1>" .
                  "<h3>Article Attempting to visit, KB:$id";
          break;

        // Page doesn't exist
        case self::ARTICLE_ERR_NO_PAGE:
          $html = "<h1>Article doesn't exist</h1>".
                  "<h3>Article Attempting to visit, KB:$id";
          break;
        default:
          $html = "<h1>An unknown error occurred</h1>".
                  "<h3>Article Attempting to visit, KB:$id";
      }
      return $html;
    }

  }
}

?>
