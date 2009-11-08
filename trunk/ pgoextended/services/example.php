<?php

/**
 * this is a class
 */
class Foo {
  /** The Add function
   * @param int $x
   * @param int $y
   * @return int
   */
  function Add($x,$y) {
    return $x+$y;
  }

  /** The echoBar function
   * @param object Bar $bar
   * @return object Bar
   */
   function echoBar($bar) { return $bar; }
}

class Bar {
  /**
   * @var string
   */
   public $param1;
  /**
   * @var string
   */
   public $param2;
  /**
   * @var string
   */
   public $param3;
}

include 'WSDL_Gen.php';
$wsdl = new WSDL_Gen('Foo', 'http://localhost/ws/Foo.php', 'http://www.example.org/myns/');
print $wsdl->toXML();
