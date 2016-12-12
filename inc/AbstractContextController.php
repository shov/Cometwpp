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
abstract class AbstractContextController
{
    use EntityProviderTrait;
    use EntityLoaderTrait;

    protected $aEntities;

    /**
     * AbstractContextEntityController constructor.
     * @param string $autoLoadPath
     */
    public function __construct($autoLoadPath)
    {
        Core::getInstance();
        $this->aEntities = [];
        $this->entitiesAutoload($autoLoadPath);
    }
}
