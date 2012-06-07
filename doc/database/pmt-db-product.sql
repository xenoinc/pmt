/* ********************************************************************
 * Copyright 2010-2012 (C) Xeno Innovations, Inc.
 * ALL RIGHTS RESERVED
 * Author:
 * Document:      db-product.sql
 * Created Date:  Aug 25, 2011, 20:03:17
 * Version:       0.2.2
 * Description:
 *   Product and Inventory tables
 *
 * To Do:
 *  2012-06-06
 *  [ ] Create table "Category"
 *  [ ] Create table "Category_Item" referencing back to Product_Id
 *  [ ] Create table "Category_Sub" referencing back to Category_Id or SubCat_Id
 *  [ ] Create table "Product_Version"            (Ex: xRehab "1.2")
 *  [ ] Create table "Product_Component"          (Ex: xRehab v1.2 > "ROM")
 *  [ ] Create table "Product_Component_Version"  (Ex: xRehab > ROM > "2.3")
 *
 * Change Log:
 * [2012-0131] - v0.2.2
 */


/*
  Changes
  2012-06-06  + Added column Product_Code
              * Set Product_Id to unsigned auto increment
*/
CREATE TABLE IF NOT EXISTS `TBLPMT_PRODUCT`
(
  Product_Id              INT UNSIGNED NOT NULL AUTO_INCREMENT,           -- Product ID Number - Unique value for internal tracking
  Product_Code            VARCHAR(255) collate utf8_unicode_ci ,          -- Unique manufacturer product code (Ex: "xi-1337")
  Product_Name            VARCHAR(255) collate utf8_unicode_ci NOT NULL,  -- Short name of product (Ex: "MMT-IR")
  Product_Description     BLOB collate utf8_unicode_ci,                   -- Description of the product
  Category                VARCHAR(50) collate utf8_unicode_ci,            -- What type of product it is (Ex: "hardware", "software", etc.)
  Sub_Category            VARCHAR(15),            --      Sub Category (Ex: "Muscle Evaluation", "Utility", "FTP-Client")
  Release_DTTM            DATETIME,               --      Product Go-Live
  Update_DTTM             DATETIME,               --      YYYYMMDD of last detail update
  Decommission_DTTM       DATETIME,               --      Date of product decommission (past, present, future)
  Changed_Uid             VARCHAR(50) collate utf8_unicode_ci,            -- UserId who made last update to this product information   [* INT to Varchar to match PMT-USERID *]
  PMT_Allow_Global_Users  BOOLEAN DEFAULT FALSE,                          -- Used by PMT - Allow the master list of users to view product
  PMT_Path                VARCHAR(256) collate utf8_unicode_ci,           -- Used by PMT - Location in PMT (if intended for DL, then this should be its own normalized table)
  PRIMARY KEY (`Product_Id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*
  ** Probably reject addition **
  Listing of updates to product (minor revisions).
  This will be used for GA Revisions made to a Branch copy
  
  Note:
  There should be a row for each Minor version. Only revisions should be
  updated; except for BETAs, they are allowed.
    i.e. [OK] "1.5.2" && "1.5.3"(beta)    [NOT-OK] "1.5.2" && "1.5.4"

  Changes:
  2012-06-06  * Made Version_Update_Id INT UNSIGNED
              * Made Product_Id INT UNSIGNED

*/
--create table PRODUCT_VERSION_UPDATE
--create table PRODUCT_UPDATE
CREATE TABLE IF NOT EXISTS `TBLPMT_PRODUCT_VERSION`
(
  Version_Update_Id   INT UNSIGNED NOT NULL AUTO_INCREMENT,  -- Unique identifyer for update
  Product_Id          INT UNSIGNED,           -- xi_product.ProductId
  Release_Dttm        DATETIME,               -- Date the revision was released
  Decommission_Dttm   DATETIME,
  Development_Build   BOOLEAN DEFAULT FALSE,  -- Version marked as a development "beta" build
  Version             VARCHAR(15),            -- Full version number (1.22.333.44444)
  Version_Major       INTEGER,                -- Major Version Number (1)
  Version_Minor       INTEGER,                -- Minor number (22)
  Version_Revision    VARCHAR(10),            -- Revision Version Number (333.rc4)
  Update_User_Name    VARCHAR(50),
  PRIMARY KEY (`Version_Update_Id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

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

