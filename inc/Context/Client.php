<?php

/*
 * This file is part of the Cometwpp package.
 *
 * (c) Alexandr Shevchenko [comet.by] alexandr@comet.by
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cometwpp\Context;

use Cometwpp\SingletonTrait;
use Cometwpp\AbstractContextEntityController;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

/**
 * Provide plugin's features to client code in the theme
 * @package Cometwpp
 * @subpackage Context
 * @category Class
 */
class Client extends AbstractContextEntityController
{
    use SingletonTrait;

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
     * @return Client
     */
    public static function getInstance()
    {
        self::init();
        return self::$_inst;
    }


    /**
     * Client constructor.
     */
    protected function __construct()
    {
        parent::__construct();

        $this->aEntities = [];
        $this->entitiesAutoload('Features');
    }
}
