<?php
/*
 * Plugin Name: _
 * Version: 2.0
 * Author: Alexandr Shevchenko [comet.by] alexandr@comet.by
 * Author URI: http://comet.by
 *
 * This file is part of the Cometwpp package.
 *
 * (c) Alexandr Shevchenko [comet.by] alexandr@comet.by
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cometwpp;

use Cometwpp\Core\Core;
use Cometwpp\Business\Business;
use Cometwpp\Context\CronWalker;
use Cometwpp\Context\AdminPanel;
use Cometwpp\Context\Client;

if ( ! defined( 'ABSPATH' ) ) {
  exit; // Exit if accessed directly.
}

/**
 * Base WP Plugin part
 * @package Cometwpp
 * @category Class
 */
abstract class AbstractPluginControll 
{
  protected static $_inst;
  protected $core;

  abstract public static function init();
  abstract public static function getClientInstance();
  abstract protected function __construct();
  abstract protected function makePluginSetup();

  final private function __wakeup() {}
  final private function __clone() {}  
}

/**
 * Wp Plugin Init class
 * @package Cometwpp
 * @category Class
 */
final class PluginName extends AbstractPluginControll 
{
  private static $_inst;
  public static function init() 
  {
    if(self::$_inst === null) {
      spl_autoload_register(function($name) {
        $nameParts = explode('\\', $name);
        $nameParts = array_slice($nameParts, 1);
        $baseIncPath = __DIR__.DIRECTORY_SEPARATOR.'inc';
        $fullPath = $baseIncPath;

        foreach ($nameParts as $key => $part) {
          $fullPath .= DIRECTORY_SEPARATOR.$part;
        }
        $fullPath .= '.php';

        if (is_readable($fullPath)) require $fullPath;
      }, true, true);

      self::$_inst = new self();
    }
  }

  private $core;
  private $business;
  private $adminPanel;
  private $client;

  private function __construct() 
  {
    /* Up Core */
    $this->core = Core::getInstance(__DIR__.DIRECTORY_SEPARATOR.'config.php');

    /* Make Setup */
    $this->makePluginSetup();

    /* Up Business */
    $this->business = Business::getInstance($this->core);
    
    /* Context instances */
    CronWalker::init($this->core, $this->business);
    $this->adminPanel = AdminPanel::getInstance($this->core, $this->business);
    $this->client     = \Client::getInstance($this->core, $this->business);
  }

  private function makePluginSetup() 
  {
    $self = $this;

    if (function_exists('add_theme_support')) { 
      add_theme_support('post-thumbnails');
    }

    register_activation_hook(__FILE__, function() use($self) {
      $self->core->pluginActivation();
    });

    register_deactivation_hook(__FILE__, function() use($self) {
      $self->core->pluginDeactivation();
    });
    
    /*register_uninstall_hook(__FILE__, function(){

    });*/
  }

  public static function getClientInstance() 
  {
    self::init();
    return self::$_inst->client;
  }
}