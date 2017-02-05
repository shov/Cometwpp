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

        /* Make Setup */
        $this->makePluginSetup();

        /* Context instances */
        $contextManager = ContextManager::getInstance();
        $contextManager::registerContextController('business', new ContextController('Model'));
        $contextManager::registerContextController('admin', new AdminContextController('Admin'));
        $contextManager::registerContextController('client', new ContextController('Feature'));
        $contextManager::registerContextController('cron', new CronWalker('Walk'));
    }

    /**
     * Plugin setup in WP hooks
     */
    private function makePluginSetup()
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
    public static function getClientInstance()
    {
        self::init();
        return ContextManager::getContextController('client');
    }
}

PluginName::init(); //Start The Plugin