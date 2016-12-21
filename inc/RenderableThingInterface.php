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

interface RenderableThingInterface
{
    public function render();
}