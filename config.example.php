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

$basePath = __DIR__.DIRECTORY_SEPARATOR;

/**
 *  @var array $aConfig - is an array who contains all one-place-control settings of the plugin
 */
$aConfig = [
    'name'  => 'Плагин',
    'prefix' => 'cometwpp',
    'path' => [
        'plugin' => $basePath,
        'inc'    => $basePath.'inc',
        'tpl'    => $basePath.'tpl',
        'js'     => $basePath.'js',
        'css'    => $basePath.'css',
        'img'    => $basePath.'img',
    ],
    'wpoptions' => [
        'settings',
    ],
];

$aIncEntity = [
    'Model',
    'Admin',
    'Feature',
    'Walk',
];

foreach ($aIncEntity as $incEntity) {
    $aConfig['path'][$incEntity] = $aConfig['path']['inc'].DIRECTORY_SEPARATOR.$incEntity;
}