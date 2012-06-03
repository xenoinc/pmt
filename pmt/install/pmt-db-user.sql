CREATE TABLE IF NOT EXISTS `TBLPMT_USER`
(
  `User_Id`       INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `User_Name`     VARCHAR(50) COLLATE utf8_unicode_ci NOT NULL,
  `Password`      VARCHAR(255) COLLATE utf8_unicode_ci NOT NULL,
  `Email`         VARCHAR(255) COLLATE utf8_unicode_ci NOT NULL,
  `Group_Id`      INT UNSIGNED NOT NULL DEFAULT '0',
  `Display_Name`  varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `Name_First`    VARCHAR(75) COLLATE utf8_unicode_ci NULL,
  `Name_Last`     VARCHAR(75) COLLATE utf8_unicode_ci NULL,
  `Name_Middle`   VARCHAR(75) COLLATE utf8_unicode_ci NULL,
  `Name_Title`    VARCHAR(4) COLLATE utf8_unicode_ci NULL,
  `Name_Salu`     VARCHAR(4) COLLATE utf8_unicode_ci NULL,
  `Customer_Id`   VARCHAR(256) COLLATE utf8_unicode_ci NULL,
  `Active`        BOOLEAN,
  `Image_File`    VARCHAR(255) COLLATE  utf8_unicode_ci,
  `Termination_Dttm`    DATETIME,
  `Created_Dttm`        DATETIME,
  `Password_Exp_Dttm`   DATETIME,
  `Session_Hash`        VARCHAR(255) COLLATE utf8_unicode_ci NULL DEFAULT '0',
  `Last_Login_Dttm`     DATETIME,
  `Last_Login_Ip`       VARCHAR(15)COLLATE utf8_unicode_ci,
  `Receive_Updates`     INT,
  primary key (`User_Id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

create table if not exists `TBLPMT_USER_SETTINGS`
(
  `User_Id` INT UNSIGNED NOT NULL,
  `Setting` varchar(255) collate utf8_unicode_ci not null,
  `Value`   varchar(128) collate utf8_unicode_ci not null,
  primary key (`User_Id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


CREATE TABLE IF NOT EXISTS `TBLPMT_USER_INFO`
(
  `User_Info_Id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `User_Id` INT UNSIGNED NOT NULL,
  `Detail_Data` VARCHAR(255) COLLATE utf8_unicode_ci NOT NULL,
  `Detail_Type` VARCHAR(10) NOT NULL,
  PRIMARY KEY (`User_Info_Id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE IF NOT EXISTS `TBLPMT_USER_AUTH_COOKIE`
(
  User_Id     INT UNSIGNED,
  User_Ip     VARCHAR(45) COLLATE utf8_unicode_ci,
  Cookie      VARCHAR(35) COLLATE utf8_unicode_ci,
  Login_Dttm  DATETIME
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

create table `TBLPMT_GROUP`
(
  `Group_Id`    INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `Group_Name`  VARCHAR(20) COLLATE utf8_unicode_ci NOT NULL,
  `Description` VARCHAR(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`Group_Id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `TBLPMT_GROUP` (Group_Name, Description) VALUES ('ADMIN', 'System Administrator Group');
INSERT INTO `TBLPMT_GROUP` (Group_Name, Description) VALUES ('DEVMNGR', 'Development Manager');
INSERT INTO `TBLPMT_GROUP` (Group_Name, Description) VALUES ('PRJMNGR', 'Project Manager');
INSERT INTO `TBLPMT_GROUP` (Group_Name, Description) VALUES ('DEV', 'Application Developer');
INSERT INTO `TBLPMT_GROUP` (Group_Name, Description) VALUES ('CUSTOMER', 'Customer General User');
INSERT INTO `TBLPMT_GROUP` (Group_Name, Description) VALUES ('CUSTOMERADMIN', 'Customer Administrator');

create table `TBLPMT_GROUP_PRIV`
(
  `Group_Id`    INT UNSIGNED NOT NULL,
  `Priv_Name`   VARCHAR(25) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE IF NOT EXISTS `TBLPMT_S_GROUP_PERMISSION`
(
  `Priv_Name`   VARCHAR(25) COLLATE utf8_unicode_ci NOT NULL,
  `Description` VARCHAR(255) COLLATE utf8_unicode_ci NOT NULL,
  `Sort_Order`  INT
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE `TBLPMT_USER_GROUP`
(
  `User_Id`  INT UNSIGNED NOT NULL,
  `Group_Id` INT UNSIGNED NOT NULL
);



CREATE TABLE IF NOT EXISTS `TBLPMT_USER_TEAM`
(
  `Team_Id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `Team_Name` VARCHAR(255) COLLATE utf8_unicode_ci NOT NULL,
  `Active` BOOLEAN,
  PRIMARY KEY (`Team_Id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


CREATE TABLE IF NOT EXISTS `TBLPMT_USER_TEAM_MEMBERS`
(
  `Team_Id` INT UNSIGNED NOT NULL,
  `User_Id` INT UNSIGNED NOT NULL,
  PRIMARY KEY (`Team_Id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `TBLPMT_S_GROUP_PERMISSION` VALUES ('ADMIN',                   'User will have access to all components', 0);
INSERT INTO `TBLPMT_S_GROUP_PERMISSION` VALUES ('REPO_BROWSER_VIEW',       'User is able to view the project files',  '0');
INSERT INTO `TBLPMT_S_GROUP_PERMISSION` VALUES ('REPO_CHANGESET_VIEW',     'User is able to view each revision desciption and compare source differences', 0);
INSERT INTO `TBLPMT_S_GROUP_PERMISSION` VALUES ('REPO_LOG_VIEW',           '', 0);
INSERT INTO `TBLPMT_S_GROUP_PERMISSION` VALUES ('REPO_FILE_VIEW',          '', 0);
INSERT INTO `TBLPMT_S_GROUP_PERMISSION` VALUES ('REPO_TIMELINE_VIEW',      '', 0);
INSERT INTO `TBLPMT_S_GROUP_PERMISSION` VALUES ('OTHER_EMAIL_VIEW',        'Shows email addresses even if `show_email_addresses` configuration option is `false`?', 0);
INSERT INTO `TBLPMT_S_GROUP_PERMISSION` VALUES ('ROADMAP_ADMIN',           '', 0);
INSERT INTO `TBLPMT_S_GROUP_PERMISSION` VALUES ('ROADMAP_VIEW',            '', 0);
INSERT INTO `TBLPMT_S_GROUP_PERMISSION` VALUES ('MILESTONE_ADMIN',         '', 0);
INSERT INTO `TBLPMT_S_GROUP_PERMISSION` VALUES ('MILESTONE_CREATE',        '', 0);
INSERT INTO `TBLPMT_S_GROUP_PERMISSION` VALUES ('MILESTONE_DELETE',        '', 0);
INSERT INTO `TBLPMT_S_GROUP_PERMISSION` VALUES ('MILESTONE_MODIFY',        '', 0);
INSERT INTO `TBLPMT_S_GROUP_PERMISSION` VALUES ('MILESTONE_VIEW',          '', 0);
INSERT INTO `TBLPMT_S_GROUP_PERMISSION` VALUES ('PERMISSION_ADMIN',        '', 0);
INSERT INTO `TBLPMT_S_GROUP_PERMISSION` VALUES ('PERMISSION_GRANT',        '', 0);
INSERT INTO `TBLPMT_S_GROUP_PERMISSION` VALUES ('PERMISSION_REVOKE',       '', 0);
INSERT INTO `TBLPMT_S_GROUP_PERMISSION` VALUES ('REPORT_ADMIN',            '', 0);
INSERT INTO `TBLPMT_S_GROUP_PERMISSION` VALUES ('REPORT_CREATE',           '', 0);
INSERT INTO `TBLPMT_S_GROUP_PERMISSION` VALUES ('REPORT_DELETE',           '', 0);
INSERT INTO `TBLPMT_S_GROUP_PERMISSION` VALUES ('REPORT_MODIFY',           '', 0);
INSERT INTO `TBLPMT_S_GROUP_PERMISSION` VALUES ('REPORT_SQL_VIEW',         '', 0);
INSERT INTO `TBLPMT_S_GROUP_PERMISSION` VALUES ('REPORT_VIEW',             '', 0);
INSERT INTO `TBLPMT_S_GROUP_PERMISSION` VALUES ('SEARCH_VIEW',             '', 0);
INSERT INTO `TBLPMT_S_GROUP_PERMISSION` VALUES ('TICKET_ADMIN',            '', 0);
INSERT INTO `TBLPMT_S_GROUP_PERMISSION` VALUES ('TICKET_APPEND',           '', 0);
INSERT INTO `TBLPMT_S_GROUP_PERMISSION` VALUES ('TICKET_CHGPROP',          '', 0);
INSERT INTO `TBLPMT_S_GROUP_PERMISSION` VALUES ('TICKET_CREATE',           '', 0);
INSERT INTO `TBLPMT_S_GROUP_PERMISSION` VALUES ('TICKET_EDIT_CC',          '', 0);
INSERT INTO `TBLPMT_S_GROUP_PERMISSION` VALUES ('TICKET_EDIT_DESCRIPTION', '', 0);
INSERT INTO `TBLPMT_S_GROUP_PERMISSION` VALUES ('TICKET_MODIFY',           '', 0);
INSERT INTO `TBLPMT_S_GROUP_PERMISSION` VALUES ('TICKET_VIEW',             '', 0);
INSERT INTO `TBLPMT_S_GROUP_PERMISSION` VALUES ('WIKI_ADMIN',              '', 0);
INSERT INTO `TBLPMT_S_GROUP_PERMISSION` VALUES ('WIKI_CREATE',             '', 0);
INSERT INTO `TBLPMT_S_GROUP_PERMISSION` VALUES ('WIKI_DELETE',             '', 0);
INSERT INTO `TBLPMT_S_GROUP_PERMISSION` VALUES ('WIKI_MODIFY',             '', 0);
INSERT INTO `TBLPMT_S_GROUP_PERMISSION` VALUES ('WIKI_VIEW',               '', 0);
