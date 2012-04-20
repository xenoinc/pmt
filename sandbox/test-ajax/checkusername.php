<?php
/**
The only piece of code you should touch in this file is the 
dreamweaver connection string, and the recordset dbname which 
matches your own connection string



Script to check and see if the username provided 
has already been used, if this is the case then 
show them a message using JQuery to switch css class,
connect to the database and retreive any usernames 
that match the value passed via querystring

if its a match or if any rows return then show the 
username in use message or if the rows return nothing 
then that username hasnt been used yet and can be used

*/

/////////////////////////
// START DREAMWEAVER CODE
/////////////////////////
?>

<?php 

//
// Change this to match your dreamweaver
// or other connection method
//
require_once('Connections/your_conn_page.php'); 

?>
<?php
if (!function_exists("GetSQLValueString")) {
function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
  $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;

  $theValue = function_exists("mysql_real_escape_string") ? mysql_real_escape_string($theValue) : mysql_escape_string($theValue);

  switch ($theType) {
    case "text":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;    
    case "long":
    case "int":
      $theValue = ($theValue != "") ? intval($theValue) : "NULL";
      break;
    case "double":
      $theValue = ($theValue != "") ? "'" . doubleval($theValue) . "'" : "NULL";
      break;
    case "date":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;
    case "defined":
      $theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;
      break;
  }
  return $theValue;
}
}

// Dreamweaver creats its own code to 
// collect information from the POST or GET method

$colname_rsUnameCheck = "-1";
if (isset($_GET['uname'])) {
  $colname_rsUnameCheck = $_GET['uname'];
}

// conenct to the database and 
// Run the SQL code to retreice our records

mysql_select_db($database_dbname, $dbname);
$query_rsUnameCheck = sprintf("SELECT username FROM users WHERE username=%s", GetSQLValueString($colname_rsUnameCheck, "text"));
$rsUnameCheck = mysql_query($query_rsUnameCheck, $dbname) or die(mysql_error());
$row_rsUnameCheck = mysql_fetch_assoc($rsUnameCheck);
$totalRows_rsUnameCheck = mysql_num_rows($rsUnameCheck);

?>
<?php 

// Quick iff statment to see if there was 
// any records returned from the query
// either way echo (print) our result

if(mysql_num_rows($rsUnameCheck)> 0 ){
	echo 0;
}else{
	//username already exists
	echo 1;
}
?>
<?php 
mysql_free_result($rsUnameCheck);
?>