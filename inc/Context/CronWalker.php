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

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

/**
 * For Cron tasks
 * @package Cometwpp
 * @subpackage Context
 * @category Class
 */
class CronWalker
{
    private static $_initDone;
    /**
     * Init procedure, always calling if have hot called yet when instance creating
     * @return null;
     */
    public static function init()
    {
        if (true === self::$_initDone) return;
        self::$_initDone = true;
    }

    /**
     * CronWalker constructor.
     */
    public function __construct()
    {
        self::init();
    }
}
