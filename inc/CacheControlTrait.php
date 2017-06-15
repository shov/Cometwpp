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

use Cometwpp\Core\Core;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

/**
 * Take cache control interfaces, no contain or drive any cache
 * @package Cometwpp
 * @category Trait
 */
trait CacheControlTrait
{
    protected static $skipCache = false;

    public static function skipCacheOn()
    {
        static::$skipCache = true;
    }

    public static function skipCacheOff()
    {
        static::$skipCache = false;
    }

    public static function getSeed(): string
    {
        return date("mmddHHii");
    }

}