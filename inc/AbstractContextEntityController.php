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
 * Load and provide features
 * @package Cometwpp
 * @category Class
 */
abstract class AbstractContextEntityController
{
    use EntityProviderTrait;
    use EntityLoaderTrait;

    protected $core;
    protected $aEntities;

    /**
     * AbstractContextEntityController constructor.
     */
    protected function __construct()
    {
        $this->core = Core::getInstance();
        assert(null != $this->core, "Core should already be initialized");

        $this->aEntities = [];
    }
}
