# Section 1 - Staging Area Setup

**Note:** Staging-Area does not contain an installer, this must be done by hand

### Steps:
1) Create User
2) Create Database
3) Grant access
4) Cleanup

```sql
CREATE USER 'testuser2'@'localhost' IDENTIFIED BY 'testpass';
CREATE DATABASE PMT_TEST;
GRANT ALL ON PMT_TEST.* to testuser2@localhost;
FLUSH PRIVILEGES;
```

# Section 2 - New Features

## New Variables
```php
$xpmtModule[]
```

## New "Custom" Folder
```
./custom/
./custom/theme
./custom/mod
./custom/lib
```

* Contains the user's custom folder for Themes, Modules and Libraries
* The system will one day pull from this directory as well as the standard "``xpmt``" core folder.


# Section 3 - System Variables

## Core Variables

### Configuration Variables
```php
$pmt_RootUserEnabled
$pmt_RootUserName
$pmt_RootUserPass
```

### Global Variables
```php
$xpmtConf[][]                       array     Configuration Array
  $xpmtConf["db"]["server"]         string    = "localhost"       // Database Server
  $xpmtConf["db"]["dbname"]         string    = "PMT_DATA"        // Database Name
  $xpmtConf["db"]["prefix"]         string    = "XI_"             // Table prefix
  $xpmtConf["db"]["user"]           string    = "betauser"        // Database Admin User
  $xpmtConf["db"]["pass"]           string    = "betapass"        // Database Password
  $xpmtConf["general"]["auth_only"] boolean   = true              // Allow access to public or auth-only
  $xpmtConf["general"]["title"]     string    = "xenoPMT 0.0.5"   // Default website title
  $xpmtConf["general"]["base_url"]  string    = "http://pmt/"     // Must include '/' at the end.
  $xpmtConf["general"]["clean_uri"] integer   = "1"               // Clean URI

$xpmtCore["info"]["version"]        string    Version String (0.0.1)
$xpmtCore["info"]["version_ex"]     integer   Numerical version string ('000001' = 'x-xx-xxx')
$xpmtCore["info"]["version_ex2"]    string    friendly value "000005" = (00 00 05)
$xpmtCore["info"]["db_version"]     string    maximum database version acceptable
$xpmtCore["page"]["cache"]          array     NOT USED  ::  array("setting"=>array());
$xpmtCore["page"]["breadcrumb"]     array     NOT USED  ::  array();
```

## Global Classes
```
$pmtDB            class     Database class
$user             class     Member class
$uri              class     URI Parser class
```

## Define
```php
PMT_VER           string    PMT Version number        $xpmtCore["info"]["version"]
PMT_PATH          string    Path to base install
PMT_TBL           string    Database table prefix     $xpmtConf["db"]["prefix"]
THEME             string    NOT USED                  $xpmtCore["uri"]->Anchor("xpmt/themes", GetSetting("theme"))
```

## Page Variables
```php
$PAGE_TITLE         // Page title
$PAGE_LOGO          // Site image path  ** not used yet.
$PAGE_METABAR       // User (login/usr-pref)/settings/logout/about
$PAGE_TOOLBAR       // Main toolbar
$PAGE_MINILEFT      // Mini-bar Left aligned (bread crumbs)
$PAGE_MINIRIGHT     // Mini-bar Right aligned (module node options)
$PAGE_HTDATA        // Main page html data
$PAGE_PATH          // Relative path to theme currently in use
```
