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
 *  [ ] Title
 *  [ ] Subject
 *  [ ] Product(s) (CSV or List>>CSV)
 *  [ ] WikiText / HTML
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

    /*
    function __construct() {
      $this->_title = $_POST["Field1"];
      $this->_subject = $_POST["Field2"];
      $this->_products = $_POST["Field3"];
      $this->_data = $_POST["Field4"];
    }
    */

    //static function DataHandler()
    function DataHandler()
    {

      // Collect data   { (expr) ? (true) : (false) };
      $this->_title     = (isset($_POST["Field1"])) ?  ($_POST["Field1"]) : ("");
      $this->_subject   = (isset($_POST["Field2"])) ?  ($_POST["Field2"]) : ("");
      $this->_products  = (isset($_POST["Field3"])) ?  ($_POST["Field3"]) : ("");
      $this->_data      = (isset($_POST["Field4"])) ?  ($_POST["Field4"]) : ("");

      $title = $this->_title;
      $subj = $this->_subject;    //SELF::subject;
      $prod = $this->_products;   //SELF::_products;
      $data = $this->_data;       //SELF::_data;

      $vals = <<<EOT
      <table border="0" cellspacing="0" cellpadding="2">
         <tr><td>Title:</td>      <td> {$title} </td></tr>
         <tr><td>Subject:</td>    <td> {$subj} </td></tr>
         <tr><td>Products:</td>   <td> {$prod} </td></tr>
         <tr><td>Data</td>        <td> {$data} </td></tr>
      </table>
EOT;

      return $vals;
    }

    static function Test() {
      // Use:       echo \xenoPMT\Module\KB\KBNew::Test()
      // returns:   "xenoPMT\Module\KB\KBNew::Test"
      return __METHOD__;
    }

    function pageGen() {

      $link = "kb?cmd=new";

      return <<<"EOT"

    <div style="background-color: #CCCCFF; width: 600px;">

      <form id="form1" name="form1" class="frmData page1"
            method="post"
            action="{$link}"
            autocomplete="off" enctype="multipart/form-data"
            novalidate="" >

        <header id="header" class="info">
          <h2>Contact Form</h2>
          <div></div>
        </header>

        <ul>

          <!-- Title -->
          <li>
            <label class="desc" id="title1" for="Field1">Email Address</label>
            <div>
              <input id="Field1" name="Field1" class="field text large"
                     size="66"
                     spellcheck="false" value="{$title}"
                     maxlength="255" tabindex="1"
                     type="text" />
            </div>
            <!-- <p class="instruct" id="instruct2"><small>We won't share this with strangers.</small></p> -->
          </li>


          <!-- Subject -->
          <li>
            <label class="desc" id="title2" for="Field2">Email Address</label>
            <div>
              <input id="Field2" name="Field2" class="field text large"
                     size="66"
                     spellcheck="false" value="{$subj}"
                     maxlength="255" tabindex="2"
                     type="text" />
            </div>
            <!-- <p class="instruct" id="instruct2"><small>We won't share this with strangers.</small></p> -->
          </li>


          <!-- Product(s) -->
          <!-- size="66" -->
          <li>
            <label class="desc" id="title3" for="Field3">Product(s)</label>
            <div>
              <input id="Field3" name="Field3" class="field text large"
                     size="66"
                     spellcheck="false" value="{$prod}"
                     maxlength="255" tabindex="3"
                     type="text" />
            </div>
            <!-- <p class="instruct" id="instruct2"><small>We won't share this with strangers.</small></p> -->
          </li>


          <!-- HTData -->
          <li>
            <label class="desc" id="title4" for="Field4">Message</label>
            <div>
              <textarea id="Field4" name="Field4" class="field textarea medium"
                        spellcheck="true" rows="10"
                        cols="50" tabindex="4"
                        required="">{$data}</textarea>
            </div>
            <!-- ** hover-over to pop up instructions ** <p id="instruct1" class="instruct"><small>This field is required.</small></p> -->
          </li>



          <li class="buttons ">
            <div>
              <input name="currentPage" id="currentPage" value="Evsvr2wuslashAbQKTfplkTXUEOwQJuCaYnw8JgQ0BYME9Ix8=" type="hidden" />
              <input
                id="saveForm" name="saveForm"
                tabindex="20" type="submit"
                class="btTxt submit"
                value="Submit" />
            </div>
          </li>

        </ul>
      </form>
    </div>

EOT;
    }

  }

}
?>
