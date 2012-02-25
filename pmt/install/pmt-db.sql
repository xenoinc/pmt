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
 * 2012-0131  - v0.2.2 (djs)
 *            * Expanded Product_Name from 50 to 255.
 *            * Changed table name to all caps. Columns to Camel_Case
 *            * VarChar now uses:  utf8_unicode_ci
 * [2011-0829]
 *    - v0.0.1
 *    - Combined old table columns & added [+ add], [- rmv], [** modificaiton] respectively
 * [2011-0825]
 *    - Initial file creation
 * [2011-0825]
 *    * Fixed column type (there were errors)
 *    + Added xi_product.version_major
 *    - Removed pmt-user tables
 * [2011-0811]
 *    - Initial NEW DB Format proposal idea
 * [2010-1107]
 *    * Changed file name from 'db-struct' to 'db-main' to denote main XI/PMT tables
 *    * Split PMT Project tables to 'db-project.sql'
 * [2010-1104] + Added new tables
 * [2010-1031] * Initial creation
 **********************************************************************/

create table if not exists `PMT_SETTINGS`
(
  `setting` varchar(255) collate utf8_unicode_ci not null,
  `value`   longtext collate utf8_unicode_ci not null,
  primary key (`setting`)
) engine=InnoDB default charset=utf8 collate=utf8_unicode_ci;
