<?php

/*
 * This file is part of the Cometwpp package.
 *
 * (c) Alexandr Shevchenko [comet.by] alexandr@comet.by
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cometwpp\Core;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

/**
 * Provide resources who can be registered
 * @package Cometwpp
 * @subpackage Core
 * @category Class
 */
class RegistrableRes extends ResGraber
{
    const REGULAR = 0;
    const ADMIN = 1;
    const LOGIN = 2;

    /**
     * Call callback in pointed context
     * @param callable $registration
     * @param int $context
     */
    protected function registerResFor(callable $registration, int $context = self::REGULAR)
    {
        switch (true) {
            case (self::REGULAR === $context):
                add_action('wp_enqueue_scripts', function () use ($registration) {
                    $registration();
                });
                break;
            case (self::ADMIN === $context):
                add_action('admin_enqueue_scripts', function () use ($registration) {
                    $registration();
                });
                break;
            case (self::LOGIN === $context):
                add_action('login_enqueue_scripts', function () use ($registration) {
                    $registration();
                });
                break;
        }
    }
}
