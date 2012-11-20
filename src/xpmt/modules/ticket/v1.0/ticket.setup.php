<?php

/** * *********************************************************
 * Copyright 2012 (C) Xeno Innovations, Inc.
 * ALL RIGHTS RESERVED
 * @Author:      Damian Suess
 * Document:     ticket
 * Created Date: Sep 6, 2012
 *
 * Description:
 *  Ticket viwer
 *
 * Change Log:
 *
 */

namespace xenoPMT\Module\Ticket
{
  class setup
  {

    function install()
    {
      // 1) Verify if prev-installed / compatable
      // 2) Execute the code in (ext/pmt-db-ticket.sql)

      /*
       * Gui Options:
       *  [ ] Insert test data
       *      * Group: "Active" - "dev1-active"
       *      * Group: "Backlog" - "dev1-backlog"
       */

    }

    function uninstall()
    {
      // 1) Verify that nothing else is linking back to tickets
      // 2) Execute the uninstall script
    }

  }
}

?>
