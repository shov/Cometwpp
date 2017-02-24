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

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

/**
 * Including js files As Is
 * @package Cometwpp
 * @subpackage Core
 * @category Class
 */
class JsProvider extends RegistrableRes
{
    /**
     * @param string $dirPath : path to target dir, who will be as root for "queries"
     */
    public function __construct($dirPath)
    {
        parent::__construct(['dir_path' => (string)$dirPath, 'ext' => 'js',]);
    }

    /**
     * Try to register script with WP functions
     * @param string $name
     * @param array $dependence
     * @param bool $bInFooter
     * @param int $context
     * @return string
     */
    public function registerScript(string $name, array $dependence = [], bool $bInFooter = true, int $context = self::REGULAR)
    {
        $regName = $this->getClearName($name);
        foreach ($dependence as $k => $depend) {
            $dependence[$k] = $this->getClearName($depend);
        }

        $version = false; // don't use it yet

        $url = $this->getUrl($name);
        assert(false !== $url);
        if (false !== $url) {
            $this->registerResFor(function () use ($regName, $url, $dependence, $version, $bInFooter) {
                wp_register_script($regName, $url, $dependence, $version, $bInFooter);
                wp_enqueue_script($regName);
            }, $context);
        }
        return $regName;
    }

    public function registerAdminScript($name, $dependence = [], $bInFooter = true)
    {
        $this->registerScript($name, $dependence, $bInFooter, self::ADMIN);
    }

    /**
     * Add js object to the script
     * @param string $name
     * @param $varName
     * @param array $varValue
     * @param int $context
     */
    public function addVarToScript(string $name, string $varName, $varValue = [], int $context = self::REGULAR)
    {
        $regName = $this->getClearName($name);
        $this->registerResFor(function () use ($regName, $varName, $varValue) {
            wp_localize_script($regName, $varName, $varValue);
        }, $context);
    }

    public function addVarToAdminScript($name, $varName, $varValue = [])
    {
        $this->addVarToScript($name, $varName, $varValue, self::ADMIN);
    }

    /**
     * Return content of js file as is
     * @param $name
     * @param string $addTags
     * @return string
     */
    public function getContent($name, $addTags = 'script')
    {
        return parent::getContent($name, $addTags);
    }
}
