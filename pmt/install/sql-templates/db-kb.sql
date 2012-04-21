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
 * 2012-0420  * (djs) Updated table structures
 *            * (djs) Named ALPHA tables with '_0' to signify differences
 * 2012-0419  * (djs) initial creation
 **********************************************************************/

/** To Do
 * [ ] Finalize 1.0 db model
 * [ ] Include in Installer
 */



/* ##[ 0.3 Tables ]##################### */


/*
  KB Article (Alpha Version)
  This allows for simple articles
  Version 0.3.0
  Created:  2012-04-19
  Last Update:  2012-04-20
*/
CREATE TABLE IF NOT EXISTS `TBLPMT_KB_ARTICLE_0`
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
CREATE TABLE IF NOT EXISTS `TBLPMT_KB_ARTICLE_SETTING_0`
(
  `Article_Id`  INT UNSIGNED NOT NULL,
  `Project_Id`  INT UNSIGNED DEFAULT 0,     -- Linked to project
  `Product_Id`  INT UNSIGNED DEFAULT 0,     -- Linked to product
  `Visible`     SMALLINT DEFAULT 0          -- Is it public or private to user (Created_Uid / Admin)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



/* ##[ 0.5 Tables ]##################### */


-- ** Not used yet **
-- Future KB table to be used
CREATE TABLE IF NOT EXISTS `TBLPMT_KB_ARTICLE`
(
  `Article_Id`      INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `Article_Data_Id` INT UNSIGNED NOT NULL,
  `Title`           VARCHAR(255) COLLATE utf8_unicode_ci NOT NULL,
  `Subject`         VARCHAR(255) COLLATE utf8_unicode_ci NOT NULL,
  `Article_Type`    VARCHAR(16) COLLATE utf8_unicode_ci NOT NULL,   -- howto, solution, general, fyi
  primary key (`Kb_Id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


-- ** Not used yet **
-- KB Article Information. This is to be used for updates & rollbacks
CREATE TABLE IF NOT EXISTS `TBLPMT_KB_ARTICLE_DATA`
(
  `Article_Data_Id` INT UNSIGNED NOT NULL AUTO_INCREMENT,         -- Unique Identifier
  `Article_Id`      INT UNSIGNED NOT NULL,                        -- KB Article to link to
  `Revision_Num`    INT UNSIGNED NOT NULL,                        -- Version number (1, 2, 3, 4)
  `Created_Uid`     INT UNSIGNED NOT NULL,                        -- Create by User_Id
  `Created_Dttm`    DATETIME,                                     -- Date created
  `Viewable`        SMALLINT,
  `Article_Data`    LONGTEXT COLLATE utf8_unicode_ci NOT NULL,
  primary key (`Kb_Revision_Id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


-- ** Not used yet **
-- Comments to the KB Article
CREATE TABLE IF NOT EXISTS `TBLPMT_KB_ARTICLE_COMMENT`
(
  `Kb_Id`           INT UNSIGNED NOT NULL,
  `Created_Uid`     INT NOT NULL,
  `Modified_Dttm`   DATETIME,
  `User_Id`         INT UNSIGNED NOT NULL,
  `Comment_Data`    LONGTEXT COLLATE utf8_unicode_ci NOT NULL,
  primary key (`Kb_Id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



-- ** Not used yet **
-- List of how items are to be viewed
-- Project, Product, General Q/A
CREATE TABLE IF NOT EXISTS `TBLPMT_KB_RELATED`
(
  `Kb_Id`   INT UNSIGNED NOT NULL,
  `Relation_Id` INT UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


-- ** Not used yet **
-- Static table to assist in the type of article
CREATE TABLE IF NOT EXISTS `TBLPMT_S_KB_RELATION`
(
  `Relation_Id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `Relation_Description` VARCHAR(24)  collate utf8_unicode_ci not null,
  primary key (`Relation_Id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `TBLPMT_S_KB_RELATION` (Relation_Id, Relation_Description) Values
( 1, 'Project' );
INSERT INTO `TBLPMT_S_KB_RELATION` (Relation_Id, Relation_Description) Values
( 2, 'Product' );
INSERT INTO `TBLPMT_S_KB_RELATION` (Relation_Id, Relation_Description) Values
( 3, 'General' );

