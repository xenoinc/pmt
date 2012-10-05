<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Register</title>

<script src="jquery.js" type="text/javascript" language="javascript"></script>

<script language="javascript" type="text/javascript">
$(document).ready(function()
{
	// using the ID="username" from the form
	// we tell the script once the user has tabbed
	// out of the textfield or click onto the
	// next field we want to run the script
	// if we wish we could change this to:
	//
	// $("#username").keypress(function()
	//
	// each time the user types it will do a call for the
	// script to run,
	$("#username").blur(function()
	{
		// Next we look for our message box span from the page
		// remove all its classes, css, and styling,
		// then we assign it a new class from our css
		// and provide it with Text, and tell it to fadein on
		// a slow setting..
		$("#msgbox").removeClass().addClass('messagebox').text('Checking.....').fadeIn("slow");

		// in this file we have our database connection string
		// out SQL statement we wish to execute and what data
		// we wish to have returned back to us for use int he script
		//
		// we use the get method to post to the page
		// much the same as using a form or url link
		// checkusername.php?uname='value'
		$.get("checkusername.php",{ uname:$(this).val() } ,function(data)
        {

		  // We check the Returned Data,
		  // if it matches our refference
		  // in this case 0, then it must mean there
		  // is is a username in the database with that
		  // name, and its not safe to use.

		  if(data==0) //if username is there
		  {
		  	// if this is the case then we can
			// show our message box to the user telling
			// them that the username was found and we cant use it.

		  	$("#msgbox").fadeTo(200,0.1,function() //start fading the messagebox
			{
			  // with the message box set and fading in we set our text which
			  // will apear in the span tag,
			  $(this).html('This Username Already exists...').addClass('messageboxerror').fadeTo(900,1);
			});
          }
		  else // if the username is not there
		  {
		  	$("#msgbox").fadeTo(200,0.1,function()  //start fading the messagebox
			{
			  //add message and change the class of the box and start fading
			  $(this).html('This Username is NOT in the database...').addClass('messageboxok').fadeTo(900,1);
			});
		  }

        });

	});
});
</script>
<style type="text/css">
/*
Our CSS for the message box
*/
.messagebox{
	width:250px;
	margin-left:30px;
	border:1px solid #0064A1;
	background:#5F81D7;
	padding:3px;
	color:#ffffff;
}
.messageboxok{
	width:250px;
	margin-left:30px;
	border:1px solid #000000;
	background:#0064A1;
	padding:3px;
	font-weight:bold;
	color:#ffffff;

}
.messageboxerror{
	width:250px;
	margin-left:30px;
	border:1px solid #ff0000;
	background:#ffff33;
	padding:3px;
	font-weight:bold;
	color:#ff0000;
}

</style>
</head>
<body>
  <span id="msgbox" style="display:none"></span>
  <br />
  <form id="frmReg" name="frmReg" method="post" action="register.php">
    username: <input name="username" id="username" type="text" />
    <br />
    password:
    <input name="password" id="password" type="text" />
    <br />
    <input type="submit" id="button" value="register" />
  </form>
</body>
</html>
