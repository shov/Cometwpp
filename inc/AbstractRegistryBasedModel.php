<?php
namespace Cometwpp;


if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

/**
 * Base class for the models who save data Registry based way
 * @package Cometwpp
 * @category Class
 */
abstract class AbstractRegistryBasedModel
{

    /**
     * AbstractRegistryBasedModel constructor.
     * @param ContextController $context
     */
    public function __construct(ContextController $context)
    {
    }
}