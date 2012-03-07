/* *********************************************************************
 * Copyright 2010 (C) Xeno Innovations, Inc.
 * ALL RIGHTS RESERVED
 * Author:        Damian J. Suess
 * Document:      pmt-db-ticket.sql
 * Created Date:  2010-11-07
 * Version:       0.2.0
 * Description:
 *   Project tables provides the basic layout of the database tables used by project files
 *
 * Change Log:
 * 2012-0305  * Updated all IP Address columns from width 15 to 45 (IPv6)
 *            * Formatted column names to camel case, tables to caps
 * 2012-0304  * Included into PMT 0.2 (djs)
 *            + Modified table names & some rows
 * 2010-1114  * RC 1.1 (djs)
 * 2010-1107  + Initial creation (djs)
 **********************************************************************/

/* Currently removed until out of BETA status & project is more well-defined
  CREATE TABLE proj_user_internal (...)   -- local database, only used if project doesn't use GLOBAL USERS
  CREATE TABLE pmt_user_priv( ... )       -- User's project access level
  CREATE TABLE pmt_user_priv_list ( ... ) -- Available Project Priv Items (STATIC)
*/


/*
  Project Enumerations
  (Custom for each project)
  Version 1.0
  Last Update:  2010-11-12
  * 2012-0306 - emum_isdefault int,-- 1/0 to mark what the default setting should be
  *           - unique( `Enum_Type`, `Enum_Name` )
*/
CREATE TABLE IF NOT EXISTS `TBLPMT_S_TBT_ENUM`
(
  `Enum_Type` VARCHAR(16) COLLATE utf8_unicode_ci NOT NULL, -- List Item Type  (ticket_type, resolution, priority)
  `Enum_Name` VARCHAR(24) COLLATE utf8_unicode_ci NOT NULL, -- List Item Description
  `Enum_Priority` INTEGER NOT NULL,                         -- List Item Priority Level
  unique( `Enum_Type`, `Enum_Name` )
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


-- Type of ticket being created
INSERT INTO TBLPMT_S_TBT_ENUM VALUES ('ticket_type',    'Defect',               1);
INSERT INTO TBLPMT_S_TBT_ENUM VALUES ('ticket_type',    'Enhancement',          2);
INSERT INTO TBLPMT_S_TBT_ENUM VALUES ('ticket_type',    'Task',                 3); /* tech request */
-- Ticket Resolutions
INSERT INTO TBLPMT_S_TBT_ENUM VALUES ('resolution',     'Fixed',                1);
INSERT INTO TBLPMT_S_TBT_ENUM VALUES ('resolution',     'Completed',            2);
INSERT INTO TBLPMT_S_TBT_ENUM VALUES ('resolution',     'Next-Release',         3);
INSERT INTO TBLPMT_S_TBT_ENUM VALUES ('resolution',     'Duplicate',            4);
INSERT INTO TBLPMT_S_TBT_ENUM VALUES ('resolution',     'Invalid',              5);
INSERT INTO TBLPMT_S_TBT_ENUM VALUES ('resolution',     'Unable to Reproduce',  6);
-- Ticket and Bug Priority Levels
INSERT INTO TBLPMT_S_TBT_ENUM VALUES ('priority',       'Trivial',              1);
INSERT INTO TBLPMT_S_TBT_ENUM VALUES ('priority',       'Minor',                2);
INSERT INTO TBLPMT_S_TBT_ENUM VALUES ('priority',       'Major',                3);
INSERT INTO TBLPMT_S_TBT_ENUM VALUES ('priority',       'Critical',             4);
INSERT INTO TBLPMT_S_TBT_ENUM VALUES ('priority',       'Blocker',              5);
-- -----------------------------------------------------------------------------
-- Ticket Status
INSERT INTO TBLPMT_S_TBT_ENUM VALUES ('ticket_status',  'Open',                 10);  -- New/Unassigned  [1x]
INSERT INTO TBLPMT_S_TBT_ENUM VALUES ('ticket_status',  'Reopened',             11);
INSERT INTO TBLPMT_S_TBT_ENUM VALUES ('ticket_status',  'Assigned',             20);  -- Owned  [2x]
INSERT INTO TBLPMT_S_TBT_ENUM VALUES ('ticket_status',  'Pending',              21);
INSERT INTO TBLPMT_S_TBT_ENUM VALUES ('ticket_status',  'Researching',          22);
INSERT INTO TBLPMT_S_TBT_ENUM VALUES ('ticket_status',  'Invalid',              50);  -- Closed [5x]
INSERT INTO TBLPMT_S_TBT_ENUM VALUES ('ticket_status',  'Closed',               51);
INSERT INTO TBLPMT_S_TBT_ENUM VALUES ('ticket_status',  'Closed-Duplicate',     52);
INSERT INTO TBLPMT_S_TBT_ENUM VALUES ('ticket_status',  'Closed-Bug',           53);
INSERT INTO TBLPMT_S_TBT_ENUM VALUES ('ticket_status',  'Closed-Canceled',      54);
-- -----------------------------------------------------------------------------
-- Bug Status
INSERT INTO TBLPMT_S_TBT_ENUM VALUES ('bug_status',     'Submitted',            1);
INSERT INTO TBLPMT_S_TBT_ENUM VALUES ('bug_status',     'Accepted',             2);
INSERT INTO TBLPMT_S_TBT_ENUM VALUES ('bug_status',     'Dev-Unverified',       3);
INSERT INTO TBLPMT_S_TBT_ENUM VALUES ('bug_status',     'Dev-Investigation',    4);
INSERT INTO TBLPMT_S_TBT_ENUM VALUES ('bug_status',     'Dev-In Progress',      5);
INSERT INTO TBLPMT_S_TBT_ENUM VALUES ('bug_status',     'Dev-Restesting',       6);
INSERT INTO TBLPMT_S_TBT_ENUM VALUES ('bug_status',     'Closed',               7);
INSERT INTO TBLPMT_S_TBT_ENUM VALUES ('bug_status',     'Closed-Deferred',      8);
INSERT INTO TBLPMT_S_TBT_ENUM VALUES ('bug_status',     'Closed-Rejected',      9);
-- -----------------------------------------------------------------------------
-- Task Type
INSERT INTO TBLPMT_S_TBT_ENUM VALUES ('task_type',      'TechRequest',          1); -- Technical Request
INSERT INTO TBLPMT_S_TBT_ENUM VALUES ('task_type',      'Purchase Order',       2); -- Purchased Equipment
INSERT INTO TBLPMT_S_TBT_ENUM VALUES ('task_type',      'Custom Report',        3); -- Custom Report (Software, Other)
INSERT INTO TBLPMT_S_TBT_ENUM VALUES ('task_type',      'SQL Task',             4); -- Custom SQL (misc)
INSERT INTO TBLPMT_S_TBT_ENUM VALUES ('task_type',      'Upgrade',              5); -- Upgrade customer
INSERT INTO TBLPMT_S_TBT_ENUM VALUES ('task_type',      'HotFix',               6); -- Manual Hotfix
INSERT INTO TBLPMT_S_TBT_ENUM VALUES ('task_type',      'Other',                7); -- Other :: Provide Description
-- Task Status
INSERT INTO TBLPMT_S_TBT_ENUM VALUES ('task_status',    'New',                  10);  -- Submitted (unassigned)
INSERT INTO TBLPMT_S_TBT_ENUM VALUES ('task_status',    'Mgmt-Pending-Approv',  11);  -- Pending Management Approv
INSERT INTO TBLPMT_S_TBT_ENUM VALUES ('task_status',    'PO-Approv',            30);  -- In Progress :: Purchase Order Approved
INSERT INTO TBLPMT_S_TBT_ENUM VALUES ('task_status',    'PO-Shipped',           31);  -- In Progress :: Purchase Order Shipped
INSERT INTO TBLPMT_S_TBT_ENUM VALUES ('task_status',    'Task-Assigned',        32);  -- In Progress :: TR Assigned
INSERT INTO TBLPMT_S_TBT_ENUM VALUES ('task_status',    'Task-In Progress',     33);  -- In Progress :: TR In-Progress
INSERT INTO TBLPMT_S_TBT_ENUM VALUES ('task_status',    'Completed',            40);  -- Task completed, waiting managment verfication
INSERT INTO TBLPMT_S_TBT_ENUM VALUES ('task_status',    'Review-Pending',       41);  -- Task completed, waiting review verfication
INSERT INTO TBLPMT_S_TBT_ENUM VALUES ('task_status',    'Review-Mgmt',          42);  -- Task completed, waiting review verfication
INSERT INTO TBLPMT_S_TBT_ENUM VALUES ('task_status',    'Closed',               50);  -- Closed :: General
INSERT INTO TBLPMT_S_TBT_ENUM VALUES ('task_status',    'Closed-Declined',      51);  -- Closed :: Never worked on
-- "Tech Request"    :: [New] > [Assigned] > [In Progress] > [Completed] > (Review-Mgmt) > [Closed]
-- "Purchase Orders" :: [New] > [Mgmt-Pending-Approv] > [PO-...] > [Closed]
-- "Custom Report"   :: [New] > []


/*
  Static P-Type
  Helps split tickets to reference Products or Projects.
  This helps resolve the Chicken or the Egg.
  Created: 2012-0305
  * 2012-0306 - UNIQUE INDEX `Reference_Type` (`Reference_Type`)
*/
CREATE TABLE IF NOT EXISTS `TBLPMT_S_REFERENCE_TYPE`
(
  `Reference_Type_Id` INT NOT NULL AUTO_INCREMENT,
  `Reference_Type` VARCHAR(16) COLLATE utf8_unicode_ci,
  `Description` VARCHAR(64) COLLATE utf8_unicode_ci DEFAULT '',
  PRIMARY KEY (`Reference_Type_Id`),
  UNIQUE (`Reference_Type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `TBLPMT_S_REFERENCE_TYPE` (`Reference_Type`,`Description`) VALUES ('project', 'Development Project');
INSERT INTO `TBLPMT_S_REFERENCE_TYPE` (`Reference_Type`,`Description`) VALUES ('product', 'Live Hardware, Application or 3rd-Party');



/* ***[ Ticket Tables ]******************************************** */

/*
  Ticket / TechRequest being issued
  TRs will be branched to its own table in the future
  Request "Ticket Type" on the PMT Ticket page, "Enhancement, Deficet, TechRequest, Bug Report, etc.)
  Last Update: 2010-11-07
*/
CREATE TABLE IF NOT EXISTS `TBLPMT_TICKET`
(
  `Ticket_Id`         INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `Ticket_Type`       VARCHAR(15) COLLATE utf8_unicode_ci,  -- Ticket Type:  enhancement, deficet, inquiry, etc.  (Ask this on the Ticket Page)
  `Created_Dttm`      DATETIME,               -- When was it created
  `Updated_Dttm`      DATETIME,               -- Main Items last update
  `Priority_Enum`     VARCHAR(15) COLLATE utf8_unicode_ci,  -- Priority Name:  major, minor, critical  [TBLPMT_S_TBT_ENUM.enum_name]
  `Status_Enum`       VARCHAR(24) COLLATE utf8_unicode_ci,  -- Ticket status (from ENUM table)
  `Priority_Score`    INT,                                  -- low-high (0-999) Used to rate within the Priority_Enum

  `Customer_Id`       VARCHAR(256) COLLATE utf8_unicode_ci, -- Customer Id Number (if specified)
  `Reporter_Uid`      INT UNSIGNED,           -- UserID of who reported the issue (this can be handwritten if 'anon_user' is checked) <-- To allow Annon, set in ProjCONFIG.PHP
  `Reporter_Ip`       INT UNSIGNED,           -- User's IP, used for anonymous submitions
  `Owner_Gid`         INT UNSIGNED,           -- Not Needed :: Name of the general group assigned the Inquiry
  `Owner_Uid`         INT UNSIGNED,           -- UserID of who it addressing the ticket request
  `Cc_Addr`           VARCHAR(255) COLLATE utf8_unicode_ci, -- UserID or series of email addresses

  `Reference_Type_Id` INT NOT NULL,           -- Product or Project (pmt_s_ptype)
  `Pro_Id`            INT UNSIGNED NOT NULL,  -- Reference (Project/Product) Id  (xi_product.product_name)
  `Pro_Version_Id`    INT UNSIGNED,           -- Name of the Project Version (xi_product_version.version)
  `Component_Id`      INT UNSIGNED,           -- Name of the Project Component (xi_project_component.component_id)
  `Component_Version` VARCHAR(24) COLLATE utf8_unicode_ci,  -- Component Revision
  `Milestone_Id`      VARCHAR(64) COLLATE utf8_unicode_ci,  -- Attatch to Milestone (Dev only, can be null)

  `Resolution_Enum`   VARCHAR(20) COLLATE utf8_unicode_ci,  -- Resolution description (from ENUM table TBLPMT_S_TBT_ENUM.enum_name = 'resolution')
  `Subject`           VARCHAR(64) COLLATE utf8_unicode_ci,  -- Short Description
  `Summary`           MEDIUMBLOB,                           -- Description of the ticket
  PRIMARY KEY (`Ticket_Id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


/*
  Message Posting & updates associated with Tickets issued
  Version 0.2.1
  Created: 2010-11-07
*/
CREATE TABLE IF NOT EXISTS `TBLPMT_TICKET_UPDATE`
(
  `Ticket_Update_Id`  INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `Ticket_Id`         INT UNSIGNED NOT NULL,  -- Ticked ID Number
  `Quality_Score`     INT DEFAULT 0,          -- Quality of comment +/-
  `Created_Dttm`      DATETIME,               -- When was this created
  `Update_Dttm`       DATETIME,               -- When was it updated by the user
  `User_Id`           INT UNSIGNED,           -- Who created it
  `User_Ip`           VARCHAR(45),            -- User IP (used for anonymous updates)
  `Update_Type`       VARCHAR(15),            -- Status change, Additional Info, etc.  (not used in 1.0)
  `Summary`           MEDIUMBLOB,             -- User update text
  PRIMARY KEY (`Ticket_Update_Id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


/*
  Ticket Attatchments
  Version 0.2.2
  Created:  2010-11-14
  * 2012-0305 * Changed User_Ip length from 15 to 45 for IPv6 (ABCD:ABCD:ABCD:ABCD:ABCD:ABCD:192.168.158.190)
  *           * Changed File_Size to BIGINT so it can be greater than 4GB
*/
CREATE TABLE IF NOT EXISTS `TBLPMT_TICKET_ATTACHMENT`
(
  `Attachment_Id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `Ticket_Id`     INT UNSIGNED NOT NULL,  -- ticket ID
  `File_Name`     VARCHAR(256),     -- file name
  `File_Size`     BIGINT,           -- file size (max > 4gb)
  `Created_Dttm`  DATETIME,         -- when it was uploaded
  `Update_Dttm`   DATETIME,         -- file was modified (NULL)
  `User_Id`       INT UNSIGNED,     -- who uploaded it
  `User_Ip`       VARCHAR(45),      -- user's IPv4/IPv6 (created/mod)
  `Description`   VARCHAR(64) COLLATE utf8_unicode_ci,  -- short description
  PRIMARY KEY (`Attachment_Id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


/* ***[ Bug Report Tables ]******************************************** */

/*
  Version: 0.2.1
  Created: 2010-11-05
  * 2012-0305 * Modified for new db rules
*/
CREATE TABLE IF NOT EXISTS `TBLPMT_BUG`
(
  `Bug_Id`            INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `Created_Dttm`      DATETIME,               -- Created DateTime
  `Updated_Dttm`      DATETIME,               -- Modified DateTime
  `Priority_Enum`     VARCHAR(15) COLLATE utf8_unicode_ci,  -- Priority Name:  major, minor, critical  [TBLPMT_S_TBT_ENUM.enum_name]
  `Status_Enum`       VARCHAR(15) COLLATE utf8_unicode_ci,  -- Ticket status (from ENUM table)
  `Priority_Score`    INT,                                  -- low-high (0-999) Used to rate within the Priority_Enum

  `Reporter_Uid`      INT UNSIGNED,           -- User ID of who created this
  `Changed_Uid`       INT UNSIGNED,           -- User ID of who updated it last (use IP if anon)
  `Reporter_Ip`       VARCHAR(45),            -- User's IP, used for anonymous submitions
  `Owner_Gid`         INT UNSIGNED,           -- Not Needed :: Name of the general group assigned the Inquiry
  `Owner_Uid`         INT UNSIGNED,           -- UserID of who it addressing the ticket request

  `Reference_Type_Id` INT NOT NULL,           -- (Static) Reference Type: Project / Product
  `Pro_Id`            INT UNSIGNED NOT NULL,  -- Name of the project/product  (xi_product.product_name)
  `Pro_Version_Id`    INT UNSIGNED,           -- Name of the project/product Version (xi_product_version.version)
  `Component_Id`      INT UNSIGNED,           -- Name of the Project Component
  `Component_Version` VARCHAR(24) COLLATE utf8_unicode_ci,    -- Component Revision
  `Milestone_Id`      VARCHAR(64) COLLATE utf8_unicode_ci,    -- Attatch to Milestone (Dev only, can be null)

  `Resolution_Enum`   VARCHAR(20) COLLATE utf8_unicode_ci,    -- Resolution description (from ENUM table TBLPMT_S_TBT_ENUM.enum_name = 'resolution')
  `Subject`           VARCHAR(64) COLLATE utf8_unicode_ci,    -- Short Description
  `Summary`           MEDIUMBLOB,                             -- Full Description of the bug
  PRIMARY KEY (`Bug_Id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*
  Tickets that the bug is linked to
  Version 0.2.0
  Created: 2010-11-07
*/
-- CREATE TABLE bug_ticket_links();

CREATE TABLE IF NOT EXISTS `TBLPMT_BUG_UPDATE`
(
  `Bug_Update_Id`  INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `Bug_id`         INT UNSIGNED NOT NULL,  -- Bug ID Number
  `Quality_Score`  INT DEFAULT 0,          -- Quality of comment +/-
  `Created_Dttm`   DATETIME,               -- When was this created
  `Update_Dttm`    DATETIME,               -- When was it updated by the user
  `User_Id`        INT UNSIGNED,           -- Who created it
  `User_Ip`        VARCHAR(45),            -- User IP (used for anonymous updates)
  `Update_Type`    VARCHAR(15),            -- Status change, Additional Info, etc.  (not used in 1.0)
  `Summary`        MEDIUMBLOB,             -- User update text
  PRIMARY KEY (`Bug_Update_Id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


CREATE TABLE IF NOT EXISTS `TBLPMT_BUG_ATTACHMENT`
(
  `Attachment_Id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `Bug_Id`        INT UNSIGNED NOT NULL,  -- ticket ID
  `File_Name`     VARCHAR(256),     -- file name
  `File_Size`     BIGINT,           -- file size (max > 4gb)
  `Created_Dttm`  DATETIME,         -- when it was uploaded
  `Update_Dttm`   DATETIME,         -- file was modified (NULL)
  `User_Id`       INT UNSIGNED,     -- who uploaded it
  `User_Ip`       VARCHAR(45),      -- user's IPv4/IPv6 (created/mod)
  `Description`   VARCHAR(64) COLLATE utf8_unicode_ci,  -- short description
  PRIMARY KEY (`Attachment_Id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



/* ***[ Task Tables - ToDo List / Tech Request ]****************** */
/*
  Task Request table
  Version:      0.2.3
  Created:  2010-11-13
  * 2012-0306 * Updated column types and added in compliance to table changes
  *           + Added, Status_Enum (it was missing before)
*/
CREATE TABLE IF NOT EXISTS `TBLPMT_TASK`
(
  `Task_id`           INT UNSIGNED AUTO_INCREMENT,
  `Created_Dttm`      DATETIME,                 -- When was this created
  `Transfer_Dttm`     DATETIME,                 -- When was it transferred to the "Assigned User"
  `Due_Dttm`          DATETIME,                 -- due date/time
  `Start_Dttm`        DATETIME,                 -- not to begin before
  `Closed_Dttm`       DATETIME,                 -- Closed Date
  `Priority_Enum`     VARCHAR(15) COLLATE utf8_unicode_ci,  -- High/Low/Med
  `Status_Enum`       VARCHAR(24) COLLATE utf8_unicode_ci,  -- Open/Progress/Closed - pmt_s_tbt_enum.Enum_Type='Ticket_Status'

  `Customer_Id`       VARCHAR(256) COLLATE utf8_unicode_ci, -- Name of the customer (not needed)
  `Request_Uid`       INT UNSIGNED,                         -- User who requested Task [pmt_user]
  `Assigned_Gid`      INT UNSIGNED,                         -- Owner Group of the ticket [pmt_group]
  `Assigned_Uid`      INT UNSIGNED,                         -- User Assigned to ticket  [pmt_user] (Nullable, if still is group queue)
  `Billable`          BOOLEAN DEFAULT FALSE,                -- Is this a customer billable task
  `Contact_Name`      VARCHAR(64) COLLATE utf8_unicode_ci,  -- Name of (customer) contact on the Task Request
  `Contact_Phone`     VARCHAR(25) COLLATE utf8_unicode_ci,  -- ...

  `Pro_Id`            INT UNSIGNED NOT NULL,  -- Reference (Project/Product) Id  (xi_product.product_name)
  `Pro_Version_Id`    INT UNSIGNED ,          -- Name of the Project Version (xi_product_version.version)
  `Component_Id`      INT UNSIGNED,           -- Name of the Project Component (xi_project_component.component_id)
  `Component_Version` VARCHAR(24) COLLATE utf8_unicode_ci,  -- Component Revision
  `Milestone_Id`      VARCHAR(64) COLLATE utf8_unicode_ci,  -- Attatch to Milestone (Dev only, can be null)

  `Summary`           MEDIUMBLOB,                -- Description of the ticket
  PRIMARY KEY (`Task_Id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE IF NOT EXISTS task_update
(
  `Task_Update_Id`  INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `Ticket_Id`       INT UNSIGNED NOT NULL,  -- Ticked ID Number
  `Quality_Score`   INT DEFAULT 0,          -- Quality of comment +/-
  `Created_Dttm`    DATETIME,               -- When was this created
  `Update_Dttm`     DATETIME,               -- When was it updated by the user
  `User_Id`         INT UNSIGNED,           -- Who created it
  `User_Ip`         VARCHAR(45),            -- User IP (used for anonymous updates)
  `Update_Type`     VARCHAR(15),            -- Status change, Additional Info, etc.  (not used in 1.0)
  `Summary`         MEDIUMBLOB,             -- User update text
  PRIMARY KEY (`Task_Update_Id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE IF NOT EXISTS `TBLPMT_TASK_ATTACHMENT`
(
  `Attachment_Id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `Bug_Id`        INT UNSIGNED NOT NULL,  -- ticket ID
  `File_Name`     VARCHAR(256),     -- file name
  `File_Size`     BIGINT,           -- file size (max > 4gb)
  `Created_Dttm`  DATETIME,         -- when it was uploaded
  `Update_Dttm`   DATETIME,         -- file was modified (NULL)
  `User_Id`       INT UNSIGNED,     -- who uploaded it
  `User_Ip`       VARCHAR(45),      -- user's IPv4/IPv6 (created/mod)
  `Description`   VARCHAR(64) COLLATE utf8_unicode_ci,  -- short description
  PRIMARY KEY (`Attachment_Id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


/*
 SVN Tables (keep local log for quick info)
  ------------------------------------------
  + svn_repos       db_ver, repo_dir, repo_ver
  + svn_revision
*/
/*

-- Used to track when something was updated
CREATE TABLE `TBLPMT_TBT_AUDIT`
(
  `Audit_Id`    INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `TBT_Type`    VARCHAR(6),   -- ticket,bug,task
  `Action`      VARCHAR(),    -- Update Type: status,desc,score,priority
  `User_Id`     INT UNSIGNED,
  `Update_Dttm` DATETIME,
  PRIMARY KEY (`Audit_Id`)
)

CREATE TABLE PROJECT_SVN_REPO
(
--db_ver      VARCHAR(5),       -- Version of Subversion cor compatibility.  ex: 1.4  (not really needed)
  repo_dir    VARCHAR(255),     -- where is it located  (svn:42d14667-55a7-4533-b801-018457db5143:/var/svn/fdx1)
  repo_ver    INT UNSIGNED      -- Current HEAD version
)

CREATE TABLE PROJECT_SVN_REVISION
(
  svn_rev       INT UNSIGNED,   -- Revision number
  svn_path      VARCHAR(256),   -- Path to the project.  EX: "https://svn.xenoinc.org/project1"
  node_type     VARCHAR(1),     -- (F)ile, (D)irectory
  change_type   VARCHAR(1),     -- (A)dded, C, (D)elete, E, (M)odified
--base_path     VARCHAR(256),   -- Used to describe if/what it updated from (NOT IN BETA)
  base_rev      INT UNSIGNED    -- Updated from revision, NULL=unknown/new
)

*/

