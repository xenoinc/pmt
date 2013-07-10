drop table xi_product;
create table xi_product
(
  product_id          INTEGER UNSIGNED NOT NULL,
  product_name        VARCHAR(50) NOT NULL,
  product_desc        BLOB,
  category            VARCHAR(15),
  sub_category        VARCHAR(15),
  version_major       VARCHAR(5),
  pmt_path            VARCHAR(256),
  release_dttm        DATETIME,
  update_dttm         DATETIME,
  decommission_dttm   DATETIME,
  changed_uid         INTEGER,
  allow_global_users  BOOLEAN DEFAULT FALSE
);

drop table xi_product_version;
CREATE TABLE xi_product_version
(
  product_id          INTEGER UNSIGNED NOT NULL,
  product_name        VARCHAR(50) NOT NULL DEFAULT '',
  version             VARCHAR(15),
  is_default          BOOLEAN,
  pmt_path            VARCHAR(256),
  release_dttm        DATETIME,
  update_dttm         DATETIME,
  decommission_dttm   DATETIME,
  changed_uid         VARCHAR(15),
  allow_global_users  BOOLEAN DEFAULT FALSE,
  description         BLOB
);

drop table xi_customer;
CREATE TABLE xi_customer
(
  customer_ndx_id   INTEGER UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  customer_id       VARCHAR(10),
  customer_name     VARCHAR(256),
  licensed_to       VARCHAR(50),
  address1          VARCHAR(100),
  address2          VARCHAR(100),
  addr_city         VARCHAR(50),
  addr_state        VARCHAR(50),
  addr_zip          VARCHAR(10),
  addr_country      VARCHAR(50),
  website           VARCHAR(256),
  phone1            VARCHAR(25),
  phone2            VARCHAR(25),
  fax1              VARCHAR(25),
  email1            VARCHAR(50),
  email2            VARCHAR(50),
  custom1           VARCHAR(50),
  custom2           VARCHAR(50),
  custom3           VARCHAR(50),
  custom4           VARCHAR(50)
);

drop table xi_customer_mmt1;
CREATE TABLE xi_customer_mmt1
(
  customer_id       VARCHAR(10),
  mmt1_cid          VARCHAR(10),
  mmt1_id_mode      SMALLINT
);


drop table xi_customer_product;
create table xi_customer_product
(
  customer_id     VARCHAR(10) NOT NULL,
  product_id      INT NOT NULL,
  product_cost    FLOAT,
  paid_infull     INTEGER,
  num_licenses    INTEGER UNSIGNED,
  payment_plan    BOOLEAN,
  update_enabled  BOOLEAN,
  update_major    BOOLEAN,
  update_minor    BOOLEAN,
  update_rev      BOOLEAN
);

drop table xi_registered_license;
create table xi_registered_license
(
  regm_id         INTEGER UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  customer_id     VARCHAR(10),
  product_id      INTEGER,
  reg_dttm        DATETIME,
  exp_dttm        DATETIME,
  machine_id      TEXT,
  reg_status      BOOLEAN,
  revoke_status   BOOLEAN,
  revoke_reason   VARCHAR(50),
  allow_updates   BOOLEAN
);


