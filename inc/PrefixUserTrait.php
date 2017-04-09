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
 * Prefix User Trait
 * @package Cometwpp
 * @category Trait
 */
trait PrefixUserTrait
{
    private $prefix = 'cometwpp_';

    private function setPrefix($prefix)
    {
        if (!is_string($prefix)) return false;
        if (!preg_match(Core::NAME_CHECK_REGEXP, $prefix)) return false;
        $this->prefix = $prefix;
    }
}