<?php

/*
 * This file is part of the Cometwpp package.
 *
 * (c) Alexandr Shevchenko [comet.by] alexandr@comet.by
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cometwpp\Core;

use Cometwpp\SingletonTrait;
use Cometwpp\PrefixUserTrait;

if ( ! defined( 'ABSPATH' ) ) {
  exit; // Exit if accessed directly.
}

/**
 * Wordpress options adapter made as the Registry
 * @package Cometwpp
 * @subpackage Core
 * @category Class
 */
final class Registry 
{
  use SingletonTrait, PrefixUserTrait;

  public static function getInstance($prefix, $aWpOptions) 
  {
    if(self::$_inst === null) {
      self::$_inst = new self($prefix, $aWpOptions);
    }
    return self::$_inst;
  }

  private $aOptions;

  /**
   * @param string $prefix
   * @param array of strings $aWpOptions
   */  
  private function __construct($prefix, $aWpOptions) 
  {
    if(is_string($prefix) && !empty($prefix)) $this->setPrefix($prefix);

    $aOptNames = [];
    if(is_array($aWpOptions)) {
      $aOptNames = $aWpOptions;
    } else {
      $aOptNames = [(string)$aWpOptions,];
    }

    $this->aOptions = [];
    foreach($aOptNames as $sOptName) {
      $this->aOptions[$sOptName] = new Option($this->prefix, $sOptName);
    }
  }

  /**
   * Add option to Registry, if the same name not exists
   * @param string $name
   * @param mixed $value : as default use empty array
   */  
  public function addOption($name, $value = []) 
  {
    if(empty($name)) return; //here false or 0 will be empty
    $name = (string)$name;
    if(!array_key_exists($name, $this->aOptions)) {
      $this->aOptions[$name] = new Option($this->prefix, $name);
    }
  }

  /**
  * Try to get access to the option by name
  * @param string $name
  * @return Option | false
  */
  public function __get($name) 
  {
    if(is_object($this->aOptions[$name])) {
      return $this->aOptions[$name];
    }
    return false;
  }
}