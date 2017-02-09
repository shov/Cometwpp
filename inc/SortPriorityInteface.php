<?php
namespace Cometwpp;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

/**
 * Method @see SortPriorityInterface::sortPriority() returns integer for sort priority known
 * @package Cometwpp
 * @category Interface
 */
interface SortPriorityInterface
{
    public function sortPriority(): int;
}