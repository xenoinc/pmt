<?php

/** * *********************************************************
 * Copyright 2012 (C) Xeno Innovations, Inc.
 * ALL RIGHTS RESERVED
 * @Author:      Damian Suess
 * Document:     xi
 * Created Date: Oct 9, 2012
 *
 * Description:
 *  PHP-Jquery-Ajax text
 *
 * Change Log:
 *
 */


if (isset($_POST['sendValue'])){
  $value = $_POST['sendValue'];
  echo json_encode(array("returnValue"=>"Returned from PHP: '".$value."'"));
}else{
  $value = "";  // never called
  echo json_encode(array("returnValue"=>"blank"));
}


?>
