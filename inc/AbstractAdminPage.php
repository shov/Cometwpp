<?php
namespace Cometwpp;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

/**
 * Can display some html
 * @package Cometwpp
 * @category Class
 */
abstract class AbstractAdminPage
{
    abstract public function render();
}