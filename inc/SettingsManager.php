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
 * Manage plugin settings
 * @package Cometwpp
 * @category Class
 */
class SettingsManager
{
    use SingletonTrait;

    protected $settings;
    const TRY_GET = false;
    const SET_ANY_WAY = true;
    const SETTING_NAME_SEPARATOR = ':';

    private function __construct()
    {
        $registry = Core::getInstance()->getRegistry();
        $registry->addOption('settings', []);
        $this->settings = $registry->settings;

        $settingsVal = $this->settings->get();
        assert(is_array($settingsVal));
        if (!is_array($settingsVal)) {
            $this->settings->update([]);
        }
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
        $this->checkNameIsCorrect($name);
        return $this->operationSetGet($name, $initVal, self::TRY_GET);
    }

    /**
     * Set setting by multi key (like "currency:usd")
     * @param $name
     * @param $val
     * @return mixed
     */
    public function setSetting($name, $val)
    {
        $this->checkNameIsCorrect($name);
        return $this->operationSetGet($name, $val, self::SET_ANY_WAY);
    }

    /**
     * Pick the name, init array parts of the key, read / write
     * @param $name
     * @param null $newVal
     * @param $record
     * @return mixed
     */
    protected function operationSetGet($name, $newVal = null, $record)
    {
        if (!is_bool($record)) throw new \InvalidArgumentException(sprintf("record flag should be bool"));
        if (!is_string($name) || empty($name)) throw new \InvalidArgumentException(sprintf("name should be not an empty string"));

        $optVal = $this->settings->get();
        $branch = &$optVal;

        $aPath = explode(self::SETTING_NAME_SEPARATOR, $name);
        $count = count($aPath);
        $last = 1;

        $resultVal = $newVal;

        while ($count) {
            $part = array_shift($aPath);

            if ($last === $count) {
                $shouldWrite = (!isset($branch[$part]) || (true === $record));
                if ($shouldWrite) {
                    $branch[$part] = $newVal;
                    $this->settings->update($optVal);
                } else {
                    $resultVal = $branch[$part];
                }
            }

            if (!(isset($branch[$part]) && is_array($branch[$part]))) {
                $branch[$part] = [];
            }

            $branch = &$branch[$part];
            $count--;
        }

        return $resultVal;
    }

    /**
     * Check is passed name correct
     * @throws \InvalidArgumentException
     * @param $name
     */
    protected function checkNameIsCorrect($name)
    {
        if (!preg_match('/^[a-zA-Z_\x7f-\xff][a-zA-Z0-9_'.self::SETTING_NAME_SEPARATOR.'\x7f-\xff]*$/', $name)) {
            throw new \InvalidArgumentException(sprintf("Cant use this name, its no correct, %s passed", $name));
        }
    }
}