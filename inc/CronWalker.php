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
 * For Cron tasks
 * @package Cometwpp
 * @category Class
 */
class CronWalker extends AbstractContextController
{

    /**
     * CronWalker constructor.
     * @param string $autoLoadPath path to walks collects directory
     */
    public function __construct($autoLoadPath)
    {
       parent::__construct($autoLoadPath);
    }
}
