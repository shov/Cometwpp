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

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

/**
 * Return walk name and callback
 * @package Cometwpp
 * @category Class
 */
abstract class AbstractCronWalk
{
    /**
     * @return array ['name' => string, 'interval', 'action' => callable,]
     */
    abstract public function getWalkAction();
}