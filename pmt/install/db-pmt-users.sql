/*
********************************************************************
* Copyright 2010-2011 (C) Xeno Innovations, Inc.
* ALL RIGHTS RESERVED
* Author:        Damian J. Suess
* Document:      db-pmt-users.sql
* Created Date:  Oct 31, 2010, 11:03:17 PM
* Version:       0.1.9
* Description:
*   Project Management and Tracking User Database
* Change Log:
* [2012-0131] - v0.1.9
*             * Modified table names
*             + Added table, USER_INFO to normalize database
* [2010-1104] - (djs) Added new tables
* [2010-1031] - (djs) Initial creation
**********************************************************************/

-- =====================================


/*
  Used for customer logins
  Version 1.0.3
  Last Update:  2010-11-07
  Note:  Accounts should not be active until they verify their Email address
*/
create table `USER`
(
  `User_Ndx`            INT NOT NULL AUTO_INCREMENT,    -- Internal ID
  `User_Id`             VARCHAR(255) COLLATE utf8_unicode_ci NOT NULL,        -- user login name
  `User_Pass`           VARCHAR(255) COLLATE utf8_unicode_ci NOT NULL,        -- encrypted user password
  `Name_First`          VARCHAR(75),        -- First Name
  `Name_Last`           VARCHAR(75),        -- Last Name
  `Name_Middle`         VARCHAR(75),        -- Middle Name
  `Name_Title`          VARCHAR(4),         -- Dr, Mr, Mrs, Ms
  `Name_Salu`           VARCHAR(4),         -- Sr, Jr, III, IV, Esq(uire)
  `Phone1`              VARCHAR(16),         -- 111-222-333-4444 (country, area, .., ..)
  `Phone2`              VARCHAR(16),         -- 111-222-333-4444 (country, area, .., ..)
  `Email1`              VARCHAR(255) COLLATE utf8_unicode_ci NOT NULL,        --
  `Email2`              VARCHAR(255) COLLATE utf8_unicode_ci NOT NULL,        --

  `Customer_Id`         VARCHAR(10),        -- Customer ID
  `Work_Name`           VARCHAR(50),        -- Misc. If not linked to Customer table
  `Work_Addr1`          VARCHAR(50),        -- ..
  `Work_Addr2`          VARCHAR(50),        -- ..
  `Work_City`           VARCHAR(25),        -- ..
  `Work_State`          VARCHAR(25),        -- ..
  `Work_Zip`            VARCHAR(20),        -- ..
  `Work_Country`        VARCHAR(50),        -- ..

  `Account_Active`      BOOLEAN,
  `Termination_Dttm1`    DATETIME,           -- used for interns, etc
  `Created_Dttm`        DATETIME,           -- when was user created
  `Password_Exp_Dttm`   DATETIME,           -- date of password expiration

  `Last_Login_Dttm`     DATETIME,           -- Last date/time of login
  `Last_Login_Ip`       VARCHAR(15),        -- Last logged in from (IPv4)
  `Receive_Updates`     INT,                -- email updates
  primary key (`User_Ndx`)
);

create table if not exists USER_INFO
(
  `id`            bigint not null auto_increment,
  `Phone`         varchar(50),
  `Phone_Type`    varchar(10),          -- home, office, cell, business, fax
  primary key (`id`)
) engine=InnoDb default charset=utf8 collate=utf_8_uicode_ci;

/*
  User's project access level
  Workflow:  USER > PMT_USER_PRODUCT_PRIV > PMT_GROUP
  Version 1.0
  Last Update:  2010-11-07
*/
create table USER_PRODUCT_PRIV
(
  user_id       VARCHAR(15) NOT NULL,   -- User ID from 'pmt_user'
  product_id    INT NOT NULL,           -- Product ID
  group_name    VARCHAR(20) NOT NULL    -- Name of the group priv
);


/*
  Groups and Descriptions
  List the different "available" groups
  Version 1.0
  Last Update:  2010-11-07
  Priv:  SELECT p.priv_name from pmt_group g JOIN ON pmt_group_priv p WHERE g.pmt_group.group_id = p.pmt_group_priv.group_id;
*/
create table USER_GROUP
(
  group_id      VARCHAR(20),  -- Name of the group
  description   TEXT          -- Description of what this group does
);


/*
  User Group Permissions
  List the different groups via "GROUP BY group_name"
  Version 1.0
  Last Update:  2010-11-07
*/
create table USER_GROUP_PRIV
(
  group_id      VARCHAR(20),    -- Name of the group
  priv_name     VARCHAR(25)     -- Available Privilege
);
/*
  Group Name          Description
  Administrator       EVERYTHING!
  Developer           View Source, Edit Tickets
  Developer L2        View Source, Edit Tickets, Edit Wiki
  Project Manager     View Tickets, Run reports
  Customer            View/Post "this.user" tickets, View Wiki
  Customer Level 2    View/Post "this.customer" Tickets, View Wiki, Run "this.customer" Reports
  Anonymous           View wiki
*/

/*
  Available Project Priv Items (STATIC)
  Version 1.0.1
  Last Update:  2010-11-07
*/
create table S_USER_GROUP_PERMISSION
(
  priv_name     VARCHAR(25),     -- 0001, ADMIN,
  description   VARCHAR(25),     -- ALL,  Administrator
  sort_order    int              -- Used when grouped by priv_name
);

INSERT INTO s_group_permission VALUES ('ADMIN',                   'User will have access to all components', 0);
INSERT INTO s_group_permission VALUES ('REPO_BROWSER_VIEW',       'User is able to view the project files',  '0');
INSERT INTO s_group_permission VALUES ('REPO_CHANGESET_VIEW',     'User is able to view each revision desciption and compare source differences', 0);
INSERT INTO s_group_permission VALUES ('REPO_LOG_VIEW',           '', 0);
INSERT INTO s_group_permission VALUES ('REPO_FILE_VIEW',          '', 0);
INSERT INTO s_group_permission VALUES ('REPO_TIMELINE_VIEW',      '', 0);
-- INSERT INTO s_group_permission VALUES ('OTHER_CONFIG_VIEW',          'Enables additional pages on About Trac that show the current configuration or the list of installed plugins', 0);
INSERT INTO s_group_permission VALUES ('OTHER_EMAIL_VIEW',        'Shows email addresses even if `show_email_addresses` configuration option is `false`?', 0);
INSERT INTO s_group_permission VALUES ('MILESTONE_ADMIN',         '', 0);
INSERT INTO s_group_permission VALUES ('MILESTONE_CREATE',        '', 0);
INSERT INTO s_group_permission VALUES ('MILESTONE_DELETE',        '', 0);
INSERT INTO s_group_permission VALUES ('MILESTONE_MODIFY',        '', 0);
INSERT INTO s_group_permission VALUES ('MILESTONE_VIEW',          '', 0);
INSERT INTO s_group_permission VALUES ('PERMISSION_ADMIN',        '', 0);
INSERT INTO s_group_permission VALUES ('PERMISSION_GRANT',        '', 0);
INSERT INTO s_group_permission VALUES ('PERMISSION_REVOKE',       '', 0);
INSERT INTO s_group_permission VALUES ('REPORT_ADMIN',            '', 0);
INSERT INTO s_group_permission VALUES ('REPORT_CREATE',           '', 0);
INSERT INTO s_group_permission VALUES ('REPORT_DELETE',           '', 0);
INSERT INTO s_group_permission VALUES ('REPORT_MODIFY',           '', 0);
INSERT INTO s_group_permission VALUES ('REPORT_SQL_VIEW',         '', 0);
INSERT INTO s_group_permission VALUES ('REPORT_VIEW',             '', 0);
INSERT INTO s_group_permission VALUES ('ROADMAP_ADMIN',           '', 0);
INSERT INTO s_group_permission VALUES ('ROADMAP_VIEW',            '', 0);
INSERT INTO s_group_permission VALUES ('SEARCH_VIEW',             '', 0);
INSERT INTO s_group_permission VALUES ('TICKET_ADMIN',            '', 0);
INSERT INTO s_group_permission VALUES ('TICKET_APPEND',           '', 0);
INSERT INTO s_group_permission VALUES ('TICKET_CHGPROP',          '', 0);
INSERT INTO s_group_permission VALUES ('TICKET_CREATE',           '', 0);
INSERT INTO s_group_permission VALUES ('TICKET_EDIT_CC',          '', 0);
INSERT INTO s_group_permission VALUES ('TICKET_EDIT_DESCRIPTION', '', 0);
INSERT INTO s_group_permission VALUES ('TICKET_MODIFY',           '', 0);
INSERT INTO s_group_permission VALUES ('TICKET_VIEW',             '', 0);
INSERT INTO s_group_permission VALUES ('WIKI_ADMIN',              '', 0);
INSERT INTO s_group_permission VALUES ('WIKI_CREATE',             '', 0);
INSERT INTO s_group_permission VALUES ('WIKI_DELETE',             '', 0);
INSERT INTO s_group_permission VALUES ('WIKI_MODIFY',             '', 0);
INSERT INTO s_group_permission VALUES ('WIKI_VIEW',               '', 0);


