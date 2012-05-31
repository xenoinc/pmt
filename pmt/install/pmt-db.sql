create table if not exists `TBLPMT_SETTINGS`
(
  `Setting` varchar(255) collate utf8_unicode_ci not null,
  `Value`   longtext collate utf8_unicode_ci not null,
  primary key (`Setting`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE IF NOT EXISTS `TBLPMT_URI`
(
  `Uri_Id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `Uri_Name`  VARCHAR(256),   -- URN Name
  `Enabled`   BOOLEAN,        -- Under Construnction
  PRIMARY KEY (`Uri_Id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;





