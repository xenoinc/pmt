/** * *********************************************************
 * Copyright 2012 (C) Xeno Innovations, Inc.
 * ALL RIGHTS RESERVED
 * @Author:      Damian Suess
 * Document:     installer.js
 * Created Date: Oct 9, 2012
 *
 * Description:
 *  Installer (v0.0.5)
 *  jQuery handler
 *
 * Change Log:
 *  2012-1005 + Initial creation
 */

// Move this to install.js
$(document).ready(function() {


  // ######################################################################
  /**[ jQuery Entry ]******** */


  init();



  // ######################################################################
  /**[ Event Handlers ]************************ */

  // List index changed
  $("#lstStep").change(function() {
    // alert($(this).val());
  });

  // Clear ALL tables in database
  $("#btnClearDb").click(function() {

    $.ChangePanel(-1, 3);
    UpdateStep(-1, 3);
    DbRemoveTables();
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

        case "btnSysConfig":
          // Create procedure to handle this!!!
        break;

        default:
          alert("Unknown button pressed!");
          break;
      }
    }
  });


  // ######################################################################
  /**[ Functions ]****************************** */
  $(function()
  {
    $.ChangePanel = function(old, next) {

      // 2012-1018 :: Added (-1) logic to quickly move to database installer after clearning tables
      if (old == -1)
      {
        $("#step1").hide(0);
        $("#step2").hide(0);
        $("#step4").hide(0);
        $("#step5").hide(0);
        $("#step6").hide(0);
        $("#step7").hide(0);
        $("#step3").show("normal");
      } else {
        $("#step"+old).hide("fast");
        $("#step"+next).show("normal");
      }
    };
  });
});


/* ############################################################################ */
/* ############################################################################ */

/**
 * Page Initialization
 */
function init()
{
  $('#step1').show(); $("#tblItem1").css("font-weight", "bold");
  $("#step2").hide(); $("#tblItem2").css("font-weight", "normal");
  $("#step3").hide(); $("#tblItem3").css("font-weight", "normal");
  $("#step4").hide(); $("#tblItem4").css("font-weight", "normal");
  $("#step5").hide(); $("#tblItem5").css("font-weight", "normal");
  $("#step6").hide(); $("#tblItem6").css("font-weight", "normal");
  $("#step7").hide(); $("#tblItem7").css("font-weight", "normal");

  $("#btnInstallDb").hide();
  $("#btnDbConnectionTest").show();
}


/*
 * update system with install step we're up (list box and display)
 */
function UpdateStep(ndxCurrent, ndxMove)
{
  var MAX_STEPS=6;  // (1-6)
  var iStep = 0;    // Go to this step

  /* 1. Get current step from #stepNdx=ndxCurrent */
  //alert("Move To: " + ndxMove +
  //"\n Max: " + MAX_STEPS);
  // We're doing this incase we allow jumping of steps later
  if(ndxMove > MAX_STEPS)  {
    iStep = ndxCurrent;           //alert("US.ndxMove=OOB_MAX");
  } else if (ndxMove < 1) {
    iStep = ndxCurrent;           //alert("US.ndxMove=OOB_low");
  } else {
    iStep = ndxMove;              //alert("US.ndxMove=good");
  }

  // Step 2) Switch item to bold
  // 2012-1018 + Added (-1) logic for ClearTables to quickly move to Db Installer
  if (ndxCurrent == -1)
  {
    $("#tblItem1").css("font-weight", "normal");
    $("#tblItem2").css("font-weight", "normal");
    $("#tblItem4").css("font-weight", "normal");
    $("#tblItem5").css("font-weight", "normal");
    $("#tblItem6").css("font-weight", "normal");
    $("#tblItem3" ).css("font-weight", "bold");
  }else{
    $("#tblItem" + ndxCurrent).css("font-weight", "normal");
    $("#tblItem" + iStep).css("font-weight", "bold");
  }

  // Step 3) Update PHP - Not really needed
  // 2012-1018 - Removed (djs) - Let jQuery do all the work, not PHP
  $("#stepNdx").val(iStep);
  /*
  $.post(
    "install.ajax.php",
    {UpdateStep: iStep},              // post data ($_POST["updateStep"] = iStep)
    function(data) {
      $("#stepNdx").val(data.returnValue);
      $("debugStep").html(data.returnValue);
    }
    ,"json");
  */
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
  $("#spnDbConnectionStatus").removeClass();
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
    beforeSend: function()
    {
      $("#spnDbConnectionStatus").html("<img src='pix/busy.gif' />");  // loading img during request
    },
    success: function(data)
    {
      $("#spnDbConnectionStatus").html(data.dbRet_msg);
      $("#spnDbConnectionStatus").removeClass();            // Remove all previous classes to reload load new update
      $("#spnDbConnectionStatus").addClass(data.dbRet_class)
      // $("#spnDbConnectionStatus").remove();    // remove the div
      //alert ('"'+ data.dbRet_class +'"');
      if(data.dbRet_class == "Success")
        { $("#btnInstallDb").show(); }
      else
        { $("#btnInstallDb").hide(); }
    }
  }).error(function()
  {
    alert("DbTestConnection Error");
  });
}


/**
 * Calls PHP to install the database
 */
function DbInstall()
{

  var dbHost     = $("#txtDbServer").val();
  var dbDatabase = $("#txtDbName").val();
  var dbPrefix   = $("#txtDbPrefix").val();
  var dbUser     = $("#txtDbUser").val();
  var dbPass     = $("#txtDbPass").val();

  // Clear old rules so we properly display spinner
  $("#spnDbConnectionStatus").removeClass();

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
      $("#spnDbConnectionStatus").removeClass();
      // $("#spnDbConnectionStatus").html("<img src='pix/spinner.gif' />");
      $("#spnDbConnectionStatus").html("<img src='pix/busy.gif' /> Installing...");
    },
    success: function(data) {
      $("#spnDbConnectionStatus").html(data.dbRet_msg);
      $("#spnDbConnectionStatus").removeClass();            // Remove all previous classes to reload load new update
      $("#spnDbConnectionStatus").addClass(data.dbRet_class)
    }
  });
}


/**
 * Calls PHP to install the database
 */
function DbRemoveTables()
{

  var dbHost     = $("#txtDbServer").val();
  var dbDatabase = $("#txtDbName").val();
  var dbPrefix   = $("#txtDbPrefix").val();
  var dbUser     = $("#txtDbUser").val();
  var dbPass     = $("#txtDbPass").val();

  // Clear old rules so we properly display spinner
  $("#spnDbConnectionStatus").removeClass();

  $.ajax({
    type: "POST",
    url:  "install.ajax.php",
    cache: false,
    dataType: "json",
    data: { ClearDB: "1",
            db_host: dbHost,
            db_name: dbDatabase,
            db_pref: dbPrefix,
            db_user: dbUser,
            db_pass: dbPass
          },
    beforeSend: function() {
      //$("#spnDbConnectionStatus").html("<img src='pix/spinner.gif' /> Removing database...");
      $("#spnDbConnectionStatus").html("<img src='pix/busy.gif' Removing database... />");
    },
    success: function(data) {
      $("#spnDbConnectionStatus").html(data.dbRet_msg);
      $("#spnDbConnectionStatus").removeClass();            // Remove all previous classes to reload load new update
      $("#spnDbConnectionStatus").addClass(data.dbRet_class)
    }
  });
}


/* ######################################################### */
/* ##[ Unused Code ]######################################## */
/* ######################################################### */

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
