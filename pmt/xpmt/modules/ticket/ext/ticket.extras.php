<?php

/** * *********************************************************
 * Copyright 2012 (C) Xeno Innovations, Inc.
 * ALL RIGHTS RESERVED
 * @Author:      Damian Suess
 * Document:     ticket
 * Created Date: Sep 6, 2012
 *
 * Description:
 *  Ticket enumerations and extra components
 *
 * Change Log:
 *
 */

namespace xenoPMT\Module\Ticket
{
  class ENUM_TicketMode
  {
    const TMain = 0;    // Welcome page. Search box, Create New, My Queue, [Group/Customer Queue], [Filter Listing (proj, ver, severity, date, status)]
    const TNew  = 10;
    const TView = 11;
    const TEdit = 12;
  }

  /**
   * Authorization permissions
   */
  class ENUM_TicketAuth
  {

  }

  /**
   * Filters used during listing of tickets
   */
  class ENUM_ProjectFilters
  {
    // Project
    // d, p, pv, priority, c, cv, subj

    // Product
    // date, p, pv, priority, pc, pcv, c, cn, subj
    // Date, product, product version, product component, product component version, customer name, customer number, ticket-subject

    const Date = "d";
    const Project = "p";

  }
}

?>
