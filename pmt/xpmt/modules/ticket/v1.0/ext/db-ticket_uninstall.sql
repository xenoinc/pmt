/* *********************************************************************
 * Copyright 2010 (C) Xeno Innovations, Inc.
 * ALL RIGHTS RESERVED
 * Author:        Damian J. Suess
 * Document:      db-ticket_uninstall.sql
 * Created Date:  2012-09-06
 * Version:       0.2.0
 * Description:
 *   removes the issue ticketing system
 *
 * Change Log:
 * 2012-0906  + Initial creation (djs)
 **********************************************************************/


/*
  Project Enumerations
  DTTM:  2010-1112 - 2012-0306
*/
DROP TABLE IF EXISTS `TBLPMT_S_TBT_ENUM`;

/*
  Static P-Type - Helps split tickets to reference Products or Projects.
  DTTM: 2012-0305 - 2012-0306
*/
DROP TABLE IF EXISTS `TBLPMT_S_REFERENCE_TYPE`;


/* ***[ Ticket Tables ]******************************************** */
/*
  Ticket / TechRequest being issued
  DTTM: 2010-1107
*/
DROP TABLE IF EXISTS `TBLPMT_TICKET`;
DROP TABLE IF EXISTS `TBLPMT_TICKET_UPDATE`;
DROP TABLE IF EXISTS `TBLPMT_TICKET_ATTACHMENT`;


/* ***[ Bug Report Tables ]******************************************** */
/*
  DTTM: 2010-11-05 - 2012-0305
*/
DROP TABLE IF EXISTS `TBLPMT_BUG`;
DROP TABLE IF EXISTS `TBLPMT_BUG_UPDATE`;
DROP TABLE IF EXISTS `TBLPMT_BUG_ATTACHMENT`;


/* ***[ Task Tables - ToDo List / Tech Request ]****************** */
/*
  Task Request table
  DTTM:  2010-1113 - 2012-0306
*/
DROP TABLE IF EXISTS `TBLPMT_TASK`;
DROP TABLE IF EXISTS `TBLPMT_TASK_UPDATE`;
DROP TABLE IF EXISTS `TBLPMT_TASK_ATTACHMENT`;