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
use Cometwpp\Core\Option;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

/**
 * The base for the registry-property manager
 * @package Cometwpp
 * @category Class
 */
abstract class AbstractRegistryManager
{
    const GET = 0;
    const BURN = 1;
    const CACHE = 2;

    const NAME_SEPARATOR = ':';

    /**
     * @var Option $property
     */
    private $property;

    private function setRootOption(Option $property)
    {
        $this->property = $property;
        $val = $this->property->get();
        assert(is_array($val));
        if (!is_array($val)) {
            $this->property->update([]);
        }
    }

    /**
     * Set root property which will be the base of the manager
     * Ορίζει θεμελιώδης ιδιότητα, η οποία βρίσκεται στη βάση του διαχειριστή
     * @param $propName
     */
    protected function setRootProperty($propName)
    {
        $this->checkNameIsCorrect($propName);
        $registry = Core::getInstance()->getRegistry();
        $registry->addOption($propName, []);
        $this->setRootOption($registry->$propName);
    }

    /**
     * Try to get sub property by multi key (like "currency:usd")
     * If value not exists, set it as $initVal
     * @param $name
     * @param array $initVal
     * @return mixed
     */
    protected function getTheProp($name, $initVal = [])
    {
        $this->checkNameIsCorrect($name);
        return $this->operationSetGet($name, $initVal, self::GET);
    }

    /**
     * Set sub property by multi key (like "currency:usd")
     * @param string|null $name
     * @param mixed|null $val
     * @param int $mod fine-tuning the cache with CACHE or BURN value
     * @return mixed
     */
    protected function setTheProp(string $name = null, $val = null, int $mod = self::CACHE)
    {
        $this->checkNameIsCorrect($name);
        $setMod = self::CACHE;
        if(self::BURN === $mod) $setMod = $mod;

        return $this->operationSetGet($name, $val, $setMod);
    }

    /**
     * Pick the name, init array parts of the key, read / write / cache
     * @param string $name
     * @param null $newVal
     * @param $mod
     * @return mixed
     */
    private function operationSetGet(string $name, $newVal = null, int $mod)
    {
        $optVal = $this->property->get();
        $branch = &$optVal;

        $aPath = explode(self::NAME_SEPARATOR, $name);
        $count = count($aPath);
        $last = 1;

        $resultVal = $newVal;

        while ($count) {
            $part = array_shift($aPath);

            if ($last === $count) {
                if (!isset($branch[$part])) {
                    $branch[$part] = $newVal;

                    switch (true) {
                        case (self::BURN === $mod):
                            $this->property->update($optVal, Option::FORCE);
                            break;

                        case (self::CACHE === $mod):
                            $this->property->update($optVal);
                            break;
                    }
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
     * Burn the property
     */
    protected function burnCache()
    {
        $currentVal = $this->property->get();
        $this->property->update($currentVal, Option::FORCE);
    }

    /**
     * Check is passed name correct
     * @throws \InvalidArgumentException
     * @param $name
     */
    protected function checkNameIsCorrect($name)
    {
        if (0 === mb_strlen($name)) throw new \InvalidArgumentException(sprintf("Cant use this name, its no correct, %s passed", $name));
        if (!preg_match('/^[a-zA-Z_\x7f-\xff][a-zA-Z0-9_' . self::NAME_SEPARATOR . '\x7f-\xff]*$/', $name)) {
            throw new \InvalidArgumentException(sprintf("Cant use this name, its no correct, %s passed", $name));
        }
    }
}