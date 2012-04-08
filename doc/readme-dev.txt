Copyright (c) 2010-2012 Xeno Innovations, Inc.
-----------------------------------------------

Change Log:
-----------


2012-0224 [djs]
  * Note, the project's folder structure has reciently changed as of
    version 0.1 "nighthawk" (Concept-3). This will need to be changed.
2012-0112 [djs]
  * Created structure baised upon v0.1 "Concept-2"


To Do:
[ ] Update Folder Structure to v0.1 "Nighthawk"


###################################


Section 1
Creating Test Database
----------------------


1) Log into MySQL
  mysql -h localhost  -u root -p

2) Create User
  CREATE USER 'testuser'@'localhost' IDENTIFIED BY 'testpass';

3) Create database
  CREATE DATABASE PMT_DATA;

4) Add user permissions
  GRANT ALL ON PMT_DATA.* TO testuser@localhost;

5) Cleanup and Exit
  flush privileges;
  exit;

  

Section 2
PMT Project Pages (overview)
----------------------------
Below is a list of pages were the project is hosted/tracked

* https://github.com/xenoinc/pmt
* https://www.ohloh.net/p/pmt



Section 3
Folder structure
----------------

|/Doc/
|/pmt/
|--/admin/
|--/htdoc/
|--/skin-std/
|--/lib/
|--/pluings/
|--/svn/
|--/wiki/
|/sample-plugin/
---------------------



Section 4
Mulitple Projects
-----------------

To access a different projects, simply navigate to the name of it:
  
  Base Dir:
  http://pmt.xenoinc.org/

  Projects:
  http://pmt.xenoinc.org/project1
  http://pmt.xenoinc.org/project2

  
  

Section 5
User Interface
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

