Copyright (c) 2010-2012 Xeno Innovations, Inc.
-----------------------------------------------
Readme-Dev.txt consist of general documentation which outlines
the items which you need to get you going
  1) Creating a Test Database
  2) PMT Project Pages (overview)
  3) Basic Folder structure
  4) Example: Mulitple Projects
  5) Example: User Interface

Change Log:
-----------
2012-0224 [djs] * Note, the project's folder structure has reciently changed as of
                  version 0.1 "nighthawk" (Concept-3). This will need to be changed.
2012-0112 [djs] * Created structure baised upon v0.1 "Concept-2"
###################################


Section 1
Creating Test Database
----------------------


/* Log into MySQL */
mysql -h localhost  -u root -p

/* Create User */
CREATE USER 'testuser'@'localhost' IDENTIFIED BY 'testpass';

/* Create database */
CREATE DATABASE PMT_DATA;

/* Add user permissions */
GRANT ALL ON PMT_DATA.* TO testuser@localhost;

/* Cleanup and Exit */
flush privileges;
exit;

  

Section 2
PMT Project Pages (overview)
----------------------------
Below is a list of pages were the project is hosted/tracked

* https://github.com/xenoinc/pmt
* https://www.ohloh.net/p/pmt



Section 3
Basic Folder structure
----------------

|/doc/
|/pmt/
|   ./install/
|   ./lib/
|     ./common/
|     ./modules/
|     ./themes/
|       ./default/
|   index.php
|/sandbox/
---------------------



Section 4
Example: Mulitple Projects
-----------------

To access a different projects, simply navigate to the name of it:
  
  Base Dir:
  http://pmt.xenoinc.org/

  Projects:
  http://pmt.xenoinc.org/project1
  http://pmt.xenoinc.org/project2




Section 5
Example: User Interface
--------------

 ______________________________________________
|  <title text/image>                          |
|----------------------------------------------|
|                            <meta toolbar --> |
|----------------------------------------------|
| <--Main Toolbar>                             |
|----------------------------------------------|
| (left mini-bar)              (right mini-bar)|
|----------------------------------------------|
|                                              |
|            << User Interface >>              |
|                                              |
|______________________________________________|

