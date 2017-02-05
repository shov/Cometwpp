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
    const TRY_GET = 0;
    const SET_AND_BURN = 1;
    const SET_AND_CACHE = 2;

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
        return $this->operationSetGet($name, $initVal, self::TRY_GET);
    }

    /**
     * Set sub property by multi key (like "currency:usd") or burn, if call without arguments
     * @param string|null $name
     * @param mixed|null $val
     * @return mixed
     */
    protected function setTheProp(string $name = null, $val = null)
    {
        $burnCache = (is_null($name) && is_null($val));
        if($burnCache) {
            return $this->property->burn();
        } else {
            $this->checkNameIsCorrect($name);
            return $this->operationSetGet($name, $val, self::SET_AND_BURN);
        }
    }

    /**
     * Set sub property by multi key (like "currency:usd"), but just in cache
     * @param $name
     * @param $val
     * @return mixed
     */
    protected function cacheTheProp($name, $val)
    {
        $this->checkNameIsCorrect($name);
        return $this->operationSetGet($name, $val, self::SET_AND_CACHE);
    }

    /**
     * Pick the name, init array parts of the key, read / write / cache
     * @param string $name
     * @param null $newVal
     * @param $record
     * @return mixed
     */
    private function operationSetGet(string $name, $newVal = null, $record)
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
                        case (self::SET_AND_BURN === $record):
                            $this->property->update($optVal);
                            break;

                        case (self::SET_AND_CACHE === $record):
                            $this->property->cache($optVal);
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