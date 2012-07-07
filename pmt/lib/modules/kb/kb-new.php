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
 *
 * To Do:
 *  [ ] Product(s) (CSV to List>>CSV)
 *  [ ] WikiText / HTML
 *  [ ] Save Page Data
 *
 * Change Log:
 *  2012-0625 + Ground Breaking
 */

namespace xenoPMT\Module\KB
{

  class Create
  {

    private $_title;
    private $_subject;
    private $_products;
    private $_data;

    private $_btnSubmit = false;
    private $_btnPreview = false;

    private $_flagSaved = false;

    function __construct()
    {
      // This use to hold DataHandler() events.. for some dumb reason i moved it
      // Collect data   { (expr) ? (true) : (false) };
      $this->_title     = (isset($_POST["Field1"])) ?  ($_POST["Field1"]) : ("");
      $this->_subject   = (isset($_POST["Field2"])) ?  ($_POST["Field2"]) : ("");
      $this->_products  = (isset($_POST["Field3"])) ?  ($_POST["Field3"]) : ("");
      $this->_data      = (isset($_POST["Field4"])) ?  ($_POST["Field4"]) : ("");

      /* OnEvent()
       * btnSave    = "Submit"
       * btnPreview = "Preview"
       * ** false==null, true=1
       */

      // return TRUE(1) or FALSE(null) depending upon button event
      $this->_btnSubmit   = (isset($_POST["btnSubmit"]))  ? ($_POST["btnSubmit"]=="Submit")   : (false);
      $this->_btnPreview  = (isset($_POST["btnPreview"])) ? ($_POST["btnPreview"]=="Preview") : (false);

    }


    /**
     * Pull in $_POST and $_GET data and prepare the class to handle it
     *
     * @return string
     */
    function DataHandler()
    { //static function DataHandler()


      /* NOTE:
       * For now we don't care about PRODUCTS
       */
      if (($this->_btnSubmit == true) &&
          ($this->_title!="" || $this->_subject!="" || $this->_data!=""))
      {
        //($this->_title!="" || $this->_subject!="" || $this->_products!="" || $this->_data!="")
        $this->ArticleSave();
        $this->_flagSaved = true;

      }
      else
      {
        // Invalid submittion
        $vals = $this->ArticlePreview();
      }

      return $vals;
    }

    /**
     * Generate Page Data
     * @return String
     */
    function PageLayout()
    {
      if ($this->_flagSaved == true)
        return $this->ArticleSaved();      // Display: "Saved!"
      else
        return $this->ArticleForm();       // Display: Form Contents
    }

    /* ###################################################################### */

    /**
     *  Generates, Article was Saved Save form date as KB Article
     */
    private function ArticleSave()
    {

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
      //$title = $this->_title;
      //$subj = $this->_subject;    //SELF::subject;
      //$prod = $this->_products;   //SELF::_products;
      //$data = $this->_data;       //SELF::_data;
      $htdata = "";

      if ( $this->_title!="" || $this->_subject!="" || $this->_products!="" || $this->_data!="" )
      {
        $htdata = <<<EOT
        <div id="kb-prev-box">
          <header id="header" class="info">
            <h3>KB Article Preview</h3>
            <div></div>
          </header>
          <table class="preview" border="0" cellspacing="0" cellpadding="2">
            <tr><th>Title:</th>      <td> {$this->_title} </td></tr>
            <tr><th>Subject:</th>    <td> {$this->_subject} </td></tr>
            <tr><th>Products:</th>   <td> {$this->_products} </td></tr>
          </table>
          <div>
            <header style="border-bottom: 1px solid #8888FF; color: #000000; font-weight: bold;">
              Data:
            </header>
            <div class="data">{$this->_data}</div>
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
                          spellcheck="false" value="{$this->_title}"
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
                          spellcheck="false" value="{$this->_subject}"
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
                          spellcheck="false" value="{$this->_products}"
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
                              required="">{$this->_data}</textarea>
                  </div>
                  <!-- ** hover-over to pop up instructions ** <p id="instruct1" class="instruct"><small>This field is required.</small></p> -->
                </li>


                <!-- Buttons <li class="buttons "> -->
                <li>
                  <div>
                    <!-- <input name="currentPage" id="currentPage" value="Evsvr2wuslashAbQKTfplkTXUEOwQJuCaYnw8JgQ0BYME9Ix8=" type="hidden" /> -->
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

  }

}
?>
