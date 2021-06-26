# 0.6 Cleanup Notes

## Goals
The Project Management and Tracking system's phased rollout plan is as follows:
* Agile/Scrum product backlog system. Epics, features, PBIs and Bugs
  * Manage skunkworks projects - _internal R&D_
  * Manage products - _customer consumables_
  * Uses MarkDown formatting
* Gamification
  * Earn points for completing tasks!
* CMS - Customer Management System
  * List of customers - _external customers or internal departments_
  * Communication history with customer
  * Alerts for when to contact customer next, or overdue reach-out
  * Upload attachments linked to customers such as contract signings, or special terms-and-conditions
  * Email customers directly
  * Sales trends for products
  * Link products to customer
* PO - Purchase Order management system to manage your customer's purchases
  * Customer POs
  * Internal POs - _Buying stock from another department_
* Product Pages
  * Upload attachments (help docs, terms-and-conditions, contract-templates, etc.)
  * Mark attachments as internal/external only
  * Share documents via email (_if permissions allow_)
  * Link product (_customer visibility_) to Projects (_internal visibility_)
  * Sales Analytics
* Project pages - Documentation system for projects
* KB - Knowledgebase system
  * Used by helpdesk or engineering
  * MarkDown formatting
  * Searchable

## Merge Plan
1. v0.6.0 - Moving 0.5 (``sandbox/core-0.0.5``) into the main source folder
2. v0.6.1 - Code Cleanup

### Action Items
* Themes should use Twig for PHP/Html interlacing

## Folder "src"
* Removed ``.htaccess.bak`` - Old deprecated notes
* Imported all from 0.5

### src\updates
* This folder appears useless. It does
* It does contain some interesting code for file downloaders.

### src\Install
**TO DO BACK!!**
* Which SQL file do we keep??
*

### src\xpmt
* Imported all from 0.5

### src\xpmt\themes
The theme system needs upgraded to Twig for PHP/HTML interlacing

| Old          | 0.5       | Notes |
|--------------|-----------|-------|
| index.php    | index.php | (empty file) |
| kb.php       |           | NEEDS UPGRADED to 0.5 |
| projects.php |           | NEEDS UPGRADED to 0.5 |
| proj.css     |           | NEEDS UPGRADED to 0.5 |
| main.htm     |           | RMV - Test document layout |
| main.php     | main.php     | Uses the new associative array ``$xpmtPage["miniright"]`` |
| ---          | main_old.php | some old copy |
| skin.css     | skin.css     | Original may be newer/better |

# Merge of 0.4 into 0.5 to make 0.6

```
PS C:\work\xi\xenoPMT> git merge xpmt-0.0.5                                                                Updating 16babe1..9339fcf
Fast-forward
 .gitignore                                         |    2 +-
 ...ighly Effective Project Milestones - PM Hut.url |    2 +
 doc/database/{ => 0.0.2}/data-model.mwb            |  Bin
 doc/database/0.0.2/data-model.mwb.bak              |  Bin 0 -> 36258 bytes
 doc/database/{ => 0.0.2}/pmt-db-customer.sql       |    0
 doc/database/{ => 0.0.2}/pmt-db-kb.sql             |    0
 doc/database/{ => 0.0.2}/pmt-db-product.sql        |    0
 doc/database/{ => 0.0.2}/pmt-db-project.sql        |    0
 doc/database/{ => 0.0.2}/pmt-db-ticket.sql         |    0
 doc/database/{ => 0.0.2}/pmt-db-user.sql           |    0
 doc/database/{ => 0.0.2}/pmt-db.sql                |    0
 doc/database/0.0.5/data-model-rev2.mwb             |  Bin 0 -> 14615 bytes
 doc/database/0.0.5/data-model-rev2.mwb.bak         |  Bin 0 -> 14464 bytes
 doc/database/0.0.5/data-model.mwb                  |  Bin 0 -> 36300 bytes
 doc/database/0.0.5/pmt-db-customer.sql             |  163 ++
 doc/database/0.0.5/pmt-db-kb.sql                   |   62 +
 doc/database/0.0.5/pmt-db-product.sql              |  199 ++
 doc/database/0.0.5/pmt-db-project.sql              |  189 ++
 doc/database/0.0.5/pmt-db-ticket.sql               |  388 ++++
 doc/database/0.0.5/pmt-db-user.sql                 |  259 +++
 doc/database/0.0.5/pmt-db.sql                      |   60 +
 doc/database/db-004.docx                           |  Bin 0 -> 61958 bytes
 doc/database/db-005.docx                           |  Bin 0 -> 28096 bytes
 doc/database/readme.txt                            |   18 +
 doc/devnotes/readme.txt                            |   10 +
 doc/devnotes/xenoPMT-DevDocu.htm                   | 2200 ++++++++++++++------
 doc/modules/module-list.txt                        |   11 +
 doc/modules/readme.txt                             |    7 +
 doc/modules/search/readme.txt                      |   54 +
 .../Mobile Navigation Design & Tutorial.url        |    2 +
 .../Unsemantic CSS Framework.url                   |    2 +
 doc/todo-feature-samples/bug-skin - css tabs.png   |  Bin 0 -> 54308 bytes
 doc/todo-feature-samples/competition.txt           |    8 +
 .../jQuery/OmniWindow - jQuery Plugin Registry.url |    2 +
 ...ousetrap - Keyboard shortcuts in Javascript.url |    2 +
 .../jQuery Hotkeys - jQuery Plugin Registry.url    |    2 +
 ...oard shortcuts on websites - Stack Overflow.url |    2 +
 ...uery -hot key combinations - Stack Overflow.url |    2 +
 doc/todo-feature-samples/login-screen.png          |  Bin 0 -> 9415 bytes
 ...simile-widgets - Data Visualization Widgets.url |    2 +
 .../welcome-email.htm                              |    0
 readme.txt                                         |    4 +-
 sandbox/_feature-review/LygoEdit-jQueryWYSIWYG.url |    2 +
 sandbox/_feature-review/html2fpdf-3.0.2b.zip       |  Bin 0 -> 103683 bytes
 sandbox/core-0.4/.htaccess                         |   74 +
 sandbox/core-0.4/bak.htaccess                      |   51 +
 sandbox/core-0.4/index-old.php                     |  124 ++
 sandbox/core-0.4/index-test.php                    |  153 ++
 sandbox/core-0.4/index.php                         |   39 +
 sandbox/core-0.4/install/index.php                 |  686 ++++++
 sandbox/core-0.4/install/installer.php             |  159 ++
 sandbox/core-0.4/install/installer2.php            |   99 +
 sandbox/core-0.4/install/pmt-db-customer.sql       |  179 ++
 sandbox/core-0.4/install/pmt-db-kb.sql             |   88 +
 sandbox/core-0.4/install/pmt-db-product.sql        |  209 ++
 sandbox/core-0.4/install/pmt-db-project.sql        |  189 ++
 sandbox/core-0.4/install/pmt-db-ticket.sql         |  403 ++++
 sandbox/core-0.4/install/pmt-db-user.sql           |  273 +++
 sandbox/core-0.4/install/pmt-db.sql                |  141 ++
 sandbox/core-0.4/install/pmt.css                   |   92 +
 .../install/sql-templates/db-inventory.sql         |   15 +
 .../core-0.4/install/sql-templates/db-main-old.sql |  165 ++
 sandbox/core-0.4/install/sql-templates/db-main.sql |  181 ++
 sandbox/core-0.4/license.txt                       |  674 ++++++
 sandbox/core-0.4/updates/db-main.sql               |   98 +
 sandbox/core-0.4/updates/index.php                 |  146 ++
 sandbox/core-0.4/updates/index2.php                |  146 ++
 sandbox/core-0.4/updates/pmt-main.php              |   36 +
 sandbox/core-0.4/updates/pmtSQL.php                |  228 ++
 sandbox/core-0.4/updates/readme.txt                |   40 +
 .../core-0.4/updates/test-auto-download-file.txt   |   18 +
 sandbox/core-0.4/updates/xr1.php                   |   85 +
 sandbox/core-0.4/updates/xr1.xml                   |    7 +
 .../xpmt/config.default.php}                       |   29 +-
 sandbox/core-0.4/xpmt/core/pmt.db.php              |  220 ++
 sandbox/core-0.4/xpmt/core/pmt.i.module.php        |  132 ++
 sandbox/core-0.4/xpmt/core/pmt.log.php             |  162 ++
 sandbox/core-0.4/xpmt/core/pmt.member.php          |  367 ++++
 sandbox/core-0.4/xpmt/core/pmt.module.php          |  125 ++
 sandbox/core-0.4/xpmt/core/pmt.uri.php             |  146 ++
 sandbox/core-0.4/xpmt/debug.php                    |   26 +
 sandbox/core-0.4/xpmt/http.php                     |  132 ++
 .../core-0.4/xpmt/libraries/jquery/jquery.min.js   |    2 +
 .../xpmt/libraries/markitup/jquery.markitup.js     |  593 ++++++
 .../markitup/sets/default/images/bold.png          |  Bin 0 -> 304 bytes
 .../markitup/sets/default/images/clean.png         |  Bin 0 -> 667 bytes
 .../markitup/sets/default/images/image.png         |  Bin 0 -> 516 bytes
 .../markitup/sets/default/images/italic.png        |  Bin 0 -> 223 bytes
 .../markitup/sets/default/images/link.png          |  Bin 0 -> 343 bytes
 .../markitup/sets/default/images/list-bullet.png   |  Bin 0 -> 344 bytes
 .../markitup/sets/default/images/list-numeric.png  |  Bin 0 -> 357 bytes
 .../markitup/sets/default/images/picture.png       |  Bin 0 -> 606 bytes
 .../markitup/sets/default/images/preview.png       |  Bin 0 -> 537 bytes
 .../markitup/sets/default/images/stroke.png        |  Bin 0 -> 269 bytes
 .../xpmt/libraries/markitup/sets/default/set.js    |   30 +
 .../xpmt/libraries/markitup/sets/default/style.css |   34 +
 .../skins/markitup/images/bg-container.png         |  Bin 0 -> 322 bytes
 .../skins/markitup/images/bg-editor-bbcode.png     |  Bin 0 -> 1642 bytes
 .../skins/markitup/images/bg-editor-dotclear.png   |  Bin 0 -> 1682 bytes
 .../skins/markitup/images/bg-editor-html.png       |  Bin 0 -> 1534 bytes
 .../skins/markitup/images/bg-editor-json.png       |  Bin 0 -> 1529 bytes
 .../skins/markitup/images/bg-editor-markdown.png   |  Bin 0 -> 1783 bytes
 .../skins/markitup/images/bg-editor-textile.png    |  Bin 0 -> 1659 bytes
 .../skins/markitup/images/bg-editor-wiki.png       |  Bin 0 -> 1488 bytes
 .../skins/markitup/images/bg-editor-xml.png        |  Bin 0 -> 1495 bytes
 .../markitup/skins/markitup/images/bg-editor.png   |  Bin 0 -> 1101 bytes
 .../markitup/skins/markitup/images/handle.png      |  Bin 0 -> 258 bytes
 .../markitup/skins/markitup/images/menu.png        |  Bin 0 -> 254 bytes
 .../markitup/skins/markitup/images/submenu.png     |  Bin 0 -> 240 bytes
 .../libraries/markitup/skins/markitup/style.css    |  147 ++
 .../markitup/skins/simple/images/handle.png        |  Bin 0 -> 258 bytes
 .../markitup/skins/simple/images/menu.png          |  Bin 0 -> 27151 bytes
 .../markitup/skins/simple/images/submenu.png       |  Bin 0 -> 240 bytes
 .../xpmt/libraries/markitup/skins/simple/style.css |  118 ++
 .../xpmt/libraries/markitup/templates/preview.css  |    5 +
 .../xpmt/libraries/markitup/templates/preview.html |   11 +
 sandbox/core-0.4/xpmt/modcontroller.php            |  400 ++++
 sandbox/core-0.4/xpmt/modules/.readme.txt          |   25 +
 sandbox/core-0.4/xpmt/modules/ProjectTicket.php    |   21 +
 .../core-0.4}/xpmt/modules/admin/Admin.php         |    0
 .../core-0.4/xpmt/modules/dashboard/dashboard.php  |  166 ++
 sandbox/core-0.4/xpmt/modules/kb/kb-edit.php       |   27 +
 sandbox/core-0.4/xpmt/modules/kb/kb-list.php       |  118 ++
 sandbox/core-0.4/xpmt/modules/kb/kb-main.php       |  119 ++
 sandbox/core-0.4/xpmt/modules/kb/kb-new.php        |  524 +++++
 sandbox/core-0.4/xpmt/modules/kb/kb-view.php       |  186 ++
 sandbox/core-0.4/xpmt/modules/kb/kb-view.xsl       |   27 +
 sandbox/core-0.4/xpmt/modules/kb/kb.php            |  370 ++++
 sandbox/core-0.4/xpmt/modules/kb/v0.4/kb.php       |   34 +
 .../xpmt/modules/old-unused/project_0.2.3.php      |  635 ++++++
 .../core-0.4/xpmt/modules/product/product.main.php |   49 +
 sandbox/core-0.4/xpmt/modules/product/product.php  |   42 +
 .../core-0.4/xpmt/modules/project/ext/.readme.txt  |   15 +
 .../xpmt/modules/project/ext/project.ext.new.php   |  268 +++
 .../xpmt/modules/project/ext/project.ext.view.php  |  156 ++
 .../xpmt/modules/project/ext/project.ext.wiki.php  |   52 +
 .../xpmt/modules/project/ext/project.rss.php       |   22 +
 sandbox/core-0.4/xpmt/modules/project/project.php  |  597 ++++++
 sandbox/core-0.4/xpmt/modules/sample/sample.php    |  223 ++
 sandbox/core-0.4/xpmt/modules/svn/.readme.txt      |   16 +
 sandbox/core-0.4/xpmt/modules/svn/subversion.php   |   35 +
 sandbox/core-0.4/xpmt/modules/ticket/.readme.txt   |   10 +
 .../xpmt/modules/ticket/ext/ticket-main.php        |   48 +
 .../xpmt/modules/ticket/ext/ticket-view.xsl        |   83 +
 .../xpmt/modules/ticket/ext/ticket.extras.php      |   53 +
 .../xpmt/modules/ticket/sample-ticket-view.htm     |  370 ++++
 sandbox/core-0.4/xpmt/modules/ticket/ticket.php    |  210 ++
 .../core-0.4/xpmt/modules/ticket/v1.0/.readme.txt  |   10 +
 .../modules/ticket/v1.0/ext/db-ticket_install.sql  |  399 ++++
 .../ticket/v1.0/ext/db-ticket_uninstall.sql        |   55 +
 .../xpmt/modules/ticket/v1.0/ext/ticket.view.php   |   24 +
 .../xpmt/modules/ticket/v1.0/ticket.main.php       |   49 +
 .../core-0.4/xpmt/modules/ticket/v1.0/ticket.php   |   35 +
 .../xpmt/modules/ticket/v1.0/ticket.setup.php      |   45 +
 sandbox/core-0.4/xpmt/modules/user.php             |  249 +++
 sandbox/core-0.4/xpmt/modules/uuid/uuid.main.php   |  196 ++
 sandbox/core-0.4/xpmt/modules/uuid/uuid.php        |   36 +
 sandbox/core-0.4/xpmt/modules/wiki/.readme.txt     |   15 +
 sandbox/core-0.4/xpmt/phpConsole.php               |  384 ++++
 sandbox/core-0.4/xpmt/pmt-functions.php            |  122 ++
 sandbox/core-0.4/xpmt/pmt.php                      |  278 +++
 sandbox/core-0.4/xpmt/security.php                 |   64 +
 sandbox/core-0.4/xpmt/themes/default/dashboard.txt |   18 +
 .../xpmt/themes/default/gfx/logo-footer.png        |  Bin 0 -> 2442 bytes
 .../core-0.4/xpmt/themes/default/gfx/logo-mck.jpg  |  Bin 0 -> 4451 bytes
 sandbox/core-0.4/xpmt/themes/default/gfx/logo.png  |  Bin 0 -> 6108 bytes
 .../xpmt/themes/default/gfx/proj-action-fade.png   |  Bin 0 -> 277 bytes
 .../xpmt/themes/default/gfx/proj-default-icon.png  |  Bin 0 -> 725 bytes
 .../core-0.4/xpmt/themes/default/gfx/proj-tbt.png  |  Bin 0 -> 2314 bytes
 .../xpmt/themes/default/gfx/topbar_gradient.png    |  Bin 0 -> 248 bytes
 .../xpmt/themes/default/gfx/topbar_gradient2.png   |  Bin 0 -> 457 bytes
 .../core-0.4/xpmt/themes/default/gfx/xeno-lg.png   |  Bin 0 -> 15027 bytes
 .../core-0.4/xpmt/themes/default/gfx/xeno-sm.png   |  Bin 0 -> 8775 bytes
 sandbox/core-0.4/xpmt/themes/default/index.php     |    0
 sandbox/core-0.4/xpmt/themes/default/kb.css        |  138 ++
 sandbox/core-0.4/xpmt/themes/default/kb.php        |  123 ++
 sandbox/core-0.4/xpmt/themes/default/main.htm      |   66 +
 sandbox/core-0.4/xpmt/themes/default/main.php      |  123 ++
 sandbox/core-0.4/xpmt/themes/default/proj.css      |  151 ++
 sandbox/core-0.4/xpmt/themes/default/projects.php  |   54 +
 sandbox/core-0.4/xpmt/themes/default/skin.css      |  390 ++++
 sandbox/core-0.4/xpmt/themes/trac13/index.php      |    0
 sandbox/core-0.5/.gitignore                        |    3 +-
 sandbox/core-0.5/.htaccess                         |    7 +
 sandbox/core-0.5/config.sample.txt                 |   49 +
 sandbox/core-0.5/docu.txt                          |   37 +-
 sandbox/core-0.5/index.php                         |   19 +-
 sandbox/core-0.5/install/index.php                 |  100 +-
 sandbox/core-0.5/install/install.ajax.php          |  321 ++-
 sandbox/core-0.5/install/installer.js              |   40 +
 sandbox/core-0.5/install/sql/pmt-db-core.sql       |   58 +-
 sandbox/core-0.5/xpmt/config.default.php           |   68 +-
 sandbox/core-0.5/xpmt/core/pmt.db.php              |    5 +-
 sandbox/core-0.5/xpmt/core/pmt.i.module.php        |  217 +-
 sandbox/core-0.5/xpmt/core/pmt.member.php          |  177 +-
 sandbox/core-0.5/xpmt/core/pmt.module.php          |    4 +-
 sandbox/core-0.5/xpmt/core/pmt.uri.php             |   73 +-
 sandbox/core-0.5/xpmt/core/xenopmt.php             |  556 +++++
 sandbox/core-0.5/xpmt/core/xpmt.i.setup.php        |   84 +
 sandbox/core-0.5/xpmt/core2/Error.php              |  103 +
 sandbox/core-0.5/xpmt/core2/Functions.php          |  121 ++
 sandbox/core-0.5/xpmt/core2/Library.php            |   82 +
 sandbox/core-0.5/xpmt/core2/Module.php             |  442 ++++
 .../core-0.5/xpmt/core2/Properties/ModuleInfo.php  |   74 +
 .../xpmt/core2/Properties/ModuleSetupError.php     |   54 +
 sandbox/core-0.5/xpmt/core2/Properties/Page.php    |  175 ++
 sandbox/core-0.5/xpmt/core2/Setup.php              |  291 +++
 sandbox/core-0.5/xpmt/core2/misc/Struct.php        |  103 +
 .../core-0.5/xpmt/core2/test/clsAbstractProp.php   |   26 +
 sandbox/core-0.5/xpmt/core2/test/clsGetSet.php     |   23 +
 sandbox/core-0.5/xpmt/core2/xpmtPage.php           |  100 +
 .../core-0.5/xpmt/libraries/PhpConsole/Auth.php    |  106 +
 .../xpmt/libraries/PhpConsole/Connector.php        |  640 ++++++
 .../xpmt/libraries/PhpConsole/Dispatcher.php       |  104 +
 .../xpmt/libraries/PhpConsole/Dispatcher/Debug.php |   39 +
 .../libraries/PhpConsole/Dispatcher/Errors.php     |  134 ++
 .../libraries/PhpConsole/Dispatcher/Evaluate.php   |   72 +
 .../core-0.5/xpmt/libraries/PhpConsole/Dumper.php  |  181 ++
 .../xpmt/libraries/PhpConsole/EvalProvider.php     |  242 +++
 .../core-0.5/xpmt/libraries/PhpConsole/Handler.php |  230 ++
 .../core-0.5/xpmt/libraries/PhpConsole/Helper.php  |  121 ++
 .../libraries/PhpConsole/OldVersionAdapter.php     |  123 ++
 .../xpmt/libraries/PhpConsole/PsrLogger.php        |   77 +
 .../xpmt/libraries/PhpConsole/__autoload.php       |   10 +
 .../core-0.5/xpmt/libraries/timeline/readme.txt    |    0
 sandbox/core-0.5/xpmt/libraries/wiki/readme.txt    |    0
 sandbox/core-0.5/xpmt/modcontroller.php            |   47 +-
 sandbox/core-0.5/xpmt/modules/admin/admin.main.php |   89 +
 .../core-0.5/xpmt/modules/admin/admin.module.php   |   19 +
 sandbox/core-0.5/xpmt/modules/admin/admin.php      |   37 +
 .../core-0.5/xpmt/modules/admin/admin.setup.php    |  459 ++++
 sandbox/core-0.5/xpmt/modules/admin/admin.user.php |   19 +
 .../xpmt/modules/admin/ext/general-files.html      |   45 +
 .../xpmt/modules/dashboard/dashboard.main.php      |  112 +
 .../core-0.5/xpmt/modules/dashboard/dashboard.php  |   37 +
 .../xpmt/modules/dashboard/dashboard.setup.php     |  593 ++++++
 .../core-0.5/xpmt/modules/sample/sample.main.php   |    8 +-
 sandbox/core-0.5/xpmt/modules/sample/sample.php    |    8 +-
 .../core-0.5/xpmt/modules/sample/sample.setup.php  |  383 ++++
 .../sample/{sample.setup => sample.setup_old.php}  |  185 +-
 .../xpmt/modules/sample/skeleton.setup.php         |  396 ++++
 sandbox/core-0.5/xpmt/modules/uuid/uuid.main.php   |  254 +--
 sandbox/core-0.5/xpmt/modules/uuid/uuid.php        |   10 +-
 sandbox/core-0.5/xpmt/modules/uuid/uuid.setup.php  |  513 +++++
 sandbox/core-0.5/xpmt/phpConsole.php               |  666 +++---
 sandbox/core-0.5/xpmt/pmt-functions.php            |   46 +-
 sandbox/core-0.5/xpmt/pmt.php                      |  281 ++-
 sandbox/core-0.5/xpmt/themes/default/main.php      |   34 +-
 sandbox/core-0.5/xpmt/themes/default/main_old.php  |  103 +
 .../layout/themes/ie/Trac 0.13 Demo Project.url    |    2 +
 sandbox/layout/themes/ie/pmt.htm                   |   71 +
 sandbox/layout/themes/ie/pmt/logo.png              |  Bin 0 -> 6108 bytes
 sandbox/layout/themes/ie/pmt/proj.css              |  151 ++
 sandbox/layout/themes/ie/pmt/skin.css              |  336 +++
 sandbox/layout/themes/ie/trac.css                  |  849 ++++++++
 sandbox/layout/themes/ie/trac.htm                  |  171 ++
 sandbox/layout/themes/ie/wiki.css                  |  133 ++
 sandbox/{debug => lib_debug}/debug.php             |    0
 sandbox/{debug => lib_debug}/phpConsole.php        |    0
 sandbox/{debug => lib_debug}/test_db.php           |    0
 sandbox/lib_timeline/timeline_libraries_v2.3.0.zip |  Bin 0 -> 165264 bytes
 .../lib_timeline/timeline_local_example_1.0.zip    |  Bin 0 -> 5330 bytes
 sandbox/lib_timeline/timeline_source_v2.3.0.zip    |  Bin 0 -> 4621200 bytes
 sandbox/mod_ticket/sample/inquiry.xml              |  458 ++++
 sandbox/mod_ticket/sample/inquiry.xslt             |  329 +++
 sandbox/mod_wiki/engines/Mediawiki2HTML.url        |    2 +
 .../mod_wiki/engines/Text_Wiki_Mediawiki-0.2.0.tgz |  Bin 0 -> 17022 bytes
 sandbox/mod_wiki/engines/Text_Wiki_Mediawiki.url   |    2 +
 sandbox/mod_wiki/engines/Wiky.php.url              |    2 +
 sandbox/mod_wiki/engines/Wiky.php_2013-0703.zip    |  Bin 0 -> 5394 bytes
 sandbox/mod_wiki/engines/d2g-WikiParser.url        |    2 +
 sandbox/mod_wiki/engines/wiki2html_machine.zip     |  Bin 0 -> 2813 bytes
 sandbox/modules/kb-design-notes.php                |   22 +
 sandbox/netbeans-ticket-plugin/readme.txt          |    8 +
 sandbox/netbeans-ticket-plugin/sample/.gitignore   |    2 +
 .../sample/netbeans-mantis-integration_v0.4.zip    |  Bin 0 -> 115974 bytes
 .../netbeans-ticket-plugin/sample/plugin-cert.txt  |   36 +
 .../sample/plugin-readme.txt                       |  223 ++
 sandbox/netbeans-ticket-plugin/src/.gitignore      |    1 +
 sandbox/uuid-generator/code.txt                    |   31 -
 sandbox/xdebug/php_xdebug-2.2.1-5.4-vc9-x86_64.dll |  Bin 0 -> 198656 bytes
 src-mobile/xenoPMT-Mobile.txt                      |   20 +
 src/index.php                                      |    1 +
 src/install/index.php                              |    9 +-
 src/install/pmt-db-ticket.sql                      |   13 +-
 src/xpmt/modules/admin/admin.php                   |   35 +
 src/xpmt/modules/kb/kb-main.xsl                    |   47 +
 src/xpmt/modules/kb/kb-view.xsl                    |   35 +-
 src/xpmt/modules/ticket/v1.0/ext/ticket.view.php   |    2 +-
 src/xpmt/phpConsole_1.1.php                        |  343 +++
 290 files changed, 30168 insertions(+), 1659 deletions(-)
 create mode 100644 doc/The 10 Traits of Highly Effective Project Milestones - PM Hut.url
 rename doc/database/{ => 0.0.2}/data-model.mwb (100%)
 create mode 100644 doc/database/0.0.2/data-model.mwb.bak
 rename doc/database/{ => 0.0.2}/pmt-db-customer.sql (100%)
 rename doc/database/{ => 0.0.2}/pmt-db-kb.sql (100%)
 rename doc/database/{ => 0.0.2}/pmt-db-product.sql (100%)
 rename doc/database/{ => 0.0.2}/pmt-db-project.sql (100%)
 rename doc/database/{ => 0.0.2}/pmt-db-ticket.sql (100%)
 rename doc/database/{ => 0.0.2}/pmt-db-user.sql (100%)
 rename doc/database/{ => 0.0.2}/pmt-db.sql (100%)
 create mode 100644 doc/database/0.0.5/data-model-rev2.mwb
 create mode 100644 doc/database/0.0.5/data-model-rev2.mwb.bak
 create mode 100644 doc/database/0.0.5/data-model.mwb
 create mode 100644 doc/database/0.0.5/pmt-db-customer.sql
 create mode 100644 doc/database/0.0.5/pmt-db-kb.sql
 create mode 100644 doc/database/0.0.5/pmt-db-product.sql
 create mode 100644 doc/database/0.0.5/pmt-db-project.sql
 create mode 100644 doc/database/0.0.5/pmt-db-ticket.sql
 create mode 100644 doc/database/0.0.5/pmt-db-user.sql
 create mode 100644 doc/database/0.0.5/pmt-db.sql
 create mode 100644 doc/database/db-004.docx
 create mode 100644 doc/database/db-005.docx
 create mode 100644 doc/database/readme.txt
 create mode 100644 doc/devnotes/readme.txt
 create mode 100644 doc/modules/module-list.txt
 create mode 100644 doc/modules/readme.txt
 create mode 100644 doc/modules/search/readme.txt
 create mode 100644 doc/todo-feature-samples/Mobile Navigation Design & Tutorial.url
 create mode 100644 doc/todo-feature-samples/Unsemantic CSS Framework.url
 create mode 100644 doc/todo-feature-samples/bug-skin - css tabs.png
 create mode 100644 doc/todo-feature-samples/competition.txt
 create mode 100644 doc/todo-feature-samples/jQuery/OmniWindow - jQuery Plugin Registry.url
 create mode 100644 doc/todo-feature-samples/jQuery/hotkey/Mousetrap - Keyboard shortcuts in Javascript.url
 create mode 100644 doc/todo-feature-samples/jQuery/hotkey/jQuery Hotkeys - jQuery Plugin Registry.url
 create mode 100644 doc/todo-feature-samples/jQuery/hotkey/php - How to implement keyboard shortcuts on websites - Stack Overflow.url
 create mode 100644 doc/todo-feature-samples/jQuery/hotkey/php - jQuery -hot key combinations - Stack Overflow.url
 create mode 100644 doc/todo-feature-samples/login-screen.png
 create mode 100644 doc/todo-feature-samples/simile-widgets - Data Visualization Widgets.url
 rename doc/{Samples => todo-feature-samples}/welcome-email.htm (100%)
 create mode 100644 sandbox/_feature-review/LygoEdit-jQueryWYSIWYG.url
 create mode 100644 sandbox/_feature-review/html2fpdf-3.0.2b.zip
 create mode 100644 sandbox/core-0.4/.htaccess
 create mode 100644 sandbox/core-0.4/bak.htaccess
 create mode 100644 sandbox/core-0.4/index-old.php
 create mode 100644 sandbox/core-0.4/index-test.php
 create mode 100644 sandbox/core-0.4/index.php
 create mode 100644 sandbox/core-0.4/install/index.php
 create mode 100644 sandbox/core-0.4/install/installer.php
 create mode 100644 sandbox/core-0.4/install/installer2.php
 create mode 100644 sandbox/core-0.4/install/pmt-db-customer.sql
 create mode 100644 sandbox/core-0.4/install/pmt-db-kb.sql
 create mode 100644 sandbox/core-0.4/install/pmt-db-product.sql
 create mode 100644 sandbox/core-0.4/install/pmt-db-project.sql
 create mode 100644 sandbox/core-0.4/install/pmt-db-ticket.sql
 create mode 100644 sandbox/core-0.4/install/pmt-db-user.sql
 create mode 100644 sandbox/core-0.4/install/pmt-db.sql
 create mode 100644 sandbox/core-0.4/install/pmt.css
 create mode 100644 sandbox/core-0.4/install/sql-templates/db-inventory.sql
 create mode 100644 sandbox/core-0.4/install/sql-templates/db-main-old.sql
 create mode 100644 sandbox/core-0.4/install/sql-templates/db-main.sql
 create mode 100644 sandbox/core-0.4/license.txt
 create mode 100644 sandbox/core-0.4/updates/db-main.sql
 create mode 100644 sandbox/core-0.4/updates/index.php
 create mode 100644 sandbox/core-0.4/updates/index2.php
 create mode 100644 sandbox/core-0.4/updates/pmt-main.php
 create mode 100644 sandbox/core-0.4/updates/pmtSQL.php
 create mode 100644 sandbox/core-0.4/updates/readme.txt
 create mode 100644 sandbox/core-0.4/updates/test-auto-download-file.txt
 create mode 100644 sandbox/core-0.4/updates/xr1.php
 create mode 100644 sandbox/core-0.4/updates/xr1.xml
 rename sandbox/{core-0.5/config.php.txt => core-0.4/xpmt/config.default.php} (50%)
 create mode 100644 sandbox/core-0.4/xpmt/core/pmt.db.php
 create mode 100644 sandbox/core-0.4/xpmt/core/pmt.i.module.php
 create mode 100644 sandbox/core-0.4/xpmt/core/pmt.log.php
 create mode 100644 sandbox/core-0.4/xpmt/core/pmt.member.php
 create mode 100644 sandbox/core-0.4/xpmt/core/pmt.module.php
 create mode 100644 sandbox/core-0.4/xpmt/core/pmt.uri.php
 create mode 100644 sandbox/core-0.4/xpmt/debug.php
 create mode 100644 sandbox/core-0.4/xpmt/http.php
 create mode 100644 sandbox/core-0.4/xpmt/libraries/jquery/jquery.min.js
 create mode 100644 sandbox/core-0.4/xpmt/libraries/markitup/jquery.markitup.js
 create mode 100644 sandbox/core-0.4/xpmt/libraries/markitup/sets/default/images/bold.png
 create mode 100644 sandbox/core-0.4/xpmt/libraries/markitup/sets/default/images/clean.png
 create mode 100644 sandbox/core-0.4/xpmt/libraries/markitup/sets/default/images/image.png
 create mode 100644 sandbox/core-0.4/xpmt/libraries/markitup/sets/default/images/italic.png
 create mode 100644 sandbox/core-0.4/xpmt/libraries/markitup/sets/default/images/link.png
 create mode 100644 sandbox/core-0.4/xpmt/libraries/markitup/sets/default/images/list-bullet.png
 create mode 100644 sandbox/core-0.4/xpmt/libraries/markitup/sets/default/images/list-numeric.png
 create mode 100644 sandbox/core-0.4/xpmt/libraries/markitup/sets/default/images/picture.png
 create mode 100644 sandbox/core-0.4/xpmt/libraries/markitup/sets/default/images/preview.png
 create mode 100644 sandbox/core-0.4/xpmt/libraries/markitup/sets/default/images/stroke.png
 create mode 100644 sandbox/core-0.4/xpmt/libraries/markitup/sets/default/set.js
 create mode 100644 sandbox/core-0.4/xpmt/libraries/markitup/sets/default/style.css
 create mode 100644 sandbox/core-0.4/xpmt/libraries/markitup/skins/markitup/images/bg-container.png
 create mode 100644 sandbox/core-0.4/xpmt/libraries/markitup/skins/markitup/images/bg-editor-bbcode.png
 create mode 100644 sandbox/core-0.4/xpmt/libraries/markitup/skins/markitup/images/bg-editor-dotclear.png
 create mode 100644 sandbox/core-0.4/xpmt/libraries/markitup/skins/markitup/images/bg-editor-html.png
 create mode 100644 sandbox/core-0.4/xpmt/libraries/markitup/skins/markitup/images/bg-editor-json.png
 create mode 100644 sandbox/core-0.4/xpmt/libraries/markitup/skins/markitup/images/bg-editor-markdown.png
 create mode 100644 sandbox/core-0.4/xpmt/libraries/markitup/skins/markitup/images/bg-editor-textile.png
 create mode 100644 sandbox/core-0.4/xpmt/libraries/markitup/skins/markitup/images/bg-editor-wiki.png
 create mode 100644 sandbox/core-0.4/xpmt/libraries/markitup/skins/markitup/images/bg-editor-xml.png
 create mode 100644 sandbox/core-0.4/xpmt/libraries/markitup/skins/markitup/images/bg-editor.png
 create mode 100644 sandbox/core-0.4/xpmt/libraries/markitup/skins/markitup/images/handle.png
 create mode 100644 sandbox/core-0.4/xpmt/libraries/markitup/skins/markitup/images/menu.png
 create mode 100644 sandbox/core-0.4/xpmt/libraries/markitup/skins/markitup/images/submenu.png
 create mode 100644 sandbox/core-0.4/xpmt/libraries/markitup/skins/markitup/style.css
 create mode 100644 sandbox/core-0.4/xpmt/libraries/markitup/skins/simple/images/handle.png
 create mode 100644 sandbox/core-0.4/xpmt/libraries/markitup/skins/simple/images/menu.png
 create mode 100644 sandbox/core-0.4/xpmt/libraries/markitup/skins/simple/images/submenu.png
 create mode 100644 sandbox/core-0.4/xpmt/libraries/markitup/skins/simple/style.css
 create mode 100644 sandbox/core-0.4/xpmt/libraries/markitup/templates/preview.css
 create mode 100644 sandbox/core-0.4/xpmt/libraries/markitup/templates/preview.html
 create mode 100644 sandbox/core-0.4/xpmt/modcontroller.php
 create mode 100644 sandbox/core-0.4/xpmt/modules/.readme.txt
 create mode 100644 sandbox/core-0.4/xpmt/modules/ProjectTicket.php
 rename {src => sandbox/core-0.4}/xpmt/modules/admin/Admin.php (100%)
 create mode 100644 sandbox/core-0.4/xpmt/modules/dashboard/dashboard.php
 create mode 100644 sandbox/core-0.4/xpmt/modules/kb/kb-edit.php
 create mode 100644 sandbox/core-0.4/xpmt/modules/kb/kb-list.php
 create mode 100644 sandbox/core-0.4/xpmt/modules/kb/kb-main.php
 create mode 100644 sandbox/core-0.4/xpmt/modules/kb/kb-new.php
 create mode 100644 sandbox/core-0.4/xpmt/modules/kb/kb-view.php
 create mode 100644 sandbox/core-0.4/xpmt/modules/kb/kb-view.xsl
 create mode 100644 sandbox/core-0.4/xpmt/modules/kb/kb.php
 create mode 100644 sandbox/core-0.4/xpmt/modules/kb/v0.4/kb.php
 create mode 100644 sandbox/core-0.4/xpmt/modules/old-unused/project_0.2.3.php
 create mode 100644 sandbox/core-0.4/xpmt/modules/product/product.main.php
 create mode 100644 sandbox/core-0.4/xpmt/modules/product/product.php
 create mode 100644 sandbox/core-0.4/xpmt/modules/project/ext/.readme.txt
 create mode 100644 sandbox/core-0.4/xpmt/modules/project/ext/project.ext.new.php
 create mode 100644 sandbox/core-0.4/xpmt/modules/project/ext/project.ext.view.php
 create mode 100644 sandbox/core-0.4/xpmt/modules/project/ext/project.ext.wiki.php
 create mode 100644 sandbox/core-0.4/xpmt/modules/project/ext/project.rss.php
 create mode 100644 sandbox/core-0.4/xpmt/modules/project/project.php
 create mode 100644 sandbox/core-0.4/xpmt/modules/sample/sample.php
 create mode 100644 sandbox/core-0.4/xpmt/modules/svn/.readme.txt
 create mode 100644 sandbox/core-0.4/xpmt/modules/svn/subversion.php
 create mode 100644 sandbox/core-0.4/xpmt/modules/ticket/.readme.txt
 create mode 100644 sandbox/core-0.4/xpmt/modules/ticket/ext/ticket-main.php
 create mode 100644 sandbox/core-0.4/xpmt/modules/ticket/ext/ticket-view.xsl
 create mode 100644 sandbox/core-0.4/xpmt/modules/ticket/ext/ticket.extras.php
 create mode 100644 sandbox/core-0.4/xpmt/modules/ticket/sample-ticket-view.htm
 create mode 100644 sandbox/core-0.4/xpmt/modules/ticket/ticket.php
 create mode 100644 sandbox/core-0.4/xpmt/modules/ticket/v1.0/.readme.txt
 create mode 100644 sandbox/core-0.4/xpmt/modules/ticket/v1.0/ext/db-ticket_install.sql
 create mode 100644 sandbox/core-0.4/xpmt/modules/ticket/v1.0/ext/db-ticket_uninstall.sql
 create mode 100644 sandbox/core-0.4/xpmt/modules/ticket/v1.0/ext/ticket.view.php
 create mode 100644 sandbox/core-0.4/xpmt/modules/ticket/v1.0/ticket.main.php
 create mode 100644 sandbox/core-0.4/xpmt/modules/ticket/v1.0/ticket.php
 create mode 100644 sandbox/core-0.4/xpmt/modules/ticket/v1.0/ticket.setup.php
 create mode 100644 sandbox/core-0.4/xpmt/modules/user.php
 create mode 100644 sandbox/core-0.4/xpmt/modules/uuid/uuid.main.php
 create mode 100644 sandbox/core-0.4/xpmt/modules/uuid/uuid.php
 create mode 100644 sandbox/core-0.4/xpmt/modules/wiki/.readme.txt
 create mode 100644 sandbox/core-0.4/xpmt/phpConsole.php
 create mode 100644 sandbox/core-0.4/xpmt/pmt-functions.php
 create mode 100644 sandbox/core-0.4/xpmt/pmt.php
 create mode 100644 sandbox/core-0.4/xpmt/security.php
 create mode 100644 sandbox/core-0.4/xpmt/themes/default/dashboard.txt
 create mode 100644 sandbox/core-0.4/xpmt/themes/default/gfx/logo-footer.png
 create mode 100644 sandbox/core-0.4/xpmt/themes/default/gfx/logo-mck.jpg
 create mode 100644 sandbox/core-0.4/xpmt/themes/default/gfx/logo.png
 create mode 100644 sandbox/core-0.4/xpmt/themes/default/gfx/proj-action-fade.png
 create mode 100644 sandbox/core-0.4/xpmt/themes/default/gfx/proj-default-icon.png
 create mode 100644 sandbox/core-0.4/xpmt/themes/default/gfx/proj-tbt.png
 create mode 100644 sandbox/core-0.4/xpmt/themes/default/gfx/topbar_gradient.png
 create mode 100644 sandbox/core-0.4/xpmt/themes/default/gfx/topbar_gradient2.png
 create mode 100644 sandbox/core-0.4/xpmt/themes/default/gfx/xeno-lg.png
 create mode 100644 sandbox/core-0.4/xpmt/themes/default/gfx/xeno-sm.png
 create mode 100644 sandbox/core-0.4/xpmt/themes/default/index.php
 create mode 100644 sandbox/core-0.4/xpmt/themes/default/kb.css
 create mode 100644 sandbox/core-0.4/xpmt/themes/default/kb.php
 create mode 100644 sandbox/core-0.4/xpmt/themes/default/main.htm
 create mode 100644 sandbox/core-0.4/xpmt/themes/default/main.php
 create mode 100644 sandbox/core-0.4/xpmt/themes/default/proj.css
 create mode 100644 sandbox/core-0.4/xpmt/themes/default/projects.php
 create mode 100644 sandbox/core-0.4/xpmt/themes/default/skin.css
 create mode 100644 sandbox/core-0.4/xpmt/themes/trac13/index.php
 create mode 100644 sandbox/core-0.5/config.sample.txt
 create mode 100644 sandbox/core-0.5/xpmt/core/xenopmt.php
 create mode 100644 sandbox/core-0.5/xpmt/core/xpmt.i.setup.php
 create mode 100644 sandbox/core-0.5/xpmt/core2/Error.php
 create mode 100644 sandbox/core-0.5/xpmt/core2/Functions.php
 create mode 100644 sandbox/core-0.5/xpmt/core2/Library.php
 create mode 100644 sandbox/core-0.5/xpmt/core2/Module.php
 create mode 100644 sandbox/core-0.5/xpmt/core2/Properties/ModuleInfo.php
 create mode 100644 sandbox/core-0.5/xpmt/core2/Properties/ModuleSetupError.php
 create mode 100644 sandbox/core-0.5/xpmt/core2/Properties/Page.php
 create mode 100644 sandbox/core-0.5/xpmt/core2/Setup.php
 create mode 100644 sandbox/core-0.5/xpmt/core2/misc/Struct.php
 create mode 100644 sandbox/core-0.5/xpmt/core2/test/clsAbstractProp.php
 create mode 100644 sandbox/core-0.5/xpmt/core2/test/clsGetSet.php
 create mode 100644 sandbox/core-0.5/xpmt/core2/xpmtPage.php
 create mode 100644 sandbox/core-0.5/xpmt/libraries/PhpConsole/Auth.php
 create mode 100644 sandbox/core-0.5/xpmt/libraries/PhpConsole/Connector.php
 create mode 100644 sandbox/core-0.5/xpmt/libraries/PhpConsole/Dispatcher.php
 create mode 100644 sandbox/core-0.5/xpmt/libraries/PhpConsole/Dispatcher/Debug.php
 create mode 100644 sandbox/core-0.5/xpmt/libraries/PhpConsole/Dispatcher/Errors.php
 create mode 100644 sandbox/core-0.5/xpmt/libraries/PhpConsole/Dispatcher/Evaluate.php
 create mode 100644 sandbox/core-0.5/xpmt/libraries/PhpConsole/Dumper.php
 create mode 100644 sandbox/core-0.5/xpmt/libraries/PhpConsole/EvalProvider.php
 create mode 100644 sandbox/core-0.5/xpmt/libraries/PhpConsole/Handler.php
 create mode 100644 sandbox/core-0.5/xpmt/libraries/PhpConsole/Helper.php
 create mode 100644 sandbox/core-0.5/xpmt/libraries/PhpConsole/OldVersionAdapter.php
 create mode 100644 sandbox/core-0.5/xpmt/libraries/PhpConsole/PsrLogger.php
 create mode 100644 sandbox/core-0.5/xpmt/libraries/PhpConsole/__autoload.php
 create mode 100644 sandbox/core-0.5/xpmt/libraries/timeline/readme.txt
 create mode 100644 sandbox/core-0.5/xpmt/libraries/wiki/readme.txt
 create mode 100644 sandbox/core-0.5/xpmt/modules/admin/admin.main.php
 create mode 100644 sandbox/core-0.5/xpmt/modules/admin/admin.module.php
 create mode 100644 sandbox/core-0.5/xpmt/modules/admin/admin.php
 create mode 100644 sandbox/core-0.5/xpmt/modules/admin/admin.setup.php
 create mode 100644 sandbox/core-0.5/xpmt/modules/admin/admin.user.php
 create mode 100644 sandbox/core-0.5/xpmt/modules/admin/ext/general-files.html
 create mode 100644 sandbox/core-0.5/xpmt/modules/dashboard/dashboard.main.php
 create mode 100644 sandbox/core-0.5/xpmt/modules/dashboard/dashboard.php
 create mode 100644 sandbox/core-0.5/xpmt/modules/dashboard/dashboard.setup.php
 create mode 100644 sandbox/core-0.5/xpmt/modules/sample/sample.setup.php
 rename sandbox/core-0.5/xpmt/modules/sample/{sample.setup => sample.setup_old.php} (64%)
 create mode 100644 sandbox/core-0.5/xpmt/modules/sample/skeleton.setup.php
 create mode 100644 sandbox/core-0.5/xpmt/modules/uuid/uuid.setup.php
 create mode 100644 sandbox/core-0.5/xpmt/themes/default/main_old.php
 create mode 100644 sandbox/layout/themes/ie/Trac 0.13 Demo Project.url
 create mode 100644 sandbox/layout/themes/ie/pmt.htm
 create mode 100644 sandbox/layout/themes/ie/pmt/logo.png
 create mode 100644 sandbox/layout/themes/ie/pmt/proj.css
 create mode 100644 sandbox/layout/themes/ie/pmt/skin.css
 create mode 100644 sandbox/layout/themes/ie/trac.css
 create mode 100644 sandbox/layout/themes/ie/trac.htm
 create mode 100644 sandbox/layout/themes/ie/wiki.css
 rename sandbox/{debug => lib_debug}/debug.php (100%)
 rename sandbox/{debug => lib_debug}/phpConsole.php (100%)
 rename sandbox/{debug => lib_debug}/test_db.php (100%)
 create mode 100644 sandbox/lib_timeline/timeline_libraries_v2.3.0.zip
 create mode 100644 sandbox/lib_timeline/timeline_local_example_1.0.zip
 create mode 100644 sandbox/lib_timeline/timeline_source_v2.3.0.zip
 create mode 100644 sandbox/mod_ticket/sample/inquiry.xml
 create mode 100644 sandbox/mod_ticket/sample/inquiry.xslt
 create mode 100644 sandbox/mod_wiki/engines/Mediawiki2HTML.url
 create mode 100644 sandbox/mod_wiki/engines/Text_Wiki_Mediawiki-0.2.0.tgz
 create mode 100644 sandbox/mod_wiki/engines/Text_Wiki_Mediawiki.url
 create mode 100644 sandbox/mod_wiki/engines/Wiky.php.url
 create mode 100644 sandbox/mod_wiki/engines/Wiky.php_2013-0703.zip
 create mode 100644 sandbox/mod_wiki/engines/d2g-WikiParser.url
 create mode 100644 sandbox/mod_wiki/engines/wiki2html_machine.zip
 create mode 100644 sandbox/modules/kb-design-notes.php
 create mode 100644 sandbox/netbeans-ticket-plugin/readme.txt
 create mode 100644 sandbox/netbeans-ticket-plugin/sample/.gitignore
 create mode 100644 sandbox/netbeans-ticket-plugin/sample/netbeans-mantis-integration_v0.4.zip
 create mode 100644 sandbox/netbeans-ticket-plugin/sample/plugin-cert.txt
 create mode 100644 sandbox/netbeans-ticket-plugin/sample/plugin-readme.txt
 create mode 100644 sandbox/netbeans-ticket-plugin/src/.gitignore
 delete mode 100644 sandbox/uuid-generator/code.txt
 create mode 100644 sandbox/xdebug/php_xdebug-2.2.1-5.4-vc9-x86_64.dll
 create mode 100644 src-mobile/xenoPMT-Mobile.txt
 create mode 100644 src/xpmt/modules/admin/admin.php
 create mode 100644 src/xpmt/modules/kb/kb-main.xsl
 create mode 100644 src/xpmt/phpConsole_1.1.php
PS C:\work\xi\xenoPMT>
```
