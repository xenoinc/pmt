/* ********************************************************************
 * Copyright 2010-2011 (C) Xeno Innovations, Inc.
 * ALL RIGHTS RESERVED
 * Author:
 * Document:      pmt-db-user.sql
 * Created Date:  Oct 31, 2010, 11:03:17 PM
 * Last Update:   2012-0304
 * Version:       0.2.2
 * Description:
 *   User tables.
 *
 * Change Log:
 * 2012-0304  * (djs) Cleaned semicolons from comments. Causing issues with parser.
 * 2012-0302  * (djs) Created file to have final user db changes.
 **********************************************************************/



/*
  Used for customer logins
  Version 0.2.0
  Last Update:  2010-11-07
  Note:  Accounts should not be active until they verify their Email address
*/
CREATE TABLE IF NOT EXISTS `TBLPMT_USER` (
  `User_Id`     BIGINT(20) NOT NULL AUTO_INCREMENT,
  `Username`    VARCHAR(255) COLLATE utf8_unicode_ci NOT NULL,
  `Password`    VARCHAR(255) COLLATE utf8_unicode_ci NOT NULL,  -- encrypted user password
  `Email`       VARCHAR(255) COLLATE utf8_unicode_ci NOT NULL,
  `Group_Id`    bigint(20) NOT NULL DEFAULT '2',
  `Name`        varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `Name_First`  VARCHAR(75) COLLATE utf8_unicode_ci NULL,   -- First Name
  `Name_Last`   VARCHAR(75) COLLATE utf8_unicode_ci NULL,   -- Last Name
  `Name_Middle` VARCHAR(75) COLLATE utf8_unicode_ci NULL,   -- Middle Name
  `Name_Title`  VARCHAR(4) COLLATE utf8_unicode_ci NULL,    -- Dr, Mr, Mrs, Ms
  `Name_Salu`   VARCHAR(4) COLLATE utf8_unicode_ci NULL,    -- Sr, Jr, III, IV, Esq(uire)
  `Customer_Id` VARCHAR(10) NULL,  -- Only used if it's a customer
  `Active`      BOOLEAN,      -- Account is active

  `Termination_Dttm`    DATETIME,           -- used for interns, etc
  `Created_Dttm`        DATETIME,           -- when was user created
  `Password_Exp_Dttm`   DATETIME,           -- date of password expiration
  `Session_Hash`        VARCHAR(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  `Last_Login_Dttm`     DATETIME,           -- Last date/time of login
  `Last_Login_Ip`       VARCHAR(15),        -- Last logged in from (IPv4)
  `Receive_Updates`     INT,                -- email updates
  primary key (`User_Id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*
  User Inforamtion Details
  Purpose:
    Contains the individual user data such as:
    Phone(1,..), Address, Email, Birthday
  Version 0.2.0
  Last Update:  2010-11-07
*/
CREATE TABLE IF NOT EXISTS `TBLPMT_USER_INFO`
(
  `User_Info_Id` BIGINT(20) NOT NULL AUTO_INCREMENT,
  `User_Id` BIGINT(20) NOT NULL,
  `Detail_Data` VARCHAR(255) COLLATE utf8_unicode_ci NOT NULL,
  `Detail_Type` VARCHAR(10) NOT NULL,
  PRIMARY KEY (`User_Info_Id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



/*
  Groups and Descriptions
  List the different "available" groups
  Version 0.2.0
  Last Update:  2010-11-07
  Priv:
    SELECT p.priv_name from
      pmt_group g JOIN ON pmt_group_priv p
    WHERE g.pmt_group.group_id = p.pmt_group_priv.group_id
*/
create table `TBLPMT_USER_GROUP`
(
  `Group_Id`    BIGINT(20) NOT NULL AUTO_INCREMENT,
  `Group_Name`  VARCHAR(20) COLLATE utf8_unicode_ci NOT NULL,   -- Name of the group
  `Description` VARCHAR(255) COLLATE utf8_unicode_ci NOT NULL,  -- Description of what this group does
  PRIMARY KEY (`Group_Id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `TBLPMT_USER_GROUP` (Group_Name, Description) VALUES ('ADMIN', 'System Administrator Group');
INSERT INTO `TBLPMT_USER_GROUP` (Group_Name, Description) VALUES ('DEVMNGR', 'Development Manager');
INSERT INTO `TBLPMT_USER_GROUP` (Group_Name, Description) VALUES ('PRJMNGR', 'Project Manager');
INSERT INTO `TBLPMT_USER_GROUP` (Group_Name, Description) VALUES ('DEV', 'Application Developer');
INSERT INTO `TBLPMT_USER_GROUP` (Group_Name, Description) VALUES ('CUSTOMER', 'Customer General User');
INSERT INTO `TBLPMT_USER_GROUP` (Group_Name, Description) VALUES ('CUSTOMERADMIN', 'Customer Administrator');


/*
  User Group Permissions
  List the different groups via "GROUP BY group_name"
  Version 0.2.0
  Last Update:  2010-11-07
*/
create table `TBLPMT_USER_GROUP_PRIV`
(
  `Group_Id`      VARCHAR(20) COLLATE utf8_unicode_ci NOT NULL, -- Name of the group
  `Priv_Name`     VARCHAR(25) COLLATE utf8_unicode_ci NOT NULL  -- Available Privilege
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


/*
  Available Project Priv Items (STATIC)
  Version 0.2.0
  Last Update:  2010-11-07
*/
CREATE TABLE IF NOT EXISTS `TBLPMT_S_GROUP_PERMISSION`
(
  `Priv_Name`   VARCHAR(25) COLLATE utf8_unicode_ci NOT NULL,
  `Description` VARCHAR(255) COLLATE utf8_unicode_ci NOT NULL,
  `Sort_Order`  INT
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



/*
  User's project access level
  Workflow:  USER > TBL_USER_PRODUCT_PRIV > TBL_GROUP
  Version 0.2
  Last Update:  2010-11-07
*/
CREATE TABLE IF NOT EXISTS `TBLPMT_USER_PROJECT_PRIV`
(
  Username    VARCHAR(255) COLLATE utf8_unicode_ci NOT NULL,   -- User ID from 'pmt_user'
  Project_Id  INT NOT NULL,           -- Product ID
  Group_Name  VARCHAR(20) NOT NULL    -- Name of the group priv
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



CREATE TABLE IF NOT EXISTS `TBLPMT_USER_TEAM`
(
  `Team_Id` BIGINT(20) NOT NULL AUTO_INCREMENT,
  `Team_Name` VARCHAR(255) COLLATE utf8_unicode_ci NOT NULL,
  `Active` BOOLEAN,
  PRIMARY KEY (`Team_Id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


/* Team group members, Scrum team, etc. */
CREATE TABLE IF NOT EXISTS `TBLPMT_USER_TEAM_MEMBERS`
(
  `Team_Id` BIGINT(20) NOT NULL,
  `User_Id` BIGINT(20) NOT NULL,
  PRIMARY KEY (`Team_Id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



INSERT INTO `TBLPMT_S_GROUP_PERMISSION` VALUES ('ADMIN',                   'User will have access to all components', 0);
INSERT INTO `TBLPMT_S_GROUP_PERMISSION` VALUES ('REPO_BROWSER_VIEW',       'User is able to view the project files',  '0');
INSERT INTO `TBLPMT_S_GROUP_PERMISSION` VALUES ('REPO_CHANGESET_VIEW',     'User is able to view each revision desciption and compare source differences', 0);
INSERT INTO `TBLPMT_S_GROUP_PERMISSION` VALUES ('REPO_LOG_VIEW',           '', 0);
INSERT INTO `TBLPMT_S_GROUP_PERMISSION` VALUES ('REPO_FILE_VIEW',          '', 0);
INSERT INTO `TBLPMT_S_GROUP_PERMISSION` VALUES ('REPO_TIMELINE_VIEW',      '', 0);
INSERT INTO `TBLPMT_S_GROUP_PERMISSION` VALUES ('OTHER_EMAIL_VIEW',        'Shows email addresses even if `show_email_addresses` configuration option is `false`?', 0);
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
INSERT INTO `TBLPMT_S_GROUP_PERMISSION` VALUES ('ROADMAP_ADMIN',           '', 0);
INSERT INTO `TBLPMT_S_GROUP_PERMISSION` VALUES ('ROADMAP_VIEW',            '', 0);
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







