/*
*********************************************************************
* Copyright 2010 (C) Xeno Innovations, Inc.
* ALL RIGHTS RESERVED
* Author:        Damian J. Suess
* Document:      db-project.sql
* Created Date:  2010-11-07
* Version:       0.1.1
* Description:
*   PMT Project Tables provides the basic layout of the database tables used by project files
*
*   Note:
*   - There are 2 database levels, [1] Main PMT Admin, [2] Project Level
*   - All users get their get their permission levels from PMT Admin settings;
*     these can be overwritten on a project level.
*   - All of the table names can have titles APPENDED to them so they can share the same database. (not till 1.5/2.0)
*
* Change Log:
* [2010-1114] - (djs) RC 1.1
* [2010-1107] - (djs) Initial creation
**********************************************************************/

/* Currently removed until out of BETA status & project is more well-defined
  CREATE TABLE proj_user_internal (...);      -- local database, only used if project doesn't use GLOBAL USERS
  CREATE TABLE pmt_user_priv( ... );          -- User's project access level
  CREATE TABLE pmt_user_priv_list ( ... );    -- Available Project Priv Items (STATIC)
*/

/*
  Main Project Version
  Version 1.0
  Last Update:  2010-11-06
*/
/*
CREATE TABLE proj_version
(
  ver_name        VARCHAR(10),    -- Name of the version (branch/tag).  i.e. "1.0", "1.2", "1.4.flex"
  released_dttm   DATETIME,       -- Release date (YYYYMMDD HH:MM:SS)
  is_default      BOOLEAN         -- Should the be the default selection for Tickets/Bugs/etc (Default=0)
);
*/

CREATE TABLE PROJECT
(
  Project_Id INT NOT NULL AUTO_INCREMENT,
  Project_Name VARCHAR(255) collate utf8_unicode_ci NOT NULL,
  Project_Description VARCHAR(255) collate utf8_unicode_ci NOT NULL,
  Created_Dttm DateTime,
  Updated_User_Name VARCHAR(255) COLLATE utf8_unicode_ci NOT NULL,
  primary key (`Project_Id`)
) engine=InnoDb default charset=utf8 collate=utf_8_uicode_ci;;

CREATE TABLE PROJECT_VERSION
(
  Product_Id  INTEGER NOT NULL,
  Version_Number VARCHAR(15) NOT NULL,
  Version_Code_Name VARCHAR(255) COLLATE utf8_unicode_ci NOT NULL,
  Updated_User_Name VARCHAR(255) COLLATE utf8_unicode_ci NOT NULL,
  -- Create Forgin Key
) engine=InnoDb default charset=utf8 collate=utf_8_uicode_ci;;

/*
  Project Components
  Project Editions, Revisions, Sub-Projects
  Version 1.0
  Last Update:  2010-11-06
*/
CREATE TABLE PROJECT_COMPONENT
(
  component_name  VARCHAR(25),        -- Project Component Name (Core, DocuFAST, QuantiFAST, PIE, ALL, FlexDx, ..)
--owner_name      VARCHAR(15),        -- Default Owner, NOTE: Next release, use table 'group_component' to list all compnts assigned to what group (used in reports/issue listing)
  description     TEXT                -- description            (All Fdx 1.x Products.  This includes, ...)
);

/*
  Project Component Version
  (proj) xRehab=1.0, (component) ROM=1.2
  Version 1.0
  Last Update:  2010-11-06
*/
CREATE TABLE PROJECT_COMPONENT_VERSION
(
  component_name  VARCHAR(20),
  ver_name        VARCHAR(10),
  released_dttm   DATETIME,       --
  is_default      BOOLEAN         -- This is the default project component
);


/*
  Project Milestone
  Release dates and deadlines. This will mostly be used by Development & PMs
  Version 1.0
  Last Update:  2010-11-06
*/
CREATE TABLE PROJECT_MILESTONE
(
  mile_title      VARCHAR(50),    -- MMT v1.0, MMT v1.4, MMT 1.4.Flex, NextLevel
  due_date        DATETIME,       -- Project Due Date
  completed_dttm  DATETIME,       -- When was it completed? (NULL)
  description     BLOB,           -- What is it and what's special about it
);
-- TITLE                      DUE                 Completed           Default
-- MMT - Version 1.0          YYYYMMDD HH:MM:SS   YYYYMMDD HH:MM:SS   0
-- MMT - Version 1.1          YYYYMMDD HH:MM:SS   YYYYMMDD HH:MM:SS   0
-- MMT - Version 1.4.std      YYYYMMDD HH:MM:SS   YYYYMMDD HH:MM:SS   0
-- MMT - Version 1.4.flex     YYYYMMDD HH:MM:SS   YYYYMMDD HH:MM:SS   0


/*
  Available Statistical Reports for the project
  Version 1.0
  Last Update:  2010-11-06
*/
CREATE TABLE REPORT
(
  rpt_id        INT NOT NULL AUTO_INCREMENT,
  rpt_title     VARCHAR(20),        -- Name of the report
  rpt_author    VARCHAR(20),        -- Full name of person
  rpt_version   VARCHAR(10),        -- Report Version
  rpt_mod_dttm  DATETIME,           -- Late Modified Date
  rpt_query     TEXT                -- Code behind it
);


/*
  Wiki Pages
  Version: 1.0.3
  Last Update: 2010-11-13
*/
CREATE TABLE PROJECT_WIKI
(
  page_id         INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  page_title      VARCHAR(100),                             -- Page Title
  page_counter    BIGINT(20) UNSIGNED NOT NULL DEFAULT '0', -- Page Counter
  version         INT UNSIGNED NOT NULL DEFALUT '1',        -- page revision
  author_uid      VARCHAR(20),                              -- Author of the page (larger than default user, just incase)
  update_dttm     DATETIME,                                 -- Last updated date time
  update_ip       VARCHAR(15) DEFAULT NULL,                 -- IPv4 of who updated it last
  data_type       VARCHAR(5) DEFAULT 'wiki',                -- Page Data Type ('wiki1', 'html4', 'html5')
  page_data       BLOB,                                     -- Page code
  PRIMARY KEY ('page_id')
--UNIQUE INDEX 'name_title'
);
INSERT INTO PROJECT_WIKI (page_title, authr_uid, update_dttm, data_type, page_data) VALUES
  ('pmt_main', 'xi_pmt', '2010-11-07 00:00:00.000', 'wiki', '''''Welcome to your personal xiPMT Project Space''''');  -- ['''' = '']

/*
  Log changes made to the wiki (see MediaWiki for details)
  ** Not to be used until 1.5 or 2.0
  CREATE TABLE wiki_log ();
*/

/*
  Project Enumerations
  (Custom for each project)
  Version 1.0
  Last Update:  2010-11-12
*/
CREATE TABLE S_PROJECT_ENUM
(
  enum_type VARCHAR(15),           -- List Item Type  (ticket_type, resolution, priority)
  enum_name VARCHAR(20),           -- List Item Description
  enum_priority VARCHAR(10),       -- List Item Priority Level
  -- emum_isdefault int,           -- 1/0 to mark what the default setting should be
  unique( enum_type, enum_name )
);
-- Type of ticket being created
INSERT INTO s_proj_enum VALUES ('ticket_type',    'Defect',               '1');
INSERT INTO s_proj_enum VALUES ('ticket_type',    'Enhancement',          '2');
INSERT INTO s_proj_enum VALUES ('ticket_type',    'Task',                 '3'); /* tech request */
-- Ticket Resolutions
INSERT INTO s_proj_enum VALUES ('resolution',     'Fixed',                '1');
INSERT INTO s_proj_enum VALUES ('resolution',     'Completed',            '2');
INSERT INTO s_proj_enum VALUES ('resolution',     'Next-Release',         '3');
INSERT INTO s_proj_enum VALUES ('resolution',     'Duplicate',            '4');
INSERT INTO s_proj_enum VALUES ('resolution',     'Invalid',              '5');
INSERT INTO s_proj_enum VALUES ('resolution',     'Unable to Reproduce',  '6');
-- Ticket and Bug Priority Levels
INSERT INTO s_proj_enum VALUES ('priority',       'Trivial',              '1');
INSERT INTO s_proj_enum VALUES ('priority',       'Minor',                '2');
INSERT INTO s_proj_enum VALUES ('priority',       'Major',                '3');
INSERT INTO s_proj_enum VALUES ('priority',       'Critical',             '4');
INSERT INTO s_proj_enum VALUES ('priority',       'Blocker',              '5');
-- -----------------------------------------------------------------------------
-- Ticket Status
INSERT INTO s_proj_enum VALUES ('ticket_status',  'Open',                 '10');  -- New/Unassigned  [1x]
INSERT INTO s_proj_enum VALUES ('ticket_status',  'Reopened',             '11');
INSERT INTO s_proj_enum VALUES ('ticket_status',  'Assigned',             '20');  -- Owned  [2x]
INSERT INTO s_proj_enum VALUES ('ticket_status',  'Pending',              '21');
INSERT INTO s_proj_enum VALUES ('ticket_status',  'Researching',          '22');
INSERT INTO s_proj_enum VALUES ('ticket_status',  'Invalid',              '50');  -- Closed [5x]
INSERT INTO s_proj_enum VALUES ('ticket_status',  'Closed',               '51');
INSERT INTO s_proj_enum VALUES ('ticket_status',  'Closed-Duplicate',     '52');
INSERT INTO s_proj_enum VALUES ('ticket_status',  'Closed-Bug',           '53');
INSERT INTO s_proj_enum VALUES ('ticket_status',  'Closed-Canceled',      '54');
-- -----------------------------------------------------------------------------
-- Bug Status
INSERT INTO s_proj_enum VALUES ('bug_status',     'Submitted',            '1');
INSERT INTO s_proj_enum VALUES ('bug_status',     'Accepted',             '2');
INSERT INTO s_proj_enum VALUES ('bug_status',     'Dev-Unverified',       '3');
INSERT INTO s_proj_enum VALUES ('bug_status',     'Dev-Investigation',    '4');
INSERT INTO s_proj_enum VALUES ('bug_status',     'Dev-In Progress',      '5');
INSERT INTO s_proj_enum VALUES ('bug_status',     'Dev-Restesting',       '6');
INSERT INTO s_proj_enum VALUES ('bug_status',     'Closed',               '7');
INSERT INTO s_proj_enum VALUES ('bug_status',     'Closed-Deferred',      '8');
INSERT INTO s_proj_enum VALUES ('bug_status',     'Closed-Rejected',      '9');
-- -----------------------------------------------------------------------------
-- Task Type
INSERT INTO s_proj_enum VALUES ('task_type',      'TechRequest',          '1'); -- Technical Request
INSERT INTO s_proj_enum VALUES ('task_type',      'Purchase Order',       '2'); -- Purchased Equipment
INSERT INTO s_proj_enum VALUES ('task_type',      'Custom Report',        '3'); -- Custom Report (Software, Other)
INSERT INTO s_proj_enum VALUES ('task_type',      'SQL Task',             '4'); -- Custom SQL (misc)
INSERT INTO s_proj_enum VALUES ('task_type',      'Upgrade',              '5'); -- Upgrade customer
INSERT INTO s_proj_enum VALUES ('task_type',      'HotFix',               '6'); -- Manual Hotfix
INSERT INTO s_proj_enum VALUES ('task_type',      'Other',                '7'); -- Other :: Provide Description
-- Task Status
INSERT INTO s_proj_enum VALUES ('task_status',    'New',                  '10');  -- Submitted (unassigned)
INSERT INTO s_proj_enum VALUES ('task_status',    'Mgmt-Pending-Approv',  '11');  -- Pending Management Approv
INSERT INTO s_proj_enum VALUES ('task_status',    'PO-Approv',            '30');  -- In Progress :: Purchase Order Approved
INSERT INTO s_proj_enum VALUES ('task_status',    'PO-Shipped',           '31');  -- In Progress :: Purchase Order Shipped
INSERT INTO s_proj_enum VALUES ('task_status',    'Task-Assigned',        '32');  -- In Progress :: TR Assigned
INSERT INTO s_proj_enum VALUES ('task_status',    'Task-In Progress',     '33');  -- In Progress :: TR In-Progress
INSERT INTO s_proj_enum VALUES ('task_status',    'Completed',            '40');  -- Task completed, waiting managment verfication
INSERT INTO s_proj_enum VALUES ('task_status',    'Review-Pending',       '41');  -- Task completed, waiting review verfication
INSERT INTO s_proj_enum VALUES ('task_status',    'Review-Mgmt',          '42');  -- Task completed, waiting review verfication
INSERT INTO s_proj_enum VALUES ('task_status',    'Closed',               '50');  -- Closed :: General
INSERT INTO s_proj_enum VALUES ('task_status',    'Closed-Declined',      '51');  -- Closed :: Never worked on
-- "Tech Request"    :: [New] > [Assigned] > [In Progress] > [Completed] > (Review-Mgmt) > [Closed]
-- "Purchase Orders" :: [New] > [Mgmt-Pending-Approv] > [PO-...] > [Closed]
-- "Custom Report"   :: [New] > []



/* User Cookie info */
-- Version 1.0
-- Last Update:  2010-11-06
CREATE TABLE AUTH_COOKIE
(
  user_name       VARCHAR(15),                    -- PMT User Name
  user_ip         VARCHAR(15),                    -- User's IPv4
  cookie          VARCHAR(35),                    -- cookie id
  login_dttm      DATETIME                        -- When did they login
);


/* ***[ Ticket Tables ]******************************************** */

/*
  Ticket / TechRequest being issued
  TRs will be branched to its own table in the future
  Request "Ticket Type" on the PMT Ticket page, "Enhancement, Deficet, TechRequest, Bug Report, etc.)
  Last Update: 2010-11-07
*/
CREATE TABLE ticket
(
  ticket_id         UNSIGNED INT NOT NULL AUTO_INCREMENT,
  ticket_type       VARCHAR(15),          -- Ticket Type:  enhancement, deficet, inquiry, etc.  (Ask this on the Ticket Page)
  created_dttm      DATETIME,             -- When was it created
  updated_dttm      DATETIME,             -- Main Items last update
  priority_enum     VARCHAR(15),          -- Priority Name:  major, minor, critical  [s_proj_enum.enum_name]
  status_enum       VARCHAR(15),          -- Ticket status (from ENUM table)

  reporter_uid      VARCHAR(15),          -- UserID of who reported the issue (this can be handwritten if 'anon_user' is checked) <-- To allow Annon, set in ProjCONFIG.PHP
  reporter_ip       VARCHAR(15),          -- User's IP, used for anonymous submitions
  owner_gid         VARCHAR(20),          -- Not Needed :: Name of the general group assigned the Inquiry
  owner_uid         VARCHAR(15),          -- UserID of who it addressing the ticket request
  cc_addr           VARCHAR(100),         -- UserID or series of email addresses

  product_name      VARCHAR(50) NOT NULL, -- Name of the product  (xi_product.product_name)
  product_ver       VARCHAR(15),          -- Name of the Project Version (xi_product_version.version)
  component_name    VARCHAR(20),          -- Name of the Project Component
  component_version VARCHAR(10),          -- Component Revision
  milestone_id      VARCHAR(50),          -- Attatch to Milestone (Dev only, can be null)

  resolution        VARCHAR(20),          -- Resolution description (from ENUM table s_proj_enum.enum_name = 'resolution')
  summary           BLOB                  -- Description of the ticket
);

/*
  Message Posting & updates associated with Tickets issued
  Version 1.0
  Last Update: 2010-11-07
*/
CREATE TABLE ticket_update
(
  ticket_id       UNSIGNED INT NOT NULL,  -- Ticked ID Number
  created_dttm    DATETIME,               -- When was this created
  update_dttm     DATETIME,               -- When was it updated by the user
  user_id         VARCHAR(15),            -- Who created it
  user_ip         VARCHAR(15),            -- User IP (used for anonymous updates)
  update_type     VARCHAR(15),            -- Status change, Additional Info, etc.  (not used in 1.0)
  summary         BLOB                    -- User update text
);


/*
  Ticket Attatchments
  Version 1.0.1
  Last Update:  2010-11-14
*/
CREATE TABLE ticket_attachment
(
  atch_id         INT NOT NULL AUTO_INCREMENT,
  id              INT,                            -- ticket ID
  description     VARCHAR(50),                    -- short description
  filename        VARCHAR(256),                   -- file name
  filesize        UNSIGNED INT,                   -- file size
  created_dttm    DATETIME,                       -- when it was uploaded
  mod_dttm        DATETIME,                       -- file was modified (NULL)
  user_name       VARCHAR(15),                    -- who uploaded it
  user_ip         VARCHAR(15)                     -- user's IPv4 (created/mod)
);


/* ***[ Bug Report Tables ]******************************************** */

/*
  Version: 1.0
  Last Update: 2010-11-05
*/
CREATE TABLE bug
(
  bug_id              UNSIGNED INT NOT NULL AUTO_INCREMENT,
  created_dttm        DATETIME,               -- Created DateTime
  updated_dttm        DATETIME,               -- Modified DateTime
  priority_enum       VARCHAR(15),            -- Priority Name:  major, minor, critical  [s_proj_enum.enum_name]
  status_enum         VARCHAR(15),            -- Ticket status (from ENUM table)

  reporter_uid        VARCHAR(15),            -- User ID of who created this
  changed_uid         VARCHAR(15),            -- User ID of who updated it last (use IP if anon)
  reporter_ip         VARCHAR(15),            -- User's IP, used for anonymous submitions
  owner_gid           VARCHAR(20),            -- Not Needed :: Name of the general group assigned the Inquiry
  owner_uid           VARCHAR(15),            -- UserID of who it addressing the ticket request

  product_name      VARCHAR(50) NOT NULL,     -- Name of the product  (xi_product.product_name)
  product_ver       VARCHAR(15),              -- Name of the Project Version (xi_product_version.version)
  component_name    VARCHAR(20),              -- Name of the Project Component
  component_version VARCHAR(10),              -- Component Revision
  milestone_id      VARCHAR(50),              -- Attatch to Milestone (Dev only, can be null)

  resolution        VARCHAR(20),              -- Resolution description (from ENUM table s_proj_enum.enum_name = 'resolution')
  summary           BLOB                      -- Description of the ticket
);

/*
  Tickets that the bug is linked to
  Used in version 2.0
*/
-- CREATE TABLE bug_ticket_links();

CREATE TABLE bug_update
(
  bug_id          UNSIGNED INT NOT NULL,  -- Bug ID Number
  created_dttm    DATETIME,               -- When was this created
  update_dttm     DATETIME,               -- When was it updated by the user
  user_id         VARCHAR(15),            -- Who created it
  user_ip         VARCHAR(15),            -- User IP (used for anonymous updates)
  update_type     VARCHAR(15),            -- Status change, Additional Info, "Transfered To"
  summary         BLOB                    -- User update text
);

CREATE TABLE bug_attachment
(
  atch_id         UNSIGNED INT NOT NULL AUTO_INCREMENT,    -- Index number of attachment
  bug_id          UNSIGNED INT NOT NULL,          -- Bug ID
  description     VARCHAR(50),                    -- short description
  filename        VARCHAR(256),                   -- file name
  filesize        UNSIGNED INT,                   -- file size
  created_dttm    DATETIME,                       -- when it was uploaded
  modified_dttm   DATETIME,                       -- file was modified (NULL)
  user_name       VARCHAR(15),                    -- who uploaded it
  user_ip         VARCHAR(15)                     -- user's IPv4 (created/mod)
);


/* ***[ Task Tables - ToDo List / Tech Request ]****************** */
/*
  Task Request table
  Version:      1.0.2
  Last Update:  2010-11-13
*/
CREATE TABLE task
(
  task_id           UNSIGNED INT AUTO_INCREMENT,
  created_dttm      DATETIME,                 -- When was this created
  transfer_dttm     DATETIME,                 -- When was it transferred to the "Assigned User"
  due_dttm          DATETIME,                 -- due date/time
  start_dttm        DATETIME,                 -- not to begin before
  closed_dttm       DATETIME,                 -- Closed Date
  
  customer_id       VARCHAR(10),              -- Name of the customer (not needed)
  request_uid       VARCHAR(15),              -- User who requested Task [pmt_user]
  assigned_gid      VARCHAR(20),              -- Owner Group of the ticket [pmt_group]
  assigned_uid      VARCHAR(15),              -- User Assigned to ticket  [pmt_user] (Nullable, if still is group queue)
  billable          BOOLEAN DEFAULT FALSE,    -- Is this a customer billable task
  contact_name      VARCHAR(50),              -- Name of (customer) contact on the Task Request
  contact_phone     VARCHAR(25),              -- ...

  product_name      VARCHAR(50),              -- Used only in a GLOBAL_PROJECT setup
  component_name    VARCHAR(25),              -- Not Needed :: Attatch Component (dev group only)

  product_name      VARCHAR(50) DEFAULT NULL, -- Name of the product  (xi_product.product_name)
  product_ver       VARCHAR(15) DEFAULT NULL, -- Name of the Project Version (xi_product_version.version)
  component_name    VARCHAR(20) DEFAULT NULL, -- Name of the Project Component
  component_version VARCHAR(10) DEFAULT NULL, -- Component Revision   (can be null)
  milestone_id      VARCHAR(50),              -- Attatch to Milestone (Dev only, can be null)

  summary           BLOB                      -- Description of the ticket
);

CREATE TABLE task_update
(
  task_id         UNSIGNED INT NOT NULL,  -- Task ID Number
  created_dttm    DATETIME,               -- When was this created
  update_dttm     DATETIME,               -- When was it updated by the user
  user_id         VARCHAR(15),            -- Who created it
  user_ip         VARCHAR(15),            -- User IP (used for anonymous updates)
  update_type     VARCHAR(15),            -- Status change, Additional Info, etc.  (not used in 1.0)
  summary         BLOB                    -- User update text
);

CREATE TABLE task_attachment
(
  atch_id         UNSIGNED INT NOT NULL AUTO_INCREMENT,    -- Index number of attachment
  task_id         UNSIGNED INT NOT NULL,          -- Task ID
  description     VARCHAR(50),                    -- short description
  filename        VARCHAR(256),                   -- file name
  filesize        UNSIGNED INT,                   -- file size
  created_dttm    DATETIME,                       -- when it was uploaded
  modified_dttm   DATETIME,                       -- file was modified (NULL)
  user_name       VARCHAR(15),                    -- who uploaded it
  user_ip         VARCHAR(15)                     -- user's IPv4 (created/mod)
);



/*
 SVN Tables (keep local log for quick info)
  ------------------------------------------
  + svn_repos       db_ver, repo_dir, repo_ver
  + svn_revision
*/
/*
CREATE TABLE PROJECT_SVN_REPO
(
--db_ver      VARCHAR(5),       -- Version of Subversion cor compatibility.  ex: 1.4  (not really needed)
  repo_dir    VARCHAR(255),     -- where is it located  (svn:42d14667-55a7-4533-b801-018457db5143:/var/svn/fdx1)
  repo_ver    UNSIGNED INT      -- Current HEAD version
);
CREATE TABLE PROJECT_SVN_REVISION
(
  svn_rev       UNSIGNED INT,   -- Revision number
  svn_path      VARCHAR(256),   -- Path to the project.  EX: "https://svn.xenoinc.org/project1"
  node_type     VARCHAR(1),     -- (F)ile, (D)irectory
  change_type   VARCHAR(1),     -- (A)dded, C, (D)elete, E, (M)odified
--base_path     VARCHAR(256),   -- Used to describe if/what it updated from (NOT IN BETA)
  base_rev      UNSIGNED INT    -- Updated from revision, NULL=unknown/new
);

*/

