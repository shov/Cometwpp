<?php
/*
 * Plugin Name: Promo-Belmarket
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
use Cometwpp\Business\Business;
use Cometwpp\Context\CronWalker;
use Cometwpp\Context\AdminPanel;
use Cometwpp\Context\Client;

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
     *
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

    private $core;
    private $client;

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
        $this->core = Core::getInstance();

        /* Make Setup */
        $this->makePluginSetup();

        /* Up Business */
        Business::init();

        /* Context instances */
        CronWalker::init();
        AdminPanel::init();
        $this->client = Client::getInstance();
    }

    /**
     *
     */
    private function makePluginSetup()
    {
        $self = $this;

        if (function_exists('add_theme_support')) {
            add_theme_support('post-thumbnails');
        }

        register_activation_hook(__FILE__, function () use ($self) {
            $self->core->pluginActivation();
        });

        register_deactivation_hook(__FILE__, function () use ($self) {
            $self->core->pluginDeactivation();
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
        return self::$_inst->client;
    }
}

PluginName::init(); //Start The Plugin