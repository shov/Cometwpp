<?php
/**
 * Config the plugin
 * @package Cometwpp
 * @author Alexandr Shevchenko [comet.by] alexandr@comet.by
 */

namespace Cometwpp;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

if(!defined('DS')) {
    define('DS', DIRECTORY_SEPARATOR);
}

$basePath = __DIR__ . DS;

/**
 * @var array $aConfig - is an array who contains all one-place-control settings of the plugin
 */
$aConfig = [
/**/'name' => 'cometwpp_plugin_fw',
/**/'prefix' => 'cometwpp_plugin_fw_prefix',
    'path' => [
        'plugin' => $basePath,
        'inc' => $basePath . 'inc',
        'tpl' => $basePath . 'tpl',
        'js' => $basePath . 'js',
        'css' => $basePath . 'css',
        'img' => $basePath . 'img',
    ],
    'wpoptions' => [
        'settings',
    ],
    'default_timezone' => 'Europe/Minsk',
];

$aIncEntity = [
    ContextManager::BUSINESS => 'Model',
    ContextManager::ADMIN => 'Admin',
    ContextManager::CLIENT => 'Feature',
    ContextManager::CRON => 'Walk',
];

foreach ($aIncEntity as $contextKey => $incEntity) {
    $aConfig['path'][$contextKey] = $aConfig['path']['inc'] . DS . $incEntity;
}

$composerRelPath = 'Mod' . DS . 'Composer' . DS . 'vendor' . DS . 'autoload.php';
$aConfig['path']['composer'] = $aConfig['path']['inc'] . DS . $composerRelPath;