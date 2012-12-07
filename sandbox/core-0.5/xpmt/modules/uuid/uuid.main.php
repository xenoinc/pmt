<?php

/** * *********************************************************
 * Copyright 2012 (C) Xeno Innovations, Inc.
 * ALL RIGHTS RESERVED
 * @Author:       Damian Suess
 * Document:      uuid
 * Created Date:  Oct 29, 2012
 * Status:        {unstable/pre-alpha/alpha/beta/stable}
 * Description:
 *  Valid RFC 4122 Compliant Universally Unique Identifier (UUID) version 3, 4 and 5.
 *  http://www.ietf.org/rfc/rfc4122.txt
 *
 *
 * Change Log:
 *
 */

require_once (PMT_PATH . "xpmt/core/pmt.module.php");
// namespace xenoPMT\Module\UUID;
class uuid extends pmtModule
{
  /* Private vars */


  /* Public Vars */

  function __construct() {

  }

  function __destruct() {

  }

  public function PageData()
  {
    // No need to perform login check.. just generate the damn UUID

    $style = <<<HTM
style="border: 1px solid #E4E4E4;
border-radius: 0.5em 0.5em 0.5em 0.5em;
box-shadow: 0 0.5em 1.5em #9E9EFF;
padding: 15px;
font: 10px verdana, 'bitstream Vera Sans', helvetica, arial, sans-serif;
margin: 0.66em 0 0.33em;"
HTM;

    $html = "<h1>GUID Generator</h1>".PHP_EOL;
    // $html .= "<p><pre {$style}>".self::GenVer4()."</pre></p>".PHP_EOL;

    $html .= "<p><table {$style}><tr><td>".self::GenVer4()."</td></tr></table></p>".PHP_EOL;

    return $html;

  }


  /**
   * Generate Version 3 Name-Based UUID
   *
   * Usage:
   *  $buff = self::GenVer3("abcdef00-794f-1234-1234-1234567980ab", "Random String");
   *
   * @param string $namespace
   * @param string $name
   * @return string
   */
  private static function GenVer3($namespace, $name)
  {
    if(!self::is_valid($namespace)) return false;

    // Get hexadecimal components of namespace
    $nhex = str_replace(array('-','{','}'), '', $namespace);

    // Binary Value
    $nstr = '';

    // Convert Namespace UUID to bits
    for($i = 0; $i < strlen($nhex); $i+=2) {
      $nstr .= chr(hexdec($nhex[$i].$nhex[$i+1]));
    }

    // Calculate hash value
    $hash = md5($nstr . $name);

    return sprintf('%08s-%04s-%04x-%04x-%12s',

      // 32 bits for "time_low"
      substr($hash, 0, 8),

      // 16 bits for "time_mid"
      substr($hash, 8, 4),

      // 16 bits for "time_hi_and_version",
      // four most significant bits holds version number 3
      (hexdec(substr($hash, 12, 4)) & 0x0fff) | 0x3000,

      // 16 bits, 8 bits for "clk_seq_hi_res",
      // 8 bits for "clk_seq_low",
      // two most significant bits holds zero and one for variant DCE1.1
      (hexdec(substr($hash, 16, 4)) & 0x3fff) | 0x8000,

      substr($hash, 20, 12) // 48 bits for "node"
    );
  }

  /**
   * UUID Version 4 - Pseudo Random
   *
   * Usage:
   *  $buff = self::GenVer4();
   *
   * @return string UUID
   */
  private static function GenVer4() {
    return sprintf( '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
        // 32 bits for "time_low"
        mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ),

        // 16 bits for "time_mid"
        mt_rand( 0, 0xffff ),

        // 16 bits for "time_hi_and_version",
        // four most significant bits holds version number 4
        mt_rand( 0, 0x0fff ) | 0x4000,

        // 16 bits, 8 bits for "clk_seq_hi_res",
        // 8 bits for "clk_seq_low",
        // two most significant bits holds zero and one for variant DCE1.1
        mt_rand( 0, 0x3fff ) | 0x8000,

        // 48 bits for "node"
        mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff )
    );
  }

  /**
   * Generate Version 5 Name-Based UUID
   *
   * Usage:
   *  $buff = self::GenVer5("abcdef00-794f-1234-1234-1234567980ab", "Random String");
   *
   * @param string $namespace
   * @param string $name
   * @return string
   */
  private static function GenVer5($namespace, $name)
  {
    if(!self::is_valid($namespace)) return false;

    // Get hexadecimal components of namespace
    $nhex = str_replace(array('-','{','}'), '', $namespace);


    $nstr = '';     // Binary Value

    // Convert Namespace UUID to bits
    for($i = 0; $i < strlen($nhex); $i+=2) {
      $nstr .= chr(hexdec($nhex[$i].$nhex[$i+1]));
    }

    // Calculate hash value
    $hash = sha1($nstr . $name);

    return sprintf('%08s-%04s-%04x-%04x-%12s',

      // 32 bits for "time_low"
      substr($hash, 0, 8),

      // 16 bits for "time_mid"
      substr($hash, 8, 4),

      // 16 bits for "time_hi_and_version",
      // four most significant bits holds version number 5
      (hexdec(substr($hash, 12, 4)) & 0x0fff) | 0x5000,

      // 16 bits, 8 bits for "clk_seq_hi_res",
      // 8 bits for "clk_seq_low",
      // two most significant bits holds zero and one for variant DCE1.1
      (hexdec(substr($hash, 16, 4)) & 0x3fff) | 0x8000,

      // 48 bits for "node"
      substr($hash, 20, 12)
    );
  }


  private static function is_valid($uuid)
  {
    return preg_match('/^\{?[0-9a-f]{8}\-?[0-9a-f]{4}\-?[0-9a-f]{4}\-?'.
                    '[0-9a-f]{4}\-?[0-9a-f]{12}\}?$/i', $uuid) === 1;
  }
}

?>
