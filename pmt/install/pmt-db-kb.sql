/* ********************************************************************
 * Copyright 2010-2012 (C) Xeno Innovations, Inc.
 * ALL RIGHTS RESERVED
 * Author:        Damian Suess
 * Document:      db-kb.sql
 * Created Date:  2012-04-19
 * Last Update:
 * Version:       1.0.4
 *
 * Description:
 *  Knowledge Base tables
 *
 * Notes:
 *  This will be included in v0.3 and above
 *
 * Change Log:
 * 2016-06-11 + (djs) Added KB_ATTACHMENT
 * 2012-0422  + (djs) Added Login_Required to KB_ARTICLE_SETTING
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
  Simple settings for articles. When this feature becomes more mature
  and then the "_0" tag will be removed. Possible more dynamic column name.
  Version 0.3.0
  Created:  2012-04-19
  Last Update:  2012-04-22
  2012-0422 + Added `Login Required` to help keep articles private
*/
CREATE TABLE IF NOT EXISTS `TBLPMT_KB_ARTICLE_SETTING_0`
(
  `Article_Id`  INT UNSIGNED NOT NULL,
  `Project_Id`  INT UNSIGNED DEFAULT 0,     -- Linked to project
  `Product_Id`  INT UNSIGNED DEFAULT 0,     -- Linked to product
  `Login_Required` BOOLEAN NOT NULL DEFAULT TRUE,
  `Visible`     SMALLINT DEFAULT 0          -- Is it public or private to user (Created_Uid / Admin)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


/*
  KB Article Attachment
  File(s) attached to a knowledge base article
  * Archiving system should save file in a folder called something like, 'KB'
    and the subfolders will follow a HEX naming convention "1, FF, 10B, .."
  * The Attachment_Id will give clue to which subfolder it's in. Do the math to figure it out.
  Version 0.1
  Created:  2012-06-11
  Last Update:  2012-06-11

*/
CREATE TABLE IF NOT EXISTS `TBLPMT_KB_ATTACHMENT`
(
  `Attachment_Id` INT UNSIGNED NOT NULL,
  `File_Path`     VARCHAR(255) DEFAULT '',
  `File_Title`    VARCHAR(255),
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

