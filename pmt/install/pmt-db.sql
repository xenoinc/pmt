/* ********************************************************************
 * Copyright 2010-2011 (C) Xeno Innovations, Inc.
 * ALL RIGHTS RESERVED
 * Author:
 * Document:      pmt-db.sql
 * Created Date:  Oct 31, 2010, 11:03:17 PM
 * Last Update:
 * Version:       0.2.2
 * Description:
 *   Core tables for the PMT system
 *
 * Change Log:
 * 2012-0619  + (djs) Added outline for new table TBLPMT_MODULE
 * 2012-0224  * (djs) Consulidating tables for Milestone 0.1 to this file.
 **********************************************************************/

/**
 * Sample Settings
 * ---------------
 * Default_Language       = 'en-us'
 * User_Image_Path        = 'images'  (pmt/images)
 * Ticket_Attachment_Path = ''
 * Bug_Attachemnt_Path    = ''
 * Task_Attachemnt_Path   = ''
 * Enable_Audit           = 1/0
 * Audit_Keep_Days        = 90    - User changes
 * Addon_Customer         = 1/0   - Enable customer admin
 * Addon_Product          = 1/0   - Enable product admin
 */

/* Test table */
create table if not exists `TBLPMT_SETTINGS`
(
  `Setting` varchar(255) collate utf8_unicode_ci not null,
  `Value`   longtext collate utf8_unicode_ci not null,
  primary key (`Setting`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*

CREATE TABLE IF NOT EXISTS `TBLPMT_MODULE`
(
  `Module_Id`       AUTO_INCREMENT,
  `Module_Path`     VARCHAR(), -- folder install path ("/module/kb" or "kb")
  `Module_Name`     VARCHAR(), -- name of module "kb"
  `Module_URN`      VARCHAR(16),  -- Uniform Resource Name (kb, p, customer, ..)
  `Enabled`         BOOLEAN  -- true/false
  `Description`     varchar(255)

)
*/

/*
  * NOT IN USE *

  Uniform resource identifier.
  A uniform resource name (URN) functions like a person's name, while
  a Uniform Resource Locator (URL) resembles that person's street
  address. In other words: the URN defines an item's identity, while
  the URL provides a method for finding it.
  [-----URI-----]
  [-URL-] [-URN-]

  Created: 2012-0306
*/
CREATE TABLE IF NOT EXISTS `TBLPMT_URI`
(
  `Uri_Id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `Uri_Name`  VARCHAR(256),   -- URN Name
  `Enabled`   BOOLEAN,        -- Under Construnction
  PRIMARY KEY (`Uri_Id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;





