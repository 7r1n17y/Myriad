<?php
abstract class Extender {
  private $_exts = array();
  public $_this;
  function __construct() {
    $_this = $this;
  }
  public function add_ext($object) {
    $this->_exts[] = $object;
  } 
  public function __get($var_name) {
    foreach($this->_exts as $ext) {
      if (property_exists($ext, $var_name)) {
        return $ext->$varname;
      }
    }
  }
  public function __call($method, $args) {
    foreach($this->_exts as $ext) {
      if (method_exists($ext, $method)) {
        return call_user_method_array($method,$ext,$args);
      }
    }
    throw new \RuntimeException(sprintf('This method "%s" doesn\'t exists', $method);
  }  
}
?>
