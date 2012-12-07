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
 * 2012-1004  * (djs) Renamed TBLPMT_MODULE_CONFIG (from _config_module)
 * 2012-0907  + (djs) Enabled TBLPMT_MODULE and added UUID column
 *            -       Removed TBLPMT_MODULE_URI (not currently needed)
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
create table if not exists `TBLPMT_CORE_SETTINGS`
(
  `Setting` varchar(255) collate utf8_unicode_ci not null,
  `Value`   longtext collate utf8_unicode_ci not null,
  primary key (`Setting`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*
  Module registry
  Created: 2012-0619
  Updated:
    2012-0907 + Added column UUID to identify modules and their forks
*/

CREATE TABLE IF NOT EXISTS `TBLPMT_CORE_MODULE`
(
  `Module_Id`         INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `Module_UUID`       VARCHAR(36) NOT NULL,                           -- Unique Identifer of registered module. No two should be the same
  `Core`              BOOLEAN NOT NULL DEFAULT FALSE,                 -- Is this a core module? DEFAULT=FALSE
  `Enabled`           BOOLEAN NOT NULL DEFAULT FALSE,                 -- Disable all new modules by default
  `Module_Name`       VARCHAR(64) collate utf8_unicode_ci not null,   -- Name of module "kb"
  `Module_Version`    VARCHAR(16),                                    -- Version number/Name (0.2 nighthawk)
  `Module_Path`       VARCHAR(255) collate utf8_unicode_ci not null,  -- main install path ("/module/kb/kb.php" or "kb.php")
  `Module_Namespace`  VARCHAR(255) collate utf8_unicode_ci not null,  -- Namespace of the Module (required by class.setup.php)
  `Module_Class`      VARCHAR(255) collate utf8_unicode_ci not null,  -- Class name to be called
  `Module_URN`        VARCHAR(16) NOT NULL,                           -- BASE Uniform Resource Name (kb, p, customer, ..)
  `Description`       VARCHAR(255) collate utf8_unicode_ci not null,
  primary key (`Module_Id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*
  Module Configuration Settings

*/
create table if not exists `TBLPMT_CORE_MODULE_CONFIG`
(
  `Module_UUID`   VARCHAR(36),
  `Setting` varchar(255) collate utf8_unicode_ci not null,
  `Value`   longtext collate utf8_unicode_ci not null,
  primary key (`Module_UUID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*
  During the install of Modules data is insert into this.
  This is to used for User/Group permissions and is setup by the Administrators
  Used with XIPMT_GROUP_PRIV and/or XIPMT_USER_MODULE_PRIV table (if one exists)

  Created: 2012-0901
  2012-0923 + Added missing columns and partial description
            + Added to standard install
*/
CREATE TABLE IF NOT EXISTS `TBLPMT_S_CORE_MODULE_PRIV`
(
  `Module_UUID`       VARCHAR(36) NOT NULL,
  `Priv_Name`         VARCHAR(32),
  `Priv_Description`  VARCHAR(64),
  `DataType`          VARCHAR(8),     -- 'string', 'integer'
  `DefaultValue`      VARCHAR(64),    -- Default setting suggested to user
  primary key (`Module_UUID`)
);


/*
  Module Uniform Resource Name  - Give your module a home!
  This is only used for modules with multiple URN IDs.. currently deprecated
    Note:
      DO NOT allow'/' in your name when identifying the URN (uniform resource identifier)

    Used in "pmt.php/PmtParseURL()" to accept the 'p' in "project/prj/p" as valid uri segments name
    This is an extenion of "_MODULE.Module_URN"

    Uniform resource identifier.
      A uniform resource name (URN) functions like a person's name, while
      a Uniform Resource Locator (URL) resembles that person's street
      address. In other words: the URN defines an item's identity, while
      the URL provides a method for finding it.
      [-----URI-----]
      [-URL-] [-URN-]

  Created: 2012-0619
  Updated:
    2012-0923 - Removed Module_Name comment. This was breaking the installer
              -- `Module_Name`  VARCHAR(64) collate utf8_unicode_ci not null -- folder install path ("/module/kb" or "kb")
    2012-0907 * Removed `Module_Name` and replaced it with `Module_UUID`
*/

CREATE TABLE IF NOT EXISTS `TBLPMT_CORE_MODULE_URN`
(
  `Module_UUID`   VARCHAR(36) NOT NULL,                             -- This is better than using Module_Id
  `Module_URN`    VARCHAR(16)                                       -- don't let people get crazy with lengths
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


/*
  * NOT IN USE *
  Intention was for the dynamic naming structure of modules or other (wiki) pages. Deprecated.
  Created: 2012-0306
* /

CREATE TABLE IF NOT EXISTS `TBLPMT_CORE_URI`
(
  `Uri_Id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `Uri_Name`  VARCHAR(256),   -- URN Name
  `Enabled`   BOOLEAN,        -- Under Construnction
  PRIMARY KEY (`Uri_Id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci

*/



