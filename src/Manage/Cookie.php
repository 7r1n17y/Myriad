<?php
namespace r7r1n17y\Myriad\Manage;
use r7r1n17y\Myriad\Interfaces;
class Cookie implements Handler {
  private $params = array();
  public function __construct(array $options = array()) {
    $this->params = session_get_cookie_params();
  }
  
}
?>
