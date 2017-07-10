<?php
class Csrf implements Forms {
	private $session = null;
	private $cookie = null;
	const TOKEN_NAME = 'CsrfValidate';
	public function __construct(array $options = array()) {
		$this->session = isset($options['classes']['session']) ? $options['classes']['session'] : null;
		$this->cookie = isset($options['classes']['cookie']) ? $options['classes']['cookie'] : null;
		if (is_null($this->session) || is_null($this->cookie)) {
			throw new \RuntimeException('The csrf class requires the session class and the cookie class to function correctly.');
		}
	}
	public function get(mixed $type = null) {
		if (!is_string($type) || equals($type, '')) {
			return null;
		}
		if (equals($type, 'token_name')) {
			return self::TOKEN_NAME;
		}
		if (equals($type, 'session')) {
			if (is_null($this->session->get(self::TOKEN_NAME))) {
				$this->___generateToken();
			}
			return $this->session->get(self::TOKEN_NAME);
		}
		if (is_null($this->cookie->get('token_id'))) {
			$this->___generateCookie();
		}
		return $this->cookie->get('token_id');
    }
	public function run(array $request_data = array(), mixed $chk = null, mixed $const_options = null) {
		if (equals($const_options, 'validate_csrf')) {
			if (!$this->___validRequest($chk)) {
				return false;
			} elseif (
				is_null($this->session->get(self::TOKEN_NAME)) || is_null($this->cookie->get('token_id'))) {
				$this->___generateToken();
				$this->___generateCookie();
				return false;
			} elseif (empty($request_data[self::TOKEN_NAME])) {
				return false;
			} elseif (equals($request_data[self::TOKEN_NAME], $this->get('session'))
				&& equals($this->get('token_id'), $this->session->get('token_id'))) {
				return true;
			} else {
				$this->___generateToken();
				$this->___generateCookie();
				return false;
			}
		}
    }
	private function ___validRequest(mixed $chk = null) {
		if ($this->___isAjaxRequest($chk) && $this->___validReferer()) {
			return true;
		}
		return false;
    }
	private function ___generateToken() {
		$this->session->send(self::TOKEN_NAME, bin2hex(random_bytes(32)));
		if (!version_compare(phpversion(), '7.0.0', '>=')) {
			$this->session->send(self::TOKEN_NAME, bin2hex(openssl_random_pseudo_bytes(32)));
		}
    }
	private function ___generateCookie() {
		$token = random(20);
		$this->cookie->send('token_id', $token, array('expire', 0));
		$this->session->send('token_id', $token);
	}
	private static function ___isAjaxRequest(mixed $chk = null) {
		if ($chk === false) {
			return true;
		}
		return !empty($_SERVER['HTTP_X_REQUESTED_WITH'])
			&& strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
	}
	private function ___validReferrer() {
		$url = parse_url(isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '');
		if (!isset($url['host'])) {
			return false;
		}
		$allowed_hosts = array(
			$url['host'],
			'www.' . $url['host'],
			str_replace('www.', '', $url['host'])
		);
		if (!in_array($_SERVER['SERVER_NAME'], $allowed_hosts)) {
			return false;
		}
		return true;
	}
}
?>
