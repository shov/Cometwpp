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

use Cometwpp\PrefixUserTrait;

if ( ! defined( 'ABSPATH' ) ) {
  exit; // Exit if accessed directly.
}

/**
 * Wordpress options adapter
 * @package Cometwpp
 * @subpackage Core
 * @category Class
 */
class Option 
{
  use PrefixUserTrait;

  protected $optionName;

  /**
   *  @param string $prefix
   *  @param string $name
   */
  public function __construct($prefix, $name) 
  {
    if(is_string($prefix) && !empty($prefix)) $this->setPrefix($prefix);
    $defaultOptionName = 'options';
    
    if(empty($name)) {
      $this->optionName = $this->prefix.$defaultOptionName;
    } else {
      $this->optionName = $this->prefix.$name;
    }

    add_option($this->optionName, []); //if option are exists, do nothing
  }

  /**
   * Try to get option, if get a fail, return $orVal
   * @param mixed $orVal
   * @return mixed : option value | $orVal
   */  
  public function get($orVal = false) 
  {
    return get_option($this->optionName, $orThisVal);
  }

  /**
   * Update option value
   * @param mixed $val, if not passed, would used an empty array
   */  
  public function update($val = []) 
  {
    return update_option($this->optionName, $val);
  }
}