<?php
/**
 * @copyright 2017-2017 Aveu Mizura
 */
namespace r7r1n17y\Myriad\Helper;
abstract class Extender {
  private $_exts = array();
  public $_this;
  /**
   * Set the extender class object to a variable
   * @return void
   */
  function __construct() {
    $_this = $this;
  }
  /**
   * Add a class object to the extender
   * @param object $object The class object that will be arrayed in the extender
   * @return void
   */
  public function add_ext($object) {
    $this->_exts[] = $object;
  } 
  /**
   * Get a property variable
   * @param string $var_name The name of the property variable we want to get
   * @return void
   */
  public function __get($var_name) {
    foreach($this->_exts as $ext) {
      if (property_exists($ext, $var_name)) {
        return $ext->$varname;
      }
    }
  }
  /**
   * Call a method
   * @param string $method The name of the method to call
   * @param mixed $args the arguments associated with the method
   * @throws RuntimeException If the method does not exist
   * @return void
   */
  public function __call($method, $args) {
    foreach($this->_exts as $ext) {
      if (method_exists($ext, $method)) {
        return call_user_method_array($method,$ext,$args);
      }
    }
    throw new \RuntimeException(sprintf('This method "%s" doesn\'t exists', $method));
  }  
}
?>
