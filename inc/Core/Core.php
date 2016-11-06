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

use Cometwpp\SingletonTrait;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

/**
 * Core Class isn't god class, just initializing and provide access to all core-objects
 * @package Cometwpp
 * @subpackage Core
 * @category Class
 */
final class Core
{
    use SingletonTrait;

    /**
     * Init procedure
     * @param string $configPath
     * @return null
     */
    public static function init($configPath = '')
    {
        if (self::$_inst === null) {
            self::$_inst = new self($configPath);
        }
        return;
    }

    /**
     * @return Core|null
     */
    public static function getInstance()
    {
        self::init();
        return self::$_inst;
    }

    private $prefix;
    private $path;

    private $ajaxHandler;
    private $session;
    private $registry;

    private $templater;
    private $jsProvider;
    private $cssProvider;
    private $imgProvider;

    /**
     * Core constructor.
     * @param string $configPath
     */
    private function __construct($configPath = '')
    {
        $aConfig = $this->readConfig($configPath);

        $this->prefix = $this->setPrefix($aConfig['prefix']);
        $this->path = $aConfig['path'];

        $this->ajaxHandler = new AjaxHandler($aConfig['prefix']);
        $this->session = Session::getInstance($aConfig['prefix']);

        $this->registry = Registry::getInstance($aConfig['prefix'], $aConfig['wpoptions']);

        $this->templater = new Templater($aConfig['path']['tpl']);
        $this->jsProvider = new JsProvider($aConfig['path']['js']);
        $this->cssProvider = new CssProvider($aConfig['path']['css']);
        $this->imgProvider = new ImgProvider($aConfig['path']['img']);
    }

    /**
     * @param $prefix
     * @return null
     */
    private function setPrefix($prefix)
    {
        if (!is_string($prefix)) return;
        if (!preg_match('/^[a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*$/', $prefix)) throw new \InvalidArgumentException(sprintf("Wrong prefix: %s", $prefix));
        $this->prefix = $prefix;
        return;
    }


    /**
     * Try to read config php file, which should have $aConfig
     * @param string $configPath : is path to config php file
     * @throws \InvalidArgumentException
     * @throws \UnexpectedValueException
     * @return false|array
     */
    private function readConfig($configPath)
    {
        if (!is_readable($configPath)) throw new \InvalidArgumentException(sprintf("Wrong config file to read: %s", $configPath));

        $aConfig = null; //We hope take it in the config script
        require($configPath);
        if (!is_array($aConfig)) throw new \UnexpectedValueException(sprintf("Wrong config php file: %s \n variable $aConfig isnt array", $configPath));

        return $aConfig;
    }

    /**
     *  Acess to core-objects and core-properties
     *  call like getAjaxHandler();
     * @return NULL|mixed
     */
    public function __call($getCoreObjName, $aArgs = [])
    {
        if (!is_string($getCoreObjName)) return NULL;
        if (strlen($getCoreObjName) < 4) return NULL;
        if (strpos($getCoreObjName, 'get') != 0) return NULL;

        $prop = substr($getCoreObjName, 3);
        $prop = substr_replace($prop, strtolower(substr($prop, 0, 1)), 0, 1);

        if (property_exists($this, $prop)) return $this->$prop;
        return NULL;
    }

    /**
     *  Do something if plugin has been activated in this runing
     */
    public function pluginActivation()
    {
        //$this->registry-> skip cron status
        return;
    }

    /**
     *  Do something if plugin has been deactivated in this runing
     */
    public function pluginDeactivation()
    {
        flush_rewrite_rules(false);
    }
}