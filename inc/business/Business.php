<?php

/*
 * This file is part of the Cometwpp package.
 *
 * (c) Alexandr Shevchenko [comet.by] alexandr@comet.by
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cometwpp\Business;

use Cometwpp\Core;
use Cometwpp\SingletonTrait;

if ( ! defined( 'ABSPATH' ) ) {
  exit; // Exit if accessed directly.
}

/**
 * Find, load, initializate and provide business models objects
 * @package Cometwpp
 * @subpackage Business
 * @category Class
 */
final class Business 
{
  use SingletonTrait;

  public static function getInstance(Core $core) 
  {
    if(self::$_inst === null) {
      self::$_inst = new self($core);
    }
    return self::$_inst;
  }
  
  private $core;
  private $aModels;
  private function __construct(Core $core) 
  {
    $this->core = $core;
    $this->aModels = [];
    $this->modelsAutoload();
  }

  private function modelsAutoload() {
    //
  }

  /**
   *  Acess to business model objects
   *  call like getCart();
   *  @return NULL|mixed
   */  
  public function __call($getModelObjName) {
    if(!is_string($getModelObjName)) return NULL;
    if(strlen($getModelObjName) < 4) return NULL;
    if(strpos($getModelObjName, 'get') != 0) return NULL;

    $prop = substr($getModelObjName, 3);

    if(!empty($this->aModels[$prop])) return $this->aModels[$prop];
    return NULL;
  }
}
