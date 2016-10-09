<?php
/**
 * @package Cometwpp
 * @author Alexandr Shevchenko [comet.by] alexandr@comet.by
 * @version 3.0
 */
namespace Cometwpp;

if ( ! defined( 'ABSPATH' ) ) {
  exit; // Exit if accessed directly.
}

/**
 * Base WP Plugin part
 * @package Cometwpp
 * @category Class
 */
abstract class AbstractPluginControll {
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
final class PluginName extends AbstractPluginControll {
  private static $_inst;
  public static function init() {
    if(self::$_inst === null) {

      spl_autoload_register(function($name) {
        $aNameParts = explode('\\', $name);
        $niceName = strtolower(end($aNameParts));
        
        $prefix = 'class';
        if((strlen($niceName)-strlen('trait')) === strpos($niceName, 'trait')) $prefix = 'trait';
        
        $incPath = __DIR__.DIRECTORY_SEPARATOR.'inc'.DIRECTORY_SEPARATOR;
        $filename = $prefix.'.'.$niceName.'.php';

        $aPathes = [$incPath,];
        if(count($aNameParts > 2)) {
          $aPathes = [
            $incPath.strtolower($aNameParts[1]).DIRECTORY_SEPARATOR,
            $incPath,
          ];
        }
       
        foreach ($aPathes as $sPath) {
          if (is_readable($sPath.$filename)) {
            require $sPath.$filename;
            break;
          } 
        }
        
      }, true, true);

      self::$_inst = new self();
    }
  }

  private $core;
  private $business;
  private $adminPanel;
  private $client;

  private function __construct() {
    /* Up Core */
    $this->core = Core\Core::getInstance(__DIR__.DIRECTORY_SEPARATOR.'config.php');

    /* Make Setup */
    $this->makePluginSetup();

    /* Up Business */
    $this->business = Business\Business::getInstance($this->core);
    
    /* Context instances */
    Context\CronWalker::init($this->core, $this->business);
    $this->adminPanel = Context\AdminPanel::getInstance($this->core, $this->business);
    $this->client     = Context\Client::getInstance($this->core, $this->business);
  }

  private function makePluginSetup() {
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

  public static function getClientInstance() {
    self::init();
    return self::$_inst->client;
  }
}