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
 * Simple, php-contains Template processor
 * @package Cometwpp
 * @subpackage Core
 * @category Class
 */
class Templater extends ResGraber
{
    /**
     * @param string $dirPath : path to target dir, who will be as root for "queries"
     */
    public function __construct($dirPath)
    {
        parent::__construct(['dir_path' => (string)$dirPath, 'ext' => ['php', 'html', 'htm',],]);
    }

    /**
     * Include template, it means output html and other content.
     * We try to get one of exists with extensions in this order: php, html, htm
     * @param string $name of template like 'header', you can use packeges like 'feature:main',
     * @param array $vars should be assotiative like ['varName' => 'value',]
     * @return bool
     */
    public function display($name, $vars = [])
    {
        $fullName = $this->makeNamePath($name);
        if ($fullName === false) return false;

        if (!is_array($vars)) {
            $vars = [$vars,];
        }
        extract($vars, EXTR_PREFIX_INVALID, 'tplvar');

        assert(is_readable($fullName));
        if(is_readable($fullName)) {
            include($fullName);
            return true;
        } else {
            return false;
        }
    }

    /**
     * Render output for template and return in the variable.
     * We try to get one of exists with extensions in this order: php, html, htm
     * @param string $name of template like 'header', you can use packeges like 'feature:main',
     * @param array $vars should be assotiative like ['varName' => 'value',]
     * @return string|boolean : output|false
     */
    public function render($name, $vars = [])
    {
        ob_start();
        $res = $this->display($name, $vars);
        $output = ob_get_clean();

        if ($res === false) return false;
        return $output;
    }

    /**
     * Here just alias for @see Templater::display()
     * @param string $name
     * @return null
     */
    public function import($name)
    {
        return $this->display($name, []);
    }
}