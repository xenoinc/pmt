CREATE TABLE IF NOT EXISTS `TBLPMT_PROJECT`
(
  `Project_Id`          INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `Project_Name`        VARCHAR(255) COLLATE utf8_unicode_ci NOT NULL,
  `Project_Description` VARCHAR(255) collate utf8_unicode_ci NOT NULL,
  `Created_Dttm`        DateTime,
  `Updated_User_Id`     INT UNSIGNED,
  primary key (`Project_Id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE IF NOT EXISTS `TBLPMT_PROJECT_COMPONENT`
(
  `Component_Id`    INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `Component_Name`  VARCHAR(128) COLLATE utf8_unicode_ci NOT NULL,  -- Project Component Name (Core, DocuFAST, QuantiFAST, PIE, ALL, FlexDx, ..)
  `Project_Id`      INT UNSIGNED NOT NULL,
  `Description`     MEDIUMBLOB,                -- description            (All Fdx 1.x Products.  This includes, ...)
  PRIMARY KEY (`Component_Id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE IF NOT EXISTS `TBLPMT_PROJECT_COMPONENT_VERSION`
(
  `Component_Id`    INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `Version_Number`  VARCHAR(24) COLLATE utf8_unicode_ci NOT NULL,   -- 10.255.4345
  `Released_Dttm`   DATETIME,                       --
  `Is_Default`      BOOLEAN DEFAULT FALSE,          -- This is the default project component
  PRIMARY KEY (`Component_Id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE IF NOT EXISTS `TBLPMT_PROJECT_MILESTONE`
(
  `Milestone_Id`    INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `Milestone_Title` VARCHAR(64) COLLATE utf8_unicode_ci NOT NULL,    -- MMT v1.0, MMT v1.4, MMT 1.4.Flex, NextLevel
  `Due_Date`        DATETIME,     -- Project Due Date
  `Completed_Dttm`  DATETIME,     -- When was it completed? (NULL)
  `Description`     MEDIUMBLOB,         -- What is it and what's special about it
  PRIMARY KEY (`Milestone_Id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


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

CREATE TABLE IF NOT EXISTS `TBLPMT_PROJECT_WIKI`
(
  `Page_Id`       INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `Page_Title`    VARCHAR(100) COLLATE utf8_unicode_ci NOT NULL,  -- Page Title
  `Page_Counter`  INT UNSIGNED NOT NULL DEFAULT 0,     -- Page Counter
  `Version`       INT UNSIGNED NOT NULL DEFAULT 1,              -- page revision
  `User_Id`       INT UNSIGNED,                                   -- Author of the page (larger than default user, just incase)
  `User_Name`     VARCHAR(50) COLLATE utf8_unicode_ci NOT NULL,   -- Author name (for archiving purposes)
  `Update_Dttm`   DATETIME,                                       -- Last updated date time
  `Update_Ip`     VARCHAR(15) COLLATE utf8_unicode_ci DEFAULT NULL,  -- IPv4 of who updated it last
  `Page_Data`     MEDIUMBLOB,                                           -- Page code
  PRIMARY KEY (`page_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE IF NOT EXISTS `TBLPMT_PROJECT_PRIV`
(
  `User_Name`   VARCHAR(255) COLLATE utf8_unicode_ci NOT NULL,   -- User ID from 'pmt_user'
  `Project_Id`  INT UNSIGNED NOT NULL,  -- Product ID
  `Group_Id`    SMALLINT UNSIGNED NOT NULL   -- Name of the group priv
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
