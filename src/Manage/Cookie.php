<?php
namespace r7r1n17y\Myriad\Manage;
use r7r1n17y\Myriad\Interfaces, r7r1n17y\Myriad\Helper\Extender as Extender;
class Cookie extends Extender implements Handler {
  private $params = array();
  public function __construct(array $options = array()) {
    $this->params = session_get_cookie_params();
    parent::add_ext(new HelperFunc($options));
  }
  public function get(string $name = null, $default_return_val = null) {
    if (!is_string($name) || is_null($name)) {
      throw new \InvalidArgumentException(sprintf('$name only accepts strings. Input was: "%s"', gettype($name));
    }
    if (empty($name)) {
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
    if (empty($name)) {
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
  public function delete(string $name = null) {
    if (!is_string($name) || is_null($name)) {
      throw new \InvalidArgumentException(sprintf('$name only accepts strings. Input was: "%s"', gettype($name));
    }
    if (empty($name)) {
      throw new \InvalidArgumentException('$name can not be empty.');
    }
    if (isset($_COOKIE[$name])) {
      unset($_COOKIE[$name]);
      setcookie($name, '', time() - 42000, $this->params['path'], $this->params['domain'], $this->params['secure'], $this->params['httponly']);
    }
  }
  private function _encode(array $options = array('val' => null)) {
    $val = isset($options['val']) ? $options['val'] : null;
    if (is_array($val)) {
      $res = 'a|' . json_encode($val); 
    } elseif (is_bool($val)) {
      if ($val) {
        $res = 'b|t';
      } else {
        $res = 'b|f';
      }
    } elseif (is_int($val)) {
      $res = 'i|' . strval($val);
    } elseif (is_float($val)) {
      $res = 'f|' . strval($val);
    } elseif (is_string($val)) {
      $res = "s|$val";
    } else {
      $res = 'n|n';
    }
    return $res;
  }
  private function _decode(string $encoded = null) {
    if (!is_string($encoded) || is_null($encoded)) {
      throw new \InvalidArgumentException(sprintf('$encoded only accepts strings. Input was: "%s"', gettype($encoded));
    }
    if (empty($encoded)) {
      throw new \InvalidArgumentException('$encoded can not be empty.');
    }
    $fc = mb_substr($encoded, 0, 1);
    if ($this->equals($fc, 'a')) {
      $encoded = ltrim($encoded, 'a|');
      return json_decode($encoded);
    } elseif ($this->equals($fc, 'b')) {
      $encoded = ltrim($encoded, 'b|');
      if ($this->equals($encoded, 't')) {
        return true;
      } else {
        return false;
      }
    } elseif ($this->equals($fc, 'i')) {
      $encoded = ltrim($encoded, 'i|');
      return (int) $encoded;
    } elseif ($this->equals($fc, 'f')) {
      $encoded = ltrim($encoded, 'f|');
      return (float) $encoded;
    }
    if ($this->equals($fc, 's')) {
      $encoded = ltrim($encoded, 's|');
      return $encoded;
    } else {
      return null;
    }
  }
}
?>
