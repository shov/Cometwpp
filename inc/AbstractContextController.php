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
        if(!is_readable($autoLoadPath) || !is_dir($autoLoadPath)) throw new \InvalidArgumentException(sprintf("Autoload path is wrong, %s given", $autoLoadPath));
        $this->entitiesAutoload($autoLoadPath);
    }
}
