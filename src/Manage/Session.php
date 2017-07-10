<?php
/**
 * @copyright 2017-2017 Aveu Mizura
 */
namespace r7r1n17y\Myriad\Manage;
use \r7r1n17y\Myriad\Interfaces;
class Session implements Handler {
  /**
   * Get a session variable
   * @param string $name The name of the session variable we want to get
   * @throws InvalidArgumentException If the name variable is not a string or is empty
   * @return mixed The value of the session variable or the defualt return value
   */
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
  /**
   * Send a session variable
   * @param string $name The name of the session variable we want to send
   * @param mixed[] $options An array that contains the value we want to send
   * @throws InvalidArgumentException If the name variable is not a string or is empty
   * @return void
   */
  public function send(string $name = null, array $options = array('val' => null, 'expire' => 0)) {
    if (!is_string($name) || is_null($name)) {
      throw new \InvalidArgumentException(sprintf('$name only accepts strings. Input was: "%s"', gettype($name));
    }
    if (empty($name)) {
      throw new \InvalidArgumentException('$name can not be empty.');
    }
    $val = isset($options['val']) ? $options['val'] : null;
    $_SESSION[$name] = $val;
  }
  /**
   * Delete a session variable
   * @param string $name The name of the session variable we want to delete
   * @throws InvalidArgumentException If the name variable is not a string or is empty
   * @return void
   */
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
