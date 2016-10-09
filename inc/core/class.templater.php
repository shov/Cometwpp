<?php
namespace Cometwpp\Core;

if ( ! defined( 'ABSPATH' ) ) {
  exit; // Exit if accessed directly.
}

/**
 * Simple, php-contains Template processor
 * @package Cometwpp
 * @subpackage Core
 * @category Class
 */
class Templater extends ResGraber {
  /**
   * @param array $aConf = ['dir_path' => string, 'ext' => string|array ]
   */  
  public function __construct($dirPath) {
    parent::__construct([
      'dir_path' => (string)$dirPath,
      'ext' => [
        'php',
        'html',
        'htm',
      ],
    ]);
  }

  /**
   * Include template, it means output html and other content. 
   * We try to get one of exists with extensions in this order: php, html, htm
   * @param string $name of template like 'header', you can use packeges like 'feature:main', 
   * @param array $vars should be assotiative like ['varName' => 'value',]
   */  
  public function display($name, $vars) {
    $fullName = makeNamePath($name);
    if($fullName === false) return false;

    if(!is_array($vars)) {
      $vars = [$vars,];
    }
    extract($vars, EXTR_PREFIX_INVALID, 'tplvar');
    include($fullName);
  }

  /**
   * Render output for template and return in the variable. 
   * We try to get one of exists with extensions in this order: php, html, htm
   * @param string $name of template like 'header', you can use packeges like 'feature:main', 
   * @param array $vars should be assotiative like ['varName' => 'value',]
   * @return string|boolean : output|false
   */ 
  public function render($name, $vars) {
    $output = '';
    
    ob_start();
      $res = $this->display($name, $vars);
    $output = ob_get_clean();
    
    if($res === false) return false;
    return $output;
  }

  protected function import($name) {
    return parent::import($name);
  }
}