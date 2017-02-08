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
 * Manage plugin settings
 * @package Cometwpp
 * @category Class
 */
class SettingsManager extends AbstractRegistryManager
{
    use SingletonTrait;

    private function __construct()
    {
        $this->setRootProperty('settings');
    }

    /**
     * Try to get setting by multi key (like "currency:usd")
     * If value not exists, set it as $initVal
     * @param $name
     * @param array $initVal
     * @return mixed
     */
    public function getSetting($name, $initVal = [])
    {
        return $this->getTheProp($name, $initVal);
    }

    /**
     * Set setting by multi key (like "currency:usd")
     * about params @see AbstractRegistryManager::setTheProp()
     * @param $name
     * @param $val
     * @param int $mod
     * @return mixed
     */
    public function setSetting($name, $val, int $mod = self::CACHE)
    {
        return $this->setTheProp($name, $val, $mod);
    }

    /**
     * Burn the settings
     */
    public function burnCache()
    {
        parent::burnCache();
    }
}