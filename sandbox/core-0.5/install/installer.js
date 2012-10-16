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

  $('#step1').show(); $("#tblItem1").css("font-weight", "bold");
  $("#step2").hide(); $("#tblItem2").css("font-weight", "normal");
  $("#step3").hide(); $("#tblItem3").css("font-weight", "normal");
  $("#step4").hide(); $("#tblItem4").css("font-weight", "normal");
  $("#step5").hide(); $("#tblItem5").css("font-weight", "normal");
  $("#step6").hide(); $("#tblItem6").css("font-weight", "normal");
  $("#step7").hide(); $("#tblItem7").css("font-weight", "normal");

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

    var num = new Number;
    var iNdxGoTo = new Number;

    if ($(this).hasClass("btnNext"))
    {
      // Sometimes it appends 1+1 = "11" not=2.. stupid javascript
      num = new Number($("#stepNdx").val());    // get Value setting
      //alert("next: " + num);
      iNdxGoTo = num+1;
      UpdateStep(num, iNdxGoTo);
    }
    else if ($(this).hasClass("btnPrev"))
    { // alert("Tell PHP -1");
      num = new Number($("#stepNdx").val());    // get Value setting
      //alert("prev: " + num);
      iNdxGoTo = num-1;
      UpdateStep(num, iNdxGoTo);
    }
    else
    {
      // Handle all other button events
      switch($(this).attr("id"))
      {
        // Test Database Connection
        case "btnDbTestConn":
          DbTestConnection();
        break;

        // Install database
        case "btnInstallDb":
          DbInstall();
        break;

        default:
          alert("Unknown button pressed!");
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
  var MAX_STEPS=7;  // (1-7)
  var iStep = 0;    // Go to this step

  /* 1. Get current step from #stepNdx=ndxCurrent */
  //alert("Move To: " + ndxMove + "\n Max: " + MAX_STEPS);
  // We're doing this incase we allow jumping of steps later
  if(ndxMove > MAX_STEPS)  {
    iStep = ndxCurrent;
    //alert("US.ndxMove=OOB_MAX");
  } else if (ndxMove < 1) {
    iStep = ndxCurrent;       // ndxMove = 1;
    //alert("US.ndxMove=OOB_low");
  } else {
    iStep = ndxMove;
    //alert("US.ndxMove=good");
  }

  //alert("US.iStep: " + iStep);
  //alert("#tblItem" + iStep);
  //$("#lstStep").val(3);

  // Step 2) Switch item to bold
  $("#tblItem" + ndxCurrent).css("font-weight", "normal");
  $("#tblItem" + iStep).css("font-weight", "bold");
  //$("#tblItem3").css("font-weight", "Bold");

  //$("#tblItem" + iStep).hide();

  // Step 3) Update PHP - Not really needed
  //Actually we dont even need PHP!
  $.post(
    "install.ajax.php",
    {UpdateStep: iStep},              // post data ($_POST["updateStep"] = iStep)
    function(data) {
      $("#stepNdx").val(data.returnValue);
      $("debugStep").html(data.returnValue);
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

  // Clear old rules so we properly display spinner
  $("#spnDbConnectionTest").removeClass();
  //
  //alert(dbHost+"\n"+dbDatabase+"\n"+dbPrefix+"\n"+dbUser+"\n"+dbPass);

  // 2) Call PHP to test connection
  $.ajax({
    type: "POST",
    url:  "install.ajax.php",
    cache: false,
    dataType: "json",
    data: {step3: "1",
            db_host: dbHost,
            db_name: dbDatabase,
            db_pref: dbPrefix,
            db_user: dbUser,
            db_pass: dbPass
          },
    beforeSend: function() {
      $("#spnDbConnectionTest").html("<img src='pix/spinner.gif' />");  // loading img during request
    },
    success: function(data)
    {
      $("#spnDbConnectionTest").html(data.dbRet_msg);
      $("#spnDbConnectionTest").removeClass();            // Remove all previous classes to reload load new update
      $("#spnDbConnectionTest").addClass(data.dbRet_class)
      // $("#spnDbConnectionTest").remove();    // remove the div
    }

  }).error(function() {
    alert("DbTestConnection Error");
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

/**
 * Calls PHP to install the database
 */
function DbInstall()
{
  $.ajax({
    type: "POST",
    url:  "install.ajax.php",
    cache: false,
    dataType: "json",
    data: { step4: "1",
            db_host: dbHost,
            db_name: dbDatabase,
            db_pref: dbPrefix,
            db_user: dbUser,
            db_pass: dbPass
          },
    beforeSend: function() {

    },
    success: function(data) {

    }
  });
}