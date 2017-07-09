<?php
namespace r7r1n17y\Myriad\Manage;
use r7r1n17y\Myriad\Interfaces;
class Cookie implements Handler {
  private $params = array();
  public function __construct(array $options = array()) {
    $this->params = session_get_cookie_params();
  }
  public function get(string $name = null, $default_return_val = null) {
    if (is_null($name)) {
      throw new \InvalidArgumentException(sprintf('$name only accepts strings. Input was: "%s"', gettype($name));
    }
    if (isset($_COOKIE[$name])) {
      return $this->decode($COOKIE[$name]);
    }
    return $default_return_val;
  }
}
?>
