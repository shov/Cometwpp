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

if (!defined('ABSPATH')) {
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

    const FORCE = true;
    const CACHE = false;
    /**
     * @var string $optionName
     */
    protected $optionName;

    /**
     * @var mixed $cache
     */
    protected $cachedVal;

    /**
     * @param string $prefix
     * @param string $name
     */
    public function __construct(string $prefix, string $name)
    {
        if (0 !== strlen($prefix)) $this->setPrefix($prefix);

        if (0 === strlen($name)) {
            throw new \InvalidArgumentException(sprintf("Invalid option name! %s given", $name));
        } else {
            $this->optionName = $this->prefix . $name;
        }

        add_option($this->optionName, []); //if option are exists, do nothing
    }

    /**
     * Try to get option, if get a fail, return $orVal
     * @param mixed $orVal
     * @return mixed : option value | $orVal
     */
    public function get($orVal = null)
    {
        if(!is_null($this->cachedVal)) {
            return $this->cachedVal;
        }
        return get_option($this->optionName, $orVal);
    }

    /**
     * Update option value
     * @param mixed $val , if not passed, would used an empty array
     * @param bool $mod FORCE or CACHE, if FORCE, option will be born, cache will be updated any way
     * @return mixed
     */
    public function update($val = [], bool $mod = self::CACHE)
    {
        $this->cachedVal = $val;
        if(self::FORCE === $mod) {
            return update_option($this->optionName, $val);
        }
        return $this->cachedVal;
    }

    public function __destruct()
    {
        if(!is_null($this->cachedVal)) {
            update_option($this->optionName, $this->cachedVal);
        }
    }
}