/*
********************************************************************
* Copyright 2010-2011 (C) Xeno Innovations, Inc.
* ALL RIGHTS RESERVED
* Author:        Damian J. Suess
* Document:      db-pmt-users.sql
* Created Date:  Oct 31, 2010, 11:03:17 PM
* Version:       0.1.8
* Description:
*   Project Management and Tracking User Database
* Change Log:
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
create table pmt_user
(
  user_ndx            INT NOT NULL AUTO_INCREMENT,    -- Internal ID
  user_id             VARCHAR(15),        -- user login name
  user_pass           VARCHAR(20),        -- encrypted user password
  name_first          VARCHAR(20),        -- First Name
  name_last           VARCHAR(20),        -- Last Name
  name_middle         VARCHAR(20),        -- Middle Name
  name_title          VARCHAR(4),         -- Dr, Mr, Mrs, Ms
  name_salu           VARCHAR(4),         -- Sr, Jr, III, IV, Esq(uire)
  phone1              VARCHAR(16),         -- 111-222-333-4444 (country, area, .., ..)
  phone2              VARCHAR(16),         -- 111-222-333-4444 (country, area, .., ..)
  email1              VARCHAR(50),        --
  email2              VARCHAR(50),        --

  customer_id         VARCHAR(10),        -- Customer ID
  work_name           VARCHAR(50),        -- Misc. If not linked to Customer table
  work_addr1          VARCHAR(50),        -- ..
  work_addr2          VARCHAR(50),        -- ..
  work_city           VARCHAR(25),        -- ..
  work_state          VARCHAR(25),        -- ..
  work_zip            VARCHAR(20),        -- ..
  work_country        VARCHAR(50),        -- ..

  account_acctive     BOOLEAN,
  termination_dttm    DATETIME,           -- used for interns, etc
  created_dttm        DATETIME,           -- when was user created
  password_exp_dttm   DATETIME,           -- date of password expiration

  last_login_dttm     DATETIME,           -- Last date/time of login
  last_login_ip       VARCHAR(15),        -- Last logged in from (IPv4)
  receive_updates     INT                 -- email updates
);

/*
  User's project access level
  Workflow:  USER > PMT_USER_PRODUCT_PRIV > PMT_GROUP
  Version 1.0
  Last Update:  2010-11-07
*/
create table pmt_user_product_priv
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
create table pmt_group
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
create table pmt_group_priv
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
create table s_group_permission
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


