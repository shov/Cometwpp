<?php
namespace Cometwpp\Core;
use Cometwpp as R;

if ( ! defined( 'ABSPATH' ) ) {
  exit; // Exit if accessed directly.
}

/**
 * Core Class isn't god class, just initializing and provide access to all core-objects
 * @package Cometwpp
 * @subpackage Core
 * @category Class
 */
final class Core {
  use R\SingletonTrait, R\PrefixUserTrait;

  public static function getInstance($configPath) {
    if(self::$_inst === null) {
      self::$_inst = new self($configPath);
    }
    return self::$_inst;
  }

  private $prefix;
  private $pathes;

  private $ajaxHandler;
  private $session;
  private $registry;
  
  private $templater;
  private $jsProvider;
  private $cssProvider;
  private $imgProvider;
  
  private function __construct($configPath = false) {
    /* default.. may be wrong. Strong recomendated use config.php */
    $aDefaultConfig = [
      'prefix' => 'cometwpp',
      'pathes' => [
        'plugin' => __DIR__.'/../',
        'js'     => __DIR__.'/../js',
        'css'    => __DIR__.'/../css',
        'img'    => __DIR__.'/../img',
      ],
      'wpoptions' => [
        'status',
        'settings',
        'cart_settings',
        'hard_filters',
        'livesearch_settings',
      ],
    ];

    $aConfig = $this->readConfig($configPath);
    if(!is_array($aConfig)) $aConfig = $aDefaultConfig;

    $this->prefix = $this->setPrefix($aConfig['prefix']);
    $this->pathes = $aConfig['pathes'];

    $this->ajaxHandler = new AjaxHandler($aConfig['prefix']);
    $this->session  = Session::getInstance($aConfig['prefix']);

    $this->registry = Registry::getInstance($aConfig['prefix'], $aConfig['wpoptions']);

    $this->templater   = new Templater($aConfig['pathes']['tpl']);
    $this->jsProvider  = new JsProvider($aConfig['pathes']['js']);
    $this->cssProvider = new CssProvider($aConfig['pathes']['css']);
    $this->imgProvider = new ImgProvider($aConfig['pathes']['img']);
  }


  /**
   * Try to read config php file, which should have $aConfig 
   * @param string $configPath : is path to config php file
   * @return false|array
   */  
  private function readConfig($configPath) {
    if(!is_readable($configPath)) return false;
    
    require($configPath);
    if(!is_array($aConfig)) return false;

    return $aConfig;
  }

  /**
   *  Acess to core-objects and core-properties
   *  call like getAjaxHandler();
   *  @return mixed
   */  
  public function __call($getcoreobjname, $aArgs = []) {
    if(!is_string($getcoreobjname)) return NULL;
    if(strlen($getcoreobjname) < 4) return NULL;
    if(strpos($getcoreobjname, 'get') != 0) return NULL;

    $prop = substr($getcoreobjname, 3);
    $prop = substr_replace($prop, strtolower(substr($prop, 0, 1)), 0, 1);

    if(property_exists($this, $prop)) return $this->$prop;
    return NULL;
  }

  /**
   *  Do something if plugin has been activated in this runing
   */
  public function pluginActivation() {
    //$this->registry-> skip cron status
  }

  /**
   *  Do something if plugin has been deactivated in this runing
   */
  public function pluginDeactivation() {
    flush_rewrite_rules(false);
  }
}
