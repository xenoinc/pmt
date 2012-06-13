/* *********************************************************************
 * Copyright 2010 (C) Xeno Innovations, Inc.
 * ALL RIGHTS RESERVED
 * Author:        Damian J. Suess
 * Document:      pmt-db-project.sql
 * Created Date:  2010-11-07
 * Version:       0.2.0
 * Description:
 *   Project tables provides the basic layout of the database tables used by project files
 *
 * Change Log:
 * 2012-0606  - Removed "AUTO_INCREMENT" from Component_Version.Component_Id (bug)
 * 2012-0422  + Moved 'TBLPMT_USER_PROJECT_PRIV' here as 'TBLPMT_PROJECT_PRIV'
 * 2012-0305  * Updated all IP Address columns from width 15 to 45 (IPv6)
 *            * Formatted column names to camel case, tables to caps
 * 2012-0304  * Included into PMT 0.2 (djs)
 *            + Modified table names & some rows
 * 2010-1114  * RC 1.1 (djs)
 * 2010-1107  + Initial creation (djs)
 **********************************************************************/

/* Currently removed until out of BETA status & project is more well-defined
  CREATE TABLE proj_user_internal (...)   -- local database, only used if project doesn't use GLOBAL USERS
  CREATE TABLE pmt_user_priv( ... )       -- User's project access level
  CREATE TABLE pmt_user_priv_list ( ... ) -- Available Project Priv Items (STATIC)
*/


/*
  Created: 2010-1007
  * 2012-0304 * Changed Upadted_User_Name to Updated_User_Id
*/
CREATE TABLE IF NOT EXISTS `TBLPMT_PROJECT`
(
  `Project_Id`          INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `Project_Name`        VARCHAR(255) COLLATE utf8_unicode_ci NOT NULL,
  `Project_Description` VARCHAR(255) collate utf8_unicode_ci NOT NULL,
  `Created_Dttm`        DateTime,
  `Updated_User_Id`     INT UNSIGNED,
  primary key (`Project_Id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*
  Main Project Version
  Version 1.0
  Created:  2010-11-06
*/
CREATE TABLE IF NOT EXISTS `TBLPMT_PROJECT_VERSION`
(
  `Project_Version_Id`  INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `Product_Id`          INTEGER NOT NULL,
  `Version_Number`      VARCHAR(32) NOT NULL,                           -- Name of the version (branch/tag).  i.e. "1.0", "1.2", "1.4.flex"
  `Version_Code_Name`   VARCHAR(255) COLLATE utf8_unicode_ci NOT NULL,  -- nighthawk, blackhawk
  `Updated_User_Name`   VARCHAR(255) COLLATE utf8_unicode_ci NOT NULL,  --
  `Released_Dttm`       DATETIME,                                       -- (YYYYMMDD HH:MM:SS)
  `Default`             BOOLEAN,                                        -- Default project version
  PRIMARY KEY (`Project_Version_Id`)
  -- Create Forgin Key
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*
  Project Components
  Project Editions, Revisions, Sub-Projects
  Version 0.2.2
  Created:  2010-11-06
  * 2012-0606 + Added `Project_Version` to help identify where components belong
  * 2012-0306 - `Owner_Name` VARCHAR(15) COLLATE utf8_unicode_ci NOT NULL,   -- Default Owner, NOTE: Next release, use table 'group_component' to list all compnts assigned to what group (used in reports/issue listing)
  * 2012-0305 + Added `Project_Id` to assign the component to project
*/
CREATE TABLE IF NOT EXISTS `TBLPMT_PROJECT_COMPONENT`
(
  `Component_Id`    INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `Component_Name`  VARCHAR(128) COLLATE utf8_unicode_ci NOT NULL,  -- Project Component Name (Core, DocuFAST, QuantiFAST, PIE, ALL, FlexDx, ..)
  `Project_Id`      INT UNSIGNED NOT NULL,
  `Project_Version` VARCHAR(15) COLLATE utf8_unicode_ci ,            -- Full version number (1.22.333.44444) or (1.5)
  `Description`     MEDIUMBLOB,                                     -- description (All Fdx 1.x Products.  This includes, ...)
  PRIMARY KEY (`Component_Id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*
  Project Component Version
  (proj) xRehab=1.0, (component) ROM=1.2
  Version 1.1
  Last Update:  2010-11-06
  * 2012-0606 - Removed "AUTO_INCREMENT" from Component_Id (bug)
  * 2012-0306 - `Component_Name`  VARCHAR(128) COLLATE utf8_unicode_ci NOT NULL,  -- Range of Motion
  * Expanded Component_Name from 20 to 100
*/
CREATE TABLE IF NOT EXISTS `TBLPMT_PROJECT_COMPONENT_VERSION`
(
  `Component_Id`    INT UNSIGNED NOT NULL,
  `Version_Number`  VARCHAR(24) COLLATE utf8_unicode_ci NOT NULL,   -- 10.255.4345
  `Released_Dttm`   DATETIME,                       --
  `Is_Default`      BOOLEAN DEFAULT FALSE,          -- This is the default project component
  PRIMARY KEY (`Component_Id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


/*
  Project Milestone
  Release dates and deadlines. This will mostly be used by Development & PMs
  Version 1.0
  Last Update:  2010-11-06
*/
CREATE TABLE IF NOT EXISTS `TBLPMT_PROJECT_MILESTONE`
(
  `Milestone_Id`    INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `Milestone_Title` VARCHAR(64) COLLATE utf8_unicode_ci NOT NULL,    -- MMT v1.0, MMT v1.4, MMT 1.4.Flex, NextLevel
  `Due_Date`        DATETIME,     -- Project Due Date
  `Completed_Dttm`  DATETIME,     -- When was it completed? (NULL)
  `Description`     MEDIUMBLOB,         -- What is it and what's special about it
  PRIMARY KEY (`Milestone_Id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
-- TITLE                      DUE                 Completed           Default
-- MMT - Version 1.0          YYYYMMDD HH:MM:SS   YYYYMMDD HH:MM:SS   0
-- MMT - Version 1.1          YYYYMMDD HH:MM:SS   YYYYMMDD HH:MM:SS   0
-- MMT - Version 1.4.std      YYYYMMDD HH:MM:SS   YYYYMMDD HH:MM:SS   0
-- MMT - Version 1.4.flex     YYYYMMDD HH:MM:SS   YYYYMMDD HH:MM:SS   0


/*
  Available Statistical Reports for the project
  Version 1.0
  Last Update:  2010-11-06
*/
CREATE TABLE IF NOT EXISTS `TBLPMT_PROJECT_REPORT`
(
  `Report_Id` INT NOT NULL AUTO_INCREMENT,
  `Title`     VARCHAR(50) COLLATE utf8_unicode_ci NOT NULL,   -- Name of the report
  `Author`    VARCHAR(50) COLLATE utf8_unicode_ci NOT NULL,   -- Full name of person
  `Version`   VARCHAR(10) COLLATE utf8_unicode_ci NOT NULL,   -- Report Version
  `Mod_dttm`  DATETIME,           -- Late Modified Date
  `Query`     MEDIUMBLOB,                -- Code behind it
  PRIMARY KEY (`Report_Id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


/*
  Wiki Pages
  Version: 1.0.3
  Crated: 2010-11-13
  * 2012-0306 - `Data_Type` VARCHAR(5) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'wiki', -- Page Data Type ('wiki1', 'html', 'text')
  *           - UNIQUE INDEX 'name_title'
  * 2011-0305 - Removed row, Data_Type - This is unneeded. Strictly use WIKI text
*/
CREATE TABLE IF NOT EXISTS `TBLPMT_PROJECT_WIKI`
(
  `Page_Id`       INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `Page_Title`    VARCHAR(100) COLLATE utf8_unicode_ci NOT NULL,    -- Page Title
  `Page_Counter`  INT UNSIGNED NOT NULL DEFAULT 0,                  -- Page Counter
  `Version`       INT UNSIGNED NOT NULL DEFAULT 1,                  -- page revision
  `User_Id`       INT UNSIGNED,                                     -- Author of the page (larger than default user, just incase)
  `User_Name`     VARCHAR(50) COLLATE utf8_unicode_ci NOT NULL,     -- Author name (for archiving purposes)
  `Update_Dttm`   DATETIME,                                         -- Last updated date time
  `Update_Ip`     VARCHAR(15) COLLATE utf8_unicode_ci DEFAULT NULL, -- IPv4 of who updated it last
  `Page_Data`     MEDIUMBLOB,                                       -- Page code
  PRIMARY KEY (`Page_Id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*
  INSERT INTO `TBLPMT_PROJECT_WIKI`
  (Page_Title, Author_Uid, Update_Dttm, Data_Type, page_data) VALUES
  ('pmt_main', 'xi_pmt', '2010-11-07 00:00:00.000', 'wiki', '''''Welcome to your personal xiPMT Project Space''''')
*/

/*
  Log changes made to the wiki (see MediaWiki for details)
  ** Not to be used until 1.5 or 2.0
  CREATE TABLE wiki_log ()
*/


/*
  User's project access level
  Workflow:  USER > TBL_USER_PRODUCT_PRIV > TBL_GROUP
  Version:  0.2
  Created:  2010-11-07
  * Possibly remove User_Name & only use Group_Id & have it link back
    to the GROUP table to list the Users whom are allowed access.

  * 2012-0422 * Changed name 'TBLPMT_USER_PROJECT_PRIV' > 'TBLPMT_PROJECT_PRIV'
*/
CREATE TABLE IF NOT EXISTS `TBLPMT_PROJECT_PRIV`
(
  `User_Name`   VARCHAR(255) COLLATE utf8_unicode_ci NOT NULL,   -- User ID from 'pmt_user'
  `Project_Id`  INT UNSIGNED NOT NULL,  -- Product ID
  `Group_Id`    SMALLINT UNSIGNED NOT NULL   -- Name of the group priv
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

