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
class JsProvider extends ResGraber
{
    /**
     * @param string $dirPath : path to target dir, who will be as root for "queries"
     */
    public function __construct($dirPath)
    {
        parent::__construct(['dir_path' => (string)$dirPath, 'ext' => 'js',]);
    }
}
