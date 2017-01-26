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

    const NAME_CHECK_REGEXP = '/^[a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*$/';

    private $prefix;
    private $path;
    private $name;

    private $ajaxHandler;
    private $session;
    private $registry;

    private $dbo;

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
        $this->pathGenerate($aConfig['path']);
        $this->path = $aConfig['path'];

        $this->name = $aConfig['name'];

        $this->ajaxHandler = new AjaxHandler($aConfig['prefix']);
        $this->session = Session::getInstance($aConfig['prefix']);

        $this->registry = Registry::getInstance($aConfig['prefix'], $aConfig['wpoptions']);

        $this->dbo = Dbo::getDb();

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
        if (!preg_match(self::NAME_CHECK_REGEXP, $prefix)) throw new \InvalidArgumentException(sprintf("Wrong prefix: %s", $prefix));
        $this->prefix = $prefix;
        return;
    }

    private function pathGenerate($aPath = [])
    {
        foreach ($aPath as $path) {
            $isDir = is_dir($path);
            $exists = file_exists($path);
            if($exists && !$isDir) throw new \Exception(sprintf("Can't create required directory, %s, file exists", $path));
            if(!$isDir) {
                if(!mkdir($path, 0755, true)) throw new \Exception(sprintf("Can't create required directory, %s, php error", $path));
            }
        }
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
     * @param null|string $spec
     * @return mixed
     */
    public function getPath($spec = null)
    {
        if (!is_null($spec)) {
            $spec = (string)$spec;
            if (isset($this->path[$spec])) {
                return $this->path[$spec];
            } else {
                return null;
            }
        }
        return $this->path;
    }

    /**
     * @return AjaxHandler
     */
    public function getAjaxHandler()
    {
        return $this->ajaxHandler;
    }

    /**
     * @return Session
     */
    public function getSession()
    {
        return $this->session;
    }

    /**
     * @return Registry
     */
    public function getRegistry()
    {
        return $this->registry;
    }

    /**
     * @return Templater
     */
    public function getTemplater()
    {
        return $this->templater;
    }

    /**
     * @return JsProvider
     */
    public function getJsProvider()
    {
        return $this->jsProvider;
    }

    /**
     * @return CssProvider
     */
    public function getCssProvider()
    {
        return $this->cssProvider;
    }

    /**
     * @return ImgProvider
     */
    public function getImgProvider()
    {
        return $this->imgProvider;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return null
     */
    public function getPrefix()
    {
        return $this->prefix;
    }

    /**
     * @return object
     */
    public function getDbo() {
        return $this->dbo;
    }
}