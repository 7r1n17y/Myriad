<?php
namespace r7r1n17y\Myriad\Interfaces;
interface Handler {
  public function __construct(array $options = array());
  public function get(string $name = null, $default_return_val = null);
  public function send(string $name = null, array $options = array('val' => null, 'expire' => 0));
  public function delete(string $name = null);
}
?>
