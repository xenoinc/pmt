CREATE TABLE IF NOT EXISTS `TBLPMT_PRODUCT`
(
  Product_Id              INTEGER UNSIGNED NOT NULL,    --      Product ID Number - Unique value for internal tracking
  Product_Name            VARCHAR(255) collate utf8_unicode_ci NOT NULL,                     --      Short name of product (Ex: "MMT-IR")
  Product_Description     BLOB,                   --      Description of the product
  Category                VARCHAR(15),            --      What type of product it is (Ex: "hardware", "software", etc.)
  Sub_Category            VARCHAR(15),            --      Sub Category (Ex: "Muscle Evaluation", "Utility", "FTP-Client")
  Release_DTTM            DATETIME,               --      Product Go-Live
  Update_DTTM             DATETIME,               --      YYYYMMDD of last detail update
  Decommission_DTTM       DATETIME,               --      Date of product decommission (past, present, future)
  Changed_UID             VARCHAR(15),            -- [*]  UserId who made last update to this product information   [* INT to Varchar to match PMT-USERID *]
  PMT_Allow_Global_Users  BOOLEAN DEFAULT FALSE,  --      Used by PMT - Allow the master list of users to view product
  PMT_Path                VARCHAR(256),           --      Used by PMT - Location in PMT (if intended for DL, then this should be its own normalized table)
  PRIMARY KEY (`Product_Id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE IF NOT EXISTS `TBLPMT_PRODUCT_VERSION`
(
  Version_Update_Id   INTEGER NOT NULL AUTO_INCREMENT,  -- Unique identifyer for update
  Product_Id          INTEGER,      -- xi_product.ProductId
  Release_Dttm        DATETIME,     -- Date the revision was released
  Decommission_Dttm   DATETIME,
  Development_Build   BOOLEAN DEFAULT FALSE,  -- Version marked as a development "beta" build
  Version             VARCHAR(15),  -- Full version number (1.22.333.44444)
  Version_Major       INTEGER,      -- Major Version Number (1)
  Version_Minor       INTEGER,      -- Minor number (22)
  Product_Revision    VARCHAR(10),  -- Revision Version Number (333.rc4)
  Update_User_Name    VARCHAR(50),
  PRIMARY KEY (`Version_Update_Id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE IF NOT EXISTS `TBLPMT_PRODUCT_INVENTORY`
(
  `Product_Id`  INT UNSIGNED NOT NULL,
  `Qty`         DOUBLE,
  `Min`         DOUBLE,
  `Max`         DOUBLE,
  `Average`     DOUBLE,
  PRIMARY KEY (`Product_Id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

