<?php

/*
 * This file is part of the Cometwpp package.
 *
 * (c) Alexandr Shevchenko [comet.by] alexandr@comet.by
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cometwpp\Context;

use Cometwpp\SingletonTrait;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

/**
 * Provide plugin's features to client code in the theme
 * @package Cometwpp
 * @subpackage Context
 * @category Class
 */
class Client
{
    use SingletonTrait;
}
