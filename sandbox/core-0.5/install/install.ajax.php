<?php
/** * *********************************************************
 * Copyright 2012 (C) Xeno Innovations, Inc.
 * ALL RIGHTS RESERVED
 * @Author:      Damian Suess
 * Document:     install.ajax.php
 * Created Date: Oct 9, 2012
 *
 * Description:
 *  Event handlers from Ajax/Jquery calls
 *
 * Change Log:
 *
 */

if(isset($_POST["updateStep"]))
{

  $value = $_POST["updateStep"]+1;
  echo json_encode(array("returnValue" => "$value"));
}
else
{
  $value = "99";
  echo json_encode(array("returnValue" => "$value"));
}
?>
