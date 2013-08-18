module loader:

<?php
  $kbID = $xpmtCore["uri"]->Segment(0);    // ID number of current page
  
  
  switch $urlGet
  {
    case "":
      break;
    
    case "edit"
      // Show page editor
      $tmp = "<div id='kb_editor'>";
      
      $xpmtCore->AppendHTML($kb->ShowEditor($kbID));  // NEW method
      
      // Show old page after separator
      $xpmtCore->AppendHTML("<br /><hr />" . $kb->GetPage($kbID) . "<br />");
      break;
  }
?>