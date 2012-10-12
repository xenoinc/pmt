/** * *********************************************************
 * Copyright 2012 (C) Xeno Innovations, Inc.
 * ALL RIGHTS RESERVED
 * @Author:      Damian Suess
 * Document:     installer.js
 * Created Date: Oct 9, 2012
 *
 * Description:
 *  Installer (v0.0.5)
 *  JQuery handler
 *
 * Change Log:
 *  2012-1005 + Initial creation
 */

// Move this to install.js
$(document).ready(function() {

  /**[ Start visible ]****************************** */
  $('#imgSpinner').show();

  $('#step1').show();
  $("#step2").hide();
  $("#step3").hide();
  $("#step4").hide();
  $("#step5").hide();
  $("#step6").hide();
  $("#step7").hide();


  /**[ Event Handlers ]************************ */

  // List index changed
  $("#lstStep").change(function() {
    // alert($(this).val());
  });

  //Button Click Events
  $("button").click(function() {
    // $(this).text(); = Button Caption
    // $(".myclass).attr("class"); = String Value
    // $("").hasClass("divhover"); = BOOL_VAL

    var num;

    if ($(this).hasClass("btnNext"))
    { // alert("Tell PHP +1");
      num = $("#stepNdx").val();    // get Value setting
      UpdateStep(num, num+1);
    }
    else if ($(this).hasClass("btnPrev"))
    { // alert("Tell PHP -1");
      num = $("#stepNdx").val();    // get Value setting
      UpdateStep(num, num-1);
    }
    else
    {
      // Handle all other button events
      switch($(this).attr("id"))
      {
        // Test Database Connection
        case "btnDbTestConn":
          //alert("test conn!");
          DbTestConnection();
        break;

        default:
          alert("unknown button");
          break;
      }
    }
  });



  /**[ Functions ]****************************** */
  $(function() {

    $.ChangePanel = function(old, next) {
      $("#step"+old).hide("fast");
      $("#step"+next).show("normal");
    };

  });

  /* ** Hide/Show Toggle Test ***
   *
  $('#TestSpinner').click(function() {
    $("#imgSpinner").toggle();
  });

  Animate hiding
  $('#TestSpinner').click(function() {
    $('#imgSpinner').hide('slow', function() {
      alert('animation done!');
    });
  });
   */

  /* ** Call PHP Functions **
    // Call php function directly
    $.ajax({url: "install.ajax.php?arg1=value1&arg2=anotherval"});

    // Call PHP via POST and passing values
    $.post(
      "phpscript.php",
      { name: "testUser", user_Id: 1234 },  // Data to send
      function(data) {                      // Function to handle returned info
        $("body".append(data);
      });
   */

});

/*
 * update system with install step we're up (list box and display)
 */
function UpdateStep(ndxCurrent, ndxMove)
{
  var MAX_STEPS=7;  //(1-7)
  // 1. Get current step from #stepNdx=ndxCurrent
  if(ndxMove> (MAX_STEPS))
  {

  }

  var iStep = 1;  // Go to this step

  //$("#lstStep").val(3);

  //
  $.post(
    "install.ajax.php",
    {UpdateStep: iStep},              // post data ($_POST["updateStep"] = iStep)
    function(data) {
      //alert("pre update step");
      $("#stepNdx").html(data.returnValue);
      //alert("pre update disp");
      $("debugStep").html(data.returnValue);
      //alert("done with funciton");
    }
    ,"json");
}

/**
 * [Step 3] Test Database Connection
 */
function DbTestConnection()
{
  // 1) Get info params
  var dbHost     = $("#txtDbServer").val();
  var dbDatabase = $("#txtDbName").val();
  var dbPrefix   = $("#txtDbPrefix").val();
  var dbUser     = $("#txtDbUser").val();
  var dbPass     = $("#txtDbPass").val();
  alert(dbHost);

  // 2) Call PHP to test connection
  $.ajax({
    type: "POST",
    url:  "install.ajax.php",
    data: {step3: "1", db_host: "**"+dbHost },
    beforeSend: function() {
      $("#spnDbConnectionTest").html("<img src='pix/spinner.gif' />");  // loading img during request
    },
    success: function(data) { // html = server response code
      $("#spnDbConnectionTest").html(data.dbTestRet + "!");
      $("#spnDbConnectionTest").addClass(data.dbTestRetClass)
      // $("#spnDbConnectionTest").remove();    // remove the div
    }

  });

  /*
  $.post(
    "install.ajax.php",
    {DbTestConnection: str},
    function(data) {
      $("#spnDbConnectionTest").html(data.dbTestRet);
      $("$spnDbConnectionTest").addClass(data.dbTestRetClass);
    }
    ,"json");
    */
}