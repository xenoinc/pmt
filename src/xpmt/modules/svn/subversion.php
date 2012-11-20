<?php
/************************************************************
* Copyright 2010 (C) Xeno Innovations, Inc.
* ALL RIGHTS RESERVED
* Author:        Damian J. Suess
* Document:      subversion
* Created Date:  Nov 16, 2010, 9:35:39 PM
* Description:
*
***********************************************************/

class subversion
{
  

  private function test()
  {
    $file = "https://xenoinc.dyndns.org/svn/fdx1/notes/issues.txt";
    $descriptorspec = array(0 => array('pipe', 'r'), 1 => array('pipe', 'w'), 2 => array('pipe', 'w'));
    $process = proc_open("svn cat ". $file, $descriptorspec, $pipes);
    // $process = proc_open("svn cat ".$this->location.$file, $descriptorspec, $pipes);
    if(is_resource($process))
    {
      print ("good". "<br>\n");

      print ($pipes[1] . "<br>\n");

      fclose($pipes[0]);
      print ($pipes[1]. "<br>\n");
      print (stream_get_contents($pipes[1]));
    }
    print ("done");
  }
}
?>
