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

use Cometwpp\AbstractCronWalk;

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
    protected $intervals = [];
    protected $walksActSignatures = [];

    const WALK_SIGNATURE = ['NAME' => 0, 'INTERVAL_NAME' => 1,];

    public function __construct($prefix)
    {
        $this->prefix = $prefix;
    }

    /**
     * @param string $name
     * @param int $interval in seconds
     * @param string $desc
     */
    public function addCronInterval($name, $interval, $desc = '')
    {
        $interval = (int)$interval;
        if (empty($interval)) throw new \InvalidArgumentException(sprintf("Invalid cron interval time!"));

        $name = $this->checkAndCleanName($name, sprintf("Invalid cron interval name!"), sprintf("Wrong cron interval name: %s", $name));
        $pxName = $this->prefix . $name;

        $desc = (string)$desc;

        if (in_array($pxName, $this->intervals)) throw new \InvalidArgumentException(sprintf("Interval with name '%s' already has been registered!", $name));
        $this->intervals[] = $pxName;

        add_filter('cron_schedules', function ($schedules) use ($pxName, $interval, $desc) {
            $schedules[$pxName] = array('interval' => $interval, 'display' => $desc);
            return $schedules;
        });
    }

    /**
     *  Call this function after register all walks
     */
    public function upCron()
    {
        $self = $this;
        add_action('wp', function () use ($self) {
            foreach ($self->walksActSignatures as $walkSignature) {
                if (!wp_next_scheduled($walkSignature[$self::WALK_SIGNATURE['NAME']])) {
                    wp_schedule_event(time(), $walkSignature[$self::WALK_SIGNATURE['INTERVAL_NAME']], $walkSignature[$self::WALK_SIGNATURE['NAME']]);
                }
            }
        });
    }

    /**
     * @param AbstractCronWalk[] ...$walks
     */
    public function registerWalks(AbstractCronWalk ...$walks)
    {
        foreach ($walks as $walk) {
            $actions = $walk->getWalkAction();

            assert(is_array($actions));
            if (!is_array($actions)) continue;

            $isOneAction = (3 === count($actions)) && isset($actions['name']) && isset($actions['interval']) && isset($actions['action']);
            if ($isOneAction) $actions = [$actions,];

            foreach ($actions as $action) {
                $this->addActionToWalks(...$action);
            }
        }
    }

    /**
     * @param string $name
     * @param string $intervalName
     * @param callable $action
     * @throws \Exception
     */
    protected function addActionToWalks($name, $intervalName, callable $action)
    {
        $name = $this->checkAndCleanName($name, sprintf("Invalid walk action name!"), sprintf("Wrong walk action name: %s", $name));
        $intervalName = $this->checkAndCleanName($intervalName, sprintf("Invalid cron interval name!"), sprintf("Wrong cron interval name: %s", $intervalName));

        $pxName = $this->prefix . $name;
        $pxIntervalName = $this->prefix . $intervalName;
        if(false === wp_get_schedule($pxIntervalName)) throw new \Exception(sprintf("Unknown cron interval name %s (passed %s), add it first!", $pxIntervalName, $intervalName));

        add_action($pxName, $action);
        $this->walksActSignatures[] = [$pxName, $pxIntervalName];
    }

    protected function checkAndCleanName($name, $emptyMsg = 'Invalid name!', $regexpMsg = 'Wrong name!')
    {
        $name = (string)$name;
        if (empty($name)) throw new \InvalidArgumentException($emptyMsg);
        if (!preg_match(Core::NAME_CHECK_REGEXP, $name)) throw new \InvalidArgumentException($regexpMsg);
        return $name;
    }
}
