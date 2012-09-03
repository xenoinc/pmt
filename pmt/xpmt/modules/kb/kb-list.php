<?php
/* * **********************************************************
 * Copyright 2012 (C) Xeno Innovations, Inc.
 * ALL RIGHTS RESERVED
 * Author:       Damian Suess
 * Document:     kb-view.php
 * Created Date: Jun 21, 2012
 *
 * Description:
 *  View Knowledge Base article
 *
 * Change Log:
 *  2012-0903 * Created
 */

namespace xenoPMT\Module\KB
class ListItems {
//put your code here

  public function HasPermission()
  {
  }

  public function ListArticles()
  {
    $q = <<<QUERY
 SELECT * FROM {$dbPrefix}KB_ARTICLE WHERE `Article_Id` = {$kbId} LIMIT 1;
QUERY;
    $q = "";
  }
}
?>
