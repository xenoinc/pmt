<?php

/** * *********************************************************
 * Copyright 2012 (C) Xeno Innovations, Inc.
 * ALL RIGHTS RESERVED
 * @Author:      Damian Suess
 * Document:     product
 * Created Date: Sep 26, 2012
 *
 * Description:
 *
 *
 * Change Log:
 *
 */

/**
 * Module Permissions
 */
class ENUM_ProductPermission
{
  //const Admin       = "admin";        // Access the admin panel (useless)
  const AdminCreate = "admin_create";   // Create new products (cmd=new)    - MiniBarRight
  const AdminEdit   = "admin_edit";     // Edit product details (cmd=edit)  - MiniBarRight
  const AdminRemove = "admin_remove";   // Remove product (cmd=rmv)         - MiniBarRight

  const WikiCreate  = "wiki_create";    // Create new wiki articles
  const WikiView    = "wiki_view";      // View wiki articles
  const WikiEdit    = "wiki_edit";      // Edit wiki articles
  const WikiRemove  = "wiki_remove";    // Remove wiki articles
}

class product implements iModule {
  //put your code here

  public function Title() { return ""; }
  public function PageData() { return ""; }
  public function Toolbar() { return ""; }
  public function MiniBarLeft() { return ""; }
  public function MiniBarRight() { return ""; }

}

?>
