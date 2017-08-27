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

use Cometwpp\CacheControlTrait;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

/**
 * Including css files As Is
 * @package Cometwpp
 * @subpackage Core
 * @category Class
 */
class CssProvider extends RegistrableRes
{
    use CacheControlTrait;

    /**
     * @param string $dirPath : path to target dir, who will be as root for "queries"
     */
    public function __construct($dirPath)
    {
        parent::__construct(['dir_path' => (string)$dirPath, 'ext' => 'css',]);
    }

    /**
     * Try to register style with WP functions
     * @param $name
     * @param array $dependence
     * @param int $context
     * @return string
     */
    public function registerStyle(string $name, array $dependence = [], int $context = self::REGULAR)
    {
        $regName = $regName = $this->getClearName($name);
        foreach ($dependence as $k => $depend) {
            $dependence[$k] = $this->getClearName($depend);
        }

        $version = false;
        if(true === static::$skipCache) {
            $version = static::getSeed();
        }

        $url = $this->getUrl($name);
        assert(false !== $url);
        if (false !== $url) {
            $this->registerResFor(function () use ($regName, $url, $dependence, $version) {
                wp_register_style($regName, $url, $dependence, $version);
                wp_enqueue_style($regName);
            }, $context);
        }
        return $regName;
    }

    public function registerAdminStyle($name, $dependence = [])
    {
        $this->registerStyle($name, $dependence, self::ADMIN);
    }

    /**
     * Return content of css file as is
     * @param $name
     * @param string $addTags
     * @return string
     */
    public function getContent($name, $addTags = 'style')
    {
        return parent::getContent($name, $addTags);
    }
}
