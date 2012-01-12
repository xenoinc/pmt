/*
********************************************************************
* Copyright 2010-2011 (C) Xeno Innovations, Inc.
* ALL RIGHTS RESERVED
* Author:        Damian J. Suess
* Document:      db-main.sql
* Created Date:  Oct 31, 2010, 11:03:17 PM
* Version:       0.1.8
* Description:
*   Provides the basic layout of the database tables used by the system
* Change Log:
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
create table xi_product
(
  product_id          INTEGER UNSIGNED NOT NULL,             -- Custom Product ID Number
  product_name        VARCHAR(50) NOT NULL,     -- Name of the product
  product_desc        BLOB,                     -- Description of the product
  category            VARCHAR(15),              -- What type of product it is (hardware, software, etc.)
  sub_category        VARCHAR(15),  
  version_major       VARCHAR(5),               -- Major Version  **see minor revisions below**
  pmt_path            VARCHAR(256),             -- location in PMT
  release_dttm        DATETIME,                 -- Product Go-Live
  update_dttm         DATETIME,                 -- YYYYMMDD of last detail update
  decommission_dttm   DATETIME,                 -- Date of product decommission (past, present, future)
  changed_uid         INTEGER,                      -- User who made last update
  allow_global_users  BOOLEAN DEFAULT FALSE     -- Allow the master list of users to view product
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
CREATE TABLE xi_product_version
(
  product_id          INTEGER UNSIGNED NOT NULL,
  product_name        VARCHAR(50) NOT NULL DEFAULT '',     -- Project Name
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




/*
  Used with the customer sync table
  Version 1.0
  Last Update:  2010-11-06
*/
CREATE TABLE xi_customer
(
  customer_ndx_id   INTEGER UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,    -- Only used by internal indexing
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
  custom4           VARCHAR(50)
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
  mmt1_id_mode      SMALLINT           -- 1="std"  2="line mode"
);


/*
  Keeps record of what products the customer purchased
  Version 1.0.1
  Last Update:  2010-11-10
 */
create table xi_customer_product
(
--cp_id           INTEGER UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,  -- Customer Product ID
  customer_id     VARCHAR(10) NOT NULL,         -- Customer ID
  product_id      INTEGER NOT NULL,                 -- Product ID
  product_cost    FLOAT,                        -- Total Cost
  paid_infull     INTEGER,                          -- Did they pay their full bill?
  num_licenses    UNSIGNED INT,                 -- How many licenses are they allowed?
  payment_plan    BOOLEAN,                      -- 1/0  are they on payment plan? 1=yes 0=no (see payment plan table)  *** Leasing Co  LOOK INTI THIS
  update_enabled  BOOLEAN,                      -- Allow this customer to update this product
  update_major    BOOLEAN,                      -- Allowed to upgrade? x.x.x  def=0
  update_minor    BOOLEAN,                      -- Allowed to upgrade? #.x.x  def=0
  update_rev      BOOLEAN                      -- Allowed to upgrade? #.#.x  def=1
);


/*
  License Tracking Table
  Version 1.0
  Last Update:  2010-11-06
*/
create table xi_registered_license
(
  regm_id         INTEGER UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  customer_id     VARCHAR(10),              -- Customer ID
  product_id      INTEGER,                  -- Product ID Number
  reg_dttm        DATETIME,                 -- When did they register their device
  exp_dttm        DATETIME,                 -- Expiration date of this registeration (NULL)
  machine_id      TEXT,                     -- MAC Address; OS; Version; etc.
  reg_status      BOOLEAN,                  -- Are they registed?
  revoke_status   BOOLEAN,                  -- Force revoke of registeration (XI Admin controls this)
  revoke_reason   VARCHAR(50),              -- Why did XI, Inc. revoke this device?
  allow_updates   BOOLEAN                   -- That machine is allowed updates (depends on customer / other)
);

