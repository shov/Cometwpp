<?php
/**
 * Config the plugin
 * @package Cometwpp
 * @author Alexandr Shevchenko [comet.by] alexandr@comet.by
 */
namespace Cometwpp;

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

/**
 *  @var $aConfig an array who contains all one-place-control settings of the plugin
 */
$aConfig = [
    'name'  => 'Плагин',
    'prefix' => 'cometwpp',
    'path' => [
        'plugin' => __DIR__.'/',
        'inc'    => __DIR__.'/inc',
        'tpl'    => __DIR__.'/tpl',
        'js'     => __DIR__.'/js',
        'css'    => __DIR__.'/css',
        'img'    => __DIR__.'/img',
    ],
    'wpoptions' => [
        'settings',
    ],
];