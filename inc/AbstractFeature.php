<?php
namespace Cometwpp;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

/**
 * Base class for the common type feature
 * @package Cometwpp
 * @category Class
 */
abstract class AbstractFeature
{
    protected $context;

    /**
     * AbstractFeature constructor.
     * @param ContextController $context
     */
    public function __construct(ContextController $context)
    {
        $this->context = $context;
    }
}