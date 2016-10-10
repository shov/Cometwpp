<?php
namespace Cometwpp\Core;

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
  use SingletonTrait, PrefixUserTrait;

  public static function getInstance($configPath) {
    if(self::$_inst === null) {
      self::$_inst = new self($configPath);
    }
    return self::$_inst;
  }

  private $prefix;
  private $pathConf;
  private $dataProviderFactory;

  private $ajaxHandler;
  private $session;
  
  private $registry;
  
  private $templater;
  private $jsProvider;
  private $cssProvider;
  private $imgProvider;
  
  private function __construct($configPath) {
    /* default.. may be wrong. Strong recomendated use config.php */
    $aDefaultConfig = [
      'prefix' => 'cometwpp',
      'pathes' => [
        'plugin' => __DIR__.'/../',
        'js'     => __DIR__.'/../js',
        'css'    => __DIR__.'/../css',
        'img'    => __DIR__.'/../img',
      ],
      'sqltables' => [
        'product' => 'price',
        'cart'    => 'cart',
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

    $this->prefix   = $this->setPrefix($aConfig['prefix']);
    $this->pathConf = $aConfig['pathes'];
    $this->dataProviderFactory = new DataProviderFactory($this->getPrefix());

    $this->ajaxHandler = new AjaxHandler($this->getPrefix());
    $this->session = Session::getInstance([
      'prefix' => $this->getPrefix(),
    ]);

    $this->registry = Registry::getInstance([
      'wpoptions' => $aConfig['wpoptions'],
      'data_provider_factory' => $this->getDataProviderFactory(),
    ]);

    $this->templater = new Templater([
      'dir_path' => $aConfig['pathes']['tpl'],
      'ext' => 'php',
    ]);
    
    $this->jsProvider = new JsProvider($aConfig['pathes']['js']);
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
