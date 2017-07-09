<?php
namespace r7r1n17y\Myriad\Manage;
use r7r1n17y\Myriad\Interfaces;
class Cookie implements Handler {
  private $params = array();
  public function __construct(array $options = array()) {
    $this->params = session_get_cookie_params();
  }
  public function get(string $name = null, $default_return_val = null) {
    if (!is_string($name) || is_null($name)) {
      throw new \InvalidArgumentException(sprintf('$name only accepts strings. Input was: "%s"', gettype($name));
    }
    if ($name === '') {
      throw new \InvalidArgumentException('$name can not be empty.');
    }
    if (isset($_COOKIE[$name])) {
      return $this->_decode($COOKIE[$name]);
    }
    return $default_return_val;
  }
  public function send(string $name = null, array $options = array('val' => null, 'expire' => 0)) {
    if (!is_string($name) || is_null($name)) {
      throw new \InvalidArgumentException(sprintf('$name only accepts strings. Input was: "%s"', gettype($name));
    }
    if ($name === '') {
      throw new \InvalidArgumentException('$name can not be empty.');
    }
    $val = isset($options['val']) ? $options['val'] : null;
    $expire = isset($options['expire']) ? $options['expire'] : 0;
    if (!is_int($expire) && !is_float($expire)) {
      throw new \InvalidArgumentException(sprintf('$expire only accepts integers. Input was: "%s"', gettype($expire));
    }
		$expire = intval($expire);
    if ($expire === 0) {
      $expire = time() - (time() * 2);
    }
    setcookie($name, $this->_encode(array('val' => $val)), time() + ($expire), $this->params['path'], $this->params['domain'], $this->params['secure'], $this->params['httponly']);
	}
}
?>
