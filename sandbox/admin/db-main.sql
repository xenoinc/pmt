/**********************************************************************
* Copyright 2010 (C) Xeno Innovations, Inc.
* ALL RIGHTS RESERVED
* Author:        Damian J. Suess
* Document:      db-struct.sql
* Created Date:  Oct 31, 2010, 11:03:17 PM
* Version:       0.1.8
* Description:
*   Provides the basic layout of the database tables used by the system
* Change Log:
* [2010-1107] - (djs) Changed file name from 'db-struct' to 'db-main' to denote main XI/PMT tables
*               (djs) Split PMT Project tables to 'db-project.sql'
* [2010-1104] - (djs) Added new tables
* [2010-1031] - (djs) Initial creation
**********************************************************************/

/*
  To license software we will need the serial numbers of the following:
    - Hardware S/N  (MMT [Dianomometer], ROM, )
    - Package S/N   (Hardware, documentation, etc)
*/


/*
  Available projects and descriptions Table
  Version:      1.0
  Last Update:  2010-11-05
*/
create table xi_product
(
  product_id          UNSIGNED INT NOT NULL,    -- Custom Product ID Number
  product_name        VARCHAR(50) NOT NULL,     -- Name of the product
  product_desc        BLOB,                     -- Description of the product
  
--product_version     VARCHAR(15),              -- (Branch) Version  **see below**
  pmt_path            VARCHAR(256),             -- location in PMT
  release_dttm        DATETIME,                 -- Product Go-Live
  update_dttm         DATETIME,                 -- YYYYMMDD of last detail update
  decommission_dttm   DATETIME,                 -- Date of product decommission (past, present, future)
  changed_uid         INT,                      -- User who made last update
  allow_global_users  BOOLEAN DEFAULT FALSE     -- Allow the master list of users to view product
);
-- 01, Fdx-MMT, "blah ..", "1.4",

/*
  Product Version
  Available Project Versions
  Version:      1.0
  Last Update:  2010-11-13
  :: List Versions:  SELECT version FROM xi_project_version WHERE project_name = '...' ORDER BY version
*/
CREATE TABLE xi_product_version
(
  product_name        VARCHAR(50) NOT NULL,     -- Project Name
  version             VARCHAR(15),              -- (Branch/Tag) Version. I.E. "1.0", "1.2", "1.4.flex"
  is_default          BOOLEAN,                  -- Should this be the default selection for Tickets/Bugs/etc (Default=0)

  pmt_path            VARCHAR(256),             -- location in PMT
  release_dttm        DATETIME,                 -- Product Go-Live (YYYYMMDD HH:MM:SS)
  update_dttm         DATETIME,                 -- YYYYMMDD of last detail update
  decommission_dttm   DATETIME,                 -- Date of product decommission (past, present, future)
  changed_uid         VARCHAR(15),              -- User who made last update
  allow_global_users  BOOLEAN DEFAULT FALSE,    -- Allow the master list of users to access view product version

  description         BLOB                      -- Details on what's special about this version
);


/* used with the customer sync table */
-- Version 1.0
-- Last Update:  2010-11-06
CREATE TABLE xi_customer
(
  customer_ndx_id   INT NOT NULL AUTO_INCREMENT,    -- Only used by internal indexing
  customer_id       VARCHAR(10),                -- Custom Customer ID
  customer_name     VARCHAR(256),               -- Full Customer Name
  licensed_to       VARCHAR(50),                -- Being used by specific person(s)  (Customer: UPMC; Licensed To: Dr SOnSO)
  address1          VARCHAR(100),               -- PO Box 13543
  address2          VARCHAR(100),               -- Suite 23
  addr_city         VARCHAR(50),                -- Pittsburgh
  addr_state        VARCHAR(50),                -- Pennsylvania
  addr_zip          VARCHAR(10),                -- 15203-1234
  addr_country      VARCHAR(50),                -- United States of America
  website           VARCHAR(256),               -- Customer's Website
  phone1            VARCHAR(25),                -- "+001-412-111-0000 x1234.."
  phone2            VARCHAR(25),                --
  fax1              VARCHAR(25),                --
  email1            VARCHAR(50),                -- Default Contact Email
  email2            VARCHAR(50),                --
  -- Added for Fdx-MMT 1.x compatibility
  custom1           VARCHAR(50),                -- Custom Address Line 1, ...
  custom2           VARCHAR(50),                -- Special Contact Name, etc.
  custom3           VARCHAR(50),
  custom4           VARCHAR(50),
);


/*
  Customer's Fdx-MMT 1.0 Info
  Version:     1.0.1
  Last Update: 2010-11-13
*/
CREATE TABLE xi_customer_mmt1
(
  customer_id       VARCHAR(10),        -- Unique Customer ID defined in 'xi_customer'
  mmt1_cid          VARCHAR(10),        -- Fdx-MMT1 Customer ID [actually 8 chars]
  mmt1_id_mode      SMALLINT,           -- 1="std"  2="line mode"
);


/*
  Keeps record of what products the customer purchased
  Version 1.0.1
  Last Update:  2010-11-10
 */
create table xi_customer_product
(
--cp_id           INT NOT NULL AUTO_INCREMENT,  -- Customer Product ID
  customer_id     VARCHAR(10) NOT NULL,         -- Customer ID
  product_id      INT NOT NULL,                 -- Product ID
  product_cost    FLOAT,                        -- Total Cost
  paid_infull     INT,                          -- Did they pay their full bill?
  num_licenses    UNSIGNED INT,                 -- How many licenses are they allowed?
  payment_plan    BOOLEAN,                      -- 1/0  are they on payment plan? 1=yes 0=no (see payment plan table)  *** Leasing Co  LOOK INTI THIS
  update_enabled  BOOLEAN,                      -- Allow this customer to update this product
  update_major    BOOLEAN,                      -- Allowed to upgrade? x.x.x  def=0
  update_minor    BOOLEAN,                      -- Allowed to upgrade? #.x.x  def=0
  update_rev      BOOLEAN,                      -- Allowed to upgrade? #.#.x  def=1
);


/*
  License Tracking Table
  Version 1.0
  Last Update:  2010-11-06
*/
create table xi_registered_license
(
  regm_id         UNSIGNED INT NOT NULL AUTO_INCREMENT,
  customer_id     VARCHAR(10),              -- Customer ID
  product_id      INT,                      -- Product ID Number
  reg_dttm        DATETIME,                 -- When did they register their device
  exp_dttm        DATETIME,                 -- Expiration date of this registeration (NULL)
  machine_id      TEXT,                     -- MAC Address; OS; Version; etc.
  reg_status      BOOLEAN,                  -- Are they registed?
  revoke_status   BOOLEAN,                  -- Force revoke of registeration (XI Admin controls this)
  revoke_reason   VARCHAR(50),              -- Why did XI, Inc. revoke this device?
  allow_updates   BOOLEAN                   -- That machine is allowed updates (depends on customer / other)
);


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
  phone1              VARCHAR(16)         -- 111-222-333-4444 (country, area, .., ..)
  phone2              VARCHAR(16)         -- 111-222-333-4444 (country, area, .., ..)
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


