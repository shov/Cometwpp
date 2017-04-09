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
 * Data Transfer Object common interface
 * @package Cometwpp
 * @category Interface
 */
interface DtoInterface
{
    public function getId(): int;

    public function setId(int $id);
}