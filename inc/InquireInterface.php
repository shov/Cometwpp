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
    /**
     * Add callback to queue with priority
     * @param callable $call
     * @param $priority
     * @return mixed
     */
    public function addInquire(callable $call, $priority);
}