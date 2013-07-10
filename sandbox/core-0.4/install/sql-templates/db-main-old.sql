/*
********************************************************************
* Copyright 2010-2011 (C) Xeno Innovations, Inc.
* ALL RIGHTS RESERVED
* Author:        Damian J. Suess
* Document:      db-main-old.sql
* Created Date:  Oct 31, 2010, 11:03:17 PM
* Version:       0.1.9
* Description:
*   Provides the basic layout of the database tables used by the system
* Change Log:
* [2012-0131] * (djs) Expanded Product_Name from 50 to 255.
*             * (djs) Changed table name to all caps. Columns to Camel_Case
*             * (djs) VarChar now uses:  utf8_unicode_ci
* [2011-0825] * (djs) Fixed column type (there were errors)
*             + (djs) Added xi_product.version_major
*             - (djs) Removed pmt-user tables
* [2010-1107] * (djs) Changed file name from 'db-struct' to 'db-main' to denote main XI/PMT tables
*             * (djs) Split PMT Project tables to 'db-project.sql'
* [2010-1104] + (djs) Added new tables
* [2010-1031] * (djs) Initial creation
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

/* DROP TABLE IF EXISTS  xi_product;*/
create table if not exists PRODUCT
(
  Product_Id          INTEGER UNSIGNED NOT NULL,      -- Custom Product ID Number
  Product_Name        VARCHAR(255) utf8_unicode_ci NOT NULL,     -- Name of the product
  Description         BLOB,                           -- Description of the product
  Category            VARCHAR(15) utf8_unicode_ci,    -- What type of product it is (hardware, software, etc.)
  Sub_Category        VARCHAR(15) utf8_unicode_ci,    -- 
  Version_Major       VARCHAR(5) utf8_unicode_ci,     -- Major Version  **see minor revisions below**
  Pmt_Path            VARCHAR(256) utf8_unicode_ci,   -- location in PMT
  Release_Dttm        DATETIME,                       -- Product Go-Live
  Update_Dttm         DATETIME,                       -- YYYYMMDD of last detail update
  Decommission_Dttm   DATETIME,                       -- Date of product decommission (past, present, future)
  Changed_Uid         INTEGER,                        -- User who made last update
  Allow_Global_Users  BOOLEAN DEFAULT FALSE           -- Allow the master list of users to view product
);
/* 01, Fdx-MMT, "blah ..", "1.4", */

/*
  Product Version
  Available Project Versions
  Version:      1.0
  Last Update:  2010-11-13
  :: List Versions:  SELECT version FROM xi_project_version WHERE project_name = '...' ORDER BY version
*/

/* DROP TABLE IF EXISTS  xi_product_version;*/
CREATE TABLE PRODUCT_VERSION
(
  Product_Id          INTEGER UNSIGNED NOT NULL,
  Product_Name        VARCHAR(255) utf8_unicode_ci NOT NULL DEFAULT '',     -- Project Name
  Version             VARCHAR(15) utf8_unicode_ci,              -- (Branch/Tag) Version. I.E. "1.0", "1.2", "1.4.flex"
  Is_Default          BOOLEAN,                  -- Should this be the default selection for Tickets/Bugs/etc (Default=0)
  Pmt_Path            VARCHAR(256),             -- location in PMT
  Release_Dttm        DATETIME,                 -- Product Go-Live (YYYYMMDD HH:MM:SS)
  Update_Dttm         DATETIME,                 -- YYYYMMDD of last detail update
  Decommission_Dttm   DATETIME,                 -- Date of product decommission (past, present, future)
  Changed_Uid         VARCHAR(15) utf8_unicode_ci,              -- User who made last update
  Allow_Global_Users  BOOLEAN DEFAULT FALSE,    -- Allow the master list of users to access view product version
  Description         BLOB                      -- Details on what's special about this version
);




/*
  Used with the customer sync table
  Version 1.0
  Last Update:  2010-11-06
*/
CREATE TABLE CUSTOMER
(
  Customer_Ndx_Id   INTEGER UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,    -- Only used by internal indexing
  Customer_Id       VARCHAR(10) utf8_unicode_ci,                -- Custom Customer ID
  Customer_Name     VARCHAR(256) utf8_unicode_ci,               -- Full Customer Name
  Licensed_To       VARCHAR(50) utf8_unicode_ci,                -- Being used by specific person(s)  (Customer: UPMC; Licensed To: Dr SOnSO)
  Address1          VARCHAR(100) utf8_unicode_ci,               -- PO Box 13543
  Address2          VARCHAR(100) utf8_unicode_ci,               -- Suite 23
  Addr_City         VARCHAR(50) utf8_unicode_ci,                -- Pittsburgh
  Addr_State        VARCHAR(50) utf8_unicode_ci,                -- Pennsylvania
  Addr_Zip          VARCHAR(10) utf8_unicode_ci,                -- 15203-1234
  Addr_Country      VARCHAR(50) utf8_unicode_ci,                -- United States of America
  Website           VARCHAR(256) utf8_unicode_ci,               -- Customer's Website
  Phone1            VARCHAR(25) utf8_unicode_ci,                -- "+001-412-111-0000 x1234.."
  Phone2            VARCHAR(25) utf8_unicode_ci,                --
  Fax1              VARCHAR(25) utf8_unicode_ci,                --
  Email1            VARCHAR(50) utf8_unicode_ci,                -- Default Contact Email
  Email2            VARCHAR(50) utf8_unicode_ci,                --
  -- Added for Fdx-MMT 1.x compatibility
  Custom1           VARCHAR(50) utf8_unicode_ci,                -- Custom Address Line 1, ...
  Custom2           VARCHAR(50) utf8_unicode_ci,                -- Special Contact Name, etc.
  Custom3           VARCHAR(50) utf8_unicode_ci,
  Custom4           VARCHAR(50) utf8_unicode_ci
);


/*
  Customer's Fdx-MMT 1.0 Info
  Version:     1.0.1
  Last Update: 2010-11-13
*/
CREATE TABLE CUSTOMER_MMT1
(
  Customer_Id       VARCHAR(10),        -- Unique Customer ID defined in 'xi_customer'
  Mmt1_Cid          VARCHAR(10),        -- Fdx-MMT1 Customer ID [actually 8 chars]
  Mmt1_Id_Mode      SMALLINT            -- 1="std"  2="line mode"
);


/*
  Keeps record of what products the customer purchased
  Version 1.0.1
  Last Update:  2010-11-10
 */
create table CUSTOMER_PRODUCT
(
--cp_id           INTEGER UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,  -- Customer Product ID
  Customer_Id     VARCHAR(10) NOT NULL,         -- Customer ID
  Product_Id      INTEGER NOT NULL,                 -- Product ID
  Product_Cost    FLOAT,                        -- Total Cost
  Paid_Infull     INTEGER,                          -- Did they pay their full bill?
  Num_Licenses    UNSIGNED INT,                 -- How many licenses are they allowed?
  Payment_Plan    BOOLEAN,                      -- 1/0  are they on payment plan? 1=yes 0=no (see payment plan table)  *** Leasing Co  LOOK INTI THIS
  Update_Enabled  BOOLEAN,                      -- Allow this customer to update this product
  Update_Major    BOOLEAN,                      -- Allowed to upgrade? x.x.x  def=0
  Update_Minor    BOOLEAN,                      -- Allowed to upgrade? #.x.x  def=0
  Update_Rev      BOOLEAN                      -- Allowed to upgrade? #.#.x  def=1
);


/*
  License Tracking Table
  Version 1.0
  Last Update:  2010-11-06
*/
create table REGISTERED_LICENSE
(
  Regm_Id         INTEGER UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  Customer_Id     VARCHAR(10),              -- Customer ID
  Product_Id      INTEGER,                  -- Product ID Number
  Reg_Dttm        DATETIME,                 -- When did they register their device
  Exp_Dttm        DATETIME,                 -- Expiration date of this registeration (NULL)
  Machine_Id      TEXT,                     -- MAC Address; OS; Version; etc.
  Reg_Status      BOOLEAN,                  -- Are they registed?
  Revoke_Status   BOOLEAN,                  -- Force revoke of registeration (XI Admin controls this)
  Revoke_Reason   VARCHAR(50),              -- Why did XI, Inc. revoke this device?
  Allow_Updates   BOOLEAN                   -- That machine is allowed updates (depends on customer / other)
);

