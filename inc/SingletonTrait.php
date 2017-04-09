<?php

/*
 * This file is part of the Cometwpp package.
 *
 * (c) Alexandr Shevchenko [comet.by] alexandr@comet.by
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cometwpp;

if ( ! defined( 'ABSPATH' ) ) {
  exit; // Exit if accessed directly.
}

/**
 * Singleton Trait
 * @package   Cometwpp
 * @category  Trait
 */
trait SingletonTrait
{
  private static $_inst;
  public static function getInstance() {
    return isset(static::$_inst)
        ? static::$_inst
        : static::$_inst = new static;
  }
  private function __construct() {}
  final private function __wakeup() {}
  final private function __clone() {}    
}