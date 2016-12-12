<?php
namespace Cometwpp;

if ( ! defined( 'ABSPATH' ) ) {
  exit; // Exit if accessed directly.
}

/**
 * Prefix User Trait
 * @package Cometwpp
 * @category Trait
 */
trait PrefixUserTrait
{
  private $prefix = 'cometwpp_';
  
  private function setPrefix($prefix) {
    if(!is_string($prefix)) return false;
    if(!preg_match('/^[a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*$/', $prefix)) return false;
    $this->prefix = $prefix;
  }
}