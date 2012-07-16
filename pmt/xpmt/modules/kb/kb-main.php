<?php

/* * **********************************************************
 * Copyright 2012 (C) Xeno Innovations, Inc.
 * ALL RIGHTS RESERVED
 * Author:       Damian J. Suess
 * Document:     kb-main.php
 * Created Date: Jul 6, 2012
 *
 * Description:
 *  Main KB page to display the Welcome screen.
 *    * If permissions are set to online/offline mode
 *    Online:   Welcome, Search Options, Top Articles
 *    Offline:  Basic intro, offline warning
 *
 * Change Log:
 *
 */
namespace xenoPMT\Module\KB
{
  class Main
  {
    
    /**
     * KB Article HTML data
     * @var string HTML Data
     */
    private $_htData = "";
    
    private $_layout = 1;       // Layout to display (see enum below)
    
    // *********************
    // Makeshift Enum for Layout type
    const Layout_Main = 1;      // Display layout: search, top article
    const Layout_List = 2;      // Display a list of articles (1-50, 51-100, etc)
    
    // *********************
    // List Layout Parameters
    private $_listStart = 1;        // Start at KB-Id #
    private $_listRange = 50;       // Number of items to list (0=All)

    // *********************
    // Main Layout Parameters
    private $_searchText = "";      // Text to search for
    
    
    
    /* -------------------------------------------------- */
    
    
    public function __construct($layout = 1)
    {
      $this->_layout = $layout;
      if($layout == self::Layout_Main)
      {
        $this->_htData = $this->Generate_DefaultMain();
      }
      elseif($layout == self::Layout_List)
      {
        $this->_htData = $this->Generate_List();
      }
      else
        $this->_htData = $this->Generate_DefaultMain();
        
      
    }
    
    
    /**
     * Return page data
     */
    public function PageLayout()
    {
      return $this->_htData;
    }
    
    
    /* ################################################## */
    
    private function Generate_DefaultMain()
    {
      $html = <<<"EOT"
        <h1>Knowledge Base</h1>
        <p>
          This system is still under heavy development and is not
          ready for live action use by any means. Soon enough you will
          get to see what the future holds.  As the project develops the
          user and engineering documentation will mature along with it.</p>
        <p>Sit tight and enjoy the ride!</p>
        <p>&nbsp;</p>
        <p>- Xeno Innovations, Inc. -</p>
        <p></p>
EOT;
      return $html;
    }
    
    private function Generate_List()
    {
      $html = <<<"EOT"
        <h1>Knowledge Base</h1>
        <p>
          This feature is not yet available</p>
        <p>&nbsp;</p>
        <p>- Xeno Innovations, Inc. -</p>
        <p></p>
EOT;
      return $html;
    }
  
  }
}
?>
