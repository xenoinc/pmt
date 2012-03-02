/* ********************************************************************
 * Copyright 2010-2011 (C) Xeno Innovations, Inc.
 * ALL RIGHTS RESERVED
 * Author:
 * Document:      pmt-db.sql
 * Created Date:  Oct 31, 2010, 11:03:17 PM
 * Last Update:
 * Version:       0.2.2
 * Description:
 *   Proposal for new DB tables used by the system
 *
 * Change Log:
 * 2012-0224  * (djs) Consulidating tables for Milestone 0.1 to this file.
 **********************************************************************/

/* Test table */
create table if not exists `PMT_SETTINGS`
(
  `Setting` varchar(255) collate utf8_unicode_ci not null,
  `Value`   longtext collate utf8_unicode_ci not null,
  primary key (`setting`)
) engine=InnoDB default charset=utf8 collate=utf8_unicode_ci;


/* Test table */
CREATE TABLE IF NOT EXISTS `PMT_USERS` (
  `Id` bigint(20) NOT NULL AUTO_INCREMENT,
  `Username` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `Password` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `Name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `Email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `Group_Id` bigint(20) NOT NULL DEFAULT '2',
  `Sesshash` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/* */
CREATE TABLE IF NOT EXISTS `PMT_USER` (
  `User_Id`     BIGINT(20) NOT NULL AUTO_INCREMENT,
  `Username`    VARCHAR(255) COLLATE utf8_unicode_ci NOT NULL,
  `Password`    VARCHAR(255) COLLATE utf8_unicode_ci NOT NULL,  -- encrypted user password
  `Name_First`  VARCHAR(75) COLLATE utf8_unicode_ci NOT NULL,   -- First Name
  `Name_Last`   VARCHAR(75) COLLATE utf8_unicode_ci NOT NULL,   -- Last Name
  `Name_Middle` VARCHAR(75) COLLATE utf8_unicode_ci NOT NULL,   -- Middle Name
  `Name_Title`  VARCHAR(4) COLLATE utf8_unicode_ci NOT NULL,    -- Dr, Mr, Mrs, Ms
  `Name_Salu`   VARCHAR(4) COLLATE utf8_unicode_ci NOT NULL,    -- Sr, Jr, III, IV, Esq(uire)
  `Customer_Id` VARCHAR(10),  -- Only used if it's a customer
  `Active`      BOOLEAN,      -- Account is active

  `Termination_Dttm`    DATETIME,           -- used for interns, etc
  `Created_Dttm`        DATETIME,           -- when was user created
  `Password_Exp_Dttm`   DATETIME,           -- date of password expiration

  `Last_Login_Dttm`     DATETIME,           -- Last date/time of login
  `Last_Login_Ip`       VARCHAR(15),        -- Last logged in from (IPv4)
  `Receive_Updates`     INT,                -- email updates
  primary key (`User_Ndx`)
);

);

