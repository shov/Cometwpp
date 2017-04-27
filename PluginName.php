<?php
/*
 * Plugin Name: PluginName
 * Version: 2.0
 * Author: Alexandr Shevchenko [comet.by] alexandr@comet.by
 * Author URI: http://comet.by
 *
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
 * Base WP Plugin
 * @package Cometwpp
 * @category Interface
 */
interface PluginControlInterface
{
    public static function init();
    public static function getClientInstance();
}

/**
 * Wp Plugin Init class
 * @package Cometwpp
 * @category Class
 */
final class PluginName implements PluginControlInterface
{
    private static $_inst;

    /**
     * Init: start the plugin
     */
    public static function init()
    {
        if (self::$_inst === null) {

            self::autoloadRegister();
            self::$_inst = new self();
        }
    }

    private function __wakeup()
    {
    }

    private function __clone()
    {
    }

    /**
     * PluginName constructor.
     */
    private function __construct()
    {
        /* Up Core */
        Core::init(__DIR__ . DIRECTORY_SEPARATOR . 'config.php');
        $core = Core::getInstance();

        $this->setupDefaultTimezone();

        /* Try to include Composer autoload */
        $this->composerAutoload();

        /* Make Setup */
        $this->makePluginSetup();

        /* Context instances */
        $contextManager = ContextManager::getInstance();

        $contextManager
            ::registerContextController(ContextManager::BUSINESS,
                new ContextController(ContextManager::BUSINESS));

        $contextManager
            ::registerContextController(ContextManager::ADMIN,
                new AdminContextController(ContextManager::ADMIN));

        $contextManager
            ::registerContextController(ContextManager::CLIENT,
                new ContextController(ContextManager::CLIENT));

        $contextManager
            ::registerContextController(ContextManager::CRON,
                new CronWalker(ContextManager::CRON));
    }

    /**
     * Plugin setup in WP hooks
     */
    private function makePluginSetup(): void
    {
        $self = $this;

        if (function_exists('add_theme_support')) {
            add_theme_support('post-thumbnails');
        }

        register_activation_hook(__FILE__, function () use ($self) {

        });

        register_deactivation_hook(__FILE__, function () use ($self) {
            flush_rewrite_rules(false);
        });

        /*register_uninstall_hook(__FILE__, function(){

        });*/
    }

    /**
     * @return mixed
     */
    public static function getClientInstance(): ContextController
    {
        self::init();
        return ContextManager::getContextController('client');
    }

    private static function autoloadRegister(): void
    {
        spl_autoload_register(function ($name) {
            $nameParts = explode('\\', $name);
            $nameParts = array_slice($nameParts, 1);
            $baseIncPath = __DIR__ . DIRECTORY_SEPARATOR . 'inc';
            $fullPath = $baseIncPath;

            foreach ($nameParts as $key => $part) {
                $fullPath .= DIRECTORY_SEPARATOR . $part;
            }
            $fullPath .= '.php';

            if (is_readable($fullPath)) require $fullPath;
        }, true, true);
    }

    private function composerAutoload(): void
    {
        $composerAutoloadPath = Core::getInstance()->getPath('composer');
        if(!is_null($composerAutoloadPath) && is_file($composerAutoloadPath)) {
            include_once($composerAutoloadPath);
        }
    }

    private function setupDefaultTimezone()
    {
        $dtz = Core::getInstance()->getDefaultTimezone();
        if(!is_null($dtz)) @date_default_timezone_set($dtz);
    }
}

PluginName::init(); //Start The Plugin