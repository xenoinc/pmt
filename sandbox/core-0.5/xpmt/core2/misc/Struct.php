<?php

/* * **********************************************************
 * Copyright 2013 (C) Xeno Innovations, Inc.
 * ALL RIGHTS RESERVED
 * @Author:       Damiand Suess
 * Document:      Struct
 * Created Date:  Apr 4, 2013
 * Status:        {unstable/pre-alpha/alpha/beta/stable}
 * Description:
 *  Since PHP currently does not have a STRUCT (structure) feature we
 *  will create a pseudo one
 *
 * ToDo:
 *  [ ] perform testing
 *
 * Change Log:
 *
 */

namespace xenoPMT\Core\Misc
{
  // Credit: http://bran.name/dump/php-struct/
  /**
   * Stucture object class
   */
  class Struct
  {
    /**
     * Define a new struct object. This is a blueprint object with
     * only empty properties
     * @return \self (structure object with null properties)
     */
    public static function factory()
    {
      $struct = new self;
      foreach (func_get_args() as $value)
        $struct->$value= null;
      return $struct;
    }

    /**
     * Create a new variable witht he struct type $this
     * @return \xenoPMT\Core\Misc\Struct
     */
    public function create()
    {
      // clone the empty blueprint-struct ($this) inot the new data $struct
      $struct = clone $this;

      // Populate the new struct.
      $properties = array_keys((array) $struct);
      foreach (func_get_args() as $key => $value)
      {
        if(!is_null($value))
          $struct->$properties[$key] = $value;
      }
      // Return the populated struct.
      return $struct;
    }
    /* Ex 1
      require 'Struct.php';
      // define a 'coordinates' struct with 3 properties
      $coords = Struct::factory('degree', 'minute', 'second', 'pole');

      // create 2 latitude/longitude numbers
      $lat = $coords->create(35, 41, 5.4816, 'N');
      $lng = $coords->create(139, 45, 56.6958, 'E');

      // use the different values by name
      echo $lat->degree . '° ' . $lat->minute . "' " . $lat->second . '" ' . $lat->pole;
     **********************
     * EX 2 - Simple struct with a 'null' value
      require 'Struct.php';

      // define a struct
      $struct1 = Struct::factory('var1', 'var2');

      // create 2 variables of the 'struct1' type
      $instance0 = $struct1->create('val0-1', 'val0-2');
      $instance1 = $struct1->create('val1-1', 'val1-2');
      $instance2 = $struct1->create('val2-1'); // var2 will be null

      // use the variables later on in a readable manner
      echo $instance1->var2;
     ***********************************
     * EX 3 - Simple struct with a default value
      require 'Struct.php';
      // define a struct with a default value
      $struct2 = Struct::factory('var3', 'var4');
      $struct2->var3 = 'default';

      // create 2 variables of the 'struct2' type
      $instance3 = $struct2->create('val3-1', 'val3-2');
      $instance4 = $struct2->create('val4-1', 'val4-2');
      $instance5 = $struct2->create(null, 'val5-2'); // null becomes the default value

      // use the variables later on in a readable manner
      echo $instance4->var3;
     */
  }
}
?>
