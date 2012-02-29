<?php
/************************************************************
 * Copyright 2010 (C) Xeno Innovations, Inc.
 * ALL RIGHTS RESERVED
 *
 * Author:
 * Damian J. Suess
 * 
 * **************************************
 * * Blank project config file template *
 * **************************************
 *
 * Description:
 *   This is where you set your Database, Default Skin, etc.
 *
 * Change Log:
 *  [2010-1029] - Initial Creation
*/


/*****************
 * DO NOT EDIT!!
 ******************/
$pmt_proj_version = "0.1";


/***************
 * SAFE TO EDIT
 **************/


 /**************************************************
 * Path to the Project Management Tracking engine
 * This is so you can host multiple projects
 **************************************************/
$pmt_core = "../index.php";



/****************************************************************
 * Location of local PMT database
 *
 * Type = "sqlite3" || "mysql"    ** SQLite DB must have RW access
 * Name = Name of your DB
 * Pre  = Pre-Pend all table names with the following
 * User = DB User name -- Used for MySQL  (with Read/Write Access)
 * Pass = DB Password  -- Used for MySQL
****************************** **********************************/

$pmt_proj_db_type = "";      // Types:  "sqlite3", "mysql"
$pmt_proj_db_name = "";    // name of the database

// Only edit if you need to!!
switch($pmt_proj_db_type)
{
  case "sqlite3":
    $pmt_proj_db = "db/" . $pmt_proj_db_name . ".db";   // if using sqlite3, point to path
    break;
  case "mysql":
    $pmt_proj_db = $pmt_proj_db_name;                   // if using sqlite3, point to path
    break;
}



/* If using MySQL then set the following:
 * --------------------------------------
 * Database Name
 * Database [Pre]       * Prepend to all table names.  *** This must be unique so the wiki system doesn't write over another one. ***
 * Database User/Pass   * ***Must have RW Access***
 */

$pmt_proj_db_pre  = ""; // Pre-Pend this to all DB-Tables to keep your project settings unique
$pmt_proj_db_user = "";
$pmt_proj_db_pass = "";




/************************************************************
 * Project Info
 *
 * Note:
 *   This should ALL be stored in the "pmt_proj.db" file.
 *   For now just force the value!
 *
 ***********************************************************/

$pmt_proj_namespace   = "";            // Name of project's directory (THIS MUST BE EXACT)
$pmt_proj_title       = "";     // Title of project
$pmt_proj_description = "";  // Project description

$pmt_proj_image_icon  = "";
$pmt_proj_image_logo  = "";
$pmt_proj_image_logo_alt    = "";
$pmt_proj_image_logo_link   = "";
$pmt_proj_image_logo_width  = "";
$pmt_proj_image_logo_height = "";


/***************************************
 * Name of the project's main Repository
 ***************************************/
$pmt_proj_svn_repo = "";


/********************
 * xiPMT Default Skin
 ********************/
$pmt_proj_defskin = "skin-std";



?>