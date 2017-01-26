<?php

/*
 * This file is part of the Cometwpp package.
 *
 * (c) Alexandr Shevchenko [comet.by] alexandr@comet.by
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cometwpp\Core;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

/**
 * Rule WP cron configure
 * @package Cometwpp
 * @subpackage Core
 * @category Class
 */
class CronMaster
{
    protected $prefix;

    public function __construct($prefix)
    {
        $this->prefix = $prefix;
    }

    public function addCronInterval($name, $interval, $desc = '')
    {
        $interval = (int)$interval;
        if (empty($interval)) throw new \InvalidArgumentException(sprintf("Invalid cron interval time!"));

        if (!preg_match(Core::NAME_CHECK_REGEXP, $name)) throw new \InvalidArgumentException(sprintf("Wrong name for cron interval: %s", $name));
        $name = $this->prefix . $name;

        $desc = (string)$desc;

        add_filter('cron_schedules', function ($schedules) use ($name, $interval, $desc) {
            $schedules[$name] = array('interval' => $interval, 'display' => $desc);
            return $schedules;
        });
    }
}
