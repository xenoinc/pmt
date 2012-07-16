<?php

/* * **********************************************************
 * Copyright 2012 (C) Xeno Innovations, Inc.
 * ALL RIGHTS RESERVED
 * Author:       Damian J. Suess
 * Document:     kb-new.php
 * Created Date: Jun 25, 2012
 *
 * Description:
 *  Generate data for creating KB Articles
 *  See "./src/install/pmt-db-kb.sql" for reference
 *
 * To Do:
 *  [ ] Product(s) (CSV to List>>CSV)
 *  [ ] WikiText / HTML
 *  [\] Save Page Data
 *  [ ] Check if previous title exists
 *
 * Test Title:  "It's my "Test" title :woo;s: all around!"
 *
 * Change Log:
 *  2012-0712 * Cleaned up code errors
 *            + Added SaveArticle() private member to save to DB
 *  2012-0625 + Ground Breaking
 */

namespace xenoPMT\Module\KB
{

  class Create
  {

    // Article Settings
    private $_kbTitle;                  // Title of article
    private $_kbSubject;                // Subject of article
    private $_kbProducts;               // List of products (or projects) it pretains to
    private $_kbData;                   // HTML Data
    private $_kbType;                   // [NOT USED] "How To", "General", "Solution"
    private $_kbAttachArray = array();    // [NOT USED] File Attachments (PC Path to files)

    // Advanced Article Settings
    private $_kbProjectId = 0;          // KB Advanced Setting - Link to Project Id
    private $_kbProductId = 0;          // KB Advanced Setting - Link to Product Id
    private $_kbLoginReq = false;     // KB Advanced Setting - Login Required
    private $_kbVisible = false;      // KB Advanced Setting - Article is visible to others


    // ButtonEvents
    private $_btnSubmit = false;      // Button Save click event fired
    private $_btnPreview = false;     // Button Preview click event fired
    private $_btnTest = false;        // Button Test click event fired

    /// KB Article Preview HTML
    private $_htPreview = "";

    /// Article is valid and was saved (true). If so, Do NOT display preview & show "Article Submitted" message
    private $_flagSaved = false;


    function __construct()
    {
      // This use to hold DataHandler() events.. for some dumb reason i moved it
      // Collect data   { (expr) ? (true) : (false) };
      $this->_kbTitle     = (isset($_POST["Field1"])) ?  ($_POST["Field1"]) : ("");
      $this->_kbSubject   = (isset($_POST["Field2"])) ?  ($_POST["Field2"]) : ("");
      $this->_kbProducts  = (isset($_POST["Field3"])) ?  ($_POST["Field3"]) : ("");
      $this->_kbData      = (isset($_POST["Field4"])) ?  ($_POST["Field4"]) : ("");

      /* OnEvent()
       * btnSave    = "Submit"
       * btnPreview = "Preview"
       * ** false==null, true=1
       */

      // return TRUE(1) or FALSE(null) depending upon button event
      $this->_btnSubmit   = (isset($_POST["btnSubmit"]))  ? ($_POST["btnSubmit"]=="Submit")   : (false);
      $this->_btnPreview  = (isset($_POST["btnPreview"])) ? ($_POST["btnPreview"]=="Preview") : (false);
      $this->_btnTest     = (isset($_POST["btnTest"]))    ? ($_POST["btnTest"]=="Preview")    : (false);

    }


    /**
     * Pull in $_POST and $_GET data and prepare the class to handle it
     * ** Really this all should just go in the constructor. Doing this just as sample case.
     *
     * @return string
     */
    function DataHandler()
    { //static function DataHandler()
      /*
       * To Do:
       *  [ ] Check if previous title exists.. the throw error if it does.
       */

      // Insert Test Data
      if ($this->_btnTest == true)
      {
        $this->UnitTest();
      }
      elseif (($this->_btnSubmit == true) &&
              ($this->_kbTitle!="" || $this->_kbSubject!="" || $this->_kbData!=""))
      {
        /* NOTE:
        * For now we don't care about PRODUCTS
        */

        //($this->_kbTitle!="" || $this->_kbSubject!="" || $this->_kbProducts!="" || $this->_kbData!="")


        $dupeTitle = false;

        // Perform article check


        // save our data
        if ($dupeTitle == false)
        {
          $kbId = $this->SaveArticle();

          $this->_htPreview = "";     // No need for preview
          $this->_flagSaved = true;
        }
        else
        {
          $this->_htPreview = "<div><b>Previous Title Exists!</b> - Change your title</div>\n";
          $this->_htPreview .= $this->ArticlePreview();
        }

      }
      else
      {
        // Preview button was pressed or it was an invalid submittion
        $this->_htPreview = $this->ArticlePreview();
      }
    }

    /**
     * Generate Page Data
     * @return String
     */
    function PageLayout()
    {
      if ($this->_flagSaved == true)
      {
        return $this->ArticleSaved();      // Display: "Saved!"
      }
      else
      {
        return $this->_htPreview . $this->ArticleForm();       // Display: Form Contents
      }
    }

    /* ##[ Database Shit ]################################################### */

    /**
     * Save article to database
     * @return string KB Id Number
     */
    private function SaveArticle()
    {
      global $pmtConf;  // Database shit
      global $pmtDB;
      global $user;     // Current User
      /*
       * To Do:
       *  [ ] Save Article
       *      _KB_ARTICLE (
       *  [ ] Parse Attachements and save the files KB_ATTACHMENT(File_Path, File_Title)
       *  [ ] Add settings (Project_Id, Product_Id {recursive}, Login_Required=TRUE, Visible=(if it's still in editing mode)
       */

      // Create vars to use
      $user_id      = $user->userInfo["User_Id"];       // 1
      $user_handle  = $user->userInfo["User_Name"];     // admin
      $user_Name    = $user->userInfo["Display_Name"];  // xenoPMT Administrator
      // debug("id: '" . $user_id . "'  hanle: '" . $user_handle . "' nme: '" . $user_Name . "'");

      // $url = str_replace("%3A", ":", $url);
      $dbPrefix = $pmtConf["db"]["prefix"];
      //$fixTitle = $pmtDB->FixString(str_replace(" ", "_", $this->_kbTitle));    // Use this for Wiki Articles
      $title = $pmtDB->FixString($this->_kbTitle);
      $subject = $pmtDB->FixString($this->_kbSubject);
      $data = $pmtDB->FixString($this->_kbData);

      /*
         CREATE TABLE IF NOT EXISTS `TBLPMT_KB_ARTICLE`
           `Article_Id`    INT UNSIGNED NOT NULL AUTO_INCREMENT,
           `Article_Type`  VARCHAR(16) COLLATE  utf8_unicode_ci,           -- "How To", "General", "Solution"
           `Title`         VARCHAR(255) COLLATE utf8_unicode_ci NOT NULL,
           `Subject`       VARCHAR(255) COLLATE utf8_unicode_ci NOT NULL,
           `Article_Data`  LONGTEXT COLLATE utf8_unicode_ci NOT NULL,
           `Created_Uid`   INT UNSIGNED,
           `Created_Dttm`  DATETIME,
           `Modified_Dttm` DATETIME,
           PRIMARY KEY (`Article_Id`)
       */
      /*
        CREATE TABLE IF NOT EXISTS `TBLPMT_KB_ARTICLE_SETTING_0`
          `Article_Id`  INT UNSIGNED NOT NULL,
          `Project_Id`  INT UNSIGNED DEFAULT 0,     -- Linked to project
          `Product_Id`  INT UNSIGNED DEFAULT 0,     -- Linked to product
          `Login_Required` BOOLEAN NOT NULL DEFAULT TRUE,
          `Visible`     SMALLINT DEFAULT 0          -- Is it public or private to user (Created_Uid / Admin)
       */
      /*
        CREATE TABLE IF NOT EXISTS `TBLPMT_KB_ATTACHMENT`
          `Attachment_Id` INT UNSIGNED NOT NULL,
          `File_Path`     VARCHAR(255) DEFAULT '',
          `File_Title`    VARCHAR(255),
       */


      $q = <<<SQL
INSERT INTO {$dbPrefix}KB_ARTICLE
(`Article_Type`, `Title`, `Subject`, `Article_Data`, `Created_Uid`, `Created_Dttm`, `Modified_Dttm`) VALUES
('General', '{$title}', '{$subject}', '{$data}', {$user_id}, null, null);

SQL;

      // print("<li><b>[query]</b> - ".$q."</li>\n");
      try
      {
        $pmtDB->Query($q);
      }
      catch (Exception $e)
      {
        pmtDebug ("Error inserting KB article. Exception: $e");
      }

      return "0";
    }


    /* --[ HTML Data ]------------------------------------------------------- */

    /**
     *  Generates, Article was Saved Save form date as KB Article
     * @return string HTML Preview Data
     */
    private function ArticleSaved()
    {
      $htdata = <<<EOT
        <center><h1>Your article has been saved!</h1>
        <div>
          <header id="header">
            <h3>KB Article Submitted</h3>
          </header>
          <table class="preview" border="0" cellspacing="0" cellpadding="2">
            <tr><th>Article Id:</th> <td> <i>(unknown)</i> </td></tr>
            <tr><th>Title:</th>      <td> {$this->_kbTitle} </td></tr>
            <tr><th>Subject:</th>    <td> {$this->_kbSubject} </td></tr>
            <tr><th>Products:</th>   <td> {$this->_kbProducts} </td></tr>
          </table>
         </div>
         </center>

EOT;
      return $htdata;
    }

    /*
      static function Test() {
        // Use:       echo \xenoPMT\Module\KB\KBNew::Test()
        // returns:   "xenoPMT\Module\KB\KBNew::Test"
        return __METHOD__;
      }
    */

    private function ArticlePreview()
    {
      //$title = $this->_kbTitle;
      //$subj = $this->_kbSubject;    //SELF::subject;
      //$prod = $this->_kbProducts;   //SELF::_kbProducts;
      //$data = $this->_kbData;       //SELF::_kbData;
      $htdata = "";

      if ( $this->_kbTitle!="" || $this->_kbSubject!="" || $this->_kbProducts!="" || $this->_kbData!="" )
      {
        $htdata = <<<EOT
        <div id="kb-prev-box">
          <header id="header" class="info">
            <h3>KB Article Preview</h3>
            <div></div>
          </header>
          <table class="preview" border="0" cellspacing="0" cellpadding="2">
            <tr><th>Title:</th>      <td> {$this->_kbTitle} </td></tr>
            <tr><th>Subject:</th>    <td> {$this->_kbSubject} </td></tr>
            <tr><th>Products:</th>   <td> {$this->_kbProducts} </td></tr>
          </table>
          <div>
            <header style="border-bottom: 1px solid #8888FF; color: #000000; font-weight: bold;">
              Data:
            </header>
            <div class="data">{$this->_kbData}</div>
          </div>
        </div>

EOT;
      }
      return $htdata;
    }


    function ArticleForm()
    {

      $link = "kb?cmd=new";

      return <<<"EOT"

        <div>
          <fieldset>
            <legend>Create KB Article</legend>

            <!-- <div style="background-color: #CCCCFF; width: 600px;"> -->
            <form id="form1" name="form1" class="frmData page1"
                  method="post"
                  action="{$link}"
                  autocomplete="off" enctype="multipart/form-data"
                  novalidate="" >

              <!--
              <header id="header" class="info">
                <h2>Contact Form</h2>
                <div></div>
              </header>
              -->

              <ul style="list-style-type: none;">

                <!-- Title -->
                <li>
                  <label class="desc" id="title1" for="Field1">Article Title:</label>
                  <div>
                    <input id="Field1" name="Field1" class="field text large"
                          size="66"
                          spellcheck="false" value="{$this->_kbTitle}"
                          maxlength="255" tabindex="1"
                          type="text" />
                  </div>
                  <!-- <p class="instruct" id="instruct2"><small>We won't share this with strangers.</small></p> -->
                </li>


                <!-- Subject -->
                <li>
                  <label class="desc" id="title2" for="Field2">Subject:</label>
                  <div>
                    <input id="Field2" name="Field2" class="field text large"
                          size="66"
                          spellcheck="false" value="{$this->_kbSubject}"
                          maxlength="255" tabindex="2"
                          type="text" />
                  </div>
                  <!-- <p class="instruct" id="instruct2"><small>We won't share this with strangers.</small></p> -->
                </li>


                <!-- Product(s) -->
                <!-- size="66" -->
                <li>
                  <label class="desc" id="title3" for="Field3">Product(s):</label>
                  <div>
                    <input id="Field3" name="Field3" class="field text large"
                          size="66"
                          spellcheck="false" value="{$this->_kbProducts}"
                          maxlength="255" tabindex="3"
                          type="text" />
                  </div>
                  <!-- <p class="instruct" id="instruct2"><small>We won't share this with strangers.</small></p> -->
                </li>


                <!-- HTData -->
                <li>
                  <label class="desc" id="title4" for="Field4">Article Data:</label>
                  <div>
                    <textarea id="Field4" name="Field4" class="field textarea medium"
                              spellcheck="true" rows="20"
                              cols="70" tabindex="4"
                              required="">{$this->_kbData}</textarea>
                  </div>
                  <!-- ** hover-over to pop up instructions ** <p id="instruct1" class="instruct"><small>This field is required.</small></p> -->
                </li>


                <!-- Buttons <li class="buttons "> -->
                <li>
                  <div>
                    <!-- <input name="currentPage" id="currentPage" value="Evsvr2wuslashAbQKTfplkTXUEOwQJuCaYnw8JgQ0BYME9Ix8=" type="hidden" /> -->

                    <input
                      id="btnTest" name="btnTest"
                      tabindex="20" type="submit"
                      class="btTxt submit"
                      value="Test" />

                    <input
                      id="btnPreview" name="btnPreview"
                      tabindex="20" type="submit"
                      class="btTxt submit"
                      value="Preview" />

                    <input
                      id="btnSubmit" name="btnSubmit"
                      tabindex="20" type="submit"
                      class="btTxt submit"
                      value="Submit" />
                  </div>
                </li>

              </ul>
            </form>
            <!-- </div> -->
          </fieldset>
        </div>

EOT;
    }

    private function UnitTest()
    {
      $this->_kbType    = "General";
      $this->_kbTitle   = "Test \"title\" ok the 'KB' system";
      $this->_kbSubject   = "Test article";
      $this->_kbProducts  = "Product1;Product2";
      $this->_kbData      = "<p>This is a test article</p><p>And some <b>sample data</b>.</p>";

      $_POST["Field1"] = $this->_kbTitle;
      $_POST["Field2"] = $this->_kbSubject;
      $_POST["Field3"] = $this->_kbProducts;
      $_POST["Field4"] = $this->_kbData;
    }

  }

}
?>
