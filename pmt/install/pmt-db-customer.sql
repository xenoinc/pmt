CREATE TABLE IF NOT EXISTS `TBLPMT_CUSTOMER`
(
  `Customer_Index`  INT UNSIGNED NOT NULL AUTO_INCREMENT, -- Only used by internal indexing
  `Customer_Id`     VARCHAR(256) COLLATE utf8_unicode_ci, -- Custom Customer ID
  `Name`            VARCHAR(256) COLLATE utf8_unicode_ci, -- Full Customer Name  [* Changed column name & size *]
  `Address1`        VARCHAR(100) COLLATE utf8_unicode_ci,               --      PO Box 13543
  `Address2`        VARCHAR(100) COLLATE utf8_unicode_ci DEFAULT '',  -- [*]  Suite 23            [* MOD: added 'Default null' *]
  `City`            VARCHAR(64) COLLATE utf8_unicode_ci,                --      Pittsburgh
  `State`           VARCHAR(64) COLLATE utf8_unicode_ci,                --      Pennsylvania
  `Zip_Code`        VARCHAR(16) COLLATE utf8_unicode_ci,                --      15203-1234
  `Country`         VARCHAR(128) COLLATE utf8_unicode_ci,                --      United States of America
  `Website`         VARCHAR(256) COLLATE utf8_unicode_ci,               --      Customer's Website
  `Update_Dttm`     DATETIME,                   --  +   Date of updated information
  `Changed_Uid`     INT UNSIGNED,                --  +   UserId who made last update to this product information
  PRIMARY KEY (`Customer_Index`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE IF NOT EXISTS `TBLPMT_CUSTOMER_CONTACT`
(
  `Customer_Contact_Id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `Customer_Index`      INT UNSIGNED, -- Customer linked to
  `Name_First`          VARCHAR(64) COLLATE utf8_unicode_ci,  -- First name of contact
  `Name_Last`           VARCHAR(64) COLLATE utf8_unicode_ci,  -- Surname
  `Title`               VARCHAR(25) COLLATE utf8_unicode_ci,  -- Position / Title
  `Contact_Priority`    INT,          -- Is this person a main contact
  `Primary`             BOOLEAN,      -- This takes place of rows in PMT_CUSTOMER (to normalize it)
  PRIMARY KEY (`Customer_Contact_Id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE IF NOT EXISTS `TBLPMT_CUSTOMER_CONTACT_DATA`
(
  Customer_Contact_Id INT UNSIGNED NOT NULL,
  Detail_Type_Id  TINYINT UNSIGNED,   -- Phone, Cell, Address, Fax, Email
  Detail_Priority INTEGER,            -- Order in list
  Detail_Data     VARCHAR(256) collate utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE IF NOT EXISTS `TBLPMT_S_CONTACT_DETAIL_TYPE`
(
  `Detail_Type_Id` TINYINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `Detail_Type`    VARCHAR(24) COLLATE utf8_unicode_ci NOT NULL,   -- email, phone, fax
  `Description`    VARCHAR(64) COLLATE utf8_unicode_ci DEFAULT '', -- Description of
  PRIMARY KEY (`Detail_Type_Id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `TBLPMT_S_CONTACT_DETAIL_TYPE` (Detail_Type, Description) VALUES ('Email', 'Email Address');
INSERT INTO `TBLPMT_S_CONTACT_DETAIL_TYPE` (Detail_Type, Description) VALUES ('Phone', 'Phone');
INSERT INTO `TBLPMT_S_CONTACT_DETAIL_TYPE` (Detail_Type, Description) VALUES ('Mobile', 'Cell Phone');
INSERT INTO `TBLPMT_S_CONTACT_DETAIL_TYPE` (Detail_Type, Description) VALUES ('Fax', 'Fax machine');
INSERT INTO `TBLPMT_S_CONTACT_DETAIL_TYPE` (Detail_Type, Description) VALUES ('Website', 'Websites');

CREATE TABLE IF NOT EXISTS `TBLPMT_CUSTOMER_PRODUCT`
(
  `CPID`        INT UNSIGNED NOT NULL AUTO_INCREMENT,    -- ** Customer Product ID
  `Customer_Id` VARCHAR(256) COLLATE utf8_unicode_ci NOT NULL, -- CustomerId to link to
  `Product_Id`  INT UNSIGNED,   -- Product Identificaiton Number (xi_product.ProductId)
  `Product_Cost`  VARCHAR(10) COLLATE utf8_unicode_ci,  -- Sales price paid for product (ex: 3222111.00) [** Changed: FLOAT to VC(10) **]
  `Paid_Ammount`  VARCHAR(10) COLLATE utf8_unicode_ci,  -- Sum of payment paid to date
  `Payment_Plan`  BOOLEAN,                  -- Are they on payment plan?   1=yes 0=no (see payment plan table)
  `Num_Licenses`  INT UNSIGNED DEFAULT 3,   -- How many licenses are they allowed?
  `Install_Dttm`      DateTime,             -- Date of installation
  `Last_Update_Dttm`  DateTime,             -- Last update of product version
  `Allow_Beta`      BOOLEAN DEFAULT FALSE,  -- Customer is allowed development's pre-releases
  `Update_Enabled`  BOOLEAN DEFAULT FALSE,  -- Allow customer to update this product  ** Used by updater application
  `Update_Major`    BOOLEAN DEFAULT FALSE,  -- Allowed to upgrade? x.x.x  def=0  ** Used by updater application
  `Update_Minor`    BOOLEAN DEFAULT FALSE,  -- Allowed to upgrade? #.x.x  def=0  ** Used by updater application
  `Update_Rev`      BOOLEAN DEFAULT FALSE,  -- Allowed to upgrade? #.#.x  def=1  ** Used by updater application
  PRIMARY KEY (`CPID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE IF NOT EXISTS `TBLPMT_CUSTOMER_PRODUCT_UPDATE`
(
  `Customer_Id`         VARCHAR(256) COLLATE utf8_unicode_ci,      -- xi_customer.CustomerId
  `Product_Id`          INT UNSIGNED,      --
  `Changed_Uid`         INT UNSIGNED, -- UserId who made last update to this product information
  `Update_Dttm`         DATETIME,     -- Date of the update itself
  `New_Product_Version` VARCHAR(24) COLLATE utf8_unicode_ci,  -- Updated to version 'X'
  `Installed_By`        VARCHAR(256) COLLATE utf8_unicode_ci  -- Who installed the product (user_id or "xiUpdate" [app])
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


CREATE TABLE IF NOT EXISTS `TBLPMT_CUSTOMER_PRODUCT_REGISTRATION`
(
  `Product_Reg_Id`    INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `Customer_Id`       VARCHAR(256) COLLATE utf8_unicode_ci, -- Unique Customer ID defined in 'xi_customer'
  `MMT1_Customer_Id`  VARCHAR(16) COLLATE utf8_unicode_ci,  -- Fdx-MMT1 Customer ID [actually 8 chars]
  `MMT1_Mode_Id`      SMALLINT,      -- 1="std"  2="line mode"
  `Licensed_To`       VARCHAR(50) COLLATE utf8_unicode_ci,  -- Being used by specific person(s)  (Customer: UPMC,, Licensed To: Dr SOnSO)
  `Old_Custom1`       VARCHAR(50) COLLATE utf8_unicode_ci,  -- Custom Info / Address Line 1
  `Old_Custom2`       VARCHAR(50) COLLATE utf8_unicode_ci,  -- Custom Info / Address Line 2  *Special Contact Name, etc.*
  `Old_Custom3`       VARCHAR(50) COLLATE utf8_unicode_ci,  -- Custom Info / Address Line 3
  `Old_Custom4`       VARCHAR(50) COLLATE utf8_unicode_ci,  -- Custom Info / Address Line 4
  PRIMARY KEY (Product_Reg_Id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

