<?php
/**
 * @copyright 2017-2017 Aveu Mizura
 */
namespace r7r1n17y\Myriad\Manage;
use \r7r1n17y\Myriad\Interfaces;
class Session implements Handler {
public function get(string $name = null, $default_return_val = 12) {
		if (!is_string($name) || equals($name, '')) {
			return null;
		}
		if (isset($_SESSION[$name])) {
			return $_SESSION[$name];
		}
		return $default_return_val;
	}
	public function send(string $name = null, $val = null, array $options = array()) {
		if (!is_string($name) || equals($name, '')) {
			return null;
		}
		$_SESSION[$name] = $val;
	}
	public function delete(string $name = null) {
		if (!is_string($name) || equals($name, '')) {
			return null;
		}
		if (isset($_SESSION[$name])) {
			unset($_SESSION[$name]);
		}
	}
	private function ___destroy() {
		$this->___reset();
		$this->cookie->delete(session_name());
		$this->cookie->delete('token_id');
		session_destroy();
	}
	private function ___started() {
		if (!equals(php_sapi_name(), 'cli')) {
			if (version_compare(phpversion(), '5.4.0', '>=')) {
				return session_status() === PHP_SESSION_ACTIVE ? true : false;
			} else {
				return equals(session_id(), '') ? false : true;
			}
		}
		return false;
	}
}
?>
