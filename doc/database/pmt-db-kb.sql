/* ********************************************************************
 * Copyright 2010-2012 (C) Xeno Innovations, Inc.
 * ALL RIGHTS RESERVED
 * Author:        Damian Suess
 * Document:      db-kb.sql
 * Created Date:  2012-04-19
 * Last Update:
 * Version:       0.0.1
 * 
 * Description:
 *  Knowledge Base tables
 *
 * Notes:
 *  This will be included in v0.3 and above
 *
 * Change Log:
 * 2012-0420  + (djs) Added Table rows to TBLPMT_KB0
 *            * (djs) Split away from v0.5 tables. Keeping it basic for testing.
 * 2012-0419  * (djs) initial creation
 **********************************************************************/

/* ##[ 0.3 Tables ]##################### */

/*
  KB Article (Alpha Version)
  This allows for simple articles
  Version 0.3.0
  Created:  2012-04-19
  Last Update:  2012-04-20
*/
CREATE TABLE IF NOT EXISTS `TBLPMT_KB_ARTICLE`
(
  `Article_Id`    INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `Article_Type`  VARCHAR(16) COLLATE  utf8_unicode_ci,           -- "How To", "General", "Solution"
  `Title`         VARCHAR(255) COLLATE utf8_unicode_ci NOT NULL,
  `Subject`       VARCHAR(255) COLLATE utf8_unicode_ci NOT NULL,
  `Article_Data`  LONGTEXT COLLATE utf8_unicode_ci NOT NULL,
  `Created_Uid`   INT UNSIGNED,
  `Created_Dttm`  DATETIME,
  `Modified_Dttm` DATETIME,
  PRIMARY KEY (`Article_Id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


/*
  KB Article Settings
  This allows for simple articles
  Version 0.3.0
  Created:  2012-04-19
  Last Update:  2012-04-20
*/
CREATE TABLE IF NOT EXISTS `TBLPMT_KB_ARTICLE_SETTING`
(
  `Article_Id`  INT UNSIGNED NOT NULL,
  `Project_Id`  INT UNSIGNED DEFAULT 0,     -- Linked to project
  `Product_Id`  INT UNSIGNED DEFAULT 0,     -- Linked to product
  `Visible`     SMALLINT DEFAULT 0          -- Is it public or private to user (Created_Uid / Admin)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

