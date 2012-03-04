/* ********************************************************************
 * Copyright 2010-2011 (C) Xeno Innovations, Inc.
 * ALL RIGHTS RESERVED
 * Author:
 * Document:      pmt-db.sql
 * Created Date:  Oct 31, 2010, 11:03:17 PM
 * Last Update:
 * Version:       0.2.2
 * Description:
 *   Proposal for new DB tables used by the system
 *
 * Change Log:
 * 2012-0224  * (djs) Consulidating tables for Milestone 0.1 to this file.
 **********************************************************************/

/* Test table */
create table if not exists `TBLPMT_SETTINGS`
(
  `Setting` varchar(255) collate utf8_unicode_ci not null,
  `Value`   longtext collate utf8_unicode_ci not null,
  primary key (`setting`)
) engine=InnoDB default charset=utf8 collate=utf8_unicode_ci;









