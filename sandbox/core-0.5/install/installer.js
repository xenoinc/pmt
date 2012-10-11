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

  $('#step0').hide(); // $('#step0').show();
  $("#step1").show(); // $("#step1").hide();
  $("#step2").hide();
  $("#step3").hide();
  $("#step4").hide();
  $("#step5").hide();
  $("#step6").hide();


  /**[ Event Handlers ]************************ */

  // List index changed
  $("#lstStep").change(function() {
    // alert($(this).val());
  });

  // a button was pressed
  $("button").click(function() {
    // $(this).text(); = Button Caption
    // $(".myclass).attr("class"); = String Value
    // $("").hasClass("divhover"); = BOOL_VAL

    if ($(this).hasClass("btnNext"))
      alert("next was clicked");

    if ($(this).hasClass("btnPrev"))
      alert("prev was clicked");

    var num = $("#stepNdx").val();    // get Value setting
    //alert(num);
    //alert("enter with num: " + num);
    updateStep(num);
    //alert("exit");
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


function updateStep(str)
{
    $.post(
      "install.ajax.php",
      {updateStep: str},
      function(data) {
        //alert("pre update step");
        $("#stepNdx").html(data.returnValue);
        //alert("pre update disp");
        $("testDisplay").html(data.returnValue);
        alert("done with funciton");
      }
      ,"json");
}