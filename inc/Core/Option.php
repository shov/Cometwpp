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

    /**
     * @var string $optionName
     */
    protected $optionName;

    /**
     * @var mixed $cache
     */
    protected $valCache;

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
        return get_option($this->optionName, $orVal);
    }

    /**
     * Update option value
     * @param mixed $val , if not passed, would used an empty array
     */
    public function update($val = [])
    {
        return update_option($this->optionName, $val);
    }

    /**
     * Cache option value, for update need @see Option::burn()
     * @param mixed $val
     */
    public function cache($val = [])
    {
        $this->valCache = $val;
    }

    /**
     * If have the cache then update option with it
     * @see Option::cache()
     * @return mixed|null
     */
    public function burn()
    {
        $haveCache = !is_null($this->valCache);
        assert($haveCache);
        if($haveCache) {
            $result = $this->update($this->valCache);
            $this->valCache = null;
            return $result;
        }
        return null;
    }
}