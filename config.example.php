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
 *  @var $aConfig is an array who contains all one-place-control settings of the plugin
 */
$aConfig = [
  'prefix' => 'cometwpp',
  'pathes' => [
    'plugin' => __DIR__.'/',
    'js'     => __DIR__.'/js',
    'css'    => __DIR__.'/css',
    'img'    => __DIR__.'/img',
  ],
  'wpoptions' => [
    'status',
    'settings',
    'cart_settings',
    'hard_filters',
    'livesearch_settings',
  ],
];