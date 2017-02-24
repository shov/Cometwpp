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
 * Provide registered ContextControllers
 * @package Cometwpp
 * @category Class
 */
class ContextManager
{
    use SingletonTrait;

    protected static $pull = [];

    public static function registerContextController($name, AbstractContextController $controller)
    {
        self::getInstance();
        if (empty($name) || !is_string($name)) throw new \InvalidArgumentException(sprintf("Bad key name for context controller, %s passed", $name));

        if (key_exists($name, self::$pull)) throw new \Exception(sprintf("Some Context controller already been registered with name: '%s'", $name));

        if (!in_array($controller, self::$pull)) self::$pull[$name] = $controller;
    }

    public static function unRegisterContextController(AbstractContextController $controller)
    {
        self::getInstance();
        foreach (self::$pull as $i => $hasContext) {
            if ($hasContext === $controller) unset(self::$pull[$i]);
        }
    }

    public static function getContextController($name)
    {
        self::getInstance();

        if (empty($name) || !is_string($name)) throw new \InvalidArgumentException(sprintf("Bad key name for context controller, %s passed", $name));
        if (!isset(self::$pull[$name])) throw new \InvalidArgumentException(sprintf("Have no context controller for the name, %s passed", $name));

        return self::$pull[$name];
    }
}
