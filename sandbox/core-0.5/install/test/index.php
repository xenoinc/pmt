<?php
  // http://api.jquery.com/hide/
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
  <head>
    <meta http-equiv="Content-type" content="text/html; charset=utf-8" />
    <title>PHP, Ajax, Jquery</title>
    <style type="text/css" media="screen">
      #display {
        padding-top: 10px;
        color: purple;
      }
    </style>
    <script src="../js/jquery-1.8.2.min.js" type="text/javascript" language="JavaScript"></script>
    <script type="text/javascript" charset="utf-8">

      $(document).ready(function() {

        // Events
        $("#txtValue").keyup(function() {
          sendValue($(this).val());
        });

      });

      function sendValue(str) {
        // $.post(  AJAX_PHP_FILE,
        //          {OBJ with Values},
        //          Function call on data return,
        //          How i want data formatted on return from server
        //       );
        $.post("install.ajax.php", {sendValue: str },
          function(data){
            $('#display').html(data.returnValue);
          }
          ,"json");
      }
    </script>
  </head>
  <body>
    <label for="txtValue">Enter a value: </label>
    <input type="text" name="txtValue" value="" id="txtValue" />
    <div id="display">
      <!-- data displayed here -->
    </div>
  </body>
</html>