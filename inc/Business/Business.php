<?php

/*
 * This file is part of the Cometwpp package.
 *
 * (c) Alexandr Shevchenko [comet.by] alexandr@comet.by
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cometwpp\Business;

use Cometwpp\Core\Core;
use Cometwpp\SingletonTrait;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

/**
 * Find, load, initialize, provide business models objects
 * Warning! Core should be initialized
 * @package Cometwpp
 * @subpackage Business
 * @category Class
 */
final class Business
{
    use SingletonTrait;

    /**
     * Init procedure, create the instance
     * @return null;
     */
    public static function init()
    {
        if (self::$_inst === null) {
            self::$_inst = new self();
        }
        return;
    }

    /**
     * @return Business
     */
    public static function getInstance()
    {
        self::init();
        return self::$_inst;
    }

    private $core;
    private $aModels;

    /**
     * Business constructor.
     */
    private function __construct()
    {
        $this->core = Core::getInstance();
        assert(null != $this->core, "Core should already be initialized");

        $this->aModels = [];
        $this->modelsAutoload();
    }

    /**
     * Look for .php files in current directory,
     * and check every one of them contain class who has the same name what the file has.
     * Then try to create them correctly in current namespace.
     * Depend on spl_autoload_register @see PluginControllInterface::init()
     * Fills $this->aModels array model-objects
     */
    private function modelsAutoload()
    {
        $businessDir = new \DirectoryIterator(__DIR__);
        $aModelClasses = [];

        foreach ($businessDir as $fileInfo) {
            if ($fileInfo->isDot() || !$fileInfo->isFile() || !$fileInfo->isReadable()) continue;
            if ('php' === $fileInfo->getExtension()) {
                $expectedClassName = $fileInfo->getBasename('.php');
                $file = new \SplFileObject($fileInfo->getPathname());
                $content = $file->fread($file->getSize());

                $aTokens = token_get_all($content);
                $count = count($aTokens);
                for ($i = 2; $i < $count; $i++) {
                    if ((T_CLASS === $aTokens[$i - 2][0]) && (T_WHITESPACE === $aTokens[$i - 1][0]) && (T_STRING === $aTokens[$i][0])) {
                        $foundClassName = $aTokens[$i][1];
                        if ($expectedClassName === $foundClassName) $aModelClasses [] = $foundClassName;
                    }
                }
                $file = null;
            }
        }
        $businessDir = null;

        foreach ($aModelClasses as $className) {
            $reflect = new \ReflectionClass('\\' . __NAMESPACE__ . '\\' . $className);

            $reflect = null;
        }
    }

    /**
     * Access to business model objects
     * call like getCart();
     * @param string $getModelObjName
     * @return null|mixed
     */
    public function __call($getModelObjName)
    {
        if (!is_string($getModelObjName)) return null;
        if (strlen($getModelObjName) < 4) return null;
        if (strpos($getModelObjName, 'get') != 0) return null;

        $prop = substr($getModelObjName, 3);

        if (!empty($this->aModels[$prop])) return $this->aModels[$prop];
        return NULL;
    }
}
