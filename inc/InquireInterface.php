<?php
namespace Cometwpp;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

/**
 * Somebody can place own functions, it should call all of them
 * @package Cometwpp
 * @category Interface
 */
interface InquireInterface
{
    public function addInquire(callable $call, $priority);
}