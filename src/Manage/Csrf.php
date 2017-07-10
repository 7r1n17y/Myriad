<?php
/**
 * @copyright 2017-2017 Aveu Mizura
 */
namespace r7r1n17y\Myriad\Manage;
use \r7r1n17y\Myriad\Interfaces, \r7r1n17y\Myriad\Helper\Extender as Extender;
class Csrf extends Extender implements Forms {
  /** @var object $session An object that holds the session class */
  private $session = null;
  /** @var object $cookie An object that holds the cookie class */
  private $cookie = null;
  /** @var string $token_name The default token name for a csrf token */
  private $token_name = 'CsrfValidate';
  /**
   * Sets the object classes and adds an external helper class
   * @param mixed[] $options Contains any avaliable configuration options
   * @throws RuntimeException If the classes we not successfully set
   * @return void
   */
  public function __construct(array $options = array()) {
    $this->session = isset($options['classes']['session']) ? $options['classes']['session'] : null;
    $this->cookie = isset($options['classes']['cookie']) ? $options['classes']['cookie'] : null;
    if (is_null($this->session) || is_null($this->cookie)) {
      throw new \RuntimeException('The csrf class requires the session class and the cookie class to function correctly.');
    }
    parent::add_ext(new \HelperFunc($options));
  }
  /**
   * Sets the object classes and adds an external helper class
   * @param mixed[] $options Contains any avaliable configuration options
   * @throws RuntimeException If the classes we not successfully set
   * @return void
   */
  public function get(string $type = null) {
    if (!is_string($type) || is_null($type)) {
      throw new \InvalidArgumentException(sprintf('$type only accepts strings. Input was: "%s"', gettype($type));
    }
    if (empty($type)) {
      throw new \InvalidArgumentException('$type can not be empty.');
    }
    if ($this->equals($type, 'token_name')) {
      return $this->token_name;
    }
    if ($this->equals($type, 'session')) {
      if (is_null($this->session->get($this->token_name))) {
        $this->_generateToken();
      }
      return $this->session->get($this->token_name);
    }
    if (is_null($this->cookie->get('token_id'))) {
      $this->_generateCookie();
    }
    return $this->cookie->get('token_id');
  }
  public function run(array $request_data = array(), bool $chk = null, array $const_options = array()) {
    if ($this->equals($const_options, 'validate_csrf')) {
      if (!$this->_validRequest($chk)) {
        return false;
      } elseif (is_null($this->session->get($this->token_name)) || is_null($this->cookie->get('token_id'))) {
        $this->_generateToken();
        $this->_generateCookie();
        return false;
      } elseif (empty($request_data[$this->token_name])) {
        $this->_generateToken();
        $this->_generateCookie();
        return false;
      } elseif ($this->equals($request_data[$this->token_name], $this->get('session')) && $this->equals($this->get('token_id'), $this->session->get('token_id'))) {
        return true;
      } else {
        $this->_generateToken();
        $this->_generateCookie();
        return false;
      }
    }
  }
  private function _validRequest(bool $chk = null) {
    if ($this->_isAjaxRequest($chk) && $this->_validReferer()) {
      return true;
    }
    return false;
  }
  private function _generateToken() {
    $this->session->send($this->token_name, bin2hex(random_bytes(32)));
  }
  private function _generateCookie() {
    $token = $this->random(20);
    $this->cookie->send('token_id', $token, array('expire', 0));
    $this->session->send('token_id', $token);
  }
  private static function _isAjaxRequest(bool $chk = null) {
    if ($chk === false) {
      return true;
    }
    return !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
  }
  private function _validReferrer() {
    $url = parse_url(isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '');
    if (!isset($url['host'])) {
      return false;
    }
    $allowed_hosts = array($url['host'], 'www.' . $url['host'], str_replace('www.', '', $url['host']));
    if (!in_array($_SERVER['SERVER_NAME'], $allowed_hosts)) {
      return false;
    }
    return true;
  }
}
?>
