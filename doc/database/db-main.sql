/*
********************************************************************
* Copyright 2010-2011 (C) Xeno Innovations, Inc.
* ALL RIGHTS RESERVED
* Author:        
* Document:      db-main.sql
* Created Date:  Aug 25, 2011, 20:03:17
* Version:       0.2.2
* Description:
*   Proposal for new DB tables used by the system
*
* Change Log:
* [2012-0131] - v0.2.2
*             * (djs) Expanded Product_Name from 50 to 255.
*             * (djs) Changed table name to all caps. Columns to Camel_Case
*             * (djs) VarChar now uses:  utf8_unicode_ci
* [2011-0829] - v0.0.1
*             - (djs) Combined old table columns & added [+ add], [- rmv], [** modificaiton] respectively
* [2011-0825] - (djs) Initial file creation
* [2011-0811] - (djs) Initial proposal idea
**********************************************************************/

create table if not exists `PMT_SETTINGS`
(
  `setting`     varchar(255) collate utf8_unicode_ci not null,
  `value` longtext collate utf8_unicode_ci not null,
  primary key (`setting`)
) engine=InnoDB default charset=utf8 collate=utf8_unicode_ci;


-- Listing of products
create table PRODUCT
(

  Product_Id              INTEGER UNSIGNED NOT NULL PRIMARY KEY,    --      Product ID Number - Unique value for internal tracking
  Product_Name            VARCHAR(255) collate utf8_unicode_ci NOT NULL,                     --      Short name of product (Ex: "MMT-IR")
  Product_Description     BLOB,                                     --      Description of the product
  Category                VARCHAR(15),                              --      What type of product it is (Ex: "hardware", "software", etc.)
  Sub_Category            VARCHAR(15),                              --      Sub Category (Ex: "Muscle Evaluation", "Utility", "FTP-Client")
--Version                 VARCHAR(15),                              --  +   Full version number (1.22.333.44444)
--Versoin_Major           INTEGER DEFAULT 0,                        -- [*]  Major Version Number (Ex: 1)    [* Changed: VarChar to INT *]  
--Version_Minor           INTEGER,                                  --  +   Minor number (22)
--Version_Revision        VARCHAR(10) DEFAULT '',                   --  +   Revision Version Number (333.rc4)
  Release_DTTM            DATETIME,                                 --      Product Go-Live
  Update_DTTM             DATETIME,                                 --      YYYYMMDD of last detail update
  Decommission_DTTM       DATETIME,                                 --      Date of product decommission (past, present, future)
  Changed_UID             VARCHAR(15),                              -- [*]  UserId who made last update to this product information   [* INT to Varchar to match PMT-USERID *]
  PMT_Allow_Global_Users  BOOLEAN DEFAULT FALSE,                    --      Used by PMT - Allow the master list of users to view product
  PMT_Path                VARCHAR(256)                              --      Used by PMT - Location in PMT
);

/*
  ** Probably reject addition **
  Listing of updates to product (minor revisions).
  This will be used for GA Revisions made to a Branch copy
*/
--create table PRODUCT_VERSION_UPDATE
--create table PRODUCT_UPDATE
create table PRODUCT_VERSION
(
  Version_Update_Id   INTEGER NOT NULL AUTO_INCREMENT,          -- +  Unique identifyer for update
  Product_Id          INTEGER,                                  -- +  xi_product.ProductId
  Release_Dttm        DATETIME,                                 -- +  Date the revision was released
  Decommission_Dttm   DATETIME,
  Version             VARCHAR(15),                              -- +  Full version number (1.22.333.44444)
  Versoin_Major       INTEGER,                                  -- +  Major Version Number (1)
  Version_Minor       INTEGER,                                  -- +  Minor number (22)
  Product_Revision    VARCHAR(10),                               -- +  Revision Version Number (333.rc4)
  Update_User_Name    VARCHAR(50)
);

-- Proposed restructure of customer table
CREATE TABLE CUSTOMER
(
  Customer_Index    INTEGER UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,    -- Only used by internal indexing
  Customer_Id       VARCHAR(10),                --      Custom Customer ID
--Customer_Name     VARCHAR(256),               -- [*]  Full Customer Name  [* Changed column name *]
  Name              VARCHAR(100),               -- [*]  Full Customer Name  [* Changed column name & size *]
--LicensedTo        VARCHAR(50),                -- [-]  Being used by specific person(s)  (Customer: UPMC; Licensed To: Dr SOnSO)
  Address1          VARCHAR(100),               --      PO Box 13543
  Address2          VARCHAR(100) DEFAULT NULL,  -- [*]  Suite 23            [* MOD: added 'Default null' *]
  City              VARCHAR(50),                --      Pittsburgh
  State_Name        VARCHAR(50),                --      Pennsylvania
  Zip_Code          VARCHAR(10),                --      15203-1234
  Country           VARCHAR(50),                --      United States of America
  Website           VARCHAR(256),               --      Customer's Website
  Phone1            VARCHAR(25),                --      Main Phone "+001-412-111-0000 x1234.."
  Phone2            VARCHAR(25) DEFAULT NULL,   --      Phone #2
  Fax1              VARCHAR(25) DEFAULT NULL,   -- 
  Update_Dttm       DATETIME,                   --  +   Date of updated information
  Changed_Uid       VARCHAR(15),                --  +   UserId who made last update to this product information
--Email1            VARCHAR(50),                -- [-]  Email Default       [* RMV:  See >> xi_customer_contact.MainContact *]
--Email2            VARCHAR(50),                -- [-]  Email Backup        [* RMV:  See >> xi_customer_contact.MainContact *]
--custom1           VARCHAR(50),                -- [-]  Custom Address Line 1, ...
--custom2           VARCHAR(50),                -- [-]  Special Contact Name, etc.
--custom3           VARCHAR(50),                -- [-]
--custom4           VARCHAR(50)                 -- [-]
);

/*
  Listing of contacts for customer site
*/
create table CUSTOMER_CONTACT
(
  Customer_Contact_Id INTEGER NOT NULL AUTO_INCREMENT,
  Customer_Id         INTEGER,                -- Customer linked to
  Name_First          VARCHAR(50),            -- First name of contact
  Name_Last           VARCHAR(50),            -- Surname
  Title               VARCHAR(25),            -- Position / Title
--Phone_Number        VARCHAR(25),            -- Main phone
--Phone_Number2       VARCHAR(25),            -- Alternate phone
--Phone_Fax           VARCHAR(25),            -- Fax number
--EMail               VARCHAR(50),            -- Email address
  Contact_Priority    INTEGER                 -- Is this person a main contact
);

create table CUSTOMER_CONTACT_DATA
(
  Customer_Contact_Id   INTEGER,
  Detail_Data           VARCHAR(255) collate utf8_unicode_ci NOT NULL,
  Detail_Type           VARCHAR(15)       -- Phone, Cell, Address, Fax, Email
  Detail_Priority              INTEGER
);

-- Listing of customer products
create table CUSTOMER_PRODUCT
(
  CPID                INTEGER UNSIGNED NOT AUTO_INCREMENT NULL PRIMARY KEY,    -- ** Customer Product ID
  Customer_Id         VARCHAR(10) NOT NULL,       --      CustomerId to link to
  Product_Id          INTEGER,                    --      Product Identificaiton Number (xi_product.ProductId)

--product_cost        FLOAT,                      --      (OLD)
  Product_Cost        VARCHAR(10),                -- [*]  Sales price paid for product (ex: 3222111.00) [** Changed: FLOAT to VC(10) **]
--paid_infull         INTEGER,                    -- [-]  Did they pay in full?  (* REMOVED *)
  Paid_Ammount        VARCHAR(10),                --  +   Sum of payment paid to date
  Payment_Plan        BOOLEAN,                    --      Are they on payment plan?   1=yes 0=no (see payment plan table)
  Num_Licenses        INTEGER,                    --      How many licenses are they allowed?

  Install_Dttm        DateTime,                   --      Date of installation
  Last_Update_Dttm    DateTime,                   --      Last update of product version
  
  Update_Enabled      BOOLEAN,                    --      Allow customer to update this product  ** Used by updater application
  Update_Major        BOOLEAN,                    --      Allowed to upgrade? x.x.x  def=0  ** Used by updater application
  Update_Minor        BOOLEAN,                    --      Allowed to upgrade? #.x.x  def=0  ** Used by updater application
  Update_Rev          BOOLEAN                     --      Allowed to upgrade? #.#.x  def=1  ** Used by updater application
);


/*
 Listing of product updates as per the customer
*/
create table CUSTOMER_PRODUCT_UPDATE
(
  Customer_Id           INTEGER,                -- +  xi_customer.CustomerId
  Product_Id            INTEGER,                -- +   
  Changed_Uid           VARCHAR(15),            -- +  UserId who made last update to this product information
  Update_Dttm           DATETIME,               -- +  Date of the update itself
  New_Product_Version   VARCHAR(10),            -- +  
  Installed_By          VARCHAR(15)             -- +  
);


/*
  Registration infromation for customer product
  Was previously "CUSTOMER_MMT1" used used as reg info for Fdx-MMT v1.x
  
*/
CREATE TABLE CUSTOMER_PRODUCT_REGISTRATION
(
  Product_Reg_Id      INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
  Customer_Id         VARCHAR(10),            --    Unique Customer ID defined in 'xi_customer'
  MMT1_Customer_Id    VARCHAR(10),            --    Fdx-MMT1 Customer ID [actually 8 chars]
  MMT1_Mode_Id        SMALLINT                --    1="std"  2="line mode"
  Licensed_To         VARCHAR(50),            -- +  Being used by specific person(s)  (Customer: UPMC; Licensed To: Dr SOnSO)
  Old_Custom1         VARCHAR(50),            -- +  Custom Info / Address Line 1
  Old_Custom2         VARCHAR(50),            -- +  Custom Info / Address Line 2  *Special Contact Name, etc.*
  Old_Custom3         VARCHAR(50),            -- +  Custom Info / Address Line 3
  Old_Custom4         VARCHAR(50),            -- +  Custom Info / Address Line 4
  PRIMARY KEY (Product_Reg_Id)
);

