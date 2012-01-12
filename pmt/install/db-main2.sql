/*
********************************************************************
* Copyright 2010-2011 (C) Xeno Innovations, Inc.
* ALL RIGHTS RESERVED
* Author:        
* Document:      db-main2.sql
* Created Date:  Aug 25, 2011, 20:03:17
* Version:       0.0.1
* Description:
*   Proposal for new DB tables used by the system
*
* Change Log:
* [2011-0829] - (djs) Combined old table columns & added [+ add], [- rmv], [** modificaiton] respectively
* [2011-0825] - (djs) Initial file creation
* [2011-0811] - (djs) Initial proposal idea
**********************************************************************/

-- Listing of products
create table xi_product
(

  ProductId           INTEGER UNSIGNED NOT NULL PRIMARY KEY,    --      Product ID Number - Unique value for internal tracking
  ProductName         VARCHAR(50) NOT NULL,                     --      Short name of product (Ex: "MMT-IR")
  ProductDescription  BLOB,                                     --      Description of the product
  Category            VARCHAR(15),                              --      What type of product it is (Ex: "hardware", "software", etc.)
  SubCategory         VARCHAR(15),                              --      Sub Category (Ex: "Muscle Evaluation", "Utility", "FTP-Client")
  Version             VARCHAR(15),                              --  +   Full version number (1.22.333.44444)
  VersoinMajor        INTEGER DEFAULT 0,                        -- [*]  Major Version Number (Ex: 1)    [* Changed: VarChar to INT *]  
--version_major       VARCHAR(5),                               -- [-]  (old)
  VersionMinor        INTEGER,                                  --  +   Minor number (22)
  VersionRevision     VARCHAR(10) DEFAULT '',                   --  +   Revision Version Number (333.rc4)
  ReleaseDTTM         DATETIME,                                 --      Product Go-Live
  UpdateDTTM          DATETIME,                                 --      YYYYMMDD of last detail update
  DecommissionDTTM    DATETIME,                                 --      Date of product decommission (past, present, future)
  ChangedUID          VARCHAR(15),                              -- [*]  UserId who made last update to this product information   [* INT to Varchar to match PMT-USERID *]
  PMTAllowGlobalUsers BOOLEAN DEFAULT FALSE,                    --      Used by PMT - Allow the master list of users to view product
  PMTPath             VARCHAR(256)                              --      Used by PMT - Location in PMT
);


/*
  ** Probably reject addition **
  Listing of updates to product (minor revisions).
  This will be used for GA Revisions made to a Branch copy
*/
create table xi_product_version_update
(
  VersionUpdateId   INTEGER NOT NULL AUTO_INCREMENT,          -- +  Unique identifyer for update
  ProductId         INTEGER,                                  -- +  xi_product.ProductId
  UpdateDTTM        DATETIME,                                 -- +  Date the revision was released
  Version           VARCHAR(15),                              -- +  Full version number (1.22.333.44444)
  VersoinMajor      INTEGER,                                  -- +  Major Version Number (1)
  VersionMinor      INTEGER,                                  -- +  Minor number (22)
  ProductRevision   VARCHAR(10)                               -- +  Revision Version Number (333.rc4)
);

-- Proposed restructure of customer table
CREATE TABLE xi_customer
(
  CIndexId          INTEGER UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,    -- Only used by internal indexing
  CustomerId        VARCHAR(10),                --      Custom Customer ID
--CustomerName      VARCHAR(256),               -- [*]  Full Customer Name  [* Changed column name *]
  Name              VARCHAR(100),               -- [*]  Full Customer Name  [* Changed column name & size *]
--LicensedTo        VARCHAR(50),                -- [-]  Being used by specific person(s)  (Customer: UPMC; Licensed To: Dr SOnSO)
  Address1          VARCHAR(100),               --      PO Box 13543
  Address2          VARCHAR(100) DEFAULT NULL,  -- [*]  Suite 23            [* MOD: added 'Default null' *]
  City              VARCHAR(50),                --      Pittsburgh
  StateName         VARCHAR(50),                --      Pennsylvania
  ZipCode           VARCHAR(10),                --      15203-1234
  Country           VARCHAR(50),                --      United States of America
  Website           VARCHAR(256),               --      Customer's Website
  Phone1            VARCHAR(25),                --      Main Phone "+001-412-111-0000 x1234.."
  Phone2            VARCHAR(25) DEFAULT NULL,   --      Phone #2
  Fax1              VARCHAR(25) DEFAULT NULL,   -- 
  UpdateDTTM        DATETIME,                   --  +   Date of updated information
  ChangedUID        VARCHAR(15),                --  +   UserId who made last update to this product information
--Email1            VARCHAR(50),                -- [-]  Email Default       [* RMV:  See >> xi_customer_contact.MainContact *]
--Email2            VARCHAR(50),                -- [-]  Email Backup        [* RMV:  See >> xi_customer_contact.MainContact *]
--custom1           VARCHAR(50),                -- [-]  Custom Address Line 1, ...
--custom2           VARCHAR(50),                -- [-]  Special Contact Name, etc.
--custom3           VARCHAR(50),                -- [-]
--custom4           VARCHAR(50)                 -- [-]
);


/*
  Customer's Info used by Fdx-MMT v1.x
*/
CREATE TABLE xi_customer_mmt1
(
  CustomerId        VARCHAR(10),            --    Unique Customer ID defined in 'xi_customer'
  MMT1CustomerId    VARCHAR(10),            --    Fdx-MMT1 Customer ID [actually 8 chars]
  MMT1ModeId        SMALLINT                --    1="std"  2="line mode"
  LicensedTo        VARCHAR(50),            -- +  Being used by specific person(s)  (Customer: UPMC; Licensed To: Dr SOnSO)
  Custom1           VARCHAR(50),            -- +  Custom Info / Address Line 1
  Custom2           VARCHAR(50),            -- +  Custom Info / Address Line 2  *Special Contact Name, etc.*
  Custom3           VARCHAR(50),            -- +  Custom Info / Address Line 3
  Custom4           VARCHAR(50)             -- +  Custom Info / Address Line 4
);


/*
  Listing of contacts for customer site
*/
create table xi_customer_contact
(
  CustomerId        INTEGER,                -- +  Customer linked to
  NameFull          VARCHAR(50),            -- +  Full name of constact
  Title             VARCHAR(25),            -- +  Position / Title
  PhoneNumber       VARCHAR(25),            -- +  Main phone
  PhoneNumber2      VARCHAR(25),            -- +  Alternate phone
  PhoneFax          VARCHAR(25),            -- +  Fax number
  EMail             VARCHAR(50),            -- +  Email address
  MainContact       BOOL                    -- +  Is this person a main contact
);


-- Listing of customer products
create table xi_customer_product
(
  CPID              INTEGER UNSIGNED NOT AUTO_INCREMENT NULL PRIMARY KEY,    -- ** Customer Product ID
  CustomerId        VARCHAR(10) NOT NULL,       --      CustomerId to link to
  ProductId         INTEGER,                    --      Product Identificaiton Number (xi_product.ProductId)

--product_cost      FLOAT,                      --      (OLD)
  ProductCost       VARCHAR(10),                -- [*]  Sales price paid for product (ex: 3222111.00) [** Changed: FLOAT to VC(10) **]
--paid_infull       INTEGER,                    -- [-]  Did they pay in full?  (* REMOVED *)
  PaidAmmount       VARCHAR(10),                --  +   Sum of payment paid to date
  PaymentPlan       BOOLEAN,                    --      Are they on payment plan?   1=yes 0=no (see payment plan table)
  NumLicenses       INTEGER,                    --      How many licenses are they allowed?

  InstallDTTM       DateTime,                   --      Date of installation
  LastUpdateDTTM    DateTime,                   --      Last update of product version
  
  UpdateEnabled     BOOLEAN,                    --      Allow customer to update this product  ** Used by updater application
  UpdateMajor       BOOLEAN,                    --      Allowed to upgrade? x.x.x  def=0  ** Used by updater application
  UpdateMinor       BOOLEAN,                    --      Allowed to upgrade? #.x.x  def=0  ** Used by updater application
  UpdateRev         BOOLEAN                     --      Allowed to upgrade? #.#.x  def=1  ** Used by updater application
);

  


/*
 Listing of product updates as per the customer
*/
create table xi_customer_product_update
(
  CustomerId          INTEGER,                -- +  xi_customer.CustomerId
  ProductId           INTEGER,                -- +   
  ChangedUID          VARCHAR(15),            -- +  UserId who made last update to this product information
  UpdateDTTM          DATETIME,               -- +  Date of the update itself
  NewProductVersion   VARCHAR(10),            -- +  
  InstalledBy         VARCHAR(15)             -- +  
);



