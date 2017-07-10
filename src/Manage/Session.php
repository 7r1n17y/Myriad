<?php
/**
 * @copyright 2017-2017 Aveu Mizura
 */
namespace r7r1n17y\Myriad\Manage;
use \r7r1n17y\Myriad\Interfaces;
class Session implements Handler {
  public function get(string $name = null, $default_return_val = null) {
    if (!is_string($name) || is_null($name)) {
      throw new \InvalidArgumentException(sprintf('$name only accepts strings. Input was: "%s"', gettype($name));
    }
    if (empty($name)) {
      throw new \InvalidArgumentException('$name can not be empty.');
    }
    if (isset($_SESSION[$name])) {
      return $_SESSION[$name];
    }
    return $default_return_val;
  }
  public function send(string $name = null, array $options = array('val' => null)) {
    if (!is_string($name) || is_null($name)) {
      throw new \InvalidArgumentException(sprintf('$name only accepts strings. Input was: "%s"', gettype($name));
    }
    if (empty($name)) {
      throw new \InvalidArgumentException('$name can not be empty.');
    }
    $val = isset($options['val']) ? $options['val'] : null;
    $_SESSION[$name] = $val;
  }
  public function delete(string $name = null) {
    if (!is_string($name) || is_null($name)) {
      throw new \InvalidArgumentException(sprintf('$name only accepts strings. Input was: "%s"', gettype($name));
    }
    if (empty($name)) {
      throw new \InvalidArgumentException('$name can not be empty.');
    }
    if (isset($_SESSION[$name])) {
      unset($_SESSION[$name]);
    }
  }
}
?>
