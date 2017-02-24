<?php
namespace Cometwpp;

use Cometwpp\Core\Core;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

/**
 * Prefix User Trait
 * @package Cometwpp
 * @category Trait
 */
trait PrefixUserTrait
{
    private $prefix = 'cometwpp_';

    private function setPrefix($prefix)
    {
        if (!is_string($prefix)) return false;
        if (!preg_match(Core::NAME_CHECK_REGEXP, $prefix)) return false;
        $this->prefix = $prefix;
    }
}