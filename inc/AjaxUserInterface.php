<?php
namespace Cometwpp;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

/**
 * Can display some html
 * @package Cometwpp
 * @category Interface
 */
interface AjaxUserInterface
{
    /**
     * Used @see SheafAjaxSetter
     * @return array
     */
    public function getSheafStore();
}