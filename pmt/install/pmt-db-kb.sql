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

CREATE TABLE IF NOT EXISTS `TBLPMT_KB_ARTICLE_SETTING_0`
(
  `Article_Id`  INT UNSIGNED NOT NULL,
  `Project_Id`  INT UNSIGNED DEFAULT 0,     -- Linked to project
  `Product_Id`  INT UNSIGNED DEFAULT 0,     -- Linked to product
  `Login_Required` BOOLEAN NOT NULL DEFAULT TRUE,
  `Visible`     SMALLINT DEFAULT 0          -- Is it public or private to user (Created_Uid / Admin)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

