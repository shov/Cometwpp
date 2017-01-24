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
 * Base class for the models who save data Registry based way
 * @package Cometwpp
 * @category Class
 */
abstract class AbstractRegistryBasedModel extends AbstractRegistryManager
{
    protected $context;

    /**
     * AbstractRegistryBasedModel constructor.
     * @param ContextController $context
     */
    public function __construct(ContextController $context)
    {
        $this->context = $context;
    }
}