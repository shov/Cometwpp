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
 * Including css files As Is
 * @package Cometwpp
 * @subpackage Core
 * @category Class
 */
class CssProvider extends RegistrableRes
{
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
    public function registerStyle($name, $dependence = [], $context = self::REGULAR)
    {
        $regName = $regName = $this->getClearName($name);
        $url = $this->getUrl($name);
        assert(false !== $url);
        if (false !== $url) {
            $this->registerResFor(function () use ($regName, $url, $dependence) {
                wp_register_style($regName, $url, $dependence);
                wp_enqueue_style($regName);
            }, $context);
        }
        return $regName;
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
