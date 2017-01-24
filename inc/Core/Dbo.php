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

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

/**
 * Wordpress wpdb object provider, "db facade"
 * @package Cometwpp
 * @subpackage Core
 * @category Class
 */
final class Dbo
{
    use SingletonTrait;

    public static function getDb()
    {
        global $wpdb;
        if (!isset($wpdb)) throw new \Exception(sprintf('Can\'t find $wpdb in the global scope.'));
        if (!is_object($wpdb)) throw new \Exception(sprintf('$wpdb is not an object.'));
        if (!($wpdb instanceof \wpdb)) throw new \Exception(sprintf('$wpdb is not instance of wpdb class.'));
        return $wpdb;
    }
}