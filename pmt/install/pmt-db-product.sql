/* ********************************************************************
 * Copyright 2010-2012 (C) Xeno Innovations, Inc.
 * ALL RIGHTS RESERVED
 * Author:
 * Document:      db-product.sql
 * Created Date:  Aug 25, 2011, 20:03:17
 * Version:       0.2.2
 * Description:
 *  Product and Inventory tables
 *
 *  Basically Projects can become Products so we got to keep the
 *  table structures somewhat similar.
 *
 * To Do:
 *  2012-06-06
 *  [ ] Create table "Category"
 *  [ ] Create table "Category_Item" referencing back to Product_Id
 *  [ ] Create table "Category_Sub" referencing back to Category_Id or SubCat_Id
 *  [X] Create table "Product_Version"            (Ex: xRehab "1.2")
 *  [X] Create table "Product_Component"          (Ex: xRehab v1.2 > "ROM")
 *  [X] Create table "Product_Component_Version"  (
 *
 * Change Log:
 * 2012-0606  + Added Component, Component_Version, PRODUCT_WIKI
 *            + Added Category, Category_Item
 * 2012-0604  + Added appart of main project, it's go time! Xeno Innovation needs this shit live
 * 2012-0131  - v0.2.2
 */


/*
  Created:  2011-0825
  2012-06-06  + Added column Product_Code
              * Set Product_Id to unsigned auto increment
*/
CREATE TABLE IF NOT EXISTS `TBLPMT_PRODUCT`
(
  Product_Id              INT UNSIGNED NOT NULL AUTO_INCREMENT,           -- Product ID Number - Unique value for internal tracking
  Product_Code            VARCHAR(255) collate utf8_unicode_ci ,          -- Unique manufacturer product code (Ex: "xi-1337")
  Product_Name            VARCHAR(255) collate utf8_unicode_ci NOT NULL,  -- Short name of product (Ex: "MMT-IR")
  Product_Description     BLOB,                                           -- Description of the product
  Category                VARCHAR(50) collate utf8_unicode_ci,            -- What type of product it is (Ex: "hardware", "software", etc.)
  Sub_Category            VARCHAR(15),            --      Sub Category (Ex: "Muscle Evaluation", "Utility", "FTP-Client")
  Release_Dttm            DATETIME,               --      Product Go-Live
  Update_Dttm             DATETIME,               --      YYYYMMDD of last detail update
  Decommission_Dttm       DATETIME,               --      Date of product decommission (past, present, future)
  Changed_Uid             VARCHAR(50) collate utf8_unicode_ci,            -- UserId who made last update to this product information   [* INT to Varchar to match PMT-USERID *]
  PMT_Allow_Global_Users  BOOLEAN DEFAULT FALSE,                          -- Used by PMT - Allow the master list of users to view product
  PMT_Path                VARCHAR(255) collate utf8_unicode_ci,           -- Used by PMT - Location in PMT (if intended for DL, then this should be its own normalized table)
  PRIMARY KEY (`Product_Id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*
  ** Probably reject addition **
  Listing of updates to product (minor revisions).
  This will be used for GA Revisions made to a Branch copy

  Note:
  There should be a row for each Minor version. Only revisions should be
  updated: except for BETAs, they are allowed.
    i.e. [OK] "1.5.2" && "1.5.3"(beta)    [NOT-OK] "1.5.2" && "1.5.4"

  Changes:
  2012-06-06  * Made Version_Update_Id INT UNSIGNED
              * Made Product_Id INT UNSIGNED

*/
CREATE TABLE IF NOT EXISTS `TBLPMT_PRODUCT_VERSION`
(
  Version_Update_Id   INT UNSIGNED NOT NULL AUTO_INCREMENT, -- Unique identifyer for update
  Product_Id          INT UNSIGNED,                         -- xi_product.ProductId
  Release_Dttm        DATETIME,                             -- Date the revision was released
  Decommission_Dttm   DATETIME,
  Development_Build   BOOLEAN DEFAULT FALSE,                -- Version marked as a development "beta" build
  Version             VARCHAR(15) collate utf8_unicode_ci,  -- Full version number (1.22.333.44444) or (1.5)
  Version_Major       INTEGER,                              -- Major Version Number (1)
  Version_Minor       INTEGER,                              -- Minor number (22)
  Version_Revision    VARCHAR(10) collate utf8_unicode_ci,  -- Revision Version Number (333.rc4)
  Update_User_Name    VARCHAR(50) collate utf8_unicode_ci,  -- Raw user name for record purposes
  PRIMARY KEY (`Version_Update_Id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*
To Do:  (2012-06-06
-- Register each version's revision updates
CREATE TABLE IF NOT EXISTS `PRODUCT_VERSION_UPDATE`

-- don't know
CREATE TABLE IF NOT EXISTS `PRODUCT_UPDATE`
*/

/*
  Changes:
  2012-06-06  * Added "Inv_" to beginning of Qty, Min, Max so we don't mess with MySQL syntax
              * Made Product_Id INT UNSIGNED
  */
CREATE TABLE IF NOT EXISTS `TBLPMT_PRODUCT_INVENTORY`
(
  `Product_Id`  INT UNSIGNED NOT NULL,
  `Inv_Qty`     DOUBLE,
  `Inv_Min`     DOUBLE,
  `Inv_Max`     DOUBLE,
  `Average`     DOUBLE,
  PRIMARY KEY (`Product_Id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


/*
  Product Components
  Product Editions, Revisions, Sub-Projects
  Version 0.2
  Created:  2012-06-06
  * 2002-0606 + initial addition
              + Added Product_Version
*/
CREATE TABLE IF NOT EXISTS `TBLPMT_PRODUCT_COMPONENT`
(
  `Component_Id`    INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `Component_Name`  VARCHAR(128) COLLATE utf8_unicode_ci NOT NULL,  -- (Core, DocuFAST, PIE, ALL, ..)
  `Product_Id`      INT UNSIGNED NOT NULL,                          --
  `Product_Version` VARCHAR(15) COLLATE utf8_unicode_ci ,           -- Full version number (1.22.333.44444) or (1.5)
  `Description`     MEDIUMBLOB,
  PRIMARY KEY (`Component_Id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*
  Product Component Version
  Ex: xRehab [prod] > ROM [comp] > "2.3" [comp_ver])
  Version 1.0
  Created:  2012-06-06
  * 2012-0606 - initial addition
  *           * Made Version_Number size larger than Project just in case.
*/
CREATE TABLE IF NOT EXISTS `TBLPMT_PRODUCT_COMPONENT_VERSION`
(
  `Component_Id`    INT UNSIGNED NOT NULL,
  `Version_Number`  VARCHAR(64) COLLATE utf8_unicode_ci NOT NULL,   -- 10.255.4345
  `Released_Dttm`   DATETIME,                       --
  `Is_Default`      BOOLEAN DEFAULT FALSE,          -- This is the default project component
  PRIMARY KEY (`Component_Id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


/*
  Wiki Pages
  Version: 1.0
  Crated: 2012-06-06
*/
CREATE TABLE IF NOT EXISTS `TBLPMT_PRODUCT_WIKI`
(
  `Page_Id`       INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `Page_Title`    VARCHAR(100) COLLATE utf8_unicode_ci NOT NULL,    -- Page Title
  `Page_Counter`  INT UNSIGNED NOT NULL DEFAULT 0,                  -- Page Counter
  `Version`       INT UNSIGNED NOT NULL DEFAULT 1,                  -- page revision
  `User_Id`       INT UNSIGNED,                                     -- Author of the page (larger than default user, just incase)
  `User_Name`     VARCHAR(50) COLLATE utf8_unicode_ci NOT NULL,     -- Author name (for archiving purposes)
  `Update_Dttm`   DATETIME,                                         -- Last updated date time
  `Update_Ip`     VARCHAR(15) COLLATE utf8_unicode_ci DEFAULT NULL, -- IPv4 of who updated it last
  `Page_Data`     MEDIUMBLOB,                                       -- Page code
  PRIMARY KEY (`Page_Id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*
  Category Name
  (Hardware, Software, Physical Rehab, Network)
  Version 1.0
  Created: 2012-06-06
*/
CREATE TABLE IF NOT EXISTS `TBLPMT_PRODUCT_CATEGORY`
(
  Category_Id   INT UNSIGNED NOT NULL AUTO_INCREMENT,
  Category_Name VARCHAR(255) COLLATE utf8_unicode_ci NOT NULL,
  Description   MEDIUMBLOB,
  PRIMARY KEY (`Category_Id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*
  Version 1.0
  Created: 2012-06-06
  ** Currently Disabled
Cateogry_Sub(
  Category_Sub_Id     INT UNSIGNED NOT NULL AUTO_INCREMENT,
  Category_Sub_Name   VARCHAR(255) COLLATE utf8_unicode_ci NOT NULL,
  Parent_Category_Id  INT UNSIGNED NOT NULL,
  Description         MEDIUMBLOB,
  PRIMARY KEY (`Category_Sub_Id`)
)
*/


/*
  Product Category Item
  You can have products referencing multiple categories
  Version 1.0
  Created: 2012-06-06

  Possible Addition:
    Is_SubCat     BOOLEAN DEFAULT FALSE,      -- Point to Sub Category

*/
CREATE TABLE IF NOT EXISTS `TBLPMT_PRODUCT_CATEGORY_ITEM`
(
  Cat_Item_Id   INT UNSIGNED NOT NULL AUTO_INCREMENT,
  Category_Id   INT UNSIGNED NOT NULL,      -- Category to ref
  Product_Id    INT UNSIGNED NOT NULL,      -- Product to ref
  Description   MEDIUMBLOB,
  PRIMARY KEY (`Cat_Item_Id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

