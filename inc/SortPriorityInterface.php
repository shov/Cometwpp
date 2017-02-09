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
 * Method @see SortPriorityInterface::sortPriority() returns integer for sort priority known
 * @package Cometwpp
 * @category Interface
 */
interface SortPriorityInterface
{
    public function sortPriority(): int;
}