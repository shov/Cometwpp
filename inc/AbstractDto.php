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
 * Base for all Dto instances
 * @see DtoInterface
 * @package Cometwpp
 * @category Class
 */
abstract class AbstractDto implements DtoInterface
{
    public $id;

    public function getId(): int
    {
        return (int)$this->id;
    }

    public function setId(int $id)
    {
        $this->id = $id;
    }
}