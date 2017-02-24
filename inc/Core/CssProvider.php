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
class CssProvider extends ResGraber
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
     */
    public function registerStyle($name, $dependence = [])
    {
        $regName = $regName = $this->getClearName($name);
        foreach ($dependence as $k => $depend) {
            $dependence[$k] = $this->getClearName($depend);
        }
        $path = $this->getPath($name);
        assert(false !== $path);
        if (false !== $path) {
            wp_register_style($regName, $path, $dependence);
            wp_enqueue_style($regName);
        }
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
