<?php

/*
 * This file is part of the Cometwpp package.
 *
 * (c) Alexandr Shevchenko [comet.by] alexandr@comet.by
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cometwpp\Business;

use Cometwpp\Core\Core;
use Cometwpp\SingletonTrait;
use Cometwpp\EntityLoaderTrait;
use Cometwpp\EntityProviderTrait;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

/**
 * Find, load, initialize, provide business models objects
 * Warning! Core should be initialized
 * @package Cometwpp
 * @subpackage Business
 * @category Class
 */
final class Business
{
    use SingletonTrait;
    use EntityProviderTrait;
    use EntityLoaderTrait;

    /**
     * Init procedure, create the instance
     * @return null;
     */
    public static function init()
    {
        if (self::$_inst === null) {
            self::$_inst = new self();
        }
        return;
    }

    /**
     * @return Business
     */
    public static function getInstance()
    {
        self::init();
        return self::$_inst;
    }

    private $core;
    private $aEntities;

    /**
     * Business constructor.
     */
    private function __construct()
    {
        $this->core = Core::getInstance();
        assert(null != $this->core, "Core should already be initialized");

        $this->aEntities = [];
        $this->entitiesAutoload('Business'.DIRECTORY_SEPARATOR.'Models');
    }
}
